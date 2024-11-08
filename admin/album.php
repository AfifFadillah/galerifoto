<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['status'] != 'login') {
  echo "<script>
    alert('Anda Belum Login!');
    location.href='../index.php';
    </script>";
}
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
      <a class="navbar-brand text-white fw-bold ms-4" href="index.php"> Galeri Foto</a>
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
        </ul>
        <a href="../config/aksi_logout.php" class="btn btn-outline-danger position-absolute end-0">Keluar</a>
      </div>
    </div>
  </nav>

  <div class="container">
      <div class="col-md-12">
        <div class="card mt-5">
          <div class="card-header bg-warning text-dark fw-bold">Tambah Album</div>
          <div class="card-body bg-white">
            <form action="../config/aksi_album.php" method="POST">
              <label class="form-label">Nama Album</label>
              <input type="text" name="namaalbum" class="form-control" required>
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsi" required></textarea>
              <label class="form-label">Tanggal</label>
              <input type="date" name="tanggaldibuat" class="form-control" required>
              <button type="submit" class="btn btn-warning mt-2" name="tambah">Tambah Data</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="card mt-5">
          <div class="card-header text-center bg-warning text-dark fw-bold">Data Album</div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama Album</th>
                  <th>Deskripsi</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $userid = $_SESSION['userid'];
                $sql = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                while ($data = mysqli_fetch_array($sql)) {
                  ?>
                  <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $data['namaalbum'] ?></td>
                    <td><?php echo $data['deskripsi'] ?></td>
                    <td><?php echo $data['tanggaldibuat'] ?></td>
                    <td>

                      <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#edit<?php echo $data['albumid'] ?>">
                        Edit
                      </button>

                      <div class="modal fade" id="edit<?php echo $data['albumid'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form action="../config/aksi_album.php" method="POST">
                                <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>">
                                <label class="form-label">Nama Album</label>
                                <input type="text" name="namaalbum" value="<?php echo $data['namaalbum'] ?>"
                                  class="form-control" required>
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi"
                                  required><?php echo $data['deskripsi']; ?></textarea>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="edit" class="btn btn-warning">Edit data</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#hapus<?php echo $data['albumid'] ?>">
                        Hapus
                      </button>

                      <div class="modal fade" id="hapus<?php echo $data['albumid'] ?>" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form action="../config/aksi_album.php" method="POST">
                                <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>">
                                Apakah anda yakin akan menghapus data <strong><?php echo $data['namaalbum'] ?></strong>?
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