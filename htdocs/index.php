<?php
session_start();
$track_type = "index";

include("news_type.php");
include("format_date.php");
include("pdo-connect.php");

// --- 1. ปรับปรุงการจัดการ Input และการสร้าง Query ---

// กำหนดค่าเริ่มต้น
$keyword = '';
$news_type = '';

// สร้าง Query พื้นฐาน
$base_query = "SELECT id, news_title, news_type, concat(substr(news_title,1,250),'...') as content, image, time_stamp_accept, fact FROM news WHERE answer = '3' AND trim(fact) != '' AND publish = '1'";

$conditions = [];
$params = [];

// ตรวจสอบการค้นหา (Search)
if (!empty($_POST["search"])) {
    $keyword = trim($_POST["search"]);
    // เพิ่มเงื่อนไขแบบปลอดภัย (ใช้ named parameter :keyword)
    $conditions[] = "(news_title LIKE :keyword OR content LIKE :keyword)";
    $params[':keyword'] = '%' . $keyword . '%';
}

// ตรวจสอบการกรองตามหมวดหมู่ (Category Filter)
// วนลูปเช็ค submit1-11 ทีเดียว
for ($i = 1; $i <= 11; $i++) {
    if (isset($_POST["submit" . $i])) {
        $news_type = (string)$i;
        $conditions[] = "news_type = :news_type";
        $params[':news_type'] = $news_type;
        break; // เจอแล้วหยุดเลย
    }
}

// จัดการปุ่มยกเลิก
if (isset($_POST["submit12"])) {
    // ถ้ากดยกเลิก ก็ไม่ต้องทำอะไร ปล่อยให้ $keyword และ $news_type เป็นค่าว่าง
    // หน้าเว็บจะโหลดข้อมูลทั้งหมดใหม่
    $keyword = '';
    $news_type = '';
    // ล้าง conditions และ params ที่อาจถูกตั้งค่าไว้ก่อนหน้า
    $conditions = [];
    $params = [];
}

// รวมเงื่อนไขทั้งหมดเข้ากับ Query หลัก
$final_query = $base_query;
if (!empty($conditions)) {
    $final_query .= " AND " . implode(" AND ", $conditions);
}
$final_query .= " ORDER BY id DESC LIMIT 100";

// เตรียมและ Execute Query เพียงครั้งเดียว
$show_data = $conn->prepare($final_query);
$show_data->execute($params);

