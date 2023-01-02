<?php
include_once('database/Client.php');
$db = new Client();

if (isset($_POST['submitAdd'])) {
    $login = htmlspecialchars($_POST['loginAdd']);
    $email = htmlspecialchars($_POST['emailAdd']);
    $jmeno = htmlspecialchars($_POST['jmenoAdd']);
    $prijmeni = htmlspecialchars($_POST['prijmeniAdd']);
    $nadrizeny = htmlspecialchars($_POST['nadrizenyAdd']);
    $heslo = htmlspecialchars($_POST['hesloAdd']);
//    if ($nadrizeny == "") {
//        $nadrizeny = 'NULL';
//    }
    if ($db->insert_osobu($login, $email, $heslo, $jmeno, $prijmeni, $nadrizeny)) {
        $rezervaceMsg = "Osoba úspěšně přidána :)";
    } else {
        $errorMsg = "Nastala chyba! Osoba nebyla přidána!";
    }
}

if (isset($_POST['delete'])) {
    if ($db->delete_osobu($_POST['osobaId'])) {
        $rezervaceMsg = "Osoba úspěšně odstraněna :)";
    } else {
        $errorMsg = "Nastala chyba! Osoba nebyla odstraněna!";
    }
}

if (isset($_POST['update'])) {
//function update_osobu(string $login, string $email, int $opravneni,
//                      string $jmeno, string $prijmeni) : bool
//{
    $login = htmlspecialchars($_POST['loginUpdate']);
    $email = htmlspecialchars($_POST['emailUpdate']);
    $opravneni = htmlspecialchars($_POST['opravneniUpdate']);
    $jmeno = htmlspecialchars($_POST['jmenoUpdate']);
    $prijmeni = htmlspecialchars($_POST['prijmeniUpdate']);
    $nadrizeny = htmlspecialchars($_POST['nadrizenyUpdate']);
    if (isset($_POST['detailUpdate'])) {
        $detail = 1;
    } else {
        $detail = 0;
    }
    if ($db->update_osobu($login, $email, $opravneni, $jmeno, $prijmeni, $detail, $nadrizeny)) {
        $rezervaceMsg = "Osoba úspěšně upravena :)";
    } else {
        $errorMsg = "Nastala chyba! Osoba nebyla upravena!";
    }
}

