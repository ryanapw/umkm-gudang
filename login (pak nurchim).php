<?php
header("Content-Type: application/json");
include "koneksi.php";

// Validasi request agar aman jika diakses langsung dari browser (GET)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';

    // Mengubah dari 'admin' menjadi 'tb_admin'
    $sql = "SELECT * FROM tb_admin WHERE username='$username' AND password='$password'";
    $query = mysqli_query($conn, $sql);

    if (!$query) {
        die(json_encode([
            "success" => false,
            "message" => "Query Gagal: " . mysqli_error($conn)
        ]));
    }

    if ($row = mysqli_fetch_assoc($query)) {
        echo json_encode([
            "success" => true,
            "message" => "Login berhasil",
            "data" => [
                "id" => $row['id'],
                "username" => $row['username']
            ]
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Username atau password salah"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Metode request tidak diizinkan. Gunakan POST."
    ]);
}
?>