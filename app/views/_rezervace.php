<?php
include_once('database/Client.php');
$db = new Client();

if (!isset($_SESSION['ROLE'])) {
    header("Location:index.php");
    echo "<a href='index.php' class='text-white btn btn-danger'>nemáš přístup!! Zpět na domovskou stránku</a>";
    die();
}

if (isset($_POST['delete'])) {
    if ($db->delete_rezervaci($_POST['id'])) {
        $goodMsg = "Uspěšně odstraněno";
    } else {
        $errorMsg = "něco se nepovedlo :(";
    }
}

// TODO: přidání rezervacek

if (isset($_POST['add'])) {
    $od = $_POST['od'];
    $do = $_POST['do'];
    $od = str_replace('T', ' ', $od);
    $do = str_replace('T', ' ', $do);
    $mistnost = $_POST['mistnost'];
    if ($_SESSION['ROLE'] == 1) {
        if ($db->insert_rezervaci_mistnosti($od, $do, htmlspecialchars($_POST['login']), htmlspecialchars($_POST['mistnost']))) {
            $goodMsg = "rezervováno";
        } else {
            $errorMsg = "něco se nepovedlo";
        }
    } else {
        if ($db->insert_rezervaci_mistnosti($od, $do, $_SESSION['LOGIN'], $mistnost)) {
            $goodMsg = "rezervováno";
        } else {
            $errorMsg = "něco se nepovedlo";
        }
    }
}

if (isset($_POST['addVlastnosti'])) {
    $prislusentstviArray = array();
    foreach ($db->view_prislusenstvi() as $prislusenstvi) {
        if (isset($_POST[str_replace(' ', '', $prislusenstvi['NAZEV'])])) {
            array_push($prislusentstviArray, $prislusenstvi['NAZEV']);
        }
    }
//    var_dump($prislusentstviArray);
    $od = $_POST['od'];
    $do = $_POST['do'];
    $od = str_replace('T', ' ', $od);
    $do = str_replace('T', ' ', $do);
    if (isset($_POST['patro']) && $_POST['patro'] != "") {
        $patro = $_POST['patro'];
    } else {
        $patro = null;
    }
    if (isset($_POST['velikost']) && $_POST['velikost'] != "") {
        $velikost = $_POST['velikost'];
    } else {
        $velikost = null;
    }
    if (isset($_POST['umisteni']) && $_POST['umisteni'] != "") {
        $umisteni = $_POST['umisteni'];
    } else {
        $umisteni = null;
    }
    if (isset($_POST['ucel']) && $_POST['ucel'] != "") {
        $ucel = $_POST['ucel'];
    } else {
        $ucel = null;
    }
    if (isset($_POST['login']) && $_POST['login'] != "") {
        $login = $_POST['login'];
    } else {
        $login = $_SESSION['LOGIN'];
    }
//    echo "od: " . $od . " do: " . $do . " login: " . $login . " patro: ". $patro . " ucel: " .$ucel ." umisteni: ". $umisteni . " velikost: ". $velikost;
    if ($db->insert_rezervaci_vlastnostmi($od, $do, $login, $ucel, $umisteni, $patro, $velikost, $prislusentstviArray)) {
        $goodMsg = "rezervováno";
    } else {
        $errorMsg = "Nepovedlo se rezervovat místnost s těmito parametry!!";
    }
}

if (isset($_POST['update'])) {
//    var_dump($_POST['update']);
    $prislusenstviArray = array();
    foreach (explode(';', $_POST['prislusenstvi']) as $item) {
        array_push($prislusenstviArray, $item);
    }
    var_dump($prislusenstviArray);
    if ($db->update_rezervaci($_POST['id'], str_replace('T', ' ', $_POST['od']),
        str_replace('T', ' ', $_POST['do']), null, null, null, null,
        $prislusenstviArray)) {
        $goodMsg = "rezervace změněna";
    } else {
        $errorMsg = "něco se nepovedlo!!";
    }
}


$viewStavy = $db->view_stavy();
$viewPrilusenstvi = $db->view_prislusenstvi();

if ($_SESSION['ROLE'] == 1) {
    $viewRezervace = $db->view_rezervace_hierarchicky();
//    $viewRezervace = $db->view_rezervace();
} else {
    $viewRezervace = $db->view_rezervace_hierarchicky($_SESSION['LOGIN']);
}

