<?php

$hostname = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "klinik";

$conn = mysqli_connect($hostname, $dbusername, $dbpassword, $dbname);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}