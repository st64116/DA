<?php
include_once('database/Client.php');
$db = new Client();

if (isset($_POST['submitAdd'])) {
    $login = $_POST['loginAdd'];
    $email = $_POST['emailAdd'];
    $jmeno = $_POST['jmenoAdd'];
    $heslo = $_POST['hesloAdd'];
//    if ($nadrizeny == "") {
//        $nadrizeny = 'NULL';
//    }
    if ($db->insert_firmu($login, $email, $heslo, $jmeno)) {
        $rezervaceMsg = "Firma úspěšně přidána :)";
    } else {
        $errorMsg = "Nastala chyba! Firma nebyla přidána!";
    }
}

if (isset($_POST['delete'])) {
    if ($db->delete_zajemce($_POST['osobaId'])) {
        $rezervaceMsg = "Firma úspěšně odstraněna :)";
    } else {
        $errorMsg = "Nastala chyba! Firma nebyla odstraněna!";
    }
}

if (isset($_POST['update'])) {
    $login = $_POST['loginUpdate'];
    $email = $_POST['emailUpdate'];
    $opravneni = $_POST['opravneniUpdate'];
    $jmeno = $_POST['jmenoUpdate'];
    if ($db->update_firmu($login, $email,$jmeno,$opravneni)) {
        $rezervaceMsg = "Firma úspěšně upravena :)";
    } else {
        $errorMsg = "Nastala chyba! Firma nebyla upravena!";
    }
}

if (isset($_POST['heslo'])) {
    var_dump($_POST['noveHeslo']);
    var_dump($_POST['login']);
    if ($db->update_heslo($_POST['login'], $_POST['noveHeslo'])) {
        $rezervaceMsg = "Heslo úspěšně změněno :)";
    } else {
        $errorMsg = "Nastala chyba! Heslo nebylo změněno!";
    }
}

$firmy = $db->view_firmy();

?>
<div class="text-start my-2 filter p-2">
    <?php
    if (isset($errorMsg)) {
        echo "<p class='text-white bg-danger p-2 my-2 rounded-3'> $errorMsg </p>";
    }
    if (isset($rezervaceMsg)) {
        echo "<p class='text-white bg-success p-2 my-2 rounded-3'> $rezervaceMsg </p>";
    }
    ?>
    <div class="d-flex justify-content-between">
        <button class="btn btn-primary text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter" aria-expanded="false" aria-controls="filter">Filtr
        </button>
        <?php
        if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
            echo '<button class="btn btn-success text-uppercase text-end" type="button" data-bs-toggle="collapse"
                data-bs-target="#add" aria-expanded="false" aria-controls="add">Přidat
        </button>';
        }
        ?>
    </div>
    <div class="collapse" id="filter">
        <form>
            <div class=" mt-3 px-2 py-3 p-sm-5 border border-dark rounded-3">
                <div class="row text-start">
                    <div class="col-6 col-lg-3 my-2">
                        <label>Název:</label>
                        <input type="text" name="nazev" id="nazev">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>Login:</label>
                        <input type="text" name="login" id="login">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>Email:</label>
                        <input type="email" name="email" id="email">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>Oprávnění:</label>
                        <select class="w-100" name="opravneni" id="opravneni">
                            <option value="nevybrano"></option>
                            <option value="0">uživatel</option>
                            <option value="1">admin</option>
                        </select>
                    </div>
                    <datalist id="browsers">

                    </datalist>

                </div>
                <div>

                </div>
                <div class="row">
                    <div class="mt-3 text-start col-6">
                        <button class="btn btn-lg btn-danger text-uppercase" onclick="search()">search
                        </button>
                    </div>
                    <div class="mt-3 text-end col-6">
                        <button class="btn btn-lg btn-danger text-uppercase" onclick="reset()">clear
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
    if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
        ?>
        <div class="collapse" id="add">
            <form action="" method="post">
                <label>Login:</label>
                <input class="w-100" type="text" name="loginAdd" required>
                <label>email:</label>
                <input class="w-100" type="email" name="emailAdd" required>
                <label>Název:</label>
                <input class="w-100" type="text" name="jmenoAdd" required>
                <label>Heslo:</label>
                <input class="w-100" type="text" name="hesloAdd" required>
                <button class="btn btn-danger mt-2" type="submit" name="submitAdd">Přidat</button>
            </form>
        </div>
    <?php } ?>
