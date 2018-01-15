<?php
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
if($_SESSION['usertype']!='admin'){echo "非管理员";exit;}
?>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href='mysubtable.css' rel='stylesheet' type='text/css'/> 

	<script language=javascript src="Calendar.js"></script>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
function changeimg(imgurl){ 

var lyr = null;

     lyr = document.getElementById("goodimg");
 lyr.src="http://huwatermelon-goodimg.stor.sinaapp.com/"+imgurl;

} 
var curtab = null;
function do_onclick(tab){
  if(curtab != null) curtab.style.backgroundColor = "white"; 
  tab.style.backgroundColor = "yellow";
  curtab = tab;
}
$(document).ready(function(){

});
function display(str,node)
{  
	
	if(node.innerText=="收起")
	{
		closedisplay();return;
	}

	node.title="收起";
	node.id=1;
	//node.setAttribute("value","-");
	$("table[name=displaymore]").remove();
	$("button[name=displaynodea]").text("展开");
	$("button[name=displaynodea]").css("background","");
	
	node.innerText="收起";
	node.style.background='#f47c20';
	var toppos=getTop(node)-100;
	var leftpos=getLeft(node)+50;
	var a="<table class='mysubtable' name='displaymore' style='position:absolute;top:";
	var b=";left:";
	var c=";'>";
	//var str1=a+b+c+str;
	var str1=a+''+toppos+b+''+leftpos+''+c+str;
	//var str1=a+''+toppos+b+''+leftpos+''+str;

	$("table").append(str1);

}
function getTop(e){ 
var offset=e.offsetTop; 
if(e.offsetParent!=null) offset+=getTop(e.offsetParent); 
return offset; 
} 
function getLeft(e){ 
var offset=e.offsetLeft; 
if(e.offsetParent!=null) offset+=getLeft(e.offsetParent); 
return offset; 
} 
function closedisplay(){ 
  $("table[name=displaymore]").remove();
  mynode=document.getElementById("1");
  mynode.innerText="展开";
  mynode.style.background='';
  mynode.id=0;
}
</script>
</head>
<body>

<?php
require_once 'SqlHelper.class.php';

$sqlhelper=new SqlHelper();
$stor=new SaeStorage();
$startday=$_POST['startday'];$endday=$_POST['endday'];
if(empty($startday)||empty($endday))
	{$startday=date('Y-m-d',strtotime('2014-10-01'));$endday=date('Y-m-d',strtotime('+1 day'));}
echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> ";
echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
echo "<link href='mytr.css' rel='stylesheet' type='text/css'/> ";

//echo "<link href='link.css' rel='stylesheet' type='text/css'/> ";
//border='1px' bordercolor='green'  cellspacing='0px'  width='700px'

if(!empty($_GET['delegoodid']))
{
$del_num=$_GET['delegoodid'];
    include("delete.php");

}
if(!empty($_POST['adjustgoodid']))
{
$goodid=$_POST['adjustgoodid'];
$adjustgoodnum=$_POST['adjustgoodnum'];
$fromshopid=$_POST['fromshopid'];
$toshopid=$_POST['toshopid'];
$sql="select goods_".$fromshopid."_shop from goods where goods_id='$goodid'";

$res=$sqlhelper->execute_dql($sql);
if($row=mysql_fetch_array($res))
{	if($fromshopid==$toshopid)
	$sql="update goods set goods_".$fromshopid."_shop=ifnull(goods_".$fromshopid."_shop,0)+'$adjustgoodnum' where goods_id=$goodid";
	else
	{
		if($row[0]<$adjustgoodnum)
		{echo "<a href='admin_goods.php'>商品不够</a>";exit;}
		$sql="update goods set goods_".$fromshopid."_shop=ifnull(goods_".$fromshopid."_shop,0)-'$adjustgoodnum',goods_".$toshopid."_shop=ifnull(goods_".$toshopid."_shop,0)+'$adjustgoodnum' where goods_id=$goodid";}
	}
	if(0!=$sqlhelper->execute_dml($sql))echo "<font color='red'>调整成功</font></br>";

}

