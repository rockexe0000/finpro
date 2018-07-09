<?php




function userauth($ID, $PWD, $db_conn) {
    $sqlcmd = "SELECT * FROM user_table WHERE uid='$ID' ";
    $rs = querydb($sqlcmd, $db_conn);
    // var_dump($rs);
    $retcode = 0;
    if (count($rs) > 0) {
	$Password = $PWD;
	//echo  $Password . '<br />' ;

        $Password = sha1($PWD);
         //echo '<br />' . $ID .'<br />' . $Password . '<br />' . $rs[0]['password'];
        if ($Password == $rs[0]['password']) $retcode = 1;
    }
    return $retcode;
}
session_start();
session_unset();
require_once("../include/gpsvars.php");


$ErrMsg = "";
if (!isset($ID)) $ID = "";
if (!isset($PWD)) $PWD = "";
if (isset($Submit)) {
	require ("../include/configure.php");
	require ("../include/db_func.php");
	$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
    if (strlen($ID) > 0 && strlen($ID)<=16 && $ID==addslashes($ID)) {
        $Authorized = userauth($ID,$PWD,$db_conn);
		if ($Authorized) {
		    $sqlcmd = "SELECT * FROM user_table WHERE uid='$ID' ";
		    $rs = querydb($sqlcmd, $db_conn);
		    $LoginID = $rs[0]['uid'];
	        $_SESSION['LoginID'] = $LoginID;
			header ("Location:contactlist3.php");
			exit();
		}
		$ErrMsg = '<font color="Red">'
			. '您並非合法使用者或是使用權已被停止</font>';
    } else {
		$ErrMsg = '<font color="Red">'
			. 'ID錯誤，您並非合法使用者或是使用權已被停止</font>';
	}
  if (empty($ErrMsg)) $ErrMsg = '<font color="Red">登入錯誤</font>';
}
/*
if (isset($new)) {
        header("Location: contactlist2.php");
        exit();
    
}
*/
?>
<HTML>
<HEAD>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<title>登入系統</title>
</HEAD>
<script type="text/javascript">
<!--
function setFocus()
{
<?php if (empty($ID)) { ?>
    document.LoginForm.ID.focus();
<?php } else { ?>
    document.LoginForm.PWD.focus();
<?php } ?>
}
//-->
</script>
<body onload="setFocus()">

<div style="text-align:center;margin-top:20px;font-size:30px;font-weight:bold;">
<?php echo $ID; ?>
<?php echo $PWD; ?>
</div>

<div style="text-align:center;margin-top:20px;font-size:30px;font-weight:bold;">
I3360 資料庫
</div>
<div style="text-align:center;margin:20px;font-size:20px;">
程式設計：I4B63 張再富
</div>
<div style="text-align:center">
請於輸入框中輸入帳號與密碼，然後按「登入」按鈕登入。
<form method="POST" name="LoginForm" action="">
<table width="300" border="1" cellspacing="0" cellpadding="2"
align="center" bordercolor="Blue">
<tr bgcolor="#FFCC33" height="35">
<td align="center">登入系統
</td>
</tr>
<tr bgcolor="#FFFFCC" height="35">
<td align="center">帳號：
  <input type="text" name="ID" size="16" maxlength="16"
	value="<?php echo $ID; ?>">
</td>
</tr>
<tr bgcolor="#FFFFCC" height="35">
<td align="center">密碼：
  <input type="password" name="PWD" size="16" maxlength="16">
</td>
</tr>
<tr bgcolor="#FFCC33" height="35">
<td align="center">
  <input type="submit" name="Submit" value="登入">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="contactadd3.php">註冊</a>
  
</td>
</tr>
</table>
</form>
<?php if (!empty($ErrMsg)) echo $ErrMsg; ?>



<br><br><br>


<div style="text-align:center;">
<table class="mistab" width="30%" align="center">
<tr>
  <th width="15%">產品名稱</th>
  <th width="15%">價格</th>
  <th width="15%">數量</th>
  <th width="15%">商店</th>
  <th width="15%">販賣期限</th>
  <th width="15%">產品類別</th>
</tr>
<?php

require_once('../include/configure.php');
require_once('../include/db_func.php');
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);

$sqlcmd = "SELECT * FROM product,user_table,product_class WHERE product.pnum>0 AND product.owner_id=user_table.uid AND product.class_no=product_class.class_id";
$Contacts = querydb($sqlcmd, $db_conn);


foreach ($Contacts AS $item) {
  $pname = $item['pname']; 
  $price = $item['price']; 
  $pnum = $item['pnum']; 
  $owner_id = $item['owner_id'];
  $deadline = $item['deadline'];
  $class_name = $item['class_name'];
  
     
?>
<tr align="center">
  <td><?php echo $pname ?></td>   
  <td><?php echo $price ?></td>
  <td><?php echo $pnum ?></td>
  <td><?php echo $owner_id ?></td>
  <td><?php echo $deadline ?></td>
  <td><?php echo $class_name ?></td>
</tr>
<?php }  ?>


</div>





</body>
</html>