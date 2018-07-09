<?php
// 使用者點選放棄新增按鈕
if (isset($_POST['Abort']) && !empty($_POST['Abort'])) {
    header("Location: index.php");
    exit();
}
// Authentication 認證
//require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
//header('Content-type: text/html; charset=utf-8');
session_start();
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
// echo 'I am here';
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
// echo 'I am here point 2';

/*
$sqlcmd = "SELECT * FROM user WHERE loginid='$LoginID' AND valid='Y'";
$rs = querydb($sqlcmd, $db_conn);
*/
/*
$sqlcmd = "SELECT * FROM user_table WHERE uid=? ";
$pdodb = $db_conn->prepare($sqlcmd);
$pdodb -> bindParam(1,$LoginID, PDO::PARAM_STR,12);
$pdodb -> execute();
$rs = $pdodb -> fetchall();

if (count($rs) <= 0) die ('Unknown or invalid user!');
$UserOwnerID = $rs[0]['uid'];
*/
/*
$sqlcmd = "SELECT * FROM product WHERE pid='$pid'";
$rs = querydb($sqlcmd, $db_conn);

if (!isset($OwnerID))  $OwnerID = $rs[0]['uid'];
if (!isset($Pname)) $Pname = '';
if (!isset($date)) $date = '';
if (!isset($number)) $number = '';
if (!isset($product_id)) $product_id = '';
if (!isset($shipment_id)) $shipment_id = '';
if (!isset($Phone)) $Phone = '';
if (!isset($Address)) $Address = '';
*/
if (!isset($uid)) $uid = '';
if (!isset($password)) $password = '';
if (!isset($uname)) $uname = '';
if (!isset($phone)) $phone = '';
if (!isset($address)) $address = '';
if (!isset($e_mail)) $e_mail = '';




// 取出群組資料
/*
$sqlcmd = "SELECT * FROM user_table";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs)<=0) die('No group could be found!');  
$GroupNames = array();
foreach ($rs as $item) {
    $ID = $item['uid'];
    $GroupNames[$ID] = $item['uname'];
}
$OwnerIDs = '';
foreach ($GroupNames as $ID => $GroupName) $OwnerIDs .= "','" . $ID;
$OwnerIDs = "(" . substr($OwnerIDs,2) . "')";
*/

if ( isset($_POST['Confirm'])  && !empty($_POST['Confirm']) ) {   // 確認按鈕
    
	if (!isset($uid) || empty($uid)) $ErrMsg = '帳號不可為空白\n';
	if (!isset($password) || empty($password)) $ErrMsg = '密碼不可為空白\n';
	if (!isset($uname) || empty($uname)) $ErrMsg = '名字不可為空白\n';
	if (!isset($phone) || empty($phone)) $ErrMsg = '電話不可為空白\n';
	if (!isset($address) || empty($address)) $ErrMsg = '地址不可為空白\n';
	if (!isset($e_mail) || empty($e_mail)) $ErrMsg = 'e-mail不可為空白\n';
	
	$PWD = sha1($password);
    //echo $uid.$password.$uname.$phone.$address.$e_mail;
    
    if (empty($ErrMsg)) {
        //$OwnerID = $UserOwnerID;
        $sqlcmd='INSERT INTO user_table (uid,password,uname,phone,address,e_mail) VALUES ('
            . "'$uid','$PWD','$uname','$phone','$address','$e_mail')";
        $result = updatedb($sqlcmd, $db_conn);
		
		$sqlcmd='INSERT INTO user_table (uid,password,uname,phone,address,e_mail) VALUES ('
            . "'$uid','$password','$uname','$phone','$address','$e_mail')";
        $result = updatedb($sqlcmd, $db_conn);
		
		$sqlcmd='INSERT INTO shop (sid,grade) VALUES ('
            . "'$uid','2')";
        $result = updatedb($sqlcmd, $db_conn);
		
		$sqlcmd='INSERT INTO client (cid,time) VALUES ('
            . "'$uid','0')";
        $result = updatedb($sqlcmd, $db_conn);

        //$sqlcmd = "SELECT count(*) AS reccount FROM product WHERE OwnerID IN $OwnerIDs ";
        //$rs = querydb($sqlcmd, $db_conn);
        //$RecCount = $rs[0]['reccount'];
        //$TotalPage = (int) ceil($RecCount/$ItemPerPage);
        //$_SESSION['CurPage'] = $TotalPage; 

        header("Location:index.php");
        exit();
		
    }
}
$PageTitle = '新增帳號資料';
require_once("../include/cssheader.php");
?>
<body>
<div style="text-align:center;margin-top:5px;font-size:20px;font-weight:bold;">
I3360 資料庫</div>
<div align="center">
<form action="" method="post" name="inputform">
<b>新增帳號</b>
<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">




<tr height="30">
  <th width="40%">帳號</th>
  <td><input type="text" name="uid" value="<?php echo $uid; ?>" size="20"></td>
</tr><tr height="30">
  <th width="40%">密碼</th>
  <td><input type="text" name="password" value="<?php echo $password; ?>" size="20"></td>
</tr><tr height="30">
  <th width="40%">名字</th>
  <td><input type="text" name="uname" value="<?php echo $uname; ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">電話</th>
  <td><input type="text" name="phone" value="<?php echo $phone; ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">地址</th>
  <td><input type="text" name="address" value="<?php echo $address; ?>" size="100"></td>
</tr>

<tr height="30">
  <th width="40%">e-mail</th>
  <td><input type="text" name="e_mail" value="<?php echo $e_mail; ?>" size="20"></td>
</tr>


</table>

<input type="submit" name="Confirm" value="送出">&nbsp;
<input type="submit" name="Abort" value="放棄新增">
</form>
</div>
<?php 
require_once ('../include/footer.php');
?>
</body>
</html>