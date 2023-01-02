<?php
include_once('database/Client.php');
$db = new Client();

if(isset($_POST['add'])){
    $nazev = htmlspecialchars($_POST['nazev']);
    if($db->insert_umisteni($nazev)){
        $goodMsg = "Umístění úspěšně přidáno!";
    }else{
        $errorMsg = "něco se nepovedlo :(";
    }
}

if(isset($_POST['delete'])){
    if($db->delete_umisteni($_POST['id'])){
        $goodMsg = "Umístění úspěšně odebráno!";
    }else{
        $errorMsg = "něco se nepovedlo :(";
    }
}

if(isset($_POST['update'])){
    if($db->update_umisteni($_POST['id'],htmlspecialchars($_POST['nazev']))){
        $goodMsg = "Umístění úspěšně upraveno :)";
    }else{
        $errorMsg = "něco se nepovedlo :(";
    }
}

$viewUmisteni = $db->view_umisteni();
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
                        <label>Název:</label>
                        <input type="text" name="nazev" id="nazev">
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
                <label>Název:</label>
                <input class="w-100" type="text" name="nazev" required>
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
            <th scope="col">Název</th>
        </tr>
        </thead>
        <tbody>
        <?php

        //filter název
        if (isset($_GET["nazev"]) && $_GET['nazev'] != "") {
            $value = $_GET["nazev"];
            echo "<script> document.getElementById('nazev').value ='" . $value . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewUmisteni as $item) {
                if (!is_bool(strpos(strtolower($item["NAZEV"]), strtolower($value), 0))) {
                    array_push($pom, $item);
                }
            }
            $viewUmisteni = $pom;
        }


        //tvorba tabulky
        if (count($viewUmisteni) == 0) {
            echo "<tr><td colspan='10'>Nebyl nalezen žádný</td></tr>";
        } else {
            foreach ($viewUmisteni as $item) {
                echo '<tr scope="row radek">';

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { //pokud je přihlášen admin -> možnost editace
                    echo '<td data-title="#" class="radek">
             <button class="btn btn-light text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#item' . $item['ID_UMISTENI'] . '"  aria-expanded="false" aria-controls="item' . $item['ID_UMISTENI'] . '"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
                }
                echo "<td class='radek' data-title='název'>" . $item['NAZEV'] . "</td>";
                echo "</tr>";

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
                    echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="item' . $item['ID_UMISTENI'] . '">
<form class="w-100 px-2" action="" method="post">
<div class="row">
<input name="id" class="w-100 d-none" type="text" value="' . $item['ID_UMISTENI'] . '" required readonly>
<div><label>název:</label><input name="nazev" class="w-100" type="text" value="' . $item['NAZEV'] . '" required id="nazev' . $item['ID_UMISTENI'] . '"></div>
<div><button type="submit" name="update" class="btn btn-danger text-start mt-2">update</button></div>
</div>
</form>
<form class="px-2" action="" method="post">
<input class="d-none" type="text" name="id" value="' . $item['ID_UMISTENI'] . '">
<button class="btn btn-danger" type="submit" name="delete">Delete</button>
</form>
</div></td></tr>';
                    echo '<script>document.getElementById("nazev' . $item['ID_UMISTENI'] . '").value = ' . $item['NAZEV'] . '</script>'; // nastavení oprávnění na aktouální hodnotu
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>
