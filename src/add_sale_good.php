<?php 
header("Content-Type: text/html; charset=utf-8");

session_start();
echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> 
<link href='mytr.css' rel='stylesheet' type='text/css'/> ";

if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
require_once 'SqlHelper.class.php';
echo ' <script type="text/javascript">
    var curtab = null;
function do_onclick(tab){
  if(curtab != null) curtab.style.backgroundColor = "white"; 
  tab.style.backgroundColor = "yellow";
  curtab = tab;
}
  function reset_onclick(tab){
        var formlist=tab.parentNode.getElementsByTagName("input");         
           for(var i=0;i<formlist.length;i++)
          {
             if(formlist[i].type=="text"||formlist[i].type=="number"||formlist[i].type=="file")
             formlist[i].value="";
          }
        }
 </script>';
echo '<script type="text/javascript" src="http://huwatermelon-myeditor.stor.sinaapp.com/tinymce.min.js"></script>';
    echo '<script language="javascript" type="text/javascript">
          tinymce.init({
                    plugins: [
                        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
                    ],
                     toolbar1: "undo redo | cut copy paste | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                    toolbar2: " searchreplace | bullist numlist | outdent indent blockquote | link unlink anchor image media code | inserttime preview | forecolor backcolor",
                    
                    menubar: false,
                    width:500,
                    height:600,
                   theme_advanced_resizing : true,
            
                    style_formats: [
                        {title: "Bold text", inline: "b"},
                        {title: "Red text", inline: "span", styles: {color: "#ff0000"}},
                        {title: "Red header", block: "h1", styles: {color: "#ff0000"}},
                        {title: "Example 1", inline: "span", classes: "example1"},
                        {title: "Example 2", inline: "span", classes: "example2"},
                        {title: "Table styles"},
                        {title: "Table row 1", selector: "tr", classes: "tablerow1"}
                    ],
            
                    templates: [
                        {title: "Test template 1", content: "Test 1"},
                        {title: "Test template 2", content: "Test 2"}
                    ],
                selector: "textarea",
                 language:"zh_CN"
            });
        </script>';
$sqlhelper=new SqlHelper();
$sql="select goods_id,goods_name,goods_type from goods";
$res=$sqlhelper->execute_dql($sql);
if(0==mysql_num_rows($res)){echo "还没有商品";
	if($_SESSION['name']=='admin')
		echo "<a href='admin.php'>返回主目录</a>";
	else 
		echo "<a href='employ.php'>返回主目录</a>";
		exit;}
if(!empty($_GET['salegoodid']))
	$salegoodid=$_GET['salegoodid'];
?>
 <script type="text/javascript">
    var curtab = null;
function do_onclick(tab){
  if(curtab != null) curtab.style.backgroundColor = "white"; 
  tab.style.backgroundColor = "yellow";
  curtab = tab;
}
 </script>
<?php
 echo '<b style="font-size:20;">销售商品</b><br/>';
///********************************************************
echo "<form style='margin-top:30;margin-left:50;' action='add_sale_good.php' method='post'>";
echo "商品ID：<input style='width:80px;' type='number' name='salegoodid'  value='".$salegoodid."'/><font color='red'>*</font>&nbsp;<input type='submit' name='submit1' value='查询'><br/>";
$sql1="select admin_shopnumber from administrator";
$res1=$sqlhelper->execute_dql($sql1);
   if($row=mysql_fetch_assoc($res1))
      $shopnumber=$row['admin_shopnumber'];
	  echo "<form  action='sale_good_process.php' method='post'>";
echo   "销售店:<select  name='shopid' style='width:173px'>";
	if($_SESSION['shop']==0)echo "<option  value='0' selected='selected'>总库</option>";else echo "<option  value='0'>总库</option>";
