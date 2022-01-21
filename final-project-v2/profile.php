<?php
    session_start();
    if( ! isset($_SESSION['user_name'])){
        header('Location: index');
        exit();
    };

    $_title = 'Profile page';
    require_once(__DIR__.'/components/header.php'); 
    require_once(__DIR__.'/components/nav.php'); 
?>
    <div class="container">
        <form id="container-flex" onsubmit="return false">
            <div>
                <h2>Update user information</h2>
                <label for="name">First name</label>
                <input value=<?= $_SESSION['user_name'] ?> name="name" type="text" placeholder="First name">
                <label for="last_name">Last name</label>
                <input value=<?= $_SESSION['user_last_name'] ?> name="last_name" type="text" placeholder="Last name">
                <label for="phone_number">Phone number</label>
                <input value=<?= $_SESSION['user_phone_number'] ?> name="phone_number" type="text" maxlength="8" placeholder="New phone number">
                <label for="email">Email</label>
                <input value=<?= $_SESSION['user_email'] ?> type="text" name="email" id="email" placeholder="Email">
                <h3 class="message message-info"></h3>
                <button onclick="updateInfo()">Update information</button>
            </div>
            <div>
                <h2>Update password</h2>
                <label for="password">New password</label>
                <input type="password" name="password" id="password" placeholder="Password">
                <label for="password">Confirm password</label>
                <input type="password" name="password2" id="password2" placeholder="Confirm password">
                <h3 class="message message-password"></h3>
                <button onclick="changePassword()">Change password</button>
            </div>
        </form>
    </div>
    
    <script>
    async function updateInfo(){
    const form = event.target.form;
       let conn = await fetch("./apis/api-update-user.php", {
           method : "POST",
           body: new FormData(form)
       })
       let res = await conn.json()
       if (!conn.ok){
           document.querySelector(".message-info").textContent = res.info
       } else if (conn.ok){
            document.querySelector(".message-info").textContent = res.info
       }
    }

    async function changePassword(){
    const form = event.target.form;
       let conn = await fetch("./apis/api-change-password.php", {
           method : "POST",
           body: new FormData(form)
       })
       let res = await conn.json()
       if (!conn.ok){
           document.querySelector(".message-password").textContent = res.info
       } else if (conn.ok){
            document.querySelector(".message-password").textContent = res.info
       }
    }
</script>
    
<?php
require_once(__DIR__.'/components/footer.php');
?>
