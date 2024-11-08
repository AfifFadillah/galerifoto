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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
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
                    <a href="Foto.php" class="nav-link text-white fw-bold">Foto</a>
                    <a href="Album.php" class="nav-link text-white fw-bold">Album</a>
                    <a href="../index.php" class="btn btn-outline-danger position-absolute end-0">Keluar</a>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
    <h4 class="mb-3">Album :</h4>
    <div class="d-flex flex-wrap gap-2">
        <?php
        $album = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
        while ($row = mysqli_fetch_array($album)) { ?>
            <a href="home.php?albumid=<?php echo $row['albumid'] ?>" 
               class="btn btn-outline-danger rounded-pill px-3 py-2"><?php echo $row['namaalbum'] ?>
            </a>
        <?php } ?>
    </div>
        
        <div class="row">
            <?php
            if (isset($_GET['albumid'])) {
                $albumid = $_GET['albumid'];
                $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid' AND albumid='$albumid'");
                while ($data = mysqli_fetch_array($query)) { ?>
                    <div class="col-md-3 mt-3">
                        <div class="card">
                        <img src="../assets/img/<?php echo $data['lokasifile'] ?>" class="card-img-top" title="<?php echo $data['judulfoto'] ?>" style="height: 30rem;">
                            <div class="card-footer text-center">
                                <?php
                                $fotoid = $data['fotoid'];
                                $ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
                                if (mysqli_num_rows($ceksuka) == 1) { ?>
                                    <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" type="submit" name="suka"><i class="fa fa-heart" style="color: red;"></i></a>
                                <?php } else { ?>
                                    <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" type="submit" name="suka"><i class="fa-regular fa-heart" style="color: red;"></i></a>
                                <?php }
                                $like = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid'");
                                echo mysqli_num_rows($like) . ' Suka';
                                ?>
                                <a href=""><i class="fa-regular fa-comment"></i></a> 0 komentar
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid'");
                while ($data = mysqli_fetch_array($query)) { ?>
                    <div class="col-md-3 mt-3">
                        <div class="card">
                            <img src="../assets/img/<?php echo $data['lokasifile'] ?>" class="card-img-top" title="<?php echo $data['judulfoto'] ?>" style="height: 30rem;">
                            <div class="card-footer text-center">
                                <?php
                                $fotoid = $data['fotoid'];
                                $ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
                                if (mysqli_num_rows($ceksuka) == 1) { ?>
                                    <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" type="submit" name="suka"><i class="fa fa-heart" style="color: red;"></i></a>
                                <?php } else { ?>
                                    <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" type="submit" name="suka"><i class="fa-regular fa-heart" style="color: red;"></i></a>
                                <?php }
                                $like = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid'");
                                echo mysqli_num_rows($like) . ' Suka';
                                ?>
                                <a href=""><i class="fa-regular fa-comment"></i></a> 0 komentar
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
