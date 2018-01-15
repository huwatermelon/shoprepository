<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
echo "<script language=javascript src='Calendar.js'></script>";
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
$startday=$_POST['startday'];$endday=$_POST['endday'];
if(empty($startday)||empty($endday))
	{$startday=date('Y-m-d',strtotime('2014-10-01'));$endday=date('Y-m-d',strtotime('+1 day'));}
if(empty($startday))
	$sql="select * from income order by income_time limit 50";
else
    $sql="select * from income where UNIX_TIMESTAMP(income_time) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') limit 500";
$res=$sqlhelper->execute_dql ( $sql);


echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> ";
echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
echo '<b style="font-size:20;">收入记录</b><br/>';
echo "<a href='admin.php'><button class='mybtn'>返回主目录</button></a>　　　　";
echo "　　　<a href='export_income.php?startday=$startday&endday=$endday'><button class='mybtn'>导出</button></a>";
//echo "<a href='export_income.php'><button class='mybtn' >导出</button></a>";

if(0==mysql_num_rows($res))
    echo "还没有收入记录，请返回添加<br/>"; 

else
{
 
echo "</br></br><form action='income.php' method='post' style='margin-bottom:0px;'>
起始日期：<input type='text' name='startday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly' value='{$startday}'/>
结束日期：<input type='text' name='endday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly' value='{$endday}'/>
<input type='submit' value='GO' />
</form>";
echo "<table id='mytable'>";
echo "<tr bgcolor='#A7C942'><th>收入ID</th><th>交易商品</th><th>收入分店</th><th>经手人</th><th>收入金额</th><th>收入日期</th></tr></br>";
while($row=mysql_fetch_assoc($res)) 
{
	
	echo "<tr><th>{$row['income_id']}</th><th>{$row['income_goodname']}</th><th>{$row['income_shop']}</th><th>{$row['income_employname']}</th><th>{$row['income_number']}</th><th>{$row['income_time']}</th></tr></br>";
}
echo "</table></br>";



}


　　　　
?>
