<?php

	if($_SESSION['usertype']=='guest')
	{echo "对不起，您没有此权限";exit;}
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
$sql3="select goods_name,goods_type from goods where goods_id=$del_num";
$res3=$sqlhelper->execute_dql($sql3);
if($row3=mysql_fetch_array($res3))
{ $delgoodname=$row3[0];$delgoodtype=$row3[1];}
else {echo "error";exit;}
$ssql="delete from goods where goods_id=$del_num";
$ssql2="insert into deletegoods select * from goods where goods_id=$del_num";
//$sql2="update goods set goods_id = goods_id-1 where goods_id >$del_num";
//$sql3="ALTER TABLE goods AUTO_INCREMENT=1";
$sqlhelper->execute_dml($ssql);
echo "删除库存成功</br>";
$sql4="select goods_id from goods where goods_name='$delgoodname' and goods_type='$delgoodtype'";
$res4=$sqlhelper->execute_dql($sql4);
if(mysql_num_rows($res4)==1){$sql5="update goods set goods_flag=0";$sqlhelper->execute_dml($sql5);}
$sqlhelper->execute_dml($ssql2);
echo "添加删除记录成功</br>";

$stor->delete("goodimg",'$del_num');
echo "删除图片成功</br>";
echo "返回库存</br>";
//$sqlhelper->execute_dml($sql2);
//$sqlhelper->execute_dml($sql3);
$sale_goodid=$del_num;
include('updategoods.php');


