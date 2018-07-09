<?php
// 使用者點選放棄新增按鈕
if (isset($_POST['Abort']) && !empty($_POST['Abort'])) {
    header("Location: contactlist.php");
    exit();
}
// Authentication 認證
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
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

$sqlcmd = "SELECT * FROM user_table WHERE uid=? ";
$pdodb = $db_conn->prepare($sqlcmd);
$pdodb -> bindParam(1,$LoginID, PDO::PARAM_STR,12);
$pdodb -> execute();
$rs = $pdodb -> fetchall();

if (count($rs) <= 0) die ('Unknown or invalid user!');
$UserOwnerID = $rs[0]['uid'];
/*
$sqlcmd = "SELECT * FROM product WHERE pid='$pid'";
$rs = querydb($sqlcmd, $db_conn);
*/
if (!isset($OwnerID))  $OwnerID = $rs[0]['uid'];
if (!isset($Pname)) $Pname = '';
if (!isset($Price)) $Price = '';
if (!isset($Pnum)) $Pnum = '';
if (!isset($Deadline)) $Deadline = '';
if (!isset($Class_no)) $Class_no = '';
if (!isset($Phone)) $Phone = '';
if (!isset($Address)) $Address = '';
// 取出群組資料
$sqlcmd = "SELECT * FROM user_table WHERE uid='$UserOwnerID'";
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
if (isset($Confirm)) {   // 確認按鈕
    if (empty($Pname)) $ErrMsg = '姓名不可為空白\n';
	if (!isset($Price) || empty($Price)) $ErrMsg = '價錢不可為空白\n';
	if (!isset($Pnum) || empty($Pnum)) $ErrMsg = '數量不可為空白\n';
	if (!isset($Deadline) || empty($Deadline)) $ErrMsg = '期限不可為空白\n';
	if (!isset($Class_no) || empty($Class_no)) $ErrMsg = '種類不可為空白\n';
    if (empty($OwnerID) || $OwnerID<>addslashes($OwnerID)) $ErrMsg = '群組資料錯誤\n';
    
    if (empty($ErrMsg)) {
        $OwnerID = $UserOwnerID;
        $sqlcmd='INSERT INTO product (pname,price,pnum,owner_id,deadline,class_no) VALUES ('
            . "'$Pname','$Price','$Pnum','$OwnerID','$Deadline','$Class_no')";
        $result = updatedb($sqlcmd, $db_conn);
		
		$sqlcmd='INSERT INTO shop (sid,grade) VALUES ('
            . "'$OwnerID','2')";
        $result = updatedb($sqlcmd, $db_conn);
		
		$sqlcmd='INSERT INTO client (cid,time) VALUES ('
            . "'$OwnerID','0')";
        $result = updatedb($sqlcmd, $db_conn);

        //$sqlcmd = "SELECT count(*) AS reccount FROM product WHERE OwnerID IN $OwnerIDs ";
        //$rs = querydb($sqlcmd, $db_conn);
        //$RecCount = $rs[0]['reccount'];
        //$TotalPage = (int) ceil($RecCount/$ItemPerPage);
        //$_SESSION['CurPage'] = $TotalPage; 

        header("Location: contactlist.php");
        exit();
    }
}
$PageTitle = '示範新增產品資料';
require_once("../include/cssheader.php");
?>
<body>
<div style="text-align:center;margin-top:5px;font-size:20px;font-weight:bold;">
I3360 資料庫</div>
<div align="center">
<form action="" method="post" name="inputform">
<b>新增產品資料</b>
<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
<tr height="30">
  <th width="40%">名稱</th>
  <td><input type="text" name="Pname" value="<?php echo $Pname ?>" size="20"></td>
</tr>

<tr height="30">
  <th>商店</th>
  <td><select name="OwnerID">
<?php
    foreach ($GroupNames as $ID => $GroupName) {
        echo '    <option value="' . $ID . '"';
        if ($ID == $OwnerID) echo ' selected';
        echo ">$GroupName</option>\n";
    } 
?>
    </select>
  </td>
</tr>

<tr height="30">
  <th width="40%">價格</th>
  <td><input type="text" name="Price" value="<?php echo $Price ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">數量</th>
  <td><input type="text" name="Pnum" value="<?php echo $Pnum ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">期限</th>
  <td><input type="date" name="Deadline" value="<?php echo $Deadline ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">類別</th>
  <td><input type="text" name="Class_no" value="<?php echo $Class_no ?>" size="20"></td>
</tr>


</table>

<input type="submit" name="Confirm" value="存檔送出">&nbsp;
<input type="submit" name="Abort" value="放棄新增">
</form>
</div>
<?php 
require_once ('../include/footer.php');
?>
</body>
</html>