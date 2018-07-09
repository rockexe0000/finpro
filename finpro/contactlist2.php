<?php
//session_start();
require_once("../include/auth.php");
require_once('../include/gpsvars.php');
require_once('../include/configure.php');
require_once('../include/db_func.php');
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);


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

foreach ($rs as $item) {
    $money_bag = $item['money_bag'];
    //echo $money_bag;
}


$client_id = $rs[0]['uid'];

$sqlcmd = "SELECT * FROM user_table WHERE uid='$client_id' ";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs)<=0) die('No owner could be found!');  
$OwnerNames = array();
$OwnerIDs = '';
foreach ($rs as $item) {
    $ID = $item['uid'];
    $OwnerNames[$ID] = $item['uname'];
    $OwnerIDs .= "','" . $ID;
	//echo $OwnerIDs;
}
$OwnerIDs = "(" .  substr($OwnerIDs,2) . "')";
if (isset($action) && $action=='recover' && isset($oid)) {
    // Recover this item
    $sqlcmd = "SELECT * FROM order_table WHERE oid='$oid' AND number='0'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        $sqlcmd = "UPDATE order_table SET number=1 WHERE oid='$oid'";
        $result = updatedb($sqlcmd, $db_conn);
    }
}
if (isset($action) && $action=='delete' && isset($oid)) {
    // Invalid this item
    $sqlcmd = "SELECT * FROM order_table WHERE oid='$oid' AND number>1";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        $sqlcmd = "UPDATE order_table SET number=0 WHERE oid='$oid'";
        $result = updatedb($sqlcmd, $db_conn);
    }
}
if (!isset($ItemPerPage)) $ItemPerPage = 2;






$PageTitle = 'I3360 資料庫';


$sqlcmd = "SELECT count(*) AS reccount FROM order_table WHERE order_table.number>0 AND client_id='$client_id'";
$rs = querydb($sqlcmd, $db_conn);
$RecCount = $rs[0]['reccount'];
//echo $RecCount;
$TotalPage = (int) ceil($RecCount/$ItemPerPage);
//echo $TotalPage;
if (!isset($Page)) {
    if (isset($_SESSION['CurPage'])) $Page = $_SESSION['CurPage'];
    else $Page = 1;
}
if ($Page > $TotalPage) $Page = $TotalPage;
$_SESSION['CurPage'] = $Page;
$StartRec = ($Page-1) * $ItemPerPage;
$sqlcmd = "SELECT * FROM order_table,client WHERE order_table.number>0 AND order_table.client_id=client.cid AND order_table.client_id IN $OwnerIDs"
    . "LIMIT $StartRec,$ItemPerPage";
$Contacts = querydb($sqlcmd, $db_conn);
$PrevPage = $NextPage = '';
if ($TotalPage > 1) {
    if ($Page>1) $PrevPage = $Page - 1;
    if ($Page<$TotalPage) $NextPage = $Page + 1;   
}
$PrevLink = $NextLink = '';
if (!empty($PrevPage)) 
    $PrevLink = '<a href="contactlist2.php?Page=' . $PrevPage . '">上一頁</a>';
if (!empty($NextPage)) 
    $NextLink = '<a href="contactlist2.php?Page=' . $NextPage . '">下一頁</a>';
$sqlcmd = "SELECT * FROM user_table ";
$rs = querydb($sqlcmd, $db_conn);
$arrGroups = array();
if (count($rs)>0) {
    foreach ($rs as $item) {
        $uid = $item['uid'];
        $arrGroups["$uid"] = $item['uname'];
    }
}






/*
$sqlcmd = "SELECT * FROM order_table,client WHERE order_table.number>0 AND order_table.client_id=client.cid AND order_table.client_id IN $OwnerIDs";
$Contacts = querydb($sqlcmd, $db_conn);
*/






