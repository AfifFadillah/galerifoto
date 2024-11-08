<?php
session_start();
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $namaalbum = $_POST['namaalbum'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = date('Y-m-d');
    $userid = $_SESSION['userid'];

    $sql = mysqli_query($koneksi, "INSERT INTO album VALUES('','$namaalbum','$deskripsi','$tanggal','$userid')");

    echo "<script>
    alert('Data Berhasil Disimpan!');
    location.href='../admin/album.php';
    </script>";
}

if (isset($_POST['edit'])) {
    $albumid = $_POST['albumid'];
    $namaalbum = $_POST['namaalbum'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = date('Y-m-d');
    $userid = $_SESSION['userid'];

    $sql = mysqli_query($koneksi, "UPDATE album SET namaalbum='$namaalbum', deskripsi='$deskripsi', tanggaldibuat='$tanggal' WHERE albumid='$albumid'");

    echo "<script>
    alert('Data Berhasil Diperbarui!');
    location.href='../admin/album.php';
    </script>";
}

if (isset($_POST['hapus'])) {
    $albumid = $_POST['albumid'];

    // Hapus semua entri di tabel `likefoto` yang terkait dengan foto di album yang akan dihapus
    $deletelike = mysqli_query($koneksi, "DELETE likefoto FROM likefoto JOIN foto ON likefoto.fotoid = foto.fotoid WHERE foto.albumid = '$albumid'");

    // Hapus semua entri di tabel `foto` yang terkait dengan album yang akan dihapus
    $deletefoto = mysqli_query($koneksi, "DELETE FROM foto WHERE albumid='$albumid'");

    // Hapus album setelah entri terkait telah dihapus
    $deletealbum = mysqli_query($koneksi, "DELETE FROM album WHERE albumid='$albumid'");

    if ($deletealbum) {
        echo "<script>
        alert('Data Berhasil Dihapus!');
        location.href='../admin/album.php';
        </script>";
    } else {
        echo "<script>
        alert('Gagal menghapus album!');
        </script>";
    }
}

?>