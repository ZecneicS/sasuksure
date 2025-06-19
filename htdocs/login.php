<!--?php
session_start();

if (!empty($_POST["submit"])) {
    include("pdo-connect.php");
    $email = trim($_POST["email"]);
    $password2 = trim($_POST["password"]);

    $show_data = $conn->prepare("select id,first_name,last_name,email,pin,role from users WHERE email = :param1 and pin = :param2 limit 1;");
    $show_data->bindParam(":param1", $email);
    $show_data->bindParam(":param2", $password2);
    try{
        //
        $show_data->execute();
        $num_rows = 0;
        $num_rows = $show_data->rowCount();
        }catch(PDOException $e){
                    echo $e->getMessage();
        }
    while ($rows = $show_data->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION["user_sasuksure"] = $rows["id"];
        $_SESSION["user_email"] = $rows["email"];
        $_SESSION["user_fullname"] = $rows["first_name"];
        $_SESSION["user_role"] = $rows["role"];
    }
    if ($num_rows > 0){
        echo "<meta http-equiv='refresh' content='0;URL=main.php'>";
        exit();
    }
}
?-->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบว่ามีการกดปุ่ม submit จากฟอร์มล็อกอินหรือไม่
if (!empty($_POST["submit"])) {
    include("pdo-connect.php");
    $email = trim($_POST["email"]);
    $password_input = trim($_POST["password"]);

    // ขั้นตอนที่ 1: ค้นหาผู้ใช้จากอีเมลเพียงอย่างเดียว
    $stmt = $conn->prepare("SELECT id, first_name, last_name, email, pin, password, role FROM users WHERE email = :email LIMIT 1;");
    $stmt->bindParam(":email", $email);
    
    try {
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        // กรณีฐานข้อมูลมีปัญหา
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
        $user = false; 
    }

    $login_success = false;
    $force_password_change = false;

    // ขั้นตอนที่ 2: ตรวจสอบรหัสผ่าน (ถ้าเจอผู้ใช้)
    if ($user) {
        // ลำดับที่ 1: ตรวจสอบรหัสผ่านแบบใหม่ (Hashed) ก่อน
        if (!empty($user["password"])) {
            if (password_verify($password_input, $user["password"])) {
                $login_success = true;
            }
        } 
        // ลำดับที่ 2: ถ้าไม่มีรหัสผ่านใหม่ ให้ตรวจสอบกับ PIN แบบเก่า
        else if (!empty($user["pin"]) && $user["pin"] == $password_input) {
            $login_success = true;
            $force_password_change = true; // ตั้งค่า Flag เพื่อบังคับเปลี่ยนรหัสผ่าน
        }
    }

    // ขั้นตอนที่ 3: จัดการผลลัพธ์การล็อกอิน
    if ($login_success) {
        // ตรวจสอบสถานะ 'deactive' ก่อนเสมอ
        if ($user["role"] === 'deactive') {
            session_unset();
            session_destroy();
            echo "<script>
                    alert('ผู้ใช้ถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ');
                    window.location.href = 'login.php'; // แก้ไข: ส่งกลับไปหน้า login
                  </script>";
            exit();
        }

        // ตั้งค่า Session พื้นฐาน
        $_SESSION["user_sasuksure"] = $user["id"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["user_fullname"] = $user["first_name"];

        // ตรวจสอบว่าต้องบังคับเปลี่ยนรหัสผ่านหรือไม่
        if ($force_password_change) {
            $_SESSION["force_password_change"] = true;
            $_SESSION["user_role"] = 'changepass'; // กำหนด Role ชั่วคราว
            // ส่งไปยังหน้าบังคับเปลี่ยนรหัสผ่าน
            echo "<meta http-equiv='refresh' content='0;URL=force-change-password.php'>";
            exit();
        } else {
            // ล็อกอินปกติ
            $_SESSION["user_role"] = $user["role"];
            unset($_SESSION["force_password_change"]); // เคลียร์ค่าทิ้ง
            // ส่งไปยังหน้าหลักของระบบ
            echo "<meta http-equiv='refresh' content='0;URL=main.php'>";
            exit();
        }

    } else {
        // กรณีล็อกอินไม่สำเร็จ (อีเมลหรือรหัสผ่านผิด)
        echo "<script>
                  alert('อีเมลหรือรหัสผ่านไม่ถูกต้อง');
                  window.history.back();
              </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <title>เข้าสู่ระบบ</title>
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
    </style>



    <section class="content-02" style="display:block">
        <div class="container">
            <div class="contents">
                <div class="content col-8" data-aos="fade-up" data-aos-delay="600">

                    <div class="slide-container" data-animate="slide">
                        <!--?php include("slider.php"); ?-->
                        <div class="dots"></div>
                    </div>
                </div>

                <div class="content" data-aos="fade-up" data-aos-delay="600">

                    <div id="" class="container  mb-4" style="max-width: 400px;">
                        <div class="row ">
                            <div id="menu1" class="jumbotron col p-4 mb-4 shadow bg-white rounded">
                                <div class="" id="login">
                                    <!--img src="images/logo_vegetables_400g-150x150.png" class="rounded mx-auto d-block"
                                    style="max-width: 130px;" alt="..."-->
                                    <br>
                                    <h4>เข้าสู่ระบบ</h4>
                                    <?php //echo "num_rows".$num_rows;?>
                                    <br>

                                    <form name="form_register" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" align="center">

                                        <div class="form-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                            </div>
                                            <input name="email" class="form-control" placeholder="อีเมลที่ลงทะเบียน" type="email" style="max-width: 500px;" required autofocus>
                                        </div>

                                        <div class="form-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                            </div>
                                            <input id="password" name="password" class="form-control" placeholder="รหัสผ่าน" type="password" style="max-width: 500px;" required>
                                        </div>

                                        <div class=" text-center">

                                            <input id="btn_login" type="submit" id="submit" name="submit" class="btn btn-primary shadow rounded-pill" value="&nbsp;&nbsp;&nbsp;เข้าสู่ระบบ&nbsp;&nbsp;&nbsp;" align="center" />
                                            &nbsp;&nbsp;&nbsp;


                                            <!--a id="forgetpass" href="register.php" class="btn btn-warning shadow rounded-pill" type="button">&nbsp;ลงทะเบียน</a-->
                                        </div>
                                    </form>

                                    <div class="mt-4">
                                        <div class="d-flex justify-content-center links">
                                            <a href="reset.php"><i class="fa fa-lock" aria-hidden="true"></i> ลืมรหัสผ่าน </a>
                                            <!--a href="#" class="ml-2" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-lock" aria-hidden="true"></i> ลืมรหัสผ่าน </a> &nbsp; | &nbsp; 
                                        <a href="#"><i class="fa fa-book" aria-hidden="true"></i> คู่มือการใช้งาน</a-->

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">แจ้งลืมรหัสผ่าน</h5>
                </div>
                <div class="modal-body">
                    <h3 style="color:#063" align="center">ตรวจสอบรหัสผ่านเบื้องต้นทางอีเมลที่ได้ลงทะเบียนไว้</h3>
                    <p style="color:red; font-size:18px;" align="center">หากไม่พบกรุณาตรวจสอบในกล่องจดหมายขยะ,Junk Email หรือ Spam mail</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">ตกลง</button>

                </div>
            </div>
        </div>
    </div>















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
    </script>


</body>

</html>