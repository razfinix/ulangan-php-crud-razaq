
<?php
include "koneksi.php";

$proses = $_GET['proses'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

if ($proses == 'insert' || $proses == 'update') {

    $namapasien = trim($_POST['namapasien'] ?? '');
    $jeniskelamin = strtoupper(
        trim($_POST['jeniskelamin'] ?? '')
    );
    $alamat = trim($_POST['alamat'] ?? '');
    $nohp = trim($_POST['nohp'] ?? '');

    // Sesuaikan nilai jenis kelamin sebelum disimpan.
    if ($jeniskelamin == 'LAKI-LAKI') {
        $jeniskelamin = 'L';
    } elseif ($jeniskelamin == 'PEREMPUAN') {
        $jeniskelamin = 'P';
    }

    if (!in_array($jeniskelamin, ['L', 'P'], true)) {
        die(
            "Jenis kelamin tidak valid. " .
            "<a href='index.php'>Kembali ke form</a>"
        );
    }

    if ($namapasien == '') {
        die("Nama pasien wajib diisi.");
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO pasien
        (namapasien, jeniskelamin, alamat, nohp)
        VALUES (?, ?, ?, ?)"
    );

    if ($proses == 'insert') {

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $namapasien,
            $jeniskelamin,
            $alamat,
            $nohp
        );

        if (!mysqli_stmt_execute($stmt)) {
            die("Gagal menambahkan pasien: " .
                mysqli_stmt_error($stmt));
        }

    } else {

        mysqli_stmt_close($stmt);

        $id = (int) ($_POST['idpasien'] ?? 0);

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE pasien SET
                namapasien = ?,
                jeniskelamin = ?,
                alamat = ?,
                nohp = ?
             WHERE idpasien = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $namapasien,
            $jeniskelamin,
            $alamat,
            $nohp,
            $id
        );

        if (!mysqli_stmt_execute($stmt)) {
            die("Gagal mengubah pasien: " .
                mysqli_stmt_error($stmt));
        }
    }

    mysqli_stmt_close($stmt);

} elseif ($proses == 'hapus') {

    $id = (int) ($_POST['idpasien'] ?? 0);

    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM pasien WHERE idpasien = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (!mysqli_stmt_execute($stmt)) {
        die("Gagal menghapus pasien: " .
            mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

} else {
    die("Proses tidak dikenal.");
}

header("Location: index.php");
exit;
?>
