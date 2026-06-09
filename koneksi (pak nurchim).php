<?php

$host = "sql100.infinityfree.com";
$user = "if0_41606992";
$pass = "BXN8U27ijoFPtRJ";
$db   = "if0_41606992_gudang";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die(json_encode([
        "success" => false,
        "message" => "Koneksi gagal: " . mysqli_connect_error()
    ]));
}

?>