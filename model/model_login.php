<?php
// Producción: no mostrar errores al navegador
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', '0');   // no mostrar
ini_set('log_errors', '1');       // (opcional) seguir logeando a error_log

include_once("../config/config.php");


$usuario= $_POST['usuario'];
$password= $_POST['password'];

// AUTH_URL
// LOGIN_URL

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $AUTH_URL);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, "usuario=aquaia&clave=aquaia1234");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data_token = curl_exec($ch);
curl_close($ch);
$json = json_decode($data_token, true);
$token = $json["token"];

$headers = array('Authorization:' . $token);



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $LOGIN_URL);
curl_setopt($ch, CURLOPT_POSTFIELDS, "usuario=" . $usuario . "&password=" . $password);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

$json = json_decode($result, true);


if($json["estado"] == 'error'){
    $salida = array("estado" => -1, "mensaje" => "Error al Iniciar Sesión usuario o contraseña incorrectos");
}else{
    session_start();
    $_SESSION["id_usuario"] =$json['id_usuario'];
    $_SESSION["rut_usuario"] =$json['rut_usuario'];
    $_SESSION["nombre_usuario"] =$json['nombre_usuario'];
    $_SESSION["id_apr"] =$json['id_apr'];
    $_SESSION["nombre_apr"] =$json['nombre_apr'];

    // print_r($_SESSION);

    $salida = array("estado" => 1, "mensaje" => "Iniciar Sesión");

}

echo json_encode($salida);
