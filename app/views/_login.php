<?php
include_once('database/Client.php');
$db = new Client();

if (isset($_POST['submit'])) {

    $_SESSION['ROLE'] = 1; //zatím jen na test, pak vymazat

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    var_dump($username);
    var_dump($password);

    if($db->check_login($username,$password)){
        $sql = "SELECT OPRAVNENI FROM ZAJEMCI WHERE LOGIN=". $username;
        $opravneni = $db->custom_query($sql);
        if($opravneni != false){
            $_SESSION['LOGIN'] = $username;
            $_SESSION['ROLE'] = $opravneni;
            header("Location:index.php");
            die();
        }else{
            $errorMsg = "chyba";
        }
    }else{
        $errorMsg = "chyba";
    }

    header("Location:index.php"); //zatím jen na test, pak vymazat
}
?>
<div class="row mt-5">
    <div class="col-11 col-md-11 col-lg-8 col-xl-5 ms-auto me-auto">
        <div class="card formular text-light" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">

                <div class="mb-md-5 mt-md-4 pb-5">
                    <form action="" method="post">
                        <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
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