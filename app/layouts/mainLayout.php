<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link href="assets/css/mainLayout.css" rel="stylesheet"/>
    <title><?php echo $title; ?></title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="main-container d-flex position-relative">
    <div class="sidebar" id="side-nav">
        <div class="header-box d-flex justify-content-between">
            <h2 class="text-center text-white text-uppercase mt-2 ms-2">Místnosti</h2>
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
                <li class="sidebar-item text-center active m-1 rounded-pill">
                    <span class="sidebar-text">home</span>
                    <i class="text-end fas fa-bars sidebar-icon"></i>
                </li>
            </a>

            <a href="#" class="text-decoration-none">
                <li class="sidebar-item text-center m-1 rounded-pill">
                    <span class="sidebar-text">místnosti</span>
                    <i class="text-end fas fa-bars sidebar-icon"></i>
                </li>
            </a>

            <a href="#" class="text-decoration-none">
                <li class="sidebar-item text-center m-1 rounded-pill">
                    <span class="sidebar-text">místnosti</span>
                    <i class="text-end fas fa-bars sidebar-icon"></i>
                </li>
            </a>
        </ul>
    </div>
    <div class="content">
<!--        <nav class="navbar navbar-expand-lg navbar-light bg-light">-->
<!--            <div class="container-fluid">-->
<!--                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">-->
<!--                    <span class="navbar-toggler-icon"></span>-->
<!--                </button>-->
<!--                <div class="collapse navbar-collapse" id="navbarNav">-->
<!--                    <ul class="navbar-nav ms-auto me-3 text-center">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link active" aria-current="page" href="login.php">login</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="logout.php">log out</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--            </div>-->
<!--        </nav>-->
        <div class="mx-2 mt-1 py-1 rounded-2 main-bg">
            <h2 class="ms-2"><?php echo $title ?></h2>
        </div>

        <div class="mx-2 mt-2 p-3 rounded-2 main-bg">
            <?php include($childView); ?>
        </div>


    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script>
    $(".sidebar ul li").on('click', function () {
        $(".sidebar ul li.active").removeClass('active');
        $(this).addClass('active');
        $('.sidebar').removeClass('active');
        if(window.innerWidth <= 767){
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

    addEventListener("load",(event)=>nav());
    addEventListener("resize", (event) => nav());

    nav = (event) =>{
        if(window.innerWidth > 767){
            $('.sidebar-text').removeClass('d-none');
            $('.sidebar-icon').addClass('d-none');
            $('.sidebar-item').removeClass('text-end');
            $('.sidebar-item').addClass('text-center');
            $('.sidebar-item').addClass('m-1');
            $('.sidebar-item').addClass('rounded-pill');
        }else{
            if($('.sidebar').hasClass('active')){
                $('.sidebar-icon').addClass('d-none');
                $('.sidebar-item').removeClass('text-end');
                $('.sidebar-item').addClass('text-center');
                $('.sidebar-item').addClass('m-1');
                $('.sidebar-item').addClass('rounded-pill');
                $('.sidebar-text').removeClass('d-none');
            }else{
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