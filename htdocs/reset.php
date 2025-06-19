<?php

$regissuccess = "";



$sid = $_GET["sid"];
$sid_get = $sid;
$sid_post = $_POST["sid"];




include("dbase.php");
	
	
	

if (count($_POST) > 0) {





	        $query = "SELECT  first_name,email,pin from users where email = '".trim($_POST["email"])."' limit 1;"; 
			
			
			$result = mysqli_query($connect,$query) or die ("Error in query: $query " . mysqli_error($connect));
			
			$count_sid = 0;
			$count_sid = mysqli_num_rows($result);

			if ($count_sid >= 1){


				while($rows = mysqli_fetch_array($result))
					{
						$first_name = $rows["first_name"];
						$email = $rows["email"];
						$pin = $rows["pin"];
						
					}	
				
				$regissuccess = "1";

				echo "<meta http-equiv='refresh' content='0;URL=https://swingdb.org/phpmailer/gmail3.php?p0=".$_POST["email"]."&p1=".$first_name."&p2=".$pin."'>";
				
			}else{

				$regissuccess = "0";
			}


			
				
	
	
}else{


			/*
	        $query = "SELECT r.sid,r.email,r.hcode,r.fullname,h.fullname as hname FROM registration r inner join healthoffice h on r.hcode = h.maincode  WHERE sid= '" .trim($_GET["sid"]). "' limit 1;"; 
			
			
			$result = mysqli_query($connect,$query) or die ("Error in query: $query " . mysqli_error());
			$count_sid = 0;
			while($rows = mysqli_fetch_array($result))
			{
				$fullname = $rows["fullname"];
				$hname = $rows["hname"];
				$hcode = $rows["hcode"];
				$email = $rows["email"];
				$registersuccess = "0";	
				$count_sid++;
			}	
			*/

			
}
//echo "2".$_POST["email"];


?>
<!DOCTYPE html>
<html lang="th">
<head>
<title>ลืมรหัสผ่าน</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />


<meta property="og:url"           content="https://sasukoonchai.anamai.moph.go.th/" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="แบบสำรวจความเห็นต่อการแนวปฏิบัติตามแนวคิดองค์กรรอบรู้ด้านสุขภาพ สำหรับเจ้าหน้าที่" />
<meta property="og:description"   content="สาสุขอุ่นใจ" />
<meta property="og:image"         content="assets/app/images/default/900-600.jpg" />

<link rel="icon" type="image/x-icon" href="assets/app/images/favicon.ico" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-D3WP6TZ2QH"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-D3WP6TZ2QH');
</script>

	<style>
	:root{
        --color0:#47484d;
        --color1:#c0ca67;
        --color2:#1d684a;
        --color3:#284e51;
        --color4:#0d838d;
        --color5:#d7d8d0;
    }
</style>
<link defer async rel="stylesheet" type="text/css" href="assets/vendor/semantic-ui/components/transition.min.css" media="screen" />
<link defer async rel="stylesheet" type="text/css" href="assets/vendor/semantic-ui/components/dropdown.min.css" media="screen" />
<link defer async rel="stylesheet" type="text/css" href="assets/app/fonts/includes.css" media="screen" />
<link defer async rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" media="screen" />
<link async rel="stylesheet" type="text/css" href="assets/app/css/style.css" media="screen" />
<link async rel="stylesheet" type="text/css" href="assets/app/css/grids.css" media="screen" />
<link async rel="stylesheet" type="text/css" href="assets/app/css/navs.css" media="screen" />
<link async rel="stylesheet" type="text/css" href="assets/app/css/blocks.css" media="screen" />
<link async rel="stylesheet" type="text/css" id="css-theme" href="themes/app/css/0.css" media="screen" />
<link async rel="stylesheet" type="text/css" href="assets/app/css/ie-fix.css" media="screen" />
<link defer async rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css" />
<link defer async rel="stylesheet" type="text/css" href="assets/app/css/custom.css" />


<link href="assets/home/css/home.css" rel="stylesheet" type="text/css" />					



<link href="lib_aa/bootstrap-4.5.2-dist/css/question_bootstrap.min.css" rel="stylesheet">

