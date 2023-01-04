<?php
include_once('database/Client.php');
$db = new Client();

//error_reporting(E_ERROR | E_PARSE);
session_start();

if (isset($_POST['emulaceOn']) && $_SESSION['ADMIN'] = 1) {
    $_SESSION['ROLE'] = 0;
    header("Location:nastaveni.php");
}
if (isset($_POST['emulaceOff']) && $_SESSION['ADMIN'] = 1) {
    $_SESSION['ROLE'] = 1;
    header("Location:nastaveni.php");
}

?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link href="assets/css/mainLayout.css" rel="stylesheet"/>
    <link href="assets/css/tabulka.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title><?php echo $title; ?></title>
</head>
<body>

<div class="main-container d-flex position-relative">
    <div class="sidebar d-fixed" id="side-nav">
        <div class="header-box d-flex justify-content-between">
            <h2 class="text-center text-white text-uppercase mt-2 ms-2">Aplikace</h2>
            <button class="btn d-md-none text-white open-btn ">
                    <span class="material-symbols-outlined">
                        arrow_forward_ios
                    </span>
            </button>
            <button class="btn d-md-none text-white close-btn d-none">
                <span class="material-symbols-outlined">
                    arrow_back_ios
                </span>
            </button>
        </div>
        <ul class="list-unstyled text-uppercase">
            <a href="index.php" class="text-decoration-none">
                <li class="sidebar-item text-center m-1 rounded-pill" id="home">
                    <span class="sidebar-text">home</span>
                    <span class="material-symbols-outlined text-end sidebar-icon d-none">home</span>
                </li>
            </a>

            <a href="mistnosti.php" class="text-decoration-none">
                <li class="sidebar-item text-center m-1 rounded-pill" id="mistnosti">
                    <span class="sidebar-text">Místnosti</span>
                    <span class="material-symbols-outlined text-end sidebar-icon d-none">apartment</span>
                </li>
            </a>
            <?php
            if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
                ?>
                <a href="firmy.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="firmy">
                        <span class="sidebar-text">Firmy</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">chair</span>
                    </li>
                </a>
                <a href="patra.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="patra">
                        <span class="sidebar-text">Patra</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">stairs</span>
                    </li>
                </a>
                <a href="prislusenstvi.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="prislusenstvi">
                        <span class="sidebar-text">Příslušenství</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">key</span>
                    </li>
                </a>

                <a href="stavy.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="stavy">
                        <span class="sidebar-text">Stavy</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">bolt</span>
                    </li>
                </a>
                <a href="ucely.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="ucely">
                        <span class="sidebar-text">Účely</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">lightbulb</span>
                    </li>
                </a>
                <a href="umisteni.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="umisteni">
                        <span class="sidebar-text">Umístění</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">pin_drop</span>
                    </li>
                </a>
                <a href="velikosti.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="velikosti">
                        <span class="sidebar-text">Velikosti</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">aspect_ratio</span>
                    </li>
                </a>
                <a href="fotky.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="fotky">
                        <span class="sidebar-text">Fotky</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">photo_camera</span>
                    </li>
                </a>
                <?php
            }
            if (!isset($_SESSION['ROLE'])) {
                ?>
                <a href="login.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="login">
                        <span class="sidebar-text">log in</span>
                        <span class="material-symbols-outlined sidebar-icon text-end d-none">person</span>
                    </li>
                </a>
                <?php
            } else {
                ?>
                <a href="osoby.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="osoby">
                        <span class="sidebar-text">Osoby</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">person</span>
                    </li>
                </a>
                <a href="rezervace.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="rezervace">
                        <span class="sidebar-text">Rezervace</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">calendar_month</span>
                    </li>
                </a>
                <a href="nastaveni.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="nastaveni">
                        <span class="sidebar-text">Nastavení</span>
                        <span class="material-symbols-outlined text-end sidebar-icon d-none">settings</span>
                    </li>
                </a>

                <a href="logout.php" class="text-decoration-none">
                    <li class="sidebar-item text-center m-1 rounded-pill" id="logout">
                        <span class="sidebar-text">log out</span>
                        <span class="material-symbols-outlined sidebar-icon text-end d-none">logout</span>
                    </li>
                </a>
                <?php
            }
            ?>
        </ul>
    </div>
    <div class="content">
        <div class="mx-2 mt-1 py-1 rounded-2 d-flex justify-content-between">
            <h2 class="ms-2"><?php echo $title ?></h2>
            <?php if (isset($_SESSION['ROLE'])) { ?>
                <a href="nastaveni.php"
                   class="me-1 text-decoration-none text-dark bg-white text-uppercase fw-bold rounded-pill px-2 shadow" >
                    <?php
                    $profilovka = $db->view_profilovky($_SESSION['LOGIN']);
                    if ($profilovka) {
                        echo '<img class="top-image" src="data:image/jpeg;base64,' . base64_encode($profilovka['OBSAH']->load()) . '"/>';
                    } else {
                        echo '<img class="top-image" src="assets/img/profilePhoto.png"/>';
                    }
                    $userData = $db->view_zajemce($_SESSION['LOGIN']);
                    echo "<span class='me-1'>" . $userData['JMENO'] . "</span>";
                    echo "<span>" . $userData['PRIJMENI'] . "</span>";
                    ?>
                </a>
            <?php } ?>
        </div>

        <div class="mx-2 mt-2 p-3 rounded-2">
            <?php include($childView); ?>
        </div>

        <!--        <div class="debug shadow p-4 bg-info rounded-3">-->
        <!--            --><?php
        //            echo "<p>session:</p>";
        //            var_dump($_SESSION)
        //            ?>
        <!--        </div>-->
    </div>
