<?php
$ch = curl_init("https://api.mercadopago.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CAINFO, "C:\laragon\bin\php\php-8.3.26-Win32-vs16-x64\extras\ssl\cacert.pem");
$res = curl_exec($ch);
if(curl_errno($ch)){
    echo curl_error($ch);
} else {
    echo "Certificado leído correctamente";
}
curl_close($ch);
