<?php
include_once('database/Client.php');
$db = new Client();

if (isset($_POST['rezervace']) && isset($_SESSION['LOGIN'])) {
    $od = $_POST['od'];
    $do = $_POST['do'];
    $mistnost = $_POST['mistnost'];
    if ($db->insert_rezervaci_mistnosti($od, $do, $_SESSION['LOGIN'], $mistnost)) {
        $rezervaceMsg = "rezervace místnosti:" . $mistnost . "od: " . $od . "do: " . $do . "proběhla úspěšně";
    } else {
        $errorMsg = "Nastala chyba! Rezervace se bohůžel neprovedla!";
    }
}

if(isset($_GET['update'])){
    //TODO: udpate řádku
}

//TODO: přidání záznamu

if(isset($_POST['submitAdd'])){
    $nazev = $_POST['nazevAdd'];
    $ucel = $_POST['ucelAdd'];
    $umisteni = $_POST['ucelAdd'];
    $patro = $_POST['ucelAdd'];
    $velikost = $_POST['ucelAdd'];
    $prislusentstviArray = array();
    echo "nvm";
    foreach ($db->view_prislusenstvi() as $prislusenstvi) {
        var_dump(str_replace(' ', '' ,$prislusenstvi['NAZEV']));
        if(isset($_POST[str_replace(' ', '' ,$prislusenstvi['NAZEV'])])){
            array_push($prislusentstviArray, $prislusenstvi['NAZEV']);
        }
    }
    var_dump($prislusentstviArray);
    if($db->insert_mistnost($nazev,$ucel,$umisteni,$patro,$velikost,$prislusentstviArray)){
        $rezervaceMsg = "úspěšně přidáno! :)";
    }else{
        $errorMsg = "Nastala chyba! Položka se nepřidala!";
    }
}else{
    var_dump(isset($_POST['submitAdd']));
}

//function insert_mistnost(string $nazev, string $ucel, string $umisteni,
//                         string $patro, string $velikost, array $prislusenstvi) : bool
//{

//TODO: odebrání záznamu