<!-- data table -->
<!--link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet"-->
<link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<!-- -->


    	
<style>
        :root {
            --swiper-theme-color: #0d838d;
        }
        .client-01 .clients {
            width: 100%;
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
            padding-bottom: 36px;
        }
        .client-01 .client {
            width : 100%;
        }
        .swiper-container {
            padding-left: 46px;
            padding-right: 46px;
        }

        .swiper-button-next, .swiper-button-prev {
            top: 0;
            height: 100%;
            background-color: #d7d8d0;
        }

        .swiper-button-next {
            right: 0;
        }

        .swiper-button-prev {
            left: 0;
        }

        .swiper-container-horizontal > .swiper-pagination-bullets {
            bottom: -5px;
        }

		/* B-4 */
		.b-4-container {
		width: 100%;
		padding-right: 15px;
		padding-left: 15px;
		margin-right: auto;
		margin-left: auto;
		}

		@media (min-width: 576px) {
		.b-4-container {
			max-width: 540px;
		}
		}

		@media (min-width: 768px) {
		.b-4-container {
			max-width: 720px;
		}
		}

		@media (min-width: 992px) {
		.b-4-container {
			max-width: 960px;
		}
		}

		@media (min-width: 1200px) {
		.b-4-container {
			max-width: 1140px;
		}
		}
		.row {
		display: -ms-flexbox;
		display: flex;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		margin-right: -15px;
		margin-left: -15px;
		}

		.no-gutters {
		margin-right: 0;
		margin-left: 0;
		}

		.no-gutters > .col,
		.no-gutters > [class*="col-"] {
		padding-right: 0;
		padding-left: 0;
		}

		.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col,
		.col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm,
		.col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md,
		.col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg,
		.col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl,
		.col-xl-auto {
		position: relative;
		width: 100%;
		padding-right: 15px;
		padding-left: 15px;
		}

		.col {
		-ms-flex-preferred-size: 0;
		flex-basis: 0;
		-ms-flex-positive: 1;
		flex-grow: 1;
		min-width: 0;
		max-width: 100%;
		}

		.row-cols-1 > * {
		-ms-flex: 0 0 100%;
		flex: 0 0 100%;
		max-width: 100%;
		}

		.row-cols-2 > * {
		-ms-flex: 0 0 50%;
		flex: 0 0 50%;
		max-width: 50%;
		}

		.row-cols-3 > * {
		-ms-flex: 0 0 33.333333%;
		flex: 0 0 33.333333%;
		max-width: 33.333333%;
		}

		.row-cols-4 > * {
		-ms-flex: 0 0 25%;
		flex: 0 0 25%;
		max-width: 25%;
		}

		.row-cols-5 > * {
		-ms-flex: 0 0 20%;
		flex: 0 0 20%;
		max-width: 20%;
		}

		.row-cols-6 > * {
		-ms-flex: 0 0 16.666667%;
		flex: 0 0 16.666667%;
		max-width: 16.666667%;
		}

		.col-auto {
		-ms-flex: 0 0 auto;
		flex: 0 0 auto;
		width: auto;
		max-width: 100%;
		}

		.col-1 {
		-ms-flex: 0 0 8.333333%;
		flex: 0 0 8.333333%;
		max-width: 8.333333%;
		}

		.col-2 {
		-ms-flex: 0 0 16.666667%;
		flex: 0 0 16.666667%;
		max-width: 16.666667%;
		}

		.col-3 {
		-ms-flex: 0 0 25%;
		flex: 0 0 25%;
		max-width: 25%;
		}

		.col-4 {
		-ms-flex: 0 0 33.333333%;
		flex: 0 0 33.333333%;
		max-width: 33.333333%;
		}

		.col-5 {
		-ms-flex: 0 0 41.666667%;
		flex: 0 0 41.666667%;
		max-width: 41.666667%;
		}

		.col-6 {
		-ms-flex: 0 0 50%;
		flex: 0 0 50%;
		max-width: 50%;
		}

		.col-7 {
		-ms-flex: 0 0 58.333333%;
		flex: 0 0 58.333333%;
		max-width: 58.333333%;
		}

		.col-8 {
		-ms-flex: 0 0 66.666667%;
		flex: 0 0 66.666667%;
		max-width: 66.666667%;
		}

		.col-9 {
		-ms-flex: 0 0 75%;
		flex: 0 0 75%;
		max-width: 75%;
		}

		.col-10 {
		-ms-flex: 0 0 83.333333%;
		flex: 0 0 83.333333%;
		max-width: 83.333333%;
		}

		.col-11 {
		-ms-flex: 0 0 91.666667%;
		flex: 0 0 91.666667%;
		max-width: 91.666667%;
		}

		.col-12 {
		-ms-flex: 0 0 100%;
		flex: 0 0 100%;
		max-width: 100%;
		}

		.img-fluid {
		max-width: 100%;
		height: auto;
		}
		.p-1 {
			padding: 0.25rem !important;
		}
		.client-01{
			padding:0; 
			padding-top:4px;
			height:0;
			visibility: hidden;
			-moz-animation: cssAnimation 0s ease-in 2s forwards;
			/* Firefox */
			-webkit-animation: cssAnimation 0s ease-in 2s forwards;
			/* Safari and Chrome */
			-o-animation: cssAnimation 0s ease-in 2s forwards;
			/* Opera */
			animation: cssAnimation 0s ease-in 2s forwards;
			-webkit-animation-fill-mode: forwards;
			animation-fill-mode: forwards;
		}
		@keyframes cssAnimation {
			to {
				height:auto;
				visibility:visible;
			}
		}
		@-webkit-keyframes cssAnimation {
			to {
				height:auto;
				visibility:visible;
			}
		}

    </style>



	</head>

