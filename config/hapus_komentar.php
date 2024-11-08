<?php
session_start();
include 'koneksi.php';

if (isset($_GET['komentarid'])) {
  $komentarid = $_GET['komentarid'];

  // Query untuk menghapus komentar berdasarkan ID
  $delete = mysqli_query($koneksi, "DELETE FROM komentarfoto WHERE komentarid='$komentarid'");

  if ($delete) {
    echo "<script>
      alert('Komentar berhasil dihapus');
      location.href='../admin/index.php';
      </script>";
  } else {
    echo "<script>
      alert('Komentar gagal dihapus');
      location.href='../admin/index.php';
      </script>";
  }
} else {
  echo "<script>
    alert('ID Komentar tidak ditemukan');
    location.href='../admin/index.php';
    </script>";
}
?>
