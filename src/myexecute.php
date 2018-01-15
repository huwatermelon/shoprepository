<?php
require_once 'SqlHelper.class.php';
$sqlhelper=new SqlHelper();
$stor = new SaeStorage();
$total=$stor->getFilesNum("goodimg");
echo "total number:{$total}\n";
//$sql="update goods set goods_flag=0;";
//$sqlhelper->execute_dml($sql);

 $num = 0;
 while ( $ret = $stor->getList("goodimg",NULL,NULL,$num ) ) {
      foreach($ret as $file) {
          echo "{$file}\n";
		  if(1236==$file)
		if(false!= $stor->delete('goodimg',$file))echo "succeed";
		 // $sql1="update goods set goods_flag=1 where goods_id={$file}";
		  //$sqlhelper->execute_dml($sql1);
          $num ++;
      }
 }
 
 echo "\nTOTAL: {$num} files\n";
?>