<?php
session_start();
include 'connect.php';

$poruka = '';
$registriranKorisnik = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $username = $_POST['username'];
    $lozinka = $_POST['pass'];
    $lozinkaRepeat = $_POST['passRep'];
    $razina = 0;

    if ($lozinka !== $lozinkaRepeat) {
        $poruka = 'Lozinke se ne podudaraju!';
    } else {
        $sql = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime = ?";
        $stmt = mysqli_stmt_init($dbc);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
        }

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $poruka = 'Korisničko ime već postoji!';
        } else {
            
            $hashedLozinka = password_hash($lozinka, PASSWORD_BCRYPT);

            $sql = "INSERT INTO korisnik (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($dbc);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 'ssssi', $ime, $prezime, $username, $hashedLozinka, $razina);
                mysqli_stmt_execute($stmt);
                $registriranKorisnik = true;
            }
        }
        mysqli_close($dbc);
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
    <title>Pregled - Registracija</title>
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
        <?php if ($registriranKorisnik): ?>
            <div class="formaUnosa">
                <p style="color: green; font-weight: bold;">Korisnik je uspješno registriran! <a href="administracija.php">Prijavi se</a></p>
            </div>
        <?php else: ?>
            <h2 class="naslovSekcije">Registracija</h2>
            <form class="formaUnosa" action="registracija.php" method="POST">

                <div class="stavkaForme">
                    <label for="ime">Ime</label>
                    <input type="text" name="ime" id="ime" class="poljeUnosa" required>
                </div>

                <div class="stavkaForme">
                    <label for="prezime">Prezime</label>
                    <input type="text" name="prezime" id="prezime" class="poljeUnosa" required>
                </div>

                <div class="stavkaForme">
                    <label for="username">Korisničko ime</label>
                    <?php if ($poruka): ?>
                        <span style="color: red; font-size: 13px;"><?php echo $poruka; ?></span>
                    <?php endif; ?>
                    <input type="text" name="username" id="username" class="poljeUnosa" required>
                </div>

                <div class="stavkaForme">
                    <label for="pass">Lozinka</label>
                    <input type="password" name="pass" id="pass" class="poljeUnosa" required>
                </div>

                <div class="stavkaForme">
                    <label for="passRep">Ponovite lozinku</label>
                    <input type="password" name="passRep" id="passRep" class="poljeUnosa" required>
                </div>

                <div class="gumbiForme">
                    <button type="reset">Poništi</button>
                    <button type="submit">Registriraj se</button>
                </div>

            </form>
        <?php endif; ?>
    </main>

    <footer>
        <h1>Marko Ćorluka</h1>
        <h2><span>E-mail:</span> mcorluka@tvz.hr</h2>
        <h3>&copy; 2026</h3>
    </footer>

</body>
</html>