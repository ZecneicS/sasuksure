<!DOCTYPE html>
<html lang="th">

<head>
<title>ค้นหาข่าว</title>
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
        --color0:#47484d;
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


	
	
<!--div class="page-loader"><div class="wrapper"><div class="loader"></div></div></div-->


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
</style>
    
    
 
<section id="pageSite">
		 <div class="col-12 hr-sect ml-2 mt-4 ">
        	<h4 class="title">ค้นหาข่าว</h4>
    	 </div>
    
    	<div class="col-12">
         <div class="my-3 p-3 bg-white border rounded shadow-sm">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">คำค้นหา</label>
                                            
                                             <div class="col-sm-6">
                                             <input class="form-control"  type="text" value="<?php echo $search;?>" name = "search" placeholder="กรอกคำค้นหา"/>
                                             </div>
                                             
                                            <div class="col-sm-3">
                                            <button type="submit"  id="submit" name="submit" class="btn btn-primary"> ตกลง</button>
                                            </div>
                                            
                                             
                                             
                                        </div>
        </div>
        
         <div class="row typeNews col-md-12">
                                    

            <div class="col-md-4 mb-4 ">
                <div class="box-news">
                    <span data-type="like" data-post-id="7568c802-f5f1-4aae-bcbd-d5105542cd1b" class="btnLike ">
                        <i class="liked favme" type="button" data-feather="heart" id="like" name="clicks" value="1"></i>
                    </span>

                    <a id="link-news" href="https://sasuksure.anamai.moph.go.th/site/newsDetail/7568c802-f5f1-4aae-bcbd-d5105542cd1b">
                        <div class="tag-zone  before-background">
                                                        <img class="img-fluid high-light-image3 image6" src="https://sasuksure.anamai.moph.go.th/file/c8590e03-206b-4384-8685-bc8f666dfeec/preview">
                                                                                    <img class="img-fluid image3" src="assets/app/images/logo-truth.png" />
                                                    </div>
                        <div class="body-news">
                            <div class="row">
                                <div class="col-md-6">
                                                                        <button class="btn mt-3 btn-newsType" style="background-color: #C0C0C0;">
                                        อื่นๆ</button>
                                                                    </div>
                                <div class="col-md-6 text-right mt-3">
                                    <p class="date" id="date-right">08 กันยายน 2565</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="subNews">เทคนิคการเดินเพื่อเพิ่มความฟิต แค่หายใจเข้าออกลึกๆยาวๆ กำมือหลวมๆขณะแกว่งแขนข้างลำตัว ควบคู่กับการแขม่วหน้าท้องค้างไว้ นับ 1-...</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
                                    

            <div class="col-md-4 mb-4 ">
                <div class="box-news">
                    <span data-type="like" data-post-id="5deaecd3-e77d-4632-b00c-0a89d5897335" class="btnLike ">
                        <i class="liked favme" type="button" data-feather="heart" id="like" name="clicks" value="1"></i>
                    </span>

                    <a id="link-news" href="https://sasuksure.anamai.moph.go.th/site/newsDetail/5deaecd3-e77d-4632-b00c-0a89d5897335">
                        <div class="tag-zone  before-background">
                                                        <img class="img-fluid high-light-image3 image6" src="https://sasuksure.anamai.moph.go.th/file/a6dbb960-5e4e-4a0b-9db5-b0dee455f8df/preview">
                                                                                    <img class="img-fluid image3" src="assets/app/images/logo-truth.png" />
                                                    </div>
                        <div class="body-news">
                            <div class="row">
                                <div class="col-md-6">
                                                                        <button class="btn mt-3 btn-newsType" style="background-color: #FFBF00;">
                                        การรักษา</button>
                                                                    </div>
                                <div class="col-md-6 text-right mt-3">
                                    <p class="date" id="date-right">07 กันยายน 2565</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="subNews">อาการปวดเข่า บรรเทาได้ด้วยท่าเกร็งขาขึ้นกระดกปลายเท้าค้างไว้ 5 วินาที, เตะขาไปด้านข้างให้เฉียงไปด้านหลังเล็กน้อย, ท่า mini sq...</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
                                    

            <div class="col-md-4 mb-4 ">
                <div class="box-news">
                    <span data-type="like" data-post-id="b611388b-9828-45ff-864e-0bb9f88aca58" class="btnLike ">
                        <i class="liked favme" type="button" data-feather="heart" id="like" name="clicks" value="1"></i>
                    </span>

                    <a id="link-news" href="https://sasuksure.anamai.moph.go.th/site/newsDetail/b611388b-9828-45ff-864e-0bb9f88aca58">
                        <div class="tag-zone  before-background">
                                                        <img class="img-fluid high-light-image3 image6" src="https://sasuksure.anamai.moph.go.th/file/06c71aae-f0fb-4241-a4cf-027e6e6379e9/preview">
                                                                                    <img class="img-fluid image3" src="assets/app/images/logo-truth.png" />
                                                    </div>
                        <div class="body-news">
                            <div class="row">
                                <div class="col-md-6">
                                                                        <button class="btn mt-3 btn-newsType" style="background-color: #C0C0C0;">
                                        อื่นๆ</button>
                                                                    </div>
                                <div class="col-md-6 text-right mt-3">
                                    <p class="date" id="date-right">06 กันยายน 2565</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="subNews">มัดรวม 5 ความเชื่อผิดๆ เกี่ยวกับการออกกำลังกาย อาทิ งดดื่มน้ำฝึกความทนทาน, การเวททำให้มีกล้าม, การวิ่งทำให้น่องใหญ่, ยิ่งเจ็บ...</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
                                    

            <div class="col-md-4 mb-4 ">
                <div class="box-news">
                    <span data-type="like" data-post-id="20a15b46-383a-4236-82ab-b7a621995984" class="btnLike ">
                        <i class="liked favme" type="button" data-feather="heart" id="like" name="clicks" value="1"></i>
                    </span>

                    <a id="link-news" href="https://sasuksure.anamai.moph.go.th/site/newsDetail/20a15b46-383a-4236-82ab-b7a621995984">
                        <div class="tag-zone  before-background">
                                                        <img class="img-fluid high-light-image3 image6" src="https://sasuksure.anamai.moph.go.th/file/d5fcbce1-9fea-44b9-9a2c-fdda81782a4a/preview">
                                                                                    <img class="img-fluid image3" src="assets/app/images/logo-truth.png" />
                                                    </div>
                        <div class="body-news">
                            <div class="row">
                                <div class="col-md-6">
                                                                        <button class="btn mt-3 btn-newsType" style="background-color: #9AAA4E;">
                                        สิ่งแวดล้อม</button>
                                                                    </div>
                                <div class="col-md-6 text-right mt-3">
                                    <p class="date" id="date-right">05 กันยายน 2565</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="subNews">กระบะมักง่าย รับจ้างขนเศษวัสดุก่อสร้างมาทิ้งเต็ม 2 ข้างทาง อ้างว่าเห็นมีคนมาทิ้งก่อนแล้วเลยทิ้งตามเค้า ในพื้นที่ จ.ปทุมธานี</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
                                    

            <div class="col-md-4 mb-4 ">
                <div class="box-news">
                    <span data-type="like" data-post-id="345b7462-9128-4ebc-a101-644f1cdc40d2" class="btnLike ">
                        <i class="liked favme" type="button" data-feather="heart" id="like" name="clicks" value="1"></i>
                    </span>

                    <a id="link-news" href="https://sasuksure.anamai.moph.go.th/site/newsDetail/345b7462-9128-4ebc-a101-644f1cdc40d2">
                        <div class="tag-zone  before-background">
                                                        <img class="img-fluid high-light-image3 image6" src="https://sasuksure.anamai.moph.go.th/file/30022484-1301-4ea8-916b-6b422f9ebe49/preview">
                                                                                    <img class="img-fluid image3" src="assets/app/images/disin-big.png" />
                                                    </div>
                        <div class="body-news">
                            <div class="row">
                                <div class="col-md-6">
                                                                        <button class="btn mt-3 btn-newsType" style="background-color: #C0C0C0;">
                                        อื่นๆ</button>
                                                                    </div>
                                <div class="col-md-6 text-right mt-3">
                                    <p class="date" id="date-right">04 กันยายน 2565</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="subNews">หากก้มแตะปลายเท้าไม่ได้ เป็นสัญญาณความอ่อนแอของร่างกาย</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
                                    

            <div class="col-md-4 mb-4 ">
                <div class="box-news">
                    <span data-type="like" data-post-id="490270b7-fa84-4cfd-b1e6-0eacc9cdb8c1" class="btnLike ">
                        <i class="liked favme" type="button" data-feather="heart" id="like" name="clicks" value="1"></i>
                    </span>

                    <a id="link-news" href="https://sasuksure.anamai.moph.go.th/site/newsDetail/490270b7-fa84-4cfd-b1e6-0eacc9cdb8c1">
                        <div class="tag-zone  before-background">
                                                        <img class="img-fluid high-light-image3 image6" src="https://sasuksure.anamai.moph.go.th/file/40397ed9-1fe9-4163-bdeb-99533706a2f7/preview">
                                                                                    <img class="img-fluid image3" src="assets/app/images/logo-truth.png" />
                                                    </div>
                        <div class="body-news">
                            <div class="row">
                                <div class="col-md-6">
                                                                        <button class="btn mt-3 btn-newsType" style="background-color: #9AAA4E;">
                                        สิ่งแวดล้อม</button>
                                                                    </div>
                                <div class="col-md-6 text-right mt-3">
                                    <p class="date" id="date-right">04 กันยายน 2565</p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="subNews">ชาวบ้านเดือดร้อน กลิ่นเหม็นจากเตาเผาขยะติดเชื้อในพื้นที่ จ.สุพรรณบุรี</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
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
$('.form-vote').submit(function(e){
	e.preventDefault();
	var form = this;
	var formData = $(form).serialize();
	var vote_id = $('input[name=vote_id]', form).val();

	if($('input[name=choice_id]:checked', form).val() == undefined){
		toastr.error("กรุณาเลือกคำตอบ");
		return;
	}

	if(localStorage.getItem('voted_'+vote_id) !== null){
		toastr.warning("คุณได้ทำการโหวตแล้ว ไม่สามารถโหวตซ้ำได้ค่ะ");
		return;
	}else{
		localStorage.setItem('voted_'+vote_id, '1');
	}
	
	//post
	$.ajax({
		url : $(form).attr('action'),
		type : 'POST',
		data : formData,
		dataType : 'json',
		success : function(response){
			if(response.success){
				toastr.success(response.message);
			}else{
				toastr.warning(response.message);
			}
		},
		error : function( jqXHR ,textStatus, errorThrown ){
			toastr.error("กรุณาเลือกคำตอบ");
		}
	});
	
});

