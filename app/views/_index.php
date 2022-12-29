<div>
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
        echo "    " . oci_result($stmt, "JMENO") . "";
        echo "    " . oci_result($stmt, "PRIJMENI") . "<br>\n";
    }
    echo "$connname ----done<br>\n";
}

?>
</div>