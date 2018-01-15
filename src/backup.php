<?php
$stor = new SaeStorage();
$attr = array('private'=>false);
$ret = $stor->setDomainAttr("back", $attr); //必须为公有domain
$date = date('Y-m-d');
$dj = new SaeDeferredJob();
$taskID = $dj->addTask("export","mysql","back","backup/$date.sql.zip",SAE_MYSQL_DB,"","");
if($taskID===false){
var_dump($dj->errno(), $dj->errmsg());
$mail = new SaeMail();
$ret = $mail->quickSend( 'arthurhu@139.com' , '数据库备份失败' , '数据库备份失败 errno:'.$dj->errno().' errmsg:'.$dj->errmsg(), 'huwatermelon@gmail.com' , '390713arthurhu16' ); //邮箱通知
}else{
var_dump($taskID);
}
$attr = array('private'=>true);
$ret = $stor->setDomainAttr("back", $attr); //设置domain为private，保护数据

?>
