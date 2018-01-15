<?php
header("Content-Type: text/html; charset=utf-8");
echo "<link href='mybtn.css' rel='stylesheet' type='text/css'/> ";
if(isset($_POST['login']))
{
if(empty($_POST['employ_oldname']))
	echo "<font clor='red'>必须输入用户名</font><br/>";
elseif(empty($_POST['employ_oripassword']))
	echo "<font clor='red'>必须输入原密码</font><br/>";
elseif((empty($_POST['employ_password2'])||empty($_POST['employ_password']))&&empty($_POST['employ_email'])&&empty($_POST['employ_name']))
	echo "<font clor='red'>没有要修改的信息</font><br/>";
else{
	$flag=0;
	require_once 'SqlHelper.class.php';
	$sqlhelper=new SqlHelper();
	$employoldname=$_POST['employ_oldname'];
	$employname=$_POST['employ_name'];
	$employoripassword=$_POST['employ_oripassword'];
	$employpassword=$_POST['employ_password'];
	$employpassword2=$_POST['employ_password2'];
	$employemail=$_POST['employ_email'];
	$sql1="select admin_password from administrator where admin_name='$employoldname'";
	$sql2="select employ_password from employee where employ_name='$employoldname'";	
	
	$res1=$sqlhelper->execute_dql($sql);
	if(null!=$res1)
	{
	

		if($row1['admin_password']==md5($employoripassword))
		{
					if(!empty($_POST['employ_name']))
					{
						$sql8="select * from administrator where admin_name='$employoldname'";
							$res8=$sqlhelper->execute_dql($sql8);
							if(null!=$res8)
								echo "<font clor='red'>账号已存在</font><br/><br/>";
							else{
							$sql3="update administrator set admin_name='$employname' where admin_name='$employoldname'";
							$flag=$sqlhelper->execute_dml($sql3);
							}
					}
			
			if(!empty($employpassword)&&($employpassword==$employpassword2))
			{	
				$sql4="update administrator set admin_password=md5('$employpassword') where admin_name='$employoldname'";
				$flag=$sqlhelper->execute_dml($sql4);		
			}
			if(!empty($employemail))
			{	
				$sql5="update administrator set admin_email='$employemail' where admin_name='$employoldname'";
				$flag=$sqlhelper->execute_dml($sql5);		
			}
		
		}else echo "<font clor='red'>1原密码不正确</font><br/>";

	}
	else 
	{
		$res2=$sqlhelper->execute_dql($sql2);
		
		
		if(null!=$res2)
		{
		
			$row2=mysql_fetch_assoc($res2);
			if($row2['employ_password']==md5($employoripassword))
			{
						if(!empty($_POST['employ_name']))
						{
							$sql7="select * from employee where employ_name='$employoldname'";
							$res7=$sqlhelper->execute_dql($sql7);
							if(null!=$res7)
								echo "<font clor='red'>账号已存在</font><br/><br/>";
								else{
								$sql4="update employee set employ_name='$employname' where employ_name='$employoldname'";
								$flag=$sqlhelper->execute_dml($sql4);
								}
						}
				
				if(!empty($employpassword)&&($employpassword==$employpassword2))
				{	
					$sql5="update employee set employ_password=md5('$employpassword') where employ_name='$employoldname'";
					$flag=$sqlhelper->execute_dml($sql5);		
				}
				if(!empty($employemail))
				{	
					$sql6="update employee set employ_email='$employemail' where employ_name='$employoldname'";
					$flag=$sqlhelper->execute_dml($sql6);		
				}
			}else echo "<font clor='red'>原密码不正确</font><br/><br/>";
		}else echo "<font clor='red'>没有此账户</font><br/><br/>";
	}
	if($flag!=0)echo "<font clor='blue'>修改成功</font><br/><br/>";
}
}
echo "<form action='employ_alter.php' method='POST'>";
echo "原账号：<input type='text' name='employ_oldname'><font clor='red'>*</font><br/><br/>";
echo "新账号：<input type='text' name='employ_name'><br/><br/>";
echo "原密码：<input type='password' name='employ_oripassword'><font clor='red'>*</font><br/><br/>";
echo "新密码：<input type='password' name='employ_password'><br/><br/>";
echo "确认密码：<input type='password' name='employ_password2'><br/><br/>";
echo "邮箱：<input type='email' name='employ_email'><br/><br/><br/>";
echo "<button type='submit' class='mybtn' name='login'>提交</button><br/><br/>";
echo "<button type='reset'  class='mybtn' name='cancel'>重填</button><br/>";

echo "</form>";
echo "<a href='index.php'><button class='mybtn'>取消</button></a>　";
