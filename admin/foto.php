<?php
session_start();
include '../config/koneksi.php';

// Pastikan variabel session 'status' dan 'userid' ada sebelum digunakan
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
  echo "<script>
    alert('Anda Belum Login!');
    location.href='../index.php';
    </script>";
}

// Mengambil user ID dari session
$userid = $_SESSION['userid'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Website Galeri Foto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
  body {
    background-image: url('../assets/img/bg.png'); /* Ganti dengan path ke gambar yang Anda inginkan */
    background-size: cover; /* Gambar akan menutupi seluruh halaman */
    background-repeat: no-repeat; /* Mencegah gambar diulang */
    background-attachment: fixed; /* Membuat gambar tetap saat di-scroll */
  }

  /* Jika Anda ingin agar konten dalam card tetap terlihat dengan jelas, tambahkan background transparan */
  .card {
    background-color: rgba(255, 255, 255, 0.8); /* Background putih dengan transparansi */
  }
</style>
   
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-dark">
    <div class="container">
      <img src="../assets/img/image-modified.png" width="50px" height="50px">
      <a class="navbar-brand text-white fw-bold ms-4" href="index.php">Galeri Foto</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse mt-2" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active text-white fw-bold" aria-current="page" href="home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active text-white fw-bold" aria-current="page" href="album.php">Album</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active text-white fw-bold" aria-current="page" href="foto.php">Foto</a>
          </li>
          <a href="../config/aksi_logout.php" class="btn btn-outline-danger position-absolute end-0">Keluar</a>
      </div>
    </div>
  </nav>

  <div class="container">
      <div class="col-md-12">
        <div class="card mt-5">
          <div class="card-header bg-warning fw-bold">Tambah Album</div>
          <div class="card-body">
            <form action="../config/aksi_foto.php" method="POST" enctype="multipart/form-data">
              <label class="form-label">Judul Foto</label>
              <input type="text" name="judulfoto" class="form-control" required>
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsifoto" required></textarea>
              <label class="form-label">Album</label>
              <select class="form-control" name="albumid" required>
                <?php
                // Mengambil data album sesuai dengan user ID yang login
                $sql_album = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                while ($data_album = mysqli_fetch_array($sql_album)) { ?>
                  <option value="<?php echo $data_album['albumid'] ?>"><?php echo $data_album['namaalbum'] ?></option>
                <?php } ?>
              </select>
              <label class="form-label">File</label>
              <input type="file" class="form-control" name="lokasifile" required>
              <button type="submit" class="btn btn-warning mt-2" name="tambah">Tambah Data</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="card mt-5">
          <div class="card-header bg-warning text-center fw-bold">Data Galeri Foto</div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Foto</th>
                  <th>Nama Foto</th>
                  <th>Deskripsi</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $sql = mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid'");
                while ($data = mysqli_fetch_array($sql)) {
                  ?>
                  <tr>
                    <td><?php echo $no++ ?></td>
                    <td><img src="../assets/img/<?php echo $data['lokasifile'] ?>" width="100"></td>
                    <td><?php echo $data['judulfoto'] ?></td>
                    <td><?php echo $data['deskripsifoto'] ?></td>
                    <td><?php echo $data['tanggalunggah'] ?></td>
                    <td>

                      <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#edit<?php echo $data['fotoid'] ?>">
                        Edit
                      </button>


                      <div class="modal fade" id="edit<?php echo $data['fotoid'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form action="../config/aksi_foto.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                                <label class="form-label">Judul Foto</label>
                                <input type="text" name="judulfoto" value="<?php echo $data['judulfoto'] ?>"
                                  class="form-control" required>
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsifoto"
                                  required><?php echo $data['deskripsifoto']; ?></textarea>
                                <label class="form-label">Album</label>
                                <select class="form-control" name="albumid">
                                  <?php
                                  $sql_album = mysqli_query($koneksi, "SELECT * FROM album  WHERE userid='$userid'");
                                  while ($data_album = mysqli_fetch_array($sql_album)) { ?>
                                    <option <?php if ($data_album['albumid'] == $data['albumid']) { ?> selected="selected"
                                      <?php } ?> value="<?php echo $data_album['albumid'] ?>">
                                      <?php echo $data_album['namaalbum'] ?></option>
                                  <?php } ?>
                                </select>
                                <div class="row">
                                  <label class="form-label">Foto</label>
                                  <div class="col-md-4">
                                    <img src="../assets/img/<?php echo $data['lokasifile'] ?>" width="100">
                                  </div>
                                  <div class="col-md-8">
                                    <label class="form-label">Ganti File</label>
                                    <input type="file" class="form-control" name="lokasifile">
                                  </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="edit" class="btn btn-warning">Edit data</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#hapus<?php echo $data['fotoid'] ?>">
                        Hapus
                      </button>

                      <div class="modal fade" id="hapus<?php echo $data['fotoid'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form action="../config/aksi_foto.php" method="POST">
                                <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                                Apakah anda yakin akan menghapus data <strong> <?php echo $data['judulfoto'] ?> </strong>
                                ?

                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="hapus" class="btn btn-danger">Hapus data</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

  </div>

  <footer class="mt-5">
    <div style="background-color: #333; color: #fff; padding: 20px; text-align: center;">
        <p style="margin: 0; padding: 0;">
            <strong>Contact</strong><br>
            WhatsApp: 085624742917
        </p>
        <p style="margin: 10px 0; padding: 0;">
            <strong>Follow us:</strong>
        </p>
        <div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 15px;">
            <a href="https://www.tiktok.com/@afiffadillah690" style="color: #fff; text-decoration: none;">Tiktok</a>
            <a href="https://www.instagram.com/afif_fadill17/" style="color: #fff; text-decoration: none;">Instagram</a>
        </div>
        <p style="margin: 0;">&copy; Ujikom Galeri Foto 2024 | Afif Fadillah</p>
    </div>
</footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>
