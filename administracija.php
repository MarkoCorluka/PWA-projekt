<?php
session_start();
include 'connect.php';

define('UPLPATH', 'img/');

// LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: administracija.php");
    exit();
}

// PROVJERA PRIJAVE IZ FORME
if (isset($_POST['prijava'])) {
    $username = $_POST['username'];
    $lozinka = $_POST['lozinka'];

    $sql = "SELECT korisnicko_ime, lozinka, razina, ime FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = mysqli_stmt_init($dbc);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $imeKorisnika, $lozinkaKorisnika, $levelKorisnika, $ime);
        mysqli_stmt_fetch($stmt);
    }

    if (mysqli_stmt_num_rows($stmt) > 0 && password_verify($lozinka, $lozinkaKorisnika)) {
        $_SESSION['korisnik'] = $imeKorisnika;
        $_SESSION['razina'] = $levelKorisnika;
        $_SESSION['ime'] = $ime;
        header("Location: administracija.php");
        exit();
    } else {
        $greska = "Pogrešno korisničko ime ili lozinka.";
    }
}

// BRISANJE I UPDATE — samo ako je admin prijavljen
if (isset($_SESSION['korisnik']) && $_SESSION['razina'] == 1) {

    if (isset($_POST['delete'])) {
        $id = mysqli_real_escape_string($dbc, $_POST['id']);
        mysqli_query($dbc, "DELETE FROM vijesti WHERE id = $id");
        header("Location: administracija.php");
        exit();
    }

    if (isset($_POST['update'])) {
        $id = mysqli_real_escape_string($dbc, $_POST['id']);
        $title = mysqli_real_escape_string($dbc, $_POST['naslov']);
        $about = mysqli_real_escape_string($dbc, $_POST['sazetak']);
        $content = mysqli_real_escape_string($dbc, $_POST['tekst']);
        $category = mysqli_real_escape_string($dbc, $_POST['kategorija']);
        $archive = isset($_POST['arhiva']) ? 1 : 0;
        $picture = $_FILES['slika']['name'];

        if ($picture != "") {
            move_uploaded_file($_FILES['slika']['tmp_name'], 'img/' . $picture);
            $query = "UPDATE vijesti SET naslov='$title', sazetak='$about', tekst='$content', slika='$picture', kategorija='$category', arhiva='$archive' WHERE id=$id";
        } else {
            $query = "UPDATE vijesti SET naslov='$title', sazetak='$about', tekst='$content', kategorija='$category', arhiva='$archive' WHERE id=$id";
        }

        mysqli_query($dbc, $query);
        header("Location: administracija.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <title>Pregled - Administracija</title>
</head>
<body>

<header>
    <a href="index.php"><img src="img/Logo.png" alt=""></a>
    <h1>Pregled News</h1>
    <nav>
        <ul>
            <li><a href="index.php">Početna</a></li>
            <li><a href="kategorija.php?id=politika">Politika</a></li>
            <li><a href="kategorija.php?id=tehnologija">Tehnologija</a></li>
            <li><a href="kategorija.php?id=sport">Sport</a></li>
            <li><a href="administracija.php">Administracija</a></li>
        </ul>
    </nav>
</header>

<main>

<?php if (!isset($_SESSION['korisnik'])): ?>

    <h2 class="naslovSekcije">Prijava</h2>
    <form class="formaUnosa" action="administracija.php" method="POST">

        <?php if (isset($greska)): ?>
            <p class="porukaGreska"><?php echo $greska; ?> <a href="registracija.php">Registriraj se</a></p>
        <?php endif; ?>

        <div class="stavkaForme">
            <label for="username">Korisničko ime</label>
            <input type="text" name="username" id="username" class="poljeUnosa" required autofocus>
        </div>

        <div class="stavkaForme">
            <label for="lozinka">Lozinka</label>
            <input type="password" name="lozinka" id="lozinka" class="poljeUnosa" required>
        </div>

        <div class="gumbiForme">
            <button type="submit" name="prijava">Prijavi se</button>
        </div>

    </form>

<?php elseif ($_SESSION['razina'] != 1): ?>

    <div class="formaUnosa">
        <p class="porukaGreska">Bok <strong><?php echo $_SESSION['ime']; ?></strong>! Uspješno ste prijavljeni, ali nemate pravo pristupa administratorskoj stranici.</p>
        <a class="poveznica" href="administracija.php?logout=1">Odjavi se</a>
    </div>

<?php else: ?>

    <h2 class="naslovSekcije">Admin, Dobrodošao, <?php echo $_SESSION['ime']; ?>! <a class="poveznicaOdjava" href="administracija.php?logout=1">Odjava</a></h2>

    <?php
    $selected_row = null;
    if (isset($_POST['odaberi_vijest'])) {
        $id = (int)$_POST['vijest_id'];
        $query = "SELECT * FROM vijesti WHERE id = $id";
        $result = mysqli_query($dbc, $query);
        $selected_row = mysqli_fetch_array($result);
    }
    ?>

    <form method="POST" class="formaUnosa">
        <div class="stavkaForme">
            <label>Odaberite vijest za uređivanje</label>
            <select class="poljeUnosa" name="vijest_id" required>
                <option value="">-- Odaberite vijest --</option>
                <?php
                $all_news = mysqli_query($dbc, "SELECT id, naslov FROM vijesti ORDER BY id DESC");
                while ($news = mysqli_fetch_array($all_news)) {
                    $selected = (isset($_POST['vijest_id']) && $_POST['vijest_id'] == $news['id']) ? 'selected' : '';
                    echo '<option value="' . $news['id'] . '" ' . $selected . '>' . $news['id'] . ' - ' . htmlspecialchars($news['naslov']) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="gumbiForme">
            <button type="submit" name="odaberi_vijest">Odaberi</button>
        </div>
    </form>

    <?php if ($selected_row): ?>
    <hr>
    <form class="formaUnosa" method="POST" enctype="multipart/form-data">

        <div class="stavkaForme">
            <label>Naslov</label>
            <input class="poljeUnosa" type="text" name="naslov" value="<?php echo htmlspecialchars($selected_row['naslov']); ?>">
        </div>

        <div class="stavkaForme">
            <label>Sažetak</label>
            <textarea class="poljeUnosa" name="sazetak"><?php echo htmlspecialchars($selected_row['sazetak']); ?></textarea>
        </div>

        <div class="stavkaForme">
            <label>Tekst</label>
            <textarea class="poljeUnosa" name="tekst" rows="10"><?php echo htmlspecialchars($selected_row['tekst']); ?></textarea>
        </div>

        <div class="stavkaForme">
            <label>Kategorija</label>
            <select class="poljeUnosa" name="kategorija">
                <option value="politika" <?php echo ($selected_row['kategorija'] == 'politika') ? 'selected' : ''; ?>>Politika</option>
                <option value="tehnologija" <?php echo ($selected_row['kategorija'] == 'tehnologija') ? 'selected' : ''; ?>>Tehnologija</option>
                <option value="sport" <?php echo ($selected_row['kategorija'] == 'sport') ? 'selected' : ''; ?>>Sport</option>
            </select>
        </div>

        <div class="stavkaForme">
            <label>Slika</label>
            <input class="poljeUnosa" type="file" name="slika">
            <?php if ($selected_row['slika']): ?>
                <small class="trenutnaSlika">Trenutna slika: <?php echo htmlspecialchars($selected_row['slika']); ?></small>
            <?php endif; ?>
        </div>

        <div class="stavkaCheckbox">
            <label>
                <input type="checkbox" name="arhiva" <?php echo ($selected_row['arhiva']) ? 'checked' : ''; ?>> Arhiva
            </label>
        </div>

        <div class="gumbiForme">
            <input type="hidden" name="id" value="<?php echo $selected_row['id']; ?>">
            <button type="submit" name="update">Ažuriraj</button>
            <button type="submit" name="delete" onclick="return confirm('Jeste li sigurni da želite obrisati ovu vijest?')">Obriši</button>
            <button type="reset">Reset</button>
        </div>

    </form>
    <?php endif; ?>

<?php endif; ?>

</main>

<footer>
    <h1>Marko Ćorluka</h1>
    <h2><span>E-mail:</span> mcorluka@tvz.hr</h2>
    <h3>&copy; 2026</h3>
</footer>

</body>
</html>