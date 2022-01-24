<?php

// ##############################
function _res( $status=200, $message=[] ){
  http_response_code($status); 
  header('Content-Type: application/json'); 
  echo json_encode($message); 
  exit();
}


// ##############################
function _db(){
    
    $database_user_name = 'root';
    $database_pasword = 'root'; // MAC password is root
    $database_connection = "mysql:host=localhost:8889; dbname=bank; charset=utf8mb4";
    
    $database_options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    return new PDO( $database_connection, $database_user_name, $database_pasword, $database_options );
} 

_db();
