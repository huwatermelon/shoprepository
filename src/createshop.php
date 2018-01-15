<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
if($_SESSION['usertype']!='admin'){echo "非管理员";exit;}
 require_once 'SqlHelper.class.php';

$sqlhelper=new SqlHelper();
if(!empty($_POST['shopnumber']))
{ 
	$newshopnumber=$_POST['shopnumber'];
    $sql1="select admin_shopnumber from administrator";
    $res1=$sqlhelper->execute_dql($sql1);
    if($row=mysql_fetch_assoc($res1))
        $oldshopnumber=$row['admin_shopnumber'];

    for($i=1;$i<=$oldshopnumber;$i++)
    {
        
		 $fieldname="goods_".$i."_shop";
       
		$sql3="alter table goods drop $fieldname";
       
		$sqlhelper->execute_dml($sql3);
    }
$sql6="truncate table shop;";
if(0!=$sqlhelper->execute_dml($sql6))
echo "删除旧店成功</br>";
    for($i=1;$i<=$newshopnumber;$i++)
    {
        

        //$shopname="shop_".$i."_goods";
		$fieldname="goods_".$i."_shop";
        $beforefieldname="goods_".($i-1)."_shop";
$sql4="alter table goods add $fieldname int(128) null after $beforefieldname";
$sql5="insert into shop(shop_name) values('$fieldname')";

if(0!=$sqlhelper->execute_dml($sql4)&&0!=$sqlhelper->execute_dml($sql5))
    echo "创建店".$i."成功！<br>";
       
    }
	echo "创建分店成功，请修改名称！<br>";
    $sql2="update administrator set admin_shopnumber=$newshopnumber";
    $sqlhelper->execute_dml($sql2);

    echo "<a href='admin.php'>返回</a>";
}

echo "<form action='altershopname.php' method='post'>";
echo "添加分店，名称：<input type='text' name='shopname' ><br>";
echo "<input type='submit' value='添加'></form>";


echo "</br><h style='color:red'>注意:<br>创建分店会删除原有分店！</h>";
echo "<form action='createshop.php' method='post'>";
echo "分店个数：<input name='shopnumber' ><br>";
echo "<input type='submit' value='创建'/></br></form>";
$sql1="select admin_shopnumber from administrator";
    $res1=$sqlhelper->execute_dql($sql1);
    if($row=mysql_fetch_assoc($res1))
        $oldshopnumber=$row['admin_shopnumber'];
		echo "当前有".$oldshopnumber."个分店，修改名称：</br>";
 echo "<form action='altershopname.php' method='post'>";

 for($i=1;$i<=$oldshopnumber;$i++)
 echo "{$i}分店:<input type='text' name='$i'/></br>";
 echo " <input type='submit' value='修改'></form>"; 

echo "<a href='admin.php'>返回主界面</a>";

?>


