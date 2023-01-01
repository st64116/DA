<?php
if(isset($_SESSION['LOGIN'])){
    header("Location:index.php");
}

include_once('database/Client.php');
$db = new Client();

if (isset($_POST['submit'])) {

//    $_SESSION['ROLE'] = 1; //zatím jen na test, pak vymazat
//    $_SESSION['LOGIN'] = "z0001"; //zatím jen na test, pak vymazat

    $username = $_POST['username'];
    $password = $_POST['password'];

    if($db->check_login($username,$password)){
        $user = $db->view_zajemce($username);
        if($user){
            $_SESSION['LOGIN'] = $username;
            $_SESSION['ROLE'] = $user['OPRAVNENI'];
            $_SESSION['ADMIN'] = $user['OPRAVNENI'];
            header("Location:index.php");
            die();
        }else{
            $errorMsg = "chyba přihlášení";
        }
    }else{
        $errorMsg = "špatný login či heslo";
    }

//    header("Location:index.php"); //zatím jen na test, pak vymazat
}
?>
<div class="row mt-5">
    <div class="col-11 col-md-11 col-lg-8 col-xl-5 ms-auto me-auto">
        <div class="card formular text-light" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">

                <div class="mb-md-5 mt-md-4 pb-5">
                    <form action="" method="post">
                        <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                        <?php
                        if(isset($errorMsg)){
                            echo"<p class='text-white bg-danger p-2 rounded-3'> $errorMsg </p>";
                        }
                        ?>
                        <p class="text-white-50 mb-5">Zadej login a heslo!</p>

                        <div class="form-outline form-white mb-4 d-flex flex-column">
                            <label class="me-auto">*login:</label>
                            <input class="text-end" name="username" type="text" placeholder="jmeno" required=""/>
                            <label class="text-start">*password:</label>
                            <input class="text-end" type="password" name="password" placeholder="******" required=""/>
                        </div>

                        <div class="form-outline form-white mb-2">
                            <input type="submit" name="submit" class="btn btn-primary" value="Přihlásit">
                        </div>

                </div>
                </form>
                <div>
                    <p class="mb-0">Ještě nemáš účet? <a href="register.php" class="text-white-50 fw-bold">Sign
                            Up</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
<style>

    .formular {
        background-color: var(--color1);
    }

    /*.content{*/
    /*    !*background-image: url("assets/img/bg-1.webp");*!*/
    /*    backdrop-filter: blur(20px);*/
    /*}*/
    /*.blur{*/
    /*    backdrop-filter: blur(20px);*/
    /*    width: 100%;*/
    /*    height: 100%;*/
    /*}*/
</style>