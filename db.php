<?php
    function dbconnect(){
        $hostName = "localhost";
        $userName = "root";
        $userPass = "";
        $dbName = "apartment_system";
        $conn = mysqli_connect($hostName,$userName,$userPass,$dbName);
        if(!$conn){
            die("Connection Failed: " . mysqli_connect_error());
        }
        return $conn;
    }
    
?>