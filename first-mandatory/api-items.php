<?php

require_once('globals.php');

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

try{
    $q = $db->prepare('SELECT * FROM items');
    $q->execute();
    $items = $q->fetchAll();

    // // success
    // echo(json_encode($items));
    // file_put_contents("shop.txt", json_encode($items));


}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}