$num_rows = $show_data->rowCount();

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <title>สาสุข ชัวร์ | กรมอนามัย</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="public/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/app/css/style.css" rel="stylesheet" />
    <style>
        :root {
            --color0: #47484d;
            --color1: #c0ca67;
            --color2: #1d684a;
            --color3: #1d684a;
            --color4: #0d838d;
            --color5: #d7d8d0;
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

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DH3ELFZ1EY"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-DH3ELFZ1EY');
    </script>
</head>

<body onload="highlightActiveButton('<?php echo htmlspecialchars($news_type); ?>')">

    <?php include("menu.php"); ?>
    
    <section class="banner-01 img-only">
<h2 style="display: none;">Anamai</h2>
        <div class="slide-container" data-animate="slide">
            <div class="slides">
                <a href="#" target="_blank">
                    <div class="slide">
                        <img class="lazy-img" alt="Banner " src="assets/app/images/banner_site.jpg" data-src="assets/app/images/banner_site.jpg">
                    </div>
                    <div class="slide-sm">
                        <img alt="Banner " src="assets/app/images/banner_site.jpg" data-src="assets/app/images/banner_site.jpg">
                    </div>
                </a>
            </div>
        </div>
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
        <form role="form" id="form_register" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="Post">
            <div class="col-12 hr-sect ml-2 mt-4 " id="title">
                <h4 class="title">ค้นหาข่าว</h4>
            </div>

            <div class="col-12">
                <div class="my-3 p-3 bg-white border rounded shadow-sm">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">คำค้นหา</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" value="<?php echo htmlspecialchars($keyword); ?>" name="search" placeholder="กรอกคำค้นหาเช่น โควิด, มะเร็ง, วัคซีน" />
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" id="submit" name="submit" class="btn btn-primary">ค้นหา</button>
                            <button type="submit" id="submit12" name="submit12" class="btn btn-warning">ยกเลิก</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="my-3 p-3 bg-white border rounded shadow-sm">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">แสดงตามหมวด</label>
                        <div class="col-sm-10">
                            <button id="btn2_1" name="submit1" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">แอลกอฮอล์</button>
                            <button id="btn2_2" name="submit2" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">ยาสูบ</button>
                            <button id="btn2_3" name="submit3" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">โรค</button>
                            <button id="btn2_4" name="submit4" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">ครอบครัว</button>
                            <button id="btn2_5" name="submit5" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">การรักษา</button>
                            <button id="btn2_6" name="submit6" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">เกี่ยวกับจิตใจ</button>
                            <button id="btn2_7" name="submit7" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">สิ่งแวดล้อม</button>
                            <button id="btn2_8" name="submit8" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">สินค้าสุขภาพ</button>
                            <button id="btn2_9" name="submit9" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">เป็นอุบัติเหตุฉุกเฉิน</button>
                            <button id="btn2_10" name="submit10" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">อาหารการกิน</button>
                            <button id="btn2_11" name="submit11" type="submit" class="btn btn-4 mb-2 btn-sm btn-category">อื่นๆ</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="col-12 hr-sect ml-2 mt-4">
            <h4 class="title">ข่าวล่าสุด</h4>
        </div>

        <?php if ($num_rows == 0): ?>
            <div class="col-12 text-center">
                <h2>ไม่พบข้อมูล</h2>
            </div>
        <?php else: ?>
            <div class="col-12">
                <div class="row typeNews col-md-12">
                    <?php while ($rows = $show_data->fetch(PDO::FETCH_ASSOC)): ?>
                        <?php
                        $fact = $rows["fact"];
                        $image_logo = ""; // กำหนดค่าเริ่มต้น
                        if ($fact == "1") $image_logo = "truth-big.png";
                        else if ($fact == "2") $image_logo = "disin-big.png";
                        else if ($fact == "3") $image_logo = "fake-big.png";
                        else if ($fact == "5") $image_logo = "risk-big.png";
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="box-news">
                                <a id="link-news" href="content.php?news=<?php echo htmlspecialchars($rows["id"]); ?>" target="_blank">
                                    <div class="tag-zone before-background">
                                        <img class="img-fluid high-light-image3 image6" src="uploads/<?php echo htmlspecialchars($rows["image"]); ?>" alt="News Image">
                                        <?php if (!empty($image_logo)): ?>
                                            <img class="img-fluid image3" src="assets/app/images/<?php echo htmlspecialchars($image_logo); ?>" alt="Fact Check Status" />
                                        <?php endif; ?>
                                    </div>
                                    <div class="body-news">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo news_type(htmlspecialchars($rows["news_type"])); ?>
                                            </div>
                                            <div class="col-md-6 text-right mt-3">
                                                <p class="date" id="date-right"><?php echo DateThLong($rows["time_stamp_accept"]); ?> </p>
                                            </div>
                                            <div class="col-md-12">
                                                <h4><?php echo htmlspecialchars($rows["content"]); ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <script>
    function highlightActiveButton(activeType) {
        if (!activeType) {
            return; // ถ้าไม่มี type ก็ไม่ต้องทำอะไร
        }

        // ค้นหาปุ่ม category ทั้งหมด
        const buttons = document.querySelectorAll('.btn-category');
        
        // วนลูปเพื่อรีเซ็ตสีก่อน
        buttons.forEach(button => {
            button.style.backgroundColor = "#ededed";
            button.classList.remove('active');
        });

        // หาปุ่มที่ถูกเลือกและเปลี่ยนสี
        const activeButton = document.getElementById('btn2_' + activeType);
        if (activeButton) {
            activeButton.style.backgroundColor = "#138496";
            activeButton.classList.add('active');
        }
    }
    </script>
    
    <?php include("footer.php"); ?>

    <script src="assets/app/js/jquery-3.5.1.min.js"></script>
    <?php if ($news_type != ""): ?>
    <script type="text/javascript">
    $(document).ready(function () {
        $('html, body').animate({
            scrollTop: $('#title').offset().top
        }, 'slow');
    });
    </script>
    <?php endif; ?>

</body>
</html>