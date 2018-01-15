<?php
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
	?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
</head>
<?php
require_once 'SqlHelper.class.php';

echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> ";

$sqlhelper=new SqlHelper();
$sql='select employ_id,employ_name,employ_email,employ_shop from employee';
$res=$sqlhelper->execute_dql($sql);
echo "<a href='add_employ.php'><button class='mybtn'>添加店员</button></a>";
 echo "　　　　<a href='admin.php'><button class='mybtn'>返回主界面</button></a>";
if(0==mysql_num_rows($res))
{
    echo "还没有店员，请添加<br/>";
}
else
{
    echo "<table id='mytable'>";
    echo "<tr><th>店员ID</th><th>店员名字</th><th>店员邮箱</th><th>管理分店</th><th>修改店员</th><th>删除店员</th></tr></br>";
while($row=mysql_fetch_assoc($res))
{
	$id=$row['employ_id'];
    $shopid=$row['employ_shop'];
       $sql2="select shop_name from shop where shop_id=$shopid";
		$res2=$sqlhelper->execute_dql($sql2);
   		$shopname=mysql_result($res2,0);
	$employname=$row['employ_name'];
	echo "<tr><th>{$row['employ_id']}</th><th>{$row['employ_name']}</th><th>{$row['employ_email']}</th><th>{$shopname}</th><th><a href='employ_alterbyadmin.php?id=$id&employname=$employname'>修改店员</a></th><th><a href='emp_delete.php?id=$id'>删除店员</a></th></tr></br>";
}
echo "</table>";

}
?>
</html>


