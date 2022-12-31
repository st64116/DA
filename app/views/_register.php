<?php
include_once('database/Client.php');
$db = new Client();
if (isset($_POST['submit'])) {
    $jmeno = $_POST['name'];
    $prijmeni = $_POST["surname"];
    $email = $_POST['email'];
    $login = $_POST['username'];
    $password = $_POST['password'];
    $passwordAgain = $_POST['passwordAgain'];
    if ($password == $passwordAgain) {
        $db->insert_osobu($login,$email,$password,$jmeno,$prijmeni);
        if($db->insert_osobu($login,$email,$password,$jmeno,$prijmeni)){
            header("Location:login.php");
        }else{
            $errorMsg = "něco se nepovedlo!";
        }
    }else{
        echo "<script>console.log('hesla');</script>";
        $errorMsg = "Hesla musí být stejná!";
    }
}else{
    echo "<script>console.log('nvm');</script>";
}
?>
<div class="row mt-5">
    <div class="col-11 col-md-11 col-lg-8 col-xl-5 ms-auto me-auto">
        <div class="card formular text-light" style="border-radius: 1rem;">
            <div class="card-body p-5 text-center">

                <div class="mb-md-5 mt-md-4 pb-5">

                        <h2 class="fw-bold mb-2 text-uppercase">Registrace</h2>
                        <?php
                            if(isset($errorMsg)){
                                echo"<p class='text-white bg-danger p-2 rounded-3'> $errorMsg </p>";
                            }
                        ?>
                    <form action="" method="post">
                        <div class="form-outline form-white mb-4 d-flex flex-column">
                            <label class="me-auto">*jmeno:</label>
                            <input class="text-end" id="name" name="name" type="text" placeholder="jmeno" required/>
                            <label class="me-auto">*prijmeni:</label>
                            <input class="text-end" name="surname" type="text" placeholder="přijmeni" required/>
                            <label class="me-auto">*login:</label>
                            <input class="text-end" name="username" type="text" placeholder="login" required/>
                            <label class="me-auto">*emai:</label>
                            <input class="text-end" name="email" type="email" placeholder="jmeno@gmail.com"
                                   required/>
                            <label class="text-start">*heslo:</label>
                            <input class="text-end" type="password" name="password" placeholder="******" required/>
                            <label class="text-start">*heslo znovu:</label>
                            <input class="text-end" type="password" name="passwordAgain" placeholder="******" required/>
                        </div>

                        <div class="form-outline form-white mb-2">
                            <button type="submit" name="submit" class="btn btn-primary" value="Zaregistrovat">Registrace</button>
                        </div>
                    </form>
                </div>
                <div>
                    <p class="mb-0">Už máš účet? <a href="login.php" class="text-white-50 fw-bold">Log In</a>
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
</style>