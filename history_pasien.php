<?php
require_once 'include/dbh.inc.php';
require_once 'include/pasien.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
        .sm-link{
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
    <div class="container-lg my-lg-5 py-5 my-2">
        <div class="d-flex row justify-content-lg-between justify-content-md-around align-items-center">
            <div class="d-flex justify-content-md-start col-lg-2 col-md-3 col-3 ps-lg-4 ps-md-3 pe-0">
                <a href="daftar.php" class="link-danger icon-link icon-link-hover link-underline link-underline-opacity-0 link-opacity-75-hover sm-link mt-5 mb-4" style="--bs-icon-link-transform: translate3d(-.250rem, 0, 0);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>Return
                </a>
            </div>
            <!-- <div class="d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
                </svg>
            </div> -->
            <div class="d-flex justify-content-md-center col-lg-8 col-md-6 col-6 ps-lg-0 ps-md-0 ps-3 pe-0">
                <h3 class="text-center fs-header mt-5 mb-4">Clinic Registration History</h3>
            </div>
            <!-- logut button -->
            <div class="d-flex justify-content-end col-lg-2 col-md-3 col-3 ps-0 pe-3">
                <form action="include/logout.inc.php" method="post">
                    <button class="btn btn-danger mt-5 mb-4 sm-link" type="submit">Logout</button>
                </form>
            </div>
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
                        <th class="text-center fs-md fs-sm" scope="col">Pasient Name</th>
                        <th class="text-center fs-md fs-sm" scope="col">Birth Date</th>
                        <th class="text-center fs-md fs-sm" scope="col">Treatment Date</th>
                        <th class="text-center fs-md fs-sm" scope="col">Diagnose</th>
                        <th class="text-center fs-md fs-sm" scope="col">Medicine</th>
                        <th class="text-center fs-md fs-sm" scope="col">Action</th>
                        <th class="text-center fs-md fs-sm" scope="col">Description</th>
                        <th class="text-center fs-md fs-sm" scope="col">Complaints</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <!-- display semua data di dalem table -->
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
                        <td class="text-center fs-md fs-sm"><?php echo $row['nama']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo date_format($lahir, "j/n/Y"); ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo date_format($berobat, "j/n/Y"); ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['diagnosa']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['obat']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['tindak']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['keterangan']; ?></td>
                        <td class="text-center fs-md fs-sm"><?php echo $row['keluhan']; ?></td>
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