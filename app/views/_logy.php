<?php
include_once('database/Client.php');
$db = new Client();

if(!isset($_SESSION['ROLE']) || $_SESSION['ROLE'] == 0){
    header("Location:index.php");
    echo "<a href='index.php' class='text-white btn btn-danger'>nemáš přístup!! Zpět na domovskou stránku</a>";
    die();
}

if(isset($_POST['add'])){
    $info = htmlspecialchars($_POST['info']);
    $typ = $_POST['typ'];
    $tabulka = $_POST['tabulka'];
    if($db->insert_log($typ,$tabulka,$info)){
        $goodMsg = "log přidán!";
    }else{
        $errorMsg = "něco se nepovedlo :(";
    }
}

if(isset($_POST['delete'])){
    if($db->delete_log($_POST['id'])){
        $goodMsg = "log odebrán!";
    }else{
        $errorMsg = "něco se nepovedlo :(";
    }
}

if(isset($_POST['update'])){
    if($db->update_log($_POST['id'],$_POST['typ'],$_POST['tabulka'],htmlspecialchars($_POST['info']))){
        $goodMsg = "Stav úspěšně upraveno :)";
    }else{
        $errorMsg = "něco se nepovedlo :(";
    }
}

$viewlogy = $db->view_logy();
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
                    <div class="col-6 col-lg-4 my-2">
                        <label>typ:</label>
                        <select name="typ" id="typ" class="w-100">
                            <option value=""></option>
                            <option value="insert">insert</option>
                            <option value="update">udpate</option>
                            <option value="delete">delete</option>
                        </select>
                    </div>
                    <div class="col-6 col-lg-4 my-2">
                    <label>Tabulka</label>
                        <select name="tabulka" id="tabulka" class="w-100">
                            <option value=""></option>
                            <option value="zajemci">zajemci</option>
                            <option value="rezervace">rezervace</option>
                            <option value="osoby">osoby</option>
                            <option value="firmy">firmy</option>
                            <option value="soubory">soubory</option>
                        </select>
                    </div>
                    <div class="col-6 col-lg-4 my-2">
                        <label>čas od</label>
                        <input type="datetime-local" class="w-100" name="casod" id="casod">
                    </div>

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
                <label>typ:</label>
                <select name="typ" id="typ" class="w-100" required>
                    <option value="insert">insert</option>
                    <option value="update">udpate</option>
                    <option value="delete">delete</option>
                </select>
                <label>Tabulka</label>
                <select name="tabulka" id="tabulka" class="w-100" required>
                    <option value="zajemci">zajemci</option>
                    <option value="rezervace">rezervace</option>
                    <option value="osoby">osoby</option>
                    <option value="firmy">firmy</option>
                    <option value="soubory">soubory</option>
                </select>
                <label>Info:</label>
                <input type="text" id="info" name="info" class="w-100" placeholder="info logu" required>
                <button class="btn btn-danger mt-2" type="submit" name="add">Přidat</button>
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
            <th scope="col">operace</th>
            <th scope="col">tabulka</th>
            <th scope="col">info</th>
            <th scope="col">cas</th>
        </tr>
        </thead>
        <tbody>
        <?php

        //filter typ
        if (isset($_GET["typ"]) && $_GET['typ'] != "") {
            $value = $_GET["typ"];
            echo "<script> document.getElementById('typ').value ='" . $value . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewlogy as $item) {
                if ($item['TYP_OPERACE'] == $value) {
                    array_push($pom, $item);
                }
            }
            $viewlogy = $pom;
        }

        //litr tabulky
        if (isset($_GET["tabulka"]) && $_GET['tabulka'] != "") {
            $value = $_GET["tabulka"];
            echo "<script>document.getElementById('tabulka').value ='" . $value . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewlogy as $item) {
                if ($item['TABULKA'] == $value) {
                    array_push($pom, $item);
                }
            }
            $viewlogy = $pom;
        }
        //filtr čas
        if(isset($_GET['casod'])){
            $pom = array();
            $value = $_GET['casod'];
            echo "<script>document.getElementById('casod').value ='" . $value . "';</script>"; // nastavení inputu na hledanou hodnotu
            foreach ($viewlogy as $item){
                if(strtotime($item['CAS']) > strtotime($value)){
                    array_push($pom, $item);
                }
            }
            $viewlogy = $pom;
        }

        //tvorba tabulky
        if (count($viewlogy) == 0) {
            echo "<tr><td colspan='10'>Nebyl nalezen žádný</td></tr>";
        } else {
            foreach ($viewlogy as $item) {
                echo '<tr scope="row radek">';

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { //pokud je přihlášen admin -> možnost editace
                    echo '<td data-title="#" class="radek">
             <button class="btn btn-light text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#item' . $item['ID_LOGU'] . '"  aria-expanded="false" aria-controls="item' . $item['ID_LOGU'] . '"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
                }
                echo "<td class='radek' data-title='název'>" . $item['TYP_OPERACE'] . "</td>";
                echo "<td class='radek' data-title='název'>" . $item['TABULKA'] . "</td>";
                echo "<td class='radek' data-title='název'>" . $item['INFO'] . "</td>";
                echo "<td class='radek' data-title='název'>" . $item['CAS'] . "</td>";
                echo "</tr>";

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
                    echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="item' . $item['ID_LOGU'] . '">
<form class="w-100 px-2" action="" method="post">
<div class="row">
<input name="id" class="w-100 d-none" type="text" value="' . $item['ID_LOGU'] . '" required readonly>
<div>
<label>operace</label>
                <select name="typ" id="operace' . $item['ID_LOGU'] . '" class="w-100" required>
                    <option value="insert">insert</option>
                    <option value="update">udpate</option>
                    <option value="delete">delete</option>
                </select>
</div>
<div>
                <label>Tabulka</label>
                <select name="tabulka" id="tabulka' . $item['ID_LOGU'] . '" class="w-100" required>
                    <option value="zajemci">zajemci</option>
                    <option value="rezervace">rezervace</option>
                    <option value="osoby">osoby</option>
                    <option value="firmy">firmy</option>
                    <option value="soubory">soubory</option>
                </select>
</div>
<div><label>info:</label><input placeholder="nové info logu!!" name="info" class="w-100" type="text" required id="info' . $item['ID_LOGU'] . '"></div>
<div><button type="submit" name="update" class="btn btn-danger text-start mt-2">update</button></div>
</div>
</form>
<form class="px-2" action="" method="post">
<input class="d-none" type="text" name="id" value="' . $item['ID_LOGU'] . '">
<button class="btn btn-danger" type="submit" name="delete">Delete</button>
</form>
</div></td></tr>';
//                    echo '<script>document.getElementById("info' . $item['ID_LOGU'] . '").value ="' . htmlspecialchars($item['INFO']) . '"</script>'; // nastavení oprávnění na aktouální hodnotu
                    echo '<script>document.getElementById("operace' . $item['ID_LOGU'] . '").value ="' . $item['TYP_OPERACE'] . '";</script>'; // nastavení oprávnění na aktouální hodnotu
                    echo '<script>document.getElementById("tabulka' . $item['ID_LOGU'] . '").value ="' . $item['TABULKA'] . '";</script>'; // nastavení oprávnění na aktouální hodnotu
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>
