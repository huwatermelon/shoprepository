<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
$sql="select goods_id,goods_name,goods_type from goods";
$res=$sqlhelper->execute_dql($sql);
echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> 
<link href='mytr.css' rel='stylesheet' type='text/css'/> ";
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
$employname=$_SESSION['name'];
 echo '<b style="font-size:20;">调货申请</b><br/><br/><br/>';
echo "<form action='adjust_goods.php' method='post'>";
$disgoodid=empty($_GET['goodid'])?$_POST['goodid']:$_GET['goodid'];
$disgoodname=empty($_GET['goodname'])?$_POST['goodname']:$_GET['goodname'];
$disgoodtype=empty($_GET['goodtype'])?$_POST['goodtype']:$_GET['goodtype'];
$disgoodbrand=empty($_GET['goodbrand'])?$_POST['goodbrand']:$_GET['goodbrand'];
$disgoodcolor=empty($_GET['goodcolor'])?$_POST['goodcolor']:$_GET['goodcolor'];
echo "商品ID:<input type='number' name='goodid' value='{$disgoodid}'/><font color='red'>*</font>(可以通过下面查询获得)<br/>";


echo   "申请店:<select  name='toshopname' style='width:173px'>";
echo "<option  value='总库' selected='selected'>总库</option>";
//	 $sql2="select shop.shop_name,shop.shop_id,employee.employ_name from shop,employee where shop.shop_id=employee.employ_shop and employee.employ_name='$employname' order by shop_id";
$sql2="select shop_name from shop order by shop_id";
$res2=$sqlhelper->execute_dql($sql2);

while($row2=mysql_fetch_array($res2))
    //for($i=0;$i<=$shopnumber;$i++)
{	
    
    echo "<option  value='".$row2[0]."'>{$row2[0]}</option>";
 
}
echo "</select></br>";	
echo   "货源店:<select  name='fromshopname' style='width:173px'>";
	echo "<option  value='总库' selected='selected'>总库</option>";
$sql2="select * from shop order by shop_id";
$res2=$sqlhelper->execute_dql($sql2);

while($row2=mysql_fetch_array($res2))
    //for($i=0;$i<=$shopnumber;$i++)
{	
    echo "<option  value='".$row2[1]."'>{$row2[1]}</option>";
 
}
echo "</select><br/>";
echo "数　量:<input type='number' name='adjustgoodnumber'/>";
echo "&nbsp;&nbsp;<input type='submit' name='submit1' value='申请'/><br/>";
echo "<br/><fieldset style='width:600'><legend>查询ID</legend>商品名字:<input type='text' name='goodname' value='{$disgoodname}'/><br/>
型　　号:<input type='text' name='goodtype' value='{$disgoodtype}'/><br/>
品　　牌:<input type='text' name='goodbrand' value='{$disgoodbrand}'/><br/>
颜　　色:<input type='text' name='goodcolor' value='{$disgoodcolor}'/>&nbsp;<input  type='submit'  value='查询' name='submit2'/>&nbsp;
 <button  onclick='reset_onclick(this);' >清空</button>&nbsp;(输入名字/型号/品牌/颜色查询)</fieldset>";


echo "</form>";
if($_SESSION['usertype']=='admin')echo "　　　<a href='admin.php'>返回主目录</a>";
else echo "　　　<a href='employ.php'>返回主目录</a>";
//******************************************************************************************
if(isset($_POST['marksubmit']))
    {
    // if($_SESSION['usertype']=='admin')
    // {
            $newmark=$_POST['addmarktexts'];
            $sqlmark="update marks set mark_text='$newmark' where mark_page='adjustgoods'";
            $sqlhelper->execute_dml($sqlmark);
         // }else {echo"<script language='javascript'>alert('只有管理员才能编辑此项')</script>";exit;}
    }
    $sqlmark="select mark_text from marks where mark_page='adjustgoods'";
    $resmark=$sqlhelper->execute_dql($sqlmark);
    if($resmark)
    {
        if($markrow=mysql_fetch_array($resmark))
            $marktext=$markrow[0];
        else
            $marktext="请添加注释并点击上面按钮提交：";
        echo "<div style='position:absolute;left:800;top:20;'><form id='mark' action='adjust_goods.php' method='post'><input type='submit' value='修改注释' name='marksubmit'></form><textarea form='mark' name='addmarktexts'cols=40 rows=30>".$marktext."</textarea></div>";
    }
