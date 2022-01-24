<?php
    session_start();
    if( ! isset($_SESSION['user_name'])){
        header('Location: index');
        exit();
    };

    $_title = 'User page';
    require_once('components/header.php'); 
    require_once('api-items.php'); 
?>
    <nav>
        <a href="logout">Logout</a>
    </nav>
    <h1>
        <?php
            echo $_SESSION['user_name'];
        ?>
    </h1>

    <div id="items">
        <?php         
           foreach ($items as &$item) {
               $title = $item["item_name"];
               $price = $item["item_price"];
               $desc = $item["item_description"];
               echo "<div class='item'>
                   <div>$title</div>
                   <div>$price</div>
                   <div>$desc</div>
                </div>";
           }
        ?>
    </div>
<?php
require_once('components/footer.php');
?>
