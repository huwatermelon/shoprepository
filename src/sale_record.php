<?php
header("Content-Type: text/html; charset=utf-8");
session_start();

if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
$name=$_SESSION['name'];
require_once 'SqlHelper.class.php';
echo "<script language=javascript src='Calendar.js'></script>";
echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> ";
echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
$startday=$_POST['startday'];$endday=$_POST['endday'];
if(empty($startday)||empty($endday))
	{$startday=date('Y-m-d',strtotime('2014-10-01'));$endday=date('Y-m-d',strtotime('+1 day'));}
$sqlhelper=new SqlHelper();
if(!empty($_POST['alterid']))
{
    $sql="update salerecord set sale_mark='".$_POST['altermark']."' where sale_id={$_POST['alterid']}";
    $sqlhelper->execute_dml($sql);
    echo "<font style='position:absolute;top=10;left:1000;' color='red'>修改成功</font>";
}
if(!empty($_GET['delesalegoodid']))
{
	$sale_goodid=$_GET['delesalegoodid'];
	$sql1="delete from salerecord where sale_goodid='$sale_goodid'";
	$sql2="update salerecord set sale_goodid = sale_goodid-1 where sale_goodid >$sale_goodid";
	$sql3="ALTER TABLE salerecord AUTO_INCREMENT=1";
	$sqlhelper->execute_dml($sql1);
	$sqlhelper->execute_dml($sql2);
	$sqlhelper->execute_dml($sql3);
	
}
if(empty($endday))
{
$sql_endday="select now()";
$res_endday=$sqlhelper->execute_dql($sql_endday);
if($row_endday=mysql_fetch_array($res_endday))
   $endday=$row_endday[1];
}
//////////////////////
$searchgoodname=empty($_POST['search_goodname'])?$_GET['search_goodname']:$_POST['search_goodname'];
$searchemployname=empty($_POST['search_employname'])?$_GET['search_employname']:$_POST['search_employname'];
$searchgoodtype=empty($_POST['search_goodtype'])?$_GET['search_goodtype']:$_POST['search_goodtype'];
$searchgoodbrand=empty($_POST['search_goodbrand'])?$_GET['search_goodbrand']:$_POST['search_goodbrand'];
$searchgoodcolor=empty($_POST['search_goodcolor'])?$_GET['search_goodcolor']:$_POST['search_goodcolor'];
//*******************
$searchlist=array();
if(!empty($_POST['search_goodname']))
	$searchlist[]="sale_goodname like '%{$_POST['search_goodname']}%'";
	elseif(!empty($searchgoodname)) $searchlist[]="sale_goodname like '%{$searchgoodname}%'";
if(!empty($_POST['search_goodtype']))
	$searchlist[]="sale_goodtype like '%{$_POST['search_goodtype']}%'";
	elseif(!empty($searchgoodtype)) $searchlist[]="search_goodtype like '%{$searchgoodtype}%'";
if(!empty($_POST['search_goodbrand']))
	$searchlist[]="sale_goodbrand like '%{$_POST['search_goodbrand']}%'";
	elseif(!empty($salegoodbrand)) $searchlist[]="sale_goodbrand like '%{$salegoodbrand}%'";
if(!empty($_POST['search_goodcolor']))
	$searchlist[]="sale_goodcolor like '%{$_POST['search_goodcolor']}%'";
	elseif(!empty($salegoodcolor)) $searchlist[]="sale_goodcolor like '%{$salegoodcolor}%'";
if(!empty($_POST['search_employname']))
	$searchlist[]=" sale_employ like '%{$_POST['search_employname']}%'";
	elseif(!empty($searchemployname)) $searchlist[]="sale_employ like '%{$searchemployname}%'";
if(count($searchlist)>0)
	$search=" and ".implode(' and ',$searchlist);

	//****************
	///////////////////////////////////////////////////////////////
  $sq="select count(*) from  salerecord where UNIX_TIMESTAMP(sale_goodtime) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') {$search};";
$re=$sqlhelper->execute_dql($sq);
if($row=mysql_fetch_array($re))

$total=$row[0];
if(!empty($_POST['onepagenum']))
	$onepage=$_POST['onepagenum'];
	elseif(!empty($_SESSION['onerecordpage']))
	$onepage=$_SESSION['onerecordpage'];
	else $onepage=10;
	$_SESSION['onerecordpage']=$onepage;
$maxpage=ceil($total/$onepage);
$pagenum=empty($_GET['page'])?$maxpage:$_GET['page'];
if($pagenum>$maxpage)
	$pagenum=$maxpage;
if(($pagenum<1)&&($pagenum!=-1))
	$pagenum=1;

$lim=" limit ".($pagenum-1)*$onepage.",$onepage";	
if($pagenum==-1)	$lim="";
////////////////////////////////////////////////////////////////////////////////////////	
if(empty($startday))
$sql="select * from salerecord";
else
$sql="select * from salerecord where UNIX_TIMESTAMP(sale_goodtime) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday')".$search.$lim;
$res=$sqlhelper->execute_dql($sql);

if(0==mysql_num_rows($res))
	if($name=='admin')
		echo "<font color='red' size='3'>没有销售记录！</font><br/><a href='admin.php'>返回</a>";
	else 
		echo "<font color='red' size='3'>没有销售记录！</font><br/><a href='employ.php'>返回</a>";
