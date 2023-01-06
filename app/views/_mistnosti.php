<?php
include_once('database/Client.php');
$db = new Client();

$viewUcel = $db->view_ucely();
$viewUmisteni = $db->view_umisteni();
$viewPatra = $db->view_patra();
$viewVelikosti = $db->view_velikosti();
$viewPrislusenstvi = $db->view_prislusenstvi();

if (isset($_POST['rezervace']) && isset($_SESSION['LOGIN'])) {
    $od = $_POST['od'];
    $do = $_POST['do'];
    $od = str_replace('T', ' ', $od);
    $do = str_replace('T', ' ', $do);
    $mistnost = $_POST['mistnost'];
    if ($db->insert_rezervaci_mistnosti($od, $do, $_SESSION['LOGIN'], $mistnost)) {
        $rezervaceMsg = "rezervace místnosti od: " . $od . "do: " . $do . " proběhla úspěšně";
    } else {
        $errorMsg = "Nastala chyba! Rezervace se bohůžel neprovedla!";
    }
}

if (isset($_POST['update'])) {
    $id_mistnosti = $_POST['id_mistnosti'];
    $nazev = htmlspecialchars($_POST['mistnost']);
    foreach ($viewUcel as $item) {
        if ($item['NAZEV'] == $_POST['ucel']) {
            $ucel = $item['ID_UCELU'];
        }
    }
    foreach ($viewUmisteni as $item) {
        if (str_replace(' ', '', $item['NAZEV']) == $_POST['umisteni']) {
            $umisteni = $item['ID_UMISTENI'];
        }
    }
    foreach ($viewPatra as $item) {
        if ($item['NAZEV'] == $_POST['patro']) {
            $patro = $item['ID_PATRA'];
        }
    }
    foreach ($viewVelikosti as $item) {
        if (str_replace(' ', '', $item['NAZEV']) == $_POST['velikost']) {
            $velikost = $item['ID_VELIKOSTI'];
        }
    }
    $prislusentstviArray = array();
    foreach ($db->view_prislusenstvi() as $prislusenstvi) {
        if (isset($_POST[str_replace(' ', '', $prislusenstvi['NAZEV'])])) {
            array_push($prislusentstviArray, $prislusenstvi['NAZEV']);
        }
    }
    if ($db->update_mistnost($id_mistnosti, $nazev, $ucel, $umisteni, $patro, $velikost, $prislusentstviArray)) {
        $rezervaceMsg = "Úpravy se úspěšně neprovedly :)";
    } else {
        $errorMsg = "Nastala chyba! Úpravy se neprovedly!";
    }
}

if (isset($_POST['submitAdd'])) {
    $nazev = htmlspecialchars($_POST['nazevAdd']);
    $ucel = $_POST['ucelAdd'];
    $umisteni = $_POST['ucelAdd'];
    $patro = $_POST['ucelAdd'];
    $velikost = $_POST['ucelAdd'];
    $prislusentstviArray = array();
    foreach ($db->view_prislusenstvi() as $prislusenstvi) {
        if (isset($_POST[str_replace(' ', '', $prislusenstvi['NAZEV'])])) {
            array_push($prislusentstviArray, $prislusenstvi['NAZEV']);
        }
    }
    if ($db->insert_mistnost($nazev, $ucel, $umisteni, $patro, $velikost, $prislusentstviArray)) {
        $rezervaceMsg = "úspěšně přidáno! :)";
    } else {
        $errorMsg = "Nastala chyba! Položka se nepřidala!";
    }
}

