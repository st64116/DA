<div>
<?php

$db = new Client();
foreach ($db->view_osoby() as $person) {
    var_dump($person);
    echo "<br>";
}
?>
</div>