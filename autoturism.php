<?php
session_start();

if (!isset($_SESSION['logat']) || $_SESSION['logat'] !== true) {
    header('Location: index.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username'] ?? 'Utilizator');
?>
<!DOCTYPE html>
<html lang="ro">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AutoElite — Novi Phantom IX</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@200;300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --noir:    #080810;
    --carbon:  #0e0e18;
    --steel:   #161624;
    --panel:   #12121e;
    --silver:  #7a7a8a;
    --chrome:  #b8b8c8;
    --white:   #f0f0f6;
    --gold:    #c9a84c;
    --gold2:   #e8c97a;
    --gold3:   #f0d98a;
    --accent:  #4a6fa5;
  }

  html { scroll-behavior: smooth; }

  body {
    background: var(--noir);
    font-family: 'Jost', sans-serif;
    color: var(--white);
    overflow-x: hidden;
  }

  /* ─── NAVBAR ─── */
  nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.2rem 3rem;
    background: rgba(8,8,16,.85);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(201,168,76,.08);
    animation: slideDown .6s ease both;
  }

  .nav-logo {
    display: flex; align-items: center; gap: .9rem;
    text-decoration: none;
  }
  .nav-emblem {
    width: 40px; height: 40px;
    border: 1.5px solid var(--gold);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
  }
  .nav-emblem span {
    font-family: 'Cormorant Garamond', serif;
    font-size: .75rem; font-weight: 600;
    letter-spacing: .1em; color: var(--gold);
  }
  .nav-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.3rem; font-weight: 300;
    color: var(--white);
  }
  .nav-name em { font-style: italic; color: var(--gold); }

  .nav-right {
    display: flex; align-items: center; gap: 2rem;
  }
  .nav-user {
    font-size: .72rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--silver);
  }
  .nav-user strong { color: var(--gold); font-weight: 400; }

  .btn-logout {
    border: 1px solid rgba(201,168,76,.25);
    border-radius: 1px;
    background: transparent;
    color: var(--gold);
    font-family: 'Jost', sans-serif;
    font-size: .68rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    padding: .55rem 1.2rem;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s, border-color .2s;
  }
  .btn-logout:hover { background: rgba(201,168,76,.08); border-color: rgba(201,168,76,.5); }

  /* ─── HERO ─── */
  .hero {
    min-height: 100vh;
    display: flex; flex-direction: column;
    justify-content: flex-end;
    padding: 0 0 6rem;
    position: relative;
    overflow: hidden;
  }

  /* SVG car illustration fills hero */
  .hero-visual {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
  }

  .car-svg-wrap {
    position: absolute;
    right: -4%;
    top: 50%;
    transform: translateY(-50%);
    width: 65%;
    opacity: 0;
    animation: carReveal 1.4s cubic-bezier(.22,1,.36,1) .5s both;
  }

  /* Dark gradient that frames the left content */
  .hero::before {
    content: '';
    position: absolute; inset: 0; z-index: 1;
    background: linear-gradient(
      105deg,
      rgba(8,8,16,1) 0%,
      rgba(8,8,16,.95) 28%,
      rgba(8,8,16,.55) 55%,
      transparent 75%
    );
  }
  .hero::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0; z-index: 1;
    height: 200px;
    background: linear-gradient(to top, var(--noir), transparent);
  }

  /* grid lines */
  .hero-grid {
    position: absolute; inset: 0;
    background-image:
      linear-gradient(rgba(201,168,76,.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(201,168,76,.025) 1px, transparent 1px);
    background-size: 80px 80px;
  }

  .hero-content {
    position: relative; z-index: 2;
    padding: 0 6rem;
    animation: fadeUp .9s ease .3s both;
  }

  .badge {
    display: inline-flex; align-items: center; gap: .6rem;
    border: 1px solid rgba(201,168,76,.3);
    border-radius: 100px;
    padding: .35rem 1rem;
    font-size: .65rem;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 1.6rem;
  }
  .badge::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--gold);
    animation: blink 2s ease-in-out infinite;
  }

  .hero-model {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(3.5rem, 7vw, 7rem);
    font-weight: 300;
    line-height: .9;
    letter-spacing: -.02em;
  }
  .hero-model em {
    display: block;
    font-style: italic;
    color: var(--gold);
  }

  .hero-tagline {
    margin-top: 1.5rem;
    font-size: clamp(.85rem, 1.2vw, 1rem);
    color: var(--silver);
    font-weight: 300;
    letter-spacing: .06em;
    max-width: 380px;
    line-height: 1.7;
  }

  .hero-stats {
    display: flex; gap: 2.5rem;
    margin-top: 3rem;
  }
  .stat-item {}
  .stat-val {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    font-weight: 300;
    color: var(--white);
    line-height: 1;
  }
  .stat-val span { font-size: 1rem; color: var(--gold); }
  .stat-label {
    font-size: .65rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--silver);
    margin-top: .3rem;
  }

  .hero-cta {
    margin-top: 3rem;
    display: flex; gap: 1rem; flex-wrap: wrap;
  }
  .cta-primary {
    background: linear-gradient(135deg, var(--gold), var(--gold2));
    border: none; border-radius: 1px;
    color: var(--noir);
    font-family: 'Jost', sans-serif;
    font-size: .72rem;
    font-weight: 500;
    letter-spacing: .22em;
    text-transform: uppercase;
    padding: 1rem 2.2rem;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .2s;
  }
  .cta-primary:hover { opacity: .85; }
  .cta-secondary {
    border: 1px solid rgba(201,168,76,.3);
    border-radius: 1px;
    color: var(--gold);
    font-family: 'Jost', sans-serif;
    font-size: .72rem;
    letter-spacing: .2em;
    text-transform: uppercase;
    padding: 1rem 2rem;
    text-decoration: none;
    transition: background .2s;
  }
  .cta-secondary:hover { background: rgba(201,168,76,.07); }

  /* ─── SPECS SECTION ─── */
  .section {
    padding: 7rem 6rem;
    position: relative;
  }

  .sec-label {
    font-size: .68rem;
    letter-spacing: .25em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 1rem;
  }
  .sec-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 300;
    line-height: 1.1;
    margin-bottom: 3.5rem;
  }
  .sec-title em { font-style: italic; color: var(--gold); }

  /* specs grid */
  .specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5px;
    background: rgba(201,168,76,.08);
    border: 1px solid rgba(201,168,76,.08);
  }
  .spec-card {
    background: var(--carbon);
    padding: 2rem 1.8rem;
    position: relative;
    transition: background .25s;
  }
  .spec-card:hover { background: var(--steel); }
  .spec-card::before {
    content: '';
    position: absolute; top: 0; left: 0;
    width: 2px; height: 0;
    background: var(--gold);
    transition: height .3s ease;
  }
  .spec-card:hover::before { height: 100%; }
  .spec-icon {
    font-size: 1.4rem;
    margin-bottom: 1rem;
    opacity: .7;
  }
  .spec-num {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.4rem;
    font-weight: 300;
    line-height: 1;
    color: var(--white);
  }
  .spec-num sub { font-size: 1rem; color: var(--gold); vertical-align: baseline; }
  .spec-name {
    font-size: .68rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--silver);
    margin-top: .5rem;
  }
  .spec-desc {
    font-size: .78rem;
    color: rgba(138,138,154,.6);
    margin-top: .3rem;
    line-height: 1.5;
  }

  /* ─── FEATURES ─── */
  .features-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
  }
  .feature-list { display: flex; flex-direction: column; gap: 1.5rem; }
  .feature-item {
    display: flex; gap: 1.5rem;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,.05);
    border-radius: 2px;
    transition: border-color .25s, background .25s;
  }
  .feature-item:hover {
    border-color: rgba(201,168,76,.2);
    background: rgba(201,168,76,.03);
  }
  .feature-icon {
    width: 48px; height: 48px; flex-shrink: 0;
    border: 1px solid rgba(201,168,76,.25);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
  }
  .feature-text h4 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.15rem;
    font-weight: 400;
    margin-bottom: .4rem;
  }
  .feature-text p {
    font-size: .82rem;
    color: var(--silver);
    line-height: 1.65;
    font-weight: 300;
  }

  /* right panel visual */
  .features-visual {
    background: var(--carbon);
    border: 1px solid rgba(201,168,76,.1);
    border-radius: 2px;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }
  .fv-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem;
    font-style: italic;
    color: var(--gold);
    margin-bottom: .5rem;
  }
  .fv-body {
    font-size: .85rem;
    color: var(--silver);
    line-height: 1.8;
    font-weight: 300;
  }
  .color-swatches {
    display: flex; gap: .8rem; flex-wrap: wrap;
    margin-top: 1rem;
  }
  .swatch {
    width: 40px; height: 40px; border-radius: 50%;
    cursor: pointer;
    position: relative;
    transition: transform .2s;
  }
  .swatch:hover { transform: scale(1.15); }
  .swatch::after {
    content: attr(data-name);
    position: absolute; bottom: -1.4rem; left: 50%;
    transform: translateX(-50%);
    font-size: .6rem;
    color: var(--silver);
    white-space: nowrap;
    opacity: 0;
    transition: opacity .2s;
  }
  .swatch:hover::after { opacity: 1; }

  .price-block {
    border-top: 1px solid rgba(201,168,76,.12);
    padding-top: 1.5rem;
  }
  .price-label { font-size: .65rem; letter-spacing: .2em; text-transform: uppercase; color: var(--silver); }
  .price-val {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.5rem; font-weight: 300;
    color: var(--white); margin-top: .3rem;
  }
  .price-val span { font-size: 1.1rem; color: var(--gold); }

  /* ─── FOOTER ─── */
  footer {
    border-top: 1px solid rgba(201,168,76,.08);
    padding: 2.5rem 6rem;
    display: flex; justify-content: space-between; align-items: center;
  }
  .footer-logo {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.1rem; font-weight: 300;
    color: var(--silver);
  }
  .footer-logo em { color: var(--gold); font-style: italic; }
  .footer-copy { font-size: .7rem; color: rgba(122,122,138,.5); letter-spacing: .08em; }

  /* ─── Animations ─── */
  @keyframes slideDown { from { transform:translateY(-100%); opacity:0; } to { transform:none; opacity:1; } }
  @keyframes fadeUp    { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:none; } }
  @keyframes carReveal { from { opacity:0; transform:translateY(-50%) translateX(80px); } to { opacity:1; transform:translateY(-50%) translateX(0); } }
  @keyframes blink     { 0%,100% { opacity:1; } 50% { opacity:.3; } }

  @media (max-width: 900px) {
    nav { padding: 1rem 1.5rem; }
    .hero-content { padding: 0 2rem; }
    .section { padding: 4rem 2rem; }
    .features-wrap { grid-template-columns: 1fr; }
    footer { flex-direction: column; gap: 1rem; text-align: center; padding: 2rem; }
    .car-svg-wrap { width: 90%; right: -5%; opacity: .25; }
    .hero::before {
      background: linear-gradient(to bottom, rgba(8,8,16,.9) 0%, rgba(8,8,16,.7) 100%);
    }
  }
