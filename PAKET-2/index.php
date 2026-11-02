
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pasien - Rumah Sakit Razaq</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f7fa;
            color: #333;
        }

        .container {
            width: 90%;
            margin: auto;
        }

        .box {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background: white;
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #bbb;
            border-radius: 4px;
        }

        button, .tombol {
            display: inline-block;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            background: #e8edf3;
            color: #222;
        }

        button:hover, .tombol:hover {
            background: #d4deeb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #e8edf3;
        }

        .aksi {
            white-space: nowrap;
        }

        @media (max-width: 700px) {
            .container {
                width: 100%;
            }

            .box {
                overflow-x: auto;
            }

            table {
                min-width: 650px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <h2>Aplikasi Rumah Sakit Razaq</h2>
    <p>Pengelolaan Data Pasien</p>

    <div class="box">
        <h3><?= $edit ? 'Edit Pasien' : 'Tambah Pasien' ?></h3>

        <form method="post"
              action="proses.php?proses=<?= $edit ? 'update' : 'insert' ?>">

            <?php if ($edit) { ?>
                <input type="hidden" name="idpasien"
                       value="<?= (int) $edit['idpasien'] ?>">
            <?php } ?>

            <label>Nama Pasien</label>
            <input
                type="text"
                name="namapasien"
                value="<?= htmlspecialchars($edit['namapasien'] ?? '') ?>"
                required
            >

            <label>Jenis Kelamin</label>
            <select name="jeniskelamin" required>
                <option value="">-- Pilih Jenis Kelamin --</option>

                <option value="L"
                    <?= in_array(
                        strtoupper(trim($edit['jeniskelamin'] ?? '')),
                        ['L', 'LAKI-LAKI']
                    ) ? 'selected' : '' ?>>
                    Laki-laki
                </option>

                <option value="P"
                    <?= in_array(
                        strtoupper(trim($edit['jeniskelamin'] ?? '')),
                        ['P', 'PEREMPUAN']
                    ) ? 'selected' : '' ?>>
                    Perempuan
                </option>
            </select>

            <label>Alamat</label>
            <textarea name="alamat" rows="3"><?= htmlspecialchars($edit['alamat'] ?? '') ?></textarea>

            <label>No. HP</label>
            <input
                type="text"
                name="nohp"
                value="<?= htmlspecialchars($edit['nohp'] ?? '') ?>"
            >

            <br><br>

            <?php if ($edit) { ?>
                <button type="submit">Update Pasien</button>
                <a href="index.php" class="tombol">Batal</a>
            <?php } else { ?>
                <button type="submit">Simpan Pasien</button>
            <?php } ?>

        </form>
    </div>

    <div class="box">
        <h3>Daftar Pasien</h3>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>No. HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($data) > 0) { ?>

                    <?php while ($p = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <td><?= (int) $p['idpasien'] ?></td>

                            <td><?= htmlspecialchars($p['namapasien']) ?></td>

                            <td>
                                <?php
                                $jk = strtoupper(trim($p['jeniskelamin'] ?? ''));

                                if ($jk == 'L' || $jk == 'LAKI-LAKI') {
                                    echo 'Laki-laki';
                                } elseif ($jk == 'P' || $jk == 'PEREMPUAN') {
                                    echo 'Perempuan';
                                } else {
                                    echo htmlspecialchars($p['jeniskelamin'] ?? '');
                                }
                                ?>
                            </td>

                            <td><?= htmlspecialchars($p['alamat'] ?? '') ?></td>

                            <td><?= htmlspecialchars($p['nohp'] ?? '') ?></td>

                            <td class="aksi">
                                <a href="index.php?edit=<?= (int) $p['idpasien'] ?>">Edit</a>
                                |
                                <form
                                    method="post"
                                    action="proses.php?proses=hapus"
                                    style="display:inline"
                                    onsubmit="return confirm('Yakin ingin menghapus pasien ini?')"
                                >
                                    <input type="hidden" name="idpasien"
                                           value="<?= (int) $p['idpasien'] ?>">
                                    <button type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>

                <?php } else { ?>
                    <tr>
                        <td colspan="6" style="text-align:center">
                            Belum ada data pasien.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
