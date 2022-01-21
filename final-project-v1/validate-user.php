<?php
$_title = 'Validate email';

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
  $q->bindValue(":user_id", $_GET['id']);
  $q->execute();
  $row = $q->fetch();

if( $_GET['key'] != $row['verification_key']){
    echo "<div class='container middle'>
            <h1>Broken link, please try again</h1>
            <div class='link'>
                <a href='home'>Back to home</a>
            </div>
        </div>";
    exit();
}

$q2 = $db->prepare('UPDATE users SET verified = :verified WHERE user_id = :user_id');
  $q2->bindValue(":verified", 1);
  $q2->bindValue(":user_id", $_GET['id']);
  $q2->execute();

echo "<div class='container middle'>
        <h1>Your email has been verified!</h1>
        <div class='link'>
            <a href='home'>Back to home</a>
        </div>
    </div>";

require_once(__DIR__.'/components/footer.php');
?>