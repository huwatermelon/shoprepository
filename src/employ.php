<?php 
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
if($_SESSION['usertype']!='employ'){echo "非雇员";exit;}
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
$employname=$_SESSION['name'];
$shop=$_SESSION['shop'];
$sq="select shop_name from shop where shop_id=$shop";
$re=$sqlhelper->execute_dql($sq);
$shopname=mysql_result($re,0);

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
echo "<h1>欢迎".$shopname."的".$employname."店员登录系统</h1>";
echo "<a href='add_sale_good.php?employname={$_SESSION['name']}'><button class='mybtn'>销售商品</button></a><br>
<a href='add_good.php' ><button class='mybtn'>进&nbsp;&nbsp;货</button></a></br>
<a href='employ_goods.php?shopid=$shop'><button class='mybtn'>库存</button></a><br>
<a href='adjust_goods.php'><button class='mybtn'>调货申请</button></a><br>
<a href='sale_record.php'><button class='mybtn'>销售记录</button></a><br>
<a href='exit.php'><button class='mybtn'>安全退出</button></a><br>";
//******************************************************************************************
if(isset($_POST['marksubmit']))
    {
    // if($_SESSION['usertype']=='admin')
    // {
            $newmark=$_POST['addmarktexts'];
            $sqlmark="update marks set mark_text='$newmark' where mark_page='admin'";
            $sqlhelper->execute_dml($sqlmark);
         // }else {echo"<script language='javascript'>alert('只有管理员才能编辑此项')</script>";exit;}
    }
    $sqlmark="select mark_text from marks where mark_page='admin'";
    $resmark=$sqlhelper->execute_dql($sqlmark);
    if($resmark)
    {
        if($markrow=mysql_fetch_array($resmark))
            $marktext=$markrow[0];
        else
            $marktext="请添加注释并点击上面按钮提交：";
        echo "<div style='position:absolute;left:800;top:20;'><form id='mark' action='employ.php' method='post'><input type='submit' value='保存' name='marksubmit'></form><textarea form='mark' name='addmarktexts'cols=40 rows=30>".$marktext."</textarea></div>";
    }
//*******************************************************************************
$sql="select adjust_id,adjust_goodid,adjust_toshopname,adjust_fromshopname,adjust_goodnumber,adjust_employname,adjust_time,adjust_state from adjustgoods where adjust_employname='$employname' and adjust_state='未批准'";
$res=$sqlhelper->execute_dql( $sql);
if(0!=mysql_num_rows($res))
   {
 
   
 echo "<br><br><br><br><br>您还有调货未被审核，请速联系管理员或货源店：";
echo "<table  border='1px' bordercolor='green'  cellspacing='0px'  width='700px'>";
echo "<tr bgcolor='#A7C942'><th>调货分店</th><th>货源分店</th><th>商品名字</th><th>商品类型</th><th>商品品牌</th><th>调货数量</th><th>申请店员</th><th>申请日期</th><th>状态</th></tr></br>";
        while($row=mysql_fetch_assoc($res))
        {
       		$adjustgoodid=$row['adjust_goodid'];
            $sql2="select goods_name,goods_type,goods_brand from goods where goods_id=$adjustgoodid";
            $res2=$sqlhelper->execute_dql($sql2);
            $row2=mysql_fetch_array($res2);
            echo "<tr class='mytr' onclick='do_onclick(this)'><th>{$row['adjust_toshopname']}</th><th>{$row['adjust_fromshopname']}</th><th>{$row2[0]}</th><th>{$row2[1]}</th><th>{$row2[2]}</th><th>{$row['adjust_goodnumber']}</th><th>{$row['adjust_employname']}</th><th>{$row['adjust_time']}</th><th>待批准</th></tr></br>";
        }
   }
$sql3="select adjust_id,adjust_goodid,adjust_toshopname,adjust_fromshopname,adjust_goodnumber,adjust_employname,adjust_time,adjust_state from adjustgoods where adjust_employname='$employname' and adjust_state='未审核'";
$res3=$sqlhelper->execute_dql( $sql3);

if(0!=mysql_num_rows($res3))
   {
 
   
 echo "<br><br><br><br><br>您有调货被批准：";
echo "<table  border='1px' bordercolor='green'  cellspacing='0px'  width='700px'>";
echo "<tr bgcolor='#A7C942'><th>调货分店</th><th>货源分店</th><th>商品名字</th><th>商品类型</th><th>商品品牌</th><th>调货数量</th><th>申请店员</th><th>申请日期</th><th>状态</th></tr></br>";
        while($row=mysql_fetch_assoc($res))
        {
       		$adjustgoodid=$row['adjust_goodid'];
            $sql2="select goods_name,goods_type,goods_brand from goods where goods_id=$adjustgoodid";
            $res2=$sqlhelper->execute_dql($sql2);
            $row2=mysql_fetch_array($res2);
            echo "<tr class='mytr' onclick='do_onclick(this)'><th>{$row['adjust_toshopname']}</th><th>{$row['adjust_fromshopname']}</th><th>{$row2[0]}</th><th>{$row2[1]}</th><th>{$row2[2]}</th><th>{$row['adjust_goodnumber']}</th><th>{$row['adjust_employname']}</th><th>{$row['adjust_time']}</th><th>已批准</th></tr></br>";
        }
   }

