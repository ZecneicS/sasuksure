<?php


$tambon = $_GET["tambon"];
$amphur = substr($tambon,0,4);
$province= substr($tambon,0,2);


include('connect.php');
$sql = "SELECT * FROM provinces";
$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Multiple Dropdown - itoffside.com</title>

    <link href="assets/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <div class="card">
        <div class="card-body">
           
            <form>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="province">จังหวัด</label>
                        <select name="province_id" id="province" class="form-control">
                            <option value="">เลือกจังหวัด</option>
                            <?php while($result = mysqli_fetch_assoc($query)): ?>
                            	<? if ($result['code'] == $province){ ?>
                                <option value="<?=$result['code']?>" selected><?=$result['name_th']?></option>
                                <? }else{ ?>
                                <option value="<?=$result['code']?>"><?=$result['name_th']?></option>
                                <? }?>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="amphure">อำเภอ</label>
                        <select name="amphure_id" id="amphure" class="form-control">
                            <option value="">เลือกอำเภอ</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="district">ตำบล</label>
                        <select name="district_id" id="district" class="form-control">
                            <option value="">เลือกตำบล</option>
                        </select>
                    </div>
                </div>                
            </form>
        </div>
    </div>
</div>

<script src="assets/jquery.min.js"></script>
<script>
$(function(){
    var provinceObject = $('#province');
    var amphureObject = $('#amphure');
    var districtObject = $('#district');

    // on change province
    provinceObject.on('change', function(){
        var provinceId = $(this).val();

        amphureObject.html('<option value="">เลือกอำเภอ</option>');
        districtObject.html('<option value="">เลือกตำบล</option>');

        $.get('get_amphure.php?province_id=' + provinceId, function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
                amphureObject.append(
                    $('<option></option>').val(item.code).html(item.name_th)
                );
            });
        });
    });

    // on change amphure
    amphureObject.on('change', function(){
        var amphureId = $(this).val();

        districtObject.html('<option value="">เลือกตำบล</option>');
        
        $.get('get_district.php?amphure_id=' + amphureId, function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
                districtObject.append(
                    $('<option></option>').val(item.id).html(item.name_th)
                );
            });
        });
    });
	
	
	
	//onload
	$.get('get_amphure.php?province_id=<? echo $province; ?>', function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
				
				
				if (item.code== "<? echo $amphur; ?>"){
				
                amphureObject.append(
					//if (item == 10){
                    	$('<option selected></option>').val(item.code).html(item.name_th)
					//}else{
						//$('<option></option>').val(item.id).html(item.name_th)
					//}
                );
				
				}else{
				console.log(item.id)
				amphureObject.append(
					//if (item == 10){
                    	$('<option></option>').val(item.code).html(item.name_th)
					//}else{
						//$('<option></option>').val(item.id).html(item.name_th)
					//}
                );
				}
            });
        });
		
		
			
		 $.get('get_district.php?amphure_id=<? echo $amphur; ?>', function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
				if (item.id == "<? echo $tambon; ?>"){
                districtObject.append(
                    $('<option selected></option>').val(item.id).html(item.name_th)
                );
				}else{
				 console.log(item.id)
				 districtObject.append(
				
                    $('<option></option>').val(item.id).html(item.name_th)
                );	
				}
            });
        });
	
		
		
	
	
});

</script>
<!--script>
function province_onload(){

	$.get('get_amphure.php?province_id=77', function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
				
				
				if (item.code == "7701"){
				
                amphureObject.append(
                    	$('<option selected></option>').val(item.code).html(item.name_th)
                );
				
				}else{
				console.log(item.id)
				amphureObject.append(
                    	$('<option></option>').val(item.code).html(item.name_th)
                );
				}
            });
        });
		
		
			
		 $.get('get_district.php?amphure_id=7701', function(data){
            var result = JSON.parse(data);
            $.each(result, function(index, item){
				if (item.id == "770102"){
                districtObject.append(
                    $('<option selected></option>').val(item.id).html(item.name_th)
                );
				}else{
				 console.log(item.id)
				 districtObject.append(
				
                    $('<option></option>').val(item.id).html(item.name_th)
                );	
				}
            });
        });
	
}
	
</script-->
</body>
</html>
<?php
mysqli_close($conn);