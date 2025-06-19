<?php
ini_set('memory_limit', '-1');
session_start();
if (trim($_SESSION["user_role"]) != "administrator") {
	echo "<meta http-equiv='refresh' content='0;URL=logout.php?'>";
} else {

	if (!empty($_POST)) {

		//include("dbase.php");

		include("pdo-connect.php");













		$output = '';
		$message = '';

		$id = trim($_POST["employee_id_del"]);


		$stmt = $conn->prepare("delete from news where id = :param1 limit 1;");

		$stmt->bindParam(":param1",$id);


		try {
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}

		/*
		$query = "delete from news where id = '$id' limit 1;";

		$result = mysqli_query($connect, $query) or die("Error in query: $query " . mysqli_error($connect));
		*/

		$response = array(
			'status' => 1,
			'message' => 'ทำรายการสำเร็จ'
		);
		echo json_encode($response);



		//mysqli_close($connect);
	}
}
