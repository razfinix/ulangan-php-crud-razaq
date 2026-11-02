
<?php
include "koneksi.php";

$edit = null;

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];

    $queryedit = mysqli_query(
        $koneksi,
        "SELECT * FROM pasien WHERE idpasien = $id"
    );

    $edit = mysqli_fetch_assoc($queryedit);
}

$data = mysqli_query(
    $koneksi,
    "SELECT * FROM pasien ORDER BY idpasien DESC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rumah Sakit Razaq</title>
</head>
<body>

<h2>Aplikasi Rumah Sakit Razaq</h2>
<p>Pengelolaan Data Pasien</p>

<hr>

<h3><?php echo $edit ? "Edit Pasien" : "Tambah Pasien"; ?></h3>

<form method="post" action="proses.php?proses=<?php echo $edit ? 'update' : 'insert'; ?>">

    <?php if ($edit) { ?>
        <input type="hidden" name="idpasien"
               value="<?php echo $edit['idpasien']; ?>">
    <?php } ?>

    <p>
        Nama Pasien<br>
        <input type="text" name="namapasien"
               value="<?php echo htmlspecialchars($edit['namapasien'] ?? ''); ?>"
               required>
    </p>

    <p>
        Jenis Kelamin<br>
        <select name="jeniskelamin" required>
            <option value="">-- Pilih Jenis Kelamin --</option>

            <option value="L"
                <?php echo (($edit['jeniskelamin'] ?? '') == 'L') ? 'selected' : ''; ?>>
                Laki-laki
            </option>

            <option value="P"
                <?php echo (($edit['jeniskelamin'] ?? '') == 'P') ? 'selected' : ''; ?>>
                Perempuan
            </option>
        </select>
    </p>

    <p>
        Alamat<br>
        <textarea name="alamat" rows="3"><?php
            echo htmlspecialchars($edit['alamat'] ?? '');
        ?></textarea>
    </p>

    <p>
        Nomor HP<br>
        <input type="text" name="nohp"
               value="<?php echo htmlspecialchars($edit['nohp'] ?? ''); ?>">
    </p>

    <?php if ($edit) { ?>
        <button type="submit">Update Pasien</button>
        <a href="index.php">Batal</a>
    <?php } else { ?>
        <button type="submit">Simpan Pasien</button>
    <?php } ?>

</form>

<hr>

<h3>Daftar Pasien</h3>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Nama Pasien</th>
        <th>Jenis Kelamin</th>
        <th>Alamat</th>
        <th>Nomor HP</th>
        <th>Aksi</th>
    </tr>

    <?php if (mysqli_num_rows($data) > 0) { ?>

        <?php while ($p = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td><?php echo $p['idpasien']; ?></td>

                <td><?php echo htmlspecialchars($p['namapasien']); ?></td>

                <td>
                    <?php
                    echo $p['jeniskelamin'] == 'L'
                        ? 'Laki-laki'
                        : 'Perempuan';
                    ?>
                </td>

                <td><?php echo htmlspecialchars($p['alamat'] ?? ''); ?></td>

                <td><?php echo htmlspecialchars($p['nohp'] ?? ''); ?></td>

                <td>
                    <a href="index.php?edit=<?php echo $p['idpasien']; ?>">
                        Edit
                    </a>

                    <form method="post" action="proses.php?proses=hapus"
                          onsubmit="return confirm('Yakin ingin menghapus pasien ini?');">

                        <input type="hidden" name="idpasien"
                               value="<?php echo $p['idpasien']; ?>">

                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php } ?>

    <?php } else { ?>

        <tr>
            <td colspan="6">Belum ada data pasien.</td>
        </tr>

    <?php } ?>

</table>

</body>
</html>