else
{
echo "  <link href='mybtn.css' rel='stylesheet' type='text/css'/> ";


if($name=='admin')
   echo "<a href='admin.php'><button class='mybtn'>返回主目录</button></a>　　　　";
else echo "<a href='employ.php'><button class='mybtn'>返回主目录</button></a>　　　　";
echo "　　　<a href='export_record.php?startday=$startday&endday=$endday&search_goodname={$_POST['search_goodname']}&search_goodtype={$_POST['search_goodtype']}&search_goodbrand={$_POST['search_goodbrand']}&search_goodcolor={$_POST['search_goodcolor']}&search_employname={$_POST['search_employname']}'><button class='mybtn'>导出</button></a>";//$startday=$_GET['startday'];$endday=$_GET['endday'];
//echo "<table border='1px' bordercolor='green'  cellspacing='0px'  width='700px'>";
    echo "<form action='sale_record.php' method='post' style='position:absolute;top:10;left:400;'>销售ID:<input type='number' name='alterid' style='width:100;'>&nbsp;备注:<input type='text' name='altermark'>&nbsp;<input type='submit' value='修改'></form>";
echo "</br></br><form action='sale_record.php' method='post' style='margin-bottom:0px;'>
起始日期：<input type='text' name='startday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly' value='{$startday}'/>
结束日期：<input type='text' name='endday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly' value='{$endday}'/>
商品名字：<input type='text' name='search_goodname' style='padding-left:5px;' size='8' value='$searchgoodname'/>&nbsp;
销售店员：<input type='text' name='search_employname' style='padding-left:5px;' size='8' value='$searchemployname'/>&nbsp;
型号：<input type='text' name='search_goodtype' style='padding-left:5px;' size='8' value='$searchgoodtype'/>&nbsp;
品牌：<input type='text' name='search_goodbrand' style='padding-left:5px;' size='8' value='$searchgoodbrand'/>&nbsp;
颜色：<input type='text' name='search_goodcolor' style='padding-left:5px;' size='8' value='$searchgoodcolor'/>&nbsp;
<input type='submit' value='查询' /> 
</form>";

echo "<table class='mysaletable'>";
$totalnumber=0;
if($_SESSION['usertype']=='admin')
	{		
		echo "<tr><th>销售ID</th><th>商品ID</th><th>商品名字</th><th>型号</th><th>商品品牌</th><th>商品颜色</th><th>备注</th><th>销售分店</th><th>销售店员</th><th>销售价格</th><th>销售数量</th><th>售前商品余量</th><th>销售日期</th><th>删除记录</th></tr></br>";
		while($row=mysql_fetch_assoc($res))
		{
			$salegoodid=$row['sale_goodid'];
			//*****************************************
		
			//*******************************************
			echo "<tr><th>{$row['sale_id']}</th><th>{$salegoodid}</th><th>{$row['sale_goodname']}</th><th>{$row['sale_goodtype']}</th><th>{$row['sale_goodbrand']}</th><th>{$row['sale_goodcolor']}</th><th>{$row['sale_mark']}</th><th>{$row['sale_shop']}</th><th>{$row['sale_employ']}</th><th>{$row['sale_price']}</th><th>{$row['sale_goodnumber']}</th><th>{$row['sale_goodremain']}</th><th>{$row['sale_goodtime']}</th><th><a href='javascript:void(0)' onclick='if(confirm(\"你确定要删除？\"))location.href=\"sale_record.php?delesalegoodid=$salegoodid\"' >删除记录</a></th></tr></br>";	
			$totalnumber+=$row['sale_price']*$row['sale_goodnumber'];
		};	
}	
else
	{
		echo "<tr><th>销售ID</th><th>商品ID</th><th>商品名字</th><th>型号</th><th>商品品牌</th><th>商品颜色</th><th>备注</th><th>销售分店</th><th>销售店员</th><th>销售价格</th><th>销售数量</th><th>售前商品余量</th><th>销售日期</th></tr></br>";
			while($row=mysql_fetch_assoc($res))
		{
			$salegoodid=$row['sale_goodid'];
			echo "<tr><th>{$row['sale_id']}</th><th>{$salegoodid}</th><th>{$row['sale_goodname']}</th><th>{$row['sale_goodtype']}</th><th>{$row['sale_goodbrand']}</th><th>{$row['sale_goodcolor']}</th><th>{$row['sale_mark']}</th><th>{$row['sale_shop']}</th><th>{$row['sale_employ']}</th><th>{$row['sale_price']}</th><th>{$row['sale_goodnumber']}</th><th>{$row['sale_goodremain']}</th><th>{$row['sale_goodtime']}</th></tr></br>";	
			$totalnumber+=$row['sale_price']*$row['sale_goodnumber'];
		};	
	}

echo "</talbe></br>";
	
echo "<div style='position:absolute;top:100;left:10;'><form action='sale_record.php' method='post'><a href='sale_record.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=1' style='cursor:hand;'>首页</a>&nbsp;";
echo "<a href='sale_record.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=".($pagenum-1)."'>上一页</a>&nbsp;";
if($pagenum==-1)echo "第1-".$maxpage."页";else echo "第{$pagenum}页/共{$maxpage}页&nbsp;";
echo "<input type='submit' value='每页:'/>&nbsp;<input type='text' style='width:30px' name='onepagenum' value={$onepage} />行&nbsp;";
echo "<a href='sale_record.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=".($pagenum+1)."'>下一页</a>&nbsp;";
echo "<a href='sale_record.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=".$maxpage."'>末页</a>&nbsp;";
echo "<a href='sale_record.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=-1'>所有</a></form></div>";

echo "<b style='position:absolute;top:95;left:10;'>销售额总计：{$totalnumber}元</b>";
}