<?php
    session_start();

    if( ! isset($_SESSION['user_name'])){
        header('Location: index');
        exit();
    };

    $_title = 'Home';

    require_once(__DIR__.'/private/tsv-parser.php');
    require_once(__DIR__.'/components/header.php');
    require_once(__DIR__.'/components/nav.php');
?>

<div class="container">
    <h1>
        Welcome, <?php echo $_SESSION['user_name']; ?>
    </h1>
    
    <button onclick="openUploadModal()">Add new item</button>

    <div class="upload-modal hidden">
        <div class="upload-modal-content">
            <div class="close-icon close-upload-modal">X</div>
            <form onsubmit="return false">
                <label for="name">Item name</label>
                <input type="text" id="name" name="item_name">

                <label for="desc">Item description</label>
                <input type="text" id="desc" name="item_description">

                <label for="price">Item price</label>
                <input type="number" id="price" name="item_price">

                <label for="image">Item image name</label>
                <input type="text" id="image" name="item_image">
                <div class="error-message"></div>
                <button onclick="uploadItem()">Upload item</button>
            </form>
        </div>
    </div>

    <div class="item-modal hidden">
        <div class="item-modal-content">
            <div class="close-icon close-modal">X</div>
            <form id="update-form" onsubmit="return false">
                <label for="name">Item name</label>
                <input type="text" id="update_name" name="item_name">

                <label for="desc">Item description</label>
                <input type="text" id="update_desc" name="item_description">

                <label for="price">Item price</label>
                <input type="number" id="update_price" name="item_price">

                <label for="image">Item image name</label>
                <input type="text" id="update_image" name="item_image">
                <div class="message"></div>
                <button onclick="updateItem()">Update item</button>
            </form>
        </div>
    </div>
    <h2 class="subheader">Your items</h2>
    <div id="own-items"></div>
    <h2 class="subheader">Other items</h2>
    <div id="items">
        <?php

            $data = json_decode(file_get_contents("shop.txt"));

            foreach($data as $item){
                echo "<div class='item' data-id='{$item->id}'>
                        <div class='item-image'>
                            <img src='https://coderspage.com/2021-F-Web-Dev-Images/{$item->image}' />
                        </div>
                        <div class='item-text'>
                            <div class='price'>$ {$item->price_en}</div>
                            <div>{$item->title_en}</div>
                            <div class='desc'>{$item->desc_en}</div>
                        </div>
                    </div>";
            }
            ?>
    </div>
</div>

<script>
    getItems()
        async function getItems(){
            document.querySelector("#own-items").innerHTML = "";

            const conn = await fetch("apis/api-items", {
                method: "POST"
            })
            const res = await conn.json()
            if(conn.ok){
                res.forEach((item) =>(
                    document.querySelector("#own-items").insertAdjacentHTML("afterbegin", 
                `<div class="item" data-id="${item.item_id}">
                    <div class='item-image'>
                        <img src='https://coderspage.com/2021-F-Web-Dev-Images/${item.item_image}' />
                    </div>
                    <div class='item-text'>
                        <div class="price">$ ${item.item_price}</div>
                        <div class="name">${item.item_name}</div>
                        <div class="desc">${item.item_description}</div>
                        <div class='trash' onclick="deleteItem()">🗑️</div>
                        <div class='pen' onclick="editItem()">🖊️</div>
                    </div>
                </div>`)
                ))
            }
        }

        function openUploadModal(){
            const modal = document.querySelector(".upload-modal")
            const closeModal = document.querySelector(".close-upload-modal")

            modal.classList.remove("hidden")

            closeModal.addEventListener("click", function(){
                modal.classList.add("hidden")
            })
        }

        async function editItem(){
            const item = event.target.parentNode.parentNode
            const modal = document.querySelector(".item-modal")
            const modalContent = document.querySelector(".item-modal-content")
            const closeModal = document.querySelector(".close-modal")
            const itemId = item.dataset.id
            let formData = new FormData();

            formData.append('item_id', itemId);
            modal.classList.remove("hidden")          
            closeModal.addEventListener("click", function(){
                modal.classList.add("hidden")
            })
            const conn = await fetch("apis/api-get-single-item", {
                method: "POST",
                body: formData
            })
            const res = await conn.json()
            document.querySelector("#update-form").dataset.id = itemId
            document.querySelector("#update_name").value = res.item_name
            document.querySelector("#update_desc").value = res.item_description
            document.querySelector("#update_price").value = res.item_price
            document.querySelector("#update_image").value = res.item_image
        }

        async function updateItem(){           
            const form = event.target.form;
            const itemId = document.querySelector("#update-form").dataset.id
            const modal = document.querySelector(".item-modal")
            let formData = new FormData(form);

            document.querySelector(".message").textContent = "";
            formData.append('item_id', itemId);

            const conn = await fetch("apis/api-update-item", {
                method: "POST",
                body: formData
            })
            const res = await conn.json()

            if (!conn.ok){
                document.querySelector(".message").textContent = res.info;
            } else if(conn.ok){
                modal.classList.add("hidden")
                getItems()
            }
           
        }

        async function uploadItem(){
            const form = event.target.form;
            const itemName = document.querySelector("#name").value
            const itemDesc = document.querySelector("#desc").value
            const itemPrice = document.querySelector("#price").value
            const itemImage = document.querySelector("#image").value
            const modal = document.querySelector(".upload-modal")

            const conn = await fetch("apis/api-upload-item", {
                method: "POST",
                body: new FormData(form)
            })
            const res = await conn.json()
            
            if(conn.ok){
                document.querySelector("#own-items").insertAdjacentHTML("afterbegin", 
                `<div class="item" data-id="${res.item_id}">
                    <div class='item-image'>
                        <img src='https://coderspage.com/2021-F-Web-Dev-Images/${itemImage}' />
                    </div>
                    <div class='item-text'>
                        <div class="price">$ ${itemPrice}</div>
                        <div class="name">${itemName}</div>
                        <div class="desc">${itemDesc}</div>
                        <div class='trash' onclick="deleteItem()">🗑️</div>
                        <div class='pen' onclick="editItem()">🖊️</div>
                    </div>
                </div>`)
                modal.classList.add("hidden")
            } else if (!conn.ok){
                document.querySelector(".error-message").textContent = res.info;
            }
        }

        async function deleteItem(){
            const item = event.target.parentNode.parentNode
            const id = item.getAttribute('data-id')
            let formData = new FormData();
            formData.append('item_id', id);

            const conn = await fetch("apis/api-delete-item", {
                method: "POST",
                body: formData
            })
            const res = await conn.text()
            if(conn.ok){
                item.remove();
            }
        }
    </script>
<?php
require_once(__DIR__.'/components/footer.php');
?>