<?php
  session_start();
  if(!isset($_SESSION["studentloggedin"]) || $_SESSION["studentloggedin"] !== true){
      header("location: studentlogin.php");
      exit;
  }
  $rollnumber=$_SESSION["rollnumber"];
?>
<?php
			include "database.php";
		    $query = "select * from quizconfig where quiznumber=(select max(quiznumber) from quizconfig);";		    
		    $result = $conn->query($query);   
		    $row = $result->fetch_assoc();                   
		    $quiznumber=$row["quiznumber"];
		    $typea=$row["typea"];
		    $typeb=$row["typeb"];
		    $typec=$row["typec"];
		    $typed=$row["typed"];
		    $typee=$row["typee"];
		    $typef=$row["typef"];
		    $typeamarks=$row["typeamarks"];
		    $typebmarks=$row["typebmarks"];
		    $typecmarks=$row["typecmarks"];
		    $typedmarks=$row["typedmarks"];
		    $typeemarks=$row["typeemarks"];
		    $typefmarks=$row["typefmarks"];
			selectrand($conn, $typea,$typeamarks,'a',$rollnumber,$quiznumber); 
		    selectrand($conn, $typeb,$typebmarks,'b',$rollnumber,$quiznumber); 
		    selectrand($conn, $typec,$typecmarks,'c',$rollnumber,$quiznumber); 
		    selectrand($conn, $typed,$typedmarks,'d',$rollnumber,$quiznumber); 
		    selectrand($conn, $typee,$typeemarks,'e',$rollnumber,$quiznumber); 
		    selectrand($conn, $typef,$typefmarks,'f',$rollnumber,$quiznumber); 
			$_SESSION["quizset"] = true;
		    header("location: quizpage.php");		
		    exit;
      		function selectrand($conn1, $typen,$typemarks,$type,$rollno,$quizno){	      		
				static $serialnumber=1;			
				if ($conn1->connect_error) {
				    die("Connection failed: " . $conn1->connect_error);
				}          
	            if($type=='a'){
					$sql = "select id,question
					from mcqdb
					order by rand()
					limit ".$typen.";";
				}
				if($type=='b'){
					$sql = "select id,question
					from numericaldb
					order by rand()
					limit ".$typen.";";
				}
				if($type=='c'){
					$sql = "select id,question
					from dropdown
					order by rand()
					limit ".$typen.";";
				}
				if($type=='d'){
					$sql = "select id,question
					from fillintheblanks
					order by rand()
					limit ".$typen.";";
				}
				if($type=='e'){
					$sql = "select id,question
					from shortanswer
					order by rand()
					limit ".$typen.";";
				}
				if($type=='f'){
					$sql = "select id,question
					from essay
					order by rand()
					limit ".$typen.";";
				}
				$result= $conn1->query($sql);
	            if ($result->num_rows > 0) {
				    while($row = $result->fetch_assoc()) {   
		        		$insert = "insert into response (serialnumber,rollnumber,quiznumber,quesid,type,quesmarks) values (".$serialnumber.",".$rollno.",".$quizno.",".$row["id"].",'".$type."',".$typemarks.");";						
						$conn1->query($insert);
						$serialnumber=$serialnumber+1;
			   		}
	   			 	mysqli_free_result($result);
				} 
				else {
	    			// echo "0 results"; // Temporarily commented out
				}
	      		// $conn1->close(); // Removed as connection is managed outside
      		}				
		?>
