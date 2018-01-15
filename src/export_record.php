<?php
header("Content-type:application/vnd.ms-excel;charset=gbk");

date_default_timezone_set(prc);
$startday=$_GET['startday'];
$endday=$_GET['endday'];
if(empty($startday)||empty($endday))
	{$startday=date('y-m-d',strtotime('-10 day'));$endday=date('y-m-d',strtotime('+1 day'));}
header ("Content-Disposition: attachment; filename=".date('Y-m-d')."销售记录.xls" );
require_once 'SqlHelper.class.php';

$sqlhelper=new SqlHelper();
if(empty($endday))
{
$sql_endday="select now()";
$res_endday=$sqlhelper->execute_dql($sql_endday);
if($row_endday=mysql_fetch_array($res_endday))
   $endday=$row_endday[1];
}
$searchlist=array();$search1=array();
if(!empty($_POST['search_goodname']))
	$search1[]="sale_goodname like '%{$_POST['search_goodname']}%'";
if(!empty($_POST['search_goodtype']))
	$searchlist[]="goods_type like '%{$_POST['search_goodtype']}%'";
if(!empty($_POST['search_goodbrand']))
	$searchlist[]="goods_brand like '%{$_POST['search_goodbrand']}%'";
if(!empty($_POST['search_goodcolor']))
	$searchlist[]="goods_color like '%{$_POST['search_goodcolor']}%'";
if(!empty($_POST['search_employname']))
	$search1[]=" sale_employ like '%{$_POST['search_employname']}%'";
if(count($searchlist)>0)
	$search=" and ".implode(' and ',$searchlist);
if(count($search1)>0)
	$searchemp=" and ".implode(' and ',$search1);
if(empty($startday))
$sql="select * from salerecord order by sale_goodtime limit 50";
else
$sql="select * from salerecord where UNIX_TIMESTAMP(sale_goodtime) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday')".$searchemp;
$res=$sqlhelper->execute_dql ( $sql);
echo "\xEF\xBB\xBF";
echo "<table  border='1px' bordercolor='green'  cellspacing='0px'  width='1600px'>";
$totalnumber=0;
		echo "<tr><th>商品ID</th><th>商品名字</th><th>型号</th><th>商品品牌</th><th>商品颜色</th><th>销售分店</th><th>销售店员</th><th>销售价格</th><th>销售数量</th><th>售前商品余量</th><th>销售日期</th></tr></br>";
		while($row=mysql_fetch_assoc($res))
		{
			$salegoodid=$row['sale_goodid'];
			//*****************************************
			$sql2="select goods_type,goods_brand,goods_color from goods where goods_id='$salegoodid'".$search;	
//echo $sql2."</br>";			
			$res2=$sqlhelper->execute_dql($sql2);
			if(0==mysql_num_rows($res2))continue;
			$row2=mysql_fetch_array($res2);
			//*******************************************
			echo "<tr><th>{$salegoodid}</th><th>{$row['sale_goodname']}</th><th>{$row2[0]}</th><th>{$row2[1]}</th><th>{$row2[2]}</th><th>{$row['sale_shop']}</th><th>{$row['sale_employ']}</th><th>{$row['sale_price']}</th><th>{$row['sale_goodnumber']}</th><th>{$row['sale_goodremain']}</th><th>{$row['sale_goodtime']}</th></tr></br>";	
			$totalnumber+=$row['sale_price']*$row['sale_goodnumber'];
		};	
echo "</talbe></br>";
echo "<b style='position:absolute;top:100;left:10;'>销售额总计：{$totalnumber}元</b>";
?>