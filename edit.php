<?php
// error_reporting(E_ALL);
// ini_set('display_errors',1);
session_start();
$error = "";
$sukses = "";
require_once('include/dbh.inc.php');

if(!isset($_GET['id'])){
    die("Data tidak dapat ditemukan");
}
// kalo access user bukan admin/dokter, redirect ke page daftar
if($_SESSION['access'] != "admin" && $_SESSION['access'] != "doctor"){
    header('location: daftar.php');
}


$id = $_GET['id'];
// select semua data dr table daftar dimana value row  id == id
$select = "SELECT * FROM daftar WHERE id = '$id' ";
$result = pg_query($conn, $select);
// kalo jumlah row lbh kecil dr 1, id ga ada di database
if(pg_num_rows($result) < 1){
    $error = "Data tidak ditemukan di dalam database";
}
// fetch data
$data = pg_fetch_assoc($result);

$perusahaan = "";
$nama = "";
$dept = "";
$diagnosa = "";
$obat = "";
$tindak = "";
$keterangan = "";
$keluhan = "";

$perusahaan = is_bool($data['perusahaan']) == 1 ? "" : $data['perusahaan'];
$nama = is_bool($data['nama']) == 1 ? "" : $data['nama'];
$dept = is_bool($data['dept']) == 1 ? "" : $data['dept'];
$diagnosa = is_bool($data['diagnosa']) == 1 ? "" : $data['diagnosa'];
$obat = is_bool($data['obat']) == 1 ? "" : $data['obat'];
$tindak = is_bool($data['tindak']) == 1 ? "" : $data['tindak'];
$keterangan = is_bool($data['keterangan']) == 1 ? "" : $data['keterangan'];
$keluhan = is_bool($data['keluhan']) == 1 ? "" : $data['keluhan'];

