<?php
session_start();
include 'include/dbh.inc.php';
// declare variable
$error = "";
$sukses = "";
$disable = "";
$currentUser = "";
$currentPass = "";
$waktuRow = array();
// kalo session variable email ada isi, assign value ke currentUser
if(isset($_SESSION['email'])){
    $currentUser = $_SESSION['email'];
}
// kalo session variable password ada isi, assign value ke currentPass
if(isset($_SESSION['passwd'])){
    $currentPass = $_SESSION['passwd'];
}
// kalo currentUser/currentPass kosong, destroy session & redirect ke login page
if($currentUser == "" || $currentPass == ""){
    session_destroy();
    header('location: index.php');
}
// select semua data dari table credentials dimana value row email == currentUser
$checkData = "SELECT * FROM `credentials` WHERE email = '$currentUser' ";
$result = mysqli_query($conn, $checkData);
// set timezone ke jakarta, format tgl berobat ke d/m/Y
date_default_timezone_set('Asia/Jakarta');
$berobat = date('d/m/Y');
// define waktu form dibuka, convert ke unix timestamp
$start = "10:15";
$tStart = strtotime($start);
// define waktu isitirahat dimulai, convert ke unix timestamp
$breakStart = "11:45";
$tBreakStart = strtotime($breakStart);
// define waktu istirahat selesai, convert ke unix timestamp
$breakEnd = "13:00";
$tBreakEnd = strtotime($breakEnd);
// define waktu form ditutup, convert ke unix timestamp
$end = "15:45";
$tEnd = strtotime($end);
$timeSelect = "";
// algorithm biar current time selalu snap ke interval 15 menit terdekat
$time = time();
$interval = 15 * 60;
$last = $time - ($time % $interval);
$next = $last + $interval;
$waktu = date('H:i',$next);
// cek kalo current time lbh kecil dr start time, current time selalu 10:15
if($time < $tStart){
    $timeSelect = date_format(date_create("10:15"),"H:i");
}
// cek kalo current time lbh besar dr break start & lbh kecil dr break end, current time selalu 13:00
elseif($time > $tBreakStart && $time < $tBreakEnd){
    $timeSelect = date_format(date_create("13:00"),"H:i");
}
// diluar kondisi diatas waktu akan snap ke interval 15 menit terdekat dari current time
else{
    $timeSelect = $waktu;
}
// select semua data dr table credentials dimana value row email == currentUser
$select = "SELECT * FROM credentials WHERE email = '$currentUser' ";
$query = mysqli_query($conn, $select);
$getData = mysqli_fetch_assoc($query);

