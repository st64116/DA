<?php
include_once('database/Client.php');
$db = new Client();

if (isset($_POST['heslo'])) {
    if ($_POST['password'] == $_POST['passwordAgain']) {
        if ($db->update_heslo($_SESSION['LOGIN'], $_POST['password'])) {
            $goodMsg = "Heslo úspěšně změněno";
        } else {
            $errorMsg = "něco se nepovedlo :(";
        }
    } else {
        $errorMsg = "hesla musí být stejná!!";
    }
}

if (isset($_POST['update'])) {
    if (isset($_POST['detail'])) {
        $detail = 0;
    } else {
        $detail = 1;
    }
    if ($db->update_osobu($_POST['login'], $_POST['email'], $_POST['opravneni'], $_POST['jmeno'], $_POST['prijmeni'], $detail, $_POST['nadrizeny'])) {
        $goodMsg = "Data úspěšně změněna";
    } else {
        $errorMsg = "něco se nepovedlo :(";
    }
}

$userData = $db->view_zajemce($_SESSION['LOGIN']);
//var_dump($userData);

?>


<div class="shadow row text-center">
    <?php
    if (isset($errorMsg)) {
        echo "<p class='text-white bg-danger p-2 my-2 rounded-3'> $errorMsg </p>";
    }
    if (isset($goodMsg)) {
        echo "<p class='text-white bg-success p-2 my-2 rounded-3'> $goodMsg </p>";
    }
    ?>
    <div class="shadow mb-2 nadpis">
        <h3 class="p-2">Uživatelská data</h3>
    </div>
    <form class="text-start" action="" method="post">
        <div class="col-12"><label class="">Login:</label><input id="login" name="login" class="w-100" type="text"
                                                                 required></div>
        <div class="col-12"><label class="">Email:</label><input id="email" name="email" class="w-100" type="email"
                                                                 required></div>
        <!--        <div class="col-12"><label class="">Firma:</label><input id="firma" name="firma" class="w-100" type="text">-->
        <!--        </div>-->
        <div class="col-12"><label class="">Jméno:</label><input id="jmeno" name="jmeno" class="w-100" type="text"
                                                                 required></div>
        <div class="col-12"><label class="">Příjmení:</label><input id="prijmeni" name="prijmeni" class="w-100"
                                                                    type="text" required></div>
        <div class="form-check form-switch my-2">
            <?php
            if ($userData['DETAIL'] == 0) {
                echo '<input class="form-check-input" type="checkbox" role="switch" id="detail" name="detail" checked>';
            } else {
                echo '<input class="form-check-input" type="checkbox" role="switch" id="detail" name="detail">';
            }
            ?>
            <label class="form-check-label" for="detail">Veřejný profil</label>
        </div>
        <div class="col-12">
            <label>Oprávnění: </label>
            <select class="w-100 py-1 text-uppercase" id="opravneni" name="opravneni" required>
                <option value="0">uživatel</option>
                <option value="1">admin</option>
            </select>
        </div>
        <div class="col-12"><label>Nadřízený:</label><input id="nadrizeny" name="nadrizeny" class="w-100" type="text">
        </div>
        <button class="btn btn-danger my-2" type="submit" name="update">Update</button>
    </form>
    <hr>
    <form action="" method="post">
        <div class="row text-start">
            <div class="col-12 col-lg-4">
                <label>Nové heslo:</label>
                <input name="password" class="w-100" type="text" minlength="4">
            </div>
            <div class="col-lg-4">
                <label>Nové heslo znovu:</label>
                <input name="passwordAgain" class="w-100" minlength="4" type="text">
            </div>
            <div class="col-4">
                <label class="w-100">&nbsp</label>
                <button type="submit" name="heslo" class="btn btn-danger btn-sm w-100">Změnit Heslo</button>
            </div>
        </div>
    </form>
</div>

<?php
echo "<script>document.getElementById('login').value='" . $userData['LOGIN'] . "'</script>";
echo "<script>document.getElementById('email').value='" . $userData['EMAIL'] . "'</script>";
echo "<script>document.getElementById('firma').value='" . $userData['NAZEV'] . "'</script>";
echo "<script>document.getElementById('jmeno').value='" . $userData['JMENO'] . "'</script>";
echo "<script>document.getElementById('prijmeni').value='" . $userData['PRIJMENI'] . "'</script>";
echo "<script>document.getElementById('nadrizeny').value='" . $userData['NADRIZENY'] . "'</script>";
echo "<script>document.getElementById('opravneni').value='" . $userData['OPRAVNENI'] . "'</script>";
if ($_SESSION['ROLE'] == 0) {
    echo "<script>document.getElementById('opravneni').disabled=true</script>";
}
?>
<style>
    .nadpis{
        background-color: var(--color1);
        color: white;
        font-weight: bold;
    }
</style>