?>
<table class="mistnosti ms-auto me-auto text-center shadow-lg mt-3">

    <div class="text-start my-2 filter p-2">
        <?php
        if (isset($errorMsg)) {
            echo "<p class='text-white bg-danger p-2 my-2 rounded-3'> $errorMsg </p>";
        }
        if (isset($rezervaceMsg)) {
            echo "<p class='text-white bg-danger p-2 my-2 rounded-3'> $rezervaceMsg </p>";
        }
        ?>
        <div class="d-flex justify-content-between">
        <button class="btn btn-primary text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter" aria-expanded="false" aria-controls="filter">Filtr
        </button>
        <?php
            if(isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1){
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
                                foreach ($db->view_Ucely() as $Ucel) {
                                    echo "<option value='" . $Ucel["NAZEV"] . "'>" . $Ucel["NAZEV"] . "</option>";
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
            </form>
        </div>

        <div class="collapse" id="add">
<!--            function insert_mistnost(string $nazev, string $ucel, string $umisteni,-->
<!--            string $patro, string $velikost, array $prislusenstvi) : bool-->
<!--            {-->
            <form action="" method="post">
                <label>Název:</label>
                <input class="w-100" type="text" name="nazevAdd" required>
                <label>účel:</label>
                <select class="w-100" name="ucelAdd" id="ucelAdd" required>
                    <option value="nevybrano"></option>
                    <?php
                    foreach ($db->view_Ucely() as $Ucel) {
                        echo "<option value='" . $Ucel["NAZEV"] . "'>" . $Ucel["NAZEV"] . "</option>";
                    }
                    ?>
                </select>
                <label>umísťění:</label>
                <select class="w-100" name="umisteniAdd" id="umisteniAdd" required>
                    <option value="nevybrano"></option>
                    <?php
                    foreach ($db->view_umisteni() as $umisteni) {
                        echo "<option value='" . $umisteni["NAZEV"] . "'>" . $umisteni["NAZEV"] . "</option>";
                    }
                    ?>
                </select>
                <label>Patro:</label>
                <select class="w-100" name="patra" id="patra" required>
                    <option value="nevybrano"></option>
                    <?php
                    foreach ($db->view_patra() as $patro) {
                        echo "<option value='" . $patro["NAZEV"] . "'>" . $patro["NAZEV"] . "</option>";
                    }
                    ?>
                </select>
                <label>Velikost:</label>
                <select class="w-100" name="velikost" id="velikost" required>
                    <option value="nevybrano"></option>
                    <?php
                    foreach ($db->view_velikosti() as $velikost) {
                        echo "<option value='" . $velikost["NAZEV"] . "'>" . $velikost["NAZEV"] . "</option>";
                    }
                    ?>
                </select>

                <div class="row d-flex justify-content-around">
                <?php
                foreach ($db->view_prislusenstvi() as $prislusenstvi) {
                    echo "<label>". $prislusenstvi['NAZEV'] .":</label><input type='checkbox' name=".str_replace(' ', '' ,$prislusenstvi['NAZEV']).">";
                }
                ?>
                </div>
                <button class="btn btn-danger" type="submit" name="submitAdd">Přidat</button>
            </form>
        </div>

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
        echo "<script> document.getElementById('patra').value ='" . $patra . "';</script>"; // nastavení inputu na hledanou hodnotu
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
        echo "<script> document.getElementById('umisteni').value ='" . $umisteni . "';</script>"; // nastavení inputu na hledanou hodnotu
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

    //tvorba tabulky
    foreach ($mistnosti as $mistnost) {
        echo '<tr scope="row radek">';

        if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { //pokud je přihlášen admin -> možnost editace
            echo '<td>
             <button class="btn btn-light text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#item' . $mistnost["Mistnost"] . '"  aria-expanded="false" aria-controls="item' . $mistnost["Mistnost"] . '"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
        }
        echo "<td>" . "<span class='my-4'>" . $mistnost["Mistnost"] . "</span>" . "</td>";
        echo "<td>" . $mistnost["Ucel"] . "</td>";
        echo "<td>" . $mistnost["Umisteni"] . "</td>";
        echo "<td>" . $mistnost["Patro"] . "</td>";
        echo "<td>" . $mistnost["Velikost"] . "</td>";

        if (isset($_SESSION['ROLE'])) { //pokud je přihlášen -> možnost rezervace
            echo '<td>
             <button class="btn btn-light btn-sm text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#rezerv' . $mistnost["Mistnost"] . '"  aria-expanded="false" aria-controls="rezerv' . $mistnost["Mistnost"] . '">
                <span class="material-symbols-outlined fw-light">add</span>
            </button>
                  </td>';
        }

        echo "</tr>";

        if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
            echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="item' . $mistnost["Mistnost"] . '">
<form class="w-100 px-2 border border-bottom-1 p-1">
<div class="row">
<div><label>název:</label><input class="w-100" type="text" value="' . $mistnost["Mistnost"] . '"></input></div>
<div><label>účel:</label><input class="w-100" type="text" value="' . $mistnost["Ucel"] . '"></input></div>
<div><label>umítění:</label><input class="w-100" type="text" value="' . $mistnost["Umisteni"] . '"></input></div>
<div><label>patro:</label><input class="w-100" type="text" value="' . $mistnost["Patro"] . '"></input></div>
<div><label>velikost:</label><input class="w-100" type="text" value="' . $mistnost["Velikost"] . '"></input></div> 
<div><button type="submit" name="update" class="btn btn-danger text-start mt-2">update</button></div>
</div>
</form>
</div></td></tr>';
        }

        if (isset($_SESSION['ROLE'])) {
            echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="rezerv' . $mistnost["Mistnost"] . '">
<form class="w-100 px-2 border border-bottom-1 p-1" action="" method="post">
<div class="row">
<div><label>místnost:</label><input class="w-100" name="mistnost" type="text" value="' . $mistnost["Mistnost"] . '" readonly></input></div>
<div><label>od:</label><input class="w-100" name="od" type="date" required></input></div>
<div><label>do:</label><input class="w-100" name="do" type="date" required></input></div> 
<div><button type="submit" name="rezervace" class="btn btn-danger text-start mt-2">rezervovat</button></div>
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

    .radek-edit:hover {
        background-color: white !important;
        color: black !important;
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