</style>
</head>
<body>

<!-- NAVBAR -->
<nav>
  <a href="#" class="nav-logo">
    <div class="nav-emblem"><span>AE</span></div>
    <span class="nav-name">Auto<em>Elite</em></span>
  </a>
  <div class="nav-right">
    <span class="nav-user">Bun venit, <strong><?= $username ?></strong></span>
    <a href="logout.php" class="btn-logout">Deconectare</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-grid"></div>

  <!-- Inline SVG Car Illustration -->
  <div class="hero-visual">
    <div class="car-svg-wrap">
      <svg viewBox="0 0 900 400" xmlns="http://www.w3.org/2000/svg" fill="none">
        <!-- Ground shadow -->
        <ellipse cx="450" cy="370" rx="370" ry="22" fill="rgba(0,0,0,0.5)"/>

        <!-- Body lower -->
        <path d="M80 280 Q90 320 160 330 L740 330 Q810 320 820 280 L820 250 L80 250 Z"
              fill="#1a1a2a" stroke="rgba(201,168,76,0.15)" stroke-width="1"/>

        <!-- Body main -->
        <path d="M140 250 L200 160 Q240 120 320 115 L580 115 Q660 118 720 155 L760 250 Z"
              fill="#141422" stroke="rgba(201,168,76,0.2)" stroke-width="1"/>

        <!-- Roof -->
        <path d="M240 160 Q280 100 360 90 L540 90 Q620 92 660 155 L240 160 Z"
              fill="#0e0e1c" stroke="rgba(201,168,76,0.1)" stroke-width="1"/>

        <!-- Windows -->
        <path d="M255 158 Q285 108 360 98 L440 96 L440 158 Z"
              fill="rgba(74,111,165,0.25)" stroke="rgba(201,168,76,0.15)" stroke-width="1"/>
        <path d="M455 96 L540 96 Q610 100 650 155 L455 158 Z"
              fill="rgba(74,111,165,0.22)" stroke="rgba(201,168,76,0.15)" stroke-width="1"/>
        <!-- window divider -->
        <line x1="448" y1="95" x2="448" y2="160" stroke="rgba(201,168,76,0.3)" stroke-width="2"/>

        <!-- Hood -->
        <path d="M140 250 L200 160 L230 165 L180 250 Z"
              fill="#121220" stroke="rgba(201,168,76,0.1)" stroke-width="1"/>

        <!-- Trunk -->
        <path d="M760 250 L720 155 L695 165 L730 250 Z"
              fill="#121220" stroke="rgba(201,168,76,0.1)" stroke-width="1"/>

        <!-- Gold accent stripe -->
        <path d="M180 250 L220 170 L670 168 L720 250"
              fill="none" stroke="rgba(201,168,76,0.5)" stroke-width="1.5"/>

        <!-- Side door lines -->
        <line x1="350" y1="165" x2="340" y2="250" stroke="rgba(201,168,76,0.1)" stroke-width="1"/>
        <line x1="560" y1="165" x2="560" y2="250" stroke="rgba(201,168,76,0.1)" stroke-width="1"/>

        <!-- Door handle front -->
        <rect x="295" y="210" width="35" height="6" rx="3" fill="rgba(201,168,76,0.4)"/>
        <!-- Door handle rear -->
        <rect x="530" y="210" width="35" height="6" rx="3" fill="rgba(201,168,76,0.4)"/>

        <!-- Front wheel arch -->
        <path d="M140 250 Q145 300 220 310 Q300 318 305 250 Z"
              fill="#0e0e1c" stroke="rgba(201,168,76,0.12)" stroke-width="1"/>
        <!-- Rear wheel arch -->
        <path d="M595 250 Q600 305 675 312 Q755 318 760 250 Z"
              fill="#0e0e1c" stroke="rgba(201,168,76,0.12)" stroke-width="1"/>

        <!-- Front wheel -->
        <circle cx="220" cy="312" r="52" fill="#0d0d18" stroke="rgba(201,168,76,0.3)" stroke-width="2"/>
        <circle cx="220" cy="312" r="36" fill="#111120" stroke="rgba(201,168,76,0.15)" stroke-width="1"/>
        <circle cx="220" cy="312" r="18" fill="rgba(201,168,76,0.08)" stroke="rgba(201,168,76,0.5)" stroke-width="2"/>
        <circle cx="220" cy="312" r="5" fill="var(--gold)"/>
        <!-- spoke lines front -->
        <line x1="220" y1="278" x2="220" y2="294" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="220" y1="330" x2="220" y2="346" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="186" y1="312" x2="202" y2="312" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="238" y1="312" x2="254" y2="312" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="196" y1="288" x2="207" y2="299" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="233" y1="325" x2="244" y2="336" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="244" y1="288" x2="233" y2="299" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="207" y1="325" x2="196" y2="336" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>

        <!-- Rear wheel -->
        <circle cx="672" cy="312" r="52" fill="#0d0d18" stroke="rgba(201,168,76,0.3)" stroke-width="2"/>
        <circle cx="672" cy="312" r="36" fill="#111120" stroke="rgba(201,168,76,0.15)" stroke-width="1"/>
        <circle cx="672" cy="312" r="18" fill="rgba(201,168,76,0.08)" stroke="rgba(201,168,76,0.5)" stroke-width="2"/>
        <circle cx="672" cy="312" r="5" fill="var(--gold)"/>
        <!-- spoke lines rear -->
        <line x1="672" y1="278" x2="672" y2="294" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="672" y1="330" x2="672" y2="346" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="638" y1="312" x2="654" y2="312" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="690" y1="312" x2="706" y2="312" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="648" y1="288" x2="659" y2="299" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="685" y1="325" x2="696" y2="336" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="696" y1="288" x2="685" y2="299" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>
        <line x1="659" y1="325" x2="648" y2="336" stroke="rgba(201,168,76,0.4)" stroke-width="2"/>

        <!-- Headlight -->
        <path d="M148 220 L175 200 L185 218 L155 232 Z" fill="rgba(232,201,122,0.15)" stroke="rgba(201,168,76,0.5)" stroke-width="1"/>
        <path d="M150 224 L175 206 L183 220 L157 230 Z" fill="rgba(232,201,122,0.25)"/>
        <!-- DRL -->
        <line x1="150" y1="234" x2="178" y2="228" stroke="rgba(201,168,76,0.8)" stroke-width="2.5" stroke-linecap="round"/>

        <!-- Taillight -->
        <path d="M742 218 L762 200 L772 215 L752 234 Z" fill="rgba(180,30,30,0.2)" stroke="rgba(200,60,60,0.5)" stroke-width="1"/>
        <line x1="744" y1="230" x2="770" y2="222" stroke="rgba(200,60,60,0.8)" stroke-width="2.5" stroke-linecap="round"/>

        <!-- Grille -->
        <rect x="148" y="255" width="40" height="22" rx="2" fill="rgba(201,168,76,0.06)" stroke="rgba(201,168,76,0.25)" stroke-width="1"/>
        <line x1="148" y1="263" x2="188" y2="263" stroke="rgba(201,168,76,0.2)" stroke-width="1"/>
        <line x1="148" y1="271" x2="188" y2="271" stroke="rgba(201,168,76,0.2)" stroke-width="1"/>
        <line x1="161" y1="255" x2="161" y2="277" stroke="rgba(201,168,76,0.2)" stroke-width="1"/>
        <line x1="174" y1="255" x2="174" y2="277" stroke="rgba(201,168,76,0.2)" stroke-width="1"/>

        <!-- Logo badge front -->
        <circle cx="195" cy="253" r="8" fill="rgba(201,168,76,0.1)" stroke="rgba(201,168,76,0.6)" stroke-width="1.5"/>
        <!-- Logo badge rear -->
        <circle cx="705" cy="253" r="8" fill="rgba(201,168,76,0.1)" stroke="rgba(201,168,76,0.6)" stroke-width="1.5"/>

        <!-- Exhaust -->
        <ellipse cx="745" cy="325" rx="10" ry="5" fill="#0a0a14" stroke="rgba(201,168,76,0.2)" stroke-width="1"/>
        <ellipse cx="755" cy="325" rx="10" ry="5" fill="#0a0a14" stroke="rgba(201,168,76,0.2)" stroke-width="1"/>

        <!-- reflection line on body -->
        <path d="M210 185 Q350 170 590 172 Q660 174 700 188"
              fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="3" stroke-linecap="round"/>
      </svg>
    </div>
  </div>

  <div class="hero-content">
    <div class="badge">Nou 2025 — Ediție Limitată</div>
    <h1 class="hero-model">
      Phantom<br>
      <em>IX</em>
    </h1>
    <p class="hero-tagline">
      O simfonie de putere și eleganță — redefinind conceptul de lux
      în era mobilității avansate.
    </p>

    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-val">0–100<span> km/h</span></div>
        <div class="stat-label">3.2 secunde</div>
      </div>
      <div class="stat-item">
        <div class="stat-val">680<span> CP</span></div>
        <div class="stat-label">Putere maximă</div>
      </div>
      <div class="stat-item">
        <div class="stat-val">340<span> km/h</span></div>
        <div class="stat-label">Viteză maximă</div>
      </div>
    </div>

    <div class="hero-cta">
      <a href="#specs" class="cta-primary">Specificații tehnice</a>
      <a href="#features" class="cta-secondary">Caracteristici</a>
    </div>
  </div>
