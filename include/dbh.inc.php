<?php
$conn = pg_connect("
        user=postgres.cssgkvsiazqwtiifkgfw 
        password=vqAeAlmpuA3zBxSz 
        host=aws-0-ap-southeast-1.pooler.supabase.com 
        port=6543 
        dbname=postgres
    ");

if(!$conn){
    die("Connection failed: " . pg_last_error());
};

