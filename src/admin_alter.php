<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
if(!empty($_POST['admin_name']))
{
	require_once 'SqlHelper.class.php';
	$sqlhelper=new SqlHelper();
	$adminoldname=$_POST['admin_oldname'];
	$adminname=$_POST['admin_name'];
	$adminoripassword=$_POST['admin_oripassword'];
	$adminpassword=$_POST['admin_password'];
	$adminpassword2=$_POST['admin_password2'];
	$sql1="select admin_password from administrator where admin_name='$adminoldname'";
	$res=$sqlhelper->execute_dql($sql1);
	if($row=mysql_fetch_assoc($res)){
		if($row['admin_password']==md5($adminoripassword))
		{
			
			if($adminpassword==$adminpassword2)
			{
				$adminemail=$_POST['admin_email'];
				
				$sql="update administrator set admin_name='$adminname',admin_password=md5('$adminpassword'), admin_email='$adminemail'";
				$sqlhelper->execute_dml($sql);
				header("location:exit.php?altsucc=4");
				exit();
			}
			header("location:exit.php?altsucc=3");
			exit();
		}
		header("location:exit.php?altsucc=2");
		exit();
	}
	header("location:exit.php?altsucc=1");
	exit();
}
echo "<form action='admin_alter.php' method='POST'>";
echo "原账号：　<input type='text' name='admin_oldname'><br/>";
echo "新账号：　<input type='text' name='admin_name'><br/>";
echo "原密码：　<input type='password' name='admin_oripassword'><br/>";
echo "新密码：　<input type='password' name='admin_password'><br/>";
echo "确认密码：<input type='password' name='admin_password2'><br/>";
echo "邮箱：　　<input type='email' name='admin_email'><br/>";
echo "　<input type='submit' name='login'>　";
echo "　<input type='reset' name='cancel'>　";
echo "　<a href='admin.php'>取消</a>　";
echo "</form>";