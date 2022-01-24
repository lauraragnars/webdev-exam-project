<?php

require_once('globals.php');

// validate
if( ! isset( $_POST['from_name'] ) ){ _res(400, ['info' => 'From name required']); };

if( ! isset( $_POST['to_name'] ) ){ _res(400, ['info' => 'To name required']); };

if( ! isset( $_POST['transfer_amount'] ) ){ _res(400, ['info' => 'Transfer amount required']); };
if( strlen( $_POST['transfer_amount'] ) < 0  ){ _res(400, ['info' => 'Transfer amount cannot be less than 0']); };

// Connect to DB
try{
    $db = _db();
  
  }catch(Exception $ex){
    _res(500, ['info' => 'System under maintenence', 'error' => __LINE__]);
}

$db->beginTransaction();

try {
    $q = $db->prepare('SELECT * FROM customer WHERE name = :name');
    $q->bindValue(":name", $_POST['from_name']);
    $q->execute();
    $row_from = $q -> fetch();
    $balance_from = $row_from['balance'];
    $new_from_balance = $balance_from - $_POST['transfer_amount'];

    if(!$row_from){
        _res(500, ['info' => 'From user does not exist', 'error' => __LINE__]);
        $db->rollBack();
        exit();
    }
    
    $q2 = $db->prepare('UPDATE customer SET balance = :new_balance WHERE name = :name');
    $q2->bindValue(":new_balance", $new_from_balance);
    $q2->bindValue(":name", $_POST['from_name']);
    $q2->execute();
 
} catch(Exception $ex){
    http_response_code(500);
    echo 'System under maintainance';
    $db->rollBack();
    exit();
}

try {
    $q3 = $db->prepare('SELECT * FROM customer WHERE name = :name');
    $q3->bindValue(":name", $_POST['to_name']);
    $q3->execute();
    $row_to = $q3 -> fetch();
    $balance_to = $row_to['balance'];
    $new_to_balance = $balance_to + $_POST['transfer_amount'];

    if(!$row_to){
        _res(500, ['info' => 'To user does not exist', 'error' => __LINE__]);
        $db->rollBack();
        exit();
    }
    
    $q4 = $db->prepare('UPDATE customer SET balance = :new_balance WHERE name = :name');
    $q4->bindValue(":new_balance", $new_to_balance);
    $q4->bindValue(":name", $_POST['to_name']);
    $q4->execute();
    echo('Sucessfully transferred '.$_POST['transfer_amount'].' from '.$_POST['from_name'].' to '.$_POST['to_name'].'');
 
} catch(Exception $ex){
    http_response_code(500);
    echo 'System under maintainance';
    $db->rollBack();
    exit();
}

$db->commit();



