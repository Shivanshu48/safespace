<?php include "backend/db_connect.php";  // starts the session?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>SafeSpace — Home</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" href="assets/icons/favicon.png">
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="logo">
        <div class="mark">SS</div>
        <div>
          <div style="font-weight:700">SafeSpace</div>
          <div style="font-size:12px;color:var(--muted)">Real-Time Safety Tools</div>
        </div>
      </div>

      <nav class="nav" aria-label="Main navigation">
        <a href="/index.php">Home</a>
        <a href="/map.php">Safety Map</a>
        <a href="/report.php">Report</a>
        <a href="/sos.php">SOS</a>
        <a href="/dashboard.php">Dashboard</a>
        <a href="about.html">About</a>
        <a href="/contact.php" class="cta">Contact</a>
      </nav>

      <div class="flex center">
  <div class="flex center">
    <?php if (isset($_SESSION['user_id'])): ?>
     <div class="user-info" style="display:flex;align-items:center;gap:10px;">
      <span style="font-weight:600;">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 👋</span>
      <button class="btn" onclick="location.href='backend/logout.php'">Logout</button>
    </div>
   <?php else: ?>
    <button class="btn cta" onclick="location.href='login.html'">Sign In</button>
      <?php endif; ?>
  </div>
  </div>
  </header>

  <main class="container">
    <section class="hero">
      <div class="left">
        <h1 class="app-title fade-in">SafeSpace — Real-Time Harassment Reporting & SOS Tool</h1>
        <p class="tagline fade-in">Report incidents anonymously, send SOS alerts instantly, and explore safer routes — all from your browser.</p>

        <div class="hero-actions fade-in">
          <button class="sos-btn" onclick="location.href='/sos.php'">Get Help Now — SOS</button>
          <button class="btn" onclick="location.href='/report.php'">Report Incident</button>
        </div>

        <div class="features fade-in" style="margin-top:26px">
          <div class="feature">
            <div class="ico">R</div>
            <div>
              <div style="font-weight:700">Real-Time Reporting</div>
              <div class="small">Quick incident logs with optional photo evidence.</div>
            </div>
          </div>
          <div class="feature">
            <div class="ico">S</div>
            <div>
              <div style="font-weight:700">SOS Alert</div>
              <div class="small">Immediate alerts with countdown & cancel option.</div>
            </div>
          </div>
          <div class="feature">
            <div class="ico">M</div>
            <div>
              <div style="font-weight:700">Safety Map</div>
              <div class="small">View community reports & safe zones nearby.</div>
            </div>
          </div>
          <div class="feature">
            <div class="ico">A</div>
            <div>
              <div style="font-weight:700">Anonymous Mode</div>
              <div class="small">Keep your identity private while reporting.</div>
            </div>
          </div>
        </div>
      </div>

      <aside class="hero-card fade-in">
        <h4>Quick Overview</h4>
        <div class="stats">
          <div class="stat">
            <div style="font-size:18px;font-weight:800">1.2k</div>
            <div class="small">Reports this month</div>
          </div>
          <div class="stat">
            <div style="font-size:18px;font-weight:800">43</div>
            <div class="small">Active safe zones</div>
          </div>
          <div class="stat">
            <div style="font-size:18px;font-weight:800">24/7</div>
            <div class="small">Emergency support</div>
          </div>
          <div class="stat">
            <div style="font-size:18px;font-weight:800">Anonymous</div>
            <div class="small">Privacy mode</div>
          </div>
        </div>

        <div style="margin-top:18px">
          <div class="small">Get the essentials</div>
          <ul style="padding-left:18px;margin-top:8px;color:var(--muted);line-height:1.6">
            <li>Instant SOS</li>
            <li>Incident mapping</li>
            <li>Community resources</li>
          </ul>
        </div>
      </aside>
    </section>

    <section style="margin-top:20px;display:grid;grid-template-columns:1fr 320px;gap:18px;">
      <div class="card">
        <h3>How it works</h3>
        <p class="small">SafeSpace uses community reporting to surface unsafe locations. Use the report form or SOS to notify help. Everything here is a front-end mock — no backend required.</p>

        <div style="margin-top:12px;display:flex;gap:10px;flex-wrap:wrap">
          <div class="card" style="flex:1;min-width:180px">
            <strong>Report</strong><div class="small">Submit location and details.</div>
          </div>
          <div class="card" style="flex:1;min-width:180px">
            <strong>SOS</strong><div class="small">Countdown then alert authorities.</div>
          </div>
          <div class="card" style="flex:1;min-width:180px">
            <strong>Map</strong><div class="small">Explore incidents & safe spots.</div>
          </div>
        </div>
      </div>

      <div class="card">
        <h4>Quick Actions</h4>
        <div style="display:flex;flex-direction:column;gap:8px;margin-top:8px">
          <button class="btn" onclick="location.href='/report.php'">Report Incident</button>
          <button class="btn" onclick="location.href='/map.php'">Open Safety Map</button>
          <button class="btn" onclick="location.href='/sos.php'">Trigger SOS</button>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <div>© <strong>SafeSpace</strong> · Built by Innov8</div>
      <div>
        <a href="about.html" style="color:var(--muted);margin-right:12px">About</a>
        <a href="/contact.php" style="color:var(--muted)">Contact</a>
      </div>
    </div>
  </footer>

  <script src="js/script.js"></script>
</body>
</html>