</section>

<!-- SPECS -->
<section class="section" id="specs" style="background: var(--carbon);">
  <p class="sec-label">Inginerie de excepție</p>
  <h2 class="sec-title">Specificații <em>Tehnice</em></h2>

  <div class="specs-grid">
    <div class="spec-card">
      <div class="spec-icon">⚡</div>
      <div class="spec-num">680<sub>CP</sub></div>
      <div class="spec-name">Putere</div>
      <div class="spec-desc">Motor V12 biturbo cu injecție directă</div>
    </div>
    <div class="spec-card">
      <div class="spec-icon">🏎</div>
      <div class="spec-num">3.2<sub>s</sub></div>
      <div class="spec-name">0–100 km/h</div>
      <div class="spec-desc">Accelerație de categorie supercar</div>
    </div>
    <div class="spec-card">
      <div class="spec-icon">🌀</div>
      <div class="spec-num">920<sub>Nm</sub></div>
      <div class="spec-name">Cuplu maxim</div>
      <div class="spec-desc">Disponibil de la 1.800 RPM</div>
    </div>
    <div class="spec-card">
      <div class="spec-icon">🛞</div>
      <div class="spec-num">4<sub>WD</sub></div>
      <div class="spec-name">Tracțiune integrală</div>
      <div class="spec-desc">Sistem adaptiv cu distribuție variabilă</div>
    </div>
    <div class="spec-card">
      <div class="spec-icon">🔋</div>
      <div class="spec-num">9.2<sub>l/100</sub></div>
      <div class="spec-name">Consum mixt</div>
      <div class="spec-desc">Motor eficient cu sistem start-stop</div>
    </div>
    <div class="spec-card">
      <div class="spec-icon">📡</div>
      <div class="spec-num">L5<sub>AD</sub></div>
      <div class="spec-name">Autonomie</div>
      <div class="spec-desc">Pilot automat de nivel 5 — ready</div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="section" id="features">
  <p class="sec-label">Dotări de top</p>
  <h2 class="sec-title">Experiență <em>Redefinită</em></h2>

  <div class="features-wrap">
    <div class="feature-list">
      <div class="feature-item">
        <div class="feature-icon">🎵</div>
        <div class="feature-text">
          <h4>Sistem Audio Meridian 3D</h4>
          <p>24 de difuzoare cu tehnologie surround tridimensional, cu calibrare automată a acusticii în timp real.</p>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">🛡</div>
        <div class="feature-text">
          <h4>SafeGuard AI Pro</h4>
          <p>Sistem de asistență avansată cu 12 senzori LiDAR, 8 camere 4K și radar de 360° pentru siguranță maximă.</p>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">❄️</div>
        <div class="feature-text">
          <h4>Climatronic 5 Zone</h4>
          <p>Climatizare independentă pentru 5 zone, cu purificare HEPA și ionizare negativă pentru aer interior perfect.</p>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">💺</div>
        <div class="feature-text">
          <h4>Scaune Massage Nappa</h4>
          <p>Piele Nappa ventilată cu funcție masaj cu 16 puncte, memorie pentru 3 profiluri, reglaj electric pe 20 axe.</p>
        </div>
      </div>
    </div>

    <div class="features-visual">
      <div>
        <p class="fv-title">Culori disponibile</p>
        <p class="fv-body">Alegeți dintre 12 culori exclusive, inclusiv 4 nuanțe speciale disponibile doar pentru ediția Phantom IX.</p>
        <div class="color-swatches">
          <div class="swatch" data-name="Obsidian" style="background:linear-gradient(135deg,#1a1a1a,#2d2d2d);border:1px solid rgba(255,255,255,.1)"></div>
          <div class="swatch" data-name="Ivory" style="background:linear-gradient(135deg,#f5f0e8,#e8e0d0);border:1px solid rgba(0,0,0,.1)"></div>
          <div class="swatch" data-name="Bordeaux" style="background:linear-gradient(135deg,#5c1a1a,#8b2020)"></div>
          <div class="swatch" data-name="Arctic" style="background:linear-gradient(135deg,#b0c8d8,#8aafc4)"></div>
          <div class="swatch" data-name="Racing" style="background:linear-gradient(135deg,#1a3a1a,#2d5a2d)"></div>
          <div class="swatch" data-name="Gold" style="background:linear-gradient(135deg,var(--gold),var(--gold2))"></div>
        </div>
      </div>

      <div class="price-block">
        <p class="price-label">Preț de bază</p>
        <div class="price-val"><span>€</span> 189.900</div>
        <p style="font-size:.75rem;color:var(--silver);margin-top:.5rem;font-weight:300;">
          Prețul include TVA · Livrare estimată: T2 2025
        </p>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <span class="footer-logo">Auto<em>Elite</em> — Phantom IX</span>
  <span class="footer-copy">© 2025 AutoElite S.R.L. · Toate drepturile rezervate</span>
</footer>

</body>
</html>
