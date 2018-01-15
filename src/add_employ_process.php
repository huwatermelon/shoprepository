<?php
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
require_once 'SqlHelper.class.php';
$employ_name=$_POST['employname'];
$shopid=$_POST['shopid'];

$employ_password=$_POST['employpassword'];
$employ_password2=$_POST['employpassword2'];
$employ_email=$_POST['employemail'];
if(empty($employ_name))
header("location:add_employ.php?errno=账号不能为空");
elseif(empty($shopid))
header("location:add_employ.php?errno=必须选择分店");
elseif(empty($employ_password))
header("location:add_employ.php?errno=必须输入密码");
elseif(empty($employ_password2))
header("location:add_employ.php?errno=必须输入确认密码");
elseif(empty($employ_email))
$employ_email="无";
elseif($employ_password2!=$employ_password)
	header("location:add_employ.php?errno=两次输入密码不一致");
else
{
$sqlhelper=new SqlHelper();
	$sql2="select * from employee where employ_name='$employ_name'";
	$res2=$sqlhelper->execute_dql($sql2);
	if(0!=mysql_num_rows($res2))
	header("location:add_employ.php?errno=用户名已存在");
	else{
		
		$sql="insert into employee(employ_name,employ_password,employ_email,employ_regtime,employ_shop) values('$employ_name',md5('$employ_password'),'$employ_email',now(),$shopid)";
		if(1==$sqlhelper->execute_dml($sql))
		   header("location:admin.php?regsucc=1");
		}
}
