<?php
session_start();

if (isset($_SESSION['logat']) && $_SESSION['logat'] === true) {
    header('Location: autoturism.php');
    exit;
}

$eroare  = '';
$succes  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user   = trim($_POST['username']  ?? '');
    $pass1  = trim($_POST['password1'] ?? '');
    $pass2  = trim($_POST['password2'] ?? '');

    // Validari
    if (empty($user) || empty($pass1) || empty($pass2)) {
        $eroare = 'Toate câmpurile sunt obligatorii!';
    } elseif (strlen($user) < 3) {
        $eroare = 'Utilizatorul trebuie să aibă cel puțin 3 caractere!';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $user)) {
        $eroare = 'Utilizatorul poate conține doar litere, cifre și "_"!';
    } elseif (strlen($pass1) < 6) {
        $eroare = 'Parola trebuie să aibă cel puțin 6 caractere!';
    } elseif ($pass1 !== $pass2) {
        $eroare = 'Parolele introduse nu coincid!';
    } else {
        // Verificam daca utilizatorul exista deja
        $useri = [];
        if (file_exists('user.txt')) {
            $useri = file('user.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        $exista = false;
        foreach ($useri as $u) {
            if (strtolower(trim($u)) === strtolower($user)) {
                $exista = true;
                break;
            }
        }

        if ($exista) {
            $eroare = 'Acest utilizator există deja! Alegeți alt nume.';
        } else {
            // Adaugam utilizatorul si parola in fisiere
            file_put_contents('user.txt',  $user  . PHP_EOL, FILE_APPEND | LOCK_EX);
            file_put_contents('passw.txt', $pass1 . PHP_EOL, FILE_APPEND | LOCK_EX);
            $succes = 'Cont creat cu succes! Vă puteți autentifica acum.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AutoElite — Creare Cont</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=Jost:wght@200;300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --noir:   #0a0a0f;
    --carbon: #111118;
    --steel:  #1c1c28;
    --silver: #8a8a9a;
    --chrome: #c8c8d8;
    --white:  #f0f0f5;
    --gold:   #c9a84c;
    --gold2:  #e8c97a;
    --green:  #27ae60;
    --red:    #c0392b;
  }

  html, body {
    min-height: 100%;
    background: var(--noir);
    font-family: 'Jost', sans-serif;
    color: var(--white);
  }

  .bg {
    position: fixed; inset: 0; z-index: 0;
    background:
      radial-gradient(ellipse 60% 60% at 30% 40%, #1a1a2e 0%, transparent 60%),
      radial-gradient(ellipse 50% 70% at 80% 70%, #12121f 0%, transparent 55%),
      var(--noir);
  }
  .bg::after {
    content: '';
    position: absolute; inset: 0;
    background-image:
      linear-gradient(rgba(201,168,76,.03) 1px, transparent 1px),
      linear-gradient(90deg, rgba(201,168,76,.03) 1px, transparent 1px);
    background-size: 60px 60px;
  }

  .wrapper {
    position: relative; z-index: 1;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
  }

  .card {
    background: var(--carbon);
    border: 1px solid rgba(201,168,76,.12);
    border-radius: 2px;
    padding: 3rem 2.8rem;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 40px 80px rgba(0,0,0,.6), inset 0 1px 0 rgba(255,255,255,.04);
    animation: fadeUp .7s ease both;
    position: relative;
    overflow: hidden;
  }
  .card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
  }

  .back-link {
    display: inline-flex; align-items: center; gap: .5rem;
    color: var(--silver);
    text-decoration: none;
    font-size: .72rem;
    letter-spacing: .15em;
    text-transform: uppercase;
    margin-bottom: 2rem;
    transition: color .2s;
  }
  .back-link:hover { color: var(--gold); }
  .back-link::before { content: '←'; font-size: .9rem; }

  .logo-row {
    display: flex; align-items: center; gap: 1rem;
    margin-bottom: 1.8rem;
  }
  .logo-mark {
    width: 44px; height: 44px;
    border: 1.5px solid var(--gold);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .logo-mark span {
    font-family: 'Cormorant Garamond', serif;
    font-size: .78rem;
    font-weight: 600;
    letter-spacing: .1em;
    color: var(--gold);
  }
  .logo-text {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.5rem;
    font-weight: 300;
  }
  .logo-text em { font-style: italic; color: var(--gold); }

  .card-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.55rem;
    font-weight: 300;
    margin-bottom: .3rem;
  }
  .card-hint {
    font-size: .72rem;
    color: var(--silver);
    letter-spacing: .12em;
    text-transform: uppercase;
    margin-bottom: 1.8rem;
  }

  .field { margin-bottom: 1.25rem; }
  .field label {
    display: block;
    font-size: .68rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--silver);
    margin-bottom: .5rem;
  }
  .field input {
    width: 100%;
    background: var(--steel);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 1px;
    color: var(--white);
    font-family: 'Jost', sans-serif;
    font-size: .9rem;
    font-weight: 300;
    padding: .82rem 1rem;
    outline: none;
    transition: border-color .25s, box-shadow .25s;
    letter-spacing: .04em;
  }
  .field input::placeholder { color: rgba(138,138,154,.35); }
  .field input:focus {
    border-color: rgba(201,168,76,.4);
    box-shadow: 0 0 0 3px rgba(201,168,76,.06);
  }
  .field-note {
    font-size: .68rem;
    color: rgba(138,138,154,.6);
    margin-top: .35rem;
    letter-spacing: .04em;
  }

  .btn-primary {
    width: 100%;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold2) 100%);
    border: none; border-radius: 1px;
    color: var(--noir);
    font-family: 'Jost', sans-serif;
    font-size: .73rem;
    font-weight: 500;
    letter-spacing: .22em;
    text-transform: uppercase;
    padding: 1rem;
    cursor: pointer;
    margin-top: .6rem;
    transition: opacity .2s, transform .15s;
  }
  .btn-primary:hover { opacity: .88; transform: translateY(-1px); }

  .eroare {
    background: rgba(192,57,43,.12);
    border: 1px solid rgba(192,57,43,.3);
    border-radius: 1px;
    color: #e07068;
    font-size: .8rem;
    padding: .75rem 1rem;
    margin-bottom: 1.4rem;
  }
  .succes {
    background: rgba(39,174,96,.1);
    border: 1px solid rgba(39,174,96,.3);
    border-radius: 1px;
    color: #58d68d;
    font-size: .85rem;
    padding: 1rem;
    margin-bottom: 1.4rem;
    line-height: 1.5;
  }
  .succes a {
    color: var(--gold);
    text-decoration: none;
    font-weight: 500;
  }
  .succes a:hover { text-decoration: underline; }

  @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:none; } }