</div>

<?php
if (isset($script)) {
    echo "<script type='text/javascript' src='$script'></script>";
}
?>
<script src="extensions/mobile/bootstrap-table-mobile.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $('select').selectize({
            sortField: 'text'
        });
    });

    $(".sidebar ul li").on('click', function () {
        $(".sidebar ul li.active").removeClass('active');
        $(this).addClass('active');
        $('.sidebar').removeClass('active');
        if (window.innerWidth <= 767) {
            $('.open-btn').removeClass('d-none');
            $('.close-btn').addClass('d-none');
            $('.sidebar-icon').removeClass('d-none');
            $('.sidebar-item').addClass('text-end');
            $('.sidebar-item').removeClass('text-center');
            $('.sidebar-item').removeClass('m-1');
            $('.sidebar-item').removeClass('rounded-pill');
            $('.sidebar-text').addClass('d-none');
        }
    })

    $('.open-btn').on('click', function () {
        $('.open-btn').addClass('d-none');
        $('.close-btn').removeClass('d-none');
        $('.sidebar').addClass('active');
        $('.sidebar-icon').addClass('d-none');
        $('.sidebar-item').removeClass('text-end');
        $('.sidebar-item').addClass('text-center');
        $('.sidebar-item').addClass('m-1');
        $('.sidebar-item').addClass('rounded-pill');
        $('.sidebar-text').removeClass('d-none');

    })

    $('.close-btn').on('click', function () {
        $('.open-btn').removeClass('d-none');
        $('.close-btn').addClass('d-none');
        $('.sidebar').removeClass('active');
        $('.sidebar-icon').removeClass('d-none');
        $('.sidebar-item').addClass('text-end');
        $('.sidebar-item').removeClass('text-center');
        $('.sidebar-item').removeClass('m-1');
        $('.sidebar-item').removeClass('rounded-pill');
        $('.sidebar-text').addClass('d-none');
    })

    addEventListener("load", (event) => {
        nav();
        var url = location.href;
        var urlFilename = url.substring(url.lastIndexOf('/') + 1);
        var fileName = urlFilename.slice(0, -4);
        if (fileName == "index") {
            $(".sidebar ul li.active").removeClass('active');
            $("#home").addClass('active');
        } else {
            $(".sidebar ul li.active").removeClass('active');
            $("#" + fileName).addClass('active');
        }

    });
    addEventListener("resize", (event) => nav());

    nav = (event) => {
        if (window.innerWidth > 767) {
            $('.sidebar-text').removeClass('d-none');
            $('.sidebar-icon').addClass('d-none');
            $('.sidebar-item').removeClass('text-end');
            $('.sidebar-item').addClass('text-center');
            $('.sidebar-item').addClass('m-1');
            $('.sidebar-item').addClass('rounded-pill');
        } else {
            if ($('.sidebar').hasClass('active')) {
                $('.sidebar-icon').addClass('d-none');
                $('.sidebar-item').removeClass('text-end');
                $('.sidebar-item').addClass('text-center');
                $('.sidebar-item').addClass('m-1');
                $('.sidebar-item').addClass('rounded-pill');
                $('.sidebar-text').removeClass('d-none');
            } else {
                $('.sidebar-icon').removeClass('d-none');
                $('.sidebar-item').addClass('text-end');
                $('.sidebar-item').removeClass('text-center');
                $('.sidebar-item').removeClass('m-1');
                $('.sidebar-item').removeClass('rounded-pill');
                $('.sidebar-text').addClass('d-none');
            }
        }
    }

</script>

</body>
</html>