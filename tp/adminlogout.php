<?php
include 'connect.php';
setcookie('giasu_id', '', time() - 1, '/');
header('location:../admin/dangnhap.php');