$sql2="select * from shop order by shop_id";
$res2=$sqlhelper->execute_dql($sql2);
$i=1;
while($row2=mysql_fetch_array($res2))
    //for($i=0;$i<=$shopnumber;$i++)
{	if($_SESSION['shop']==$i)
    echo "<option  value='$i' selected='selected'>{$row2[1]}</option>";
 else
    echo "<option  value='$i'>{$row2[1]}</option>";
    $i++;
}
echo "</select><br/>";
$sql2="select * from employee";
$res2=$sqlhelper->execute_dql($sql2);
$empnum=mysql_num_rows($res2);
echo   "销售店员:<select  name='sale_empname' style='width:173px'>";
if($_SESSION['name']=='admin')echo "<option value='admin' selected='selected'>管理员</option>";else echo "<option value='admin'>管理员</option>";
while($row2=mysql_fetch_array($res2))
{if($_SESSION['name']==$row2[1])echo "<option value={$row2[1]} selected='selected'>{$row2[1]}</option>";else echo "<option value={$row2[1]}>{$row2[1]}</option>";}

echo "</select>"; 

    $disgoodname=isset($_POST['submit4'])?"":$_GET['goodname'];
 $disgoodtype=isset($_POST['submit4'])?"":$_GET['goodtype'];
 $disgoodbrand=isset($_POST['submit4'])?"":$_GET['goodbrand'];
 $disgoodcolor=isset($_POST['submit4'])?"":$_GET['goodcolor'];

echo "</br>售价：<input style='width:80px;' type='number' name='saleprice' /><font color='red'>*</font></br>";
echo "数量：<input type='number' name='salenumber' value='1'/><font color='red'>*</font></br>";
echo "备注：<input type='text' name='salemark' />&nbsp;&nbsp;";
echo "<input type='submit' name='submit2' value='确认销售'/>";
echo "</br></br><fieldset style='width:600'><legend>查询ID</legend>商品名字:<input type='text' name='goodname' value='{$disgoodname}'/></br>
商品型号:<input type='text' name='goodtype' value='{$disgoodtype}'/></br>
商品品牌:<input type='text' name='goodbrand' value='{$disgoodbrand}'/></br>
商品颜色:<input type='text' name='goodcolor' value='{$disgoodcolor}'/>&nbsp; <button  onclick='reset_onclick(this);' >清空</button>&nbsp;
<input  type='submit'  value='查询' name='submit3'/>(输入名字/型号/品牌查询)</fieldset>";
if($_SESSION['usertype']=='admin')
	echo "　　<a href='admin.php'>返回</a></form>";
else echo "　　<a href='employ.php'>返回</a></form>";
//******************************************************************************************
if(isset($_POST['marksubmit']))
    {
    // if($_SESSION['usertype']=='admin')
    // {
            $newmark=$_POST['marktexts'];
            $sqlmark="update marks set mark_text='$newmark' where mark_page='addsale'";
            $sqlhelper->execute_dml($sqlmark);
         // }else {echo"<script language='javascript'>alert('只有管理员才能编辑此项')</script>";exit;}
    }
    $sqlmark="select mark_text from marks where mark_page='addsale'";
    $resmark=$sqlhelper->execute_dql($sqlmark);
    if($resmark)
    {
        if($markrow=mysql_fetch_array($resmark))
            $marktext=$markrow[0];
        else
            $marktext="请管理员添加注释并点击上面按钮提交：";
        echo "<div style='position:absolute;left:800;top:20;'><form id='mark' action='add_sale_good.php' method='post'><input type='submit' value='修改注释' name='marksubmit'></form><textarea form='mark' name='marktexts'cols=40 rows=30>".$marktext."</textarea></div>";
    }
    

