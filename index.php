<?php
// error_reporting(E_ALL);
// ini_set('display_errors',1);
session_start();
include 'include/dbh.inc.php';
// define variable
$error = "";
$warning = "";
$user = "";
$email = "";
$passwd = "";
$_SESSION['access'] = "";
$_SESSION['email'] = "";
$_SESSION['passwd'] = "";
//cek kalo login button di click
if(isset($_POST['login'])){
    //store email, password yg di post ke variable local
    $email = $_POST['email'];
    $passwd = $_POST['passwd'];
    //select semua data dari table 'credentials' dimana email & password di database == email & password local
    $select = "SELECT * FROM `credentials` WHERE email = '$email' && passwd = '$passwd'";
    //execute query select
    $result = pg_query($conn, $select); 
    //fetch array dari hasil query buat ambil access
    $row = pg_fetch_assoc($result); 
    @$_SESSION['access'] = $row['access']; //store value access dari database ke session variable

    //cek jumlah row, lebih dari 0 atau ga (cek apakah data ada di database)
    if(pg_num_rows($result) > 0){
        $_SESSION['email'] = $email;
        $_SESSION['passwd'] = $passwd;
        //kalo access == admin, redirect ke page history admin
        if($_SESSION['access'] == "admin"){
            header('location: history.php');
        //kalo access == dokter, redirect ke page history dokter
        }elseif($_SESSION['access'] == "dokter"){
            header('location: history_dokter.php');
        }else{
            header('location: daftar.php');
        }
    }elseif($row['email'] != $email && $row['passwd'] != $passwd){
        $error = "Error: Username atau Password Salah";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- <link rel="icon" type="image/jpg" href="image/CP-icon.jpg"> -->
    <title>Login</title>
    <!-- <style>
        .bg{
            background-image: url(image/Kantor-CP-bg.jpg);
            /* height: 90vh; */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style> -->
</head>
<body>
    <div class="container my-5 p-5">
        <div class="row justify-content-center">
            <!-- <div class="col-lg-2 col-md-3 col-4">
                <img src="image/CP-logo.jpg" class="img-fluid text-center" alt="Logo CP">
            </div> -->
            <!-- <h3 class="text-center mt-3">Klinik Head Office Ancol</h3> -->
            <h3 class="text-center">Clinic Registration Website</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12 card mt-3">
                <div class="card-body">
                    <h3 class="text-center mt-3 mb-4">Login</h3>
                    <form action="index.php" method="post">
                        <?php
                        //kalo error ga kosong, display error message
                        if($error != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-danger alert-dismissable fade show" role="alert">'.$error.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        // kalo warning ga kosong, display warning message
                        elseif($warning != ""){
                            echo '<div class="d-flex m-4 align-items-center justify-content-between alert alert-warning alert-dismissable fade show" role="alert">'.$warning.'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.'</div>';
                        }
                        ?>
                        <!-- field input username -->
                        <div class="col-lg-12 px-4 mt-3 mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Input username">
                        </div>
                        <!-- field input password -->
                        <div class="col-lg-12 px-4 mb-4">
                            <label for="passwd" class="form-label">Password</label>
                            <input type="password" class="form-control" id="passwd" name="passwd" placeholder="Input password">
                        </div>
                        <div class="d-grid col-lg-12 px-4 mt-5 mb-3">
                            <button class="btn btn-primary" name="login" type="submit">Login</button>
                            <p class="text-center mt-3 fw-semibold">Don't have an account?
                                <a href="register.php" class="link-underline link-underline-opacity-0">click here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>