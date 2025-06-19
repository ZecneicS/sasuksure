<?php
session_start();

// 1. ตรวจสอบการล็อกอิน และ redirect หากยังไม่ล็อกอิน
if (empty(trim($_SESSION["user_sasuksure"]))) {
    header("Location: logout.php");
    exit; // จบการทำงานทันทีหลัง redirect
}

// 2. เรียกใช้ไฟล์เชื่อมต่อ PDO และจัดการข้อผิดพลาด
try {
    require_once 'pdo-connect.php';
} catch (PDOException $e) {
    // ใน production ควรบันทึก log แทนการแสดง error
    die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้: " . $e->getMessage());
}

// 3. กำหนดค่าเริ่มต้นให้ตัวแปรทั้งหมด เพื่อป้องกัน Error
$officer = $_GET["officer"] ?? null;
$oid = $_GET["oid"] ?? null;
$type = $_GET["type"] ?? null;

$email = $first_name = $department = $position = $tel = $province = "";
$modal_text = "";
$registersuccess = "0";
$heading = "ข้อมูลการลงทะเบียน"; // ค่าเริ่มต้น

// --- ส่วนประมวลผลฟอร์ม (POST Request) ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // --- ส่วนของการลบข้อมูล ---
    if (isset($_POST["del"]) && $_SESSION["user_role"] === "administrator") {
        $conn->beginTransaction();
        try {
            $oid_to_delete = $_POST["oid"];

            // 1. ดึงข้อมูลผู้ที่จะลบเพื่อนำไปเก็บ Log (ใช้ Prepared Statement)
            $stmt_select = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt_select->execute([$oid_to_delete]);
            $user_to_log = $stmt_select->fetch(PDO::FETCH_ASSOC);

            if ($user_to_log) {
                // 2. บันทึก Log การลบ (ไม่บันทึกรหัสผ่าน)
                $log_sql = "INSERT INTO users_log (sid, first_name, email, department, position, tel, province, pin, date_modified) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt_log = $conn->prepare($log_sql);
                $stmt_log->execute([
                    $_SESSION["user_sasuksure"],
                    $user_to_log['first_name'],
                    $user_to_log['email'],
                    $user_to_log['department'],
                    $user_to_log['position'],
                    $user_to_log['tel'],
                    $user_to_log['province'],
                    '***DELETED***' // ไม่บันทึกรหัสผ่านลง Log
                ]);

                // 3. ลบผู้ใช้ออกจากตาราง users
                $stmt_delete = $conn->prepare("DELETE FROM users WHERE id = ?");
                $stmt_delete->execute([$oid_to_delete]);
            }

            $conn->commit(); // ยืนยันการทำรายการทั้งหมด
            $registersuccess = "1";
            $modal_text = "ลบข้อมูลสำเร็จ";
        } catch (PDOException $e) {
            $conn->rollBack(); // ยกเลิกการทำรายการทั้งหมดหากเกิดข้อผิดพลาด
            $modal_text = "เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage();
            $registersuccess = "1"; // แสดง modal แจ้งเตือน
        }
    }

    // --- ส่วนของการบันทึก (แก้ไขข้อมูล) ---
    else if (isset($_POST["save"])) {
        try {
            $params = [
                $_POST["first_name"], $_POST["email"], $_POST["department"],
                $_POST["position"], $_POST["tel"], $_POST["province_id"]
            ];
            
            $update_sql = "UPDATE users SET first_name = ?, email = ?, department = ?, 
                            position = ?, tel = ?, province = ?, date_modified = NOW()";

            // หากมีการติ๊กแก้ไขรหัสผ่าน
            if (!empty($_POST["agree"]) && $_POST["agree"] == "1") {
                if (!empty($_POST["password"]) && strlen($_POST['password']) >= 8) {
                    // ทำการ hash รหัสผ่านใหม่
                    $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                    $update_sql .= ", password = ?";
                    $params[] = $hashed_password;
                }
            }
            
            $update_sql .= " WHERE id = ? LIMIT 1";
            $params[] = $_SESSION["user_sasuksure"];

            $stmt = $conn->prepare($update_sql);
            $stmt->execute($params);

            $registersuccess = "1";
            $modal_text = "บันทึกสำเร็จ";

        } catch (PDOException $e) {
            $modal_text = "เกิดข้อผิดพลาดในการบันทึก: " . $e->getMessage();
            $registersuccess = "1";
        }
    }

    // --- ส่วนของการเพิ่มผู้ใช้ใหม่ ---
    else if (isset($_POST["new"]) && $_SESSION["user_role"] === "administrator") {
        
        // ** เพิ่ม **: ตรวจสอบอีเมลซ้ำก่อน
        $email_to_check = $_POST["email"];
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt_check->execute([$email_to_check]);
        $email_exists = $stmt_check->fetchColumn();

        if ($email_exists > 0) {
            // ถ้าอีเมลซ้ำ, ตั้งค่าข้อความแจ้งเตือนและหยุดการทำงานส่วนนี้
            $registersuccess = "1";
            $modal_text = "อีเมลนี้มีผู้ใช้งานในระบบแล้ว กรุณาใช้อีเมลอื่น";
        } else {
            // ถ้าอีเมลไม่ซ้ำ, ดำเนินการเพิ่มผู้ใช้ต่อไป
            try {
                // Hash รหัสผ่านก่อนบันทึกเสมอ
                $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

                // ** แก้ไข **: เพิ่ม us_status ในคำสั่ง INSERT และกำหนดค่าเป็น 1
                $insert_sql = "INSERT INTO users (first_name, email, department, position, tel, province, pin, date_created, created_by, date_modified, us_status)
                               VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, NOW(), ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->execute([
                    $_POST["first_name"], $_POST["email"], $_POST["department"],
                    $_POST["position"], $_POST["tel"], $_POST["province_id"],
                    $hashed_password, $_SESSION["user_fullname"],
                    1 // กำหนด us_status เป็น 1 (Active)
                ]);

                $registersuccess = "1";
                $modal_text = "เพิ่มเจ้าหน้าที่สำเร็จ";

            } catch (PDOException $e) {
                // ตรวจจับ error ที่อาจเกิดจาก UNIQUE constraint ของอีเมล (เป็นการป้องกันอีกชั้น)
                if ($e->errorInfo[1] == 1062) {
                     $modal_text = "เกิดข้อผิดพลาด: อีเมลนี้มีผู้ใช้งานในระบบแล้ว";
                } else {
                     $modal_text = "เกิดข้อผิดพลาดในการเพิ่มเจ้าหน้าที่: " . $e->getMessage();
                }
                $registersuccess = "1";
            }
        }
    }
} 
// --- ส่วนดึงข้อมูลมาแสดงในฟอร์ม (GET Request) ---
else {
    $id_to_fetch = null;
    if ($type === 'del' && $_SESSION["user_role"] === 'administrator' && !empty($oid)) {
        $id_to_fetch = $oid;
        $heading = "ยืนยันการลบข้อมูลเจ้าหน้าที่";
    } else if ($officer === 'new' && $_SESSION["user_role"] === 'administrator') {
        $heading = "เพิ่มเจ้าหน้าที่";
    } else {
        // กรณีเป็นการแก้ไขข้อมูลส่วนตัว หรือ admin เข้ามาดู
        $id_to_fetch = ($_SESSION["user_role"] === 'administrator' && !empty($oid)) ? $oid : $_SESSION["user_sasuksure"];
        $heading = "ข้อมูลการลงทะเบียน";
    }

    if ($id_to_fetch) {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->execute([$id_to_fetch]);
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_data) {
                $email = $user_data["email"];
                $first_name = $user_data["first_name"];
                $department = $user_data["department"];
                $position = $user_data["position"];
                $tel = $user_data["tel"];
                $province = $user_data["province"];
            }
        } catch (PDOException $e) {
            $modal_text = "เกิดข้อผิดพลาดในการดึงข้อมูล: " . $e->getMessage();
            $registersuccess = "1";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <title>ข้อมูลการลงทะเบียน</title>
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
            

         <?php if ($officer == ""){
            $heading = "ข้อมูลการลงทะเบียน";
            
         }else{
            $heading = "เพิ่มเจ้าหน้าที่";

            
         }    
        
        ?>   
		 <div class="col-12 hr-sect ml-2 mt-4 ">
        	<h4 class="title"><?php echo $heading; ?></h4>
    	 </div>
         
         
         
         <div id="div_register" class="col-12">
                <form role="form" id="form_register" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="my-3 p-3 bg-white border rounded shadow-sm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">อีเมล (ใช้เป็น username เข้าระบบ)</label>
                            <div class="col-sm-4">
                                <input id="email" name="email" class="form-control" placeholder="อีเมล" type="email" required minlength="10" 
                                       value="<?php echo htmlspecialchars($email); ?>" <?php if ($type === "del") echo "readonly"; ?>>
                            </div>
                        </div>
                    </div>
                    <div class="my-3 p-3 bg-white border rounded shadow-sm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">ชื่อ-สกุล</label>
                            <div class="col-sm-4">
                                <input id="first_name" name="first_name" class="form-control" placeholder="ชื่อ-สกุล" type="text" required minlength="3" 
                                       value="<?php echo htmlspecialchars($first_name); ?>" <?php if ($type === "del") echo "readonly"; ?>>
                            </div>
                        </div>
                    </div>
                    <div class="my-3 p-3 bg-white border rounded shadow-sm">
                        <div class="form-group row">
                             <label class="col-sm-3 col-form-label">จังหวัด</label>
                             <div class="col-sm-4">
                                 <select name="province_id" id="province" required class="form-control" <?php if ($type === "del") echo "disabled"; ?>>
                                     <option value="">เลือกจังหวัด</option>
                                     <?php
                                     try {
                                         // ใช้ $conn ตัวเดียวกันในการ query จังหวัด
                                         $province_stmt = $conn->query("SELECT * FROM provinces ORDER BY name_th ASC");
                                         while($province_row = $province_stmt->fetch(PDO::FETCH_ASSOC)):
                                            // ใช้ htmlspecialchars ป้องกัน XSS
                                            $province_code = htmlspecialchars($province_row['code']);
                                            $province_name = htmlspecialchars($province_row['name_th']);
                                            $selected = ($province_code == $province) ? "selected" : "";
                                            echo "<option value=\"{$province_code}\" {$selected}>{$province_name}</option>";
                                         endwhile;
                                     } catch (PDOException $e) {
                                        echo "<option value=''>ไม่สามารถโหลดข้อมูลจังหวัดได้</option>";
                                     }
                                     ?>
                                 </select>
                             </div>
                        </div>
                    </div>

                    <div class="my-3 p-3 bg-white border rounded shadow-sm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">หน่วยงาน</label>
                            <div class="col-sm-6">
                                <input id="department" name="department" class="form-control" placeholder="หน่วยงาน" type="text" required
                                       value="<?php echo htmlspecialchars($department); ?>" <?php if ($type === "del") echo "readonly"; ?>>
                            </div>
                        </div>
                    </div>
                    <div class="my-3 p-3 bg-white border rounded shadow-sm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">ตำแหน่ง</label>
                            <div class="col-sm-6">
                                <input id="position" name="position" class="form-control" placeholder="ตำแหน่ง" type="text" required
                                       value="<?php echo htmlspecialchars($position); ?>" <?php if ($type === "del") echo "readonly"; ?>>
                            </div>
                        </div>
                    </div>
                    <div class="my-3 p-3 bg-white border rounded shadow-sm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">โทรศัพท์</label>
                            <div class="col-sm-4">
                                <input id="tel" name="tel" class="form-control" placeholder="โทรศัพท์" type="text" required
                                       value="<?php echo htmlspecialchars($tel); ?>" <?php if ($type === "del") echo "readonly"; ?>>
                            </div>
                        </div>
                    </div>

                    <?php if ($officer !== "new" && $type !== "del"): // แสดงเฉพาะหน้าแก้ไขข้อมูลส่วนตัว ?>
                    <div class="my-3 p-3 bg-white border rounded shadow-sm">
                         <div class="form-group row">
                            <label class="col-sm-3 col-form-label">เปลี่ยนรหัสผ่าน</label>
                            <div class="form-check-inline col-sm-6">
                                 <input class="form-check-input" type="checkbox" name="agree" id="agree" value="1">
                                 <label class="form-check-label" for="agree">ต้องการแก้ไขรหัสผ่าน</label>
                            </div>
                         </div>
                         <div class="form-group row" id="div_password" style="display:none;">
                             <label class="col-sm-3 col-form-label">รหัสผ่านใหม่</label>
                             <div class="col-sm-4">
                                 <input id="password" name="password" class="form-control" placeholder="กำหนดรหัสผ่าน ขั้นต่ำ 8 หลัก" type="password" minlength="8">
                             </div>
                         </div>
                    </div>
                    <?php elseif ($officer === "new"): // แสดงเฉพาะหน้าเพิ่มผู้ใช้ใหม่ ?>
                     <div class="my-3 p-3 bg-white border rounded shadow-sm">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">รหัสผ่านชั่วคราว</label>
                            <div class="col-sm-4">
                                <input id="password" name="password" class="form-control" placeholder="กำหนดรหัสผ่าน ขั้นต่ำ 8 หลัก" type="password" required minlength="8">
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group text-center">
                        <?php if ($type === "del"): ?>
                            <input type="hidden" name="oid" value="<?php echo htmlspecialchars($oid); ?>">
                            <button type="submit" id="del" name="del" class="btn btn-danger" style="width:200px" onclick="return confirm('คุณยืนยันที่จะลบข้อมูลนี้ใช่หรือไม่?');">ยืนยันการลบ</button>
                            <a class="btn btn-secondary" href="officer.php">ยกเลิก</a>
                        <?php elseif ($officer === "new"): ?>
                            <button type="submit" id="new" name="new" class="btn btn-primary" style="width:200px">เพิ่มเจ้าหน้าที่</button>
                        <?php else: ?>
                            <button type="submit" id="save" name="save" class="btn btn-primary" style="width:200px">บันทึก</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php if ($registersuccess === "1"): ?>
<div id="myModal" class="modal fade" tabindex="-1" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog shadow">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ผลการดำเนินการ</h5>
            </div>
            <div class="modal-body text-center">
                <h4 style="color:#063;"><?php echo htmlspecialchars($modal_text); ?></h4>
            </div>
            <div class="modal-footer">
                 <?php if (isset($_POST["del"])): ?>
                    <a type="button" class="btn btn-info" href="officer.php">ตกลง</a>
                 <?php else: ?>
                    <a type="button" class="btn btn-info" href="profile.php">ตกลง</a>
                 <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include("footer.php");?>

<script src="assets/app/js/jquery-3.5.1.min.js"></script>
<script src="lib_aa/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Show modal if needed
    <?php if ($registersuccess === "1"): ?>
    $("#myModal").modal("show");
    <?php endif; ?>

    // Toggle password field visibility
    $('#agree').change(function() {
        if ($(this).is(":checked")) {
            $('#div_password').show();
            $("#password").prop('required', true);
        } else {
            $('#div_password').hide();
            $("#password").prop('required', false);
            $("#password").val(''); // Clear password field when hiding
        }
    });
});
</script>

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
		
		document.getElementById('btn1_1').style.backgroundColor = "#138496";
		document.getElementById('btn1_2').style.backgroundColor = "#ededed";
		
		document.getElementById('div_sender').style.display = 'none';
		
		
		
    }
	
	if (score == 2) {
		document.getElementById('sender').value = "2"
		
		document.getElementById('btn1_1').style.backgroundColor = "#ededed";
		document.getElementById('btn1_2').style.backgroundColor = "#138496";
		
		document.getElementById('div_sender').style.display = 'block';
		
		
		
		
    }
	
	
	
   
}


</script>

<script type="text/javascript">
        $(document).ready(function() {
            <?php 
            if ($type == "del"){
            ?>
                document.getElementById("agree").disabled = true;

            <?php
            }
                
            ?>
        });
</script>






<script src="lib_aa/bootstrap-4.5.2-dist/js/bootstrap.min.js"></script>	

  <?php  if ($registersuccess == "1") { ?>
        <script type="text/javascript">
        $(document).ready(function() {
            $("#myModal").modal("show");
        });
        </script>

    <?php } ?>

</body>
</html>