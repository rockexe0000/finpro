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






$PageTitle = '49906217';


$sqlcmd = "SELECT count(*) AS reccount FROM product,user_table,product_class WHERE product.pnum>0 AND product.owner_id=user_table.uid AND product.class_no=product_class.class_id ";
$rs = querydb($sqlcmd, $db_conn);
$RecCount = $rs[0]['reccount'];
//echo $RecCount;
$TotalPage = (int) ceil($RecCount/$ItemPerPage);
//echo $ItemPerPage;
//echo $TotalPage;
if (!isset($Page)) {
    if (isset($_SESSION['CurPage'])) $Page = $_SESSION['CurPage'];
    else $Page = 1;
}
if ($Page > $TotalPage) $Page = $TotalPage;
$_SESSION['CurPage'] = $Page;
$StartRec = ($Page-1) * $ItemPerPage;
$sqlcmd = "SELECT * FROM product,user_table,product_class WHERE product.pnum>0 AND product.owner_id=user_table.uid AND product.class_no=product_class.class_id "
    . "LIMIT $StartRec,$ItemPerPage";
$Contacts = querydb($sqlcmd, $db_conn);
$PrevPage = $NextPage = '';
if ($TotalPage > 1) {
    if ($Page>1) $PrevPage = $Page - 1;
    if ($Page<$TotalPage) $NextPage = $Page + 1;   
}
$PrevLink = $NextLink = '';
if (!empty($PrevPage)) 
    $PrevLink = '<a href="contactlist3.php?Page=' . $PrevPage . '">上一頁</a>';
if (!empty($NextPage)) 
    $NextLink = '<a href="contactlist3.php?Page=' . $NextPage . '">下一頁</a>';

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
/*
$sqlcmd = "SELECT * FROM product,user_table,product_class WHERE product.pnum>0 AND product.owner_id=user_table.uid AND product.class_no=product_class.class_id";
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
    <!--<a href="contactadd2.php">新增訂單</a>&nbsp;-->
    <a href="logout.php">登出</a>
  </td>
</tr>
</table>



	<div style="text-align:center;">

		<table border="0" width="90%" align="center">



			<td width="20%" align="left">
			
			<a href="contactlist3.php" style="color:#FF0000">商城</a><br>
			<a href="contactlist.php">我的賣場</a><br>
			<a href="contactlist2.php">購物車</a><br>
			
			</td>



			<td align="right" width="70%">

			<table class="mistab" width="100%" align="center">
			<tr>
			   <th width="15%"></th>
			   <th width="15%">產品名稱</th>
			   <th width="15%">價格</th>
			   <th width="15%">數量</th>
			   <th width="15%">商店</th>
			   <th width="15%">販賣期限</th>
			   <th width="15%">產品類別</th>
			  
			</tr>
			<?php
			foreach ($Contacts AS $item) {
			  $pid = $item['pid']; 
			  $pname = $item['pname']; 
			  $price = $item['price']; 
			  $pnum = $item['pnum']; 
			  $owner_id = $item['owner_id'];
			  $deadline = $item['deadline'];
			  $class_name = $item['class_name'];
			  
				 
			?>
			<tr align="center">
			  <td><a href="contactadd2.php?pid=<?php echo $pid; ?>">
			  購買</a>&nbsp;</td>
			  <td><?php echo $pname ?></td>   
			  <td><?php echo $price ?></td>
			  <td><?php echo $pnum ?></td>
			  <td><?php echo $owner_id ?></td>
			  <td><?php echo $deadline ?></td>
			  <td><?php echo $class_name ?></td>
			</tr>
			<?php }  ?>
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