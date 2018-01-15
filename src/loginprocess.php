<?php
header("Content-Type: text/html; charset=utf-8");
if(!isset($_POST['username'])){  
    exit('非法访问!');  
}
require_once 'SqlHelper.class.php';
$name=$_POST['username'];
$password=$_POST['password'];
$usertype=$_POST['usertype'];
//数据库校验用户身份，如果是店员要获得店员号
$sqlhelper = new SqlHelper ();
session_start();

switch ($usertype)
{
	case "admin" :

		$sql = "select admin_password from administrator where admin_name='$name'";		
		$res = $sqlhelper->execute_dql ( $sql );
		if ($row=mysql_fetch_assoc($res)) {			
			if (md5 ( $password ) == $row ['admin_password']) {
				$_SESSION['name']=$name;
				$_SESSION['usertype']='admin';
				$_SESSION['shop']=0;
				header ( "location:admin.php" );	
				exit;			
			}
			else header("location:index.php?errno='密码不正确'");
		}
    else header("location:index.php?errno='账号不正确'");
		break;
	case "employ" :
		$sql1 = "select employ_password,employ_id,employ_shop from employee where employ_name='$name'";
		$res=$sqlhelper->execute_dql ( $sql1);
		if ($row=mysql_fetch_assoc($res)) {
			if (md5 ( $password ) ==$row["employ_password"] ){
				$_SESSION['name']=$name;
				$_SESSION['usertype']='employ';
                $_SESSION['shop']=$row["employ_shop"];
				header( "Location:employ.php?" );	
			}
			else header("location:index.php?errno='密码不正确'");
				
		}
    else header("location:index.php?errno='账号不正确'");
		break;
	default :
		break;
}	
//$sqlhelper->close_connect();	
// $conn=$sqlhelper->
// if ($usertype=="admin") {
// 	header("location:admin.php");
// 	exit();
// }elseif ($usertype=="employ")
// {
// 	header("location:employ.php");
// 	exit();
// }
?>