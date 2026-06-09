<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];

/* ==========================================
   ➤ METHOD: GET (MENAMPILKAN BARANG KELUAR)
   ========================================== */
if ($method == "GET") {
    // JOIN dengan tb_produk untuk mendapatkan nama_produk
    $sql = "SELECT bk.id, bk.produk_id, p.nama_produk, bk.jumlah, bk.tanggal, bk.tujuan 
            FROM tb_barang_keluar bk
            JOIN tb_produk p ON bk.produk_id = p.id
            ORDER BY bk.id DESC";
            
    $query = mysqli_query($conn, $sql);

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
}

/* ==========================================
   ➤ METHOD: POST (TAMBAH BARANG KELUAR)
   ========================================== */
elseif ($method == "POST") {
    $produk_id = isset($_POST['produk_id']) ? mysqli_real_escape_string($conn, $_POST['produk_id']) : '';
    $jumlah    = isset($_POST['jumlah']) ? mysqli_real_escape_string($conn, $_POST['jumlah']) : '';
    $tujuan    = isset($_POST['tujuan']) ? mysqli_real_escape_string($conn, $_POST['tujuan']) : '';

    if (empty($produk_id) || empty($jumlah)) {
        die(json_encode([
            "success" => false,
            "message" => "Data produk_id dan jumlah wajib diisi"
        ]));
    }

    $sql = "INSERT INTO tb_barang_keluar (produk_id, jumlah, tujuan) 
            VALUES ('$produk_id', '$jumlah', '$tujuan')";
            
    $query = mysqli_query($conn, $sql);

    echo json_encode([
        "success" => $query ? true : false,
        "message" => $query ? "Berhasil mencatat barang keluar" : "Gagal mencatat: " . mysqli_error($conn)
    ]);
}

else {
    echo json_encode([
        "success" => false,
        "message" => "Metode Request Tidak Diizinkan"
    ]);
}
?>