<?php
//header("content=text/html; charset=gb2312");
header("Content-type:application/vnd.ms-excel;charset=gbk");

header ("Content-Disposition: attachment; filename=".date('Y-m-d')."收入.xls" );

require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
date_default_timezone_set(prc);
$startday=$_GET['startday'];
$endday=$_GET['endday'];
if(empty($startday)||empty($endday))
	{$startday=date('y-m-d',strtotime('-10 day'));$endday=date('y-m-d',strtotime('+1 day'));}

$sql="select * from income where UNIX_TIMESTAMP(income_time) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') limit 500";
echo "\xEF\xBB\xBF";
$res=$sqlhelper->execute_dql ( $sql);
echo "<table border='1px' bordercolor='green'  cellspacing='0px'  width='700px'>";

echo "<tr><th>收入ID</th><th>交易商品</th><th>收入分店</th><th>经手人</th><th>收入金额</th><th>收入日期</th></tr></br>";
while($row=mysql_fetch_assoc($res)) 
{
	
	echo "<tr><th>{$row['income_id']}</th><th>{$row['income_goodname']}</th><th>{$row['income_shop']}</th><th>{$row['income_employname']}</th><th>{$row['income_number']}</th><th>{$row['income_time']}</th></tr></br>";
}
echo "</table>";

?>