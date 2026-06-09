<?php
header("Content-Type: application/json");
include "koneksi.php";

$query = mysqli_query($conn, "SELECT id, username FROM tb_admin");

if (!$query) {
    die(json_encode([
        "success" => false,
        "message" => "Query Gagal: " . mysqli_error($conn)
    ]));
}

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

echo json_encode([
    "success" => true,
    "data" => $data
]);
?>
