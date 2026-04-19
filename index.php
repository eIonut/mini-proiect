<?php
session_start();

// Daca utilizatorul e deja logat, redirectam la pagina auto
if (isset($_SESSION['logat']) && $_SESSION['logat'] === true) {
    header('Location: autoturism.php');
    exit;
}

$eroare = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actiune']) && $_POST['actiune'] === 'login') {
    $user_input = trim($_POST['username'] ?? '');
    $pass_input = trim($_POST['password'] ?? '');

    if (empty($user_input) || empty($pass_input)) {
        $eroare = 'Completati toate câmpurile!';
    } else {
        // Citim fisierele
        $useri   = file('user.txt',  FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $parole  = file('passw.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $gasit = false;
        foreach ($useri as $i => $u) {
            if (trim($u) === $user_input && isset($parole[$i]) && trim($parole[$i]) === $pass_input) {
                $gasit = true;
                break;
            }
        }

        if ($gasit) {
            $_SESSION['logat']    = true;
            $_SESSION['username'] = $user_input;
            header('Location: autoturism.php');
            exit;
        } else {
            $eroare = 'Utilizator sau parolă incorectă!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AutoElite - Autentificare</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300&family=Jost:wght@200;300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --noir:    #0a0a0f;
    --carbon:  #111118;
    --steel:   #1c1c28;
    --silver:  #8a8a9a;
    --chrome:  #c8c8d8;
    --white:   #f0f0f5;
    --gold:    #c9a84c;
    --gold2:   #e8c97a;
    --red:     #c0392b;
  }

  html, body {
    height: 100%;
    background: var(--noir);
    font-family: 'Jost', sans-serif;
    color: var(--white);
    overflow: hidden;
  }

  /* ── Background ── */
  .bg {
    position: fixed; inset: 0; z-index: 0;
    background:
      radial-gradient(ellipse 80% 60% at 70% 50%, #1a1a2e 0%, transparent 60%),
      radial-gradient(ellipse 50% 80% at 10% 80%, #12121f 0%, transparent 55%),
      var(--noir);
  }
  .bg::after {
    content: '';
    position: absolute; inset: 0;
    background-image:
      linear-gradient(rgba(201,168,76,.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(201,168,76,.04) 1px, transparent 1px);
    background-size: 60px 60px;
  }

  /* ── Layout ── */
  .wrapper {
    position: relative; z-index: 1;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
  }

  /* ── Left panel ── */
  .brand-panel {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 3rem 4rem;
    width: 420px;
    animation: fadeLeft .9s ease both;
  }

  .logo-mark {
    width: 56px; height: 56px;
    border: 2px solid var(--gold);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 2rem;
    position: relative;
  }
  .logo-mark::before {
    content: 'AE';
    font-family: 'Cormorant Garamond', serif;
    font-size: .95rem;
    font-weight: 600;
    letter-spacing: .15em;
    color: var(--gold);
  }
  .logo-mark::after {
    content: '';
    position: absolute; inset: -6px;
    border-radius: 50%;
    border: 1px solid rgba(201,168,76,.2);
  }

  .brand-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 3.5rem;
    font-weight: 300;
    line-height: 1.05;
    color: var(--white);
    letter-spacing: -.01em;
  }
  .brand-title em {
    font-style: italic;
    color: var(--gold);
  }

  .brand-sub {
    margin-top: 1.2rem;
    font-size: .78rem;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--silver);
  }

  .divider {
    width: 48px; height: 1px;
    background: linear-gradient(90deg, var(--gold), transparent);
    margin: 2rem 0;
  }

  .brand-desc {
    font-size: .88rem;
    line-height: 1.75;
    color: var(--silver);
    font-weight: 300;
    max-width: 300px;
  }

  /* ── Card ── */
  .card {
    background: var(--carbon);
    border: 1px solid rgba(201,168,76,.12);
    border-radius: 2px;
    padding: 3rem 2.8rem;
    width: 380px;
    box-shadow: 0 40px 80px rgba(0,0,0,.6), inset 0 1px 0 rgba(255,255,255,.04);
    animation: fadeRight .9s ease both;
    position: relative;
    overflow: hidden;
  }
  .card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
  }

  .card-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.6rem;
    font-weight: 300;
    color: var(--white);
    margin-bottom: .4rem;
  }
  .card-hint {
    font-size: .75rem;
    color: var(--silver);
    letter-spacing: .1em;
    text-transform: uppercase;
    margin-bottom: 2rem;
  }

  .field { margin-bottom: 1.4rem; }
  .field label {
    display: block;
    font-size: .7rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--silver);
    margin-bottom: .55rem;
  }
  .field input {
    width: 100%;
    background: var(--steel);
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 1px;
    color: var(--white);
    font-family: 'Jost', sans-serif;
    font-size: .92rem;
    font-weight: 300;
    padding: .85rem 1.1rem;
    transition: border-color .25s, box-shadow .25s;
    outline: none;
    letter-spacing: .04em;
  }
  .field input::placeholder { color: rgba(138,138,154,.4); }
  .field input:focus {
    border-color: rgba(201,168,76,.4);
    box-shadow: 0 0 0 3px rgba(201,168,76,.06);
  }

  .btn-primary {
    width: 100%;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold2) 100%);
    border: none;
    border-radius: 1px;
    color: var(--noir);
    font-family: 'Jost', sans-serif;
    font-size: .75rem;
    font-weight: 500;
    letter-spacing: .22em;
    text-transform: uppercase;
    padding: 1rem;
    cursor: pointer;
    margin-top: .4rem;
    transition: opacity .2s, transform .15s;
  }
  .btn-primary:hover { opacity: .88; transform: translateY(-1px); }
  .btn-primary:active { transform: translateY(0); }

  .separator {
    display: flex; align-items: center; gap: 1rem;
    margin: 1.6rem 0;
  }
  .separator::before, .separator::after {
    content: ''; flex: 1; height: 1px;
    background: rgba(255,255,255,.07);
  }
  .separator span { font-size: .68rem; color: var(--silver); letter-spacing: .12em; }

  .btn-secondary {
    display: block; width: 100%; text-align: center;
    border: 1px solid rgba(201,168,76,.25);
    border-radius: 1px;
    color: var(--gold);
    font-family: 'Jost', sans-serif;
    font-size: .72rem;
    font-weight: 400;
    letter-spacing: .2em;
    text-transform: uppercase;
    padding: .9rem;
    text-decoration: none;
    transition: background .2s, border-color .2s;
  }
  .btn-secondary:hover {
    background: rgba(201,168,76,.07);
    border-color: rgba(201,168,76,.5);
  }

  .eroare {
    background: rgba(192,57,43,.12);
    border: 1px solid rgba(192,57,43,.3);
    border-radius: 1px;
    color: #e07068;
    font-size: .8rem;
    padding: .75rem 1rem;
    margin-bottom: 1.4rem;
    letter-spacing: .03em;
  }

  /* ── Animations ── */
  @keyframes fadeLeft  { from { opacity:0; transform:translateX(-30px); } to { opacity:1; transform:none; } }
  @keyframes fadeRight { from { opacity:0; transform:translateX( 30px); } to { opacity:1; transform:none; } }

  @media (max-width: 780px) {
    .brand-panel { display: none; }
    body { overflow: auto; }
    .wrapper { padding: 2rem 1rem; }
    .card { width: 100%; max-width: 400px; }
  }