echo "　　　　　　　　<a href='admin.php' style='position:absolute;top:60;left:220;'><button class='mybtn'>返回主目录</button></a>";
echo "　　　<a href='add_good.php' style='position:absolute;top:10;left:220;'><button class='mybtn'>返回添加</button></a>";
echo "　　　<a href='export_goods.php?startday=$startday&endday=$endday' style='position:absolute;top:10;left:320;'><button class='mybtn'>导出</button></a>";
echo "　　　<a href='altergood.php' style='position:absolute;top:60;left:320;'><button class='mybtn'>修改商品</button></a>";
echo "</br></br><form action='admin_goods.php' method='post' style='position:absolute;left:410px;top:10px;'>";

echo "商品ID：<input style='width:80px;height:20px;' type='number' name='adjustgoodid'　/>&nbsp;
数量：<input style='width:100px;' type='number' name='adjustgoodnum'　/>&nbsp;";
$sql="select * from shop order by shop_id";

$res=$sqlhelper->execute_dql($sql);
echo "从店：<select name='fromshopid'>";
echo "<option  value='0'>总库</option>";
$i=1;
while($row=mysql_fetch_array($res))
    //for($i=0;$i<=$shopnumber;$i++)
{	
    echo "<option  value='$i'>{$row[1]}</option>";
    $i++;
}
echo "</select>";
echo "　　到店：<select name='toshopid'>";
echo "<option  value='0'>总库</option>";
$res=$sqlhelper->execute_dql($sql);
$i=1;
while($row=mysql_fetch_array($res))
    //for($i=0;$i<=$shopnumber;$i++)
{	
    echo "<option  value='$i'>{$row[1]}</option>";
    $i++;
}
echo "</select>　　
<input type='submit' value='调整' />
</form>";
$sql1="select * from goods limit 10";
$res1=$sqlhelper->execute_dql ( $sql1);
if(0==mysql_num_rows($res1))
    {echo "还没有进货，请返回添加<br/>"; exit;}