//*******************************************************************************
if(isset($_POST['submit1']))
{
   if($_POST['toshopname']==$_POST['fromshopname'])
   {
       echo "<font color='red'>申请店跟货源店不能一样！</font>";exit;
   }
    if(empty($_POST['goodid']))
    { echo "<font color=red>请输入商品ID，可以通过查询获得</font>";exit;}
	$goodid=$_POST['goodid'];
    $fromshopname=$_POST['fromshopname'];
    $toshopname=$_POST['toshopname'];
    $goodnumber=$_POST["adjustgoodnumber"];
    if($fromshopname=='总库')
        $fromshopid=0;
    else
    {$sql="select shop_id from shop where shop_name=trim('$fromshopname')";
	 $res=$sqlhelper->execute_dql($sql);
     $fromshopid=mysql_result($res,0);}
    
    $employ=$_SESSION['name'];
	$shop=$_SESSION['shop'];

    $sql="select goods_".$fromshopid."_shop from goods where goods_id=$goodid";
    $res=$sqlhelper->execute_dql($sql);
    $row=mysql_fetch_array($res);

    if($goodnumber<=$row[0])
    {
        $sql1="select adjust_id from adjustgoods where adjust_goodid=$goodid and adjust_state='未批准'";     
        $res1=$sqlhelper->execute_dql($sql1);
        if(0!=mysql_num_rows($res1))        
        {            
            echo "此商品还有调货处于审核中，请联系管理员批准";
             exit;
        }
        $sql="insert into adjustgoods(adjust_goodid,adjust_fromshopname,adjust_toshopname,adjust_employname,adjust_goodnumber,adjust_time)
              values($goodid,'$fromshopname','$toshopname','$employ',$goodnumber,now())";
        if(1==$sqlhelper->execute_dml($sql))
           
        	echo "申请成功";
    }
    else {echo "此库余量不够";exit;}
}

if(isset($_POST['submit2']))
{
    $sale_goodbrand=$_POST['goodbrand'];$sale_goodname=$_POST['goodname'];$sale_goodtype=$_POST['goodtype'];$sale_goodcolor=$_POST['goodcolor'];

    if(empty($sale_goodbrand)&&empty($sale_goodname)&&empty($sale_goodtype)&&empty($sale_goodcolor))
    {
        echo "<br/>名字、品牌、类型、颜色至少输入一个";exit;
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


			$sql="select goods_flag,goods_id,goods_name,goods_type,goods_serialnumber,goods_mark1,goods_buynumber,goods_0_shop".$shopstr.",goods_buyprice,goods_saleprice,goods_controlprice,goods_brand,goods_color,goods_emp,factory_name,factory_tel,goods_buytime from goods".$wherels;  
			

			$res=$sqlhelper->execute_dql($sql);
			echo "<table  class='mysaletable' style='position:absolute;top:550;'>";
			echo "<tr  bgcolor='#A7C942'><th style='border-style:none;background-color:white;width:10;'></th><th>ID</th><th>商品名字</th><th>型号</th><th>商品系列号</th><th>备注</th><th>进货数量</th><th>总库余量</th>".$str5."<th>进价</th><th>建议售价</th><th>控价</th><th>品牌</th><th>颜色</th><th>入库店员</th><th>厂家</th><th>厂家电话</th><th>进货日期</th></tr></br>";		
					
			while($row=mysql_fetch_array($res))
			{	$str6="<tr class='mytr' onclick='do_onclick(this)'><th><a href='adjust_goods.php?goodid={$row[1]}&goodname={$row[2]}&goodtype={$row[3]}&goodbrand={$row[$shopnumber+11]}'>使用</a></th>";
					for($i=1;$i<$shopnumber+17;$i++)
					{
						$str6.="<th>{$row[$i]}</th>";	
						
					}	
						//$str6=htmlspecialchars_decode("</tr></br>");
						$str6=$str6."</tr>";
						echo $str6;
						
			}echo "</table>";
}
?>