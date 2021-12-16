<?php

require_once(__DIR__.'/../globals.php');

if( !isset($_POST['item_id'])){ _res(400, ['info' => 'Item id required']); };
if( strlen($_POST['item_id']) == _ITEMID_LEN){ http_response_code(400); echo 'item_id needs to be'._ITEMID_LEN.' characters'; exit(); }

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





