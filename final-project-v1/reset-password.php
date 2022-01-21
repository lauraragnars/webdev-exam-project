<?php
$_title = 'Reset password';

require_once(__DIR__.'/globals.php');
require_once(__DIR__.'/components/header.php');

if( !isset($_GET['key'])){
    echo "<div class='container middle'>
            <h1>Page not found</h1>
            <div class='link'>
                <a href='home'>Back to home</a>
            </div>
        </div>";
    exit();
}

if( strlen($_GET['key']) != 32 ){
    echo "<div class='container middle'>
            <h1>Page not found</h1>
            <div class='link'>
                <a href='home'>Back to home</a>
            </div>
        </div>";
    exit();
}

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

$q = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
  $q->bindValue(':user_id', $_GET['id']);
  $q->execute();
  $row = $q->fetch();

if( $_GET['key'] != $row['forgot_password_key']){
    echo "<div class='container middle'>
            <h1>Broken link, please try again later</h1>
            <div class='link'>
                <a href='home'>Back to home</a>
            </div
        </div>";
    exit();
}
?>

<div class="wrapper">
    <div class="modal">
    <h1>Reset password</h1>
        <form data-id=<?= $_GET['id'] ?> onsubmit="return false">
                <label for="password">New password</label>
                <input type="password" name="password" id="password" placeholder="Password">
                <label for="password2">Confirm password</label>
                <input type="password" name="password2" id="password2" placeholder="Confirm password">
                <h3 class="error-message"></h3>
                <button onclick="resetPassword()">Reset password</button>
                <div class="link">
                    <a href="index">Back to login</a>
                </div>
            </form>
    </div>
</div>

<script>
     async function resetPassword(){
        const form = event.target.form;
        const userId = form.dataset.id

        const formData = new FormData(form)
        formData.append('user_id', userId);

        let conn = await fetch("./apis/api-reset-password.php", {
            method: "POST",
            body: formData
        }) 
        let res = await conn.json();

        if (!conn.ok){
            document.querySelector(".error-message").textContent = res.info
        } else if (conn.ok){
            document.querySelector(".error-message").textContent = res.info
        }

        }
</script>

<?php
require_once(__DIR__.'/components/footer.php');
?>
