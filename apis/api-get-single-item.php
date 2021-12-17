<?php

require_once(__DIR__.'/../globals.php');

if( !isset($_POST['item_id'])){ _res(400, ['info' => 'Item id required']); };
if( strlen($_POST['item_id']) > _ITEMID_MAX_LEN){ _res(400, ['info' => 'Item id cannot be more than'._ITEMID_MAX_LEN.' characters']); }

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

try{
    $q = $db->prepare('SELECT * FROM items WHERE item_id = :item_id');
    $q->bindValue(':item_id', $_POST['item_id']);
    $q->execute();
    $item = $q->fetch();

    // success
    echo(json_encode($item));

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}





