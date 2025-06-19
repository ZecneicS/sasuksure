<?php

    $_SESSION["visitor_date"] = date('Y-m-d');
	$_SESSION["visitor_ip"] = $_SERVER['REMOTE_ADDR'];
	$_SESSION["visitor"] = session_id();
	
if (!empty($track_type) and !isset($_POST["save"])){
	include("dbase.php");
	$query = "Insert into visitor(sid,ip,track_type,date_visit,date_created) ";
	$query .= " values('" . $_SESSION["visitor"] . "','" . $track_type . "','" . $_SESSION["visitor_ip"] . "','" . $_SESSION["visitor_date"] . "',now()); ";
	$result = mysqli_query($connect, $query) or die("Error in query: $query " . mysqli_error($connect));
		mysqli_close($connect);
}

if (!empty($id)){
    include("dbase.php");
	$query = "Insert into news_visit(sid,news_id,date_created) ";
	$query .= " values('" . $_SESSION["visitor"] . "','" . $id. "',now()); ";
	$result = mysqli_query($connect, $query) or die("Error in query: $query " . mysqli_error($connect));
	mysqli_close($connect);
}

include("dbase.php");
$users_total = 0;
$users_today = 0;
$users_yesterday = 0;
$pages_view_today = 0;

// users total
$query1 = "select count(DISTINCT(sid)) as total from visitor group by sid,date(date_visit) ;";
$result1 = mysqli_query($connect,$query1) or die ("Error in query: $query1 " . mysqli_error($connect));
while ($rows = mysqli_fetch_array($result1)) {
	$users_total += $rows["total"];
}

// users today
$query1 = "select count(DISTINCT(sid)) as total from visitor where date(date_created) = CURDATE() group by sid ;";
$result1 = mysqli_query($connect,$query1) or die ("Error in query: $query1 " . mysqli_error($connect));
while ($rows = mysqli_fetch_array($result1)) {
	$users_today += $rows["total"];
}

// users total
$query1 = "select count(DISTINCT(sid)) as total from visitor where date(date_created) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) group by sid ;";
$result1 = mysqli_query($connect,$query1) or die ("Error in query: $query1 " . mysqli_error($connect));
while ($rows = mysqli_fetch_array($result1)) {
	$users_yesterday += $rows["total"];
}

// users total
$query1 = "select count(sid) as total from visitor where date(date_created) = CURDATE();";
$result1 = mysqli_query($connect,$query1) or die ("Error in query: $query1 " . mysqli_error($connect));
while ($rows = mysqli_fetch_array($result1)) {
	$pages_view_today = $rows["total"];
}
mysqli_close($connect);
