<?php
    // Validate
    if( !isset($_POST['to_phone'])){ _res(400, ['info' => 'To phone required']);}
    if( !is_numeric( $_POST['to_phone'] ) ){ _res(400, ['info' => 'Phone number can only contain numbers']); };
    if( strlen( $_POST['to_phone'] ) < _PHONE_MIN_LEN ){ _res(400, ['info' => 'Phone number min '._PHONE_MIN_LEN.' characters']); };
    if( strlen( $_POST['to_phone'] ) > _PHONE_MAX_LEN ){ _res(400, ['info' => 'Phone number max '._PHONE_MAX_LEN.' characters']); };

    if( !isset($_POST['message'])){ _res(400, ['info' => 'Message required']);}

    $api_key = '286de1fe-c456-4edd-b303-c2c3d1ee25dc';

    $fields = [
        'to_phone' => $_POST['to_phone'],
        'message' => $_POST['message'],
        'api_key' => $api_key
    ];
    $postdata = http_build_query($fields);
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, 'https://fatsms.com/send-sms');
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    echo $result;
?>
