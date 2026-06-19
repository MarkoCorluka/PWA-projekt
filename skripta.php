<?php
if (isset($_POST['title'])) {
    $title = $_POST['title'];
}
if (isset($_POST['about'])) {
    $about = $_POST['about'];
}
if (isset($_POST['content'])) {
    $content = $_POST['content'];
}
if (isset($_POST['category'])) {
    $category = $_POST['category'];
}

if (isset($_POST['archive'])) {
    $archive = "Da";
} else {
    $archive = "Ne";
}

if (isset($_FILES['pphoto']) && $_FILES['pphoto']['name'] != '') {
    $image = $_FILES['pphoto']['name'];
} else {
    $image = '';
}

?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <title>Pregled - Pregled unesene vijesti</title>
</head>
<body>

    <header>
        <a href="index.html"><img src="img/Logo.png" alt=""></a>
        <h1>Pregled News</h1>
        <nav>
            <ul>
                <li><a href="index.html">Početna</a></li>
                <li><a href="index.html#politika">Politika</a></li>
                <li><a href="index.html#tehnologija">Tehnologija</a></li>
                <li><a href="index.html#sport">Sport</a></li>
                <li><a href="unos.html">Administracija</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <article class="clanak">

            <span class="kategorija"><?php echo strtoupper($category); ?></span>

            <h2 class="clanak-naslov"><?php echo $title; ?></h2>


            <?php if ($image) { ?>
                <img class="clanak-slika" src="img/<?php echo $image; ?>" alt="<?php echo $title; ?>">
            <?php } ?>

            <p class="clanak-uvod">
                <?php echo $about; ?>
            </p>

            <p class="clanak-tekst">
                <?php echo $content; ?>
            </p>

        </article>
    </main>

    <footer>
        <h1>Marko Ćorluka</h1>
        <h2><span>E-mail:</span> mcorluka@tvz.hr</h2>
        <h3>&copy; 2026</h3>
    </footer>

</body>
</html>