if (isset($_POST['heslo'])) {
    if ($db->update_heslo($_POST['login'], htmlspecialchars($_POST['noveHeslo']))) {
        $rezervaceMsg = "Heslo úspěšně změněno :)";
    } else {
        $errorMsg = "Nastala chyba! Heslo nebylo změněno!";
    }
}

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
                        <label>Jméno:</label>
                        <input type="text" name="jmeno" id="jmeno">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>Příjmení:</label>
                        <input type="text" name="prijmeni" id="prijmeni">
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
                <label>Jméno:</label>
                <input class="w-100" type="text" name="jmenoAdd" required>
                <label>Příjmení:</label>
                <input class="w-100" type="text" name="prijmeniAdd" required>
                <label>login nadřízeného:</label>
                <input class="w-100" type="text" name="nadrizenyAdd">
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
                echo "<th scope='col''>#</th>
                      <th scope='col'>login</th>";
            }
            ?>
            <th scope="col">email</th>
            <th scope="col">jméno</th>
            <th scope="col">příjmení</th>
            <th scope="col">oprávnění</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $osoby = $db->view_osoby();


        //TODO filtr se rozbil?
        //filter jmeno
        if (isset($_GET["jmeno"]) && $_GET['jmeno'] != "") {
            $jmeno = $_GET["jmeno"];
            echo "<script> document.getElementById('jmeno').value ='" . $jmeno . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($osoby as $osoba) { // filtr mistnosti
                if (!is_bool(strpos(strtolower($osoba["JMENO"]), strtolower($jmeno), 0))) {
                    array_push($pom, $osoba);
                }
            }
            $osoby = $pom;
        }
        //
        //    //filtr prijmeni
        if (isset($_GET["prijmeni"]) && $_GET["prijmeni"] != "") {
            $prijmeni = $_GET["prijmeni"];
            echo "<script> document.getElementById('prijmeni').value ='" . $prijmeni . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($osoby as $osoba) {
                if (!is_bool(strpos(strtolower($osoba["PRIJMENI"]), strtolower($prijmeni), 0))) {
                    array_push($pom, $osoba);
                }
            }
            $osoby = $pom;
        }
        //
        //    //filtr loginu
        if (isset($_GET["login"]) && $_GET["login"] != "") {
            $login = $_GET["login"];
            echo "<script> document.getElementById('login').value ='" . $login . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($osoby as $osoba) {
                if (!is_bool(strpos(strtolower($osoba["LOGIN"]), strtolower($login), 0))) {
                    array_push($pom, $osoba);
                }
            }
            $osoby = $pom;
        }
        //
        //    //filtr emailu
        if (isset($_GET["email"]) && $_GET["email"] != "") {
            $email = $_GET["email"];
            echo "<script> document.getElementById('email').value ='" . $email . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($osoby as $osoba) {
                if (!is_bool(strpos(strtolower($osoba["EMAIL"]), strtolower($email), 0))) {
                    array_push($pom, $osoba);
                }
            }
            $osoby = $pom;
        }
        //filtr opravneni
        if (isset($_GET["opravneni"]) && $_GET["opravneni"] != "nevybrano") {
            $opravneni = $_GET["opravneni"];
            echo "<script> document.getElementById('opravneni').value ='" . $opravneni . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($osoby as $osoba) {
                if ($osoba["OPRAVNENI"] == $opravneni) {
                    array_push($pom, $osoba);
                }
            }
            $osoby = $pom;
        }

        //tvorba tabulky
        if (count($osoby) == 0) {
            echo "<tr><td colspan='10'>Nebyl nalezen žádný</td></tr>";
        } else {
            foreach ($osoby as $osoba) {
                echo '<tr scope="row radek">';

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { //pokud je přihlášen admin -> možnost editace
                    echo '<td data-title="#" class="radek">
             <button class="btn btn-light text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#item' . $osoba["LOGIN"] . '"  aria-expanded="false" aria-controls="item' . $osoba["LOGIN"] . '"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
                    echo "<td class='radek' data-title='login'>" . "<span class='my-4'>" . $osoba["LOGIN"] . "</span>" . "</td>";
                }
                if($_SESSION['ROLE'] == 0 && $osoba['DETAIL'] == 1){
                        echo "<td class='radek' data-title='email'>***</td>";
                }else{
                    echo "<td class='radek' data-title='email'>" . $osoba["EMAIL"] . "</td>";

                }
                echo "<td class='radek' data-title='jméno'>" . $osoba["JMENO"] . "</td>";
                echo "<td class='radek' data-title='příjmení'>" . $osoba["PRIJMENI"] . "</td>";
                if($_SESSION['ROLE'] == 0 && $osoba['DETAIL'] == 1){
                    echo "<td class='radek' data-title='oprávnění'>***</td>";
                }else{
                    echo "<td class='radek' data-title='oprávnění'>" . $osoba["OPRAVNENI"] . "</td>";
                }
                echo "</tr>";

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
                    echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="item' . $osoba["LOGIN"] . '">
<form action="" method="post" class="border border-1 rounded-3 p-2 mx-2 text-center">
<label>nové heslo(min 4 znaky): </label>
<input class="d-none" name="login" type="text" value="' . $osoba['LOGIN'] . '" required readonly>
<input class="mx-2" name="noveHeslo" type="text" minlength="4" required>
<button type="submit" name="heslo" class="btn btn-danger btn-sm">Změnit Heslo</button>
</form>
<form class="w-100 px-2" action="" method="post">
<div class="row">
<div><label>login:</label><input name="loginUpdate" class="w-100" type="text" value="' . $osoba["LOGIN"] . '" required readonly></div>
<div><label>email:</label><input name="emailUpdate" class="w-100" type="email" value="' . $osoba["EMAIL"] . '" required></div>
<div><label>jméno:</label><input name="jmenoUpdate" class="w-100" type="text" value="' . $osoba["JMENO"] . '" required></div>
<div><label>příjmení:</label><input name="prijmeniUpdate" class="w-100" type="text" value="' . $osoba["PRIJMENI"] . '" required></div>
<div><label>nadřizeny:</label><input type="text" name="nadrizenyUpdate" class="w-100" value="' . $osoba['NADRIZENY'] . '"></div>
<div><label>opravnění:</label><select name="opravneniUpdate" class="w-100" type="text" id="opravneniUpdate' . $osoba['LOGIN'] . '">
<option value="0">uživatel</option><option value="1">admin</option></select></div>
<div><div class="form-check form-switch col-6 col-lg-4">';
                    if ($osoba['DETAIL'] == 0) {
                        echo '<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault' . $osoba["LOGIN"] . '" name="detailUpdate"  checked>';
                    } else {
                        echo '<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault' . $osoba["LOGIN"] . '" name="detailUpdate">';
                    }
echo '<label class="form-check-label" for="flexSwitchCheckDefault' . $osoba["LOGIN"] . '">veřejný profil</label>
</div></div>
<div><button type="submit" name="update" class="btn btn-danger text-start mt-2">update</button></div>
</div>
</form>
<form class="px-2" action="" method="post">
<input class="d-none" type="text" name="osobaId" value="' . $osoba["LOGIN"] . '">
<button class="btn btn-danger" type="submit" name="delete">Delete</button>
</form>
</div></td></tr>';
                    echo '<script>document.getElementById("opravneniUpdate' . $osoba["LOGIN"] . '").value = ' . $osoba['OPRAVNENI'] . '</script>'; // nastavení oprávnění na aktouální hodnotu
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>
