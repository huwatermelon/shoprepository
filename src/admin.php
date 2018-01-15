<?php 
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
if($_SESSION['usertype']!='admin'){echo "非管理员";exit;}
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
$sql5="select admin_shopnumber from administrator";
  $res5=$sqlhelper->execute_dql( $sql5);
if($row=mysql_fetch_assoc($res5))
    $shopnumber=$row['admin_shopnumber'];
	$_SESSION['shopnumber']=$shopnumber;
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

echo "<div style='text-align:10px;margin-top:10px;margin-left:50px;'><b >欢迎".	$_SESSION['name']."管理员登陆</b></br></br></div></br>";
echo "<div style='position:absolute;top:80;left:50px;'><a href='add_good.php' ><button class='mybtn'>进&nbsp;&nbsp;货</button></a></br>
<a href='add_sale_good.php?employname={$_SESSION['name']}'><button class='mybtn'>销售</button></a></br>
<a href='admin_goods.php'><button class='mybtn'>总库存</button></a></br>
<a href='sale_record.php'><button class='mybtn'>销售记录</button></a><br>
<a href='income.php'><button class='mybtn'>收入记录</button></a><br>
<a href='outcome.php'><button class='mybtn'>支出记录</button></a><br>
<a href='adjustrecord.php'><button class='mybtn'>调货记录</button></a><br>
<a href='search.php'><button class='mybtn'>查找</button></a><br></div>
<div style='position:absolute;top:80;left:450px;'><a href='altergood.php'><button class='mybtn'>修改商品</button></a><br><a href='createshop.php'><button class='mybtn'>分店管理</button></a><br>
<a href='add_employ.php'><button class='mybtn'>添加店员</button></a><br>
<a href='employeemanage.php'><button class='mybtn'>管理店员</button></a><br>
<a href='admin_alter.php'><button class='mybtn'>管理员修改资料</button></a><br>
<a href='javascript:void(0)' onclick='if(confirm(\"你确定要删除？\"))location.href=\"admin_reset.php\"'><button class='mybtn'>重置系统</button></a><br>
<a href='exit.php'><button class='mybtn'>安全退出</button></a><br></div>";

if(!empty($_GET['regsucc']))
	echo  "<br/><font style='position:absolute;top:450;left:20px;' color='red' size='3'>添加成功，请通知店员并提示其修改信息</font>";


if(!empty($_GET['deladjustid']))
{
    $sql="delete from adjustgoods where adjust_id={$_GET['deladjustid']}";
    //$sql="update  adjustgoods set adjust_state='已删除' where adjust_id={$_GET['deladjustid']}";
    $sqlhelper->execute_dml($sql);
}
elseif(!empty($_GET['adjustid']))
{
    $adjustid=$_GET['adjustid'];
    $sql="select * from adjustgoods where adjust_id=$adjustid";
    $res=$sqlhelper->execute_dql($sql);
    if($row=mysql_fetch_assoc($res))
    {
        if($row['adjust_state']!='未批准'){/*echo "<br><br><br><br><br><font style='position:absolute;top:420;left:20px;' color='red'>error!</font>";*/exit;}
        $adjustnumber=$row['adjust_goodnumber'];
        $adjustfromshopname=$row['adjust_fromshopname'];
        $adjusttoshopname=$row['adjust_toshopname'];
        $adjustgoodid=$row['adjust_goodid'];
        $sql="select shop_id from shop where shop_name='$adjustfromshopname'";
		$res=$sqlhelper->execute_dql($sql);
   		$fromshopid=mysql_result($res,0);
        $sql="select shop_id from shop where shop_name='$adjusttoshopname'";
		$res=$sqlhelper->execute_dql($sql);
   		$toshopid=mysql_result($res,0);
        $sql1="select goods_{$fromshopid}_shop from goods where goods_id={$row['adjust_goodid']}";
        $res1=$sqlhelper->execute_dql($sql1);
        $total=mysql_result($res1,0);
        if($total<$adjustnumber){echo "<font color=red>商品数量不够</font>";exit;}
        $sql1="update goods set goods_{$fromshopid}_shop=ifnull(goods_{$fromshopid}_shop,0)-$adjustnumber where goods_id=$adjustgoodid";
        $sql2="update goods set goods_{$toshopid}_shop=ifnull(goods_{$toshopid}_shop,0)+$adjustnumber where goods_id=$adjustgoodid";
        $sql3="update adjustgoods set adjust_state='已审核' where adjust_id=$adjustid";
        $sqlhelper->execute_dml($sql1);$sqlhelper->execute_dml($sql2);
        echo "<br><br><br><br><br><font style='position:absolute;top:420;left:20px;' color='blue'>调货成功！</font>";
                   $sqlhelper->execute_dml($sql3);
       
           
          
    }
   
}
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
        echo "<div style='position:absolute;left:700;top:20;'><form id='mark' action='admin.php' method='post'><input type='submit' value='保存' name='marksubmit'></form><textarea form='mark' name='addmarktexts'>".$marktext."</textarea></div>";
    }
