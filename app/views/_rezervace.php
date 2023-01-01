<?php
include_once('database/Client.php');
$db = new Client();

if (isset($_POST['delete'])) {
    if ($db->delete_rezervaci($_POST['id'])) {
        $goodMsg = "Uspěšně odstraněno";
    } else {
        $errorMsg = "něco se nepovedlo :(";
    }
}

// TODO: admin -> přidání rezervace

// TODO: update rezervace

$viewStavy = $db->view_stavy();
$viewPrilusenstvi = $db->view_prislusenstvi();

if ($_SESSION['ROLE'] == 1) {
    $viewRezervace = $db->view_rezervace();
} else {
    $viewRezervace = $db->view_rezervaci($_SESSION['LOGIN']);
}

//foreach ($viewRezervace as $rezervace){
//    var_dump($rezervace);
//    echo "<hr>";
//}
?>
<div class="text-start my-2 filter p-2">
    <?php
    if (isset($errorMsg)) {
        echo "<p class='text-white bg-danger p-2 my-2 rounded-3'> $errorMsg </p>";
    }
    if (isset($goodMsg)) {
        echo "<p class='text-white bg-success p-2 my-2 rounded-3'> $goodMsg </p>";
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
                        <label>od:</label>
                        <input class="w-100" type="datetime-local" name="od" id="od">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>do:</label>
                        <input class="w-100" type="datetime-local" name="do" id="do">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>zájemce:</label>
                        <input class="w-100" type="text" name="login" id="login">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>místnost:</label>
                        <input class="w-100" type="text" name="mistnost" id="mistnost">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>příslušenství:</label>
                        <select class="w-100" name="prislusenstvi" id="prislusenstvi">
                            <option value="nevybrano"></option>
                            <?php
                            foreach ($viewPrilusenstvi as $prislusenstvi) {
                                echo "<option value='" . $prislusenstvi["NAZEV"] . "'>" . $prislusenstvi['NAZEV'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>stav:</label>
                        <select class="w-100" name="stav" id="stav">
                            <option value="nevybrano"></option>
                            <?php
                            foreach ($viewStavy as $stav) {
                                echo "<option value='" . $stav["NAZEV"] . "'>" . $stav['NAZEV'] . "</option>";
                            }
                            ?>
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
                <label>Od:</label>
                <input class="w-100" type="datetime-local" name="odAdd" required>
                <label>DO:</label>
                <input class="w-100" type="datetime-local" name="doAdd" required>
                <label>Místnost:</label>
                <input class="w-100" type="text" name="mistnostADd" required>
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
            <th scope="col">od</th>
            <th scope="col">do</th>
            <th scope="col">Zájemce</th>
            <th scope="col">Místnost</th>
            <th scope="col">Příslušenství</th>
            <th scope="col">Stav</th>
        </tr>
        </thead>
        <tbody>
        <?php

        //    //filter od
        if (isset($_GET["od"]) && $_GET['od'] != "") {
            $od = $_GET["od"];
            echo "<script> document.getElementById('od').value ='" . $od . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewRezervace as $rezervace) { // filtr mistnosti
                if ($od == str_replace(' ', 'T', $rezervace['Od'])) {
                    array_push($pom, $rezervace);
                }
            }
            $viewRezervace = $pom;
        }
        //filter do
        if (isset($_GET["do"]) && $_GET['do'] != "") {
            $do = $_GET["do"];
            echo "<script> document.getElementById('do').value ='" . $do . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewRezervace as $rezervace) { // filtr mistnosti
                if ($do == str_replace(' ', 'T', $rezervace['Do'])) {
                    array_push($pom, $rezervace);
                }
            }
            $viewRezervace = $pom;
        }
        //
        //    //filtr loginu
        if (isset($_GET["login"]) && $_GET["login"] != "") {
            $login = $_GET["login"];
            echo "<script> document.getElementById('login').value ='" . $login . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewRezervace as $rezervace) {
                if (!is_bool(strpos(strtolower($rezervace["Zajemce"]), strtolower($login), 0))) {
                    array_push($pom, $rezervace);
                }
            }
            $viewRezervace = $pom;
        }
        //
        //    //filtr mistnosti
        if (isset($_GET["mistnost"]) && $_GET["mistnost"] != "") {
            $mistnost = $_GET["mistnost"];
            echo "<script> document.getElementById('mistnost').value ='" . $mistnost . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewRezervace as $rezervace) {
                if (!is_bool(strpos(strtolower($rezervace["Mistnost"]), strtolower($mistnost), 0))) {
                    array_push($pom, $rezervace);
                }
            }
            $viewRezervace = $pom;
        }
        //filtr stavu
        if (isset($_GET["stav"]) && $_GET["stav"] != "nevybrano") {
            $stav = $_GET["stav"];
            echo "<script> document.getElementById('stav').value ='" . $stav . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewRezervace as $rezervace) {
                if ($rezervace["Stav"] == $stav) {
                    array_push($pom, $rezervace);
                }
            }
            $viewRezervace = $pom;
        }
        //filtr prislusenstvi
        if (isset($_GET["prislusenstvi"]) && $_GET["prislusenstvi"] != "nevybrano") {
            $prislusenstvi = $_GET["prislusenstvi"];
            echo "<script> document.getElementById('prislusenstvi').value ='" . $prislusenstvi . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewRezervace as $rezervace) {
                if (!is_bool(strpos(strtolower($rezervace["Prislusenstvi"]), strtolower($prislusenstvi), 0))) {
                    array_push($pom, $rezervace);
                }
            }
            $viewRezervace = $pom;
        }

        //tvorba tabulky
        if (count($viewRezervace) == 0) {
            echo "<tr><td colspan='10'>Nebyl nalezen žádný záznam</td></tr>";
        } else {
            foreach ($viewRezervace as $rezervace) {
                echo '<tr scope="row radek">';

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { //pokud je přihlášen admin -> možnost editace
                    echo '<td class="radek" data-title="#">
             <button class="btn btn-light text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#item' . $rezervace["ID"] . '"  aria-expanded="false" aria-controls="item' . $rezervace["ID"] . '"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
                }
                echo "<td class='radek' data-title='od'>" . $rezervace["Od"] . "</td>";
                echo "<td class='radek' data-title='do'>" . $rezervace["Do"] . "</td>";
                echo "<td class='radek' data-title='zájemce'>" . $rezervace['Zajemce'] . "</td>";
                echo "<td class='radek' data-title='místnost'>" . $rezervace['Mistnost'] . "</td>";
                echo "<td class='radek' data-title='příslušenství'>" . str_replace(';', ' ', $rezervace['Prislusenstvi']) . "&nbsp</td>";
                echo "<td class='radek' data-title='stav'>" . $rezervace['Stav'] . "</td>";
                echo "</tr>";

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { //editační formulář
                    echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="item' . $rezervace['ID'] . '">
<form class="w-100 px-2" action="" method="post">
<div class="row">
<div><label>Od:</label><input name="loginUpdate" class="w-100" type="datetime-local" value="' . str_replace(' ', 'T', $rezervace['Od']) . '" required></div>
<div><label>Do:</label><input name="emailUpdate" class="w-100" type="datetime-local" value="' . str_replace(' ', 'T', $rezervace['Do']) . '" required></div>
</div>
<button class="btn btn-danger mt-2" type="submit" name="update">Update</button>
</form>
<form class="px-2" action="" method="post">
<input class="d-none" type="text" name="id" value="' . $rezervace['ID'] . '">
<button class="btn btn-danger" type="submit" name="delete">Delete</button>
</form>
</div></td></tr>';
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>

