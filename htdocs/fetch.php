<?php  
session_start();
if (trim($_SESSION["user_sasuksure"]) == ""){
	echo "<meta http-equiv='refresh' content='0;URL=logout.php?'>";
 }


 if(isset($_GET["employee_id"])){  
 
 	  //include("dbase.php");
	  include("pdo-connect.php");
	  
	  $id = trim($_GET["employee_id"]);  

	  							/*
     							$query  = "select id,news_title,site,content_request,content,image as image1,rq,sender,office,answer,date_appointment,content_answer1, 	";
								$query  .= "content_answer2,fact,publish,content_answer3,content_answer4 ";
								
								$query .= " from news  ";
								$query .= " where id =  '".$id."' limit 1;";
								$result = mysqli_query($connect,$query);
								//$num_rows = 0;
								$count_n = 0;
								//echo $num_rows;
								
								//echo $num_rows;
								//echo $query2;
								$rows = mysqli_fetch_array($result);
								echo json_encode($rows);  
								*/


								$show_data = $conn->prepare("select id,news_title,site,content_request,content,image as image1,rq,sender,office,answer,date_appointment,content_answer1, 	
								content_answer2,fact,publish,content_answer3,content_answer4 
								from news 
								where id = :param1 limit 1;");
    							$show_data->bindParam(":param1", $id);

  
								try{
									//
									$show_data->execute();
									$num_rows = 0;
									$num_rows = $show_data->rowCount();
									}catch(PDOException $e){
												echo $e->getMessage();
									}

								$rows = $show_data->fetch(PDO::FETCH_ASSOC);
								echo json_encode($rows);  
								
	
	
 }  
 
 ?>