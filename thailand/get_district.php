<?php
include('connect.php');
$sql = "SELECT * FROM districts WHERE left(id,4)={$_GET['amphure_id']}";
$query = mysqli_query($connect, $sql);

$json = array();
while($result = mysqli_fetch_assoc($query)) {    
    array_push($json, $result);
}
echo json_encode($json);