<?php
header("Content-Type: application/json");
include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];

/* ➤ METHOD: GET (TAMPILKAN KATEGORI) */
if ($method == "GET") {

    // Mengubah dari 'kategori' menjadi 'tb_kategori'
    $query = mysqli_query($conn, "SELECT * FROM tb_kategori");
    
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

/* ➤ METHOD: POST (TAMBAH KATEGORI) */
} elseif ($method == "POST") {

    $nama = isset($_POST['nama_kategori']) ? mysqli_real_escape_string($conn, $_POST['nama_kategori']) : '';

    if (empty($nama)) {
        die(json_encode([
            "success" => false,
            "message" => "Nama kategori tidak boleh kosong"
        ]));
    }

    // Mengubah dari 'kategori' menjadi 'tb_kategori'
    $query = mysqli_query($conn, "INSERT INTO tb_kategori (nama_kategori) VALUES ('$nama')");

    echo json_encode([
        "success" => $query ? true : false,
        "message" => $query ? "Berhasil tambah kategori" : "Gagal: " . mysqli_error($conn)
    ]);
}
?>