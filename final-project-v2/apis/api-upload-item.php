<?php
require_once(__DIR__.'/../globals.php');

// Validate
if( !isset($_POST['item_name'])){ _res(400, ['info' => 'Item name required']);}
if( strlen($_POST['item_name']) < _ITEM_MIN_LEN){ _res(400, ['info' => 'Item_name min '._ITEM_MIN_LEN.' characters']);}
if( strlen($_POST['item_name']) > _ITEM_MAX_LEN){ _res(400, ['info' => 'Item_name max '._ITEM_MAX_LEN.' characters']);}

if( !isset($_POST['item_description'])){ _res(400, ['info' => 'Item description required']);}
if( strlen($_POST['item_description']) < _DESC_MIN_LEN){ _res(400, ['info' => 'Item description min '._DESC_MIN_LEN.' characters']);}
if( strlen($_POST['item_description']) > _DESC_MAX_LEN){ _res(400, ['info' => 'Item description max '._DESC_MAX_LEN.' characters']);}

if( !isset($_POST['item_price'])){ _res(400, ['info' => 'Item price required']);}
if( strlen($_POST['item_price']) < _PRICE_MIN_LEN){ _res(400, ['info' => 'Item price min '._PRICE_MIN_LEN.' characters']);}
if( strlen($_POST['item_price']) > _PRICE_MAX_LEN){ _res(400, ['info' => 'Item price max '._PRICE_MAX_LEN.' characters']);}

if( !isset($_POST['item_image'])){ _res(400, ['info' => 'Item image required']);}
if( strlen($_POST['item_image']) < _IMAGE_MIN_LEN){ _res(400, ['info' => 'Item image min '._IMAGE_MIN_LEN.' characters']);}
if( strlen($_POST['item_image']) > _IMAGE_MAX_LEN){ _res(400, ['info' => 'Item image max '._IMAGE_MAX_LEN.' characters']);}

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

try{
    $item_id = bin2hex(random_bytes(16));
    $q = $db->prepare('INSERT INTO items VALUES(:item_id, :item_name, :item_description, :item_price, :item_image)');
    $q->bindValue(':item_id', $item_id);
    $q->bindValue(':item_name', $_POST['item_name']);
    $q->bindValue(':item_description', $_POST['item_description']);
    $q->bindValue(':item_price', $_POST['item_price']);
    $q->bindValue(':item_image', $_POST['item_image']);
    $q->execute();

    _res(200, ['info' => "Item created with item id: $item_id", 'item_id' => $item_id]);
    
}catch(Exception $ex){
    http_response_code(500);
    echo 'System under maintainance';
    exit();
  }
  