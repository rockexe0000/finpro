<?php
//I4B63
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_quiz";


function connect2db($dbhost, $dbuser, $dbpwd, $dbname) {
    $dsn = "mysql:host=$dbhost;dbname=$dbname";
    try {
        $db_conn = new PDO($dsn, $dbuser, $dbpwd);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
        die ("錯誤: 無法連接到資料庫");
    }
    $db_conn->query("SET NAMES UTF8");
    return $db_conn;
}

function updatedb($updatestr, $conn_id) {
    try {
        $result = $conn_id->query($updatestr); 
    } catch (PDOException $e) {
        echo $e->getMessage();
        die ("資料庫異動失敗，請重試，若問題仍在，請通知管理單位。");
    }
    return $result;
}

function querydb($querystr, $conn_id) {
    try {
        $result = $conn_id->query($querystr);
    } catch (PDOException $e) {
        die ("$querystr 資料庫查詢失敗，請重試，若問題仍在，請通知管理單位。");
    }
    $rs = array();
    if ($result) $rs = $result->fetch_all(); 
    return $rs;
}






$sele='';
if(isset($_POST['sele'])){ 
	$sele=$_POST['sele']; 
}

$sel='';
if(isset($_POST['sel'])){ 
	$sel=$_POST['sel']; 
}

$insert='';
if(isset($_POST['insert'])){ 
	$insert=$_POST['insert']; 
}

$Account='';
if(isset($_POST['Account'])){ 
	$Account=$_POST['Account']; 
}

$Password='';
if(isset($_POST['Password'])){ 
	$Password=$_POST['Password']; 
}

$updata='';
if(isset($_POST['updata'])){ 
	$updata=$_POST['updata']; 
}

$sign='';
if(isset($_POST['sign'])){ 
	$sign=$_POST['sign']; 
}



?>


<html>
<body>



<!--
<form action="index.php" method="post">
  show all data:<br>
  <input type="submit" value="click" name="all">
</form>
-->

<!--
<form action="index.php" method="post">
	what do you want to do?<br>
	<select name="sel">
	  <option value="<?php echo"$sel"; ?>"><?php echo"$sel"; ?></option>
	  <option value="">-------</option>
	  <option value="insert">insert</option>
	  <option value="modify">modify</option>
	  <option value="delete">delete</option>
	</select>
	<input type="submit" value="click" name="sele">
</form>
-->





<form action="index.php" method="post">
	新增帳號<br>
	<select name="sel">
	  <option value="<?php echo"$sel"; ?>"><?php echo"$sel"; ?></option>
	  <option value="">-------</option>
	  <option value="insert">insert</option>
	  <option value="modify">modify</option>
	  <option value="delete">delete</option>
	</select>
	<input type="submit" value="new" name="sele">
</form>






<?php 
//49906217
//if(isset($_POST["sel"]))echo $_POST["sel"]."<br>";
//if(isset($sele))echo $sele."<br>";
//if(isset($sel))echo $sel."<br>";
///echo "delete";
?>

<!--
<form action="index.php" method="post">
First name: <input type="text" name="fname"><br>
Last name: <input type="text" name="lname"><br>
<input type="submit">
</form>
-->

<form action="index.php" method="post">
Account: <input type="text" name="Account"><br>
Password: <input type="text" name="Password"><br>
<input type="submit" value="登入" name="sign">
</form>


<?php 

if(  isset($_POST["all"])   ){
	



	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT * FROM EMPLOYEE ";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		echo '<table border="1" style="width:100%">';
		echo"<tr>";
		 echo "<th>Fname</th>";
		 echo "<th>Minit</th> ";
		 echo "<th>Lname</th>";
		 echo "<th>Ssn</th>";
		 echo "<th>Bdate</th>";
		 echo "<th>Address</th>";
		 echo "<th>Sex</th>";
		 echo "<th>Salary</th>";
		 echo "<th>Super_ssn</th>";
		 echo "<th>Dno</th>";
		 echo"</tr>";
		 echo"<tr>";	
		while($row = $result->fetch_assoc()) {


		 echo "<td>" . $row["Fname"]. "</td>";
		 echo "<td>" . $row["Minit"]. "</td>";
		 echo "<td>" . $row["Lname"]. "</td>";
		 echo "<td>" . $row["Ssn"]. "</td>";
		 echo "<td>" . $row["Bdate"]. "</td>";
		 echo "<td>" . $row["Address"]. "</td>";
		 echo "<td>" . $row["Sex"]. "</td>";
		 echo "<td>" . $row["Salary"]. "</td>";
		 echo "<td>" . $row["Super_ssn"]. "</td>";
		 echo "<td>" . $row["Dno"]. "</td>";
		 echo"</tr>";

		   
		}
		echo '</table>';
	} else {
		echo "0 results";
	}
	$conn->close();




}














