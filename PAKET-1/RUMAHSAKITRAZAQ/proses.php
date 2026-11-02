
<?php
include "koneksi.php";

$proses = $_GET['proses'] ?? '';

if ($proses == "insert") {

    $namapasien = trim($_POST['namapasien'] ?? '');
    $jeniskelamin = $_POST['jeniskelamin'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $nohp = trim($_POST['nohp'] ?? '');

    if ($namapasien == '' ||
        !in_array($jeniskelamin, ['L', 'P'])) {
        die("Nama pasien dan jenis kelamin wajib diisi dengan benar.");
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO pasien (namapasien, jeniskelamin, alamat, nohp)
         VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $namapasien,
        $jeniskelamin,
        $alamat,
        $nohp
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        die("Gagal menyimpan data pasien.");
    }

} elseif ($proses == "update") {

    $idpasien = (int) ($_POST['idpasien'] ?? 0);
    $namapasien = trim($_POST['namapasien'] ?? '');
    $jeniskelamin = $_POST['jeniskelamin'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $nohp = trim($_POST['nohp'] ?? '');

    if ($idpasien <= 0 ||
        $namapasien == '' ||
        !in_array($jeniskelamin, ['L', 'P'])) {
        die("Data pasien tidak valid.");
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE pasien
         SET namapasien = ?, jeniskelamin = ?, alamat = ?, nohp = ?
         WHERE idpasien = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $namapasien,
        $jeniskelamin,
        $alamat,
        $nohp,
        $idpasien
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        die("Gagal mengubah data pasien.");
    }

} elseif ($proses == "hapus") {

    $idpasien = (int) ($_POST['idpasien'] ?? 0);

    if ($idpasien <= 0) {
        die("ID pasien tidak valid.");
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM pasien WHERE idpasien = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $idpasien);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        die("Gagal menghapus data pasien.");
    }

} else {
    die("Proses tidak ditemukan.");
}
?>
