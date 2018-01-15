<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
$shopnumber=$_SESSION['shopnumber'];

require_once 'SqlHelper.class.php';
echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
$sqlhelper=new SqlHelper();
if(!empty($_GET['erro']))
echo "<font color='red'>两次输入密码不一样</font>";
if(!empty($_POST['employname']))
{
	$employshop=$_POST['shopid'];
	$employname=$_POST['employname'];
	$employpassword=$_POST['employpassword'];
	$employpassword2=$_POST['employpassword2'];
	$employemail=$_POST['employemail'];
	if($employpassword==$employpassword2)
	{
	
	$oldemployid=$_SESSION['oldemployid'];
	$sql="update employee set employ_shop=$employshop,employ_name='$employname',employ_password=md5($employpassword),employ_email='$employemail' where employ_id=$oldemployid";
	
	$sqlhelper->execute_dml($sql);
	echo "<a href='employeemanage.php'>修改成功</a>";
	}
	else
	header('location:employ_alterbyadmin.php?erro=1');
}
else
{
$oldemployid=$_GET['id'];
$oldemployname=$_GET['employname'];
$_SESSION['oldemployid']=$oldemployid;
echo "<form action='employ_alterbyadmin.php' method='post'>";
 echo   "选择分店:<select name='shopid'>";
for($i=1;$i<=$shopnumber;$i++)
{
echo "<option value='$i'>{$i}分店</option>";
}
echo "店员ID：".$_GET['id']."&nbsp&nbsp原名：".$oldemployname."";
echo "</select><br/>";
echo "新　　名:<input type='text' value='$oldemployname' name='employname'/><br/>";
echo "密　　码:<input type='password' name='employpassword'/><br/>";
echo "密码确认:<input type='password' name='employpassword2'><br/>";
echo "邮　　箱:<input type='email' name='employemail'/><br/>";
//echo "<input 　type='submit' width='86px' value='修改'/>";
echo "<button  type='submit' class='mybtn'>修改</button><br>";
echo "</form>";
echo "<a href='employeemanage.php'><button class='mybtn'>取消</button></a><br>";

}
?>
