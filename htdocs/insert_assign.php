<?php 
ini_set('memory_limit','-1');
session_start();
if (trim($_SESSION["user_sasuksure"]) == ""){
	echo "<meta http-equiv='refresh' content='0;URL=logout.php?'>";
}
 if(!empty($_POST))  
 {  
 	  include("dbase.php");
	   $output = '';  
       $message = '';  
	 
	   $id = trim($_POST["assign_id"]);  
	   
	   $assign_by_id = trim($_SESSION["user_sasuksure"]);
	   $assign_by_fullname = trim($_SESSION["user_fullname"]);
	   
	   $assign = trim($_POST["district_id"]);
	   $find_stop = strpos($assign,":");
	   
	   $assign_to_id = substr($assign,0,$find_stop);
	   $assign_to_fullname = substr($assign,$find_stop+1,100);
			   
		   $query = "UPDATE news SET ";
		   $query .= " assign_by_id = '".$assign_by_id."', ";  
		   $query .= " assign_by_fullname = '".$assign_by_fullname."', ";  
		   $query .= " assign_to_id = '".$assign_to_id."', "; 
		   $query .= " assign_to_fullname = '".$assign_to_fullname."', "; 
		   $query .= " assign_timestamp = now() ";  
		   $query .= "WHERE id = '".$id."' limit 1;"; 
		   
           $message = 'Data Updated';  
		   $result = mysqli_query($connect,$query) or die ("Error in query: $query " . mysqli_error($connect));
		   
		   //echo $query;
		   $query = "Insert into news_log(sid,assign_by_id,assign_by_fullname,assign_to_id,assign_to_fullname,assign_timestamp,time_stamp) ";
		   $query .= " values('$id','".$assign_by_id."','".$assign_by_fullname."','".$assign_to_id."','".$assign_to_fullname."',now(),now()); ";
		   
           //$message = 'Data Updated';  
		   $result = mysqli_query($connect,$query) or die ("Error in query: $query " . mysqli_error($connect));
		   
		   $query = "select email from users  where id = '".$assign_to_id."' limit 1;";
			//echo $query;
			$result = mysqli_query($connect,$query);
			$num_rows = mysqli_num_rows($result);
			while($rows = mysqli_fetch_array($result))
			{
				$email = $rows["email"];
			}
			
			$query = "select news_title from news  where id = '".$id."' limit 1;";
			//echo $query;
			$result = mysqli_query($connect,$query);
			$num_rows = mysqli_num_rows($result);
			while($rows = mysqli_fetch_array($result))
			{
				$news_title = $rows["news_title"];	
			}
		  $response = array( 
						'status' => 1, 
						'message' => 'ทำรายการสำเร็จ',
						'id' => $id,
						'news_title' => $news_title,
						'email' => $email); 
						 
		  echo json_encode($response);
		  mysqli_close($connect);
 }  
 ?>