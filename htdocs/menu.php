<nav class="topnav">
    <div class="container">
        <div class="wrapper">
            <a href="index.php" class="logo">
                <img src="assets/app/images/logo.png" alt="Logo" />
                <div class="text">
                    <span class="ss-h1">กรมอนามัย</span>
                    <p>ส่งเสริมให้คนไทยสุขภาพดี</p>
                </div>
            </a>
            <div class="menu-container">
                <div class="top-menu">
                    <div class="option">
                        <p>ขนาดตัวอักษร</p>
                        <div class="btn font-size-btn" data-size="-1">-</div>
                        <div class="btn font-size-btn" data-size="0">ก</div>
                        <div class="btn font-size-btn" data-size="1">+</div>
                    </div>



                </div>

                <div class="bottom-menu" id="topnav-menu-container">
                    <div class="menu icon-menu active">
                        <a href="index.php" data-label="หน้าหลัก">
                            <div><i class="fas fa-home"></i></div>
                        </a>
                    </div>

                    

                    <!--div class="menu">
                            <a href="register.php" target="_self">
                                <div>หน้าหลัก</div>
                            </a>
                    </div-->

                    <div class="menu">
                            <a href="https://multimedia.anamai.moph.go.th/infographics/" target="_blank">
                                <div>ความรู้อนามัยมีเดีย</div>
                            </a>
                    </div>

                    <div class="menu">
                            <a href="insert_assign.php" target="_self">
                            <!--a href="https://forms.gle/jWBCDsy82Fj7ntxC6" target="_blank"-->
                                <div>แจ้งเบาะแสข่าว</div>
                            </a>
                    </div>

                    <div class="menu">
                            <a href="https://forms.gle/RZyM1Q1RKfSzYnXQA" target="_blank">
                                <div>ประเมินความพึงพอใจ</div>
                            </a>
                    </div>


                    <div class="menu has-children ">
                        <a data-mid="816" href="main.php">
                            <div>ตรวจสอบข่าวเฝ้าระวัง</div>
                        </a>

                        <div class="submenu-container ">
                            <div class="wrapper">
                              

                                <div class="submenus">

                                    <div class="submenu ">


                                        <div class="submenu ">
                                            <?php if (isset($_SESSION["user_sasuksure"]) && $_SESSION["user_sasuksure"] != "") { ?> <!--sciencez-->
                                                <a data-mid="836" href="https://lookerstudio.google.com/reporting/d7288d97-fb9a-4498-868b-c46b0f874db8" target="_blank">Dashboard</a>
                                            <?php } else { ?> 
                                            <?php } ?>
                                        </div>

                                        <div class="submenu ">
                                            <?php if (isset($_SESSION["user_sasuksure"]) && $_SESSION["user_sasuksure"] != "") { ?> <!--sciencez-->
                                                 <a data-mid="836" href="officer.php" target="_self">รายชื่อเจ้าหน้าที่</a>
                                            <?php } else { ?>
                                            <?php } ?>
                                        </div>

                                        <div class="submenu"> <!--sciencez18-->
                                            <?php
                                            if (!isset($_SESSION["user_sasuksure"]) || $_SESSION["user_sasuksure"] == "") { 
                                            ?>
                                                <a data-mid="836" href="login.php" target="_self">เข้าสู่ระบบ (สำหรับเจ้าหน้าที่)</a>
                                            <?php 
                                            } else { 
                                            ?>
                                                <a data-mid="836" href="profile.php" target="_self">
                                                    <?php 
                                                    // ถ้ามี user_fullname ให้แสดงชื่อ ถ้าไม่มีให้แสดงคำว่า 'โปรไฟล์' แทน
                                                    echo isset($_SESSION["user_fullname"]) ? htmlspecialchars($_SESSION["user_fullname"]) : 'โปรไฟล์'; 
                                                    ?>
                                                </a>
                                            <?php 
                                            } 
                                            ?>
                                        </div>



                                        <?php if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == "administrator") { ?> <!--sciencez-->
                                            <div class="submenu ">

                                                <a data-mid="836" href="profile.php?officer=new" target="_self">เพิ่มเจ้าหน้าที่</a>

                                            </div>
                                        <?php } ?>


                                        <div class="submenu ">
                                            <?php if (isset($_SESSION["user_sasuksure"]) && $_SESSION["user_sasuksure"] != "") { ?> <!--sciencez-->
                                                <a data-mid="836" href="logout.php" target="_self">ออกจากระบบ</a>
                                            <?php } ?>

                                        </div>
                                    </div>




                                </div>
                            </div>
                        </div>


                    </div>










                    <!--div class="menu icon-menu ">
        <a class="global-search-toggle" href="#">
            <div><i class="fas fa-search"></i></div>
        </a>
    </div-->
                </div>

                <div class="mobile-menu">
                    <div class="sidenav-btn">
                        <div class="hamburger">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="topnav-spacer"></div>

<!-- Sidenav -->
<nav class="sidenav">
    <div class="wrapper">
        <div class="sidenav-btn">
            <div class="hamburger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="options">
            <div class="option">
                <div class="icon">ก</div>
                <div class="dropdown">
                    <div class="btn font-size-btn" data-size="-1">-</div>
                    <div class="btn font-size-btn" data-size="0">ก</div>
                    <div class="btn font-size-btn" data-size="1">+</div>
                </div>
            </div>


            <div class="option">
                <!--a class="icon" href="https://anamai.moph.go.th/th/search">
                    <i class="fas fa-sitemap"></i>
                </a-->
            </div>

        </div>
        <div class="scroll-wrapper" data-simplebar>
            <div class="menu-container"></div>
        </div>
    </div>
</nav>
<div class="sidenav-filter"></div>