<?php  
/*
session_start();
 if ($_SESSION["group_user"] == ""){
	echo "<meta http-equiv='refresh' content='0;URL=logout.php?'>";
}
 */


 if(isset($_GET["employee_id"]))  
 {  
 
 	  include("dbase.php");

	  $id = trim($_GET["employee_id"]);  
	  
     							$query  = "select id,news_title,site,content_request,image as image1 ";
								
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
								
	  
	  /*
      $query = "SELECT l.objectid as id,SUBSTRING(l.uic, 1, 8) as uic,CONCAT(m.firstname,' ',m.lastname) AS name,l.HBsAg as HBsAg,l.CRE as CRE,l.eGFR as eGFR,l.ALT as ALT,l.Estradiol as Estradiol,l.Testosterone as Testosterone,l.CT as CT,l.NG as NG,viralload as viralload,viralload_value as viralload_value,l.service_date_refer as service_date_refer,l.service_date,l.PrEP as PrEP,pregment as pregment FROM lab l inner join member m on l.uic = m.uic WHERE l.objectid = '".$_GET["employee_id"]."'"; 
	  //echo $query; 
      $result = mysql_query($query);  
      $row = mysql_fetch_array($result);  
      echo json_encode($row);  
	  */
	  mysqli_close($connect);
 }  
 
 ?>