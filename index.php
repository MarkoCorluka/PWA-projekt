<?php
include 'connect.php';
include 'header.php';
define('UPLPATH', 'img/');
?>

<main>

<section id="politika">
<h2 class="naslovSekcije">Politika</h2>
<div class="artikli">
<?php
$query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='politika' LIMIT 3";
$result = mysqli_query($dbc, $query);

while($row = mysqli_fetch_array($result)) {
    echo '<article>';
    echo '<span class="kategorija">'.$row['kategorija'].'</span>';
    echo '<a href="clanak.php?id='.$row['id'].'"><h3>'.$row['naslov'].'</h3></a>';
    echo '<img src="'.UPLPATH.$row['slika'].'">';
    echo '<p class="summary">'.$row['sazetak'].'</p>';
    echo '</article>';
}
?>
</div>
</section>

<section id="tehnologija">
<h2 class="naslovSekcije">Tehnologija</h2>
<div class="artikli">
<?php
$query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='tehnologija' LIMIT 3";
$result = mysqli_query($dbc, $query);

while($row = mysqli_fetch_array($result)) {
    echo '<article>';
    echo '<span class="kategorija">'.$row['kategorija'].'</span>';
    echo '<a href="clanak.php?id='.$row['id'].'"><h3>'.$row['naslov'].'</h3></a>';
    echo '<img src="'.UPLPATH.$row['slika'].'">';
    echo '<p class="summary">'.$row['sazetak'].'</p>';
    echo '</article>';
}
?>
</div>
</section>

<section id="sport">
<h2 class="naslovSekcije">Sport</h2>
<div class="artikli">
<?php
$query = "SELECT * FROM vijesti WHERE arhiva=0 AND kategorija='sport' LIMIT 3";
$result = mysqli_query($dbc, $query);

while($row = mysqli_fetch_array($result)) {
    echo '<article>';
    echo '<span class="kategorija">'.$row['kategorija'].'</span>';
    echo '<a href="clanak.php?id='.$row['id'].'"><h3>'.$row['naslov'].'</h3></a>';
    echo '<img src="'.UPLPATH.$row['slika'].'">';
    echo '<p class="summary">'.$row['sazetak'].'</p>';
    echo '</article>';
}
?>
</div>
</section>

</main>

<?php include 'footer.php'; ?>