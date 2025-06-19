<?php
include('connect.php');
$sql = "SELECT department as code,department as name_th FROM users WHERE (left(province,2)={$_GET['province_id']}) and (department is not null) group by department;";
$query = mysqli_query($connect, $sql);

$json = array();
while($result = mysqli_fetch_assoc($query)) {    
    array_push($json, $result);
}
echo json_encode($json);