</style>
</head>
<body>
<div class="bg"></div>
<div class="wrapper">

  <div class="brand-panel">
    <div class="logo-mark"></div>
    <h1 class="brand-title">Auto<br><em>Elite</em></h1>
    <p class="brand-sub">Showroom &amp; Experience</p>
    <div class="divider"></div>
    <p class="brand-desc">
      Descoperă cele mai rafinate autoturisme ale momentului.
      Autentifică-te pentru acces exclusiv la prezentările noastre premium.
    </p>
  </div>

  <div class="card">
    <h2 class="card-title">Bun venit</h2>
    <p class="card-hint">Autentificare cont</p>

    <?php if ($eroare): ?>
      <div class="eroare"><?= htmlspecialchars($eroare) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php">
      <input type="hidden" name="actiune" value="login">

      <div class="field">
        <label for="username">Utilizator</label>
        <input type="text" id="username" name="username"
               placeholder="Introduceți utilizatorul"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               autocomplete="username">
      </div>

      <div class="field">
        <label for="password">Parolă</label>
        <input type="password" id="password" name="password"
               placeholder="Introduceți parola"
               autocomplete="current-password">
      </div>

      <button type="submit" class="btn-primary">Autentificare</button>
    </form>

    <div class="separator"><span>sau</span></div>

    <a href="register.php" class="btn-secondary">Creează cont nou</a>
  </div>

</div>
</body>
</html>
