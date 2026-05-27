<?php /* salle_privee.php — Page de présentation des salles privées CinéCrown */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Salle Privée — CinéCrown</title>
<link rel="stylesheet" href="../index.css">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Josefin+Sans:wght@300;400;600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box}

/* ── HERO ── */
.hero{position:relative;min-height:480px;display:flex;align-items:center;justify-content:center;text-align:center;overflow:hidden;background:#0a0a0a}
.hero-bg{position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(184,134,11,.18) 0%,rgba(10,10,10,1) 70%)}
.hero-lines{position:absolute;inset:0;opacity:.06;width:100%;height:100%}
.hero-content{position:relative;z-index:2;padding:70px 40px}
.hero-tag{display:inline-block;border:1px solid rgba(224,192,104,.4);color:#e0c068;font-size:.72rem;letter-spacing:3px;text-transform:uppercase;padding:6px 18px;border-radius:20px;margin-bottom:28px;font-family:'Josefin Sans',sans-serif}
.hero h1{font-family:'Cormorant Garamond',serif;font-size:3.8rem;color:#f5f0e0;font-weight:700;line-height:1.1;margin-bottom:22px}
.hero h1 em{color:#e0c068;font-style:normal}
.hero-sub{color:#888;font-size:1rem;letter-spacing:1px;max-width:500px;margin:0 auto 36px;line-height:1.9;font-weight:300;font-family:'Josefin Sans',sans-serif}
.btn-hero{display:inline-block;background:linear-gradient(135deg,#b8860b,#e0c068,#b8860b);background-size:200%;color:#0a0a0a;font-family:'Josefin Sans',sans-serif;font-size:.88rem;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;padding:16px 42px;border-radius:40px;text-decoration:none;transition:background-position .4s,transform .2s}
.btn-hero:hover{background-position:right center;transform:translateY(-2px)}
.gold-divider{display:flex;align-items:center;justify-content:center;gap:12px;margin:24px 0 0;opacity:.35}
.gold-divider span{width:40px;height:1px;background:#e0c068;display:inline-block}

/* ── BANDE OR ── */
.gold-line{height:1px;background:linear-gradient(90deg,transparent,#b8860b,#e0c068,#b8860b,transparent)}

/* ── STATS ── */
.stats{background:#111;border-bottom:1px solid #1e1e1e;display:flex;justify-content:center}
.stat{flex:1;text-align:center;padding:28px 20px;border-right:1px solid #1e1e1e}
.stat:last-child{border-right:none}
.stat-num{font-family:'Cormorant Garamond',serif;font-size:2.4rem;color:#e0c068;font-weight:700;line-height:1}
.stat-label{font-size:.72rem;color:#555;letter-spacing:1.5px;text-transform:uppercase;margin-top:6px;font-family:'Josefin Sans',sans-serif}

/* ── SECTION SALLES ── */
.salles-section{padding:70px 50px}
.section-header{text-align:center;margin-bottom:55px}
.section-tag{font-size:.72rem;letter-spacing:3px;text-transform:uppercase;color:#7a5c00;margin-bottom:14px;display:block;font-family:'Josefin Sans',sans-serif}
.section-title{font-family:'Cormorant Garamond',serif;font-size:2.8rem;color:#1a1200;font-weight:700;line-height:1.2;margin-bottom:16px}
.section-title em{color:#b8860b;font-style:normal}
.section-desc{color:#5a4800;font-size:.95rem;max-width:520px;margin:0 auto;line-height:1.9;font-weight:300;font-family:'Josefin Sans',sans-serif}

/* ── CARDS ── */
.salles-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:24px;max-width:900px;margin:0 auto}
.salle-card{background:#0a0a0a;border-radius:18px;overflow:hidden;position:relative;transition:transform .3s,box-shadow .3s;border:1px solid #1a1a1a}
.salle-card:hover{transform:translateY(-6px);box-shadow:0 20px 50px rgba(0,0,0,.4),0 0 0 1px rgba(224,192,104,.2)}
.card-visual{height:180px;position:relative;display:flex;align-items:center;justify-content:center}
.cv-royale{background:linear-gradient(135deg,#1a1000,#3d2800,#1a1000)}
.cv-prestige{background:linear-gradient(135deg,#0a0a1a,#1a1530,#0a0a1a)}
.cv-crown{background:linear-gradient(135deg,#0a1a0a,#152a10,#0a1a0a)}
.cv-mini{background:linear-gradient(135deg,#1a0a1a,#2a1030,#1a0a1a)}
.card-icon{font-size:4rem;position:relative;z-index:2}
.card-badge{position:absolute;top:14px;right:14px;background:rgba(224,192,104,.15);border:1px solid rgba(224,192,104,.3);color:#e0c068;font-size:.68rem;letter-spacing:1.5px;text-transform:uppercase;padding:4px 12px;border-radius:12px;font-family:'Josefin Sans',sans-serif}
.card-dots{position:absolute;inset:0;opacity:.05;background-image:radial-gradient(circle,#e0c068 1px,transparent 1px);background-size:20px 20px}
.card-body{padding:24px}
.card-name{font-family:'Cormorant Garamond',serif;font-size:1.5rem;color:#e0c068;font-weight:700;margin-bottom:6px}
.card-cap{font-size:.8rem;color:#555;letter-spacing:1px;text-transform:uppercase;margin-bottom:16px;font-family:'Josefin Sans',sans-serif}
.card-features{list-style:none;padding:0;margin-bottom:20px}
.card-features li{display:flex;align-items:center;gap:10px;font-size:.85rem;color:#888;margin-bottom:10px;padding-bottom:10px;border-bottom:1px solid #141414;font-family:'Josefin Sans',sans-serif}
.card-features li:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
.feat-dot{width:6px;height:6px;border-radius:50%;background:#e0c068;flex-shrink:0;opacity:.7}
.card-price{display:flex;align-items:baseline;gap:5px}
.price-from{font-size:.72rem;color:#444;letter-spacing:1px;text-transform:uppercase;font-family:'Josefin Sans',sans-serif}
.price-num{font-family:'Cormorant Garamond',serif;font-size:1.8rem;color:#e0c068;font-weight:700}
.price-unit{font-size:.75rem;color:#555;font-family:'Josefin Sans',sans-serif}
.card-cta{margin-top:16px;width:100%;padding:12px;background:transparent;border:1px solid rgba(224,192,104,.3);border-radius:8px;color:#e0c068;font-family:'Josefin Sans',sans-serif;font-size:.8rem;font-weight:600;letter-spacing:2px;text-transform:uppercase;cursor:pointer;transition:.3s}
.card-cta:hover{background:rgba(224,192,104,.1);border-color:#e0c068}

/* ── CTA FINALE ── */
.cta-section{background:#0a0a0a;padding:70px 50px;text-align:center;position:relative;overflow:hidden}
.cta-section::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(184,134,11,.1),transparent 70%);pointer-events:none}
.cta-title{font-family:'Cormorant Garamond',serif;font-size:3rem;color:#f5f0e0;font-weight:700;margin-bottom:16px}
.cta-title em{color:#e0c068;font-style:normal}
.cta-sub{color:#666;font-size:.95rem;max-width:480px;margin:0 auto 40px;line-height:1.9;font-family:'Josefin Sans',sans-serif;font-weight:300}
.cta-feats{display:flex;justify-content:center;gap:40px;margin-bottom:40px;flex-wrap:wrap}
.cta-feat{text-align:center}
.cta-feat-icon{font-size:1.8rem;margin-bottom:8px;display:block}
.cta-feat-text{font-size:.75rem;color:#555;letter-spacing:1px;text-transform:uppercase;font-family:'Josefin Sans',sans-serif}
.btn-cta{display:inline-block;background:linear-gradient(135deg,#b8860b,#e0c068);color:#0a0a0a;font-family:'Josefin Sans',sans-serif;font-size:.88rem;font-weight:700;letter-spacing:2.5px;text-transform:uppercase;padding:18px 50px;border-radius:40px;text-decoration:none;transition:transform .2s,box-shadow .3s;margin-right:16px}
.btn-cta:hover{transform:translateY(-2px);box-shadow:0 12px 35px rgba(184,134,11,.35)}
.btn-cta-sec{display:inline-block;border:1px solid rgba(224,192,104,.3);color:#e0c068;font-family:'Josefin Sans',sans-serif;font-size:.88rem;font-weight:600;letter-spacing:2px;text-transform:uppercase;padding:17px 35px;border-radius:40px;text-decoration:none;transition:.3s}
.btn-cta-sec:hover{border-color:#e0c068;background:rgba(224,192,104,.08)}
</style>
</head>
<body>

<?php require 'index_client.php'; ?>

<!-- ══ CONTENU PRINCIPAL ══ -->
<div style="margin-left:260px">

  <!-- HERO -->
  <section class="hero">
    <div class="hero-bg"></div>
    
    <div class="hero-content">
      <span class="hero-tag">Privatisation exclusive</span>
      <h1>Votre événement mérite<br>une <em>scène royale</em></h1>
      <p class="hero-sub">Réservez notre salle entière pour une nuit inoubliable — projection privée, anniversaire, gala d'entreprise ou soirée prestige.</p>
      <a href="../envoie_messages.php" class="btn-hero">Envoyer ma demande</a>
    </div>
  </section>

  <div class="gold-line"></div>

  <!-- STATS -->
  <div class="stats">

    <div class="stat"><div class="stat-num">200</div><div class="stat-label">Places maximum</div></div>
    <div class="stat"><div class="stat-num">24h</div><div class="stat-label">Réponse garantie</div></div>
    <div class="stat"><div class="stat-num">2</div><div class="stat-label">Villes — DLA · YDE</div></div>
  </div>

  <div class="gold-line"></div>

  <!-- SALLES -->
  <section class="salles-section">
    <div class="section-header">
      <span class="section-tag">Nos espaces</span>
      <h2 class="section-title">Choisissez votre <em>salle de rêve</em></h2>
      <p class="section-desc">Quatre écrans d'exception, conçus pour magnifier chaque instant. Du gala fastueux à la projection intime — nous avons votre cadre.</p>
    </div>

    <div class="salles-grid">

      <div class="salle-card">
        <div class="card-visual cv-royale">
          <div class="card-dots"></div>
         
          <span class="card-badge">★ Coup de cœur</span>
        </div>
        <div class="card-body">
          <div class="card-name">Salle Royale</div>
          <div class="card-cap">Jusqu'à 200 personnes</div>
          <ul class="card-features">
            <li><span class="feat-dot"></span>Écran géant 8K cinématique</li>
            <li><span class="feat-dot"></span>Son Dolby Atmos immersif</li>
            <li><span class="feat-dot"></span>Sièges VIP inclinables</li>
            <li><span class="feat-dot"></span>Scène & podium intégrés</li>
          </ul>
          <div class="card-price">
            <span class="price-from">À partir de</span>
            <span class="price-num">350 000</span>
            <span class="price-unit">FCFA / soirée</span>
          </div>
          </div>
      </div>

      <div class="salle-card">
        <div class="card-visual cv-prestige">
          <div class="card-dots"></div>
          <span class="card-icon"><img src="../images/cinema.png" alt=""></span>
        </div>
        <div class="card-body">
          <div class="card-name">Salle Prestige</div>
          <div class="card-cap">Jusqu'à 120 personnes</div>
          <ul class="card-features">
            <li><span class="feat-dot"></span>Écran 4K & son surround</li>
            <li><span class="feat-dot"></span>Décor luxe personnalisable</li>
            <li><span class="feat-dot"></span>Bar & service à la salle</li>
            <li><span class="feat-dot"></span>Éclairage d'ambiance scénico</li>
          </ul>
          <div class="card-price">
            <span class="price-from">À partir de</span>
            <span class="price-num">220 000</span>
            <span class="price-unit">FCFA / soirée</span>
          </div>
          </div>
      </div>

      <div class="salle-card">
        <div class="card-visual cv-crown">
          <div class="card-dots"></div>
          <span class="card-icon">👑</span>
        </div>
        <div class="card-body">
          <div class="card-name">Salle Crown</div>
          <div class="card-cap">Jusqu'à 80 personnes</div>
          <ul class="card-features">
            <li><span class="feat-dot"></span>Cadre intimiste haut de gamme</li>
            <li><span class="feat-dot"></span>Projection 4K laser</li>
            <li><span class="feat-dot"></span>Acoustique traitée</li>
            <li><span class="feat-dot"></span>Idéal famille & amis proches</li>
          </ul>
          <div class="card-price">
            <span class="price-from">À partir de</span>
            <span class="price-num">130 000</span>
            <span class="price-unit">FCFA / soirée</span>
          </div>
         </div>
      </div>

      <div class="salle-card">
        <div class="card-visual cv-mini">
          <div class="card-dots"></div>
          <span class="card-icon">🎞️</span>
          <span class="card-badge">Nouveau</span>
        </div>
        <div class="card-body">
          <div class="card-name">Salle Mini</div>
          <div class="card-cap">Jusqu'à 50 personnes</div>
          <ul class="card-features">
            <li><span class="feat-dot"></span>Projection HD premium</li>
            <li><span class="feat-dot"></span>Confort cosy & chaleureux</li>
            <li><span class="feat-dot"></span>Tarif accessible & flexible</li>
            <li><span class="feat-dot"></span>Parfait pour screening privé</li>
          </ul>
          <div class="card-price">
            <span class="price-from">À partir de</span>
            <span class="price-num">75 000</span>
            <span class="price-unit">FCFA / soirée</span>
          </div>
         </div>
      </div>

    </div>
  </section>

  <div class="gold-line"></div>

  </section>

</div><!-- /main -->
</body>
</html>