</div>
<div class="table-responsive shadow-lg mt-3" id="responsive-table">
    <table class="tabulka ms-auto me-auto text-center">
        <thead class="shadow">
        <tr class="text-uppercase">
            <?php
            if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
                echo "<th scope='col''>#</th>";
            }
            ?>
            <th scope="col">login</th>
            <th scope="col">email</th>
            <th scope="col">název</th>
            <th scope="col">oprávnění</th>
        </tr>
        </thead>
        <tbody>
        <?php

        //    //filter jmeno
        if (isset($_GET["nazev"]) && $_GET['nazev'] != "") {
            $nazev = $_GET["nazev"];
            echo "<script> document.getElementById('nazev').value ='" . $nazev . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($firmy as $firma) { // filtr mistnosti
                if (!is_bool(strpos(strtolower($firma["NAZEV"]), strtolower($nazev), 0))) {
                    array_push($pom, $firma);
                }
            }
            $firmy = $pom;
        }

            //filtr loginu
        if (isset($_GET["login"]) && $_GET["login"] != "") {
            $login = $_GET["login"];
            echo "<script> document.getElementById('login').value ='" . $login . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($firmy as $firma) {
                if (!is_bool(strpos(mb_strtolower($firma["LOGIN"]), mb_strtolower($login), 0))) {
                    array_push($pom, $firma);
                }
            }
            $firmy = $pom;
        }
        //
        //    //filtr emailu
        if (isset($_GET["email"]) && $_GET["email"] != "") {
            $email = $_GET["email"];
            echo "<script> document.getElementById('email').value ='" . $email . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($firmy as $firma) {
                if (!is_bool(strpos(strtolower($firma["EMAIL"]), strtolower($email), 0))) {
                    array_push($pom, $firma);
                }
            }
            $firmy = $pom;
        }
        //filtr opravneni
        if (isset($_GET["opravneni"]) && $_GET["opravneni"] != "nevybrano") {
            $opravneni = $_GET["opravneni"];
            echo "<script> document.getElementById('opravneni').value ='" . $opravneni . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($firmy as $firma) {
                if ($firma["OPRAVNENI"] == $opravneni) {
                    array_push($pom, $firma);
                }
            }
            $firmy = $pom;
        }

        //tvorba tabulky
        if (count($firmy) == 0) {
            echo "<tr><td colspan='10'>Nebyl nalezen žádný</td></tr>";
        } else {
            foreach ($firmy as $firma) {
                echo '<tr scope="row radek">';

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { //pokud je přihlášen admin -> možnost editace
                    echo '<td data-title="#" class="radek">
             <button class="btn btn-light text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#item' . $firma["LOGIN"] . '"  aria-expanded="false" aria-controls="item' . $firma["LOGIN"] . '"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
                }
                echo "<td class='radek' data-title='login'>" . "<span class='my-4'>" . $firma["LOGIN"] . "</span>" . "</td>";
                echo "<td class='radek' data-title='email'>" . $firma["EMAIL"] . "</td>";
                echo "<td class='radek' data-title='název'>" . $firma["NAZEV"] . "</td>";
                echo "<td class='radek' data-title='oprávnění'>" . $firma["OPRAVNENI"] . "</td>";
                echo "</tr>";

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
                    echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="item' . $firma["LOGIN"] . '">
<form action="" method="post" class="border border-1 rounded-3 p-2 mx-2 text-center">
<label>nové heslo(min 4 znaky): </label>
<input class="d-none" name="login" type="text" value="' . $firma['LOGIN'] . '" required readonly>
<input class="mx-2" name="noveHeslo" type="text" minlength="4" required>
<button type="submit" name="heslo" class="btn btn-danger btn-sm">Změnit Heslo</button>
</form>
<form class="w-100 px-2" action="" method="post">
<div class="row">
<div><label>login:</label><input name="loginUpdate" class="w-100" type="text" value="' . $firma["LOGIN"] . '" required readonly></div>
<div><label>email:</label><input name="emailUpdate" class="w-100" type="email" value="' . $firma["EMAIL"] . '" required></div>
<div><label>název:</label><input name="jmenoUpdate" class="w-100" type="text" value="' . $firma["NAZEV"] . '" required></div>
<div><label>opravnění:</label><select name="opravneniUpdate" class="w-100" type="text" id="opravneniUpdate' . $firma['LOGIN'] . '">
<option value="0">uživatel</option><option value="1">admin</option></select>
</div>
</div>
<button class="btn btn-danger" type="submit" name="update">Update</button>
</form>
<form class="px-2" action="" method="post">
<input class="d-none" type="text" name="osobaId" value="' . $firma["LOGIN"] . '">
<button class="btn btn-danger" type="submit" name="delete">Delete</button>
</form>
</div></td></tr>';
                    echo '<script>document.getElementById("opravneniUpdate' . $firma["LOGIN"] . '").value = ' . $firma['OPRAVNENI'] . '</script>'; // nastavení oprávnění na aktouální hodnotu
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>
