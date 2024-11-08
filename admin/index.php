<?php
session_start();
include '../config/koneksi.php';
$userid = $_SESSION['userid'];
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
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
          <a href="home.php" class="nav-link text-white fw-bold">Home</a>
          <a href="foto.php" class="nav-link text-white fw-bold">Foto</a>
          <a href="Album.php" class="nav-link text-white fw-bold">Album</a>
          <a href="../index.php" class="btn btn-outline-danger position-absolute end-0">Keluar</a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <div class="row">
      <?php
      $query = mysqli_query($koneksi, "SELECT * FROM foto INNER JOIN user ON foto.userid=user.userid INNER JOIN album ON foto.albumid=album.albumid");
      while ($data = mysqli_fetch_array($query)) {
        ?>
        <div class="col-md-3 d-flex justify-content-center align-items-stretch">
          <div class="card mb-4" style="width: 18rem;">
            <a class="" type="button" data-bs-toggle="modal" data-bs-target="#komentar<?php echo $data['fotoid'] ?>">
              <img src="../assets/img/<?php echo $data['lokasifile'] ?>" class="card-img-top"
                title="<?php echo $data['judulfoto'] ?>" style="height: 30rem;">
            </a>
            <div class="card-footer text-center">
              <?php
              $fotoid = $data['fotoid'];
              $ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
              if (mysqli_num_rows($ceksuka) == 1) { ?>
                <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" type="submit" name="suka"><i
                    class="fa fa-heart" style="color: red"></i></a>
              <?php } else { ?>
                <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>"type="submit" name="suka"><i
                    class="fa-regular fa-heart" style="color: red;"></i></a>
              <?php }
              $like = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid'");
              echo mysqli_num_rows($like) . ' Suka';
              ?>
              <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#komentar<?php echo $data['fotoid'] ?>"><i
                  class="fa-regular fa-comment" style="color: blue;"></i></a>
              <?php
              $jmlkomen = mysqli_query($koneksi, "SELECT * FROM komentarfoto WHERE fotoid='$fotoid'");
              echo mysqli_num_rows($jmlkomen) . ' Komentar';
              ?>
            </div>
          </div>

          <div class="modal fade" id="komentar<?php echo $data['fotoid'] ?>" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-8">
                      <img src="../assets/img/<?php echo $data['lokasifile'] ?>" class="card-img-top"
                        title="<?php echo $data['judulfoto'] ?>">
                    </div>
                    <div class="col-md-4">
                      <div class="m-2">
                        <div class="overflow-auto">
                          <div class="sticky-top">
                            <strong><?php echo $data['judulfoto'] ?></strong><br>
                            <span class="badge bg-secondary"><?php echo $data['userid'] ?></span>
                            <span class="badge bg-secondary"><?php echo $data['username'] ?></span>
                            <span class="badge bg-primary"><?php echo $data['namaalbum'] ?></span>
                          </div>
                          <hr>
                          <p align="left">
                            <?php echo $data['deskripsifoto'] ?>
                          </p>
                          <hr>
                          <?php
                        $komentar = mysqli_query($koneksi, "SELECT user.username, user.userid, komentarfoto.isikomentar, komentarfoto.komentarid FROM komentarfoto INNER JOIN user ON komentarfoto.userid=user.userid WHERE fotoid='$fotoid'");
                          while ($row = mysqli_fetch_array($komentar)) {
                            ?>
                            <p align="left">
                              <strong><?php echo $row['username'] ?></strong>
                              <?php echo $row['isikomentar'] ?>
                              <?php 
                              // Check if the logged-in user is the author of the comment
                              if ($row['userid'] == $userid) { ?>
                                <a href="../config/hapus_komentar.php?komentarid=<?php echo $row['komentarid'] ?>"
                                  class="text-danger ms-2" onclick="return confirm('Yakin ingin menghapus komentar ini?')">
                                  Hapus
                                </a>
                              <?php } ?>
                            </p>
                          <?php } ?>
                          
                          <hr>
                          <div class="sticky-bottom">
                            <form action="../config/proses_komentar.php" method="POST">
                              <div class="input-group">
                                <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                                <input type="text" name="isikomentar" class="form-control" placeholder="Tambah Komentar">
                                <div class="input-group-prepend">
                                  <button type="submit" name="kirimkomentar"
                                    class="btn btn-outline-primary">Kirim</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>