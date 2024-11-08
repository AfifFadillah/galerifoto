<?php
session_start();
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $judulfoto = $_POST['judulfoto'];
    $deskripsifoto = $_POST['deskripsifoto'];
    $tanggalunggah = date('Y-m-d');
    $albumid = $_POST['albumid'];
    $userid = $_SESSION['userid'];
    $foto = $_FILES['lokasifile']['name'];
    $tmp = $_FILES['lokasifile']['tmp_name'];
    $lokasi = '../assets/img/';
    $namafoto = rand() . '-' . $foto;

    // Validasi tipe dan ukuran file
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $fileExtension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
    $fileType = mime_content_type($tmp);
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($fileExtension, $allowedExtensions) || !in_array($fileType, $allowedMimeTypes)) {
        echo "<script>alert('Hanya file JPG, JPEG, PNG, dan WebP yang diperbolehkan!'); location.href='../admin/foto.php';</script>";
        exit;
    }

    if ($_FILES['lokasifile']['size'] > $maxFileSize) {
        echo "<script>alert('Ukuran file maksimal 2MB!'); location.href='../admin/foto.php';</script>";
        exit;
    }

    move_uploaded_file($tmp, $lokasi . $namafoto);

    $sql = mysqli_query($koneksi, "INSERT INTO foto VALUES('','$judulfoto','$deskripsifoto','$tanggalunggah','$namafoto','$albumid','$userid')");

    echo "<script>
        alert('Data Berhasil Disimpan!');
        location.href='../admin/foto.php';
        </script>";
}

if (isset($_POST['edit'])) {
    $fotoid = $_POST['fotoid'];
    $judulfoto = $_POST['judulfoto'];
    $deskripsifoto = $_POST['deskripsifoto'];
    $tanggalunggah = date('Y-m-d');
    $albumid = $_POST['albumid'];
    $userid = $_SESSION['userid'];
    $foto = $_FILES['lokasifile']['name'];
    $tmp = $_FILES['lokasifile']['tmp_name'];
    $lokasi = '../assets/img/';
    $namafoto = rand() . '-' . $foto;

    if ($foto == null) {
        $sql = mysqli_query($koneksi, "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto', tanggalunggah='$tanggalunggah', albumid='$albumid' WHERE fotoid='$fotoid'");
    } else {
        // Validasi tipe dan ukuran file
        $fileExtension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        $fileType = mime_content_type($tmp);

        if (!in_array($fileExtension, $allowedExtensions) || !in_array($fileType, $allowedMimeTypes)) {
            echo "<script>alert('Hanya file JPG, JPEG, PNG, dan WebP yang diperbolehkan!'); location.href='../admin/foto.php';</script>";
            exit;
        }

        if ($_FILES['lokasifile']['size'] > $maxFileSize) {
            echo "<script>alert('Ukuran file maksimal 2MB!'); location.href='../admin/foto.php';</script>";
            exit;
        }

        $query = mysqli_query($koneksi, "SELECT * FROM  foto WHERE fotoid='$fotoid'");
        $data = mysqli_fetch_array($query);
        if (is_file('../assets/img/' . $data['lokasifile'])) {
            unlink('../assets/img/' . $data['lokasifile']);
        }
        move_uploaded_file($tmp, $lokasi . $namafoto);
        $sql = mysqli_query($koneksi, "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto', tanggalunggah='$tanggalunggah', lokasifile='$namafoto', albumid='$albumid' WHERE fotoid='$fotoid'");
    }
    echo "<script>
    alert('Data Berhasil Diperbarui!');
    location.href='../admin/foto.php';
    </script>";
}

if (isset($_POST['hapus'])) {
    $fotoid = $_POST['fotoid'];

    // Hapus data terkait di tabel likefoto terlebih dahulu
    $deletelike = mysqli_query($koneksi, "DELETE FROM likefoto WHERE fotoid='$fotoid'");

    if ($deletelike) {
        $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE fotoid='$fotoid'");
        $data = mysqli_fetch_array($query);

        // Jika file gambar ada, hapus file fisik dari direktori
        if (is_file('../assets/img/' . $data['lokasifile'])) {
            unlink('../assets/img/' . $data['lokasifile']);
        }

        // Hapus data dari tabel foto setelah data terkait dihapus dari likefoto
        $sql = mysqli_query($koneksi, "DELETE FROM foto WHERE fotoid='$fotoid'");

        if ($sql) {
            echo "<script>alert('Data berhasil dihapus!'); location.href='../admin/foto.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data!'); location.href='../admin/foto.php';</script>";
        }
    } else {
        echo "<script>alert('Gagal menghapus data dari tabel likefoto!'); location.href='../admin/foto.php';</script>";
    }
}
?>
