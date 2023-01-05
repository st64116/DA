<div>
<?php
$db = new Client();
if(isset($_SESSION['LOGIN'])){
    header("Location:mistnosti.php");
}
?>
</div>