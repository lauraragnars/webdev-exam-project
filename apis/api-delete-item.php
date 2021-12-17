<?php
require_once(__DIR__.'/../globals.php');

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

$db->beginTransaction();

try{
    $q2 = $db->prepare('INSERT INTO items_archive SELECT * FROM items WHERE item_id = :item_id');
    $q2->bindValue(':item_id', $_POST['item_id']);
    $q2->execute();
    echo "Item added to archive";
}catch(Exception $ex){
    http_response_code(500);
    echo 'System under maintainance'.__LINE__;
    $db->rollBack();
    exit();
}

try{
    $q = $db->prepare('DELETE FROM items WHERE item_id = :item_id');
    $q->bindValue(':item_id', $_POST['item_id']);
    $q->execute();
    echo "Item deleted";
}catch(Exception $ex){
    http_response_code(500);
    echo 'System under maintainance'.__LINE__;
    $db->rollBack();
    exit();
}

$db->commit();
  