//*******************************************************************************

/*
if(!empty($_GET['adjustname']))
{
    
    $adjustname=$_GET['adjustname'];
    $adjusttype=$_GET['adjusttype'];
    $adjustnumber=$_GET['adjustnumber'];
    $adjustemploy=$_GET['adjustemploy'];
    $shopid=$_GET['shopid'];
    //$shopname="shop_".$_GET['shopid']."_goods";
    //$sql1="delete from adjustgoods where adjust_goodname= '$adjustname' and adjust_shopid=$shopid";
    $sql1="update adjustgoods set adjust_state=1 where adjust_goodname= '$adjustname' and adjust_goodtype='adjusttype' and adjust_shopid='$shopid' and adjust_employname='$adjustemploy'";
    $sql2="select goods_serialnumber,goods_remain,goods_saleprice,goods_controlprice from goods where goods_name='$adjustname' ";
    // $sql4="update goods set goods_remain=goods_remain-$adjustnumber";
    $sql4="update goods set goods_".$shopid."_shop=goods_remain-$adjustnumber";
    $sqlhelper->execute_dml( $sql4);
    $res2=$sqlhelper->execute_dql( $sql2);
    if($row2=mysql_fetch_assoc($res2))
       {
       	
        $sql3="insert into ".$shopname." values({$row2['goods_serialnumber']},'$adjustname',$adjustnumber,{$row2['goods_saleprice']},{$row2['goods_controlprice']})";     
    	  $sqlhelper->execute_dml($sql3);
       }
       if(0==$sqlhelper->execute_dml($sql1));

}
*/     
 $sql="select adjust_id,adjust_goodid,adjust_toshopname,adjust_fromshopname,adjust_employname,adjust_goodnumber,adjust_time,adjust_state from adjustgoods where  adjust_state='未批准' or adjust_state='未审核'";
$res=$sqlhelper->execute_dql( $sql);
if(0!=mysql_num_rows($res))
   {
   
    echo "<br><br><br><br><br><font style='position:absolute;top:450;left:20px;' color='red'>您有调货动态需要处理:</font>";
echo "<table  border='1px' bordercolor='green'  cellspacing='0px'  style='position:absolute;top:550;left:20px;' width='700px'>";
    echo "<tr bgcolor='#A7C942'><th>调货ID</th><th>调入店</th><th>调出店</th><th>申请店员</th><th>商品名字</th><th>商品类型</th><th>商品品牌</th><th>调货数量</th><th>申请日期</th><th>处理</th></tr></br>";
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
            	echo "<tr class='mytr' onclick='do_onclick(this)'><th>{$row['adjust_id']}</th><th>".$inshopname."</th><th>".$outshopname."</th><th>{$row['adjust_employname']}</th><th>{$row2[0]}</th><th>{$row2[1]}</th><th>{$row2[2]}</th><th>{$row['adjust_goodnumber']}</th><th>{$row['adjust_time']}</th><th>
                <a href='admin.php?adjustid=$adjustid'>批准</a>&nbsp;<a href='admin.php?deladjustid=$adjustid'>删除</a></th></tr></br>";
            elseif($row['adjust_state']=='未审核')
            {
                echo "<tr class='mytr' onclick='do_onclick(this)'><th>{$row['adjust_id']}</th><th>".$inshopname."</th><th>".$outshopname."</th><th>{$row['adjust_employname']}</th><th>{$row2[0]}</th><th>{$row2[1]}</th><th>{$row2[2]}</th><th>{$row['adjust_goodnumber']}</th><th>{$row['adjust_time']}</th><th>
                已由分店批准</th></tr></br>";
                $sql="update adjustgoods set adjust_state=2 where adjust_id='$adjustid'";
                $sqlhelper->execute_dml($sql);
            }
        }
   }

?>

