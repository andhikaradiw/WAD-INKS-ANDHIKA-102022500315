<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $id             = mysqli_real_escape_string($koneksi, $_POST['id_penghuni']);
    $nama_lengkap   = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $no_ktp         = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $pekerjaan      = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $kontak_darurat = mysqli_real_escape_string($koneksi, $_POST['kontak_darurat']);

    if (empty($id)) {
        $query = "INSERT INTO penghuni (nama_lengkap, no_ktp, pekerjaan, kontak_darurat) 
                  VALUES ('$nama_lengkap', '$no_ktp', '$pekerjaan', '$kontak_darurat')";
    } else {
        $query = "UPDATE penghuni SET 
                  nama_lengkap='$nama_lengkap', no_ktp='$no_ktp', pekerjaan='$pekerjaan', 
                  kontak_darurat='$kontak_darurat' WHERE id_penghuni='$id'";
    }
    mysqli_query($koneksi, $query);
    header("Location: penghuni.php");
    exit();
}

if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM penghuni WHERE id_penghuni='$id'");
    header("Location: penghuni.php");
    exit();
}

$edit_data = ['id_penghuni' => '', 'nama_lengkap' => '', 'no_ktp' => '', 'pekerjaan' => '', 'kontak_darurat' => ''];
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $result = mysqli_query($koneksi, "SELECT * FROM penghuni WHERE id_penghuni='$id'");
    if (mysqli_num_rows($result) > 0) {
        $edit_data = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul Penghuni - Sistem Manajemen Kos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="sidebar">
        <div class="brand">🏢 Manajemen Kos</div>
        <ul class="sidebar-menu">
            <li><a href="index.php">📊 Dashboard</a></li>
            <li><a href="kamar.php">🖈 Modul Kamar</a></li>
            <li><a href="penghuni.php" class="active">👥 Modul Penghuni</a></li>
            <li><a href="housekeeper.php">🧹 Modul Housekeeper</a></li>
            <li><a href="inventaris.php">📦 Modul Inventaris</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-header">
            <div>
                <h1>Modul Data Penghuni</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Kelola profil penyewa/penghuni kos dan identitasnya.</p>
            </div>
            <div class="user-profile">
                <div class="user-avatar">D</div>
                <span style="font-size: 0.875rem; font-weight: 600;">Dhika / Admin</span>
            </div>
        </div>

        <form method="POST" action="">
            <input type="hidden" name="id_penghuni" value="<?= htmlspecialchars($edit_data['id_penghuni']); ?>">
            
            <label>Nama Lengkap:</label>
            <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($edit_data['nama_lengkap']); ?>" required placeholder="Contoh: Budi Santoso">

            <label>Nomor KTP / Identitas:</label>
            <input type="text" name="no_ktp" value="<?= htmlspecialchars($edit_data['no_ktp']); ?>" required placeholder="Contoh: 3271234567890001">

            <label>Pekerjaan:</label>
            <input type="text" name="pekerjaan" value="<?= htmlspecialchars($edit_data['pekerjaan']); ?>" required placeholder="Contoh: Mahasiswa / Karyawan">

            <label>Kontak Darurat:</label>
            <input type="text" name="kontak_darurat" value="<?= htmlspecialchars($edit_data['kontak_darurat']); ?>" required placeholder="Contoh: 08123456789">

            <button type="submit" name="simpan">Simpan Data Penghuni</button>
        </form>

        <h3 style="margin-bottom: 16px;">Daftar Penghuni Kos</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>No KTP</th>
                    <th>Pekerjaan</th>
                    <th>Kontak Darurat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $data = mysqli_query($koneksi, "SELECT * FROM penghuni ORDER BY id_penghuni DESC");
                while ($row = mysqli_fetch_assoc($data)) {
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><b><?= htmlspecialchars($row['nama_lengkap']); ?></b></td>
                    <td><?= htmlspecialchars($row['no_ktp']); ?></td>
                    <td><?= htmlspecialchars($row['pekerjaan']); ?></td>
                    <td><?= htmlspecialchars($row['kontak_darurat']); ?></td>
                    <td>
                        <a href="penghuni.php?edit=<?= $row['id_penghuni']; ?>">Edit</a> | 
                        <a href="penghuni.php?hapus=<?= $row['id_penghuni']; ?>" onclick="return confirm('Yakin hapus data penghuni ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>