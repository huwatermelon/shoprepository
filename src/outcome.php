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

$sql="select * from outcome where UNIX_TIMESTAMP(outcome_time) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') limit 50";
$res=$sqlhelper->execute_dql ( $sql);

echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> ";
echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
 echo '<b style="font-size:20;">支出记录</b><br/>';
echo "<a href='admin.php'><button class='mybtn'>返回主目录</button></a>　　　";
echo "　　<a href='export_outcome.php?startday=$startday&endday=$endday'><button class='mybtn'>导出</button></a>　　　　";

if(0==mysql_num_rows($res))
    echo "还没有支出记录，请返回添加<br/>"; 

else
{
echo "</br></br><form action='outcome.php' method='post' style='margin-bottom:0px;'>
起始日期：<input type='text' name='startday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly' value='{$startday}'/>
结束日期：<input type='text' name='endday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly' value='{$endday}'/>
<input type='submit' value='GO' />
</form>";

echo "<table id='mytable'>";
echo "<tr bgcolor='#A7C942'><th>支出ID</th><th>交易商品</th><th>经手人</th><th>支出金额</th><th>支出日期</th></tr></br>";
while($row=mysql_fetch_assoc($res)) 
{
	
	echo "<tr><th>{$row['outcome_id']}</th><th>{$row['outcome_goodname']}</th><th>{$row['outcome_employname']}</th><th>{$row['outcome_number']}</th><th>{$row['outcome_time']}</th></tr></br>";
}
echo "</table></br>";



}


　　　　
?>
