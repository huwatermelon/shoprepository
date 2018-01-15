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
 echo '<b style="font-size:20;">调货记录</b><br/>';
$startday=$_POST['startday'];$endday=$_POST['endday'];
if(empty($startday)||empty($endday))
	{$startday=date('Y-m-d',strtotime('2014-10-01'));$endday=date('Y-m-d',strtotime('+1 day'));}
$sqlhelper=new SqlHelper();

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
/*$searchgoodname=empty($_POST['search_goodname'])?$_GET['search_goodname']:$_POST['search_goodname'];
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
*/
	//****************
	///////////////////////////////////////////////////////////////
  $sq="select count(*) from  adjustgoods where UNIX_TIMESTAMP(adjust_time) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') ;";
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
$sql="select * from adjustgoods";
else
 $sql="select * from adjustgoods where UNIX_TIMESTAMP(adjust_time) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday')".$lim;
$res=$sqlhelper->execute_dql($sql);

if(0==mysql_num_rows($res))
	if($name=='admin')
		echo "<font color='red' size='3'>没有调货记录！</font><br/><a href='admin.php'>返回</a>";
	else 
		echo "<font color='red' size='3'>没有调货记录！</font><br/><a href='employ.php'>返回</a>";
else
{
echo "  <link href='mybtn.css' rel='stylesheet' type='text/css'/> ";


if($name=='admin')
   echo "<a href='admin.php'><button class='mybtn'>返回主目录</button></a>　　　　";
else echo "<a href='employ.php'><button class='mybtn'>返回主目录</button></a>　　　　";

//echo "<table border='1px' bordercolor='green'  cellspacing='0px'  width='700px'>";

echo "</br></br><form action='sale_record.php' method='post' style='margin-bottom:0px;'>
起始日期：<input type='text' name='startday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly' value='{$startday}'/>
结束日期：<input type='text' name='endday' style='padding-left:5px;' size='10'  onclick='SelectDate(this)' readonly='readonly' value='{$endday}'/>";
    /*商品名字：<input type='text' name='search_goodname' style='padding-left:5px;' size='8' value='$searchgoodname'/>&nbsp;
销售店员：<input type='text' name='search_employname' style='padding-left:5px;' size='8' value='$searchemployname'/>&nbsp;
型号：<input type='text' name='search_goodtype' style='padding-left:5px;' size='8' value='$searchgoodtype'/>&nbsp;
品牌：<input type='text' name='search_goodbrand' style='padding-left:5px;' size='8' value='$searchgoodbrand'/>&nbsp;
颜色：<input type='text' name='search_goodcolor' style='padding-left:5px;' size='8' value='$searchgoodcolor'/>&nbsp;*/
echo "<input type='submit' value='查询' /> 
</form>";

 $sql="select adjust_id,adjust_goodid,adjust_toshopname,adjust_fromshopname,adjust_employname,adjust_goodnumber,adjust_time,adjust_state from adjustgoods  where UNIX_TIMESTAMP(adjust_time) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') order by adjust_id".$lim;
$res=$sqlhelper->execute_dql( $sql);
if(0!=mysql_num_rows($res))
   {
   
    
    echo "<table style='position:absolute;top:140;width:1000;' border='1px' bordercolor='green'  cellspacing='0px'  >";
    echo "<tr><th>调货ID</th><th>调入店</th><th>调出店</th><th>申请店员</th><th>商品名字</th><th>商品类型</th><th>商品品牌</th><th>调货数量</th><th>申请日期</th><th>处理</th></tr></br>";
        while($row=mysql_fetch_assoc($res))
        {
           $adjustgoodid=$row['adjust_goodid'];
            $adjustgoodtype=$row['adjust_goodtype'];
            $adjustemploy=$row['adjust_employname'];
            $shopid=$row['adjust_shopid'];
            $inshopname=$row['adjust_toshopname'];
            $outshopname=$row['adjust_fromshopname'];
           
            $adjustgoodnumber=$row['adjust_goodnumber'];
            $adjustid=$row['adjust_id'];
            $sql2="select goods_name,goods_type,goods_brand from goods where goods_id=$adjustgoodid";
            $res2=$sqlhelper->execute_dql($sql2);
            $row2=mysql_fetch_array($res2);
            if($row['adjust_state']=='未批准')
            	echo "<tr><th>{$row['adjust_id']}</th><th>".$inshopname."</th><th>".$outshopname."</th><th>{$row['adjust_employname']}</th><th>{$row2[0]}</th><th>{$row2[1]}</th><th>{$row2[2]}</th><th>{$row['adjust_goodnumber']}</th><th>{$row['adjust_time']}</th><th>
                <a href='admin.php?adjustid=$adjustid'>批准</a>&nbsp;<a href='admin.php?deladjustid=$adjustid'>删除</a></th></tr></br>";
            elseif($row['adjust_state']=='未审核')
            {
                echo "<tr><th>{$row['adjust_id']}</th><th>".$inshopname."</th><th>".$outshopname."</th><th>{$row['adjust_employname']}</th><th>{$row2[0]}</th><th>{$row2[1]}</th><th>{$row2[2]}</th><th>{$row['adjust_goodnumber']}</th><th>{$row['adjust_time']}</th><th>
                已由分店批准</th></tr></br>";
                $sql="update adjustgoods set adjust_state=2 where adjust_id='$adjustid'";
                $sqlhelper->execute_dml($sql);
            }else echo "<tr><th>{$row['adjust_id']}</th><th>".$inshopname."</th><th>".$outshopname."</th><th>{$row['adjust_employname']}</th><th>{$row2[0]}</th><th>{$row2[1]}</th><th>{$row2[2]}</th><th>{$row['adjust_goodnumber']}</th><th>{$row['adjust_time']}</th><th>
                已审核</th></tr></br>";
        }
   }
	
echo "<div style='position:absolute;top:100;left:10;'><form action='adjustrecord.php' method='post'><a href='adjustrecord.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=1' style='cursor:hand;'>首页</a>&nbsp;";
echo "<a href='adjustrecord.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=".($pagenum-1)."'>上一页</a>&nbsp;";
if($pagenum==-1)echo "第1-".$maxpage."页";else echo "第{$pagenum}页/共{$maxpage}页&nbsp;";
echo "<input type='submit' value='每页:'/>&nbsp;<input type='text' style='width:30px' name='onepagenum' value={$onepage} />行&nbsp;";
echo "<a href='adjustrecord.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=".($pagenum+1)."'>下一页</a>&nbsp;";
echo "<a href='adjustrecord.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=".$maxpage."'>末页</a>&nbsp;";
echo "<a href='adjustrecord.php?search_employname={$searchemployname}&search_goodname={$searchgoodname}&search_goodtype={$searchgoodtype}&search_goodbrand={$searchgoodbrand}&search_goodcolor={$searchgoodcolor}&page=-1'>所有</a></form></div>";

}