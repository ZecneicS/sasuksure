<!--?php
session_start();

$_SESSION["user_sasuksure"] = "";
$_SESSION["user_email"] = "";
$_SESSION["user_fullname"] = "";
$_SESSION["user_role"] = "";

unset($_SESSION["user_sasuksure"]);
unset($_SESSION["user_email"]);
unset($_SESSION["user_fullname"]);
unset($_SESSION["user_role"]);


echo "<meta http-equiv='refresh' content='0;URL=login.php'>";
? -->
<?php
session_start();

// ล้างค่า session ทั้งหมด (เป็นวิธีที่แนะนำ)
session_unset();

// ทำลาย session
session_destroy();

// แสดงผล JavaScript เพื่อแจ้งเตือนและ redirect
echo "
<script type='text/javascript'>
    alert('ออกจากระบบเรียบร้อยแล้ว');
    window.location.href = 'login.php';
</script>
";
?>