else
{
echo "<style >

a:link,a:visited{
 text-decoration:none;  /*超链接无下划线*/
}
a:hover{
 text-decoration:underline;  /*鼠标放上去有下划线*/
}
</style>";
if(isset($_POST['searchsubmit'])) $_SESSION['search_goodname']= $_POST['search_goodname']; 
if(isset($_POST['searchsubmit']))  $_SESSION['search_goodtype']= $_POST['search_goodtype']; 
if(isset($_POST['searchsubmit']))  $_SESSION['search_goodbrand']= $_POST['search_goodbrand']; 
if(isset($_POST['searchsubmit']))  $_SESSION['search_goodcolor']= $_POST['search_goodcolor']; 
if(isset($_POST['shopid']))  $_SESSION['disshopid']= $_POST['shopid'];     
if(isset($_SESSION['disshopid'])) ;else $_SESSION['disshopid']=-1;   
if(isset($_POST['clearsubmit'])) 
{
    $_SESSION['search_goodname']=""; 
     $_SESSION['search_goodtype']=""; 
     $_SESSION['search_goodbrand']=""; 
     $_SESSION['search_goodcolor']="";  
    $_SESSION['disshopid']=-1;
}    
    //$searchgoodname=empty($_POST['search_goodname'])?$_GET['search_goodname']:$_POST['search_goodname'];
    //$searchgoodtype=empty($_POST['search_goodtype'])?$_GET['search_goodtype']:$_POST['search_goodtype'];
    //$searchgoodbrand=empty($_POST['search_goodbrand'])?$_GET['search_goodbrand']:$_POST['search_goodbrand'];
    //$searchgoodcolor=empty($_POST['search_goodcolor'])?$_GET['search_goodcolor']:$_POST['search_goodcolor'];

echo "</br></br><form action='admin_goods.php' method='post' style='position:absolute;top:45px;left:410px;margin-bottom:0px;'>
起始日期：<input type='text' name='startday' style='padding-left:5px;' size='8'  onclick='SelectDate(this)' readonly='readonly' value='{$startday}'/>&nbsp;
结束日期：<input type='text' name='endday' style='padding-left:5px;' size='8'  onclick='SelectDate(this)' readonly='readonly' value='{$endday}'/>（结果为起始和结束日期之间记录，不包括当天）</br>
商品名字：<input type='text' name='search_goodname' style='padding-left:5px;' size='8' value='".$_SESSION['search_goodname']."'/>&nbsp;
型号：<input type='text' name='search_goodtype' style='padding-left:5px;' size='8' value='".$_SESSION['search_goodtype']."'/>&nbsp;
品牌：<input type='text' name='search_goodbrand' style='padding-left:5px;' size='8' value= '".$_SESSION['search_goodbrand']."'/>&nbsp;
颜色：<input type='text' name='search_goodcolor' style='padding-left:5px;' size='8' value='".$_SESSION['search_goodcolor']."'/>&nbsp;";

    echo   "分店:<select  name='shopid' style='width:100px'>";
if($_SESSION['disshopid']==-1) echo "<option  value='-1' selected='selected'>所有</option>";else echo "<option  value='-1'>所有</option>";
if($_SESSION['disshopid']==0)	echo "<option  value='0' selected='selected'>总库</option>";else echo "<option  value='0' >总库</option>";
$sql2="select * from shop order by shop_id";
$res2=$sqlhelper->execute_dql($sql2);
$i=1;
while($row2=mysql_fetch_array($res2))
    //for($i=0;$i<=$shopnumber;$i++)
{	
   if($_SESSION['disshopid']==$i)echo "<option  value='$i' selected='selected'>{$row2[1]}</option>";else echo "<option  value='$i'>{$row2[1]}</option>";
    $i++;
}
echo "</select>
<input type='submit' name='searchsubmit' value='查询' /> 
<input type='submit' name='clearsubmit' value='清空' /> 

</form></br>";
if(!empty($startday))
{
	
	//$sql1="select * from goods where UNIX_TIMESTAMP(goods_buytime) between UNIX_TIMESTAMP('2014-10-01') and UNIX_TIMESTAMP('2014-10-12')";
	
	$sql2="select * from goods where UNIX_TIMESTAMP(date(goods_buytime)) >= UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP(date(goods_buytime)) <= UNIX_TIMESTAMP('$endday')";

	$res2=$sqlhelper->execute_dql ( $sql2);
	if(null==$res2)
	{	
	   echo "<script language='javascript'>alert('没有查找到记录')</script>";
	   exit;
	}
	if(empty($_GET['displayname']))
	{
		$maxtime="FROM_UNIXTIME( MAX( UNIX_TIMESTAMP( goods_buytime ) ) )";
		//select goods_id,goods_serialnumber,goods_name,sum(goods_buynumber),sum(goods_0_shop),FROM_UNIXTIME( MAX( UNIX_TIMESTAMP( goods_buytime ) ) ) from goods  
		//where UNIX_TIMESTAMP(goods_buytime) between UNIX_TIMESTAMP('2014-10-01') and UNIX_TIMESTAMP('2014-10-12') group by goods_name order by goods_id
		//$sql5="select * from(select COLUMN_NAME from information_schema.COLUMNS where table_schema = 'app_huwatermelon' and table_name = 'goods' )t where COLUMN_NAME like 'goods%shop'";
		$sql5="select admin_shopnumber from administrator";
		$res5=$sqlhelper->execute_dql($sql5);
		$row5=mysql_fetch_array($res5);
		$shopnumber=$row5[0];
		$shopstr="";
		$shopstr2="";
		$str5="";$str51="";
		$sql8="select * from shop order by shop_id";
		$res8=$sqlhelper->execute_dql ( $sql8);
		$i=1;
		while($row8=mysql_fetch_array($res8))
		//for($i=1;$i<=$shopnumber;$i++)
		{
			$shopstr.=",goods_".$i."_shop";
				$shopstr2.=",goods_".$i."_shop";
				if($i==$_SESSION['disshopid']){$str5.="<th style=\'background:#ff0000;\'>{$row8[1]}</th>";	
			$str51.="<th style='background:#ff0000;'>{$row8[1]}</th>";}
			else{$str5.="<th style=\'background:#73B1E0;\'>{$row8[1]}</th>";	
			$str51.="<th style='background:#73B1E0;'>{$row8[1]}</th>";}
			$i++;			
		}
////////////////////////////////////////////////////////////////////////////////////////
$searchlist=array();
        if(!empty($_SESSION['search_goodname']))
	$searchlist[]="goods_name like '%{$_SESSION['search_goodname']}%'";
        //elseif(!empty($searchgoodname)) $searchlist[]="goods_name like '%{$searchgoodname}%'";
        if(!empty($_SESSION['search_goodtype']))
	$searchlist[]="goods_type like '%{$_SESSION['search_goodtype']}%'";
        //elseif(!empty($searchgoodtype)) $searchlist[]="goods_name like '%{$searchgoodtype}%'";
        if(!empty($_SESSION['search_goodbrand']))
	$searchlist[]="goods_brand like '%{$_SESSION['search_goodbrand']}%'";
        //elseif(!empty($searchgoodbrand)) $searchlist[]="goods_name like '%{$searchgoodbrand}%'";
        if(!empty($_SESSION['search_goodcolor']))
	$searchlist[]="goods_color like '%{$_SESSION['search_goodcolor']}%'";
        //elseif(!empty($searchgoodcolor)) $searchlist[]="goods_name like '%{$searchgoodcolor}%'";
     	if(!empty($_SESSION['disshopid'])&&$_SESSION['disshopid']>=0)
            $searchlist[]="goods_{$_SESSION['disshopid']}_shop>0";
if(count($searchlist)>0)
	$search=" and ".implode(' and ',$searchlist);
///////////////////////////////////////////////////////////////
 $sq="select count(*) from goods where UNIX_TIMESTAMP(goods_buytime) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday')".$search." order by goods_id;";
$re=$sqlhelper->execute_dql($sq);
if($row=mysql_fetch_array($re))

$total=$row[0];
if(!empty($_POST['onepagenum']))
	$onepage=$_POST['onepagenum'];
	elseif(!empty($_SESSION['onepage']))
	$onepage=$_SESSION['onepage'];
	else $onepage=10;
	$_SESSION['onepage']=$onepage;
$maxpage=ceil($total/$onepage);
$pagenum=empty($_GET['page'])?$maxpage:$_GET['page'];
if($pagenum>$maxpage)
	$pagenum=$maxpage;
if(($pagenum<1)&&($pagenum!=-1))
	$pagenum=1;

$lim=" limit ".($pagenum-1)*$onepage.",$onepage";	
if($pagenum==-1)	$lim="";
////////////////////////////////////////////////////////////////////////////////////////	
		 $sql="select goods_flag,goods_id,goods_name,goods_type,goods_brand,goods_color,goods_mark1,goods_buynumber,goods_0_shop".$shopstr.",goods_buyprice,goods_realbuyprice,goods_saleprice,goods_controlprice,goods_serialnumber,goods_emp,factory_name,factory_tel,goods_buytime from goods  
		where UNIX_TIMESTAMP(goods_buytime) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday')".$search." order by goods_id ".$lim;

		$res=$sqlhelper->execute_dql ( $sql);
		echo "</br><hr />";
		echo "<table id='mytable'><caption style='font-size:25;'><b>（{$startday}）到（{$endday}）间入库商品</b></caption><br/>";
if(0==$_SESSION['disshopid'])	{echo "<tr  bgcolor='#A7C942'><th style='border-style:none;background-color:white;width:10;'></th>
		<th>ID</th><th>商品名字</th><th>型号</th><th>品牌</th><th>颜色</th><th>备注</th><th>进货数量</th><th style='background:#ff0000;'>总库余量</th>".$str51."<th>进价</th><th>实际进价</th><th>建议售价</th><th>控价</th><th>商品系列号</th><th>入库店员</th><th>厂家</th><th>厂家电话</th><th>进货日期</th><th>处理商品</th></tr></br>";		
	}
else {echo "<tr  bgcolor='#A7C942'><th style='border-style:none;background-color:white;width:10;'></th>
		<th>ID</th><th>商品名字</th><th>型号</th><th>品牌</th><th>颜色</th><th>备注</th><th>进货数量</th><th style='background:#73B1E0;'>总库余量</th>".$str51."<th>进价</th><th>实际进价</th><th>建议售价</th><th>控价</th><th>商品系列号</th><th>入库店员</th><th>厂家</th><th>厂家电话</th><th>进货日期</th><th>处理商品</th></tr></br>";		
		}
		while($row=mysql_fetch_array($res)) 
		{
				
			//$id=$row['goods_id'];
			$good_name=$row[2];
			$good_type=$row[3];
            /*if((1==$row[0]))
			{	
				$str6="";
				
				for($i=1;$i<$shopnumber+18;$i++)
				{	if(($i>7)&&($i<=$shopnumber+8))
						{if($i==$_POST['shopid']+8)$str6.="<th style='background:#ff0000;'>{$row[$i]}</th>";else $str6.="<th style='background:#73B1E0;'>{$row[$i]}</th>";}
					else $str6.="<th>{$row[$i]}</th>";	
					
				}	
					//$str6=htmlspecialchars_decode("</tr></br>");
					$str6=$str6."<th></th><th></th></tr></br>";
			
				$str4="<tr ><th>ID</th><th>商品名字</th><th>型号</th><th>品牌</th><th>颜色</th><th>备注</th><th>进货数量</th><th style=\'background:#73B1E0;\'>总库余量</th>".$str5."<th>进价</th><th>实际进价</th><th>建议售价</th><th>控价</th><th>商品系列号</th><th>入库店员</th><th>厂家</th><th>厂家电话</th><th>进货日期</th><th>处理商品</th><th style=\'border-style:none;background-color:red;\'><a  class=\'close\' font-size=5px title=\'关闭\' href=\'javascript:\' onclick=\'closedisplay();\'></a></th></tr>";

				$sql4="select goods_flag,goods_id,goods_name,goods_type,goods_brand,goods_color,goods_mark1,goods_buynumber,goods_0_shop".$shopstr2.",goods_buyprice,goods_realbuyprice,goods_saleprice,goods_controlprice,goods_serialnumber,goods_emp,factory_name,factory_tel,goods_buytime from goods where UNIX_TIMESTAMP(goods_buytime) between UNIX_TIMESTAMP('$startday') and UNIX_TIMESTAMP('$endday') and goods_name=trim('$good_name') and goods_type=trim('$good_type')  order by goods_id";
				$res4=$sqlhelper->execute_dql ( $sql4);
				//if(mysql_num_rows($res4)<2){$sql="update goods set goods_flag=0 where goods_name='$good_name' and goods_type='$good_type'";$sqlhelper->execute_dml($sql);continue;}
				while($row4=mysql_fetch_array($res4))	
				{	$str7="";	
					for($i=1;$i<$shopnumber+18;$i++)
					{
					if(($i>7)&&($i<=$shopnumber+8)){if($i==$_POST['shopid']+8)$str7.="<th style=\'background:#ff0000;\'>{$row4[$i]}</th>";else $str7.="<th style=\'background:#73B1E0;\'>{$row4[$i]}</th>";	}
					else	$str7.="<th>{$row4[$i]}</th>";	
					}	
					$goodid=$row4[1];
					
					$str4=$str4."<tr class=\'mytr\' onclick=\'do_onclick(this)\' onmouseover=\'changeimg({$goodid})\'>".$str7."<th width=120px><a href=\'add_sale_good.php?salegoodid=$goodid\'>销售</a>&nbsp;<a href=\'altergood.php?altergoodid=$goodid\'>修改</a>&nbsp;<a href=\'javascript:void(0)\' onclick=if(confirm(\'你确定要删除？\'))location.href=\'admin_goods.php?delegoodid=$goodid\'>删除</a></th><th bgcolor=\'white\'></th></tr></br>";		
				}
				$str4=$str4."</table>";
				echo	"<tr class='mysubtr' onclick='do_onclick(this)' onmouseover='changeimg(\"$goodid\")'><th style='border-style:none;width=10;'> <button name='displaynodea' title='展开' onclick=\"display('$str4',this);\" class='mybtn' style='width:40;background:' >展开</button></th>".$str6;			
			}
		
			elseif(0==$row[0])*/
			
			$goodid=$row[1];
			if(0==$row[0])
				$str6="<tr class='mytr' onclick='do_onclick(this)' onmouseover='changeimg(\"$goodid\")'><th style='border-style:none;width=10;'></th>";
			else
				$str6="<tr class='mytr' onclick='do_onclick(this)' onmouseover='changeimg(\"$goodid\")'><th style='border-style:none;width=10;'>有图</th>";
			for($i=1;$i<$shopnumber+18;$i++)
			{if(($i>7)&&($i<=$shopnumber+8)){if($i==$_SESSION['disshopid']+8)$str6.="<th style='background:#ff0000;'>{$row[$i]}</th>";else $str6.="<th style='background:#73B1E0;'>{$row[$i]}</th>";}
			else	$str6.="<th>{$row[$i]}</th>";	
			}	
			//$str6=htmlspecialchars_decode("</tr></br>");
			
			$str6.="<th width=160px><a href='add_sale_good.php?salegoodid=$goodid'>销售</a>&nbsp;<a href='adjust_goods.php?goodid=$goodid'>调货</a>&nbsp;<a href='altergood.php?altergoodid=$goodid'>修改</a>&nbsp;<a href='javascript:void(0)' onclick='if(confirm(\"你确定要删除？\"))location.href=\"admin_goods.php?delegoodid=$goodid\"'>删除</a></th></tr></br>";
				echo $str6;//"<tr><th style='border-style:none;width=10;'></th><th>{$row[0]}</th><th>{$row[1]}</th><th>{$row[2]}</th><th>{$row[3]}</th><th>{$row[4]}</th>".$str6."</tr></br>";
			
		}
	}
	echo "</table></br></br></br></br></br>";
	
echo "<div style='position:absolute;top:105;left:10;'><form action='admin_goods.php' method='post'><a href='admin_goods.php?page=1' style='cursor:hand;'>首页</a>&nbsp;";
echo "<a href='admin_goods.php?page=".($pagenum-1)."'>上一页</a>&nbsp;";
if($pagenum==-1)echo "第1-".$maxpage."页";else echo "第{$pagenum}页/共{$maxpage}页&nbsp;";
echo "<input type='submit' value='每页:'/>&nbsp;<input type='text' style='width:30px' name='onepagenum' value={$onepage} />行&nbsp;";
echo "<a href='admin_goods.php?page=".($pagenum+1)."'>下一页</a>&nbsp;";
echo "<a href='admin_goods.php?page=".$maxpage."'>末页</a>&nbsp;";
echo "<a href='admin_goods.php?page=-1'>所有</a></form></div>";


}
}　
//　　　

 
?>
<img id="goodimg" src="cat" style="position:fixed;z-index:999;top:10;left:120;width:80;height:80;display:;">
    </body>
</html>
