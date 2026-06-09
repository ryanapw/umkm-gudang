<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include "koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "GET") {

    $sql = "SELECT tb_produk.*, tb_kategori.nama_kategori 
            FROM tb_produk 
            JOIN tb_kategori ON tb_produk.kategori_id = tb_kategori.id
            ORDER BY tb_produk.id DESC";
            
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

elseif ($method == "POST") {

    $nama        = isset($_POST['nama_produk']) ? mysqli_real_escape_string($conn, $_POST['nama_produk']) : '';
    $harga       = isset($_POST['harga_produk']) ? mysqli_real_escape_string($conn, $_POST['harga_produk']) : '';
    $stok        = isset($_POST['stok']) ? mysqli_real_escape_string($conn, $_POST['stok']) : '0'; // Mengambil parameter stok
    $deskripsi   = isset($_POST['deskripsi']) ? mysqli_real_escape_string($conn, $_POST['deskripsi']) : '';
    $gambar      = isset($_POST['gambar']) ? mysqli_real_escape_string($conn, $_POST['gambar']) : '';
    $kategori_id = isset($_POST['kategori_id']) ? mysqli_real_escape_string($conn, $_POST['kategori_id']) : '';

    if (empty($nama) || empty($harga) || empty($kategori_id)) {
        die(json_encode([
            "success" => false,
            "message" => "Data nama, harga, dan kategori_id tidak boleh kosong"
        ]));
    }

    $sql = "INSERT INTO tb_produk (nama_produk, harga_produk, stok, deskripsi, gambar, kategori_id)
            VALUES ('$nama', '$harga', '$stok', '$deskripsi', '$gambar', '$kategori_id')";
            
    $query = mysqli_query($conn, $sql);

    echo json_encode([
        "success" => $query ? true : false,
        "message" => $query ? "Produk ditambahkan" : "Gagal tambah produk: " . mysqli_error($conn)
    ]);
}

elseif ($method == "PUT") {

    parse_str(file_get_contents("php://input"), $_PUT);

    $id        = isset($_PUT['id']) ? mysqli_real_escape_string($conn, $_PUT['id']) : '';
    $nama      = isset($_PUT['nama_produk']) ? mysqli_real_escape_string($conn, $_PUT['nama_produk']) : '';
    $harga     = isset($_PUT['harga_produk']) ? mysqli_real_escape_string($conn, $_PUT['harga_produk']) : '';
    $stok      = isset($_PUT['stok']) ? mysqli_real_escape_string($conn, $_PUT['stok']) : ''; // Mengambil data update stok
    $deskripsi = isset($_PUT['deskripsi']) ? mysqli_real_escape_string($conn, $_PUT['deskripsi']) : '';

    if (empty($id)) {
        die(json_encode([
            "success" => false,
            "message" => "ID produk wajib dikirim untuk update"
        ]));
    }

    $sql = "UPDATE tb_produk SET 
            nama_produk='$nama',
            harga_produk='$harga',
            stok='$stok',
            deskripsi='$deskripsi'
            WHERE id=$id";

    $query = mysqli_query($conn, $sql);

    echo json_encode([
        "success" => $query ? true : false,
        "message" => $query ? "Produk diupdate" : "Gagal update: " . mysqli_error($conn)
    ]);
}

elseif ($method == "DELETE") {

    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = isset($_DELETE['id']) ? mysqli_real_escape_string($conn, $_DELETE['id']) : '';

    if (empty($id)) {
        die(json_encode([
            "success" => false,
            "message" => "ID produk wajib dikirim untuk menghapus"
        ]));
    }

    $sql = "DELETE FROM tb_produk WHERE id=$id";
    $query = mysqli_query($conn, $sql);

    echo json_encode([
        "success" => $query ? true : false,
        "message" => $query ? "Produk dihapus" : "Gagal menghapus: " . mysqli_error($conn)
    ]);
}

else {
    echo json_encode([
        "success" => false,
        "message" => "Metode Request Tidak Diizinkan"
    ]);
}
?>
