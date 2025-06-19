<?php
session_start();
function secondsToTime($seconds)
{
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a วัน, %h ชม., %i นาที');
}

?>
<!DOCTYPE html>
<html lang="th">
<?php

function type_rq($status_code)
{
    switch ($status_code) {
        case "1":
            //$status = "<p style='color:#B6E2A1;'>ด่วน</p>";
            $status = "<button class='btn btn-newsType btn-sm' style='background-color: #B6E2A1;'>ด่วน</button>";
            break;
        case "2":
            //$status = "<p style='color:#FEBE8C;'>ด่วนมาก<p>";
            $status = "<button class='btn btn-newsType btn-sm' style='background-color: #FEBE8C;'>ด่วนมาก</button>";
            break;
        case "3":
            //$status = "<p style='color:#F7A4A4;'>ด่วนที่สุด</p>";
            $status = "<button class='btn btn-newsType btn-sm' style='background-color: #F7A4A4;'>ด่วนที่สุด</button>";
            break;
        default:
            $status =  "-";
    }
    return $status;
}

function type_response($status_code)
{
    switch ($status_code) {
        case "1":
            //$status = "<p style='color:#B6E2A1;'>ด่วน</p>";
            $status = "<button class='btn btn-newsType' style='background-color: #B6E2A1;'>ด่วน</button>";
            break;
        case "2":
            //$status = "<p style='color:#FEBE8C;'>ด่วนมาก<p>";
            $status = "<button class='btn btn-newsType' style='background-color: #FEBE8C;'>ด่วนมาก</button>";
            break;
        case "3":
            //$status = "<p style='color:#F7A4A4;'>ด่วนที่สุด</p>";
            $status = "<button class='btn btn-newsType' style='background-color: #F7A4A4;'>ด่วนที่สุด</button>";
            break;
        default:
            $status =  "-";
    }
    return $status;
}


function type_sender($status_code, $status_name)
{
    switch ($status_code) {
        case "1":
            $status = "ประชาชน";
            break;
        case "2":
            $status =  $status_name;
            break;
        default:
            $status =  "-";
    }
    return $status;
}

$datestart_post = $_POST["datestart"];
$datestop_post = $_POST["datestop"];
$sla = $_POST["sla"];
$news_fact = $_POST["news_fact"];
$status = $_POST["status"];




//echo "type=".$type;

if ($datestart_post != "") {

    $datestart = substr($datestart_post, 6, 4) . "-" . substr($datestart_post, 0, 2) . "-" . substr($datestart_post, 3, 2);
}

if ($datestop_post != "") {

    $datestop = substr($datestop_post, 6, 4) . "-" . substr($datestop_post, 0, 2) . "-" . substr($datestop_post, 3, 2);
}

if (($datestart != "") and ($datestop != "")) {

    $fillter .= "and (date(time_stamp) between '" . $datestart . "' and '" . $datestop . "' )";
} else if ($datestart != "") {

    $fillter .= "and (date(time_stamp) >= '" . $datestart . "')";
} else if ($datestop != "") {

    $fillter .= "and (date(time_stamp) <= '" . $datestop . "')";
}

if ($sla != "") {
    $fillter .= " and (rq = '" . $sla . "') ";
}


if ($news_fact != "") {

    if ($news_fact != "5") {
        $fillter .= " and (fact = '" . $news_fact . "') ";
    } else {
        $fillter .= " and (((fact is null) or (trim(fact) = '')) and (time_stamp_accept is null)) ";
    }
}


if ($status != "") {
    if ($status == "1") {
        $fillter .= " and ((answer = '3') or (answer = '4')) ";
    } else if ($status == "2") {
        $fillter .= " and ((answer = '1') or (answer = '2')) ";
    } else if ($status == "3") {
        $fillter .= " and ((answer is null) or (answer = '')) ";
    } else if ($status == "4") {
        $fillter .= " and (answer = '3') ";
    } else if ($status == "5") {
        $fillter .= " and (answer = '1') ";
    }
}


?>

