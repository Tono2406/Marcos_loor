<?php
$servername = "bi0aqi7zz0lebqqotyb2-mysql.services.clever-cloud.com";
$username = "uibqp4selzd2lnsl";
$password = "JXheMSoK2121Z87qfJtI";
$dbname = "bi0aqi7zz0lebqqotyb2";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
