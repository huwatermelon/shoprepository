<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
if(!isset($_SESSION['name'])){  
    header("Location:index.php");  
    exit();  }
echo "<form action='add_employ_process.php' method='post'>";
echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
$shopnumber=$_SESSION['shopnumber'];

 echo   "选择分店:<select  name='shopid' style='width:155px'>";
for($i=1;$i<=$shopnumber;$i++)
{
	
    echo "<option  value='$i'>{$i}分店</option>";
}
echo "</select><br/>";
 echo "姓　　名:<input type='text' name='employname'/>*<br/>";
echo "密　　码:<input type='password' name='employpassword'/>*<br/>";
echo "密码确认:<input type='password' name='employpassword2'>*<br/>";
echo "邮　　箱:<input type='email' name='employemail'/><br/>";
//echo "<input type='submit' value='提交'/>";
echo "<button  type='submit' class='mybtn'>提交</button>";

echo "　　<a href='admin.php'>取消</a>";
echo "</form>";

if(!empty($_GET['errno']))
	echo "<font color='red'>".$_GET['errno']."</font>"; 
?>