// kalo id ada & edit di klik, store semua value post ke local variable
if(isset($_GET['id']) && isset($_POST['edit'])){
    $id = $_GET['id'];
    $waktu = $_POST["waktu"];
    $nama = $_POST["nama"];
    $lahir = $_POST["lahir"];
    $berobat = $_POST["berobat"];
    $diagnosa = $_POST["diagnosa"];
    $obat = $_POST["obat"];
    $tindak = $_POST["tindak"];
    $perusahaan = $_POST["perusahaan"];
    $nik = $_POST["nik"];
    $dept = $_POST["dept"];
    $keterangan = $_POST["keterangan"];
    $keluhan = $_POST["keluhan"];
    // update query buat update table daftar
    $updateDaftar = "UPDATE daftar SET
                waktu = '$waktu',
                nama = '$nama',
                lahir = '$lahir',
                berobat = '$berobat',
                diagnosa = '$diagnosa',
                obat = '$obat',
                tindak = '$tindak',
                perusahaan = '$perusahaan',
                nik = '$nik',
                dept = '$dept',
                keterangan = '$keterangan',
                keluhan = '$keluhan'
                WHERE id = '$id' ";
    // execute query, kalo berhasil display success message
    if(pg_query($conn, $updateDaftar) == true){
        $sukses = "Data pasien berhasil di update";
    }else{
        // kalo gagal display error message
        $error = "Data paseien gagal di update. Error: " . pg_last_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Edit</title>
    <style>
    @media(max-width: 768px){
        .fs-sm{
            font-size: 0.85rem;
        }
    }
    @media(max-width: 500px){
        .header-sm{
            font-size: 1.25rem;
        }
    }
    </style>
</head>
<body>
    <div class="container my-lg-5 p-5">
        <div class="d-flex row justify-content-center align-items-center">
            <div class="col-lg-1 col-md-1 col-2 p-0">
                <a href="history.php" class="link-danger icon-link icon-link-hover link-underline link-underline-opacity-0 link-opacity-75-hover mt-lg-5 mb-4 fs-sm" style="--bs-icon-link-transform: translate3d(-.125rem, 0, 0);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>Return
                </a>
            </div>
            <div class="col-lg-7 col-md-11 col-10 ps-0">
                <h3 class="text-center mt-lg-5 mb-4 fs-header header-sm">Edit Pasient Data</h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 card">
                <div class="card-body">
                    <form action="./edit.php?id=<?php echo $id ?>" method="post">
                        <?php
                        // kalo error ga kosong, display error message
                        if($error != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-danger alert-dismissable fade show" role="alert">'.$error.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        // kalo sukses ga kosong, display success message
                        elseif($sukses != "") {
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-success alert-dismissable fade show" role="alert">'.$sukses.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }

                        ?>
                        <div class="row px-sm-4 px-0 my-sm-3 my-2">
                            <!-- input field tanggal berobat -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="berobat" class="form-label fs-sm">Treatment Date</label>
                                <input type="date" class="form-control fs-sm" id="berobat" name="berobat" value="<?php echo $data['berobat'] ?>">
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-sm-3 mb-2">
                            <!-- input field waktu -->
                            <div class="col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-2">
                                <label for="waktu" class="form-label fs-sm">Time</label>
                                <input type="time" class="form-control fs-sm" id="waktu" name="waktu" value="<?php echo $data['waktu'] ?>">
                            </div>
                            <!-- input field nik -->
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <label for="nik" class="form-label fs-sm">Employee ID</label>
                                <input type="number" class="form-control fs-sm" id="nik" name="nik" value="<?php echo $data['nik'] ?>">
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-sm-3 mb-2">
                            <!-- input field perusahaan -->
                            <div class="col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-2">
                                <label for="perusahaan" class="form-label fs-sm">Company</label>
                                <input type="text" class="form-control fs-sm" id="perusahaan" name="perusahaan" value="<?php echo $perusahaan ?>">
                            </div>
                            <!-- input field nama -->
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <label for="nama" class="form-label fs-sm">Full Name</label>
                                <input type="text" class="form-control fs-sm" id="nama" name="nama" value="<?php echo $nama ?>">
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-sm-3 mb-2">
                            <!-- input field tgl lahir -->
                            <div class="col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-2">
                                <label for="lahir" class="form-label fs-sm">Birth Date</label>
                                <input type="date" class="form-control fs-sm" id="lahir" name="lahir" value="<?php echo $data['lahir'] ?>">
                            </div>
                            <!-- input field departemen -->
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <label for="dept" class="form-label fs-sm">Department</label>
                                <input type="text" class="form-control fs-sm" id="dept" name="dept" value="<?php echo $dept ?>">
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-sm-3 mb-2">
                            <!-- input field diagnosa -->
                            <div class="col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-2">
                                <label for="diagnosa" class="form-label fs-sm">Diagnose</label>
                                <input type="text" class="form-control fs-sm" id="diagnosa" name="diagnosa" value="<?php echo $diagnosa ?>">
                            </div>
                            <!-- input field obat -->
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <label for="obat" class="form-label fs-sm">Medicine</label>
                                <input type="text" class="form-control fs-sm" id="obat" name="obat" value="<?php echo $obat ?>">
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-sm-3 mb-2">
                            <!-- input field tindakan -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="tindak" class="form-label fs-sm">Action</label>
                                <input type="text" class="form-control fs-sm" id="tindak" name="tindak" value="<?php echo $tindak ?>">
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-sm-3 mb-2">
                            <!-- input field keterangan -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="keterangan" class="form-label fs-sm">Description</label>
                                <input type="text" class="form-control fs-sm" id="keterangan" name="keterangan" value="<?php echo $keterangan ?>">
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 my-sm-3 my-2">
                            <!-- text area keluhan -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="keluhan" class="form-label fs-sm">Complaints</label>
                                <textarea class="form-control fs-sm" id="keluhan" name="keluhan" rows="3"><?php echo htmlspecialchars($keluhan); ?></textarea>
                            </div>
                        </div>
                        <div class="row justify-content-end px-sm-4 px-0 my-3">
                            <div class="d-grid col-6">
                                <!-- edit modal trigger -->
                                <button class="btn btn-warning fs-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="editModalLabel">Edit Pasient Data</h3>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="fs-5 fs-sm">Are you sure you want to edit this pasien data?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <!-- cancel button -->
                                                <button class="btn btn-secondary fs-sm" type="button" data-bs-dismiss="modal">Cancel</button>
                                                <!-- edit button -->
                                                <button class="btn btn-warning fs-sm" type="submit" name="edit">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>