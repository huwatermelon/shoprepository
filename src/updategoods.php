<?php
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
	$sql1="select admin_shopnumber from administrator";
	$res1=$sqlhelper->execute_dql($sql1);
    if($row=mysql_fetch_assoc($res1))
      $shopnumber=$row['admin_shopnumber'];
	  	$goodshopnum="ifnull(goods_0_shop,0)";
	  for($i=1;$i<=$shopnumber;$i++)
		$goodshopnum.="+ifnull(goods_{$i}_shop,0)";
		$sql3="select {$goodshopnum} as total from goods where goods_id={$sale_goodid}";
		$res3=$sqlhelper->execute_dql($sql3);		
		if($row3=mysql_fetch_array($res3))
		 if($row3[0]==0){$sql5="delete from goods where goods_id={$sale_goodid}";$sqlhelper->execute_dml($sql5);}
		 echo "删除库存为空商品成功";
 ?>