//Chart
var voteCharts = document.querySelectorAll('.vote-chart');
if(voteCharts){
	voteCharts.forEach(function(elm,i){
		var series = JSON.parse(elm.dataset.series);
		var labels = JSON.parse(elm.dataset.labels);
		var total = elm.dataset.total;
		var chartID = elm.getAttribute('id');
		var options = {
		        chart: {
		            type: 'donut'
		        },
		        series: series,
		        labels: labels,
		        legend: {
		            show: false
		        },
		        colors: ['#ab7ba3', '#4d4e52', '#b0c65a', '#67ccc8', '#1d9ab0','#466099','#86877f','#d89a2b','#c84557','#84469c','#2c9f76'],
		        plotOptions: {
		            pie: {
		                donut: {
		                    size: '65%',
		                    background: '#ffffff',
		                    labels: {
		                        show: true,
		                        name: {
		                            show: true,
		                            offsetY: -5
		                        },
		                        value: {
		                            show: true,
		                            fontFamily: 'DB Ozone X',
		                            fontWeight: 400,
		                            fontSize: '26px',
		                            color: '#5a5a5c',
		                            offsetY: 5
		                        },
		                        total: {
		                            show: true,
		                            showAlways: true,
		                            label: 'จำนวนผู้โหวต',
		                            fontSize: '30px',
		                            fontWeight: 400,
		                            fontFamily: 'DB Ozone X',
		                            color: '#5a5a5c',
		                            formatter: function(w){
		                                return total+' คน';
		                            }
		                        }
		                    }
		                }
		            }
		        }
		    }

		    var chart1 = new ApexCharts(document.querySelector('#'+chartID), options);
		    chart1.render();
	});
    
}

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
	

</body>

</html>