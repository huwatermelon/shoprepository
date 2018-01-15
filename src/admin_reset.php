<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
if($_SESSION['usertype']!='admin'){echo "非管理员";exit;}
 require_once 'SqlHelper.class.php';
echo "<form style='position:absolute;top:100;left:200;' name='adminpass' method='post' ><input type='password' name='password' required='required'>　　<input type='submit' value='确定'/></form>";
if(!empty($_POST['password']))
{
$sqlhelper=new SqlHelper();
$password=$_POST['password'];
	$sql1="select admin_shopnumber,admin_password from administrator";
    $res1=$sqlhelper->execute_dql($sql1);
    if($row=mysql_fetch_assoc($res1))
        {
			$oldshopnumber=$row['admin_shopnumber'];
			$admin_password=$row['admin_password'];
		}

if(md5($password)==$admin_password)
{
    for($i=1;$i<=$oldshopnumber;$i++)
    {
        
		 $fieldname="goods_".$i."_shop";
       
		$sql3="alter table goods drop $fieldname";
       
		if(0!=$sqlhelper->execute_dml($sql3))echo "删除".$i."分店成功</br>";
    }
$sql="truncate table shop";
$sql1="truncate table employee";
$sql2="truncate table income";
$sql3="truncate table outcome";
$sql4="truncate table adjustgoods";
$sql5="truncate table salerecord";
$sql6="truncate table goods";
$sql7="update administrator set admin_shopnumber=0";
if(0!=$sqlhelper->execute_dml($sql)&&0!=$sqlhelper->execute_dml($sql1)&&0!=$sqlhelper->execute_dml($sql2)&&0!=$sqlhelper->execute_dml($sql3)&&0!=$sqlhelper->execute_dml($sql4)&&0!=$sqlhelper->execute_dml($sql5)&&0!=$sqlhelper->execute_dml($sql6)&&0!=$sqlhelper->execute_dml($sql7))
	echo "重置成功，<a href='admin.php'>返回主目录</a>";
}else {echo "密码错误";echo "<a href='admin.php'>返回</a>";}
}	
?>