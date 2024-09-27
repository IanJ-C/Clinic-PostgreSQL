<?php
session_start();
require_once 'include/dbh.inc.php';

// kalo access user bukan aadmin/dokter, redirect ke page daftar
if($_SESSION['access'] != "admin" && $_SESSION['access'] != "dokter"){
    header('location: daftar.php');
}

$query = "";
$tglBerobat = "";
$namaPasien = "";
// filter berdasarkan tanggal berobat
if(isset($_POST['searchTgl'])){
    $tglBerobat = $_POST['tglBerobat'];
    $query = "SELECT * FROM daftar WHERE berobat = '$tglBerobat' ";
}
// filter berdasarkan bulan berobat
elseif(isset($_POST['searchPasien'])){
    $namaPasien = $_POST['namaPasien'];
    $query = "SELECT * FROM daftar WHERE nama = '$namaPasien' ";
}
// clear search filter
elseif(isset($_POST['clear'])){
    unset($_POST['searchTgl']);
    unset($_POST['searchPasien']);
    $query = "SELECT * FROM daftar";
}else{
// display semua data
    $query = "SELECT * FROM daftar";
}
// execute query
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/jpg" href="image/CP-icon.jpg">
    <title>History</title>
    <style>
    @media(max-width: 1200px){
        .fs-md{
            font-size: 0.9rem;
        }
    }
    @media(max-width: 768px){
        .fs-header{
            font-size: 1rem;
        }
        .link-sm{
            font-size: 0.75rem;
        }
    }
    @media(max-width: 500px){
        .fs-sm{
            font-size: 0.7rem;
        }
    }
    </style>
</head>
<body>
    <div class="container-lg my-lg-5 p-5 my-3">
        <div class="d-flex row justify-content-between align-items-center">
            <div class="d-flex justify-content-center col-lg-2 col-md-2 col-2">
                <a href="#offcanvas" class="btn btn-secondary" data-bs-toggle="offcanvas" role="button" aria-controls="offcanvas">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel mb-1" viewBox="0 0 16 16">
                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
                    </svg>
                </a>
            </div>
            <div class="offcanvas offcanvas-start pt-3" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
                <div class="d-flex justify-content-center offcanvas-header pb-0">
                    <h3 class="offcanvas-title my-4" id="offcanvasLabel">Filter Data</h3>
                </div>
                <div class="offcanvas-body">
                    <div class="row">
                        <form action="history_dokter.php" method="post">
                            <div class="col-lg-12">
                                <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#searchTanggal" aria-expanded="false" aria-controls="searchTanggal">
                                    Filter by Treatment Date
                                </button>
                                <div class="collapse mt-3" id="searchTanggal">
                                    <div class="input-group">
                                        <input type="date" name="tglBerobat" class="form-control" aria-label="Search Tanggal" aria-describedby="tanggalSearch">
                                        <button type="submit" name="searchTgl" class="btn btn-success" id="tanggalSearch">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search mb-1" viewBox="0 0 16 16">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-secondary mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#searchNama" aria-expanded="false" aria-controls="searchNama">
                                    Filter by Pasient Name
                                </button>
                                <div class="collapse mt-3" id="searchNama">
                                    <div class="input-group">
                                        <input type="text" name="namaPasien" class="form-control" placeholder="Pasient Name" aria-label="Search Nama" aria-describedby="namaSearch">
                                        <button type="submit" name="searchPasien" class="btn btn-success" id="namaSearch">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search mb-1" viewBox="0 0 16 16">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" name="clear" class="btn btn-success mt-3">Clear Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center col-lg-8 col-md-8 col-8">
                <h3 class="text-center fs-header mt-5 mb-4">Clinic Registration History</h3>
            </div>
            <div class="d-flex justify-content-center col-lg-2 col-md-2 col-2">
                <form action="include/logout.inc.php" method="post">
                    <button class="btn btn-danger link-sm mt-5 mb-4" type="submit">Logout</button>
                </form>
            </div>
            <?php
                $sukses = "";
                $error = "";
                if(!empty($_SESSION["sukses"])){
                    $sukses = $_SESSION["sukses"];
                    echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-success alert-dismissable fade show" role="alert">'.$sukses.
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                    unset($_SESSION['sukses']);
                }else if(!empty($_SESSION["error"])){
                    $error = $_SESSION["error"];
                    echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-danger alert-dismissable fade show" role="alert">'.$error.
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                    unset($_SESSION['error']);
                }
            ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <!-- table header -->
                    <tr>
                        <th class="text-center fs-md fs-sm" scope="col">Num.</th>
                        <th class="text-center fs-md fs-sm" scope="col">Time</th>
                        <th class="text-center fs-md fs-sm" scope="col">ID</th>
                        <th class="text-center fs-md fs-sm" scope="col">Company</th>
                        <th class="text-center fs-md fs-sm" scope="col">Department</th>
                        <th class="text-center fs-md fs-sm" scope="col">Pasient Name</th>
                        <th class="text-center fs-md fs-sm" scope="col">Birth Date</th>
                        <th class="text-center fs-md fs-sm" scope="col">Treatment Date</th>
                        <th class="text-center fs-md fs-sm" scope="col">Diagnose</th>
                        <th class="text-center fs-md fs-sm" scope="col">Medical</th>
                        <th class="text-center fs-md fs-sm" scope="col">Action</th>
                        <th class="text-center fs-md fs-sm" scope="col">Description</th>
                        <th class="text-center fs-md fs-sm" scope="col">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <!-- display semua data -->
                    <?php
                    $antrian = 1;
                    while($row = mysqli_fetch_assoc($result)){
                        $waktu = date_create($row['waktu']);
                        $lahir = date_create($row['lahir']);
                        $berobat = date_create($row['berobat']);
                    ?>
                        <td class="text-center fs-md fs-sm"><?php echo $antrian++; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo date_format($waktu,"G:i"); ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['nik']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['perusahaan']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['dept']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['nama']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo date_format($lahir, "j/n/Y"); ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo date_format($berobat, "j/n/Y"); ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['diagnosa']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['obat']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['tindak']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['keluhan']; ?></td>
                        <td class="text-center">
                            <?php
                                echo "<a class='btn btn-warning fs-md fs-sm' href='./edit.php?id=".$row['id']."'>Edit</a>";
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>