$viewmistnosti = $db->view_mistnosti();

$vypsanyRezervace = array();

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
        if (isset($_SESSION['ROLE'])) {
            echo '<div><button class="btn btn-success text-uppercase text-end btn-sm" type="button" data-bs-toggle="collapse"
                data-bs-target="#add" aria-expanded="false" aria-controls="add">Přidat
        </button><button class="btn btn-success text-uppercase text-end ms-3 btn-sm" type="button" data-bs-toggle="collapse"
                data-bs-target="#addVlastnosti" aria-expanded="false" aria-controls="addVlastnosti">Přidat Dle vlastností
        </button></div>';
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
                                echo "<option value='" . $stav["ID_STAVU"] . "'>" . $stav['NAZEV'] . "</option>";
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
    if (isset($_SESSION['ROLE'])) {
        ?>
        <div class="collapse" id="add">
            <form action="" method="post">
                <label>Od:</label>
                <input class="w-100" type="datetime-local" name="od" required>
                <label>DO:</label>
                <input class="w-100" type="datetime-local" name="do" required>
                <label>Místnost:</label>
                <select name="mistnost" class="w-100" required>
                    <?php foreach ($db->view_mistnosti() as $item) {
                        echo "<option value='" . $item['ID_MISTNOSTI'] . "'>" . $item["Mistnost"] . "</option>";
                    } ?>
                </select>
                <?php if ($_SESSION['ROLE'] == 1) {
                    echo "<label>Login:</label><input type='text' class='w-100' name='login'>";
                } ?>
                <button class="btn btn-danger mt-2" type="submit" name="add">Přidat</button>
            </form>
        </div>
        <!--        function insert_rezervaci_vlastnostmi(string $casOd, string $casDo, string $loginZajemce,-->
        <!--        ?int $id_ucelu, ?int $id_umisteni, ?int $id_patra,-->
        <!--        ?int $id_velikosti, ?array $prislusenstvi) : bool-->
        <div class="collapse" id="addVlastnosti">
            <form action="" method="post">
                <label>Od:</label>
                <input class="w-100" type="datetime-local" name="od" required>
                <label>DO:</label>
                <input class="w-100" type="datetime-local" name="do" required>
                <label>Účely:</label>
                <select name="ucel" class="w-100">
                    <option value=""></option>
                    <?php foreach ($db->view_ucely() as $item) {
                        echo "<option value='" . $item['ID_UCELU'] . "'>" . $item["NAZEV"] . "</option>";
                    } ?>
                </select>
                <label>Umístění:</label>
                <select name="umisteni" class="w-100">
                    <option value=""></option>
                    <?php foreach ($db->view_umisteni() as $item) {
                        echo "<option value='" . $item['ID_UMISTENI'] . "'>" . $item["NAZEV"] . "</option>";
                    } ?>
                </select>
                <label>Patro:</label>
                <select name="patro" class="w-100">
                    <option value=""></option>
                    <?php foreach ($db->view_patra() as $item) {
                        echo "<option value='" . $item['ID_PATRA'] . "'>" . $item["NAZEV"] . "</option>";
                    } ?>
                </select>
                <label>Velikost:</label>
                <select name="velikost" class="w-100">
                    <option value=""></option>
                    <?php foreach ($db->view_velikosti() as $item) {
                        echo "<option value='" . $item['ID_VELIKOSTI'] . "'>" . $item["NAZEV"] . "</option>";
                    } ?>
                </select>
                <div class="row mx-0">
                    <?php
                    foreach ($db->view_prislusenstvi() as $prislusenstvi) {
                        echo '<div class="form-check form-switch col-6 col-lg-4">
                                  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault' . $prislusenstvi['NAZEV'] . '" name=' . str_replace(' ', '', $prislusenstvi['NAZEV']) . '>
                                  <label class="form-check-label" for="flexSwitchCheckDefault' . $prislusenstvi['NAZEV'] . '">' . $prislusenstvi['NAZEV'] . '</label>
                                </div>';
                    }
                    ?>
                </div>
                <?php if ($_SESSION['ROLE'] == 1) {
                    echo "<label>Login:</label><input type='text' class='w-100' name='login'>";
                } ?>
                <button class="btn btn-danger mt-2" type="submit" name="addVlastnosti">Přidat</button>
            </form>
        </div>
    <?php } ?>
</div>
<div class="table-responsive shadow-lg mt-3" id="responsive-table">
    <table class="tabulka ms-auto me-auto text-center">
        <thead class="shadow">
        <tr class="text-uppercase">
            <?php
            //            if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
            if (isset($_SESSION['ROLE'])) {
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
                if (!is_bool(strpos(strtolower($rezervace["LOGIN"]), strtolower($login), 0))) {
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
            $mistnosti = array();
            foreach ($viewmistnosti as $item) {
                if (!is_bool(strpos(strtolower($item['Mistnost']), strtolower($mistnost), 0))) {
                    array_push($mistnosti, $item['ID_MISTNOSTI']);
                }
            }
//            var_dump($mistnosti);
            foreach ($viewRezervace as $rezervace) {
                foreach ($mistnosti as $id) {
                    if ($id == $rezervace['ID_MISTNOSTI']) {
                        array_push($pom, $rezervace);
                    }
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
                if ($rezervace["ID_STAVU"] == $stav) {
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
                $uzJeRezervaceVypsana = false;
                foreach ($vypsanyRezervace as $vypsanaRezervace) {
                    if ($vypsanaRezervace == $rezervace['ID_REZERVACE']) {
                        $uzJeRezervaceVypsana = true;
                    }
                }
                if ($uzJeRezervaceVypsana) {
                    continue;
                } else {
                    array_push($vypsanyRezervace, $rezervace['ID_REZERVACE']);
                }
                echo '<tr scope="row radek">';

                if ((isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) || (isset($_SESSION['ROLE']) && $rezervace['ID_STAVU'] == 1)) { //pokud je přihlášen admin -> možnost editace
                    echo '<td class="radek" data-title="#">
             <button class="btn btn-light text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#item' . $rezervace["ID_REZERVACE"] . '"  aria-expanded="false" aria-controls="item' . $rezervace["ID_REZERVACE"] . '"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
                } else {
                    echo "<td></td>";
                }
                echo "<td class='radek' data-title='od'>" . $rezervace["CASOD"] . "</td>";
                echo "<td class='radek' data-title='do'>" . $rezervace["CASDO"] . "</td>";
                echo "<td class='radek' data-title='zájemce'>" . $rezervace['LOGIN'] . "</td>";

                echo "<td class='radek' data-title='místnost'>";
                foreach ($viewmistnosti as $item) {
                    if ($item['ID_MISTNOSTI'] == $rezervace['ID_MISTNOSTI']) {
                        echo $item['Mistnost'];
                        $mistnost = $item;
                        break;
                    }
                }
                echo "</td>";
                echo "<td class='radek' data-title='příslušenství'>" . str_replace(';', ' ', $mistnost['Prislusenstvi']) . "&nbsp</td>";
                echo "<td class='radek' data-title='stav'>";
                foreach ($viewStavy as $item) {
                    if ($item['ID_STAVU'] == $rezervace['ID_STAVU']) {
                        echo $item['NAZEV'];
                        break;
                    }
                }
                echo "</td>";
                echo "</tr>";

                if ((isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) || (isset($_SESSION['ROLE']) && $rezervace['ID_STAVU'] == 1)) { //editační formulář
                    echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="item' . $rezervace["ID_REZERVACE"] . '">
<form class="w-100 px-2" action="" method="post">
<div class="row">
<input class="d-none" type="text" name="id" value="' . $rezervace["ID_REZERVACE"] . '">
<div><label>Od:</label><input name="od" class="w-100" type="datetime-local" value="' . str_replace(' ', 'T', $rezervace['CASOD']) . '" required></div>
<div><label>Do:</label><input name="do" class="w-100" type="datetime-local" value="' . str_replace(' ', 'T', $rezervace['CASDO']) . '" required></div>
<input type="text" name="prislusenstvi" value="' . $mistnost['Prislusenstvi'] . '" class="d-none" readonly>
</div>
<button class="btn btn-danger mt-2" type="submit" name="update">Update</button>
</form>
<form class="px-2" action="" method="post">
<input class="d-none" type="text" name="id" value="' . $rezervace["ID_REZERVACE"] . '">
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

