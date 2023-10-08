<?php 
    $database_name = 'mysql:host=localhost;dbname=webhoctap';
    $database_username ='root';
    $database_password ='';
    $conn =  new PDO($database_name,$database_username,$database_password);
    function create_unique_id(){
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $random = array();
        $leng = strlen($str) - 1;
        for ($i=0; $i < 20 ; $i++) { 
            $n = mt_rand(0,$leng);
            $random[]= $str[$n];
        }
        return implode($random);
    }
