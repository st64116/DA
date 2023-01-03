<?php
include_once('database/Client.php');
$db = new Client();

if (isset($_POST['foto'])) {
    $login = $_POST['login'];
    if ($db->view_zajemce($login)) {
        if ($_FILES["image"]["error"] > 0) {
            $errorMsg = "něco se nepovedlo!!";
        } else {
            if ($db->view_profilovky($login) != false) {
                $db->delete_profilovku($login);
            }
            if (!is_bool(strpos(strtolower($_FILES["image"]["type"]), "jpeg", 0))) {
                $pripona = "jpg";
            } else {
                $pripona = "png";
            }

            if ($db->insert_profilovku_pokus($login, $_FILES["image"]["name"], $pripona, $_FILES["image"]["tmp_name"])) {
                $goodMsg = "profilovka nastavena";
            } else {
                $errorMsg = "něco se nepovedlo!!";
            };

        }
    } else {
        $errorMsg = "Login neexistuje";
    }
}

if (isset($_POST['delete'])) {
    if ($db->delete_ucel($_POST['id'])) {
        $goodMsg = "Stav úspěšně odebráno!";
    } else {
        $errorMsg = "něco se nepovedlo :(";
    }
}

if (isset($_POST['update'])) {
    if (isset($_FILES['imageUpdate']) && $_FILES["imageUpdate"]["error"] == 0) {
        $db->delete_profilovku($_POST['login']);
        if (!is_bool(strpos(strtolower($_FILES["imageUpdate"]["type"]), "jpeg", 0))) {
            $pripona = "jpg";
        } else {
            $pripona = "png";
        }
        if ($db->insert_profilovku_pokus($_POST['login'], $_FILES["imageUpdate"]["name"], $pripona,$_FILES['imageUpdate']["tmp_name"])) {
            $goodMsg = "profilovka nastavena";
        } else {
            $errorMsg = "něco se nepovedlo!!";
        };
    } else {
        if ($db->update_profilovku($_POST['login'], $_POST['nazev'], $_POST['pripona'])) {
            $goodMsg = "Fotka upravena :)";
        } else {
            $errorMsg = "něco se nepovedlo :(";
        }
    }
}

$viewProfilovky = $db->view_profilovky();
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
                        <label>login:</label>
                        <input type="text" name="login" id="login">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>Název:</label>
                        <input type="text" name="nazev" id="nazev">
                    </div>
                    <div class="col-6 col-lg-3 my-2">
                        <label>Přípona:</label>
                        <input type="text" name="pripona" id="pripona">
                    </div>

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
            <form action="" method="post" enctype="multipart/form-data">
                <form action="" method="post" class="border border-1 rounded-3 p-2 mx-2 text-center"
                      enctype="multipart/form-data">
                    <label>login:</label>
                    <input type="text" name="login" placeholder="existující login!!" required class="w-100">
                    <input class="my-2 w-100" type="file" accept="image/png, image/jpeg" name="image" required>
                    <button type="submit" name="foto" class="btn btn-danger btn-sm">Přidat</button>
                </form>
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
            <th scope="col">Login</th>
            <th scope="col">Fotka</th>
            <th scope="col">Název</th>
            <th scope="col">Přípona</th>
        </tr>
        </thead>
        <tbody>
        <?php

        //filter název
        if (isset($_GET["login"]) && $_GET['login'] != "") {
            $value = $_GET["login"];
            echo "<script> document.getElementById('login').value ='" . $value . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewProfilovky as $item) {
                if (!is_bool(strpos(strtolower($item["LOGIN"]), strtolower($value), 0))) {
                    array_push($pom, $item);
                }
            }
            $viewProfilovky = $pom;
        }

        //filter název
        if (isset($_GET["nazev"]) && $_GET['nazev'] != "") {
            $value = $_GET["nazev"];
            echo "<script> document.getElementById('nazev').value ='" . $value . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewProfilovky as $item) {
                if (!is_bool(strpos(strtolower($item["NAZEV"]), strtolower($value), 0))) {
                    array_push($pom, $item);
                }
            }
            $viewProfilovky = $pom;
        }

        //filter přípon
        if (isset($_GET["pripona"]) && $_GET['pripona'] != "") {
            $value = $_GET["pripona"];
            echo "<script> document.getElementById('pripona').value ='" . $value . "';</script>"; // nastavení inputu na hledanou hodnotu
            $pom = array();
            foreach ($viewProfilovky as $item) {
                if (!is_bool(strpos(strtolower($item["PRIPONA"]), strtolower($value), 0))) {
                    array_push($pom, $item);
                }
            }
            $viewProfilovky = $pom;
        }


        //tvorba tabulky
        if (count($viewProfilovky) == 0) {
            echo "<tr><td colspan='10'>Nebyl nalezen žádný</td></tr>";
        } else {
            foreach ($viewProfilovky as $item) {
                echo '<tr scope="row radek">';

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) { //pokud je přihlášen admin -> možnost editace
                    echo '<td data-title="#" class="radek">
             <button class="btn btn-light text-uppercase p-0 " type="button" data-bs-toggle="collapse"
                data-bs-target="#item' . str_replace('.', '_', $item['LOGIN']) . '"  aria-expanded="false" aria-controls="item' . str_replace('.', '_', $item['LOGIN']) . '"><span class="material-symbols-outlined">edit</span>
            </button>
                  </td>';
                }
                echo "<td class='radek' data-title='název'>" . $item['LOGIN'] . "</td>";
                echo "<td class='radek' data-title='název'><img class='top-image' src='data:image/jpeg;base64," . base64_encode($item['OBSAH']->load()) . "'/></td>";
                echo "<td class='radek' data-title='název'>" . $item['NAZEV'] . "</td>";
                echo "<td class='radek' data-title='název'>" . $item['PRIPONA'] . "</td>";
                echo "</tr>";

                if (isset($_SESSION['ROLE']) && $_SESSION['ROLE'] == 1) {
                    echo '<tr class="radek-edit text-start"><td colspan="10" class="p-0"><div class="collapse" id="item' . str_replace('.', '_', $item['LOGIN']) . '">
<form class="w-100 px-2" action="" method="post" enctype="multipart/form-data">
<div class="row">
<input name="login" class="w-100 d-none" type="text" value="' . $item['LOGIN'] . '" required readonly>
<div><label>nová fotka:</label><input class="w-100" type="file" accept="image/png, image/jpeg" name="imageUpdate"></div>
<div><label>název:</label><input name="nazev" class="w-100" type="text" value="' . $item['NAZEV'] . '" required id="nazev' . $item['LOGIN'] . '"></div>
<div><label>Přípona:</label><input name="pripona" class="w-100" type="text" value="' . $item['PRIPONA'] . '" required id="pripona' . $item['LOGIN'] . '"></div>
<div><button type="submit" name="update" class="btn btn-danger text-start mt-2">update</button></div>
</div>
</form>
<form class="px-2" action="" method="post">
<input class="d-none" type="text" name="id" value="' . $item['LOGIN'] . '">
<button class="btn btn-danger" type="submit" name="delete">Delete</button>
</form>
</div></td></tr>';
                    echo '<script>document.getElementById("nazev' . $item['LOGIN'] . '").value = ' . $item['NAZEV'] . '</script>'; // nastavení oprávnění na aktouální hodnotu
                    echo '<script>document.getElementById("pripona' . $item['LOGIN'] . '").value = ' . $item['PRIPONA'] . '</script>'; // nastavení oprávnění na aktouální hodnotu
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>