if (isset($_POST['delete'])) {
    if ($db->delete_mistnost($_POST['mistnostId'])) {
        $rezervaceMsg = "Místnost byla úspěšně odstraněna :)";
    } else {
        $errorMsg = "Něco se nepovedlo. Místnost nebyla odstraněna!";
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
                        <label>Patro:</label>
                        <select class="w-100" name="patra" id="patraFiltr">
                            <option value="nevybrano"></option>
                            <?php
                            foreach ($viewPatra as $patro) {
                                echo "<option value='" . $patro["NAZEV"] . "'>" . $patro["NAZEV"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>účel:</label>
                        <select class="w-100" name="ucel" id="ucel">
                            <option value="nevybrano"></option>
                            <?php
                            foreach ($viewUcel as $Ucel) {
                                echo "<option value='" . $Ucel["NAZEV"] . "'>" . $Ucel["NAZEV"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>umísťění:</label>
                        <select class="w-100" name="umisteni" id="umisteniFiltr">
                            <option value="nevybrano"></option>
                            <?php
                            foreach ($viewUmisteni as $umisteni) {
                                echo "<option value='" . $umisteni["NAZEV"] . "'>" . $umisteni["NAZEV"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>Velikost:</label>
                        <select class="w-100" name="velikost" id="velikost">
                            <option value="nevybrano"></option>
                            <?php
                            foreach ($viewVelikosti as $velikost) {
                                echo "<option value='" . $velikost["NAZEV"] . "'>" . $velikost["NAZEV"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row ms-0">
                        <?php
                        foreach ($viewPrislusenstvi as $prislusenstvi) {
                            echo '<div class="form-check form-switch col-6 col-lg-4">';
//                            if (isset($_GET[str_replace(' ', '', $prislusenstvi['NAZEV'])])) {
//                                echo '<input checked class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault' . $prislusenstvi['NAZEV'] . '" name=' . str_replace(' ', '', $prislusenstvi['NAZEV']) . '>';
//                            } else {
//                                echo '<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault' . $prislusenstvi['NAZEV'] . '" name=' . str_replace(' ', '', $prislusenstvi['NAZEV']) . '>';
//                            }
                            echo '<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault' . str_replace(' ', '', $prislusenstvi['NAZEV']) . '" name=' . str_replace(' ', '', $prislusenstvi['NAZEV']) . '>';
                            echo '<label class="form-check-label" for="flexSwitchCheckDefault' . $prislusenstvi['NAZEV'] . '">' . $prislusenstvi['NAZEV'] . '</label>
                        </div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="mt-3 text-start col-6">
                        <button class="btn btn-lg btn-danger text-uppercase" type="submit">search
                        </button>
                    </div>
                    <div class="mt-3 text-end col-6">
                        <button class="btn btn-lg btn-danger text-uppercase" type="reset">clear
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
                <label>*Název:</label>
                <input class="w-100" type="text" name="nazevAdd" required>
                <label>*účel:</label>
                <select class="w-100" name="ucelAdd" id="ucelAdd" required>
                    <option value="nevybrano"></option>
                    <?php
                    foreach ($viewUcel as $Ucel) {
                        echo "<option value='" . $Ucel["ID_UCELU"] . "'>" . $Ucel["NAZEV"] . "</option>";
                    }
                    ?>
                </select>
                <label>*umísťění:</label>
                <select class="w-100" name="umisteniAdd" id="umisteniAdd" required>
                    <option value="nevybrano"></option>
                    <?php
                    foreach ($viewUmisteni as $umisteni) {
                        echo "<option value='" . $umisteni["ID_UMISTENI"] . "'>" . $umisteni["NAZEV"] . "</option>";
                    }
                    ?>
                </select>
                <label>*Patro:</label>
                <select class="w-100" name="patra" id="patro" required>
                    <option value="nevybrano"></option>
                    <?php
                    foreach ($viewPatra as $patro) {
                        echo "<option value='" . $patro["ID_PATRA"] . "'>" . $patro["NAZEV"] . "</option>";
                    }
                    ?>
                </select>
                <label>*Velikost:</label>
                <select class="w-100" name="velikost" id="velikost" required>
                    <option value="nevybrano"></option>
                    <?php
                    foreach ($viewVelikosti as $velikost) {
                        echo "<option value='" . $velikost["ID_VELIKOSTI"] . "'>" . $velikost["NAZEV"] . "</option>";
                    }
                    ?>
                </select>

                <div class="row mx-0">
                    <?php
                    foreach ($viewPrislusenstvi as $prislusenstvi) {
//                            echo "<div class='col-6 col-lg-4 text-start'><label>" . $prislusenstvi['NAZEV'] . ":</label><input class='mx-2' type='checkbox' name=" . str_replace(' ', '', $prislusenstvi['NAZEV']) . "></div>";
                        echo '<div class="form-check form-switch col-6 col-lg-4">
                                  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault' . $prislusenstvi['NAZEV'] . '" name=' . str_replace(' ', '', $prislusenstvi['NAZEV']) . '>
                                  <label class="form-check-label" for="flexSwitchCheckDefault' . $prislusenstvi['NAZEV'] . '">' . $prislusenstvi['NAZEV'] . '</label>
                                </div>';
                    }
                    ?>
                </div>
                <button class="btn btn-danger" type="submit" name="submitAdd">Přidat</button>
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
            <th scope="col">nazev</th>
            <th scope="col">účel</th>
            <th scope="col">umísťění</th>
            <th scope="col">patro</th>
            <th scope="col">velikost</th>
            <th scope="col">Příslušenství</th>
            <?php
            if (isset($_SESSION['ROLE'])) {
                echo "<th scope='col''>rezervace</th>";
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php

        $mistnosti = $db->view_mistnosti();

        //filter patra
        if (isset($_GET["patra"]) && $_GET["patra"] != "nevybrano") {
            $patra = $_GET["patra"];
            echo "<script> document.getElementById('patraFiltr').value ='" . $patra . "';console.log('patra no ty mrtko' + document.getElementById('patra').value);</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($mistnosti as $mistnost) { // filtr mistnosti
                if ($mistnost["Patro"] == $patra) {
                    array_push($pom, $mistnost);
                }
            }
            $mistnosti = $pom;
        }

        //filtr Ucelu
        if (isset($_GET["ucel"]) && $_GET["ucel"] != "nevybrano") {
            $ucel = $_GET["ucel"];
            echo "<script> document.getElementById('ucel').value ='" . $ucel . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($mistnosti as $mistnost) {
                if ($mistnost["Ucel"] == $ucel) {
                    array_push($pom, $mistnost);
                }
            }
            $mistnosti = $pom;
        }

        //filtr umisteni
        if (isset($_GET["umisteni"]) && $_GET["umisteni"] != "nevybrano") {
            $umisteni = $_GET["umisteni"];
            echo "<script> document.getElementById('umisteniFiltr').value ='" . $umisteni . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($mistnosti as $mistnost) {
                if ($mistnost["Umisteni"] == $umisteni) {
                    array_push($pom, $mistnost);
                }
            }
            $mistnosti = $pom;
        }

        //filtr velikosti
        if (isset($_GET["velikost"]) && $_GET["velikost"] != "nevybrano") {
            $velikost = $_GET["velikost"];
            echo "<script> document.getElementById('velikost').value ='" . $velikost . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($mistnosti as $mistnost) {
                if ($mistnost["Velikost"] == $velikost) {
                    array_push($pom, $mistnost);
                }
            }
            $mistnosti = $pom;
        }

        //Filtr příslušenství
        $prislusentstviArray = array();
        foreach ($db->view_prislusenstvi() as $prislusenstvi) {
            if (isset($_GET[str_replace(' ', '', $prislusenstvi['NAZEV'])])) {
                array_push($prislusentstviArray, $prislusenstvi['NAZEV']);
                echo "<script>document.getElementById('flexSwitchCheckDefault" . str_replace(' ', '', $prislusenstvi['NAZEV']) . "').checked = true;</script>";
            }
        }
        if (count($prislusentstviArray) > 0) {
            $pom = array();
            foreach ($mistnosti as $mistnost) {
                $maHledanePrislusenstvi = true;
                foreach ($prislusentstviArray as $item) {
                    if (!is_bool(strpos(strtolower($mistnost["Prislusenstvi"]), strtolower($item), 0))) {

                    } else {
                        $maHledanePrislusenstvi = false;
                    }
                }
                if ($maHledanePrislusenstvi) {
                    array_push($pom, $mistnost);
                }
            }
            $mistnosti = $pom;
        }

        if (count($mistnosti) == 0) {
            echo "<tr><td colspan='10'>Nebyl nalezen žádný záznam</td></tr>";
        } else {
            //tvorba tabulky
            foreach ($mistnosti as $mistnost) {
                echo '<tr scope="row radek">';

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { //pokud je přihlášen admin -> možnost editace
                    echo '<td data-title="#" class="radek">
             <button class="btn btn-light text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#item' . $mistnost["ID_MISTNOSTI"] . '"  aria-expanded="false" aria-controls="item' . $mistnost["ID_MISTNOSTI"] . '"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
                }
                echo "<td data-title='nazev' class='radek'>" . "<span class='my-4'>" . $mistnost["Mistnost"] . "</span>" . "</td>";
                echo "<td data-title='účel' class='radek'>" . $mistnost["Ucel"] . "</td>";
                echo "<td data-title='umístění' class='radek'>" . $mistnost["Umisteni"] . "</td>";
                echo "<td data-title='patro' class='radek'>" . $mistnost["Patro"] . "</td>";
                echo "<td data-title='velikost' class='radek'>" . $mistnost["Velikost"] . "</td>";
                echo "<td data-title='příslušenství' class='radek'>" . str_replace(';', ' ', $mistnost["Prislusenstvi"]) . "</td>";

                if (isset($_SESSION['ROLE'])) { //pokud je přihlášen -> možnost rezervace
                    echo '<td data-title="rezervace" class="radek">
             <button class="btn btn-light btn-sm text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#rezerv' . $mistnost["ID_MISTNOSTI"] . '"  aria-expanded="false" aria-controls="rezerv' . $mistnost["ID_MISTNOSTI"] . '">
                <span class="material-symbols-outlined fw-light">add</span>
            </button>
                  </td>';
                }

                echo "</tr>";

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { // editační formulář
                    echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="item' . $mistnost["ID_MISTNOSTI"] . '">
<form class="w-100 px-2" action="" method="post">
<div class="row">
<input class="d-none" name="id_mistnosti" type="text" value="' . $mistnost['ID_MISTNOSTI'] . '" readonly required>
<div><label>název:</label><input name="mistnost" class="w-100" type="text" value="' . $mistnost["Mistnost"] . '"></div>
<div><label>účel:</label>
<select class="w-100" name="ucel" id="ucel' . $mistnost["ID_MISTNOSTI"] . '" required>';
                    foreach ($viewUcel as $Ucel) {
                        echo "<option value='" . $Ucel["NAZEV"] . "'>" . $Ucel["NAZEV"] . "</option>";
                    }
                    echo '</select>
</div>
<div><label>umítění:</label>
<select class="w-100" name="umisteni" id="umisteni' . $mistnost["ID_MISTNOSTI"] . '" required>';
                    foreach ($viewUmisteni as $umisteni) {
                        echo "<option value='" . str_replace(' ', '', $umisteni["NAZEV"]) . "'>" . $umisteni["NAZEV"] . "</option>";
                    }
                    echo '</select>
</div>
<div><label>patro:</label>
<select class="w-100" name="patro" id="patra' . $mistnost["ID_MISTNOSTI"] . '" required>';
                    foreach ($viewPatra as $patro) {
                        echo "<option value='" . $patro["NAZEV"] . "'>" . $patro["NAZEV"] . "</option>";
                    }
                    echo '</select></div>
<div><label>velikost:</label>
<select class="w-100" name="velikost" id="velikost' . $mistnost["ID_MISTNOSTI"] . '" required>';
                    foreach ($viewVelikosti as $velikost) {
                        echo "<option value='" . str_replace(' ', '', $velikost["NAZEV"]) . "'>" . $velikost["NAZEV"] . "</option>";
                    }

                    echo '</select>
                  </div>
                  <div class="row m-0">';

                    foreach ($viewPrislusenstvi as $prislusenstvi) {
                        echo '<div class="form-check form-switch col-6 col-lg-4">';
                        if (!is_bool(strpos(strtolower($mistnost["Prislusenstvi"]), strtolower($prislusenstvi['NAZEV']), 0))) {
                            echo '<input class="form-check-input" checked type="checkbox" role="switch" id="flexSwitchCheckDefaultEdit' . $prislusenstvi['NAZEV'] . '" name=' . str_replace(' ', '', $prislusenstvi['NAZEV']) . '>';
                        } else {
                            echo '<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault' . $prislusenstvi['NAZEV'] . '" name=' . str_replace(' ', '', $prislusenstvi['NAZEV']) . '>';
                        }
                        echo '<label class="form-check-label" for="flexSwitchCheckDefaultEdit' . $prislusenstvi['NAZEV'] . '">' . $prislusenstvi['NAZEV'] . '</label>
                      </div>';
                    }
                    echo '</div> 
<div><button type="submit" name="update" class="btn btn-danger text-start mt-2">update</button></div>
</div>
</form>
<form class="px-2" action="" method="post">
<input class="d-none" type="text" name="mistnostId" value="' . $mistnost['ID_MISTNOSTI'] . '">
<button class="btn btn-danger" type="submit" name="delete">Delete</button>
</form>
</div></td></tr>';

                    echo '<script>document.getElementById("patra' . $mistnost['ID_MISTNOSTI'] . '").value="' . $mistnost["Patro"] . '"</script>';
                    echo '<script>document.getElementById("ucel' . $mistnost['ID_MISTNOSTI'] . '").value="' . $mistnost["Ucel"] . '"</script>';
                    echo '<script>document.getElementById("umisteni' . $mistnost['ID_MISTNOSTI'] . '").value="' . str_replace(' ', '', $mistnost["Umisteni"]) . '"</script>';
                    echo '<script>document.getElementById("velikost' . $mistnost['ID_MISTNOSTI'] . '").value="' . str_replace(' ', '', $mistnost["Velikost"]) . '"</script>';
                }

                if (isset($_SESSION['ROLE'])) { // rezervační formulář
                    echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="rezerv' . $mistnost["ID_MISTNOSTI"] . '">
<form class="w-100 px-2 border border-bottom-1 p-1" action="" method="post">
<div class="row">
<div><label>místnost:</label><input class="w-100 d-none" name="mistnost" type="text" value="' . $mistnost["ID_MISTNOSTI"] . '" readonly></div>
<div><label>od:</label><input class="w-100" name="od" type="datetime-local" required></div>
<div><label>do:</label><input class="w-100" name="do" type="datetime-local" required></div> 
<div><button type="submit" name="rezervace" class="btn btn-danger text-start mt-2">rezervovat</button></div>
</div>
</form>
</div></td></tr>';
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>