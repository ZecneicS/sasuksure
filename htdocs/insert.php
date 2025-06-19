<?php
ini_set('memory_limit', '-1');
session_start();
if (trim($_SESSION["user_sasuksure"]) == "") {
	echo "<meta http-equiv='refresh' content='0;URL=logout.php?'>";
}

if (!empty($_POST)) {

	include("dbase.php");
	include("pdo-connect.php");

	if (!empty($_FILES["file_to_upload"]["name"])) {
		// start upload
		$target_dir = "uploads/";
		//$target_file = $target_dir . basename($_FILES["file_to_upload"]["name"]);
		$upload_file_name = ($_FILES["file_to_upload"]["name"]);
		$target_file = $target_dir . basename($_FILES["file_to_upload"]["name"]);
		$file_extension = strrchr($_FILES["file_to_upload"]["name"], ".");
		$file_size = $_FILES["file_to_upload"]["size"];

		//$upload_ok = 1;

		$file_type = $_FILES['file_to_upload']['type'];

		//echo $file_type."<br>";
		//echo $file_extension."<br>";
		//echo $target_file."<br>";
		$label = time();

		$new_file_name = "sasuksure_" . $label . $file_extension;
		$image_uploaded = "0";

		if (move_uploaded_file($_FILES["file_to_upload"]["tmp_name"], $target_dir . $new_file_name)) {
			$image_uploaded = "1";
		} else {
			//echo "A error has occured uploading.";
			$image_uploaded = "0";
		}
	}

	$response = array(
		'status' => 1,
		'message' => 'ทำรายการสำเร็จ'
	);
	echo json_encode($response);

	$image = $new_file_name;

	$output = '';
	$message = '';

	$id = trim($_POST["employee_id"]);

	/*
	$news_title = mysqli_real_escape_string($connect, $_POST["news_title"]);
	$content_request = mysqli_real_escape_string($connect, $_POST["content_request"]);
	$content = mysqli_real_escape_string($connect, $_POST["content"]);
	$site = mysqli_real_escape_string($connect, $_POST["site"]);


	$answer = mysqli_real_escape_string($connect, $_POST["answer"]);
	$date_appointment = mysqli_real_escape_string($connect, $_POST["date_appointment"]);
	$content_answer1 = mysqli_real_escape_string($connect, $_POST["content_answer1"]);

	$content_answer2 = mysqli_real_escape_string($connect, $_POST["content_answer2"]);

	$fact = mysqli_real_escape_string($connect, $_POST["fact"]);
	$publish = mysqli_real_escape_string($connect, $_POST["publish"]);
	$content_answer3 = mysqli_real_escape_string($connect, $_POST["content_answer3"]);

	$content_answer4 = mysqli_real_escape_string($connect, $_POST["content_answer4"]);
	*/

	$news_title = $_POST["news_title"];
	$content_request = trim($_POST["content_request"]);
	$content = trim($_POST["content"]);
	$site = trim($_POST["site"]);


	$answer = trim($_POST["answer"]);
	$date_appointment = trim($_POST["date_appointment"]);
	$content_answer1 = trim($_POST["content_answer1"]);

	$content_answer2 = trim($_POST["content_answer2"]);

	$fact = trim($_POST["fact"]);
	$publish = trim($_POST["publish"]);
	$content_answer3 = trim($_POST["content_answer3"]);

	$content_answer4 = trim($_POST["content_answer4"]);

	/*
	$query  = "select id,time_stamp_accept from news where id = '" . $id . "' order by id asc limit 1;	";

	$result = mysqli_query($connect, $query);
	*/
	//$num_rows = mysqli_num_rows($result);


	$show_data = $conn->prepare("select id,time_stamp_accept from news where id = :param1 order by id asc limit 1;	");
	$show_data->bindParam(":param1", $id);


	try {
		//
		$show_data->execute();
		$num_rows = 0;
		$num_rows = $show_data->rowCount();
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	while ($rows = $show_data->fetch(PDO::FETCH_ASSOC)) {
		$time_stamp_accept = trim($rows["time_stamp_accept"]);
	}

	$query = "UPDATE news SET answer = '" . $answer . "', ";
	$query .= " news_title = '" . $news_title . "', ";
	$query .= " content_request = '" . $content_request . "', ";
	$query .= " content = '" . $content . "', ";
	$query .= " site = '" . $site . "', ";

	if ($time_stamp_accept ==  "") {
		$query .= " time_stamp_accept = now(), ";
	}

	if (($answer ==  "3") or ($answer ==  "4")) {
		$query .= " time_stamp_reply = now(), ";
	}

	if ($image_uploaded == "1") {
		$query .= " image = '" . $image . "',";
	}
	$query .= " date_appointment = '" . $date_appointment . "', ";
	$query .= " content_answer1 = '" . $content_answer1 . "', ";

	$query .= " content_answer2 = '" . $content_answer2 . "', ";

	$query .= " fact = '" . $fact . "', ";
	$query .= " publish = '" . $publish . "', ";
	$query .= " content_answer3 = '" . $content_answer3 . "', ";

	$query .= " content_answer4 = '" . $content_answer4 . "', ";
	$query .= " user_id = '" . $_SESSION["user_sasuksure"] . "', ";
	$query .= " user_fullname = '" . $_SESSION["user_fullname"] . "' ";


	$query .= "WHERE id = '" . $id . "' limit 1;";

	$message = 'Data Updated';

	$result = mysqli_query($connect, $query) or die("Error in query: $query " . mysqli_error($connect));

	$query = "Insert into news_log(sid,news_title,content_request,content,site,answer,date_appointment,content_answer1,content_answer2,fact,publish,content_answer3,content_answer4,image,time_stamp,user_id,user_fullname) ";
	$query .= " values('$id','$news_title','$content_request','$content','$site','$answer','$date_appointment','$content_answer1','$content_answer2','$fact','$publish','$content_answer3','$content_answer4','$image',now(),'" . $_SESSION["user_sasuksure"] . "','" . $_SESSION["user_fullname"] . "'); ";

	//$message = 'Data Updated';  
	$result = mysqli_query($connect, $query) or die("Error in query: $query " . mysqli_error($connect));
	mysqli_close($connect);
}
