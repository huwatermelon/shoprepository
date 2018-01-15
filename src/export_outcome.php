<?php

header("Content-type:application/vnd.ms-excel;charset=utf8");

header ("Content-Disposition: attachment; filename=".date('Y-m-d')."支出.xls" );
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();

$startday=$_GET['startday'];
$endday=$_GET['endday'];


$sql="select * from outcome where UNIX_TIMESTAMP(outcome_time) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') limit 50";

$res=$sqlhelper->execute_dql ( $sql);
echo "\xEF\xBB\xBF";
echo "<table border='1px' bordercolor='green'  cellspacing='0px'  width='700px'>";

echo "<tr><th>支出ID</th><th>交易商品</th><th>经手人</th><th>支出金额</th><th>支出日期</th></tr></br>";
while($row=mysql_fetch_assoc($res)) 
{

	echo "<tr><th>{$row['outcome_id']}</th><th>{$row['outcome_goodname']}</th><th>{$row['outcome_employname']}</th><th>{$row['outcome_number']}</th><th>{$row['outcome_time']}</th></tr></br>";
}
echo "</table>";

?>