<body>

<?php include("menu.php"); ?>

	
	
<!-- <div class="page-loader"><div class="wrapper"><div class="loader"></div></div></div> -->

<section class="banner-01 img-only">
    <h2 style="display: none;">Anamai</h2>
</section>
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
	
	.bg-color-01{
		background:#ffffff;
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
</style>
<style>
body {
  font-family: 'DB-Ozone-X';
}

/*
@font-face {
    font-family: DB-Ozone-X;
    src: url(assets/app/fonts/DB-Ozone-X/DB-Ozone-X-UltraLi.ttf);
}
*/

.font-set-default{
	font-size:20px;
	
}

</style>

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-home fa-lg" aria-hidden="true"></i>
                        หน้าแรก </a></li>
                <li class="breadcrumb-item active"><a>แจ้งข้อมูลการลงทะเบียนทางอีเมล</a></li>
            </ol>
        </nav>
        <br>
        <h2 class="text-center">แจ้งข้อมูลการลงทะเบียนทางอีเมล</h2>
        <hr>
    </div>
    <div class="container bg-light">
        <div class="card-body mx-auto" style="max-width: 600px;">
            <form name="frmRegister" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                
 
                                            <div class="form-group input-group" id="div_password">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                                </div>
                                                <input id="email" name="email" class="form-control" placeholder="กรอกอีเมลที่ใช้เข้าสู่ระบบ" type="email"  required>
                                                    
                                            </div>
                                           
                                             <input type="hidden"  name="sid"  value= "<?php echo $sid; ?>">
                                             <input type="hidden"  name="account"  value= "<?php echo $hcode; ?>">


											 <button type="submit" class="btn btn-primary btn-block "> ส่งข้อมูลการเข้าระบบไปยังอีเมล </button>
                                            
                                            
                                            
                                        </div>
                </div>
                                    
                                        
                                        
                
               
                
                
             
               
            </form>
        </div>
    </div>

    <br>

   <?php  include("footer.php"); ?>

    <!-- success modal -->
	<?php if ($regissuccess == "1") { ?>
    <div id="myModal" class="modal fade " tabindex="-1" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog shadow">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ข้อความจากระบบ</h5>
                    <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
                </div>
                <div class="modal-body text-center">
                    <p>ระบบกำลังส่งข้อมูลไปยังอีเมล : <?php echo $_POST["email"]; ?> </p>
                    <p style="color:red;">โปรดตรวจสอบข้อความในกล่องจดหมายขยะ,Junk Email หรือ Spam mail</p>
                    
                    
                </div>
               
            </div>
        </div>
    </div>
	<?php } else if ($regissuccess == "0") { ?>


	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">ไม่พบข้อมูล</h5>
			</div>
			<div class="modal-body">
			<h3 style="color:red" align="center">ไม่พบข้อมูลอีเมลในระบบ</h3>
						
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">ปิด</button>
				
			</div>
			</div>
		</div>
		</div>
    

    <?php } ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="lib_aa/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>

    <script src="lib_aa/js/index.js"></script>
    <script src="lib_aa/js/validate.js"></script>

    <?php
        if ($regissuccess == "1") {
    ?>
        <script type="text/javascript">
        $(document).ready(function() {
            $("#myModal").modal("show");
        });
        </script>

    <?php
        }else if ($regissuccess == "0") {
    ?>

<script type="text/javascript">
        $(document).ready(function() {
            $("#exampleModal").modal("show");
        });
        </script>

<?php
        }
    ?>

    <!-- validate email -->
    <script type="text/javascript">
    
	
	
	
	function checkPasswordMatch() {
		
		
		var password = document.getElementById("password").value;
		var confirmPassword =document.getElementById("password2").value;
	
		if (password != confirmPassword){
			$("#password2").addClass("is-invalid");
            $("#password2").removeClass("is-valid");            
			$("#divCheckPasswordMatch").html("Passwords do not match!");
		}else{
			$("#password2").addClass("is-valid");
            $("#password2").removeClass("is-invalid");
			$("#divCheckPasswordMatch").html("Passwords match.");
		}
	
	}

	$(document).ready(function () {
	   $("#password2").keyup(checkPasswordMatch);
	});
    </script>

   
</body>

<script>

 function cal_age() {
        var byear = document.getElementById("byear").value;
        var now = new Date();
        var n = now.getFullYear();
        var age = n - byear;

        document.getElementById("age").value = age;
    }
	
	
	//ระบุหน่วยงานในการลงทะเบียน
	 function office_div(){
		    if ($('#occupation').val() == '1')
			  {
				$("#office_div").show();
			  }
			  else
			  {
				$("#office_div").hide();
				document.getElementById("office_name").value = "";
			  }
	}
	
	
	 //ข่าวสาร
	 function news_div(){
		    if ($('#news').val() == '4')
			  {
				$("#news_div").show();
			  }
			  else
			  {
				$("#news_div").hide();
			  }
	}
	
	//สนใจ
	 function attention_div(){
		    if ($('#attention').val() == '5')
			  {
				$("#attention_div").show();
			  }
			  else
			  {
				$("#attention_div").hide();
			  }
	}
	
	  function news_opt() {
		var selectBox = document.getElementById("news");
		var selectedValue = selectBox.options[selectBox.selectedIndex].value;
		if (selectedValue == "4"){
			$("#news_other").prop('required',true);
		}else {
			document.getElementById("news_other").value = "";
			$("#news_other").prop('required',false);
		}
	} 
	
	 function attention_opt() {
		var selectBox = document.getElementById("attention");
		var selectedValue = selectBox.options[selectBox.selectedIndex].value;
		if (selectedValue == "5"){
			$("#attention_other").prop('required',true);
		}else {
			document.getElementById("attention_other").value = "";
			$("#attention_other").prop('required',false);
		}
	} 
	   
	   
	function show_email() {
		
		$('#phone').val("");
		
		$('#div_email').show();
		$('#div_phone').hide();
		$('#div_password').show();
		$('#div_password_again').show();
		
		$("#email").prop('required',true);
		$("#phone").prop('required',false);
	}

	function show_phone() {
		
		$('#email').val("");
		
		$('#div_email').hide();
		$('#div_phone').show();
		$('#div_password').show();
		$('#div_password_again').show();
		
		$("#email").prop('required',false);
		$("#phone").prop('required',true);
		
	}
	
	
	
	   
		$(document).ready(function(){

			news_div();
			attention_div();
			
			
			
			$('#news').on('change', function() {
				news_div();
				//show news_other
				news_opt();
			});
			
			$('#attention').on('change', function() {
				attention_div();
				
				attention_opt();
			});
			
			$('#occupation').on('change', function() {
				office_div();
				
				office_opt();
			});
			
			
		});
		
		

</script>
</html>