// select semua data dr table daftar dimana value row berobat == current date (memastikan data yg ditampilkan sesuai hari)
$selectHari = "SELECT * FROM daftar WHERE berobat = CURDATE() ";
$queryHari = mysqli_query($conn, $selectHari);
// get jumlah row dan + 1 sebagai no antrian
$rowCount = mysqli_num_rows($queryHari);
$antrian = $rowCount + 1;
// fetch data sesuai queryHari, store ke array waktuRow buat cek waktu
while($cekWaktu = mysqli_fetch_assoc($queryHari)){
    $waktuRow[] = $cekWaktu['waktu'];
}
// kalo daftar di klik, store value waktu & keluhan ke local variable
if(isset($_POST["daftar"])){
    $timeSelect = $_POST['waktu'];
    $keluhan = $_POST['keluhan'];
    // kalo field keluhan kosong, display message lengkapi data
    if(empty($_POST['keluhan'])){
        $error = "Keluhan harus dilengkapi"; 
    }
    // kalo jumlah row hari ini lebih dari 19, antrian penuh
    elseif($rowCount >= 19){
        $error = "Antrian sudah penuh";
    }
    // kalo waktu yg dipilih sudah ada di database, slot waktu terpilih
    elseif(in_array($timeSelect,$waktuRow)){
        $error = "Slot waktu sudah dipilih, silahkan pilih waktu lain";
    }
    // kalo current time lebih besar dari end time, form sudah ditutup
    elseif($time > $tEnd){
        $error = "Pendaftaran sudah ditutup untuk hari ini";
    }else{
    // kalo tidak masuk ke kondisi diatas, siapin sql statement buat insert
        $insert = "INSERT INTO `daftar` 
                (`email`, `waktu`,`nama`,`lahir`,`perusahaan`,`nik`,`dept`,`keluhan`) VALUES 
                ('$getData[email]', '$timeSelect','$getData[nama]','$getData[lahir]',
                '$getData[perusahaan]','$getData[nik]','$getData[dept]','$keluhan') ";
        // execute query, kalo true display success message
        if($conn->query($insert) == true){
            $sukses = "Registration Successful";
        }else{
            // kalo gagal display error
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/jpg" href="image/CP-icon.jpg">
    <title>Registration</title>
    <style>
        .noscroll{
            position: static;
            overflow-y: auto;
        }
        /* .bg{
            background-image: url(image/Kantor-CP-bg.jpg);
            height: 94.75vh;
            width: auto;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        } */
        #waktu option[disabled]{
            display: none;
        }
        @media(max-width: 1600px){
            /* .bg{
                background-image: url(image/Kantor-CP-bg.jpg);
                max-height: 120vh;
                width: auto;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover; */
            /* } */
        }
        @media(max-width: 1200px){
            /* .bg{
                background-image: url(image/Kantor-CP-bg.jpg);
                max-height: 94.75vh;
                width: auto;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover; */
            /* } */
        }
        @media(max-width: 768px){
            .link-sm{
                font-size: 0.85rem;
            }
            /* .bg{
                background-image: url(image/Kantor-CP-bg.jpg);
                max-height: 100vh;
                width: auto;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover; */
            /* } */
        }
        @media(max-width: 500px){
            .header-sm{
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body class="bg noscroll">
    <div class="container p-5 my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 card">
                <div class="card-body">                   
                    <div class="d-flex row justify-content-md-center justify-content-end align-items-center ms-md-4 me-md-3 mx-0">
                        <div class="col-lg-10 col-md-10 col-10 pe-0">
                            <h3 class="text-center my-md-3 mt-3 mb-0 ms-md-5 ps-md-4 header-sm">Clinic Registration Form</h3>
                        </div>
                        <div class="col-lg-2 col-md-2 col-2 ps-md-2 p-0 pt-md-0 pt-3">
                            <a href="history_pasien.php" class="icon-link icon-link-hover link-opacity-75-hover link-underline link-underline-opacity-0 link-sm" style="--bs-icon-link-transform: translate3d(.250rem, 0, 0);">
                                History
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right d-flex align-items-center" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <form action="daftar.php" method="post">
                        <?php
                        // kalo error ga kosong, display error message
                        if($error != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-danger alert-dismissable fade show" role="alert">'.$error.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        // kalo sukses ga kosong, displaay sukses message
                        elseif($sukses != "") {
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-success alert-dismissable fade show" role="alert">'.$sukses.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        ?>
                        <div class="row px-sm-4 px-0 my-3">
                            <!-- input field tanggal -->
                            <div class="col-lg-6 col-md-6 col-7">
                                <label for="berobat" class="form-label link-sm">Date</label>
                                <input type="text" class="form-control link-sm" id="berobat" name="berobat" value="<?php echo $berobat; ?>" disabled>
                            </div>
                            <!-- input field antrian -->
                            <div class="col-lg-6 col-md-6 col-5">
                                <label for="antrian" class="form-label link-sm">Queue</label>
                                <input type="number" class="form-control link-sm" id="antrian" name="antrian" value="<?php echo $antrian; ?>" disabled>
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 mb-3">
                            <!-- input field waktu -->
                            <div class="col-lg-12 col-md-12 col-12">
                                <div class="d-flex row justify-content-between">
                                    <div class="col">
                                        <label for="waktu" class="d-flex form-label link-sm">Time</label>
                                    </div>
                                </div>
                                <select name="waktu" id="waktu" class="form-select">
                                    <option selected value="<?php echo $timeSelect ?>"><?php echo $timeSelect ?></option>
                                    <?php
                                    $rowCheck = array();
                                    $timeQuery = "SELECT * FROM list_waktu";
                                    $timeResult = mysqli_query($conn,$timeQuery);
                                    $waktuQuery = "SELECT * FROM daftar WHERE berobat = CURDATE() ";
                                    $waktuResult = mysqli_query($conn, $waktuQuery);
                                    while($rowResult = mysqli_fetch_assoc($waktuResult)){
                                        $rowCheck[] = $rowResult['waktu'];
                                    }
                                    while($row = mysqli_fetch_assoc($timeResult)){
                                    ?>
                                        <option value="<?php echo $row['jam'] ?>"
                                        <?php
                                        if(in_array($row['jam'],$rowCheck) || strtotime($row['jam']) <= $time){
                                            echo "disabled = \"disabled\"";
                                        }
                                        ?>><?php echo $row['jam'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row px-sm-4 px-0 my-3">
                            <!-- text area keluhan -->
                            <div class="col-lg-12 col-md-12 col-12">
                                <label for="keluhan" class="form-label link-sm">Complaints</label>
                                <textarea class="form-control link-sm" id="keluhan" name="keluhan" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="row justify-content-end px-sm-4 px-0 mt-4 mb-3">
                            <div class="d-grid col-6">
                                <button class="btn btn-success link-sm" name="daftar" type="submit">Register</button>
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