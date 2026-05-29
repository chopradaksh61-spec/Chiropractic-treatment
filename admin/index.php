<?php
/**
 * CMS Admin Panel - Login & Multi-Tab Dashboard
 * Secure, zero-config admin space managing content, stats, and settings.
 */

session_start();

// Handle local authentication POST
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Seeded admin credentials for immediate local testing
    if ($email === 'admin@chiro.com' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        
        // Active secure session cookie
        setcookie('admin_session', 'active', time() + 86400, '/');
        header('Location: index.php');
        exit;
    } else {
        $login_error = 'Invalid email or password. Use: admin@chiro.com / admin123';
    }
}

// Handle administrative sign-out requests
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    session_destroy();
    setcookie('admin_session', '', time() - 3600, '/');
    header('Location: index.php');
    exit;
}

$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Load current site content for seed loading in editor forms
$content_file = __DIR__ . '/../data/content.json';
$site_data = [];
if (file_exists($content_file)) {
    $site_data = json_decode(file_get_contents($content_file), true);
}

// Load current stats for visual report metrics
$stats_file = __DIR__ . '/../data/stats.json';
$stats = ["page_views" => 0, "whatsapp_clicks" => 0, "instagram_clicks" => 0];
if (file_exists($stats_file)) {
    $loaded_stats = json_decode(file_get_contents($stats_file), true);
    if ($loaded_stats !== null) {
        $stats = $loaded_stats;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CMS Administration | Advance Chiropractic &amp; Cupping</title>
  <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/3022/3022238.png">
  
  <!-- Font and Core styles -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Custom Modern Dashboard Theme (Green + White Brand Accents) -->
  <style>
    :root {
      --color-primary: #013e37;
      --color-primary-light: #1a5c52;
      --color-accent: #ffefb3;
      --color-bg: #f4f7f6;
      --color-card: #ffffff;
      --color-border: #e2e8f0;
      --color-text: #013e37;
      --color-text-body: #4a5568;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Poppins', sans-serif; background-color: var(--color-bg); color: var(--color-text-body); }
    
    /* Login Page Layout */
    .login-container {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
      padding: 1.5rem;
    }

    .login-card {
      background: var(--color-card);
      border-radius: 16px;
      padding: 3rem 2.5rem;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .login-logo {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .login-logo h1 {
      font-size: 1.6rem;
      color: var(--color-primary);
      font-weight: 700;
    }

    .login-logo p {
      font-size: 0.85rem;
      color: var(--color-text-body);
      margin-top: 0.25rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      display: block;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--color-primary);
      margin-bottom: 0.5rem;
    }

    .form-input {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1.5px solid var(--color-border);
      border-radius: 8px;
      font-family: inherit;
      font-size: 0.9rem;
      transition: all 0.2s ease;
    }

    .form-input:focus {
      outline: none;
      border-color: var(--color-primary);
      box-shadow: 0 0 0 3px rgba(1, 62, 55, 0.1);
    }

    .btn-login {
      width: 100%;
      padding: 0.85rem;
      background-color: var(--color-primary);
      color: var(--color-accent);
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .btn-login:hover {
      background-color: var(--color-primary-light);
    }

    .login-error {
      background-color: #fff5f5;
      color: #e53e3e;
      border: 1px solid #fed7d7;
      padding: 0.75rem;
      border-radius: 8px;
      font-size: 0.8rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .login-info-box {
      margin-top: 1.5rem;
      background-color: #ebf8ff;
      border: 1px solid #bee3f8;
      border-radius: 8px;
      padding: 0.75rem;
      font-size: 0.75rem;
      color: #2b6cb0;
      line-height: 1.4;
      text-align: center;
    }

    /* Dashboard Layout */
    .dashboard {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar Navigation */
    .sidebar {
      width: 280px;
      background-color: var(--color-primary);
      color: #ffffff;
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
    }

    .sidebar-header {
      padding: 2rem 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-logo {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--color-accent);
      line-height: 1.2;
    }

    .sidebar-logo span {
      display: block;
      font-size: 0.75rem;
      font-weight: 400;
      color: #cbd5e1;
      letter-spacing: 0.5px;
    }

    .sidebar-menu {
      list-style: none;
      padding: 2rem 0;
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
      flex-grow: 1;
    }

    .menu-item button {
      width: 100%;
      text-align: left;
      padding: 0.85rem 1.5rem;
      background: none;
      border: none;
      color: #cbd5e1;
      font-family: inherit;
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: all 0.2s ease;
      border-left: 4px solid transparent;
    }

    .menu-item.active button,
    .menu-item button:hover {
      color: #ffffff;
      background-color: rgba(255, 255, 255, 0.05);
    }

    .menu-item.active button {
      border-left-color: var(--color-accent);
      background-color: rgba(255, 255, 255, 0.08);
      font-weight: 600;
    }

    .sidebar-footer {
      padding: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn-logout {
      width: 100%;
      padding: 0.65rem;
      background-color: rgba(255, 255, 255, 0.1);
      color: #ffffff;
      border: none;
      border-radius: 6px;
      font-family: inherit;
      font-size: 0.85rem;
      font-weight: 500;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      transition: all 0.2s ease;
    }

    .btn-logout:hover {
      background-color: #e53e3e;
    }

    /* Main Content panel */
    .main-panel {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      height: 100vh;
    }

    .top-bar {
      height: 70px;
      background-color: #ffffff;
      border-bottom: 1px solid var(--color-border);
      padding: 0 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }

    .top-bar-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--color-primary);
    }

    .sync-status {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.8rem;
      background-color: #f0fdf4;
      color: #15803d;
      padding: 0.4rem 0.8rem;
      border-radius: var(--radius-pill);
      border: 1px solid #bbf7d0;
      font-weight: 500;
    }

    .sync-status.offline {
      background-color: #fef3c7;
      color: #d97706;
      border-color: #fde68a;
    }

    .content-area {
      padding: 2.5rem;
      flex-grow: 1;
      max-width: 1000px;
      width: 100%;
      margin: 0 auto;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
      animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Panel elements styling */
    .panel-card {
      background-color: var(--color-card);
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      border: 1px solid var(--color-border);
      margin-bottom: 2rem;
    }

    .panel-card-title {
      font-size: 1.15rem;
      font-weight: 600;
      color: var(--color-primary);
      margin-bottom: 1.5rem;
      border-bottom: 1px solid var(--color-border);
      padding-bottom: 0.75rem;
    }

    /* Form Fields Grid */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
    }

    .form-grid-full {
      grid-column: span 2;
    }

    textarea.form-input {
      resize: vertical;
      min-height: 100px;
    }

    .btn-save {
      background-color: var(--color-primary);
      color: var(--color-accent);
      padding: 0.75rem 2rem;
      font-weight: 600;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-family: inherit;
      font-size: 0.9rem;
      transition: all 0.2s ease;
    }

    .btn-save:hover {
      background-color: var(--color-primary-light);
    }

    /* Analytics Dashboard Widgets */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
      margin-bottom: 2.5rem;
    }

    .stat-card {
      background-color: var(--color-card);
      border-radius: 12px;
      padding: 1.75rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      border: 1px solid var(--color-border);
      display: flex;
      align-items: center;
      gap: 1.25rem;
    }

    .stat-icon {
      width: 54px;
      height: 54px;
      background-color: #f0fdf4;
      border-radius: 10px;
      color: var(--color-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .stat-icon svg { width: 28px; height: 28px; fill: currentColor; }

    .stat-card:nth-child(2) .stat-icon { background-color: #ebf8ff; color: #3182ce; }
    .stat-card:nth-child(3) .stat-icon { background-color: #faf5ff; color: #805ad5; }

    .stat-number { font-size: 2rem; font-weight: 700; color: var(--color-text); line-height: 1.1; }
    .stat-name { font-size: 0.8rem; color: var(--color-text-body); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Service management cards */
    .service-manager-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .service-item-card {
      background-color: #fafafa;
      border: 1px solid var(--color-border);
      border-radius: 8px;
      padding: 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      cursor: grab;
      transition: all 0.2s ease;
    }

    .service-item-card:active { cursor: grabbing; background-color: #f1f5f9; }

    .service-item-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .drag-handle { color: #94a3b8; cursor: grab; display: flex; align-items: center; }

    .service-item-info h4 { font-size: 0.95rem; font-weight: 600; color: var(--color-text); }
    .service-item-info p { font-size: 0.8rem; opacity: 0.8; }

    .service-item-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .toggle-switch {
      position: relative;
      display: inline-block;
      width: 44px;
      height: 24px;
    }

    .toggle-switch input { opacity: 0; width: 0; height: 0; }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: #cbd5e1;
      transition: .4s;
      border-radius: 34px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 16px; width: 16px;
      left: 4px; bottom: 4px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    input:checked + .slider { background-color: var(--color-primary); }
    input:checked + .slider:before { transform: translateX(20px); }

    .btn-edit-item {
      background: none;
      border: none;
      color: var(--color-primary-light);
      font-weight: 500;
      font-size: 0.85rem;
      cursor: pointer;
    }

    .btn-edit-item:hover { text-decoration: underline; }

    /* Modal dialog */
    .modal {
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
    }

    .modal.active { display: flex; }

    .modal-card {
      background: #ffffff;
      border-radius: 12px;
      width: 90%;
      max-width: 600px;
      max-height: 85vh;
      overflow-y: auto;
      padding: 2rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid var(--color-border);
      padding-bottom: 1rem;
      margin-bottom: 1.5rem;
    }

    .modal-header h3 { font-size: 1.15rem; color: var(--color-primary); }

    .modal-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #94a3b8;
    }

    .modal-close:hover { color: #64748b; }
  </style>
</head>
<body>

  <!-- --- A. LOGIN PAGE VIEW --- -->
  <?php if (!$is_logged_in): ?>
  <div class="login-container">
    <div class="login-card">
      <div class="login-logo">
        <h1>Advance Chiropractic</h1>
        <p>Administrative Control Center</p>
      </div>

      <?php if (!empty($login_error)): ?>
        <div class="login-error"><?php echo htmlspecialchars($login_error); ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label class="form-label" for="email">Admin Email</label>
          <input class="form-input" type="email" id="email" name="email" value="admin@chiro.com" required placeholder="admin@chiro.com">
        </div>
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input class="form-input" type="password" id="password" name="password" value="admin123" required placeholder="••••••••">
        </div>
        
        <button class="btn-login" type="submit" name="login">Log In to Dashboard</button>
      </form>
      
      <div class="login-info-box">
        <strong>Seeded Local Authentication:</strong><br>
        Email: <code>admin@chiro.com</code><br>
        Password: <code>admin123</code>
      </div>
    </div>
  </div>
  
  <!-- --- B. DASHBOARD VIEW (AUTHENTICATED) --- -->
  <?php else: ?>
  <div class="dashboard">
    
    <!-- Left Sidebar Menu -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <h2 class="sidebar-logo">
          Advance Chiro
          <span>CMS Control Panel v1.0</span>
        </h2>
      </div>
      
      <ul class="sidebar-menu">
        <li class="menu-item active" data-tab="tab-overview"><button>Dashboard Stats</button></li>
        <li class="menu-item" data-tab="tab-hero"><button>Hero Editor</button></li>
        <li class="menu-item" data-tab="tab-services"><button>Services Manager</button></li>
        <li class="menu-item" data-tab="tab-testimonials"><button>Testimonials Manager</button></li>
        <li class="menu-item" data-tab="tab-gallery"><button>Clinic Gallery</button></li>
        <li class="menu-item" data-tab="tab-contact"><button>Contact Details</button></li>
        <li class="menu-item" data-tab="tab-trust"><button>Trust Ribbon</button></li>
        <li class="menu-item" data-tab="tab-seo"><button>SEO Config</button></li>
      </ul>
      
      <div class="sidebar-footer">
        <a href="?action=logout" class="btn-logout">
          Sign Out
        </a>
      </div>
    </aside>
    
    <!-- Right Main Content Panel -->
    <main class="main-panel">
      
      <!-- Top status bar -->
      <header class="top-bar">
        <div class="top-bar-title" id="panel-tab-title">Dashboard Overview</div>
        <div class="sync-status offline" id="fb-status-badge">
          Local XAMPP Mode (Direct Sync Active)
        </div>
      </header>
      
      <!-- Main Scrollable Content Box -->
      <div class="content-area">
        
        <!-- --- TAB 1: OVERVIEW STATS --- -->
        <div class="tab-content active" id="tab-overview">
          <div class="stats-row">
            <div class="stat-card">
              <div class="stat-icon">
                <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
              </div>
              <div>
                <div class="stat-number" id="stat-views"><?php echo htmlspecialchars($stats['page_views']); ?></div>
                <div class="stat-name">Total Page Views</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon">
                <svg viewBox="0 0 24 24"><path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57a1.02 1.02 0 0 0-1.02.24l-2.2 2.2a15.045 15.045 0 0 1-6.59-6.59l2.2-2.2c.28-.28.36-.67.25-1.02A11.36 11.36 0 0 1 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1z"/></svg>
              </div>
              <div>
                <div class="stat-number" id="stat-wa"><?php echo htmlspecialchars($stats['whatsapp_clicks']); ?></div>
                <div class="stat-name">WhatsApp Bookings</div>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
              </div>
              <div>
                <div class="stat-number" id="stat-ig"><?php echo htmlspecialchars($stats['instagram_clicks']); ?></div>
                <div class="stat-name">Instagram Links</div>
              </div>
            </div>
          </div>
          
          <div class="panel-card">
            <h3 class="panel-card-title">Live Firebase synchronization</h3>
            <p style="font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem;">The website currently saves all data locally into the XAMPP <code>data/content.json</code> file instantly. To activate instant multi-device Firebase cloud synchronization, you can input your Firebase web credentials below.</p>
            
            <form id="firebase-credentials-form">
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Firebase API Key</label>
                  <input class="form-input" type="text" id="fb-apikey" placeholder="AIzaSyA1...">
                </div>
                <div class="form-group">
                  <label class="form-label">Project ID</label>
                  <input class="form-input" type="text" id="fb-projectid" placeholder="chiro-airoli-123">
                </div>
                <div class="form-group form-grid-full">
                  <button type="submit" class="btn-save">Activate Cloud Sync</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- --- TAB 2: HERO BANNER --- -->
        <div class="tab-content" id="tab-hero">
          <form id="hero-editor-form">
            <div class="panel-card">
              <h3 class="panel-card-title">Main Hero Banner</h3>
              <div class="form-grid">
                <div class="form-group form-grid-full">
                  <label class="form-label" for="hero-headline">Headline</label>
                  <input class="form-input" type="text" id="hero-headline" value="<?php echo htmlspecialchars($site_data['hero']['headline']); ?>" required>
                </div>
                
                <div class="form-group form-grid-full">
                  <label class="form-label" for="hero-subheadline">Subheadline / Supporting Pitch</label>
                  <textarea class="form-input" id="hero-subheadline" required><?php echo htmlspecialchars($site_data['hero']['subheadline']); ?></textarea>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="hero-cta-primary">Primary Button Text (WhatsApp)</label>
                  <input class="form-input" type="text" id="hero-cta-primary" value="<?php echo htmlspecialchars($site_data['hero']['cta_primary']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="hero-cta-secondary">Secondary Button Text (Scroll)</label>
                  <input class="form-input" type="text" id="hero-cta-secondary" value="<?php echo htmlspecialchars($site_data['hero']['cta_secondary']); ?>" required>
                </div>
                
                <div class="form-group form-grid-full">
                  <label class="form-label" for="hero-bg">Hero Background Image URL (Unsplash or uploaded)</label>
                  <input class="form-input" type="text" id="hero-bg" value="<?php echo htmlspecialchars($site_data['hero']['bg_image']); ?>" required>
                </div>
                
                <div class="form-group form-grid-full" style="margin-top: 1rem;">
                  <button class="btn-save" type="submit">Save Hero Modifications</button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <!-- --- TAB 3: SERVICES MANAGER --- -->
        <div class="tab-content" id="tab-services">
          <div class="panel-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
              <h3 style="margin-bottom: 0; border: none; padding: 0;" class="panel-card-title">Active Treatments &amp; Therapies</h3>
              <button class="btn-save" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;" onclick="openServiceModal('new')">Add New Service</button>
            </div>
            
            <p style="font-size: 0.85rem; margin-bottom: 1.5rem; color: var(--color-text-body);">Drag and drop services in the list below to reorder their slide priority on the main landing page.</p>
            
            <div class="service-manager-list" id="services-sortable-list">
              <?php 
              $s_index = 0;
              foreach ($site_data['services'] as $srv): 
                $is_checked = (!isset($srv['visible']) || $srv['visible'] === true) ? 'checked' : '';
              ?>
              <div class="service-item-card" draggable="true" data-array-index="<?php echo $s_index; ?>">
                <div class="service-item-left">
                  <span class="drag-handle">☰</span>
                  <div class="service-item-info">
                    <h4><?php echo htmlspecialchars($srv['name']); ?></h4>
                    <p>Slide Backdrop: <?php echo htmlspecialchars($srv['number']); ?></p>
                  </div>
                </div>
                <div class="service-item-actions">
                  <button class="btn-edit-item" onclick="openServiceModal(<?php echo $s_index; ?>)">Edit Details</button>
                  <label class="toggle-switch">
                    <input type="checkbox" class="service-visibility-toggle" data-array-index="<?php echo $s_index; ?>" <?php echo $is_checked; ?>>
                    <span class="slider"></span>
                  </label>
                </div>
              </div>
              <?php 
                $s_index++;
              endforeach; 
              ?>
            </div>
            
            <div style="margin-top: 2rem;">
              <button class="btn-save" onclick="saveServicesOrder()">Save Services Configuration</button>
            </div>
          </div>
        </div>

        <!-- --- TAB 4: TESTIMONIALS MANAGER --- -->
        <div class="tab-content" id="tab-testimonials">
          <div class="panel-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: 0.75rem;">
              <h3 style="margin-bottom: 0; border: none; padding: 0;" class="panel-card-title">Patient Success Stories</h3>
              <button class="btn-save" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;" onclick="openTestimonialModal('new')">Add Testimonial</button>
            </div>
            
            <div class="service-manager-list" id="testimonials-cms-list">
              <?php 
              $t_idx = 0;
              foreach ($site_data['testimonials'] as $test):
                $test_checked = (!isset($test['visible']) || $test['visible'] === true) ? 'checked' : '';
              ?>
              <div class="service-item-card" style="cursor: default;" data-array-index="<?php echo $t_idx; ?>">
                <div class="service-item-left">
                  <div class="service-item-info">
                    <h4><?php echo htmlspecialchars($test['name']); ?> (<?php echo htmlspecialchars($test['treatment']); ?>)</h4>
                    <p style="font-style: italic;">"<?php echo htmlspecialchars(substr($test['quote'], 0, 70)); ?>..."</p>
                  </div>
                </div>
                <div class="service-item-actions">
                  <button class="btn-edit-item" onclick="openTestimonialModal(<?php echo $t_idx; ?>)">Edit</button>
                  <button class="btn-edit-item" style="color: #e53e3e;" onclick="deleteTestimonial(<?php echo $t_idx; ?>)">Delete</button>
                  <label class="toggle-switch">
                    <input type="checkbox" class="testimonial-visibility-toggle" data-array-index="<?php echo $t_idx; ?>" <?php echo $test_checked; ?>>
                    <span class="slider"></span>
                  </label>
                </div>
              </div>
              <?php 
                $t_idx++;
              endforeach; 
              ?>
            </div>
            
            <div style="margin-top: 2rem;">
              <button class="btn-save" onclick="saveTestimonialsConfig()">Save Testimonials Changes</button>
            </div>
          </div>
        </div>

        <!-- --- TAB 5: CLINIC GALLERY --- -->
        <div class="tab-content" id="tab-gallery">
          <div class="panel-card">
            <h3 class="panel-card-title">Instagram Fallback Grid &amp; Gallery</h3>
            
            <div class="service-manager-list" id="gallery-cms-list">
              <?php 
              $g_idx = 0;
              foreach ($site_data['gallery'] as $post):
              ?>
              <div class="service-item-card" style="cursor: default;">
                <div class="service-item-left" style="width: 80%;">
                  <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="thumbnail" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                  <div class="service-item-info" style="width: 100%;">
                    <input type="text" class="form-input gallery-caption-input" data-index="<?php echo $g_idx; ?>" value="<?php echo htmlspecialchars($post['caption']); ?>" placeholder="Caption this clinical picture" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; width: 90%;">
                  </div>
                </div>
                <div class="service-item-actions">
                  <input type="hidden" class="gallery-image-url" data-index="<?php echo $g_idx; ?>" value="<?php echo htmlspecialchars($post['image']); ?>">
                  <button class="btn-edit-item" style="color: #e53e3e;" onclick="deleteGalleryItem(<?php echo $g_idx; ?>)">Delete</button>
                </div>
              </div>
              <?php 
                $g_idx++;
              endforeach; 
              ?>
            </div>
            
            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
              <button class="btn-save" onclick="saveGalleryModifications()">Save Gallery Edits</button>
              <button class="btn-save btn-primary-style" onclick="openGalleryModal()">Add New Image</button>
            </div>
          </div>
        </div>

        <!-- --- TAB 6: CONTACT DETAILS --- -->
        <div class="tab-content" id="tab-contact">
          <form id="contact-editor-form">
            <div class="panel-card">
              <h3 class="panel-card-title">Clinic Access &amp; Contact Metadata</h3>
              <div class="form-grid">
                <div class="form-group form-grid-full">
                  <label class="form-label" for="contact-name">Clinic Name</label>
                  <input class="form-input" type="text" id="contact-name" value="<?php echo htmlspecialchars($site_data['contact']['clinic_name']); ?>" required>
                </div>
                
                <div class="form-group form-grid-full">
                  <label class="form-label" for="contact-address">Physical Clinic Address</label>
                  <input class="form-input" type="text" id="contact-address" value="<?php echo htmlspecialchars($site_data['contact']['address']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="contact-whatsapp">Display Phone/WhatsApp</label>
                  <input class="form-input" type="text" id="contact-whatsapp" value="<?php echo htmlspecialchars($site_data['contact']['whatsapp']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="contact-whatsapp-raw">WhatsApp Raw Number (No spaces/No '+')</label>
                  <input class="form-input" type="text" id="contact-whatsapp-raw" value="<?php echo htmlspecialchars($site_data['contact']['whatsapp_raw']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="contact-email">Email ID</label>
                  <input class="form-input" type="email" id="contact-email" value="<?php echo htmlspecialchars($site_data['contact']['email']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="contact-ig-handle">Instagram Handle</label>
                  <input class="form-input" type="text" id="contact-ig-handle" value="<?php echo htmlspecialchars($site_data['contact']['instagram']); ?>" required>
                </div>
                
                <div class="form-group form-grid-full">
                  <label class="form-label" for="contact-map-link">Google Maps App Link</label>
                  <input class="form-input" type="text" id="contact-map-link" value="<?php echo htmlspecialchars($site_data['contact']['map_link']); ?>" required>
                </div>
                
                <div class="form-group form-grid-full">
                  <label class="form-label" for="contact-map-embed">Google Maps Iframe Embed SRC Link Only</label>
                  <textarea class="form-input" id="contact-map-embed" required><?php echo htmlspecialchars($site_data['contact']['map_embed_url']); ?></textarea>
                </div>
                
                <div class="form-group form-grid-full" style="margin-top: 1rem;">
                  <button class="btn-save" type="submit">Save Location Settings</button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <!-- --- TAB 7: TRUST BAR RIBBON --- -->
        <div class="tab-content" id="tab-trust">
          <form id="trust-editor-form">
            <div class="panel-card">
              <h3 class="panel-card-title">Trust stats ribbon</h3>
              <div class="form-grid">
                
                <div class="form-group">
                  <label class="form-label">Stat 1 Count / Title</label>
                  <input class="form-input" type="text" id="trust-s1-num" value="<?php echo htmlspecialchars($site_data['trust_bar']['stat1_num']); ?>" required style="margin-bottom: 0.5rem;">
                  <input class="form-input" type="text" id="trust-s1-lbl" value="<?php echo htmlspecialchars($site_data['trust_bar']['stat1_lbl']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label">Stat 2 Count / Title</label>
                  <input class="form-input" type="text" id="trust-s2-num" value="<?php echo htmlspecialchars($site_data['trust_bar']['stat2_num']); ?>" required style="margin-bottom: 0.5rem;">
                  <input class="form-input" type="text" id="trust-s2-lbl" value="<?php echo htmlspecialchars($site_data['trust_bar']['stat2_lbl']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label">Stat 3 Count / Title</label>
                  <input class="form-input" type="text" id="trust-s3-num" value="<?php echo htmlspecialchars($site_data['trust_bar']['stat3_num']); ?>" required style="margin-bottom: 0.5rem;">
                  <input class="form-input" type="text" id="trust-s3-lbl" value="<?php echo htmlspecialchars($site_data['trust_bar']['stat3_lbl']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label">Stat 4 Count / Title</label>
                  <input class="form-input" type="text" id="trust-s4-num" value="<?php echo htmlspecialchars($site_data['trust_bar']['stat4_num']); ?>" required style="margin-bottom: 0.5rem;">
                  <input class="form-input" type="text" id="trust-s4-lbl" value="<?php echo htmlspecialchars($site_data['trust_bar']['stat4_lbl']); ?>" required>
                </div>
                
                <div class="form-group form-grid-full" style="margin-top: 1rem;">
                  <button class="btn-save" type="submit">Update Trust Ribbon</button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <!-- --- TAB 8: SEO & METADATA CONFIG --- -->
        <div class="tab-content" id="tab-seo">
          <form id="seo-editor-form">
            <div class="panel-card">
              <h3 class="panel-card-title">SEO &amp; Analytics Tags Settings</h3>
              <div class="form-grid">
                <div class="form-group form-grid-full">
                  <label class="form-label" for="seo-title">Search Engine Page Title</label>
                  <input class="form-input" type="text" id="seo-title" value="<?php echo htmlspecialchars($site_data['seo']['title']); ?>" required>
                </div>
                
                <div class="form-group form-grid-full">
                  <label class="form-label" for="seo-desc">Meta Description (150-160 characters ideal for Google snippets)</label>
                  <textarea class="form-input" id="seo-desc" required><?php echo htmlspecialchars($site_data['seo']['meta_desc']); ?></textarea>
                </div>
                
                <div class="form-group form-grid-full">
                  <label class="form-label" for="seo-keywords">Search Keywords (separated by commas)</label>
                  <input class="form-input" type="text" id="seo-keywords" value="<?php echo htmlspecialchars($site_data['seo']['keywords']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="seo-favicon">Favicon Icon URL</label>
                  <input class="form-input" type="text" id="seo-favicon" value="<?php echo htmlspecialchars($site_data['seo']['favicon']); ?>" required>
                </div>
                
                <div class="form-group">
                  <label class="form-label" for="seo-og">Open Graph Sharing Image URL (WhatsApp backdrop)</label>
                  <input class="form-input" type="text" id="seo-og" value="<?php echo htmlspecialchars($site_data['seo']['og_image']); ?>" required>
                </div>
                
                <div class="form-group form-grid-full" style="margin-top: 1rem;">
                  <button class="btn-save" type="submit">Publish SEO Configurations</button>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div>
    </main>
  </div>

  <!-- --- MODALS FOR INTERACTION --- -->

  <!-- 1. Treatment Slide Dialog Modal -->
  <div class="modal" id="modal-service">
    <div class="modal-card">
      <div class="modal-header">
        <h3 id="srv-modal-title">Edit Treatment details</h3>
        <button class="modal-close" onclick="closeModal('modal-service')">&times;</button>
      </div>
      <form id="service-item-editor-form" onsubmit="saveServiceItemForm(event)">
        <input type="hidden" id="srv-edit-index" value="">
        <div class="form-group">
          <label class="form-label">Service Title</label>
          <input class="form-input" type="text" id="srv-edit-name" required placeholder="e.g. Spine Adjustment">
        </div>
        <div class="form-group">
          <label class="form-label">Backdrop Display Number</label>
          <input class="form-input" type="text" id="srv-edit-num" required placeholder="e.g. 06">
        </div>
        <div class="form-group">
          <label class="form-label">Treatment Description</label>
          <textarea class="form-input" id="srv-edit-desc" required placeholder="Details about this treatment..."></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Key Benefit 1</label>
          <input class="form-input" type="text" id="srv-edit-b1" required placeholder="Benefit 1">
        </div>
        <div class="form-group">
          <label class="form-label">Key Benefit 2</label>
          <input class="form-input" type="text" id="srv-edit-b2" required placeholder="Benefit 2">
        </div>
        <div class="form-group">
          <label class="form-label">Key Benefit 3</label>
          <input class="form-input" type="text" id="srv-edit-b3" required placeholder="Benefit 3">
        </div>
        <div class="form-group">
          <label class="form-label">Parallax Background Image URL</label>
          <input class="form-input" type="text" id="srv-edit-bg" required placeholder="Image link">
        </div>
        <button type="submit" class="btn-save" style="width: 100%; justify-content: center; margin-top: 1rem;">Save Service Details</button>
      </form>
    </div>
  </div>

  <!-- 2. Testimonial Dialog Modal -->
  <div class="modal" id="modal-testimonial">
    <div class="modal-card">
      <div class="modal-header">
        <h3 id="test-modal-title">Edit Review Card</h3>
        <button class="modal-close" onclick="closeModal('modal-testimonial')">&times;</button>
      </div>
      <form id="testimonial-item-editor-form" onsubmit="saveTestimonialItemForm(event)">
        <input type="hidden" id="test-edit-index" value="">
        <div class="form-group">
          <label class="form-label">Patient Name</label>
          <input class="form-input" type="text" id="test-edit-name" required placeholder="Amit Patel">
        </div>
        <div class="form-group">
          <label class="form-label">Treatment Taken</label>
          <input class="form-input" type="text" id="test-edit-treatment" required placeholder="Spinal Alignment">
        </div>
        <div class="form-group">
          <label class="form-label">Star Rating (1 to 5)</label>
          <input class="form-input" type="number" id="test-edit-rating" min="1" max="5" value="5" required>
        </div>
        <div class="form-group">
          <label class="form-label">Review Quote / Experience</label>
          <textarea class="form-input" id="test-edit-quote" required placeholder="Write review here..."></textarea>
        </div>
        <button type="submit" class="btn-save" style="width: 100%; justify-content: center; margin-top: 1rem;">Save Review</button>
      </form>
    </div>
  </div>

  <!-- 3. Add Gallery Image Modal -->
  <div class="modal" id="modal-gallery">
    <div class="modal-card">
      <div class="modal-header">
        <h3>Add Clinical Photo</h3>
        <button class="modal-close" onclick="closeModal('modal-gallery')">&times;</button>
      </div>
      <form id="gallery-item-add-form" onsubmit="addGalleryItem(event)">
        <div class="form-group">
          <label class="form-label">Photo Image URL</label>
          <input class="form-input" type="text" id="gal-add-url" required placeholder="https://images.unsplash.com/...">
        </div>
        <div class="form-group">
          <label class="form-label">Photo Description / Caption</label>
          <input class="form-input" type="text" id="gal-add-caption" required placeholder="Therapy room backdrop">
        </div>
        <button type="submit" class="btn-save" style="width: 100%; justify-content: center; margin-top: 1rem;">Add Photo to Grid</button>
      </form>
    </div>
  </div>

  <!-- BINDING DATABASE SCRIPTS -->
  <!-- Transferring PHP Seeded Arrays directly into Javascript memory -->
  <script type="text/javascript">
    window.WEBSITE_DATABASE = <?php echo json_encode($site_data); ?>;
  </script>
  
  <script src="admin.js"></script>

  <?php endif; ?>
</body>
</html>