if(  isset($_POST["fname"]) && isset($_POST["lname"])  ){
	



	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "company_quiz";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT * FROM EMPLOYEE WHERE Fname='".$_POST["fname"]."' AND Lname='".$_POST["lname"]."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		echo '<table border="1" style="width:100%">';
		echo"<tr>";
		 echo "<th>Fname</th>";
		 echo "<th>Minit</th> ";
		 echo "<th>Lname</th>";
		 echo "<th>Ssn</th>";
		 echo "<th>Bdate</th>";
		 echo "<th>Address</th>";
		 echo "<th>Sex</th>";
		 echo "<th>Salary</th>";
		 echo "<th>Super_ssn</th>";
		 echo "<th>Dno</th>";
		 echo"</tr>";
		 echo"<tr>";	
		while($row = $result->fetch_assoc()) {


		 echo "<td>" . $row["Fname"]. "</td>";
		 echo "<td>" . $row["Minit"]. "</td>";
		 echo "<td>" . $row["Lname"]. "</td>";
		 echo "<td>" . $row["Ssn"]. "</td>";
		 echo "<td>" . $row["Bdate"]. "</td>";
		 echo "<td>" . $row["Address"]. "</td>";
		 echo "<td>" . $row["Sex"]. "</td>";
		 echo "<td>" . $row["Salary"]. "</td>";
		 echo "<td>" . $row["Super_ssn"]. "</td>";
		 echo "<td>" . $row["Dno"]. "</td>";
		 echo"</tr>";

		   
		}
		echo '</table>';
	} else {
		echo "0 results";
	}
	$conn->close();




}






if(  isset($_POST['Account']) && isset($_POST['Password'])  ){
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT * FROM EMPLOYEE WHERE Account='".$Account."' AND Password='".$Password."'";
	$result = $conn->query($sql);

	
		
		$row = $result->fetch_assoc();
		
		$First_name=$row["Fname"];
		$Minit=$row["Minit"];
		$Last_name=$row["Lname"];
		$Ssn=$row["Ssn"];
		$Birthday=$row["Bdate"];
		$Address=$row["Address"];
		$Sex=$row["Sex"];
		$Salary=$row["Salary"];
		$Superior_ssn=$row["Super_ssn"];
		$Department_number=$row["Dno"];
		$Account=$row["Account"];
		$Password=$row["Password"];
		
		echo"Hello ,".$First_name." ".$Last_name." !";
		
		
?>
		<form action="index.php" method="post">
			 <table border="0" style="width:25%">
			 <tr><th>Fname</th><td><input type="text" name="First_name" value="<?php echo "$First_name"; ?>" ></td></tr>
			 <tr><th>Minit</th><td><input type="text" name="Minit" value="<?php echo "$Minit"; ?>" ></td></tr>
			 <tr><th>Lname</th><td><input type="text" name="Last_name" value="<?php echo "$Last_name"; ?>"></td></tr>
			 <tr><th>Ssn</th><td><input type="text" name="Ssn" value="<?php echo "$Ssn"; ?>"></td></tr>
			 <tr><th>Bdate</th><td><input type="text" name="Birthday" value="<?php echo "$Birthday"; ?>"></td></tr>
			 <tr><th>Address</th><td><input type="text" name="Address" value="<?php echo "$Address"; ?>"></td></tr>
			 <tr><th>Sex</th><td><input type="radio" name="Sex" value="M" <?php if($Sex=="M")echo "checked"; ?> > Male <input type="radio" name="Sex" value="F" <?php if($Sex=="F")echo "checked"; ?> > Female</td></tr>
			 <tr><th>Salary</th><td><input type="text" name="Salary" value="<?php echo "$Salary"; ?>"></td></tr>
			 <tr><th>Super_ssn</th><td><input type="text" name="Superior_ssn" value="<?php echo "$Superior_ssn"; ?>"></td></tr>
			 <tr><th>Dno</th><td><input type="text" name="Department_number" value="<?php echo "$Department_number"; ?>"></td></tr>
			 <tr><th>Account</th><td><input type="text" name="Account" value="<?php echo "$Account"; ?>"></td></tr>
			 <tr><th>Password</th><td><input type="text" name="Password" value="<?php echo "$Password"; ?>"></td></tr>
			 
			 <tr><td><input type="submit" value="updata" name="updata"></td></tr>
			 </table>
		 </form>
<?php
		
		//echo  $First_name.'<br>'.$_POST["First_name"];
	
		$conn->close();
	
	



		

	
	
	
	

		



	



	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	


}