$sql="select adjust_id,adjust_goodid,adjust_toshopname,adjust_fromshopname,adjust_goodnumber,adjust_employname,adjust_time,adjust_state from adjustgoods where adjust_fromshopname='$shopname' and adjust_state='未批准'";
$res=$sqlhelper->execute_dql($sql);
if(0!=mysql_num_rows($res))
{
    echo "<br><br><br><br><br><font style='position:absolute;top:450;left:20px;' color='red'>您有调货未审核</font>";
	echo "<table  border='1px' bordercolor='green'  cellspacing='0px'  style='position:absolute;top:550;left:20px;' width='700px'>";
	echo "<tr bgcolor='#A7C942'><th>调货分店</th><th>货源分店</th><th>申请店员</th><th>商品名字</th><th>商品类型</th><th>商品品牌</th><th>调货数量</th><th>申请日期</th><th>是否批准</th></tr></br>";  
      while($row=mysql_fetch_assoc($res))
        {
       		$adjustgoodid=$row['adjust_goodid'];
            $sql2="select goods_name,goods_type,goods_brand from goods where goods_id=$adjustgoodid";
            $res2=$sqlhelper->execute_dql($sql2);
            $row2=mysql_fetch_array($res2);
            echo "<tr class='mytr' onclick='do_onclick(this)'><th>{$row['adjust_toshopname']}</th><th>{$row['adjust_fromshopname']}</th><th>{$row['adjust_employname']}</th><th>{$row2[0]}</th><th>{$row2[1]}</th><th>{$row2[2]}</th><th>{$row['adjust_goodnumber']}</th><th>{$row['adjust_time']}</th><th><a href='employ.php?adjustid=$adjustid'>批准</a>&nbsp;<a href='employ.php?deladjustid=$adjustid'>拒绝</a></th></tr></br>";
        }
}
if(!empty($_GET['deladjustid']))
{
    $sql="update adjustgoods set adjust_state='分店拒绝' where adjust_id={$_GET['deladjustid']}";
    $sqlhelper->execute_dml($sql);
}
elseif(!empty($_GET['adjustid']))
{
    $sql="select * from adjustgoods where adjust_id={$_GET['adjustid']}";
    $res=$sqlhelper->execute_dql($sql);
    if($row=mysql_fetch_assoc($res))
    {
         if($row['adjust_state']!='未批准'){echo "<br><br><br><br><br><font style='position:absolute;top:420;left:20px;' color='red'>error!</font>";exit;}
        $adjustnumber=$row['adjust_goodnumber'];
        $adjustfromshopname=$row['adjust_fromshopname'];
        $adjusttoshopname=$row['adjust_toshopname'];
        $adjustgoodid=$row['adjust_goodid'];
        $sql="select shop_id from shop where shop_name=trim({$adjustfromshopname})";
		$res=$sqlhelper->execute_dql($sql);
   		$fromshopid=mysql_result($res,0);
        $sql="select shop_id from shop where shop_name=trim({$adjusttoshopname})";
		$res=$sqlhelper->execute_dql($sql);
   		$toshopid=mysql_result($res,0);
        $sql1="select goods_{$fromshopid}_shop from goods where goods_id={$row['adjust_goodid']}";
        $res1=$sqlhelper->execute_dql($sql1);
        $total=mysql_result($res1,0);
        if($total<$adjustnumber){echo "<font color=red>商品数量不够</font>";exit;}
        $sql1="update goods set goods_{$fromshopid}_shop=ifnull(goods_{$fromshopid}_shop,0)-$adjustnumber where goods_id=$adjustgoodid";
        $sql2="update goods set goods_{$toshopid}_shop=ifnull(goods_{$toshopid}_shop,0)+$adjustnumber where goods_id=$adjustgoodid";
        $sql3="update adjustgoods set adjust_state='未审核' where adjust_id=$adjustid";
        $sqlhelper->execute_dml($sql1);$sqlhelper->execute_dml($sql2);
        echo "<br><br><br><br><br><font style='position:absolute;top:420;left:20px;' color='blue'>调货成功！</font>";
                   $sqlhelper->execute_dml($sql3);
    }
   
}
?>


