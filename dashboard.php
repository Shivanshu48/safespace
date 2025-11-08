<?php
include "backend/db_connect.php";
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

// ✅ Fetch user info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, trusted_email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_name = $user ? $user['name'] : $_SESSION['user_name']; 
$trusted_email = $user ? $user['trusted_email'] : '';
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard — SafeSpace</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="header">
  <div class="container">
    <div class="logo">
      <div class="mark">SS</div>
      <div>
        <div style="font-weight:700">SafeSpace</div>
        <div style="font-size:12px;color:var(--muted)">Dashboard</div>
      </div>
    </div>

    <nav class="nav">
      <a href="/index.php">Home</a>
      <a href="/map.php">Map</a>
      <a href="/report.php">Report</a>
    </nav>

    <!-- ✅ Logout button moved to right -->
    <button class="logout-btn" onclick="location.href='backend/logout.php'">Logout</button>

    <button class="hamburger">☰</button>
  </div>
</header>


  <main class="container" style="padding:22px 0">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px">
      <div>
        <h2>Welcome, 
          <span style="background:linear-gradient(90deg,var(--accent-2),var(--accent-3));
          -webkit-background-clip:text;background-clip:text;color:transparent">
          <?php echo htmlspecialchars($user_name); ?> 👋</span>
        </h2>
        <div class="small">Here's a snapshot of your activity</div>
      </div>
      <div style="display:flex;gap:10px">
        <button class="btn" onclick="location.href='/report.php'">New Report</button>
        <button class="btn" onclick="location.href='/sos.php'">Trigger SOS</button>
      </div>
    </div>

    <!-- ✅ Trusted Contact Section -->
    <div class="card" style="margin-top:20px;">
      <h3>Add / Update Trusted Contact</h3>
      <form id="trustedForm" method="POST">
        <input type="email" id="trustedEmail" name="trusted_email"
               value="<?php echo htmlspecialchars($trusted_email); ?>"
               placeholder="Enter trusted contact email" required class="input">
        <button type="submit" class="btn cta">Save Contact</button>
      </form>
      <p id="trustedMsg" class="small" style="margin-top:10px;color:var(--muted)"></p>
    </div>

    <script>
    document.getElementById("trustedForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const email = document.getElementById("trustedEmail").value.trim();
      const res = await fetch("backend/update_trusted.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "trusted_email=" + encodeURIComponent(email)
      });
      const txt = await res.text();
      document.getElementById("trustedMsg").innerText = txt;
    });
    </script>

    <!-- ✅ Tabs Section -->
    <section style="margin-top:14px">
      <div class="tabs">
        <div class="tab active" data-target="history">Report History</div>
        <div class="tab" data-target="tips">Safety Tips</div>
        <div class="tab" data-target="centers">Help Centers</div>
      </div>

      <!-- ✅ Dynamic Report History -->
      <div id="history" class="tab-content card" style="display:block">
        <h4>Report History</h4>

        <?php
        $reports = $conn->prepare("SELECT * FROM reports WHERE user_id = ? ORDER BY created_at DESC");
        $reports->bind_param("i", $user_id);
        $reports->execute();
        $res = $reports->get_result();

        if ($res->num_rows > 0): ?>
          <div style="margin-top:10px;display:grid;gap:10px">
            <?php while ($r = $res->fetch_assoc()): 
              $statusColor = ($r['status'] === 'Resolved') ? 'lime' :
                            (($r['status'] === 'Rejected') ? 'red' : 'orange');
            ?>
              <div class="card" style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                  <strong><?php echo htmlspecialchars($r['type']); ?></strong>
                  <div class="small">
                    📍 <a href="<?php echo htmlspecialchars($r['location']); ?>" target="_blank" style="color:#8ab4f8;">
                      View Location
                    </a>
                  </div>
                  <div class="small"><?php echo htmlspecialchars($r['description']); ?></div>
                  <div class="small" style="color:var(--muted)">
                    🕒 <?php echo date("d M Y, h:i A", strtotime($r['created_at'])); ?>
                  </div>
                </div>
                <div style="text-align:right;">
                  <?php if (!empty($r['image_path'])): ?>
                    <a href="<?php echo htmlspecialchars($r['image_path']); ?>" target="_blank">
                      <img src="<?php echo htmlspecialchars($r['image_path']); ?>" 
                           alt="evidence" style="width:80px;height:80px;border-radius:10px;object-fit:cover;">
                    </a>
                  <?php endif; ?>
                  <div style="margin-top:8px;">
                    <span style="color:<?php echo $statusColor; ?>;font-weight:600;">
                      ● <?php echo htmlspecialchars($r['status']); ?>
                    </span>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p class="small" style="margin-top:8px;">No reports submitted yet.</p>
        <?php endif; ?>
      </div>

      <!-- ✅ Safety Tips -->
      <div id="tips" class="tab-content card" style="display:none">
        <h4>Safety Tips</h4>
        <ul style="margin-top:8px;color:var(--muted);line-height:1.6">
          <li>Keep your phone accessible.</li>
          <li>Share your location with trusted contacts.</li>
          <li>Avoid low-lit routes at night.</li>
        </ul>
      </div>

      <!-- ✅ Help Centers -->
      <div id="centers" class="tab-content card" style="display:none">
        <h4>Help Centers</h4>
        <div style="display:grid;gap:8px;margin-top:8px">
          <div class="card"><strong>Central Police Station</strong><div class="small">24/7 – 100</div></div>
          <div class="card"><strong>Women Help Line</strong><div class="small">08:00 - 20:00 – 1800-180-256</div></div>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <div class="small">Dashboard</div>
      <div class="small">© SafeSpace</div>
    </div>
  </footer>

  <script src="js/script.js"></script>
  <script>
    // Activate first tab by default
    document.addEventListener('DOMContentLoaded', () => {
      const firstTab = document.querySelector('.tab');
      firstTab && firstTab.click();
    });
  </script>
</body>
</html>
