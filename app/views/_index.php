<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
include_once('database/Client.php');
$db = new Client();
foreach ($db->view_osoby() as $person) {
    var_dump($person);
    echo "<br>";
}
?>
</body>
</html>
