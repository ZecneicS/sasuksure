<?php
session_start();
//echo "session:".$_SESSION["user_sasuksure"];
if (trim($_SESSION["user_sasuksure"]) == ""){
   echo "<meta http-equiv='refresh' content='0;URL=logout.php?'>";
}

?>
<!DOCTYPE html>
<html lang="th">
<?php 
include("dbase.php");

if ($_GET["email"] != ""){
    $id = base64_decode($_GET["id"]);
    $email = base64_decode($_GET["email"]);
    $news_title = trim($_GET["news_title"]);
	$type = trim($_GET["type"]);

	// nvillage
	$query = "INSERT INTO mail_log (sid,type,assign_by,email,news_title,date_created)";
	$query.= "VALUES ('".$id."',";
    $query.= "'".$type."',";
    $query.= "'".$_SESSION["user_sasuksure"]."',";
    $query.= "'".$email."',";
	$query.= "'".$news_title."',";
	$query.= "now());";
	
	//echo $query;
	$result = mysqli_query($connect,$query) or die ("Error in query: $query " . mysqli_error($connect));
	
	mysqli_close($connect);	
	}			         
?>	

<head>
<title>การแจ้งเตือนทางอีเมล</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta property="og:url"           content="" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="หน้าหลัก" />
<meta property="og:description"   content="" />
<meta property="og:image"         content="assets/app/images/default/900-600.jpg" />

<link rel="icon" type="image/x-icon" href="assets/app/images/favicon.ico" />
<style>
	:root{
        --color0:#c0ca67;
        --color1:#c0ca67;
        --color2:#1d684a;
        --color3:#1d684a;
		--color4:#0d838d;
        --color5:#d7d8d0;
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
<link media="all" type="text/css" rel="stylesheet" href="public/css/siteMain.css" >

<link href="lib_aa/bootstrap-4.5.2-dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/home/css/home.css" rel="stylesheet" type="text/css" />					
	
	<script>
		const LANG = 'th';
	</script>
			<!-- Google Tag Manager -->
		<script>
			(function(w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({
					'gtm.start': new Date().getTime(),
					event: 'gtm.js'
				});
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src =
					'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', 'GTM-WV3LL9Z');
		</script>
		<!-- End Google Tag Manager -->
	</head>

<body data-clarity-unmask="true">
			<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WV3LL9Z" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
	      
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
	
.btn-1{color:#343a40;background-color:#e5f4f2;border-color:#e5f4f2;}
.btn-1:hover{color:#fff;background-color:#138496;border-color:#117a8b}
.btn-1.focus,.btn-1:focus{color:#fff;background-color:#138496;border-color:#117a8b;box-shadow:0 0 0 .2rem rgba(58,176,195,.5)}
.btn-1.disabled,.btn-1:disabled{color:#fff;background-color:#17a2b8;border-color:#17a2b8}
.btn-1:not(:disabled):not(.disabled).active,.btn-1:not(:disabled):not(.disabled):active,.show>.btn-1.dropdown-toggle{color:#fff;background-color:#117a8b;border-color:#10707f}
.btn-1:not(:disabled):not(.disabled).active:focus,.btn-1:not(:disabled):not(.disabled):active:focus,.show>.btn-1.dropdown-toggle:focus{box-shadow:0 0 0 .2rem rgba(58,176,195,.5)}

.btn-2{color:#343a40;background-color:#b2dfd8;border-color:#e5f4f2;}
.btn-2:hover{color:#fff;background-color:#138496;border-color:#117a8b}
.btn-2.focus,.btn-2:focus{color:#fff;background-color:#138496;border-color:#117a8b;box-shadow:0 0 0 .2rem rgba(58,176,195,.5)}
.btn-2.disabled,.btn-2:disabled{color:#fff;background-color:#17a2b8;border-color:#17a2b8}
.btn-2:not(:disabled):not(.disabled).active,.btn-2:not(:disabled):not(.disabled):active,.show>.btn-2.dropdown-toggle{color:#fff;background-color:#117a8b;border-color:#10707f}
.btn-2:not(:disabled):not(.disabled).active:focus,.btn-2:not(:disabled):not(.disabled):active:focus,.show>.btn-2.dropdown-toggle:focus{box-shadow:0 0 0 .2rem rgba(58,176,195,.5)}

.btn-3{color:#343a40;background-color:#ededed;border-color:#e5f4f2;font-size: 1.5rem;}
.btn-3:hover{color:#fff;background-color:#138496;border-color:#117a8b}
.btn-3.focus,.btn-3:focus{color:#fff;background-color:#138496;border-color:#117a8b;box-shadow:0 0 0 .2rem rgba(58,176,195,.5)}
.btn-3.disabled,.btn-3:disabled{color:#fff;background-color:#17a2b8;border-color:#17a2b8}
.btn-3:not(:disabled):not(.disabled).active,.btn-3:not(:disabled):not(.disabled):active,.show>.btn-3.dropdown-toggle{color:#fff;background-color:#117a8b;border-color:#10707f}
.btn-3:not(:disabled):not(.disabled).active:focus,.btn-3:not(:disabled):not(.disabled):active:focus,.show>.btn-3.dropdown-toggle:focus{box-shadow:0 0 0 .8rem rgba(58,176,195,.5)}

</style>
    
<section class="content-02" style="display:block">
                <div class="container">
            <div class="contents">

		 <div class="col-12 hr-sect ml-2 mt-4 ">
        	<h4 class="title">ผลการแจ้งเตือนทางอีเมล</h4>
    	 </div>

         <div id="div_register" class="col-12">
                <form  role="form"  id="form_register" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
                 <div class="my-3 p-3 bg-white border rounded shadow-sm">
                                        
                                        <?php if (($type == "1") or ($type == "")){ ?>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">ID</label>
                                            <div class="col-sm-9">
                                                 <p><?php echo $id; ?></p>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">หัวข้อข่าว</label>
                                            <div class="col-sm-9">
                                                 <p><?php echo $news_title; ?></p>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">ผลการส่งอีเมล</label>
                                            <div class="col-sm-9">
                                                 <p class="text-success">ระบบได้ส่งอีเมลแจ้งเตือนไปยัง <?php echo $email; ?></p>
                                            </div>
                                        </div>
                </div> 
                </form>     
         </div>       
         </div>
         </div>   
</section>
    	
<?php include("footer.php");?>

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
<script src="https://anamai.moph.go.th/assets/vendor/toastr-2.1.4/toastr.js" async defer ></script>					
<script src="https://cdn.jsdelivr.net/npm/apexcharts" ></script>			
	<!-- For inline Scripts -->
<script>  

var bannerIndex = $("#bannerIndex").val();
if(bannerIndex>6){
	$('.multiple-items').slick({
		infinite: true,
		slidesToShow: 6,
		slidesToScroll: 1,
		prevArrow:"",
		nextArrow:"",
		autoplay:true
		});
	}
</script>
<script src="lib_aa/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>	
</html>