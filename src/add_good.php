<?php
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
	?>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
<!-- <title>进货</title> -->
  <link href='mybtn.css' rel='stylesheet' type='text/css'/> 
    <link href='mytr.css' rel='stylesheet' type='text/css'/> 
    <script type="text/javascript">
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
             if(formlist[i].type=='text'||formlist[i].type=='number'||formlist[i].type=="file")
             formlist[i].value='';
          }
        }
 </script>
   <script type="text/javascript" src="http://huwatermelon-myeditor.stor.sinaapp.com/tinymce.min.js"></script>
<script language="javascript" type="text/javascript">
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
        </script>
</head>
<body style="text-align:left;margin-top:20px;">
    <b style="font-size:20;">录入商品信息</b><br/><br/><br/>

<?php 
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> ";
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
$stor = new SaeStorage();
$sql1="select admin_shopnumber from administrator";
$res1=$sqlhelper->execute_dql($sql1);
   if($row=mysql_fetch_assoc($res1))
      $shopnumber=$row['admin_shopnumber'];

	  echo "<form  action='add_good.php' enctype='multipart/form-data' method='post' id='myForm'>";
echo   "入库到店:<select  name='shopid' style='width:173px'>";
	echo "<option  value='0' selected='selected'>总库</option>";
$sql2="select * from shop order by shop_id";
$res2=$sqlhelper->execute_dql($sql2);
$i=1;
while($row2=mysql_fetch_array($res2))
    //for($i=0;$i<=$shopnumber;$i++)
{	
    echo "<option  value='$i'>{$row2[1]}</option>";
    $i++;
}
echo "</select><br/>";
$sql2="select * from employee";
$res2=$sqlhelper->execute_dql($sql2);
$empnum=mysql_num_rows($res2);

echo   "入库店员:<select  name='empname' style='width:173px'>";
echo "<option value='admin'>管理员</option>";
while($row2=mysql_fetch_array($res2))
 echo "<option value={$row2[1]}>{$row2[1]}</option>";

echo "</select><br/>"; 


$disgoodname=empty($_GET['goodname'])?$_POST['goodname']:$_GET['goodname'];
$disgoodtype=empty($_GET['goodtype'])?$_POST['goodtype']:$_GET['goodtype'];
$disgoodbrand=empty($_GET['goodbrand'])?$_POST['goodbrand']:$_GET['goodbrand'];
$disgoodcolor=empty($_GET['goodcolor'])?$_POST['goodcolor']:$_GET['goodcolor'];
$disgoodmark1=empty($_GET['goodmark1'])?$_POST['goodmark1']:$_GET['goodmark1'];
//$disgoodmark2=empty($_GET['goodmark2'])?$_POST['goodmark2']:$_GET['goodmark2'];
$disgoodrealprice=empty($_GET['good_realbuyprice'])?0:$_GET['good_realbuyprice'];
echo "<fieldset style='width:600'><legend>查询商品</legend>商品名字:<input type='text' name='goodname' value='{$disgoodname}'/><font color='red'>*</font><br/>
型　　号:<input type='text' name='goodtype' value='{$disgoodtype}'/><br/>
品　　牌:<input type='text' name='goodbrand' value='{$disgoodbrand}'/><br/>
商品颜色:<input type='text' name='goodcolor' value='{$disgoodcolor}'/>&nbsp;<input  type='submit'  value='查询' name='submit2'/>(输入名字/型号/品牌查询)</fieldset><br/>

备　　注:<input type='text' name='goodmark1' value='{$disgoodmark1}'/>&nbsp;</br>

商品数量:<input type='number' name='goodnumber' value='{$_GET['goodnumber']}'/>（个）<font color='red'>*</font><br/>
进货价格:<input type='number' name='good_buyprice' value='{$_GET['good_buyprice']}'/>（元）<font color='red'>*</font><br/>";
if($_SESSION['usertype']=='admin')echo "实际进价:<input type='number' name='good_realbuyprice' value=''/>（元）<br/>";
echo "条 形 码:<input type='number' name='goodserialnumber' value='{$_GET['goodserialnumber']}'/><br/>
<label for='file'>缩 略 图:</label>
<input type='file' name='imgfile' id='file'><br>
";
echo "销售价格:<input type='number' name='good_saleprice' value='{$_GET['good_saleprice']}'/>（元）<br/>
控　　价:<input type='number' name='good_controlprice' value='{$_GET['good_controlprice']}'/>（元）<br/>
厂　　家:<input type='text' name='factoryname' value='{$_GET['factoryname']}'/><br/>
厂家电话:<input type='text' name='factorytel' value='{$_GET['factorytel']}'/><br/><br/>


 　　<button class='mybtn' type='submit'  name='submit1'>添加</button>　 <button class='mybtn'  onclick='reset_onclick(this);' >清空</button>&nbsp;";
   

if($_SESSION['usertype']=='admin')
	echo "　　<a href='admin.php'>Back</a></form>";
	else echo "　　<a href='employ.php'>Back</a></form>";
