<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
if($_SESSION['usertype']!='admin'){echo "非管理员";exit;}
echo "<link href='mytable.css' rel='stylesheet' type='text/css'/> ";
echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
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
                    width:450,
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
require_once 'SqlHelper.class.php';
 echo '<b style="font-size:20;">修改商品</b><br/>';
echo "<div style='position:absolute;margin-top:10;margin-left:10;'>";
$sqlhelper=new SqlHelper();

if(isset($_POST['submit2']))
{

$shop_id=$_POST['shopid'];
$employ_name=$_POST['empname'];
$good_id=$_POST['goodid'];
$good_mark1=$_POST['goodmark1'];

$good_name=$_POST['goodname'];
$good_serialnumber=$_POST['goodserialnumber'];
$good_brand=$_POST['goodbrand'];
$good_type=$_POST['goodtype'];
$good_number=$_POST['goodnumber'];
$factory_name=$_POST['factoryname'];
$factory_tel=$_POST['factorytel'];
$good_buyprice=$_POST['good_buyprice'];
$good_realbuyprice=$_POST['good_realbuyprice'];
$good_saleprice=$_POST['good_saleprice'];
$good_controlprice=$_POST['good_controlprice'];
$good_color=$_POST['goodcolor'];

$flag=1;
//////
if(!empty($_FILES["imgfile"]["name"]))
{
$allowedExts = array("gif","bmp","jpeg", "jpg", "png");
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
		$imgname=$good_id;
		//move_uploaded_file($_FILES["imgfile"]["tmp_name"],
		//"goodimg/".$good_id);
		$stor = new SaeStorage();
		if($stor->fileExists('goodimg',$imgname))$stor->delete('goodimg',$imgname);
		
		if (false!=$stor->upload("goodimg",$imgname,$_FILES['imgfile']['tmp_name'])){
		echo "<br/>图片".$_FILES['imgfile']['name']."上传成功,对应于ID为".$imgname."的商品<br/>";$flag=0;
		$mysq="update goods set goods_flag=1 where goods_id={$imgname}";
		$sqlhelper->execute_dml($mysq);
		}

	}
}//////
	else
	{
		echo "<font color='red'>图片格式为gif、jpeg、jpg、bmp、pjepg、x-png、png且不超过500k</font><br><br>";
	}
}
if(!empty($good_name)){$sql="update goods set goods_name=trim('$good_name') where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$sql3="update salerecord set sale_goodname='$good_name' where sale_goodid={$good_id}";$sqlhelper->execute_dml($sql3);$flag=0;}
if(!empty($employ_name)){$sql="update goods set goods_emp='$employ_name' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}

$sql2="select * from shop order by shop_id";

$res2=$sqlhelper->execute_dql($sql2);
$i=1;
    if(!empty($_POST['goods0shop']))
    {
        $sql="update goods set goods_0_shop=ifnull(goods_0_shop,0)+{$_POST['goods0shop']} where goods_id={$good_id}";
        $sqlhelper->execute_dml($sql);
        echo "修改总仓库库存成功</br>";
    }   
while($row2=mysql_fetch_array($res2))
    //for($i=0;$i<=$shopnumber;$i++)
{	$goodishop="goods".$i."shop";
    if(!empty($_POST[$goodishop]))
    {
        $sql="update goods set goods_{$i}_shop=ifnull(goods_{$i}_shop,0)+{$_POST[$goodishop]} where goods_id={$good_id}";
        $sqlhelper->execute_dml($sql);
        echo "修改".$row2[1]."库存成功</br>";
    }
    $i++;
}

if(!empty($shop_id))
{
		
		
		$shopstr="goods_0_shop=0";
		$sql8="select * from shop order by shop_id";
		$res8=$sqlhelper->execute_dql ( $sql8);
		$i=1;
		while($row8=mysql_fetch_array($res8))
		{
			$shopstr.=",goods_".$i."_shop=0";
			
			$i++;			
		}
	$sql2="update goods set ".$shopstr." where goods_id={$good_id}";$sqlhelper->execute_dml($sql2);
	$sql="update goods set goods_".$shop_id."_shop=goods_buynumber where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;
}
if(!empty($good_serialnumber)){$sql="update goods set goods_serialnumber='$good_serialnumber' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($good_brand)){$sql="update goods set goods_brand='$good_brand' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
    if(!empty($good_mark1)){$sql="update goods set goods_mark1='$good_mark1' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($good_type)){$sql="update goods set goods_type=trim('$good_type') where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($good_number)){$sql="update goods set goods_buynumber='$good_number' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($factory_name)){$sql="update goods set factory_name='$factory_name' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($factory_tel)){$sql="update goods set factory_tel='$factory_tel' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($good_buyprice)){$sql="update goods set goods_buyprice='$good_buyprice' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($good_realbuyprice)){$sql="update goods set goods_realbuyprice='$good_realbuyprice' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($good_saleprice)){$sql="update goods set goods_saleprice='$good_saleprice' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($good_controlprice)){$sql="update goods set goods_controlprice='$good_controlprice' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($good_color)){$sql="update goods set goods_color='$good_color' where goods_id={$good_id}";$sqlhelper->execute_dml($sql);$flag=0;}
if(!empty($good_type)||!empty($good_name))
{
	$sql="select goods_name,goods_type from goods  where goods_id={$good_id}";
	$res=$sqlhelper->execute_dql($sql);
	$row=mysql_fetch_array($res);
	$sql2="select goods_id from goods where goods_name='{$row[0]}' and goods_type='{$row[1]}'";
	$res2=$sqlhelper->execute_dql($sql2);
	if(mysql_num_rows($res2)>1){
	//$sql="update goods set goods_flag=1 where goods_id={$good_id}";$sqlhelper->execute_dml($sql);
	}
}
if($flag==0){echo "修改成功";echo "<a href='admin.php'>返回主目录</a><br>";exit;}
}
echo "<b>商品ID必填，不用修改的不填</b>";
echo "<form action='altergood.php' enctype='multipart/form-data' method='post'>";
echo   "入库到店:<select  name='shopid' style='width:173px'>";
echo "<option  value=''></option>";
	echo "<option  value='0'>总库</option>";
$sql2="select * from shop order by shop_id";

$res2=$sqlhelper->execute_dql($sql2);
$i=1;
while($row2=mysql_fetch_array($res2))
    //for($i=0;$i<=$shopnumber;$i++)
{	
    echo "<option  value='$i'>{$row2[1]}</option>";
    $i++;
}
echo "</select><font color='red'>注意</font>：修改入库店会清空现有库存恢复到进货量！<br/>";
$sql2="select * from employee";
$res2=$sqlhelper->execute_dql($sql2);
$empnum=mysql_num_rows($res2);

echo   "入库店员:<select  name='empname' style='width:173px'>";
echo "<option value=''></option>";
echo "<option value='admin'>管理员</option>";
while($row2=mysql_fetch_array($res2))
 echo "<option value={$row2[1]}>{$row2[1]}</option>";

echo "</select><br/>"; 
$idvalue=empty($_GET['altergoodid'])?$_POST['goodid']:$_GET['altergoodid'];
echo "商 品 ID:<form action='altergood.php' method='post'><input type='text' name='goodid' required='required' value='{$idvalue}'><font color='red'>*</font><input type='submit' name='submit1' value='查询'>(输入ID点击查询会查到商品信息)</br>
商品名字:<input type='text' name='goodname' ><br/>
型　　号:<input type='text' name='goodtype'/><br/>
品　　牌:<input type='text' name='goodbrand'/><br/>
备&nbsp;&nbsp;&nbsp;注 :<input type='text' name='goodmark1' />&nbsp;</br>
条 形 码:<input type='number' name='goodserialnumber' /><br/>
商品颜色:<input type='text' name='goodcolor'/><br/>
<label for='file'>缩 略 图:</label>
<input type='file' name='imgfile' id='file'>(<200k)<br>
进货数量:<input type='number' name='goodnumber' />";
if($_SESSION['usertype']=='admin')
{	echo "</br><font color='blue'>下行重新分配库存时用(正值表示增加，负值表示减少)</font></br>";
    echo "总库:<input type='number' name='goods0shop' style='width:80px' value='0'/>";
    $sql2="select * from shop order by shop_id";
    $res2=$sqlhelper->execute_dql($sql2);
    $i=1;
    while($row2=mysql_fetch_array($res2))
        //for($i=0;$i<=$shopnumber;$i++)
    {	
        echo $row2[1].":<input type='number' name='goods{$i}shop' style='width:80px' value='0'/>";
        $i++;
    }
}
echo "（个）<br/>
进货价格:<input type='number' name='good_buyprice' />（元）<br/>
实际进价:<input type='number' name='good_realbuyprice' />（元）<br/>
销售价格:<input type='number' name='good_saleprice'/>（元）<br/>
控　　价:<input type='number' name='good_controlprice'/>（元）<br/>
厂　　家:<input type='text' name='factoryname'/><br/>
厂家电话:<input type='text' name='factorytel'/><br/><br/>
<button class='mybtn' type='submit' value='submit' name='submit2'>修改</button>
 　
  <button  onclick='reset_onclick(this);' >重置</button>&nbsp; 　　<a href='admin.php'>返回</a>";
    
	echo "</form>";

//echo "<a href='admin.php'>返回</a>";
echo "</div>";
//******************************************************************************************
if(isset($_POST['marksubmit']))
    {
    // if($_SESSION['usertype']=='admin')
    // {
            $newmark=$_POST['addmarktexts'];
            $sqlmark="update marks set mark_text='$newmark' where mark_page='altergood'";
            $sqlhelper->execute_dml($sqlmark);
         // }else {echo"<script language='javascript'>alert('只有管理员才能编辑此项')</script>";exit;}
    }
    $sqlmark="select mark_text from marks where mark_page='altergood'";
    $resmark=$sqlhelper->execute_dql($sqlmark);
    if($resmark)
    {
        if($markrow=mysql_fetch_array($resmark))
            $marktext=$markrow[0];
        else
            $marktext="请添加注释并点击上面按钮提交：";
        echo "<div style='position:absolute;left:850;top:20;'><form id='mark' action='altergood.php' method='post'><input type='submit' value='修改注释' name='marksubmit'></form><textarea form='mark' name='addmarktexts'cols=40 rows=30>".$marktext."</textarea></div>";
    }
//*******************************************************************************
if(isset($_POST['submit1']))
{
		$good_id=$_POST['goodid'];


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
		echo "<table id='mytable' style='position:absolute;top:550;'>";
		echo "<tr  bgcolor='#A7C942'><th>ID</th><th>商品名字</th><th>型号</th><th>商品系列号</th><th>进货数量</th><th>总库余量</th>".$str5."<th>进价</th><th>建议售价</th><th>控价</th><th>品牌</th><th>颜色</th><th>入库店员</th><th>厂家</th><th>厂家电话</th><th>进货日期</th></tr></br>";		
				$str6="<tr>";
		if($row=mysql_fetch_array($res))
{	
				for($i=1;$i<$shopnumber+16;$i++)
				{
					$str6.="<th>{$row[$i]}</th>";	
					
				}	
					//$str6=htmlspecialchars_decode("</tr></br>");
					$str6=$str6."</tr>";
					echo $str6."</table>";
}else echo "<font color='red'>商品ID不正确！</font>";
}
?>