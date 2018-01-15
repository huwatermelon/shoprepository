<?php
//header("http-equiv=Content-Type content=text/html; charset=utf-8");

header("Content-type:application/vnd.ms-excel;charset=gbk");

header ("Content-Disposition: attachment; filename=".date('Y-m-d')."商品.xls" );
//echo "<meta http-equiv='Content-Type' content='text/html' charset='utf-8'>";
$startday=$_GET['startday'];
$endday=$_GET['endday'];
//echo $startday;
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
if(empty($endday))
{
$sql_endday="select now()";
$res_endday=$sqlhelper->execute_dql($sql_endday);
if($row_endday=mysql_fetch_array($res_endday))
   $endday=$row_endday[1];
}
$shopstr2="";
$str5="";
$sql8="select * from shop order by shop_id";
$res8=$sqlhelper->execute_dql ( $sql8);
$i=1;
while($row8=mysql_fetch_array($res8))
    //for($i=1;$i<=$shopnumber;$i++)
{
    
    $shopstr2.=",goods_".$i."_shop";
    $str5.="<th>{$row8[1]}</th>";	
    $i++;			
}
$shopnumber=$i-1;
$sql="select goods_name,goods_serialnumber,goods_type,goods_buynumber,goods_0_shop".$shopstr2.",goods_buyprice,goods_saleprice,goods_controlprice,goods_brand,goods_color,goods_emp,factory_name,factory_tel,goods_buytime from goods  
		where UNIX_TIMESTAMP(goods_buytime) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') order by goods_id;";
//$sql="select goods_id,goods_name,goods_saleprice,goods_buynumber,goods_remain,goods_buytime,factory_name,factory_tel from goods where UNIX_TIMESTAMP(goods_buytime) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') limit 500";
$res=$sqlhelper->execute_dql ( $sql);
echo "\xEF\xBB\xBF";
echo "<table border='1px' bordercolor='green'  cellspacing='0px'  width='700px'>";
echo "<tr><th>商品名字</th><th>条形码</th><th>型号</th><th>进货数量</th><th>总库余量</th>".$str5."<th>进价</th><th>建议售价</th><th>控价</th><th>品牌</th><th>颜色</th><th>入库店员</th>
<th>厂家</th><th>厂家电话</th><th>进货日期</th></tr></br>";

while($row=mysql_fetch_array($res)) 
{	$str6="<tr>";
				
				for($i=0;$i<$shopnumber+14;$i++)
				{
                    $str6.="<th>{$row[$i]}</th>";	
					
				}	
 $str6.="</tr></br>";
	echo $str6;
   
}
echo "</table>";

?>