<head>
    <title>ตรวจสอบข่าวเฝ้าระวัง | สาสุข ชัวร์</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta property="og:url" content="" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="ตรวจสอบข่าวเฝ้าระวัง | สาสุข ชัวร์" />
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
    <!--link defer async rel="stylesheet" type="text/css" href="assets/vendor/vanilla-datepicker/dist/css/datepicker.min.css" /-->
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

    <!-- data table -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- datepicker -->
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-datepicker/css/datepicker.css" />
    <!--link rel="stylesheet" type="text/css" href="assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-datetimepicker/css/datetimepicker.css" /-->

    <link href="lib_aa/bootstrap-4.5.2-dist/css/bootstrap.min.css" rel="stylesheet">


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

        .btn-3 {
            color: #343a40;
            background-color: #EDEDED;
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
    </style>



    <section id="pageSite">
        <div class="col-12 hr-sect ml-2 mt-4 ">
            <h4 class="title">รายชื่อเจ้าหน้าที่</h4>
        </div>


        <div id="div1" class="col-12">

            <!--form method="post" name="form1" id="form1" action="main.php">
                <div class="my-3 p-3 bg-white border rounded shadow-sm">
                    <div class="form-group row">
                        

                        <div class="col-sm-2">
                            <input class="form-control default-date-picker" type="text" value="<?php echo $datestart_post; ?>" name="datestart" placeholder="ตั้งแต่วันที่">
                        </div>

                        <div class="col-sm-2">
                            <input class="form-control default-date-picker" type="text" value="<?php echo $datestop_post; ?>" name="datestop" placeholder="ถึงวันที่">
                        </div>


                        <div class="col-sm-2">
                            <select name="sla" id="sla" class="form-control">
                                <option value="">SLA</option>
                                <option value="1" <?php if ($sla == "1") {
                                                        echo "selected";
                                                    } ?>>ด่วน</option>
                                <option value="2" <?php if ($sla == "2") {
                                                        echo "selected";
                                                    } ?>>ด่วนมาก</option>
                                <option value="2" <?php if ($sla == "3") {
                                                        echo "selected";
                                                    } ?>>ด่วนที่สุด</option>

                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="news_fact" id="news_fact" class="form-control">
                                <option value="">ประเภทข่าว</option>

                                <option value="1" <?php if ($news_fact == "1") {
                                                        echo "selected";
                                                    } ?>>ข่าวจริง</option>
                                <option value="2" <?php if ($news_fact == "2") {
                                                        echo "selected";
                                                    } ?>>ข่าวบิดเบือน</option>
                                <option value="3" <?php if ($news_fact == "3") {
                                                        echo "selected";
                                                    } ?>>ข่าวปลอม</option>
                                <option value="4" <?php if ($news_fact == "4") {
                                                        echo "selected";
                                                    } ?>>ไม่สามารถชี้แจ้งได้</option>
                                <option value="5" <?php if ($news_fact == "5") {
                                                        echo "selected";
                                                    } ?>>รอการตรวจสอบ</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="status" id="status" class="form-control">
                                <option value="">All Status</option>

                                <option value="1" <?php if ($status == "1") {
                                                        echo "selected";
                                                    } ?>>ดำเนินการแล้ว</option>
                                <option value="2" <?php if ($status == "2") {
                                                        echo "selected";
                                                    } ?>>ระหว่างดำเนินการ</option>
                                <option value="3" <?php if ($status == "3") {
                                                        echo "selected";
                                                    } ?>>รายการใหม่</option>
                                <option value="4" <?php if ($status == "4") {
                                                        echo "selected";
                                                    } ?>>ตอบกลับแล้ว</option>
                                <option value="5" <?php if ($status == "5") {
                                                        echo "selected";
                                                    } ?>>ขอข้อมูลเพิ่มเติม</option>
                                <option value="6" <?php if ($status == "6") {
                                                        echo "selected";
                                                    } ?>>ได้รับข้อมูลเพิ่มเติม</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <button type="submit" id="submit" name="submit" class="btn btn-primary" value="submit"> ตกลง</button>
                            <a href="main.php" role="button" class="btn btn-warning">ล้างค่า</a>
                        </div>
                    </div>
                </div>
            </form-->


            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อ-สกุล</th>
                        <th>อีเมล</th>
                        <th>โทรศัพท์</th>
                        <th>ตำแหน่ง</th>
                        <th>สังกัด</th>
                        <th>จังหวัด</th>
                        <th>เขต</th>
                        <th>แก้ไข/ลบ</th>
                        <td style='display:none'></td>

                    </tr>
                </thead>
                <tbody>


                    <?php

                    include("dbase.php");






                    $query = "select u.first_name,email,tel,department,position,name_th,moph";
                    $query .= " from users u left join provinces p on  u.province = p.code ";
                    
                    $query .= " order by p.moph,u.department asc;";
                    //$query .= " limit 100;";
                    //echo $query;
                    //echo "datestart=".$datestart;
                    //echo "datestop=".$datestop;
                    //echo $fillter;
                    $no = 1;
                    $result = mysqli_query($connect, $query);
                    $num_rows = mysqli_num_rows($result);
                    while ($rows = mysqli_fetch_array($result)) {

                        $status = ""; //สถานะการตอบกลับ
                        $hcode = $rows["hcode"];
                        echo "<tr>";
                        echo "<td align='center'>" .$no. "</td>";
                        echo "<td>&nbsp;&nbsp;" .$rows["first_name"]. "</td>";
                        echo "<td>&nbsp;&nbsp;" .$rows["email"]. "</td>";
                        echo "<td>&nbsp;&nbsp;" .$rows["tel"]. "</td>";
                        echo "<td>&nbsp;&nbsp;" .$rows["position"]. "</td>";
                        echo "<td>&nbsp;&nbsp;" .$rows["department"]. "</td>";
                        echo "<td>&nbsp;&nbsp;" .$rows["name_th"]. "</td>";
                        echo "<td>&nbsp;&nbsp;" .$rows["moph"]. "</td>";

                        if ($_SESSION["user_role"] == "administrator") {
                            echo "<td align='center'>&nbsp;&nbsp;<a href='profile.php?email=".$rows["email"]."'><span style='color:red'> <i class= 'fa fa-user'></i></a></span></td>";
                            }

                        echo "<td style='display:none'></td>";
                        echo "</tr>";

                        $no++;

                        //echo"<td>&nbsp;&nbsp;<a href='#' id='".$rows["hcode"]."' class='show_data' />".$rows["hname"]."</a></td>";






                    }



                    ?>





                </tbody>
            </table>












        </div>
    </section>


    <div id="add_data_Modal2" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เนื้อหา</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">




                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">ชื่อเรื่อง</label>
                        <div class="col-sm-9">
                            <input id="news_title1" name="news_title1" class="form-control" type="text" disabled>
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">ข้อมูลที่ต้องการแจ้งให้ตรวจสอบ</label>
                        <div class="col-sm-9">
                            <!--input id="content" name="content" class="form-control"   type="text"  disabled-->
                            <textarea class="form-control rounded-0" name="content1" id="content1" rows="3" placeholder="ข้อมูลที่ต้องการแจ้งให้ตรวจสอบ" value="" required disabled></textarea>

                        </div>

                    </div>


                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">ลิงก์ที่ส่งตรวจสอบ</label>
                        <div class="col-sm-9">
                            <input id="site1" name="site1" class="form-control" type="text" disabled>
                            <!--a id = 'site' href="" target="_blank"></a-->
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">ภาพประกอบ</label>
                        <div class="col-sm-9" id="div_image">
                            <img src="" name="image" id="image" height="100px">
                        </div>

                    </div>








                    <div class="modal-footer">
                        <a type="button" class="close" data-dismiss="modal">ปิด</a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="add_data_Modal" class="modal fade" tabindex="-1" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ตอบกลับ</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">


                        <form method="post" id="insert_form" enctype="multipart/form-data">


                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ชื่อเรื่อง</label>
                                <div class="col-sm-9">
                                    <input id="news_title" name="news_title" class="form-control" type="text">
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ข้อมูลที่ต้องการแจ้งให้ตรวจสอบ</label>
                                <div class="col-sm-9">
                                    <!--input id="content" name="content" class="form-control"   type="text"  disabled-->
                                    <textarea class="form-control rounded-0" name="content_request" id="content_request" rows="3" placeholder="ข้อมูลที่ต้องการแจ้งให้ตรวจสอบ" value="" required></textarea>

                                </div>

                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">รายละเอียดตอบกลับ (แสดงในหน้าหลัก)</label>
                                <div class="col-sm-9">
                                    <!--input id="content" name="content" class="form-control"   type="text"  disabled-->
                                    <textarea class="form-control rounded-0" name="content" id="content" rows="3" placeholder="รายละเอียดตอบกลับ" value="" required></textarea>

                                </div>

                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ลิงก์ข่าว</label>
                                <div class="col-sm-7">
                                    <input id="site" name="site" class="form-control" type="text">

                                </div>
                                <div class="col-sm-2 text-center" id="linkto"></div>

                            </div>




                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">รายละเอียดการตอบกลับ</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline col-sm-3 icheck-material-blue">
                                        <input class="form-check-input" type="radio" required name="answer" id="answer1" value="1">
                                        <label class="form-check-label" for="answer1">รับเรื่องประสานงาน</label>
                                    </div>
                                    <div class="form-check form-check-inline col-sm-3 icheck-material-blue">
                                        <input class="form-check-input" type="radio" required name="answer" id="answer2" value="2">
                                        <label class="form-check-label" for="answer2">ขอข้อมูลเพิ่มเติม</label>
                                    </div>
                                    <div class="form-check form-check-inline col-sm-2 icheck-material-blue">
                                        <input class="form-check-input" type="radio" required name="answer" id="answer3" value="3">
                                        <label class="form-check-label" for="answer3">ตอบกลับ</label>
                                    </div>
                                    <div class="form-check form-check-inline col-sm-2 icheck-material-blue">
                                        <input class="form-check-input" type="radio" required name="answer" id="answer4" value="4">
                                        <label class="form-check-label" for="answer4">ปฏิเสธ</label>
                                    </div>
                                </div>

                            </div>


                            <div id="div_answer1" style="display:none">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">กำหนดส่ง</label>
                                    <div class="col-sm-9">
                                        <input id="date_appointment" name="date_appointment" class="form-control" type="text">
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">หมายเหตุ</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control rounded-0" name="content_answer1" id="content_answer1" rows="3" placeholder="" value=""></textarea>
                                    </div>

                                </div>
                            </div>


                            <div id="div_answer2" style="display:none">

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">ขอข้อมูลเพิ่มเติม</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control rounded-0" name="content_answer2" id="content_answer2" rows="3" placeholder="" value=""></textarea>
                                    </div>

                                </div>
                            </div>


                            <div id="div_answer3" style="display:none">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">ผลการตรวจสอบ</label>
                                    <div class="col-sm-9">
                                        <button id="btn0_1" type="button" class="btn btn-3 mb-1" onclick="rq_0(1)">ข่าวจริง</button>&nbsp;&nbsp;
                                        <button id="btn0_2" type="button" class="btn btn-3 mb-1" onclick="rq_0(2)">ข่าวบิดเบือน</button>&nbsp;&nbsp;
                                        <button id="btn0_3" type="button" class="btn btn-3 mb-1" onclick="rq_0(3)">ข่าวปลอม</button>&nbsp;&nbsp;
                                        <button id="btn0_4" type="button" class="btn btn-3 mb-1" onclick="rq_0(4)">ไม่สามารถชี้แจงได้</button>

                                    </div>
                                    <div class="col-sm-3" style="display:none">

                                        <input type="text" class="form-control" id="fact" name="fact">
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">รายละเอียดข้อเท็จจริง</label>
                                    <div class="col-sm-9">
                                        <div class="form-check form-check-inline col-sm-3 icheck-material-blue">
                                            <input class="form-check-input" type="radio" name="publish" id="publish1" value="1">
                                            <label class="form-check-label" for="publish1">ประสงค์เผยแพร่</label>
                                        </div>
                                        <div class="form-check form-check-inline col-sm-3 icheck-material-blue">
                                            <input class="form-check-input" type="radio" name="publish" id="publish2" value="0">
                                            <label class="form-check-label" for="publish2">ไม่ประสงค์เผยแพร่</label>
                                        </div>

                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">หมายเหตุ</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control rounded-0" name="content_answer3" id="content_answer3" rows="3" placeholder="" value=""></textarea>
                                    </div>

                                </div>
                            </div>



                            <div id="div_answer4" style="display:none">

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">เหตุผลการปฏิเสธ</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control rounded-0" name="content_answer4" id="content_answer4" rows="3" placeholder="" value=""></textarea>
                                    </div>

                                </div>
                            </div>






                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ภาพประกอบ (แสดงในหน้าหลัก)</label>
                                <div class="col-sm-9" id="div_image1">
                                    <img src="" name="image1" id="image1" height="50px">
                                </div>

                            </div>



                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">แนบภาพประกอบใหม่ (ถ้ามี) </label>

                                <div class="col-sm-6">
                                    <div class="custom-file">
                                        <input type="file" accept=".doc, .docx, .xls, .xlsx, .pdf, .jpg, .png, .jpeg" class="custom-file-input" name="file_to_upload" id="file_to_upload">
                                        <label class="custom-file-label" for="file"></label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <div class="col-lg-9 text-center" id="statusMsg"></div>
                                </div>
                            </div>












                            <div class="row">
                                <input type="hidden" name="employee_id" id="employee_id" />
                                <label for="name" class="col-lg-3 col-sm-2 control-label"></label>
                                <div class="col-lg-9">
                                    <input type="submit" name="insert" id="insert" value="บันทึก" class="btn btn-success" />
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div id="del_data_Modal" class="modal fade" tabindex="-1" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ลบข้อมูล</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">


                        <form method="post" id="delete_form" enctype="multipart/form-data">


                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ชื่อเรื่อง</label>
                                <div class="col-sm-9">
                                    <input id="news_title_del" name="news_title_del" class="form-control" type="text">
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ข้อมูลที่ต้องการแจ้งให้ตรวจสอบ</label>
                                <div class="col-sm-9">
                                    <!--input id="content" name="content" class="form-control"   type="text"  disabled-->
                                    <textarea class="form-control rounded-0" name="content_request_del" id="content_request_del" rows="3" placeholder="ข้อมูลที่ต้องการแจ้งให้ตรวจสอบ" value="" required></textarea>

                                </div>

                            </div>

                           

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ลิงก์ข่าว</label>
                                <div class="col-sm-7">
                                    <input id="site_del" name="site_del" class="form-control" type="text">

                                </div>
                                <div class="col-sm-2 text-center" id="linkto"></div>

                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ภาพประกอบ (แสดงในหน้าหลัก)</label>
                                <div class="col-sm-9" id="div_image1_del">
                                    <img src="" name="image1_del" id="image1_del" height="50px">
                                </div>

                            </div>


                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">โปรดคลิกที่ยืนยันการลบข้อมูล</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline col-sm-3 icheck-material-blue">
                                        <input class="form-check-input" type="checkbox" required name="del_radio" id="del_radio" value="1">
                                        <label class="form-check-label" for="del_radio">ยืนยันการลบข้อมูล</label>
                                    </div>
                                   
                                </div>

                            </div>






                            <div class="row">
                                <input type="hidden" name="employee_id_del" id="employee_id_del" />
                                <label for="name" class="col-lg-3 col-sm-2 control-label"></label>
                                <div class="col-lg-9">
                                    <input type="submit" name="insert_del" id="insert_del" value="ลบข้อมูล" class="btn btn-danger" />
                                    <button type="button" class="btn btn-warning" data-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div id="show_assign" class="modal fade" tabindex="-1" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign To</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">


                        <form method="post" id="insert_form_assigned" enctype="multipart/form-data">


                            <div class="my-3 p-3 bg-white border rounded shadow-sm">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">เขต</label>
                                    <div class="col-sm-9">
                                        <select name="moph_id" id="moph" class="form-control" style="max-width: 500px;" required>
                                            <?php
                                            include('thailand/connect.php');
                                            $sql = "SELECT * FROM provinces group by moph order by cast(moph as signed) asc;";
                                            $query = mysqli_query($connect, $sql);
                                            ?>

                                            <option value="">เขต</option>
                                            <?php while ($result = mysqli_fetch_assoc($query)) :
                                                if ($result['moph'] == $moph) { ?>
                                                    <option value="<?= $result['moph'] ?>" selected>
                                                        เขต&nbsp;<?= $result['moph'] ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $result['moph'] ?>">เขต&nbsp;<?= $result['moph'] ?>
                                                    </option>
                                                <?php } ?>
                                            <?php endwhile;

                                            ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">จังหวัด</label>

                                    <div class="col-sm-9">
                                        <select name="province_id" id="province" class="form-control" style="max-width: 500px;">
                                            <option value="">จังหวัด</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">หน่วยงาน</label>

                                    <div class="col-sm-9">
                                        <select name="amphure_id" id="amphure" class="form-control" style="max-width: 500px;">
                                            <option value="">หน่วยงาน</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">รายชื่อ</label>

                                    <div class="col-sm-9">
                                        <select name="district_id" id="district" class="form-control" style="max-width: 500px;">
                                            <option value="">เจ้าหน้าที่</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <div class="col-lg-9 text-center" id="statusMsg_assign"></div>
                        </div>
                    </div>


                    <div class="row">
                        <input type="hidden" name="assign_id" id="assign_id" />
                        <label for="name" class="col-lg-3 col-sm-2 control-label"></label>
                        <div class="col-lg-9">
                            <input type="submit" name="insert_assign" id="insert_assign" value="Assign" class="btn btn-success" style="display:none" />
                            <button type="button" class="btn btn-warning" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>



    <div id="log_data_Modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">รายละเอียด</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">


                        <form method="get" id="insert_form2">


                            <table id="table_name">

                            </table>

                            <div class="row">
                                <input type="hidden" name="employee_id" id="employee_id" />
                                <label for="name" class="col-lg-3 col-sm-2 control-label"></label>
                                <div class="col-lg-9">

                                    <button type="button" class="btn btn-warning" data-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--div class="modal-footer">  
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div-->
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
    <!--script defer src="assets/vendor/vanilla-datepicker/dist/js/datepicker.min.js"></script-->
    <script defer src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js"></script>
    <script defer src="assets/app/js/script.js"></script>
    <script defer src="assets/app/js/lazy-load.js"></script>
    <script defer src="assets/app/js/contact-form.js"></script>
    <script defer src="assets/app/js/stats.js"></script>
    <script src="https://anamai.moph.go.th/assets/vendor/toastr-2.1.4/toastr.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- For inline Scripts -->
    <script src="assets/app/js/jquery-3.5.1.min.js"></script>
    <script defer src="assets/vendor/semantic-ui/components/transition.min.js"></script>
    <script defer src="assets/vendor/semantic-ui/components/dropdown.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>
    <script defer src="assets/app/js/script.js"></script>

    <!-- data table -->

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.15/dataRender/percentageBars.js"></script>
    <script>
        $(document).ready(function() {

            $(document).on('click', '.assigned', function() {
                var employee_id = $(this).attr("id");

                $('#assign_id').val(employee_id);

                $('#show_assign').modal('show');



            });



            $(document).on('click', '.show_data', function() {
                var employee_id = $(this).attr("id");




                $.ajax({

                    url: "fetch_detail.php",
                    method: "GET",
                    data: {
                        employee_id: employee_id
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#news_title1').val(data.news_title);
                        $('#content1').val(data.content_request);
                        $('#site1').val(data.site);
                        $('#div_image').html('<a href = "uploads/' + (data.image1) + '" target="_Blank"><img src="uploads/' + (data.image1) + '" height="60px"/></a>');

                        $('#add_data_Modal2').modal('show');
                    }
                });
            });


            <?php if ($_SESSION["user_sasuksure"] != "") { ?>

                $(document).on('click', '.edit_data', function() {

                    $('#insert_form')[0].reset();
                    $('#file_to_upload').val(null);
                    $('#statusMsg').html('<p></p>');
                    $('#insert').show();

                    var employee_id = $(this).attr("id");
                    $.ajax({
                        url: "fetch.php",
                        method: "GET",
                        data: {
                            employee_id: employee_id
                        },
                        dataType: "json",
                        success: function(data) {
                            $('#id').val(data.id);
                            $('#news_title').val(data.news_title);
                            $('#content_request').val(data.content_request);
                            $('#content').val(data.content);
                            $('#site').val(data.site);
                            $('#linkto').html('<a href="' + data.site + '" target ="_Blank"><i class="fa fa-link"> link</i></a>');

                            //$('#image1').val(data.image1);
                            $('#div_image1').html('<a href = "uploads/' + (data.image1) + '" target="_Blank"><img src="uploads/' + (data.image1) + '" height="60px"/></a>');

                            /*
					 if  ((($.trim(data.image1)) != "")) {
						 $('#image1').val("uploads/"+data.image1);
					 }else{
						 $('#image1').val("");
					 }
					 */
                            if ((($.trim(data.answer)) == "1")) {
                                $("#answer1").prop("checked", true);
                                $('#div_answer1').show();
                                $('#div_answer2').hide();
                                $('#div_answer3').hide();
                                $('#div_answer4').hide();

                            } else if ((($.trim(data.answer)) == "2")) {
                                $("#answer2").prop("checked", true);
                                $('#div_answer1').hide();
                                $('#div_answer2').show();
                                $('#div_answer3').hide();
                                $('#div_answer4').hide();
                            } else if ((($.trim(data.answer)) == "3")) {
                                $("#answer3").prop("checked", true);
                                $('#div_answer1').hide();
                                $('#div_answer2').hide();
                                $('#div_answer3').show();
                                $('#div_answer4').hide();
                            } else if ((($.trim(data.answer)) == "4")) {
                                $("#answer4").prop("checked", true);
                                $('#div_answer1').hide();
                                $('#div_answer2').hide();
                                $('#div_answer3').hide();
                                $('#div_answer4').show();
                            }

                            $('#date_appointment').val(data.date_appointment);
                            $('#content_answer1').val(data.content_answer1);

                            $('#content_answer2').val(data.content_answer2);

                            $('#fact').val(data.fact);



                            if ((($.trim(data.fact)) == "1")) {
                                $("#btn0_1").css("background-color", "#E6E5A3");
                                $("#btn0_2").css("background-color", "#e5f4f2");
                                $("#btn0_3").css("background-color", "#e5f4f2");
                                $("#btn0_4").css("background-color", "#e5f4f2");
                            } else if ((($.trim(data.fact)) == "2")) {
                                $("#btn0_1").css("background-color", "#e5f4f2");
                                $("#btn0_2").css("background-color", "#FD841F");
                                $("#btn0_3").css("background-color", "#e5f4f2");
                                $("#btn0_4").css("background-color", "#e5f4f2");
                            } else if ((($.trim(data.fact)) == "3")) {
                                $("#btn0_1").css("background-color", "#e5f4f2");
                                $("#btn0_2").css("background-color", "#e5f4f2");
                                $("#btn0_3").css("background-color", "#E97777");
                                $("#btn0_4").css("background-color", "#e5f4f2");
                            } else if ((($.trim(data.fact)) == "4")) {
                                $("#btn0_1").css("background-color", "#e5f4f2");
                                $("#btn0_2").css("background-color", "#e5f4f2");
                                $("#btn0_3").css("background-color", "#e5f4f2");
                                $("#btn0_4").css("background-color", "#EDEDED");
                            } else {

                                $("#btn0_1").css("background-color", "#e5f4f2");
                                $("#btn0_2").css("background-color", "#e5f4f2");
                                $("#btn0_3").css("background-color", "#e5f4f2");
                                $("#btn0_4").css("background-color", "#e5f4f2");

                            }





                            if ((($.trim(data.publish)) == "1")) {
                                $("#publish1").prop("checked", true);
                            } else if ((($.trim(data.publish)) == "0")) {
                                $("#publish2").prop("checked", true);
                            }


                            $('#content_answer3').val(data.content_answer3);

                            $('#content_answer4').val(data.content_answer4);




                            $('#employee_id').val(data.id);

                            $('#insert').val("บันทึก");
                            $('#add_data_Modal').modal('show');
                        }





                    });
                });


                $(document).on('click', '.del_data', function() {

                    //$('#insert_form')[0].reset();
                    //$('#file_to_upload').val(null);
                    //$('#statusMsg').html('<p></p>');
                    //$('#insert').show();

                    var employee_id = $(this).attr("id");
                    $.ajax({
                        url: "fetch.php",
                        method: "GET",
                        data: {
                            employee_id: employee_id
                        },
                        dataType: "json",
                        success: function(data) {
                            //$('#id').val(data.id);
                            $('#news_title_del').val(data.news_title);
                            $('#content_request_del').val(data.content_request);
                            $('#content_del').val(data.content);
                            $('#site_del').val(data.site);
                            // $('#linkto').html('<a href="' + data.site + '" target ="_Blank"><i class="fa fa-link"> link</i></a>');

                            //$('#image1').val(data.image1);
                            $('#div_image1_del').html('<a href = "uploads/' + (data.image1) + '" target="_Blank"><img src="uploads/' + (data.image1) + '" height="60px"/></a>');

                            /*
 if  ((($.trim(data.image1)) != "")) {
     $('#image1').val("uploads/"+data.image1);
 }else{
     $('#image1').val("");
 }
 */
                           



                           


                            $('#employee_id_del').val(data.id);

                            $('#delete').val("ลบข้อมูล");
                            $('#del_data_Modal').modal('show');
                        }





                    });
                });



                $(document).on('click', '.show_log', function() {

                    //('#insert_form')[0].reset();  

                    /* 
		  type: "POST",
                url: "get_hcode.php",
				data:{hcode:hcode},
     			success: function (data) {
					*/

                    var employee_id = $(this).attr("id");
                    $.ajax({
                        url: "fetch_log.php",
                        method: "GET",
                        data: {
                            employee_id: employee_id
                        },
                        //dataType:"json",  
                        success: function(data) {
                            $('#table_name').html(data)

                            $('#log_data_Modal').modal('show');
                        }



                    });
                });

                $('#insert_form').on("submit", function(event) {
                    event.preventDefault();

                    $.ajax({
                        url: "insert.php",
                        /*
					 method:"POST",  
                     data:$('#insert_form').serialize(),  
                     beforeSend:function(){  
                          $('#insert').val("Inserting");  
                     },  
					 */
                        type: 'POST',
                        data: new FormData(this),
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,
                        /*
					success:function(data){  
                          $('#insert_form')[0].reset();  
                          $('#add_data_Modal').modal('hide');  
                          $('#employee_table').html(data);  
                     }
					 */
                        success: function(response) { //console.log(response);

                            if (response.status == 1) {
                                //$('#insert_form')[0].reset();
                                $('#statusMsg').html('<p class="alert alert-success">' + response.message + '</p>');
                            } else {
                                $('#statusMsg').html('<p class="alert alert-danger">' + response.message + '</p>');
                            }
                            $('#insert_form').css("opacity", "");
                            $("#insert").hide();

                            location.reload();
                        }
                    });

                });

                $('#delete_form').on("submit", function(event) {
                    event.preventDefault();

                    $.ajax({
                        url: "remove.php",
                        /*
					 method:"POST",  
                     data:$('#insert_form').serialize(),  
                     beforeSend:function(){  
                          $('#insert').val("Inserting");  
                     },  
					 */
                        type: 'POST',
                        data: new FormData(this),
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,
                        /*
					success:function(data){  
                          $('#insert_form')[0].reset();  
                          $('#add_data_Modal').modal('hide');  
                          $('#employee_table').html(data);  
                     }
					 */
                        success: function(response) { //console.log(response);

                            if (response.status == 1) {
                                //$('#insert_form')[0].reset();
                                $('#statusMsg').html('<p class="alert alert-success">' + response.message + '</p>');
                            } else {
                                $('#statusMsg').html('<p class="alert alert-danger">' + response.message + '</p>');
                            }
                            //$('#insert_form').css("opacity", "");
                            $("#insert_del").hide();

                            location.reload();
                        }
                    });

                });





                $('#insert_form_assigned').on("submit", function(event) {
                    event.preventDefault();

                    $.ajax({
                        url: "insert_assign.php",
                        type: 'POST',
                        data: new FormData(this),
                        dataType: 'json',
                        contentType: false,
                        cache: false,
                        processData: false,

                        success: function(response) { //console.log(response);

                            if (response.status == 1) {
                                //$('#insert_form')[0].reset();
                                $('#statusMsg_assign').html('<p class="alert alert-success">' + response.message + '</p>');
                            } else {
                                $('#statusMsg_assign').html('<p class="alert alert-danger">' + response.message + '</p>');
                            }
                            $('#insert_form_assign').css("opacity", "");
                            $("#insert_assign").hide();


                            console.log(response.email);
                            //window.open("https://mail.google.com/mail/?view=cm&fs=1&to=" + response.email + "&su=แจ้งเตือนข่าว sasuksure&body=เรียน ผู้ที่เกี่ยวข้อง%0aขอความอนุเคราะห์หน่วยงานท่านดำเนินการตรวจสอบข้อเท็จจริงและส่งผลการตรวจสอบ ภายใน 24 ชั่วโมง นับจากที่ท่านได้รับการแจ้งหัวข้อข่าวที่เกี่ยวข้องกับหน่วยงานท่าน ผ่านอีเมล์นี้%0a%0aหมายเลขข่าวลำดับที่ " + response.id + "%0aหัวข้อข่าว " + response.news_title + "%0a%0aคลิกเพื่อตรวจสอบข่าวเสี่ยง%0ahttps://sasuksure.anamai.moph.go.th/main.php%0a%0aขอขอบคุณสำหรับความอนุเคราะห์จากท่านมา ณ โอกาสนี้ด้วย%0a%0aศูนย์เฝ้าระวังและตอบโต้ความเสี่ยงเพื่อความรอบรู้ด้านสุขภาพ (RRHL)%0aโทร. 02 590 4705");
                            window.open("https://swingdb.org/phpmailer/gmail1.php?p0=" + response.email + "&p2=" + response.id + "&p3=" + response.news_title + " ");
                            //console.log(response.email);
                            location.reload();
                        }


                    });

                });



                
                


            <?php } ?>


            $('#example').DataTable({


                <?php if ($_SESSION["user_sasuksure"] != "") { ?>
                    dom: 'Bfrtip',
                    buttons: [
                        'excel', 'print'
                    ],
                <?php } ?>

                "columnDefs": [{
                        "type": "num",
                        "targets": [0],
                        "order": ['asc']
                    }

                ],
                "order": [
                    [0, 'asc']
                ],
                "pageLength": 50,
                "scrollX": true
            });


            $('input:radio[name="answer"]').change(function() {

                if ($(this).val() == '1') {
                    $('#div_answer1').show();
                    $('#div_answer2').hide();
                    $('#div_answer3').hide();
                    $('#div_answer4').hide();
                    $("#content").prop('required', false);

                    $("#content_answer4").prop('required', false);

                } else if ($(this).val() == '2') {
                    $('#div_answer1').hide();
                    $('#div_answer2').show();
                    $('#div_answer3').hide();
                    $('#div_answer4').hide();
                    $("#content").prop('required', false);

                    $("#content_answer4").prop('required', false);
                } else if ($(this).val() == '3') {
                    $('#div_answer1').hide();
                    $('#div_answer2').hide();
                    $('#div_answer3').show();
                    $('#div_answer4').hide();
                    $("#content").prop('required', true);

                    $("#content_answer4").prop('required', false);
                } else if ($(this).val() == '4') {
                    $('#div_answer1').hide();
                    $('#div_answer2').hide();
                    $('#div_answer3').hide();
                    $('#div_answer4').show();
                    $("#content").prop('required', false);

                    $("#content_answer4").prop('required', true);


                }
            });

        });
    </script>
    <!-- datepicker -->

    <script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <!--script type="text/javascript" src="assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="assets/bootstrap-daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="assets/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript" src="assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
    <script type="text/javascript" src="assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script-->
    <script src="js/common-scripts.js"></script>
    <script src="js/advanced-form-components.js"></script>

</body>


<script src="lib_aa/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
<script>
    function rq_0(a) {
        var score = document.getElementById('fact').value = a;

        if (score == 3) {
            document.getElementById('fact').value = "3"

            document.getElementById('btn0_4').style.backgroundColor = "#e5f4f2";
            document.getElementById('btn0_3').style.backgroundColor = "#E97777";
            document.getElementById('btn0_2').style.backgroundColor = "#e5f4f2";
            document.getElementById('btn0_1').style.backgroundColor = "#e5f4f2";


        }

        if (score == 2) {
            document.getElementById('fact').value = "2"

            document.getElementById('btn0_4').style.backgroundColor = "#e5f4f2";
            document.getElementById('btn0_3').style.backgroundColor = "#e5f4f2";
            document.getElementById('btn0_2').style.backgroundColor = "#FD841F";
            document.getElementById('btn0_1').style.backgroundColor = "#e5f4f2";


        }

        if (score == 1) {
            document.getElementById('fact').value = "1"

            document.getElementById('btn0_4').style.backgroundColor = "#e5f4f2";
            document.getElementById('btn0_3').style.backgroundColor = "#e5f4f2";
            document.getElementById('btn0_2').style.backgroundColor = "#e5f4f2";
            document.getElementById('btn0_1').style.backgroundColor = "#E6E5A3";


        }

        if (score == 4) {
            document.getElementById('fact').value = "4"

            document.getElementById('btn0_4').style.backgroundColor = "#EDEDED";
            document.getElementById('btn0_3').style.backgroundColor = "#e5f4f2";
            document.getElementById('btn0_2').style.backgroundColor = "#e5f4f2";
            document.getElementById('btn0_1').style.backgroundColor = "#e5f4f2";


        }




    }
</script>

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


<script>
    $(function() {
        var mophObject = $('#moph');
        var provinceObject = $('#province');
        var amphureObject = $('#amphure');
        var districtObject = $('#district');

        // on change province
        mophObject.on('change', function() {
            var mophId = $(this).val();

            provinceObject.html('<option value="">เลือกจังหวัด</option>');
            //districtObject.html('<option value="">เลือกตำบล</option>');

            $.get('thailand/get_province.php?moph_id=' + mophId, function(data) {
                var result = JSON.parse(data);
                $.each(result, function(index, item) {
                    provinceObject.append(
                        $('<option></option>').val(item.code).html(item.name_th)
                    );
                });
            });
        });



        // on change province
        provinceObject.on('change', function() {
            var provinceId = $(this).val();

            amphureObject.html('<option value="">เลือกหน่วยงาน</option>');
            //districtObject.html('<option value="">เลือกตำบล</option>');

            $.get('thailand/get_department.php?province_id=' + provinceId, function(data) {
                var result = JSON.parse(data);
                $.each(result, function(index, item) {
                    amphureObject.append(
                        $('<option></option>').val(item.code).html(item.name_th)
                    );


                });





            });
        });



        // on change province
        amphureObject.on('change', function() {
            var provinceId = $(this).val();

            districtObject.html('<option value="">เลือกผู้รับผิดชอบ</option>');
            //districtObject.html('<option value="">เลือกตำบล</option>');

            $.get('thailand/get_officer.php?province_id=' + provinceId, function(data) {
                var result = JSON.parse(data);
                $.each(result, function(index, item) {
                    districtObject.append(
                        $('<option></option>').val(item.code).html(item.name_th)
                    );

                    $('#insert_assign').show();
                });





            });
        });


        //onload

        $.get('thailand/get_province.php?moph_id=<?php echo $moph; ?>', function(data) {
            var result = JSON.parse(data);
            $.each(result, function(index, item) {


                if (item.code == "<?php echo $province; ?>") {

                    provinceObject.append(
                        //if (item == 10){
                        $('<option selected></option>').val(item.code).html(item.name_th)
                        //}else{
                        //$('<option></option>').val(item.id).html(item.name_th)
                        //}
                    );

                } else {
                    //console.log(item.id)
                    provinceObject.append(
                        //if (item == 10){
                        $('<option></option>').val(item.code).html(item.name_th)
                        //}else{
                        //$('<option></option>').val(item.id).html(item.name_th)
                        //}
                    );
                }
            });
        });


        $.get('thailand/get_department.php?province_id=<?php echo $province; ?>', function(data) {
            var result = JSON.parse(data);
            $.each(result, function(index, item) {

                if (item.code == "<?php echo $amphur; ?>") {

                    amphureObject.append(
                        //if (item == 10){
                        $('<option selected></option>').val(item.code).html(item.name_th)
                        //}else{
                        //$('<option></option>').val(item.id).html(item.name_th)
                        //}
                    );


                } else {
                    //console.log(item.id)
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


    });
</script>


</html>