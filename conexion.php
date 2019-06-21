<?php
$servername = "remotemysql.com";
$database = "vN3T9N3HJT";
$username = "vN3T9N3HJT";
$password = "65oLG7iRbU";
// Create connection
$conection = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conection) {
    die("Coneccion fallida" . mysqli_connect_error());
}
//mysqli_close($conection);
?>