//echo "<font color='red'>提示，请认真填写</font>（系统视名字跟型号一样则为同一种商品）";
if(!empty($_GET['erno']))
	//echo "<br/><font color='red' size='3'>".$_GET['erno']."</font>";
	echo "<br/><font color='red' size='3'>输入有误</font>";
if(isset($_POST['submit1']))
{


$shopid=$_POST['shopid'];
$empname=$_POST['empname'];

$good_name=$_POST['goodname'];
$good_serialnumber=$_POST['goodserialnumber'];
$good_brand=$_POST['goodbrand'];
$good_type=$_POST['goodtype'];
$good_mark1=$_POST['goodmark1'];
    //$good_mark2=$_POST['goodmark2'];
$good_number=$_POST['goodnumber'];
$factory_name=$_POST['factoryname'];
$factory_tel=$_POST['factorytel'];
$good_buyprice=$_POST['good_buyprice'];
if(!empty($_POST['good_realbuyprice']))$good_realbuyprice=$_POST['good_realbuyprice'];
$good_saleprice=$_POST['good_saleprice'];
$good_controlprice=$_POST['good_controlprice'];
$good_color=$_POST['goodcolor'];

//
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["imgfile"]["name"]);

$extension = end($temp);
if ((($_FILES["imgfile"]["type"] == "image/gif")
|| ($_FILES["imgfile"]["type"] == "image/jpeg")
|| ($_FILES["imgfile"]["type"] == "image/jpg")
|| ($_FILES["imgfile"]["type"] == "image/bmp")
|| ($_FILES["imgfile"]["type"] == "image/pjpeg")
|| ($_FILES["imgfile"]["type"] == "image/x-png")
|| ($_FILES["imgfile"]["type"] == "image/png"))
&& ($_FILES["imgfile"]["size"] < 500000)
&& in_array($extension, $allowedExts))
{
if ($_FILES["imgfile"]["error"] > 0)
{
echo "Error: " . $_FILES["imgfile"]["error"] . "<br>";
}
else
{
$sql="select max(goods_id)+1 from goods";
$res=$sqlhelper->execute_dql($sql);
if($row=mysql_fetch_array($res))
$imgname=$row[0];	


if (file_exists("goodimg/" . $_FILES["imgfile"]["name"]))
{
echo $_FILES["imgfile"]["name"] . " already exists. ";
}
else
{
$imgupload=($stor->upload("goodimg",$imgname,$_FILES['imgfile']['tmp_name']));
if ($imgupload!=false){
echo "<br/>图片".$_FILES['imgfile']['name']."上传成功,对应于ID为".$imgname."的商品<br/>";
}
//move_uploaded_file($_FILES["imgfile"]["tmp_name"],
//"goodimg:".$imgname);
//echo "<br/>图片".$imgname."上传成功<br/>";
}
}
}

else
{
echo "<font color='red'>图片格式为gif、jpeg、jpg、bmp、pjepg、x-png、png且不超过500k</font><br><br>";
}
//
$fieldname="goods_".$shopid."_shop";
if(isset($_POST['good_realbuyprice']))
	{
	if($imgupload==true)
		$sql="insert into goods(goods_flag,goods_mark1,goods_mark2,goods_serialnumber,goods_brand,goods_type,goods_name,goods_buyprice,goods_realbuyprice,goods_saleprice,goods_controlprice,goods_buynumber,".$fieldname.",goods_buytime,factory_name,factory_tel,goods_color,goods_emp) 
			values(1,'$good_mark1','$good_mark1','$good_serialnumber','$good_brand',trim('$good_type'),trim('$good_name'),'$good_buyprice','$good_realbuyprice','$good_saleprice','$good_controlprice','$good_number','$good_number',now(),'$factory_name','$factory_tel','$good_color','$empname')";
	else
	$sql="insert into goods(goods_mark1,goods_mark2,goods_serialnumber,goods_brand,goods_type,goods_name,goods_buyprice,goods_realbuyprice,goods_saleprice,goods_controlprice,goods_buynumber,".$fieldname.",goods_buytime,factory_name,factory_tel,goods_color,goods_emp) 
			values('$good_mark1','$good_mark1','$good_serialnumber','$good_brand',trim('$good_type'),trim('$good_name'),'$good_buyprice','$good_realbuyprice','$good_saleprice','$good_controlprice','$good_number','$good_number',now(),'$factory_name','$factory_tel','$good_color','$empname')";

		$outcome=$good_realbuyprice*$good_number;
	}