//*******************************************************************************
if(isset($_POST['submit2']))
{
	$sale_shop=$_POST['shopid'];
    $sale_employ=$_POST['sale_empname']?$_POST['sale_empname']:$_SESSION['name'];
	
	$sale_goodnametype=$_POST['salegood'];
	$goodnametype=explode('-',$sale_goodnametype);
	$sale_goodname=$goodnametype[0];
	$sale_goodtype=$goodnametype[1];
	$sale_mark=$_POST['salemark'];
	$sale_goodprice=$_POST['saleprice'];
	$sale_goodnumber=$_POST['salenumber'];
	$sale_goodid=$_POST['salegoodid'];
if(empty($sale_goodnumber)){echo "请输入销售数量";exit;}
if(empty($sale_goodprice)){echo "请输入销售价格";exit;}
	if(!empty($sale_goodid))
	{
		$sql="select goods_".$sale_shop."_shop,goods_name,goods_type,goods_brand,goods_color from goods where goods_id='$sale_goodid'";
		$res=$sqlhelper->execute_dql($sql);
		if(0==mysql_num_rows($res))
			{echo "商品ID不对";echo "<a href='add_sale_good.php'>返回修改</a>";exit;}
		if($row=mysql_fetch_array($res)){$sale_goodremain=$row[0];$salegoodname=$row[1];$salegoodtype=$row[2];$salegoodbrand=$row[3];$salegoodcolor=$row[4];}
		if(0!=strcmp($salegoodname,$sale_goodname)) $sale_goodname=$salegoodname;

	}
	else
	{
		echo $sql2="select goods_".$sale_shop."_shop,goods_id,goods_brand from goods where goods_name='$sale_goodname' and goods_type='$sale_goodtype'";
		
		$res2=$sqlhelper->execute_dql($sql2);
		if(0==mysql_num_rows($res2))
				{echo "没有此种商品";exit;}
		elseif(1<mysql_num_rows($res2))
		{
			echo "此商品有同名商品，请通过ID销售";exit;
		}
		if($row=mysql_fetch_array($res2))
		{
			$sale_goodremain=$row[0];$sale_goodid=$row[1];
		}
	}
		if($sale_goodremain<$sale_goodnumber)
		{	
			echo "此库商品不够";
			exit;
		}
	$sql="select shop_name from shop where shop_id='$sale_shop'";
	$res=$sqlhelper->execute_dql($sql);
	$row=mysql_fetch_array($res);
	$sale_shopname=$row[0];
	 $sql4="update goods set goods_".$sale_shop."_shop=goods_".$sale_shop."_shop-$sale_goodnumber where goods_id={$sale_goodid}";	
 	$sql="insert into salerecord(sale_goodtype,sale_goodbrand,sale_goodcolor,sale_mark,sale_goodid,sale_goodname,sale_goodnumber,sale_goodremain,sale_goodtime,sale_shop,sale_employ,sale_price)
			 values('$salegoodtype','$salegoodbrand','$salegoodcolor','$sale_mark','$sale_goodid','$sale_goodname',$sale_goodnumber,$sale_goodremain,now(),'$sale_shopname','$sale_employ',$sale_goodprice)";



	$income=$sale_goodprice*$sale_goodnumber;

	$sql3="insert into income(income_shop,income_goodid,income_goodname,income_employname,income_number,income_time) values($sale_shop,'$sale_goodid','$sale_goodname','$sale_employ',".$income.",now())";

	if(1==$sqlhelper->execute_dml($sql3))
		echo " 更新收入成功!<br/>";
	if(1==$sqlhelper->execute_dml($sql4))
		echo " 更新库存成功!<br/>";
	if(1==$sqlhelper->execute_dml($sql))
		echo "<a href='sale_record.php'>更新销售记录成功!</a>";
////////////////////////include('updategoods.php');
}
///**********************************
if(isset($_POST['submit1']))
{
	

			$good_id=$_POST['salegoodid'];


			$sql5="select admin_shopnumber from administrator";
			$res5=$sqlhelper->execute_dql($sql5);
			$row5=mysql_fetch_array($res5);
			$shopnumber=$row5[0];
			$shopstr="";
			
			$str5="";
			$sql8="select * from shop order by shop_id";
			$res8=$sqlhelper->execute_dql ( $sql8);
			$i=1;
			while($row8=mysql_fetch_array($res8))
			//for($i=1;$i<=$shopnumber;$i++)
			{
				$shopstr.=",goods_".$i."_shop";
				
				$str5.="<th>{$row8[1]}</th>";	
				$i++;			
			}


			$sql="select goods_flag,goods_id,goods_name,goods_type,goods_serialnumber,goods_buynumber,goods_0_shop".$shopstr.",goods_buyprice,goods_saleprice,goods_controlprice,goods_brand,goods_color,goods_emp,factory_name,factory_tel,goods_buytime from goods  
			where goods_id={$good_id}";
			$res=$sqlhelper->execute_dql($sql);
			echo "<table id='mytable' style='position:absolute;top:450;'>";
			echo "<tr  bgcolor='#A7C942'><th style='border-style:none;background-color:white;width:10;'></th><th>ID</th><th>商品名字</th><th>型号</th><th>商品系列号</th><th>进货数量</th><th>总库余量</th>".$str5."<th>进价</th><th>建议售价</th><th>控价</th><th>品牌</th><th>颜色</th><th>入库店员</th><th>厂家</th><th>厂家电话</th><th>进货日期</th></tr></br>";		
					$str6="<tr>";
			if($row=mysql_fetch_array($res))
            {	$str6.="<th><a href='add_sale_good.php?salegoodid=$good_id&salegoodname={$row[2]}'>使用</a></th>";
					for($i=1;$i<$shopnumber+16;$i++)
					{
						$str6.="<th>{$row[$i]}</th>";	
						
					}	
						//$str6=htmlspecialchars_decode("</tr></br>");
						$str6=$str6."</tr>";
						echo $str6."</table>";
             //echo "<a href='add_sale_good.php?salegoodid=$good_id&salegoodname={$row[2]}'><button>使用</button></a>";
			}else echo "<font color='red'>商品ID不正确！</font>";
}
if(isset($_POST['submit3']))
{

     $sale_goodbrand=$_POST['goodbrand'];$sale_goodname=$_POST['goodname'];$sale_goodtype=$_POST['goodtype'];$sale_goodcolor=$_POST['goodcolor'];

    if(empty($sale_goodbrand)&&empty($sale_goodname)&&empty($sale_goodtype))
    {
        echo "<br/>名字、品牌、类型至少输入一个";exit;
    }
        $where=array();
        if(!empty($_POST['goodbrand']))$where[]="goods_brand like '%{$sale_goodbrand}%'";
    
        if(!empty($sale_goodname))$where[]="goods_name like '%{$sale_goodname}%'";
        if(!empty($sale_goodtype))$where[]="goods_type like '%{$sale_goodtype}%'";
    if(!empty($sale_goodcolor))$where[]="goods_color like '%{$sale_goodcolor}%'";
        $wherels=" where ".implode(' and ',$where);
			$sql5="select admin_shopnumber from administrator";
			$res5=$sqlhelper->execute_dql($sql5);
			$row5=mysql_fetch_array($res5);
			$shopnumber=$row5[0];
			$shopstr="";
			
			$str5="";
			$sql8="select * from shop order by shop_id";
			$res8=$sqlhelper->execute_dql ( $sql8);
			$i=1;
			while($row8=mysql_fetch_array($res8))
			//for($i=1;$i<=$shopnumber;$i++)
			{
				$shopstr.=",goods_".$i."_shop";
				
				$str5.="<th>{$row8[1]}</th>";	
				$i++;			
			}


			$sql="select goods_flag,goods_id,goods_name,goods_type,goods_serialnumber,goods_buynumber,goods_0_shop".$shopstr.",goods_buyprice,goods_saleprice,goods_controlprice,goods_brand,goods_color,goods_emp,factory_name,factory_tel,goods_buytime from goods  
			".$wherels;
			$res=$sqlhelper->execute_dql($sql);
			echo "<table class='mysaletable' style='position:absolute;top:450;'>";
			echo "<tr  bgcolor='#A7C942'><th style='border-style:none;background-color:white;width:10;'></th><th>ID</th><th>商品名字</th><th>型号</th><th>商品系列号</th><th>进货数量</th><th>总库余量</th>".$str5."<th>进价</th><th>建议售价</th><th>控价</th><th>品牌</th><th>颜色</th><th>入库店员</th><th>厂家</th><th>厂家电话</th><th>进货日期</th></tr></br>";		
					
			while($row=mysql_fetch_array($res))
            {	$str6="<tr class='mytr' onclick='do_onclick(this)'><th><a href='add_sale_good.php?salegoodid={$row[1]}&goodname={$row[2]}&goodtype={$row[3]}&goodbrand={$row[".$shopnumber."+15]}&goodcolor={$row[".$shopnumber."+16]}'>使用</a></th>";
					for($i=1;$i<$shopnumber+16;$i++)
					{
						$str6.="<th>{$row[$i]}</th>";	
						
					}	
						//$str6=htmlspecialchars_decode("</tr></br>");
						$str6=$str6."</tr>";
						echo $str6;
						
			}echo "</table>";
			
}
?>
