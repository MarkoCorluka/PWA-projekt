<?php
include 'connect.php';
include 'header.php';
define('UPLPATH','img/');

$id = $_GET['id'];
$query = "SELECT * FROM vijesti WHERE id=$id";
$result = mysqli_query($dbc,$query);
$row = mysqli_fetch_array($result);
?>

<div class="clanak">
        <h1 class="clanak-naslov"><?php echo $row['naslov']; ?></h1>
        <img class="clanak-slika"  src="<?php echo UPLPATH.$row['slika']; ?>" width="500">
        <p class="clanak-uvod"><?php echo $row['sazetak']; ?></p>
        <p class="clanak-tekst"><?php echo $row['tekst']; ?></p>
</div>



<?php include 'footer.php'; ?>