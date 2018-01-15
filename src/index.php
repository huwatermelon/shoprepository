<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/> 
<!-- <title>Login</title> -->
<link href='mybtn.css' rel='stylesheet' type='text/css'/> 
</head>


    <body style="text-align:center;margin-top:100px;margin-left:100px;">
    <div style="margin:0px auto">
<form action="loginprocess.php" method="post">

名字:&nbsp;<input type="text" name="username"/><br/>
密码:&nbsp;<input type="password" name="password"/><br/>
<input type="radio" name="usertype" value="admin" checked="checked">管理员&nbsp;&nbsp;
<input type="radio" name="usertype" value="employ">店员<br/><br/>
        
　　　　<button name="submit" class='mybtn' type="submit">登陆</button>
<button class='mybtn' type="reset">重填</button>

<a href='employ_alter.php'>修改密码</a>
</form>
</div>
<?php 
if(!empty($_GET['errno']))
    echo  "<br/><font color='red' size='3'>账号或密码不正确</font>";
if(!empty($_GET['regsucc']))
	echo  "<br/><font color='red' size='3'>注册成功，请登录</font>";
if(!empty($_GET['altsucc']))
	switch ($_GET['altsucc']) {
		case 1:
		echo  "<br/><font color='red' size='3'>原账号不正确</font>";;
		break;
		case 2:
		echo  "<br/><font color='red' size='3'>原密码不正确</font>";;
		break;
		case 3:
		echo  "<br/><font color='red' size='3'>两次输入密码不正确</font>";;
		break;
		case 4:
		echo  "<br/><font color='red' size='3'>修改成功，请登录</font>";;
		break;
		default:
			echo "wrong";
		break;
	}
	
?>
</body>
</html>