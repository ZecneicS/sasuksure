<?php
session_start();
$track_type = "trace";
?>
<!DOCTYPE html>
<html lang="th">
<?php

if (isset($_POST["save"])) {



	$news_title_post = $_POST["news_title"];
	$site_post = $_POST["site"];
	$content_post = $_POST["content"];
	$office_post = $_POST["office"];

	$news_type = $_POST["news_type"];
	$rq = $_POST["rq"];
	$sender = $_POST["sender"];




	$news_title = str_replace("https://", "", $news_title_post);
	$news_title = str_replace("http://", "", $news_title);

	$site = str_replace("https://", "", $site_post);
	$site = str_replace("http://", "", $site);

	$content = str_replace("https://", "", $content_post);
	$content = str_replace("http://", "", $content);

	$office = str_replace("https://", "", $office_post);
	$office = str_replace("http://", "", $office);


	


	$query = $site;








	include("upload_image.php");



	include("pdo-connect.php");









	// nvillage

	/*
	$query = "INSERT INTO news (news_title,site,content_request,news_type,rq,sender,office,image,time_stamp)";
	$query .= "VALUES ('" . mysqli_real_escape_string($connect, $news_title) . "',";
	$query .= "'" . mysqli_real_escape_string($connect, $site) . "',";
	$query .= "'" . mysqli_real_escape_string($connect, $content) . "',";
	$query .= "'" . mysqli_real_escape_string($connect, $_POST["news_type"]) . "',";
	$query .= "'" . mysqli_real_escape_string($connect, $_POST["rq"]) . "',";
	$query .= "'" . mysqli_real_escape_string($connect, $_POST["sender"]) . "',";
	$query .= "'" . mysqli_real_escape_string($connect, $office) . "',";
	$query .= "'" . $new_file_name . "',";
	$query .= "now());";
	*/

	
	$stmt = $conn->prepare("INSERT INTO news(news_title,site,content_request,news_type,rq,sender,office,image,time_stamp) 
						  	VALUES(:news_title, :site, :content, :news_type, :rq, :sender, :office, :new_file_name, now());");

	$stmt->bindParam(":news_title", $news_title);
	$stmt->bindParam(":site", $site);
	$stmt->bindParam(":content", $content);
	$stmt->bindParam(":news_type", $news_type);
	$stmt->bindParam(":rq", $rq);
	$stmt->bindParam(":sender", $sender);
	$stmt->bindParam(":office", $office);
	$stmt->bindParam(":new_file_name", $new_file_name);


	try {
		$stmt->execute();
	} catch (PDOException $e) {
		echo $e->getMessage();
	}




	/*
	$query = "INSERT INTO news (news_title,site,content_request,news_type,rq,sender,office,image,time_stamp)";
	$query.= "VALUES ('".$news_title."',";
	$query.= "'".$site."',";
	$query.= "'".$content."',";
	$query.= "'".$_POST["news_type"]."',";
	$query.= "'".$_POST["rq"]."',";
	$query.= "'".$_POST["sender"]."',";
	$query.= "'".$office."',";
	$query.= "'".$new_file_name."',";
	$query.= "now());";

	
	echo $query;
	*/



	//$result = mysqli_query($connect, $query) or die("Error in query: $query " . mysqli_error($connect));


	//mysqli_close($connect);

	$registersuccess = "1";


	//echo $query.$registersuccess;




}






?>

<head>
	<title>แจ้งเบาะแสข่าว | สาสุข ชัวร์</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta property="og:url" content="" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="แจ้งเบาะแสข่าว | สาสุข ชัวร์" />
	<meta property="og:description" content="" />
	<meta property="og:image" content="assets/app/images/default/900-600.jpg" />

	<link rel="icon" type="image/x-icon" href="assets/app/images/favicon.ico" />
	<style>
		:root {
			--color0: #c0ca67;
			--color1: #c0ca67;
			--color2: #1d684a;
			--color3: #1d684a;
			--color4: #0d838d;
			--color5: #d7d8d0;

		}
	</style>
	<link defer async rel="stylesheet" type="text/css" href="assets/vendor/semantic-ui/components/transition.min.css" media="screen" />
	<link defer async rel="stylesheet" type="text/css" href="assets/vendor/semantic-ui/components/dropdown.min.css" media="screen" />
	<link defer async rel="stylesheet" type="text/css" href="assets/app/fonts/includes.css" media="screen" />
	<link defer async rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" media="screen" />
	<link defer async rel="stylesheet" type="text/css" href="assets/vendor/slick-1.8.1/slick/slick.css" media="screen" />
	<link defer async rel="stylesheet" type="text/css" href="assets/vendor/simple-calendar/simple-calendar.min.css" media="screen" />
	<link defer async rel="stylesheet" type="text/css" href="assets/vendor/fancybox-3.5.7/dist/jquery.fancybox.min.css" media="screen" />
	<link defer async rel="stylesheet" type="text/css" href="assets/vendor/dropzone-5.7.0/dist/min/dropzone.min.css" media="screen" />
	<link defer async rel="stylesheet" type="text/css" href="assets/vendor/vanilla-datepicker/dist/css/datepicker.min.css" />
	<link async rel="stylesheet" type="text/css" href="assets/app/css/style.css" media="screen" />
	<link async rel="stylesheet" type="text/css" href="assets/app/css/grids.css" media="screen" />
	<link async rel="stylesheet" type="text/css" href="assets/app/css/navs.css" media="screen" />
	<link async rel="stylesheet" type="text/css" href="assets/app/css/blocks.css" media="screen" />
	<link async rel="stylesheet" type="text/css" id="css-theme" href="themes/app/css/0.css" media="screen" />
	<link async rel="stylesheet" type="text/css" href="assets/app/css/ie-fix.css" media="screen" />
	<link defer async rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css" />
	<link defer async rel="stylesheet" type="text/css" href="assets/app/css/custom.css" />

	<!-- STYLES -->
	<link href="assets/home/css/home.css" rel="stylesheet" type="text/css" />
	<link href="assets/vendor/toastr-2.1.4/toastr.css" rel="stylesheet" type="text/css" />

	<!-- For inline style -->
	<link href="assets/app/css/index_style.css" rel="stylesheet" type="text/css" />

	<!-- media -->
	<link media="all" type="text/css" rel="stylesheet" href="public/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<link media="all" type="text/css" rel="stylesheet" href="public/css/siteMain.css">


	<link href="lib_aa/bootstrap-4.5.2-dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/home/css/home.css" rel="stylesheet" type="text/css" />




	<script>
		const LANG = 'th';
	</script>
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-DH3ELFZ1EY"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-DH3ELFZ1EY');
	</script>
	<!-- End Google Tag Manager -->
</head>

<body data-clarity-unmask="true">


	<?php include("menu.php"); ?>







	<style>
		.slide {
			display: block;
		}

		.slide-sm {
			display: none;
			height: 270px;
			overflow: hidden;
			text-align: center;
			position: relative;
		}

		.slide-sm img {
			min-width: 100%;
			height: 270px !important;
			position: absolute;
			top: -9999px;
			bottom: -9999px;
			left: -9999px;
			right: -9999px;
			margin: auto;
		}

		@media only screen and (max-width: 768px) {
			.slide {
				display: none !important;
			}

			.slide-sm {
				display: block !important;
			}

			.dots li.slick-active>button::before {
				font-size: 2rem !important;
			}
		}

		.btn-1 {
			color: #343a40;
			background-color: #e5f4f2;
			border-color: #e5f4f2;
		}

		.btn-1:hover {
			color: #fff;
			background-color: #138496;
			border-color: #117a8b
		}

		.btn-1.focus,
		.btn-1:focus {
			color: #fff;
			background-color: #138496;
			border-color: #117a8b;
			box-shadow: 0 0 0 .2rem rgba(58, 176, 195, .5)
		}

		.btn-1.disabled,
		.btn-1:disabled {
			color: #fff;
			background-color: #17a2b8;
			border-color: #17a2b8
		}

		.btn-1:not(:disabled):not(.disabled).active,
		.btn-1:not(:disabled):not(.disabled):active,
		.show>.btn-1.dropdown-toggle {
			color: #fff;
			background-color: #117a8b;
			border-color: #10707f
		}

		.btn-1:not(:disabled):not(.disabled).active:focus,
		.btn-1:not(:disabled):not(.disabled):active:focus,
		.show>.btn-1.dropdown-toggle:focus {
			box-shadow: 0 0 0 .2rem rgba(58, 176, 195, .5)
		}

		.btn-2 {
			color: #343a40;
			background-color: #b2dfd8;
			border-color: #e5f4f2;
		}

		.btn-2:hover {
			color: #fff;
			background-color: #138496;
			border-color: #117a8b
		}

		.btn-2.focus,
		.btn-2:focus {
			color: #fff;
			background-color: #138496;
			border-color: #117a8b;
			box-shadow: 0 0 0 .2rem rgba(58, 176, 195, .5)
		}

		.btn-2.disabled,
		.btn-2:disabled {
			color: #fff;
			background-color: #17a2b8;
			border-color: #17a2b8
		}

		.btn-2:not(:disabled):not(.disabled).active,
		.btn-2:not(:disabled):not(.disabled):active,
		.show>.btn-2.dropdown-toggle {
			color: #fff;
			background-color: #117a8b;
			border-color: #10707f
		}

		.btn-2:not(:disabled):not(.disabled).active:focus,
		.btn-2:not(:disabled):not(.disabled):active:focus,
		.show>.btn-2.dropdown-toggle:focus {
			box-shadow: 0 0 0 .2rem rgba(58, 176, 195, .5)
		}

		.btn-3 {
			color: #343a40;
			background-color: #ededed;
			border-color: #e5f4f2;
			font-size: 1.5rem;
		}

		.btn-3:hover {
			color: #fff;
			background-color: #138496;
			border-color: #117a8b
		}

		.btn-3.focus,
		.btn-3:focus {
			color: #fff;
			background-color: #138496;
			border-color: #117a8b;
			box-shadow: 0 0 0 .2rem rgba(58, 176, 195, .5)
		}

		.btn-3.disabled,
		.btn-3:disabled {
			color: #fff;
			background-color: #17a2b8;
			border-color: #17a2b8
		}

		.btn-3:not(:disabled):not(.disabled).active,
		.btn-3:not(:disabled):not(.disabled):active,
		.show>.btn-3.dropdown-toggle {
			color: #fff;
			background-color: #117a8b;
			border-color: #10707f
		}

		.btn-3:not(:disabled):not(.disabled).active:focus,
		.btn-3:not(:disabled):not(.disabled):active:focus,
		.show>.btn-3.dropdown-toggle:focus {
			box-shadow: 0 0 0 .8rem rgba(58, 176, 195, .5)
		}


		.btn-4 {
			color: #343a40;
			background-color: #ededed;
			border-color: #e5f4f2;
			font-size: 1.3rem;
		}

		.btn-4:hover {
			color: #fff;
			background-color: #138496;
			border-color: #117a8b
		}

		.btn-4.focus,
		.btn-4:focus {
			color: #fff;
			background-color: #138496;
			border-color: #117a8b;
			box-shadow: 0 0 0 .2rem rgba(58, 176, 195, .5)
		}

		.btn-4.disabled,
		.btn-4:disabled {
			color: #fff;
			background-color: #17a2b8;
			border-color: #17a2b8
		}

		.btn-4:not(:disabled):not(.disabled).active,
		.btn-4:not(:disabled):not(.disabled):active,
		.show>.btn-4.dropdown-toggle {
			color: #fff;
			background-color: #117a8b;
			border-color: #10707f
		}

		.btn-4:not(:disabled):not(.disabled).active:focus,
		.btn-4:not(:disabled):not(.disabled):active:focus,
		.show>.btn-4.dropdown-toggle:focus {
			box-shadow: 0 0 0 .8rem rgba(58, 176, 195, .5)
		}
	</style>



	<section id="pageSite">
		<div class="col-12 hr-sect ml-2 mt-4 ">
			<h4 class="title">แจ้งเบาะแสข่าว</h4>
		</div>


		<div id="div_register" class="col-12">





			<form role="form" id="form_register" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
				<div class="my-3 p-3 bg-white border rounded shadow-sm">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">ชื่อเรื่อง</label>
						<div class="col-sm-9">
							<input id="news_title" name="news_title" class="form-control" placeholder="ชื่อเรื่อง" type="text" required minlength="10" value="<?php echo $news_title; ?>">
						</div>
					</div>
				</div>

				<div class="my-3 p-3 bg-white border rounded shadow-sm">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">ลิงก์เว็บไซต์ที่ต้องการแจ้งข่าว</label>
						<div class="col-sm-9">
							<input id="site" name="site" class="form-control" placeholder="กรอก URL เว็บไซต์ที่ต้องการแจ้งเบาะแสข่าว" type="text" value="<?php echo $site; ?>">
						</div>
					</div>
				</div>
				<div class="my-3 p-3 bg-white border rounded shadow-sm">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">เนื้อหาที่ต้องการให้ตรวจสอบ</label>
						<div class="col-sm-9">
							<textarea class="form-control rounded-0" name="content" id="content" rows="5" placeholder="ข้อมูลที่ต้องการแจ้งให้ตรวจสอบ" value="<?php echo $content; ?>" required><?php echo $content; ?></textarea>
						</div>
					</div>
				</div>

				<div class="my-3 p-3 bg-white border rounded shadow-sm">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">ภาพประกอบ</label>
						<div class="col-sm-9">
							<!--input type="file" class="form-control" id="file1" name="file1"  /-->
							<div class="custom-file">
								<input type="file" accept=".jpg, .png, .jpeg" class="custom-file-input" name="file_to_upload" id="file_to_upload">
								<label class="custom-file-label" for="file"></label>
							</div>
						</div>
					</div>
				</div>

				<div class="my-3 p-3 bg-white border rounded shadow-sm">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">เกี่ยวกับเรื่องใด</label>


						<div class="col-sm-9">

							<button id="btn2_1" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(1)">แอลกอฮอล์</button>&nbsp;&nbsp;
							<button id="btn2_2" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(2)">ยาสูบ</button>&nbsp;&nbsp;
							<button id="btn2_3" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(3)">โรค</button>&nbsp;&nbsp;
							<button id="btn2_4" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(4)">ครอบครัว</button>&nbsp;&nbsp;
							<button id="btn2_5" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(5)">การรักษา</button>&nbsp;&nbsp;
							<button id="btn2_6" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(6)">เกี่ยวกับจิตใจ</button>&nbsp;&nbsp;
							<button id="btn2_7" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(7)">สิ่งแวดล้อม</button>&nbsp;&nbsp;
							<button id="btn2_8" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(8)">สินค้าสุขภาพ</button>&nbsp;&nbsp;
							<button id="btn2_9" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(9)">เป็นอุบัติเหตุฉุกเฉิน</button>&nbsp;&nbsp;
							<button id="btn2_10" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(10)">อาหารการกิน</button>&nbsp;&nbsp;
							<button id="btn2_11" type="button" class="btn btn-4 mb-2 btn-sm" onclick="rq_2(11)">อื่นๆ</button>&nbsp;&nbsp;

						</div>

						<div class="col-sm-3" style="display:none">

							<input type="text" class="form-control" id="news_type" name="news_type" required>
						</div>






					</div>
				</div>




				<div class="my-3 p-3 bg-white border rounded shadow-sm">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">ระดับความด่วน</label>
						<div class="col-sm-6">

							<button id="btn0_3" type="button" class="btn btn-3 mb-2" onclick="rq_0(3)">ด่วนที่สุด</button>&nbsp;&nbsp;
							<button id="btn0_2" type="button" class="btn btn-3 mb-2" onclick="rq_0(2)">ด่วนมาก</button>&nbsp;&nbsp;
							<button id="btn0_1" type="button" class="btn btn-3 mb-2" onclick="rq_0(1)">ด่วน</button>

						</div>
						<div class="col-sm-3" style="display:none">

							<input type="text" class="form-control" id="rq" name="rq" required>
						</div>

					</div>
				</div>







				<div class="my-3 p-3 bg-white border rounded shadow-sm">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">แจ้งในนาม</label>


						<div class="col-sm-3">

							<button id="btn1_1" type="button" class="btn btn-3 mb-2" onclick="rq_1(1)">ประชาชนทั่วไป</button>&nbsp;&nbsp;
							<button id="btn1_2" type="button" class="btn btn-3 mb-2" onclick="rq_1(2)">หน่วยงาน</button>&nbsp;&nbsp;


						</div>
						<div class="col-sm-3" id='div_sender' style="display:none">

							<input type="text" class="form-control" id="office" name="office" placeholder="กรอกชื่อหน่วยงาน">
						</div>
						<div class="col-sm-3" style="display:none">

							<input type="text" class="form-control" id="sender" name="sender" required>
						</div>






					</div>
				</div>


				<div class="my-3 p-3 bg-white border rounded shadow-sm">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label">ข้อตกลง</label>

						<div class="form-check-inline col-sm-6 text-center">&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="https://anamai.moph.go.th/th/website-policy" target="_blank">นโยบายเว็บไซต์</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://anamai.moph.go.th/th/privacy-policy" target="_blank">นโยบายการคุ้มครองข้อมูลส่วนบุคคล</a>
						</div>

					</div>

					<div class="form-group row">
						<label class="col-sm-3 col-form-label"></label>

						<div class="form-check-inline col-sm-6 text-center">&nbsp;&nbsp;&nbsp;&nbsp;
							<input class="form-check-input" type="checkbox" name="agree" id="agree" value="1" required>
							<label class="form-check-label" for="agree">ฉันได้อ่านและยอมรับ นโยบายเว็บไซต์และนโยบายการคุ้มครองข้อมูลส่วนบุคคลของเว็บไซต์แล้ว * </label>
						</div>

					</div>
				</div>


				<div class="form-group center">

					<button type="submit" id="save" name="save" class="btn btn-primary" style="width:200px"> แจ้งเบาะแสข่าว</button>
					<!--a role="button" class="btn btn-warning" href='index.php' >กลับหน้าหลัก</a-->

				</div>
			</form>

		</div>


	</section>







	<?php

	if ($registersuccess == "1") {
	?>
		<!-- success modal -->
		<div id="myModal" class="modal fade" tabindex="-1" data-keyboard="false">
			<div class="modal-dialog shadow">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">สาสุข ชัวร์</h5>
					</div>
					<div class="modal-body">

						<h3 style="color:#063" align="center">แจ้งเบาะแสข่าว สำเร็จ</h3>
						<?php //echo $query;
						?>


					</div>
					<div class="modal-footer">

						<a type="button" class="btn btn-info" href="main.php">ตกลง</a>

					</div>
				</div>
			</div>
		</div>
	<?php  } ?>







	<?php include("footer.php"); ?>


	<!-- SCRIPTS -->
	<script src="assets/app/js/jquery-3.5.1.min.js"></script>
	<script defer src="assets/vendor/semantic-ui/components/transition.min.js"></script>
	<script defer src="assets/vendor/semantic-ui/components/dropdown.min.js"></script>
	<script src="assets/vendor/slick-1.8.1/slick/slick.min.js"></script>
	<script defer src="assets/vendor/simple-calendar/simple-calendar.min.js"></script>
	<script defer src="assets/vendor/fancybox-3.5.7/dist/jquery.fancybox.min.js"></script>
	<script defer src="assets/vendor/dropzone-5.7.0/dist/min/dropzone.min.js"></script>
	<script defer src="assets/vendor/vanilla-datepicker/dist/js/datepicker.min.js"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js"></script>
	<script defer src="assets/app/js/script.js"></script>
	<script defer src="assets/app/js/lazy-load.js"></script>
	<script defer src="assets/app/js/contact-form.js"></script>
	<script defer src="assets/app/js/stats.js"></script>
	<script src="https://anamai.moph.go.th/assets/vendor/toastr-2.1.4/toastr.js" async defer></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<!-- For inline Scripts -->
	<script>
		var bannerIndex = $("#bannerIndex").val();
		if (bannerIndex > 6) {
			$('.multiple-items').slick({
				infinite: true,
				slidesToShow: 6,
				slidesToScroll: 1,
				prevArrow: "",
				nextArrow: "",
				autoplay: true
			});
		}


		function rq_0(a) {
			var score = document.getElementById('rq').value = a;

			if (score == 3) {
				document.getElementById('rq').value = "3"

				document.getElementById('btn0_3').style.backgroundColor = "#E97777";
				document.getElementById('btn0_2').style.backgroundColor = "#ededed";
				document.getElementById('btn0_1').style.backgroundColor = "#ededed";


			}

			if (score == 2) {
				document.getElementById('rq').value = "2"

				document.getElementById('btn0_3').style.backgroundColor = "#ededed";
				document.getElementById('btn0_2').style.backgroundColor = "#FD841F";
				document.getElementById('btn0_1').style.backgroundColor = "#ededed";


			}

			if (score == 1) {
				document.getElementById('rq').value = "1"

				document.getElementById('btn0_3').style.backgroundColor = "#ededed";
				document.getElementById('btn0_2').style.backgroundColor = "#ededed";
				document.getElementById('btn0_1').style.backgroundColor = "#E6E5A3";


			}



		}


		function rq_1(a) {
			var score = document.getElementById('sender').value = a;


			if (score == 1) {
				document.getElementById('sender').value = "1"

				document.getElementById('btn1_1').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn1_2').style.backgroundColor = "#ededed";

				document.getElementById('div_sender').style.display = 'none';



			}

			if (score == 2) {
				document.getElementById('sender').value = "2"

				document.getElementById('btn1_1').style.backgroundColor = "#ededed";
				document.getElementById('btn1_2').style.backgroundColor = "#E6E5A3";

				document.getElementById('div_sender').style.display = 'block';




			}




		}

		function rq_2(a) {
			var score = document.getElementById('news_type').value = a;

			if (score == 1) {
				document.getElementById('news_type').value = "1"

				document.getElementById('btn2_1').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 2) {
				document.getElementById('news_type').value = "2"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 3) {
				document.getElementById('news_type').value = "3"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 4) {
				document.getElementById('news_type').value = "4"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 5) {
				document.getElementById('news_type').value = "5"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 6) {
				document.getElementById('news_type').value = "6"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 7) {
				document.getElementById('news_type').value = "7"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 8) {
				document.getElementById('news_type').value = "8"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 9) {
				document.getElementById('news_type').value = "9"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 10) {
				document.getElementById('news_type').value = "10"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#E6E5A3";
				document.getElementById('btn2_11').style.backgroundColor = "#ededed";
			} else if (score == 11) {
				document.getElementById('news_type').value = "11"

				document.getElementById('btn2_1').style.backgroundColor = "#ededed";
				document.getElementById('btn2_2').style.backgroundColor = "#ededed";
				document.getElementById('btn2_3').style.backgroundColor = "#ededed";
				document.getElementById('btn2_4').style.backgroundColor = "#ededed";
				document.getElementById('btn2_5').style.backgroundColor = "#ededed";
				document.getElementById('btn2_6').style.backgroundColor = "#ededed";
				document.getElementById('btn2_7').style.backgroundColor = "#ededed";
				document.getElementById('btn2_8').style.backgroundColor = "#ededed";
				document.getElementById('btn2_9').style.backgroundColor = "#ededed";
				document.getElementById('btn2_10').style.backgroundColor = "#ededed";
				document.getElementById('btn2_11').style.backgroundColor = "#E6E5A3";
			}


		}
	</script>



	<script>
		$(document).ready(function() {
			$('#btn1_2').on("click", function(event) {
				if ($("#office").val() == '') {
					$("#office").prop('required', true);
					console.log("true");
				} else {
					$("#office").prop('required', false);
					console.log("false");
				}

				var text = $('#site').val();
				// modify text
				text = text.replace('https://', '');

				text = text.replace('http://', '');
				// update element text
				$('#site').val(text);




				//console.log("btn1_1");
				console.log(text);


			});

			$('#btn1_1').on("click", function(event) {

				$("#office").prop('required', false);




				var text = $('#site').val();
				// modify text
				text = text.replace('https://', '');

				text = text.replace('http://', '');
				// update element text
				$('#site').val(text);




				//console.log("btn1_1");
				console.log(text);


			});



		});
	</script>

	<script src="lib_aa/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>

	<?php if ($registersuccess == "1") { ?>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#myModal").modal("show");
			});
		</script>

	<?php } ?>

</body>
<script>
	$(".custom-file-input").on("change", function() {
		var fileName = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});
</script>

<script>
	$("#file_to_upload").change(function() {
		var extall = "jpg,jpeg,png";
		file = document.uptempate.upload.value;
		ext = file.split('.').pop().toLowerCase();
		if (parseInt(extall.indexOf(ext)) < 0) {
			swal("พบข้อผิดพลาด!", "กรุณาอัปโหลดไฟล์ jpg, jpeg, png เท่านั้น", {
				icon: "warning",
				buttons: {
					confirm: {
						text: "ตกลง",
						className: 'btn btn-warning'
					}
				}
			});
			$("#file_to_upload").val('');
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		}
		var maxsize_byte = 2097152;
		var fld = document.getElementById('upload');
		if (fld.files && fld.files.length == 1 && fld.files[0].size > maxsize_byte) {
			swal("พบข้อผิดพลาด!", "กรุณาอัปโหลดไฟล์ไม่เกิน 2 MB", {
				icon: "warning",
				buttons: {
					confirm: {
						text: "ตกลง",
						className: 'btn btn-warning'
					}
				}
			});
			$("#file_to_upload").val('');
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		}

	});
</script>

</html>