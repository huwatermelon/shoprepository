<?php
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
$employ_id=$_GET['id'];
$sql="delete from employee where employ_id=$employ_id";
$sql2="update employee set employ_id = employ_id-1 where employ_id >$employ_id";
$sql3="ALTER TABLE employee AUTO_INCREMENT=1";
$sqlhelper->execute_dml($sql);
$sqlhelper->execute_dml($sql2);
$sqlhelper->execute_dml($sql3);
header('location:employeemanage.php');
