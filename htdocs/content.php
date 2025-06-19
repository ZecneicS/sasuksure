<?php
session_start();
$track_type = "content";
include("news_type.php");

$id = $_GET["news"];
include("format_date.php");
include("pdo-connect.php");


/*
$query = "select news_title,news_type,content,image,time_stamp_accept,fact,user_id from news";
$query .= " where (trim(fact) != '' and publish = '1') and id = '$id' ";
$query .= " order by id desc limit 1;";
*/


$show_data = $conn->prepare("select news_title,news_type,content,image,time_stamp_accept,fact,user_id from news where (trim(fact) != '' and publish = '1') and id = :news_type_param order by id desc limit 1;");
$show_data->bindParam(":news_type_param", $id);

//$show_data->bindParam(":aid", $id);
$show_data->execute();
$no = 1;
$num_rows = $show_data->rowCount();

//$query .= " limit 100;";
//echo $query;
/*
$no = 1;
$result = mysqli_query($connect, $query);
$num_rows = mysqli_num_rows($result);
*/
while ($rows = $show_data->fetch(PDO::FETCH_ASSOC)) {
    $news_title = $rows["news_title"];
    $content = $rows["content"];
    $image = $rows["image"];
    $time_stamp_accept = $rows["time_stamp_accept"];
    $fact = $rows["fact"];
    $news_type = $rows["news_type"];
    $user_id = $rows["user_id"];
}

/*
$query = "select department from users";
$query .= " where  id = '$user_id' ";
$query .= " order by id desc limit 1;";
//$query .= " limit 100;";
//echo $query;
$no = 1;
$result = mysqli_query($connect, $query);
$num_rows = mysqli_num_rows($result);
*/

$show_data = $conn->prepare("select department from users where  id =:user_id order by id desc limit 1;");
$show_data->bindParam(":user_id", $user_id);
$show_data->execute();

while ($rows = $show_data->fetch(PDO::FETCH_ASSOC)) {
    $department = $rows["department"];
}



$news_visit = 0;

/*
$query = "select visit as total from news_visit_import where sid = '" . $id . "' group by sid ;";
//echo $query;
$result = mysqli_query($connect, $query);
$num_rows = mysqli_num_rows($result);
while ($rows = mysqli_fetch_array($result)) {
    $news_visit = $rows["total"];
}

$query = "select count(sid) as total from news_visit where news_id = '" . $id . "' group by news_id ;";
$result = mysqli_query($connect, $query);
$num_rows = mysqli_num_rows($result);
while ($rows = mysqli_fetch_array($result)) {
    $news_visit += $rows["total"];
}
*/

$show_data = $conn->prepare("select visit as total from news_visit_import where sid =:param group by sid ;");
$show_data->bindParam(":param", $id);
$show_data->execute();
while ($rows = $show_data->fetch(PDO::FETCH_ASSOC)) {
    $news_visit = $rows["total"];
}

$show_data = $conn->prepare("select count(sid) as total from news_visit where news_id = :param group by news_id ;");
$show_data->bindParam(":param", $id);
$show_data->execute();
while ($rows = $show_data->fetch(PDO::FETCH_ASSOC)) {
    $news_visit += $rows["total"];
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <title>สาสุข ชัวร์ | <?php echo $news_title; ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta property="og:url" content="" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="หน้าหลัก" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="assets/app/images/default/900-600.jpg" />

    <link rel="icon" type="image/x-icon" href="assets/app/images/favicon.ico" />
    <style>
        :root {
            --color0: #47484d;
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



    <script>
        const LANG = 'th';
    </script>


    <!-- Google tag (gtag.js) -->
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
    </style>



    <section id="pageSite">


        <div class="col-12 hr-sect ml-2 mt-4 ">
            <h4 class="title">รายละเอียดข่าว</h4>
        </div>

        <div class="col-12 ">


            <!--div class="col-md-12"-->






            <div class="col-md-12 mb-6 ">
                <div class="box-news">



                    <div class="row">

                        <div class="col-md-3">
                        </div>
                        <div class="col-md-6">



                            <div class="tag-zone  before-background">
                                <img class="img-fluid high-light-image6 image6" src="uploads/<?php echo $image; ?>">

                                <?php
                                if ($fact == "1") {
                                    $image_logo = "truth-big.png";
                                } else if ($fact == "2") {
                                    $image_logo = "disin-big.png";
                                } else if ($fact == "3") {
                                    $image_logo = "fake-big.png";
                                } else if ($fact == "5") {
                                    $image_logo = "risk-big.png";
                                }


                                ?>
                                <img class="img-fluid image3" src="assets/app/images/<?php echo $image_logo; ?>" />
                            </div>





                            <div class="col-md-12">
                                <h3><?php echo $news_title; ?> </h3>
                            </div>

                            <div class="body-news">
                                <div class="row">




                                    <div class="col-md-12">
                                        <h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $content; ?> </h4>
                                    </div>




                                    <div class="col-md-2 text-center">

                                    </div>

                                </div>


                            </div>
                            <div class="row">
                                <div class="col-12 hr-sect ml-2 mt-4 ">

                                </div>

                                <div class="col-sm-4">
                                    <p>
                                        <?php echo news_type($news_type); ?>
                                        <span class="badge badge-light">Views : <?php echo number_format($news_visit); ?></span>
                                    </p>

                                </div>
                                <div class="col-sm-3 text-right mt-3">
                                    <p class="date" id="date-right"><?php echo DateThLong($time_stamp_accept); ?></p>

                                </div>
                                <div class="col-sm-5 text-right mt-3">
                                    <p class="date" id="date-right"><?php echo "หน่วยงานที่ตอบกลับ : " . $department; ?>

                                    </p>

                                </div>
                                <div class="col-12 hr-sect ml-2 mt-4 ">

                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>





            <!--/div-->



        </div>
    </section>
    <section>
        <div class="col-12 hr-sect ml-2 mt-4 ">
            <h4 class="title"></h4>
        </div>
    </section>















    <?php include("footer.php"); ?>


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
    </script>


</body>

</html>