require_once ('../include/cssheader.php');
?>
<body>
<script Language="javascript">
<!--
function confirmation(DspMsg, PassArg) {
var name = confirm(DspMsg)
    if (name == true) {
      location=PassArg;
    }
}
-->
</script>
<div style="text-align:center;margin:0;font-size:20px;font-weight:bold;">
你好, <?php echo $LoginID; ?></div>


<table border="0" width="90%" align="center" cellspacing="0" cellpadding="2">
<tr>
  <td width="50%" align="left">
<?php if ($TotalPage > 1) { ?>
<form name="SelPage" method="POST" action="">
<?php if (!empty($PrevLink)) echo $PrevLink . '&nbsp;'; ?>
  第<select name="Page" onchange="submit();">
<?php 
for ($p=1; $p<=$TotalPage; $p++) { 
    echo '  <option value="' . $p . '"';
    if ($p == $Page) echo ' selected';
    echo ">$p</option>\n";
}
?>
  </select>頁 共<?php echo $TotalPage ?>頁
<?php if (!empty($NextLink)) echo '&nbsp;' . $NextLink; ?>
</form>
<?php } ?>
  </td>
  <td align="right" width="30%">
	<?php echo $money_bag;?>元&nbsp;&nbsp;
    <!--<a href="contactadd2.php">新增</a>&nbsp;-->
    <a href="logout.php">登出</a>
  </td>
</tr>
</table>



	<div style="text-align:center;">

		<table border="0" width="90%" align="center">



			<td width="20%" align="left">
			
			<a href="contactlist3.php">商城</a><br>
			<a href="contactlist.php">我的賣場</a><br>
			<a style="color:#FF0000" href="contactlist2.php">購物車</a><br>
			
			</td>



			<td align="right" width="70%">

			<table class="mistab" width="100%" align="center">
			<tr>
			  <th width="20%">處理</th>
			  <th width="20%">訂單編號</th>
			  <th width="15%">日期</th>
			  <th width="15%">數量</th>
			  <th width="15%">商品</th>
			  <th width="15%">運送方式</th>
			  
			</tr>
			<?php
			foreach ($Contacts AS $item) {
			  $oid = $item['oid'];
			  $date = $item['date']; 
			  $number = $item['number']; 
			  $client_id = $item['client_id'];
			  $product_id = $item['product_id'];
			  $shipment_id = $item['shipment_id'];
			  
			  $UName = 'N/A';
			  if (isset($arrGroups["$client_id"])) $UName = $arrGroups["$client_id"];
			  $DspMsg = "'確定刪除項目?'";
			  $PassArg = "'contactlist2.php?action=delete&oid=$oid'";
			  
			  
			?>

			<tr align="center">
			  <td>
			<?php
			  if ($number=='0') {
			?>
			  <a href="contactlist2.php?action=recover&oid=<?php echo $oid; ?>">
				回復
				</a></td>
			  <td><STRIKE><?php echo $pname ?></STRIKE></td>
			<?php } else { ?>
			  <a href="javascript:confirmation(<?php echo $DspMsg ?>, <?php echo $PassArg ?>)">
			  刪除</a>&nbsp;
			  <a href="contactmod2.php?oid=<?php echo $oid; ?>">
			  修改</a>&nbsp;
			  <!--
			  <a href="upload.php?oid=<?php echo $oid; ?>">
			  照片</a>
			  -->
			  </td>
			  <td><?php echo $oid ?></td>   
			<?php } ?>
			   
			  
			  <td><?php echo $date ?></td>
			  <td><?php echo $number ?></td>
			  <td><?php echo $product_id ?></td>
			  <td><?php echo $shipment_id ?></td>
			</tr>
			<?php } ?>
			</table>
			</td>
		</table>

	</div>
</body>
<script Language="javascript">
<!--
function confirmation(DspMsg, PassArg) {
var name = confirm(DspMsg)
    if (name == true) {
      location=PassArg;
    }
}
-->
</script>
</html>