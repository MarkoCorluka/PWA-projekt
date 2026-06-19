<?php
session_start();
include 'connect.php';
include 'header.php';
?>

<main>

<?php if (!isset($_SESSION['korisnik']) || $_SESSION['razina'] != 1): ?>
    <div class="formaUnosa">
        <p class="porukaGreska">Nemate pristup ovoj stranici. <a href="administracija.php">Prijavi se</a></p>
    </div>

<?php else: ?>

<form class="formaUnosa" enctype="multipart/form-data" method="POST">

    <div class="stavkaForme">
        <label>Naslov</label>
        <input class="poljeUnosa" type="text" name="naslov" placeholder="Naslov">
    </div>

    <div class="stavkaForme">
        <label>Sažetak</label>
        <textarea class="poljeUnosa" name="sazetak" placeholder="Sažetak"></textarea>
    </div>

    <div class="stavkaForme">
        <label>Tekst</label>
        <textarea class="poljeUnosa" name="tekst" placeholder="Tekst"></textarea>
    </div>

    <div class="stavkaForme">
        <label>Kategorija</label>
        <select class="poljeUnosa" name="kategorija">
            <option value="politika">Politika</option>
            <option value="tehnologija">Tehnologija</option>
            <option value="sport">Sport</option>
        </select>
    </div>

    <div class="stavkaForme">
        <label>Slika</label>
        <input class="poljeUnosa" type="file" name="slika">
    </div>

    <div class="stavkaCheckbox">
        <label>
            <input type="checkbox" name="arhiva"> Arhiva
        </label>
    </div>

    <div class="gumbiForme">
        <button type="submit" name="submit">Spremi</button>
        <button type="reset">Reset</button>
    </div>

</form>

<?php
if (isset($_POST['submit'])) {
    $picture = $_FILES['slika']['name'];
    
    if ($picture != '') {
        move_uploaded_file($_FILES['slika']['tmp_name'], 'img/' . $picture);
    }

    $title    = $_POST['naslov'];
    $about    = $_POST['sazetak'];
    $content  = $_POST['tekst'];
    $category = $_POST['kategorija'];
    $date     = date('d.m.Y.');
    $archive  = isset($_POST['arhiva']) ? 1 : 0;

    $sql = "INSERT INTO vijesti (datum, naslov, sazetak, tekst, slika, kategorija, arhiva)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssssssi', $date, $title, $about, $content, $picture, $category, $archive);
        mysqli_stmt_execute($stmt);
        echo '<p class="porukaUspjeh">Vijest uspješno spremljena!</p>';
    } else {
        echo '<p class="porukaGreska">Greška pri spremanju vijesti.</p>';
    }
    mysqli_close($dbc);
}
?>

<?php endif; ?>

</main>

<?php include 'footer.php'; ?>