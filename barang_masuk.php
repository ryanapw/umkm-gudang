<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];

/* ==========================================
   ➤ METHOD: GET (MENAMPILKAN BARANG MASUK)
   ========================================== */
if ($method == "GET") {
    // JOIN dengan tb_produk untuk mendapatkan nama_produk & harga_produk
    $sql = "SELECT bm.id, bm.produk_id, p.nama_produk, bm.jumlah, bm.tanggal, bm.keterangan 
            FROM tb_barang_masuk bm
            JOIN tb_produk p ON bm.produk_id = p.id
            ORDER BY bm.id DESC";
            
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
   ➤ METHOD: POST (TAMBAH BARANG MASUK)
   ========================================== */
elseif ($method == "POST") {
    $produk_id  = isset($_POST['produk_id']) ? mysqli_real_escape_string($conn, $_POST['produk_id']) : '';
    $jumlah     = isset($_POST['jumlah']) ? mysqli_real_escape_string($conn, $_POST['jumlah']) : '';
    $keterangan = isset($_POST['keterangan']) ? mysqli_real_escape_string($conn, $_POST['keterangan']) : '';

    if (empty($produk_id) || empty($jumlah)) {
        die(json_encode([
            "success" => false,
            "message" => "Data produk_id dan jumlah wajib diisi"
        ]));
    }

    $sql = "INSERT INTO tb_barang_masuk (produk_id, jumlah, keterangan) 
            VALUES ('$produk_id', '$jumlah', '$keterangan')";
            
    $query = mysqli_query($conn, $sql);

    echo json_encode([
        "success" => $query ? true : false,
        "message" => $query ? "Berhasil mencatat barang masuk" : "Gagal mencatat: " . mysqli_error($conn)
    ]);
}

else {
    echo json_encode([
        "success" => false,
        "message" => "Metode Request Tidak Diizinkan"
    ]);
}
?>