</style>
</head>
<body>
<div class="bg"></div>
<div class="wrapper">
  <div class="card">

    <a href="index.php" class="back-link">Înapoi la autentificare</a>

    <div class="logo-row">
      <div class="logo-mark"><span>AE</span></div>
      <div class="logo-text">Auto<em>Elite</em></div>
    </div>

    <h2 class="card-title">Creare cont</h2>
    <p class="card-hint">Înregistrare utilizator nou</p>

    <?php if ($eroare): ?>
      <div class="eroare"><?= htmlspecialchars($eroare) ?></div>
    <?php endif; ?>

    <?php if ($succes): ?>
      <div class="succes">
        ✓ <?= htmlspecialchars($succes) ?><br><br>
        <a href="index.php">→ Mergeți la pagina de autentificare</a>
      </div>
    <?php endif; ?>

    <?php if (!$succes): ?>
    <form method="POST" action="register.php">

      <div class="field">
        <label for="username">Nume utilizator</label>
        <input type="text" id="username" name="username"
               placeholder="ex: ion_popescu"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               autocomplete="username">
        <p class="field-note">Minim 3 caractere, doar litere, cifre și "_"</p>
      </div>

      <div class="field">
        <label for="password1">Parolă</label>
        <input type="password" id="password1" name="password1"
               placeholder="Minim 6 caractere"
               autocomplete="new-password">
      </div>

      <div class="field">
        <label for="password2">Confirmare parolă</label>
        <input type="password" id="password2" name="password2"
               placeholder="Repetați parola"
               autocomplete="new-password">
      </div>

      <button type="submit" class="btn-primary">Creare cont</button>
    </form>
    <?php endif; ?>

  </div>
</div>
</body>
</html>
