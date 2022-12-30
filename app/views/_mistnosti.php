<?php
include_once('database/Client.php');
$db = new Client();
?>
<table class="mistnosti ms-auto me-auto text-center shadow-lg mt-3">

    <div class="text-start my-2 filter p-2">
        <button class="btn btn-primary text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter" aria-expanded="false" aria-controls="filter">Filtr
        </button>
        <div class="collapse" id="filter">
            <form>
                <div class=" mt-3 px-2 py-3 p-sm-5 border border-dark rounded-3">
                    <div class="row text-start">
                        <div class="col-6 col-lg-3 my-2">
                            <label>Patro:</label>
                            <select class="w-100" name="patra" id="patra">
                                <option value="nevybrano"></option>
                                <?php
                                foreach ($db->view_patra() as $patro) {
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
                                foreach ($db->view_ucely() as $ucel) {
                                    echo "<option value='" . $ucel["NAZEV"] . "'>" . $ucel["NAZEV"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-6 col-lg-3 my-2">
                            <label>umísťění:</label>
                            <select class="w-100" name="umisteni" id="umisteni">
                                <option value="nevybrano"></option>
                                <?php
                                foreach ($db->view_umisteni() as $umisteni) {
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
                                foreach ($db->view_velikosti() as $velikost) {
                                    echo "<option value='" . $velikost["NAZEV"] . "'>" . $velikost["NAZEV"] . "</option>";
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
        </div>
        </form>
    </div>

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
    </tr>
    </thead>
    <tbody>
    <?php

    $mistnosti = $db->view_mistnosti();

    //filter patra
    if (isset($_GET["patra"]) && $_GET["patra"] != "nevybrano") {
        $patra = $_GET["patra"];
        echo "<script> document.getElementById('patra').value ='" . $patra . "';</script>"; // nastavení inputu na hledanou hodnotu
        $pom = array();
        foreach ($mistnosti as $mistnost) { // filtr mistnosti
            if ($mistnost["NAZEV_PATRA"] == $patra) {
                array_push($pom, $mistnost);
            }
        }
        $mistnosti = $pom;
    }

    //filtr ucelu
    if (isset($_GET["ucel"]) && $_GET["ucel"] != "nevybrano") {
        $ucel = $_GET["ucel"];
        echo "<script> document.getElementById('ucel').value ='" . $ucel . "';</script>"; // nastavení inputu na hledanou hodnotu
        $pom = array();
        foreach ($mistnosti as $mistnost) {
            if ($mistnost["NAZEV_UCELU"] == $ucel) {
                array_push($pom, $mistnost);
            }
        }
        $mistnosti = $pom;
    }

    //filtr umisteni
    if (isset($_GET["umisteni"]) && $_GET["umisteni"] != "nevybrano") {
        $umisteni = $_GET["umisteni"];
        echo "<script> document.getElementById('umisteni').value ='" . $umisteni . "';</script>"; // nastavení inputu na hledanou hodnotu
        $pom = array();
        foreach ($mistnosti as $mistnost) {
            if ($mistnost["NAZEV_UMISTENI"] == $umisteni) {
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
            if ($mistnost["ANZEV_VELIKOSTI"] == $velikost) {
                array_push($pom, $mistnost);
            }
        }
        $mistnosti = $pom;
    }

    //tvorba tabulky
    foreach ($mistnosti as $mistnost) {
        echo '<tr scope="row radek">';

        if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
            echo '<td>
             <button class="btn btn-light text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#item'. $mistnost["NAZEV_MISTNOSTI"] . '"  aria-expanded="false" aria-controls="item' . $mistnost["NAZEV_MISTNOSTI"] .'"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
        }

        echo "<td>" . "<span class='my-4'>" . $mistnost["NAZEV_MISTNOSTI"] . "</span>" . "</td>";
        echo "<td>" . $mistnost["NAZEV_UCELU"] . "</td>";
        echo "<td>" . $mistnost["NAZEV_UMISTENI"] . "</td>";
        echo "<td>" . $mistnost["NAZEV_PATRA"] . "</td>";
        echo "<td>" . $mistnost["ANZEV_VELIKOSTI"] . "</td>";
        echo "</tr>";

        if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
            echo '<tr class="radek-edit text-start"><td colspan="6" class="p-0"><div class="collapse" id="item' . $mistnost["NAZEV_MISTNOSTI"] .'">
<form class="w-100">
<div class="row">
<div><label>název:</label><input class="w-100" type="text" value="' . $mistnost["NAZEV_MISTNOSTI"] .'"></input></div>
<div><label>účel:</label><input class="w-100" type="text" value="' . $mistnost["NAZEV_UCELU"] .'"></input></div>
<div><label>umítění:</label><input class="w-100" type="text" value="' . $mistnost["NAZEV_UMISTENI"] .'"></input></div>
<div><label>patro:</label><input class="w-100" type="text" value="' . $mistnost["NAZEV_PATRA"] .'"></input></div>
<div><label>velikost:</label><input class="w-100" type="text" value="' . $mistnost["ANZEV_VELIKOSTI"] .'"></input></div> 
<div><button type="submit" name="update" class="btn btn-danger text-start mt-2">update</button></div>
</div>
</form>
</div></td></tr>';
        }

    }
    ?>
    </tbody>
</table>
<style>
    .filter {

    }

    .mistnosti {
        width: 100%;
    }

    .mistnosti tr {
        padding-top: 10px;
        margin: 10px;
    }

    .mistnosti th {
        background-color: var(--color1);
        padding: 16px 8px 16px 8px;
        color: white;
    }

    .mistnosti td {
        padding: 8px;
        font-weight: bold;
    }

    .mistnosti tr:hover {
        background-color: var(--color2);
        color: white;
    }

    .radek-edit:hover{
        background-color: white !important;
        color:black !important;
    }

    @media (max-width: 767px) {
        .mistnosti th {
            padding: 8px 2px 8px 2px;
            font-size: 0.7em;
        }

        .mistnosti td {
            padding: 8px;
            font-size: 0.7em;
        }
    }
</style>