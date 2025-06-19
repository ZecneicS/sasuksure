<?php
session_start();
require_once 'pdo-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_status_changes'])) {
    if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "administrator") {
        
        $all_user_ids_on_page = $_POST['user_ids'] ?? [];
        
        // ** แก้ไข **: เปลี่ยนไปใช้ 'us_status' จาก POST
        $active_user_ids = isset($_POST['us_status']) ? array_keys($_POST['us_status']) : [];

        $current_admin_id = $_SESSION['user_id']; // **สำคัญ**: ตรวจสอบให้แน่ใจว่ามีการตั้งค่า $_SESSION['user_id'] ตอน login

        try {
            $conn->beginTransaction();

            foreach ($all_user_ids_on_page as $user_id) {
                if ($user_id == $current_admin_id) {
                    continue; 
                }

                $new_status = in_array($user_id, $active_user_ids) ? 1 : 0;

                // ** แก้ไข **: อัปเดตคอลัมน์ 'us_status'
                $sql = "UPDATE users SET us_status = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$new_status, $user_id]);
            }

            $conn->commit();
            // อาจจะเพิ่มการแจ้งเตือนว่าบันทึกสำเร็จ แล้ว redirect
            // header("Location: ".$_SERVER['PHP_SELF']."?update=success");
            // exit();

        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Could not update user statuses: " . $e->getMessage());
            die("เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง");
        }
    }
}
// --- จบส่วนการประมวลผล ---

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

$datestart_post = $_POST["datestart"] ?? "";
$datestop_post = $_POST["datestop"] ?? "";
$sla = $_POST["sla"] ?? "";
$news_fact = $_POST["news_fact"] ?? "";
$status = $_POST["status"] ?? "";

$datestart = "";
$datestop = "";
$fillter = "";

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

// 2. ส่วนประมวลผล PHP และการดึงข้อมูล
$users_data = [];
try {
    // ** แก้ไข SQL Query **: เปลี่ยนไปดึงคอลัมน์ `us_status` แทน `status`
    $query = "SELECT id, first_name, department, position, date_created, us_status FROM users ORDER BY id ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $users_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล กรุณาลองใหม่อีกครั้ง");
}

?>
<!DOCTYPE html>
<html lang="th">
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
            <!-- ** เพิ่ม form tag สำหรับการส่งข้อมูล ** -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="col-12 hr-sect ml-2 mt-4">
                    <h4 class="title">รายชื่อเจ้าหน้าที่</h4>
                </div>
                <div id="div1" class="container-fluid mt-3">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ชื่อ-สกุล</th>
                                <th>หน่วยงาน</th>
                                <th>ตำแหน่ง</th>
                                <th>วันที่ลงทะเบียน</th>
                                <?php if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "administrator") : ?>
                                    <!-- ** เปลี่ยนหัวตาราง ** -->
                                    <th class="table-actions">สถานะ (Active)</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($users_data as $row) :
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo htmlspecialchars($row["first_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["department"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["position"]); ?></td>
                                    <td style='font-size:1rem'><?php echo htmlspecialchars($row["date_created"]); ?></td>

                                    <?php if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "administrator") : ?>
                                        <!-- ** ส่วนที่แก้ไข: เปลี่ยนจากลบเป็น Checkbox ** -->
                                        <td class="table-actions">
                                            <!-- เพิ่ม hidden input เพื่อส่ง ID ของผู้ใช้ทุกคน -->
                                            <input type="hidden" name="user_ids[]" value="<?php echo $row['id']; ?>">
                                            
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" 
                                                    name="us_status[<?php echo $row['id']; ?>]" 
                                                    id="us_status_<?php echo $row['id']; ?>"
                                                    <?php 
                                                        // ติ๊กถูกถ้า status เป็น 1 (Active)
                                                        if (isset($row['us_status']) && $row['us_status'] == 1) { echo 'checked'; } 
                                                    ?>
                                                    <?php 
                                                        // ปิดการใช้งาน checkbox ถ้าเป็น ID ของ admin เอง
                                                        if (isset($_SESSION['user_id']) && $row['id'] == $_SESSION['user_id']) { echo 'disabled'; }
                                                    ?>
                                                >
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php
                                $no++;
                            endforeach;
                            ?>
                        </tbody>
                    </table>

                    <?php if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "administrator") : ?>
                        <!-- ** เพิ่มปุ่มบันทึก ** -->
                        <div class="text-right mt-3">
                            <button type="submit" name="save_status_changes" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    <?php endif; ?>

                </div>
            </form>
        </section>

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

	<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

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
                        "order": ['desc']
                    }],
                "order": [
                    [0, 'desc']
                ],
                "pageLength": 50,
                "scrollX": true
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
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
</html>