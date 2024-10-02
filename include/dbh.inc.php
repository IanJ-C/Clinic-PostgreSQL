<?php
$hostname = "localhost";
$username = "u9805alf_medic";
$password = "recordclinic";
$database = "u9805alf_medic";
$conn = mysqli_connect($hostname, $username, $password, $database);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
};

