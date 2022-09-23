<?php

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $fullName = $_POST['fullName'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $currency = $_POST['currency'];
    $merchantCode = $_POST['merchantCode'];
    $affiliateId = $_POST['affiliateId'];

    $userdata = "username=" . "$username" . "&" . "password=" . "$password" . "&" . "phone=" . "$phone" . "&" . "currency=" . "$currency" . "&" . "fullName=" . "$fullName" . "&" . "merchantCode=" . "$merchantCode";
    $key = "7a413a521ba57a77cfab335088eae7a810a19bcf2144c2932bdfcd1d83a461ff";
    $authkey = base64_decode($key);
    $sign1 = hash_hmac('sha1', $userdata, $authkey, true);
    $sign = base64_encode($sign1);
    $data = array('username' => $username, 'password' => $password, 'phone' => $phone, 'currency' => $currency, 'fullName' => $fullName, 'merchantCode' => $merchantCode, 'sign' => $sign, 'affiliateId' => $affiliateId);
    $data_string = json_encode($data);

    $ch = curl_init('http://cm.zt828.net/merchant-api/simplified-register-member');   // where to post
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json')
    );

    $result = curl_exec($ch);
    //echo $result;
    //echo $data_string;
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $json = json_decode($result);
    $messages = $json->messages;
    $jwt = $json->jwt;
    
    if ($httpcode == '200') {
        echo "<script language='javascript'>setTimeout(function () {
        alert('You have successful register in P88.');
                window.location.href= 'https://www.play88.digital/external-login-success/?t=$jwt'; // the redirect goes here
                },60)</script>";
    } else {
        echo "<script language='javascript'>" .
            "alert('$messages[0]');" .
            "</script>";
    }
}
?>
