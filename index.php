<?php
include 'db_config.php';

// Proses tambah data
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $kelasID = $_POST['kelasID'];

    $sql_insert = "INSERT INTO mahasiswa (nama, email, kelasID) VALUES ('$nama', '$email', '$kelasID')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "<script>alert('Data berhasil ditambahkan!');window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}


// Proses hapus data
if (isset($_GET['delete'])) {
    $mhsID = $_GET['delete'];
    $sql_delete = "DELETE FROM mahasiswa WHERE mhsID = '$mhsID'";

    if ($conn->query($sql_delete) === TRUE) {
        echo "<script>alert('Data berhasil dihapus!');window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Proses edit data
if (isset($_GET['edit'])) {
    $mhsID = $_GET['edit'];
    $sql_edit = "SELECT * FROM mahasiswa WHERE mhsID = '$mhsID'";
    $result_edit = $conn->query($sql_edit);

    if ($result_edit->num_rows > 0) {
        $row_edit = $result_edit->fetch_assoc();
        $nama = $row_edit['nama'];
        $email = $row_edit['email'];
        $kelasID = $row_edit['kelasID'];
    }
}

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $mhsID = $_POST['mhsID'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $kelasID = $_POST['kelasID'];

    $sql_update = "UPDATE mahasiswa SET nama='$nama', email='$email', kelasID='$kelasID' WHERE mhsID='$mhsID'";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Data berhasil diupdate!');window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Query data mahasiswa dan kelas
$sql = "SELECT mahasiswa.mhsID, mahasiswa.nama, mahasiswa.email, kelas.namaKelas 
        FROM mahasiswa
        JOIN kelas ON mahasiswa.kelasID = kelas.kelasID";
$result = $conn->query($sql);

// Query data kelas untuk dropdown
$sql_kelas = "SELECT * FROM kelas";
$result_kelas = $conn->query($sql_kelas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <style>
        /* Sama seperti style sebelumnya */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        form {
            margin-bottom: 20px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin: 10px 0 5px;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Daftar Mahasiswa</h1>

    <!-- Form Tambah Data -->
    <h2>Tambah Data Mahasiswa</h2>
    <form method="POST" action="">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" required>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="kelasID">Kelas</label>
        <select id="kelasID" name="kelasID" required>
            <option value="">Pilih Kelas</option>
            <?php
            $result_kelas->data_seek(0); // Reset pointer
            while ($row_kelas = $result_kelas->fetch_assoc()) {
                echo "<option value='{$row_kelas['kelasID']}'>{$row_kelas['namaKelas']}</option>";
            }
            ?>
        </select>
        <button type="submit">Tambah Data</button>
    </form>

    <!-- Tabel Data -->
    <h2>Data Mahasiswa</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Kelas</th>
            <th>Aksi</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['mhsID']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['namaKelas']}</td>
                        <td>
                            <a href='?edit={$row['mhsID']}'>Edit</a> | 
                            <a href='?delete={$row['mhsID']}' onclick='return confirm(\"Yakin ingin menghapus data?\")'>Hapus</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
        }
        ?>
    </table>

    <?php if (isset($_GET['edit'])): ?>
    <!-- Form Edit -->
    <h2>Edit Data Mahasiswa</h2>
    <form method="POST" action="">
        <input type="hidden" name="mhsID" value="<?php echo $mhsID; ?>">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>" required>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
        <label for="kelasID">Kelas</label>
        <select id="kelasID" name="kelasID" required>
            <option value="">Pilih Kelas</option>
            <?php
            // Reset query pointer agar bisa digunakan ulang
            $result_kelas->data_seek(0); 
            while ($row_kelas = $result_kelas->fetch_assoc()) {
                // Menandai kelas yang sudah dipilih dengan atribut selected
                echo "<option value='{$row_kelas['kelasID']}'" . ($row_kelas['kelasID'] == $kelasID ? " selected" : "") . ">{$row_kelas['namaKelas']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="update">Update Data</button>
    </form>
    <?php endif; ?>

</body>
</html>