else
	{
	if($imgupload==true)
		$sql="insert into goods(goods_flag,goods_mark1,goods_mark2,goods_serialnumber,goods_brand,goods_type,goods_name,goods_buyprice,goods_saleprice,goods_controlprice,goods_buynumber,".$fieldname.",goods_buytime,factory_name,factory_tel,goods_color,goods_emp) 
			values(1,'$good_mark1','$good_mark1','$good_serialnumber','$good_brand',trim('$good_type'),trim('$good_name'),'$good_buyprice','$good_saleprice','$good_controlprice','$good_number','$good_number',now(),'$factory_name','$factory_tel','$good_color','$empname')";
	else 
		$sql="insert into goods(goods_mark1,goods_mark2,goods_serialnumber,goods_brand,goods_type,goods_name,goods_buyprice,goods_saleprice,goods_controlprice,goods_buynumber,".$fieldname.",goods_buytime,factory_name,factory_tel,goods_color,goods_emp) 
			values('$good_mark1','$good_mark1','$good_serialnumber','$good_brand',trim('$good_type'),trim('$good_name'),'$good_buyprice','$good_saleprice','$good_controlprice','$good_number','$good_number',now(),'$factory_name','$factory_tel','$good_color','$empname')";

	$outcome=$good_buyprice*$good_number;
	}

$sql2="insert into outcome(outcome_number,outcome_goodname,outcome_time,outcome_employname) values(".$outcome.",'$good_name',now(),'$empname')";
$sqlhelper->execute_dml($sql2);
$sql3="select * from goods where goods_name='$good_name' and goods_type='$good_type'";
$res3=$sqlhelper->execute_dql($sql3);
if($row3=mysql_fetch_assoc($res3))
	{
	
		//$sql4="update goods set goods_flag=1 where goods_name='$good_name' and goods_type='$good_type'";
		$sqlhelper->execute_dml($sql);

		//$sqlhelper->execute_dml($sql4);
		echo "同名商品,更新成功<a href='add_good.php'>返回添加</a>";
	}
else 
{$sqlhelper->execute_dml($sql);echo "</br>新商品添加成功，直接点击查询可以查看详细信息</br><a href='admin_goods.php'>前往库存</a>&nbsp;&nbsp;<a href='add_good.php'>返回添加</a>";}
	/////////////////////////
if($_SESSION['usertype']=='admin')echo "　　　<a href='admin.php'>返回主目录</a>";
else echo "　　　<a href='employ.php'>返回主目录</a>";
}
if(isset($_POST['submit2']))
{$sale_goodbrand=$_POST['goodbrand'];$sale_goodname=$_POST['goodname'];$sale_goodtype=$_POST['goodtype'];$sale_goodcolor=$_POST['goodcolor'];
if(empty($sale_goodbrand)&&empty($sale_goodname)&&empty($sale_goodtype)&&empty($sale_goodcolor))
{echo "<br/>名字、品牌、类型、颜色至少输入一个";exit;}
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
 echo "<table class='mysaletable' style='position:absolute;left:0;top:550;'>";
			echo "<tr  bgcolor='#A7C942'><th style='border-style:none;background-color:white;width:10;'></th><th>ID</th><th>商品名字</th><th>型号</th><th>商品系列号</th><th>备注</th><th>进货数量</th><th>总库余量</th>".$str5."<th>进价</th><th>建议售价</th><th>控价</th><th>品牌</th><th>颜色</th><th>入库店员</th><th>厂家</th><th>厂家电话</th><th>进货日期</th></tr></br>";		
					
			while($row=mysql_fetch_array($res))
			{	$str6="<tr class='mytr' onclick='do_onclick(this)'><th><a href='add_good.php?goodserialnumber={$row[4]}&goodname={$row[2]}&goodtype={$row[3]}&goodbrand={$row[$shopnumber+11]}&goodcolor={$row[$shopnumber+12]}&goodnumber={$row[6]}&good_buyprice={$row[$shopnumber+8]}&good_saleprice={$row[$shopnumber+9]}&good_controlprice={$row[$shopnumber+10]}&factoryname={$row[$shopnumber+14]}&factorytel={$row[$shopnumber+15]}'>使用</a></th>";
					for($i=1;$i<$shopnumber+17;$i++)
					{
						$str6.="<th>{$row[$i]}</th>";	
						
					}	
						//$str6=htmlspecialchars_decode("</tr></br>");
						$str6=$str6."</tr>";
						echo $str6;
						
			}echo "</table>";
}
//******************************************************************************************
if(isset($_POST['marksubmit']))
    {
    // if($_SESSION['usertype']=='admin')
    // {
            $mymark=$_POST['marktexts'];
            $sqlmark="update marks set mark_text='$mymark' where mark_page='addgood'";
            $sqlhelper->execute_dml($sqlmark);
         // }else {echo"<script language='javascript'>alert('只有管理员才能编辑此项')</script>";exit;}
    }
    $sqlmark="select mark_text from marks where mark_page='addgood'";
    $resmark=$sqlhelper->execute_dql($sqlmark);
    if($resmark)
    {
        if($markrow=mysql_fetch_array($resmark))
            $marktext=$markrow[0];
        else
            $marktext="请管理员添加注释并点击上面按钮提交：";
        echo "<div style='position:absolute;left:800;top:20;'><form id='mymark' action='add_good.php' method='post'><input type='submit' value='修改注释' name='marksubmit'></form><textarea form='mymark' name='marktexts'cols=40 rows=30>".$marktext."</textarea></div>";
    }
    

//*******************************************************************************
?>
</body>
</html>
