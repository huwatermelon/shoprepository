<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> ";
echo "<script language=javascript src='Calendar.js'></script>";
require_once 'SqlHelper.class.php';
date_default_timezone_set(prc);
$sqlhelper=new SqlHelper();
$startday=$_POST['startday'];$endday=$_POST['endday'];
if(empty($startday)||empty($endday))
	{$startday=date('y-m-d',strtotime('-10 day'));$endday=date('y-m-d');}
if(!empty($_POST['tablename']))
{
 $tablename=$_POST['tablename'];
 $keyword=$_POST['keyword'];
	$str5="";$shopstr2=",goods_0_shop";
	$sql2="select * from shop order by shop_id";
	$res2=$sqlhelper->execute_dql ( $sql2);
	$i=1;
	while($row2=mysql_fetch_array($res2))
	{	$shopstr2.=",goods_".$i."_shop";
		$str5.="<th>{$row2[1]}</th>";	
		$i++;			
	}
	$shopnumber=$i-1;
 switch($tablename)
 {
 case "goods":
	$sql="select goods_flag,goods_id,goods_name,goods_serialnumber,goods_type,goods_buynumber".$shopstr2.",goods_buyprice,goods_saleprice,goods_controlprice,goods_brand,goods_color,goods_emp,factory_name,factory_tel,goods_buytime from goods where goods_name like '%$keyword%' 
	and  UNIX_TIMESTAMP(date(goods_buytime)) >= UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP(date(goods_buytime)) <= UNIX_TIMESTAMP('$endday')";
	break;
 case "salerecord":
	$sql="select * from salerecord where sale_goodname like '%$keyword%' or sale_employ like '%$keyword%' and
	  UNIX_TIMESTAMP(date(sale_goodtime)) >= UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP(date(sale_goodtime)) <= UNIX_TIMESTAMP('$endday')";break;
 case "income":
	$sql="select * from income where income_goodname like '%$keyword%' or income_employname like '%$keyword%' or income_shop like '%$keyword%' and
	  UNIX_TIMESTAMP(date(income_time)) >= UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP(date(income_time)) <= UNIX_TIMESTAMP('$endday')";break;
 case "outcome":
	$sql="select * from outcome where outcome_goodname like '%$keyword%' or outcome_employname like '%$keyword%' and 
	 UNIX_TIMESTAMP(date(outcome_time)) >= UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP(date(outcome_time)) <= UNIX_TIMESTAMP('$endday')";break;
 }
}

echo " 目前支持店员名和商品名查询：<form action='search.php' method='post' style='margin:0px;display: inline'>
<select name='tablename'>
<option value='goods'>库存</option>
<option value='salerecord'>销售</option>
<option value='income'>收入</option>
<option value='outcome'>支出</option></select>
<input name='keyword' type='text'/>起始日期：<input type='text' name='startday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly'/>
结束日期：<input type='text' name='endday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly'/>
<input name='Submit' type='submit' value='查找' />

</form><a href='admin.php'><button class='mybtn'>返回主目录</button></a>";
if(!empty($sql)){
$res=$sqlhelper->execute_dql($sql);
if(null==$res)
 echo "没有查找到符合要求的商品";
 else
 {
	
	switch($tablename)
	{
		case 'goods':
	
			echo "</br><table id='mytable' ><tr  bgcolor='#A7C942'><th style='border-style:none;background-color:white;width:10;'></th><th>ID</th><th>商品名字</th><th>商品系列号</th><th>型号</th><th>进货数量</th><th>总库余量</th>".$str5."<th>进价</th><th>建议售价</th><th>控价</th><th>品牌</th><th>颜色</th><th>入库店员</th><th>厂家</th><th>厂家电话</th><th>进货日期</th><th>删除商品</th></tr></br>";		
			while($row=mysql_fetch_array($res))
			{
				$str6="<th style='border-style:none;background-color:white;width:10;'>";
				
				for($i=1;$i<$shopnumber+16;$i++)
				{
					$str6.="<th>{$row[$i]}</th>";	
					
				}
			$goodid=$row[1];
			$str6.="<th><a href='javascript:void(0)' onclick='if(confirm(\"你确定要删除？\"))location.href=\"admin_goods.php?delegoodid=$goodid\"'>删除商品</a></th></tr></br>";				
				echo $str6;
			}
			echo "</table>";break;
		case 'salerecord':

			echo "</br><table id='mytable1' ><tr  bgcolor='#A7C942'><tr><th>ID</th><th>商品名字</th><th>店员名字</th><th>销售价格</th><th>销售数量</th><th>售前商品余量</th><th>销售日期</th><th>删除记录</th></tr></br>";
			while($row=mysql_fetch_assoc($res))
			{
				$salegoodid=$row['sale_goodid'];
				echo "<tr><th>{$salegoodid}</th><th>{$row['sale_goodname']}</th><th>{$row['sale_employ']}</th><th>{$row['sale_price']}</th><th>{$row['sale_goodnumber']}</th><th>{$row['sale_goodremain']}</th><th>{$row['sale_goodtime']}</th><th><a href='sale_record.php?delesalegoodid=$salegoodid'>删除记录</a></th></tr></br>";
			}
			echo "</table>";break;
		case 'income':
	
			echo "<table id='mytable1'>";
			echo "<tr bgcolor='#A7C942'><th>收入ID</th><th>交易商品</th><th>收入分店</th><th>经手人</th><th>收入金额</th><th>收入日期</th></tr></br>";
			while($row=mysql_fetch_assoc($res))
			{
				
				
				echo "<tr><th>{$row['income_id']}</th><th>{$row['income_goodname']}</th><th>{$row['income_shop']}</th><th>{$row['income_employname']}</th><th>{$row['income_number']}</th><th>{$row['income_time']}</th></tr></br>";
			}
			echo "</table>";break;
		case 'outcome':
	
			echo "<table id='mytable1'>";
			echo "<tr bgcolor='#A7C942'><th>支出ID</th><th>交易商品</th><th>经手人</th><th>支出金额</th><th>支出日期</th></tr></br>";
			while($row=mysql_fetch_assoc($res))
			{
				
				echo "<tr><th>{$row['outcome_id']}</th><th>{$row['outcome_goodname']}</th><th>{$row['outcome_employname']}</th><th>{$row['outcome_number']}</th><th>{$row['outcome_time']}</th></tr></br>";
			}
			echo "</table>";break;
	}
	
 }
 }
 
?>