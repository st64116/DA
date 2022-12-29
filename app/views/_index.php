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
$con = oci_connect("st64116","abcde", "fei-sql3.upceucebny.cz:1521/BDAS.UPCEUCEBNY.CZ");
echo $con;
echo "nvm\n";

select_data("pojistovna",$con);

function select_data($connname, $conn)
{
    $stmt = oci_parse($conn, "select * from osoby");
    oci_execute($stmt, OCI_DEFAULT);
    echo "$connname ----selecting<br>\n";
    while (oci_fetch($stmt)) {
        echo "    " . oci_result($stmt, "JMENO") . "<br>\n";
    }
    echo "$connname ----done<br>\n";
}

?>
</body>
</html>
