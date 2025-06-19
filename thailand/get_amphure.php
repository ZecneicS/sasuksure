<?php
include('connect.php');
$sql = "SELECT * FROM amphures WHERE left(code,2)={$_GET['province_id']}";
$query = mysqli_query($connect, $sql);

$json = array();
while($result = mysqli_fetch_assoc($query)) {    
    array_push($json, $result);
}
echo json_encode($json);