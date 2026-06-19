<?php
include 'connect.php';
include 'header.php';
define('UPLPATH','img/');

$kategorija = $_GET['id'];
$query = "SELECT * FROM vijesti WHERE kategorija='$kategorija' AND arhiva=0";
$result = mysqli_query($dbc,$query);
?>

<body>
    <main> 
    <div class="artikli">
    <?php
        while($row = mysqli_fetch_array($result)) {
        echo '<article>';
        echo '<span class="kategorija">' . strtoupper(htmlspecialchars($row['kategorija'])) . '</span>';
        echo '<a href="clanak.php?id='.$row['id'].'"><h3>' . htmlspecialchars($row['naslov']) . '</h3></a>';
        echo '<img src="'.UPLPATH . htmlspecialchars($row['slika']).'" alt="' . htmlspecialchars($row['naslov']) . '">';
        echo '<p class="summary">' . htmlspecialchars($row['sazetak']) . '</p>';
        echo '</article>';
        }
    ?>
</div>
</main>
</body>

<?php
include 'footer.php';
?>