<?php
function news_type($status_code) {
  switch ($status_code) {
  case "1":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #96ac40;'>แอลกอฮอล์</button>";
    break;
  case "2":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #ecd150;'>ยาสูบ</button>";
    break;
  case "3":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #5b8bb1;'>โรค</button>";
    break;
  case "11":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #c0c0c0;'>อื่นๆ</button>";
    break;
  case "4":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #fe9a42;'>ครอบครัว</button>";
    break;
  case "5":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #d0505d;'>การรักษา</button>";
    break;
  case "6":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #f0c195;'>เกี่ยวกับจิตใจ</button>";
    break;
  case "7":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #0d838d;'>สิ่งแวดล้อม</button>";
    break;
  case "8":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #47484d;'>สินค้าสุขภาพ</button>";
    break;
  case "9":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #58c8bc;'>เป็นอุบัติเหตุฉุกเฉิน</button>";
    break;
  case "10":
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #80489C;'>อาหารการกิน</button>";
    break;
  
  default:
    $status = "<button class='btn mt-3 btn-newsType' style='background-color: #c0c0c0;'>อื่นๆ</button>";
	}
	return $status;
}
?>