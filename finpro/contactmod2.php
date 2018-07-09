<?php
// 使用者點選放棄修改按鈕
if (isset($_POST['Abort']) && !empty($_POST['Abort'])) {
    header("Location: contactlist2.php");
    exit();
}
// Authentication 認證
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
// 確認參數是否正確
if (!isset($oid)) die ("Parameter error!");
// 找出此用戶的群組

$sqlcmd = "SELECT * FROM user_table WHERE uid=? ";
$pdodb = $db_conn->prepare($sqlcmd);
$pdodb -> bindParam(1,$LoginID, PDO::PARAM_STR,12);
$pdodb -> execute();
$rs = $pdodb -> fetchall();

if (count($rs) <= 0) die ('Unknown or invalid user!');
$UserOwnerID = $rs[0]['uid'];

// Authorization 授權
// =====================================================
// 先取得這筆資料的群組，再檢查這個帳號是否有權限
$sqlcmd = "SELECT * FROM order_table WHERE oid='$oid'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die("找不到編號為 $oid 之資料");
$GID = $rs[0]['client_id'];
if ($GID<>$UserOwnerID) {   // 非本單位人員，看看是否有額外權限
    $sqlcmd = "SELECT privilege FROM privileges "
        . "WHERE loginid='$LoginID' and uid='$GID' ";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) <= 0) die("您對編號 $oid 之資料無修改權限");
}
// =====================================================
if (!isset($date)) $date = '';
if (!isset($number)) $number = '';
if (!isset($product_id)) $product_id = '';
if (!isset($shipment_id)) $shipment_id = '';
if (!isset($Phone)) $Phone = '';

// 處理使用者異動之資料
if (isset($Confirm)) {   // 確認按鈕
    if (!isset($date) || empty($date)) $ErrMsg = '日期不可為空白\n';
	if (!isset($number) || empty($number)) $ErrMsg = '數量不可為空白\n';
	if (!isset($product_id) || empty($product_id)) $ErrMsg = '商品不可為空白\n';
	if (!isset($shipment_id) || empty($shipment_id)) $ErrMsg = '快遞不可為空白\n';
    if (empty($OwnerID) || $OwnerID<>addslashes($OwnerID)) $ErrMsg = '群組資料錯誤\n';
    if (!isset($OwnerID) || empty($OwnerID) || $OwnerID<>addslashes($OwnerID)) 
        $ErrMsg = '群組資料錯誤\n';
    if (empty($ErrMsg)) {   // 資料經初步檢核沒問題
    // Demo for XSS
    //    $Name = xssfix($Name);
    //    $Price = xssfix($Price);
    // Demo for the reason to use addslashes
        if (!get_magic_quotes_gpc()) {
            $date = addslashes($date);
            $number = addslashes($number);
			$product_id = addslashes($product_id);
			$shipment_id = addslashes($shipment_id);
			//$Class_no = addslashes($Class_no);
            
        }
        $sqlcmd="UPDATE order_table SET date='$date',number='$number',product_id='$product_id',shipment_id='$shipment_id' "
            . " WHERE oid='$oid'";
        $result = updatedb($sqlcmd, $db_conn);
        header("Location: contactlist2.php");
        exit();
    }
}
if (!isset($Name)) {    
// 此處是在contactlist.php點選後進到這支程式，因此要由資料表將欲編輯的資料列調出
    $sqlcmd = "SELECT * FROM order_table WHERE oid='$oid'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) <= 0) die('No data found');      // 找不到資料，正常應該不會發生
    /*
	$Name = $rs[0]['pname'];
    $Price = $rs[0]['price'];
	$Pnum = $rs[0]['pnum'];
	$Deadline = $rs[0]['deadline'];
	$Class_no = $rs[0]['class_no'];
	*/
	$oid = $rs[0]['oid'];
    $date = $rs[0]['date']; 
    $number = $rs[0]['number']; 
    $client_id = $rs[0]['client_id'];
    $product_id = $rs[0]['product_id'];
    $shipment_id = $rs[0]['shipment_id'];
    
    $OwnerID = $rs[0]['client_id'];
} else {    // 點選送出後，程式發現有錯誤
// Demo for stripslashes
    if (get_magic_quotes_gpc()) {
        $Name = stripslashes($Name);
        $Price = stripslashes($Price);
		$Pnum = stripslashes($Pnum);
		$Deadline = stripslashes($Deadline);
		$Class_no = stripslashes($Class_no);
        
    }
}
// 取出群組資料
$sqlcmd = "SELECT * FROM user_table WHERE uid='$UserOwnerID'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs)<=0) die('No group could be found!');  
$GroupNames = array();
foreach ($rs as $item) {
    $ID = $item['uid'];
    $GroupNames[$ID] = $item['uname'];
}
$PageTitle = '修改產品資料';
require_once("../include/cssheader.php");
?>
<body>
<div style="text-align:center;margin-top:5px;font-size:20px;font-weight:bold;">
I3360 資料庫</div>
<div align="center">
<div align="text-align:center">
<form action="" method="post" name="inputform">
<input type="hidden" name="oid" value="<?php echo $oid ?>">
<b>修改訂單</b>
<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">

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
  <th width="40%">日期</th>
  <td><input type="date" name="date" value="<?php echo $date ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">數量</th>
  <td><input type="text" name="number" value="<?php echo $number ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">商品編號</th>
  <td><input type="text" name="product_id" value="<?php echo $product_id ?>" size="20"></td>
</tr>

<tr height="30">
  <th width="40%">快遞編號</th>
  <td><input type="text" name="shipment_id" value="<?php echo $shipment_id ?>" size="20"></td>
</tr>
</table>
<input type="submit" name="Confirm" value="存檔送出">&nbsp;
<input type="submit" name="Abort" value="放棄修改">
</form>
</div>
<?php 
require_once ('../include/footer.php');
?>
</body>
</html>