if (isset($_POST["updata"])) {
			echo  '<font color="Red">'. 'YES</font>';
			
		$conn = new mysqli($servername, $username, $password, $dbname);

		
		//$A=checkInsert($_POST["Ssn"], $conn);
				
				//echo  $_POST["Minit"].$_POST["First_name"];
			
			
				//$sqlcmd = "SELECT * FROM employee WHERE Ssn='$Ssn'";
				//$rs = querydb($sqlcmd, $conn);
			
				$First_name=$_POST["First_name"];
				$Minit=$_POST["Minit"];
				$Last_name=$_POST["Last_name"];
				$Ssn=$_POST["Ssn"];
				$Birthday=$_POST["Birthday"];
				$Address=$_POST["Address"];
				$Sex=$_POST["Sex"];
				$Salary=$_POST["Salary"];
				$Superior_ssn=$_POST["Superior_ssn"];
				$Department_number=$_POST["Department_number"];
				$Account=$_POST["Account"];
				$Password=$_POST["Password"];
				
				 //$sqlcmd="UPDATE employee SET Minit='".$Minit."' WHERE Account='".$Account."' AND Password='".$Password."'";
				 $sqlcmd="UPDATE employee SET Fname='".$First_name."',Minit='".$Minit."',Lname='".$Last_name."',Bdate='".$Birthday."',Address='".$Address."',Sex='".$Sex."',Salary='".$Salary."',Super_ssn='".$Superior_ssn."',Dno='".$Department_number."',Password='".$Password."' WHERE Ssn='".$Ssn."'";
				 $result = updatedb($sqlcmd, $conn);

					
				

				
				
				
				
				
				
				
				
		        //echo  '<font color="Red">'. $Minit.'</font>';
				echo  '<font color="Red">'. '修改成功</font>';
		    		
				//if (empty($ErrMsg)) $ErrMsg = '<font color="Red">登入錯誤</font>';

			
				$conn->close();
		}











if(  isset($sele)  ){
	

	if(  isset($sel) && $sel=="insert"  ){

	
	?>
		<form action="index.php" method="post">
			
			-- Insert data --<br>
			Please key in personal information:<br><br>
			<table border="0" style="width:25%">
			  <tr>
				<td>First name:</td> <td><input type="text" name="First_name"></td> 
			  </tr>
			  <tr>
				<td>Minit:</td> <td><input type="text" name="Minit"></td> 
			  </tr>
			  <tr>
				<td>Last name:</td><td><input type="text" name="Last_name"></td> 
			  </tr>
			  <tr>
				<td>Ssn:</td><td><input type="text" name="Ssn"></td> 
			  </tr>
			  <tr>
				<td>Birthday:</td><td><input type="text" name="Birthday"></td> 
			  </tr>
			  <tr>
				<td>Address:</td><td><input type="text" name="Address"></td> 
			  </tr>
			  <tr>
				<td>Sex:</td><td><input type="radio" name="Sex" value="male" checked> Male <input type="radio" name="Sex" value="female"> Female</td> 
			  </tr>
			  <tr>
				<td>Salary:</td><td><input type="text" name="Salary"></td> 
			  </tr>
			  <tr>
				<td>Superior ssn:</td><td><input type="text" name="Superior_ssn"></td> 
			  </tr>
			  <tr>
				<td>Department number:</td><td><input type="text" name="Department_number"></td> 
			  </tr>
			  <tr>
				<td>Account:</td><td><input type="text" name="Account"></td> 
			  </tr>
			  <tr>
				<td>Password:</td><td><input type="text" name="Password"></td> 
			  </tr>
			  <tr>
				<td><input type="submit" value="insert" name="insert"></td> 
			  </tr>
			</table>
		</form>
		
		
	
	
	
	<?php

	}




	
	
	
	








		function checkInsert($Ssn, $conn) {
		    $sqlcmd = "SELECT * FROM employee WHERE Ssn='$Ssn'";
		    $rs = querydb($sqlcmd, $conn);
		    
		    $retcode = 1;
		    if (count($rs) > 0) {
			$retcode = 0;
		    }
		    return $retcode;
		}




		



		if (isset($_POST["insert"])) {
			
		
		$conn = new mysqli($servername, $username, $password, $dbname);

		
		$A=checkInsert($_POST["Ssn"], $conn);


				if (strlen($_POST["Ssn"]) > 0 && strlen($_POST["Ssn"])<=16 && $A==1 ) {
			
				$First_name=$_POST["First_name"];
				$Minit=$_POST["Minit"];
				$Last_name=$_POST["Last_name"];
				$Ssn=$_POST["Ssn"];
				$Birthday=$_POST["Birthday"];
				$Address=$_POST["Address"];
				$Sex=$_POST["Sex"];
				$Salary=$_POST["Salary"];
				$Superior_ssn=$_POST["Superior_ssn"];
				$Department_number=$_POST["Department_number"];
				$Account=$_POST["Account"];
				$Password=$_POST["Password"];
				
				
				
				 $sqlcmd='INSERT INTO employee (Fname,Minit,Lname,Ssn,Bdate,Address,Sex,Salary,Super_ssn,Dno) VALUES ('. " '$First_name','$Minit','$Last_name','$Ssn','$Birthday','$Address','$Sex','$Salary','$Superior_ssn','$Department_number') ";
				 $result = updatedb($sqlcmd, $conn);

					
				

				
				
				
				
				
				
				
				
		        
				echo  '<font color="Red">'. '新增成功</font>';
		    		} else {
					echo  '<font color="Red">'. 'Ssn輸入錯誤，輸入太長或太短或重複</font>';
				}
				//if (empty($ErrMsg)) $ErrMsg = '<font color="Red">登入錯誤</font>';

			

		}







//Fname,Minit,Lname,Ssn,Bdate,Address,Sex,Salary,Super_ssn,Dno















}

























?>






















</body>
</html>