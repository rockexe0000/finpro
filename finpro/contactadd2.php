<?php
// 使用者點選放棄新增按鈕
if (isset($_POST['Abort']) && !empty($_POST['Abort'])) {
    header("Location: contactlist3.php");
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

if (isset($_GET['pid']))$product_id=$_GET['pid'];
if (!isset($OwnerID))  $OwnerID = $rs[0]['uid'];
if (!isset($Pname)) $Pname = '';
if (!isset($date)) $date = '';
if (!isset($number)) $number = '';
if (!isset($product_id)) $product_id = '';
if (!isset($shipment_id)) $shipment_id = '';
if (!isset($Phone)) $Phone = '';
if (!isset($Address)) $Address = '';


$sqlcmd = "SELECT * FROM product,user_table WHERE pid='$product_id' AND owner_id=uid";
$rs = querydb($sqlcmd, $db_conn);
$Uname = $rs[0]['uname'];

// 取出群組資料
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
if (isset($Confirm)) {   // 確認按鈕
    
	if (!isset($date) || empty($date)) $ErrMsg = '日期不可為空白\n';
	if (!isset($number) || empty($number)) $ErrMsg = '數量不可為空白\n';
	if (!isset($product_id) || empty($product_id)) $ErrMsg = '商品不可為空白\n';
	//if (!isset($shipment_id) || empty($shipment_id)) $ErrMsg = '快遞不可為空白\n';
    if (empty($OwnerID) || $OwnerID<>addslashes($OwnerID)) $ErrMsg = '群組資料錯誤\n';
    
    if (empty($ErrMsg)) {
        $OwnerID = $UserOwnerID;
        $sqlcmd='INSERT INTO order_table (date,number,client_id,product_id,shipment_id) VALUES ('
            . "'$date','$number','$OwnerID','$product_id','$shipment_id')";
        $result = updatedb($sqlcmd, $db_conn);

        //$sqlcmd = "SELECT count(*) AS reccount FROM product WHERE OwnerID IN $OwnerIDs ";
        //$rs = querydb($sqlcmd, $db_conn);
        //$RecCount = $rs[0]['reccount'];
        //$TotalPage = (int) ceil($RecCount/$ItemPerPage);
        //$_SESSION['CurPage'] = $TotalPage; 

        header("Location: contactlist2.php");
        exit();
    }
}
$PageTitle = '49906217';
require_once("../include/cssheader.php");
?>
<body>
<div style="text-align:center;margin-top:5px;font-size:20px;font-weight:bold;">
I3360 資料庫</div>
<div align="center">
<form action="" method="post" name="inputform">
<b>新增訂單</b>
<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">


<tr height="30">
  <th>商店</th>
  <td><?php echo $Uname; ?></td>
  
	
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
  <td><?php echo $product_id; ?></td>
</tr>

<tr height="30">
  <th width="40%">快遞編號</th>
  <td><input type="text" name="shipment_id" value="<?php echo $shipment_id ?>" size="20"></td>
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