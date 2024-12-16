<?php
$host = "localhost" ;
$port = "5432" ;
$dbname = "blog" ;
$user = "postgres" ;
$password = "070911";





try{
    $pdo = new PDO("pgsql:host = $host;port=$port ;dbname= $dbname" , $user , $password) ;
    // echo"succesfully conected ";
}catch(PDOException $e){
    die("error : " . $e->getMessage()) ;
}





?>