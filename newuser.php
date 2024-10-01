<?php
session_start();
$error = "";
$sukses = "";
// kalo access bukan admin/doctor, redirect ke page daftar
if($_SESSION['access'] != "admin" && $_SESSION['access'] != "doctor"){
    header('location: daftar.php');
}
// kalo access == doctor, redirect ke page history dokter
elseif($_SESSION['access'] == "doctor"){
    header('location: history_dokter.php');
}
// cek kalo button buat user di klik
if(isset($_POST["regis"])){
    $error = "";
    include 'include/dbh.inc.php';
    // store semua value yg di post ke local variable
    $email = $_POST["email"];
    $passwd = $_POST["passwd"];
    $access = $_POST["access"];
    // select semua data dari table credentials dimana value row email == email & execute query
    $select = "SELECT * FROM credentials WHERE email = '$email' ";
    $result = pg_query($conn, $select);
    // cek kalo email tidak valid, display error message
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Email tidak valid";
    }else{
        // kalo jumlah row lbh dr 0, email sudah terdaftar
        if(pg_num_rows($result) > 0){
            $error = "User sudah terdaftar";
        }else{
            // sql statement buat insert ke table credentials
            $sql = "INSERT INTO credentials (email, passwd, access) VALUES ('$email','$passwd','$access')";
            // execute query, kalo berhasil display sukses message
            if(pg_query($conn, $sql) == true){
                $sukses = "User baru berhasil didaftarkan";
            }else{
                $error = "Error: " . pg_last_error($conn);
            }
        }
    }
    pg_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Buat User Baru</title>
</head>
<body>
    <div class="container my-5 p-5">
        <div class="d-flex row justify-content-between align-items-center">
            <div class="col-lg-1 col-md-1 col-2 pe-1 ps-lg-3 ps-md-3 ps-0">
                <a href="manage_user.php" class="link-danger icon-link icon-link-hover link-underline link-underline-opacity-0 link-opacity-75-hover mt-4 mb-5" style="--bs-icon-link-transform: translate3d(-.250rem, 0, 0);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>Return
                </a>
            </div>
            <div class="col-lg-6 col-md-10 col-8">
                <h3 class="text-center mt-4 mb-5 fs-lg-3">Create New User</h3>
            </div>
            <div class="col-lg-1 col-md-1 col-2"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 card">
                <div class="card-body">
                    <form action="newuser.php" method="post">
                        <?php
                        // kalo sukses ga kosong, display success message
                        if($sukses != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-success alert-dismissable fade show" role="alert">'.$sukses.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        // kalo error ga kosong, display error message
                        else if($error != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-danger alert-dismissable fade show" role="alert">'.$error.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        ?>
                        <!-- input field email -->
                        <div class="col-lg-12 px-4 mt-3 mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <!-- input field password -->
                        <div class="col-lg-12 px-4 mb-4">
                            <label for="passwd" class="form-label">Password</label>
                            <input type="password" class="form-control" id="passwd" name="passwd">
                        </div>
                        <!-- select option access -->
                        <div class="col-lg-12 px-4 mb-4">
                            <label for="access" class="form-label">Access Type</label>
                            <select name="access" id="access" class="form-select">
                                <option selected>Choose access type</option>
                                <option value="admin">Admin</option>
                                <option value="doctor">Doctor</option>
                            </select>
                        </div>
                        <div class="d-grid col-lg-12 px-4 mt-5 mb-2">
                            <button class="btn btn-primary" name="regis" type="submit">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>