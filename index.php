<?php
/**
 * Advance Chiropractic Treatment and Cupping Therapy
 * Main responsive landing page.
 * Loads content from data/content.json for instant load, 100% SEO, and real-time edits.
 */

// Load content from JSON file with error protection
$content_file = __DIR__ . '/data/content.json';
$data = [];

if (file_exists($content_file)) {
    $json_content = file_get_contents($content_file);
    $data = json_decode($json_content, true);
}

// Fail-safe fallbacks if JSON is empty or missing
if (empty($data)) {
    $data = [
        "hero" => [
            "headline" => "Heal Naturally. Live Without Pain.",
            "subheadline" => "Expert chiropractic care, cupping therapy & holistic wellness treatments in Airoli, Navi Mumbai.",
            "cta_primary" => "Book a Session",
            "cta_secondary" => "Explore Treatments",
            "bg_image" => "https://images.unsplash.com/photo-1519824141125-994e37c7af46?q=80&w=1600&auto=format&fit=crop"
        ],
        "trust_bar" => [
            "stat1_num" => "500+", "stat1_lbl" => "Happy Patients",
            "stat2_num" => "5", "stat2_lbl" => "Specialized Treatments",
            "stat3_num" => "Certified", "stat3_lbl" => "Practitioners",
            "stat4_num" => "Airoli's", "stat4_lbl" => "Trusted Clinic"
        ],
        "services" => [],
        "why_choose_us" => [],
        "testimonials" => [],
        "gallery" => [],
        "contact" => [
            "clinic_name" => "Advance Chiropractic Treatment and Cupping Therapy",
            "address" => "Sec 8, Opposite Apple Hospital, Sector 7, Airoli, Navi Mumbai, Maharashtra 400708",
            "whatsapp" => "+91 81691 56124",
            "whatsapp_raw" => "918169156124",
            "email" => "Advance.chiro0909@gmail.com",
            "instagram" => "@chiro.airoli",
            "instagram_url" => "https://www.instagram.com/chiro.airoli?igsh=MWUwMW9ldXZpZmFhMQ==",
            "map_embed_url" => "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3768.2045610260424!2d72.99611597520371!3d19.186259082040186!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7bf44d5c0b93d%3A0xc3b8686f0ad786e2!2sApple%20Hospital!5e0!3m2!1sen!2sin!4v1716982421345!5m2!1sen!2sin",
            "map_link" => "https://maps.app.goo.gl/4fbqmwQi6z76J4i9A"
        ],
        "seo" => [
            "title" => "Advance Chiropractic Treatment & Cupping Therapy | Airoli, Navi Mumbai",
            "meta_desc" => "Best chiropractic treatment, cupping therapy, deep tissue massage, dry needling & ice bath recovery in Airoli, Navi Mumbai. Book your session now.",
            "keywords" => "chiropractic, cupping therapy, dry needling, Navi Mumbai, Airoli",
            "favicon" => "https://cdn-icons-png.flaticon.com/512/3022/3022238.png",
            "og_image" => "https://images.unsplash.com/photo-1519824141125-994e37c7af46?q=80&w=1200&auto=format&fit=crop"
        ]
    ];
}

// Generate secure absolute links for social redirects
$whatsapp_number = preg_replace('/[^0-9]/', '', $data['contact']['whatsapp_raw']);
$whatsapp_link = "https://wa.me/" . $whatsapp_number . "?text=" . urlencode("Hi! I would like to book a session at Advance Chiropractic.");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- SEO Meta Tags -->
  <title><?php echo htmlspecialchars($data['seo']['title']); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($data['seo']['meta_desc']); ?>">
  <meta name="keywords" content="<?php echo htmlspecialchars($data['seo']['keywords']); ?>">
  <meta name="author" content="Advance Chiropractic">
  <meta name="robots" content="index, follow">
  
  <!-- Open Graph / Social Sharing Media Tags (For Rich WhatsApp Previews) -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars($data['seo']['title']); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($data['seo']['meta_desc']); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($data['seo']['og_image']); ?>">
  
  <!-- Twitter Cards -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo htmlspecialchars($data['seo']['title']); ?>">
  <meta name="twitter:description" content="<?php echo htmlspecialchars($data['seo']['meta_desc']); ?>">
  <meta name="twitter:image" content="<?php echo htmlspecialchars($data['seo']['og_image']); ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($data['seo']['favicon']); ?>">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="assets/css/style.css">
  
  <!-- Schema.org Microdata for Local SEO -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "<?php echo addslashes($data['contact']['clinic_name']); ?>",
    "image": "<?php echo addslashes($data['seo']['og_image']); ?>",
    "telephone": "<?php echo addslashes($data['contact']['whatsapp']); ?>",
    "email": "<?php echo addslashes($data['contact']['email']); ?>",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Sec 8, Opposite Apple Hospital, Sector 7",
      "addressLocality": "Airoli, Navi Mumbai",
      "addressRegion": "Maharashtra",
      "postalCode": "400708",
      "addressCountry": "IN"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 19.186259,
      "longitude": 72.996115
    },
    "url": "http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
    "priceRange": "$$",
    "openingHoursSpecification": {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
      ],
      "opens": "09:00",
      "closes": "21:00"
    }
  }
  </script>
</head>
<body>

  <!-- --- 1. PRELOADER & SCREEN SHUTTER --- -->
  <div id="preloader" role="status" aria-label="Loading Website">
    <div class="preloader-gate gate-left"></div>
    <div class="preloader-gate gate-right"></div>
    <div class="preloader-content">
      <div class="preloader-logo">
        <svg viewBox="0 0 200 200" width="240" height="240" aria-hidden="true" style="filter: drop-shadow(0 8px 24px rgba(0,0,0,0.15));">
          <!-- Outer Circle Boundary -->
          <circle cx="100" cy="100" r="95" fill="#013e37" stroke="#ffefb3" stroke-width="2.5" />
          <circle cx="100" cy="100" r="88" fill="none" stroke="#ffefb3" stroke-width="1" stroke-dasharray="3 3" opacity="0.7" />
          
          <!-- Curved Text Paths -->
          <defs>
            <!-- Top semi-circle clockwise path (arc from left-to-right) -->
            <path id="text-path-top" d="M 24 100 A 76 76 0 0 1 176 100" fill="none" />
            <!-- Bottom semi-circle clockwise path (arc from right-to-left) -->
            <path id="text-path-bottom" d="M 176 100 A 76 76 0 0 1 24 100" fill="none" />
          </defs>
          
          <!-- Top Text: ADVANCE CHIROPRACTIC & CUPPING -->
          <text font-family="'Poppins', sans-serif" font-size="10.5" font-weight="700" fill="#ffefb3" letter-spacing="0.5">
            <textPath href="#text-path-top" startOffset="50%" text-anchor="middle">
              ADVANCE CHIROPRACTIC &amp; CUPPING
            </textPath>
          </text>
          
          <!-- Bottom Text: WE MOVE THE BONE, YOU HEAL THE BODY -->
          <text font-family="'Poppins', sans-serif" font-size="7.5" font-weight="600" fill="#ffefb3" letter-spacing="0.3">
            <textPath href="#text-path-bottom" startOffset="50%" text-anchor="middle">
              ✦ WE MOVE THE BONE, YOU HEAL THE BODY ✦
            </textPath>
          </text>
          
          <!-- Stylized Cross Swoosh Waves behind Spine -->
          <path d="M 60 145 C 65 110, 100 70, 112 40 C 98 75, 78 115, 78 145 Z" fill="#ffefb3" opacity="0.6"/>
          <path d="M 140 145 C 135 110, 100 70, 88 40 C 102 75, 122 115, 122 145 Z" fill="#ffefb3" opacity="0.3"/>
          <path d="M 45 130 C 75 125, 125 105, 155 90 C 125 98, 75 118, 45 130 Z" fill="#ffefb3" opacity="0.4"/>
          <path d="M 45 115 C 75 110, 125 90, 155 75 C 125 83, 75 103, 45 115 Z" fill="#ffefb3" opacity="0.2"/>
          
          <!-- Two Glass Cupping Therapy Cups on the Left -->
          <g fill="none" stroke="#ffefb3" stroke-width="1.8" stroke-linecap="round" opacity="0.8">
            <!-- Cup 1 (Large) -->
            <path d="M 52 112 C 52 101, 67 101, 67 112" />
            <path d="M 55 101 C 55 98.5, 64 98.5, 64 101" />
            <line x1="50" y1="112" x2="69" y2="112" />
            
            <!-- Cup 2 (Small) -->
            <path d="M 71 117 C 71 108.5, 83 108.5, 83 117" />
            <line x1="73.5" y1="108.5" x2="80.5" y2="108.5" />
            <path d="M 73.5 108.5 C 73.5 106, 80.5 106, 80.5 108.5" />
            <line x1="69" y1="117" x2="85" y2="117" />
          </g>
          
          <!-- Highly Stylized Anatomical Spine Column in the Center -->
          <g fill="#ffffff" stroke="#013e37" stroke-width="1.2" stroke-linejoin="round" transform="translate(2, 0)">
            <rect x="96" y="44" width="8" height="4.5" rx="2" transform="rotate(6 100 46)"/>
            <rect x="95.5" y="50.5" width="9" height="5" rx="2" transform="rotate(4 100 53)"/>
            <rect x="95" y="57.5" width="10" height="5.5" rx="2" transform="rotate(2 100 60)"/>
            <rect x="94" y="65" width="11" height="6" rx="2.5" transform="rotate(0 100 68)"/>
            <rect x="93" y="73" width="12" height="6.5" rx="3" transform="rotate(-2 100 76)"/>
            <rect x="92" y="81.5" width="13" height="7" rx="3" transform="rotate(-4 100 85)"/>
            <rect x="91.2" y="90.5" width="13.8" height="7.5" rx="3.5" transform="rotate(-5 100 94)"/>
            <rect x="91" y="100" width="14.2" height="8" rx="3.5" transform="rotate(-4 100 104)"/>
            <rect x="91.5" y="110" width="14.6" height="8.5" rx="4" transform="rotate(-2 100 114)"/>
            <rect x="92.5" y="120.5" width="15" height="9" rx="4" transform="rotate(1 100 125)"/>
            <rect x="94.2" y="131.5" width="15.6" height="9.5" rx="4.5" transform="rotate(3 100 136)"/>
            <rect x="96.5" y="143" width="16.2" height="10" rx="4.5" transform="rotate(5 100 148)"/>
            <rect x="99" y="155" width="16.8" height="10.5" rx="5" transform="rotate(6 100 160)"/>
          </g>
        </svg>
      </div>
      <div class="preloader-title">Advance Chiropractic</div>
      <div class="preloader-subtitle">&amp; Cupping Therapy</div>
    </div>
  </div>

  <!-- --- SCROLL PROGRESS INDICATOR --- -->
  <div id="scroll-progress" aria-hidden="true"></div>

  <!-- --- 2. STICKY NAVIGATION BAR --- -->
  <header id="header">
    <div class="container navbar">
      <a href="#home" class="logo" aria-label="Advance Chiropractic Home" style="display: flex; align-items: center; gap: 12px;">
        <svg viewBox="0 0 200 200" width="48" height="48" aria-hidden="true" style="filter: drop-shadow(0 2px 8px rgba(0,0,0,0.15));">
          <!-- Outer Circle Boundary -->
          <circle cx="100" cy="100" r="95" fill="#013e37" stroke="#ffefb3" stroke-width="2.5" />
          <circle cx="100" cy="100" r="88" fill="none" stroke="#ffefb3" stroke-width="1" stroke-dasharray="3 3" opacity="0.7" />
          
          <!-- Curved Text Paths -->
          <defs>
            <path id="nav-text-path-top" d="M 24 100 A 76 76 0 0 1 176 100" fill="none" />
            <path id="nav-text-path-bottom" d="M 176 100 A 76 76 0 0 1 24 100" fill="none" />
          </defs>
          
          <!-- Top Text: ADVANCE CHIROPRACTIC & CUPPING -->
          <text font-family="'Poppins', sans-serif" font-size="10.5" font-weight="700" fill="#ffefb3" letter-spacing="0.5">
            <textPath href="#nav-text-path-top" startOffset="50%" text-anchor="middle">
              ADVANCE CHIROPRACTIC &amp; CUPPING
            </textPath>
          </text>
          
          <!-- Bottom Text: WE MOVE THE BONE, YOU HEAL THE BODY -->
          <text font-family="'Poppins', sans-serif" font-size="7.5" font-weight="600" fill="#ffefb3" letter-spacing="0.3">
            <textPath href="#nav-text-path-bottom" startOffset="50%" text-anchor="middle">
              ✦ WE MOVE THE BONE, YOU HEAL THE BODY ✦
            </textPath>
          </text>
          
          <!-- Stylized Cross Swoosh Waves behind Spine -->
          <path d="M 60 145 C 65 110, 100 70, 112 40 C 98 75, 78 115, 78 145 Z" fill="#ffefb3" opacity="0.6"/>
          <path d="M 140 145 C 135 110, 100 70, 88 40 C 102 75, 122 115, 122 145 Z" fill="#ffefb3" opacity="0.3"/>
          <path d="M 45 130 C 75 125, 125 105, 155 90 C 125 98, 75 118, 45 130 Z" fill="#ffefb3" opacity="0.4"/>
          <path d="M 45 115 C 75 110, 125 90, 155 75 C 125 83, 75 103, 45 115 Z" fill="#ffefb3" opacity="0.2"/>
          
          <!-- Two Glass Cupping Therapy Cups on the Left -->
          <g fill="none" stroke="#ffefb3" stroke-width="1.8" stroke-linecap="round" opacity="0.8">
            <!-- Cup 1 (Large) -->
            <path d="M 52 112 C 52 101, 67 101, 67 112" />
            <path d="M 55 101 C 55 98.5, 64 98.5, 64 101" />
            <line x1="50" y1="112" x2="69" y2="112" />
            
            <!-- Cup 2 (Small) -->
            <path d="M 71 117 C 71 108.5, 83 108.5, 83 117" />
            <line x1="73.5" y1="108.5" x2="80.5" y2="108.5" />
            <path d="M 73.5 108.5 C 73.5 106, 80.5 106, 80.5 108.5" />
            <line x1="69" y1="117" x2="85" y2="117" />
          </g>
          
          <!-- Highly Stylized Anatomical Spine Column in the Center -->
          <g fill="#ffffff" stroke="#013e37" stroke-width="1.2" stroke-linejoin="round" transform="translate(2, 0)">
            <rect x="96" y="44" width="8" height="4.5" rx="2" transform="rotate(6 100 46)"/>
            <rect x="95.5" y="50.5" width="9" height="5" rx="2" transform="rotate(4 100 53)"/>
            <rect x="95" y="57.5" width="10" height="5.5" rx="2" transform="rotate(2 100 60)"/>
            <rect x="94" y="65" width="11" height="6" rx="2.5" transform="rotate(0 100 68)"/>
            <rect x="93" y="73" width="12" height="6.5" rx="3" transform="rotate(-2 100 76)"/>
            <rect x="92" y="81.5" width="13" height="7" rx="3" transform="rotate(-4 100 85)"/>
            <rect x="91.2" y="90.5" width="13.8" height="7.5" rx="3.5" transform="rotate(-5 100 94)"/>
            <rect x="91" y="100" width="14.2" height="8" rx="3.5" transform="rotate(-4 100 104)"/>
            <rect x="91.5" y="110" width="14.6" height="8.5" rx="4" transform="rotate(-2 100 114)"/>
            <rect x="92.5" y="120.5" width="15" height="9" rx="4" transform="rotate(1 100 125)"/>
            <rect x="94.2" y="131.5" width="15.6" height="9.5" rx="4.5" transform="rotate(3 100 136)"/>
            <rect x="96.5" y="143" width="16.2" height="10" rx="4.5" transform="rotate(5 100 148)"/>
            <rect x="99" y="155" width="16.8" height="10.5" rx="5" transform="rotate(6 100 160)"/>
          </g>
        </svg>
        <div style="display: flex; flex-direction: column;">
          <span class="logo-main" style="line-height: 1.1;">Advance Chiropractic</span>
          <span class="logo-sub" style="line-height: 1.1; margin-top: 2px;">&amp; Cupping Therapy</span>
        </div>
      </a>
      <nav id="nav" aria-label="Main Navigation">
        <ul class="nav-menu">
          <li><a href="#home" class="nav-link">Home</a></li>
          <li><a href="#services" class="nav-link">Services</a></li>
          <li><a href="#about" class="nav-link">About</a></li>
          <li><a href="#testimonials" class="nav-link">Testimonials</a></li>
          <li><a href="#contact" class="nav-link">Contact</a></li>
          <li class="nav-cta"><a href="<?php echo $whatsapp_link; ?>" class="btn btn-accent track-whatsapp" aria-label="Book session on WhatsApp">Book Now</a></li>
        </ul>
      </nav>
      <div class="hamburger" id="hamburger-toggle" aria-expanded="false" aria-controls="nav" aria-label="Toggle navigation menu">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </header>

  <!-- --- 3. HERO SECTION --- -->
  <section id="home" class="hero" aria-label="Welcome Banner">
    <div class="hero-overlay"></div>
    <img src="<?php echo htmlspecialchars($data['hero']['bg_image']); ?>" alt="Chiropractic and Wellness Treatment Backdrop" class="hero-bg" loading="eager">
    <div class="container hero-container">
      <div class="hero-content">
        <span class="hero-tagline">Navi Mumbai's Premium Wellness Clinic</span>
        <h1 class="hero-title"><?php echo htmlspecialchars($data['hero']['headline']); ?></h1>
        <p class="hero-subtitle"><?php echo htmlspecialchars($data['hero']['subheadline']); ?></p>
        <div class="hero-ctas">
          <a href="<?php echo $whatsapp_link; ?>" class="btn btn-accent track-whatsapp" aria-label="Book a Healing Session on WhatsApp"><?php echo htmlspecialchars($data['hero']['cta_primary']); ?></a>
          <a href="#services" class="btn btn-outline-accent" aria-label="Explore chiropractic and cupping services"><?php echo htmlspecialchars($data['hero']['cta_secondary']); ?></a>
        </div>
      </div>
      <div class="hero-visual-side">
        <div class="visual-wrapper">
          <?php
          $srv_imgs = [];
          if (!empty($data['services'])) {
              foreach ($data['services'] as $srv) {
                  if (!isset($srv['visible']) || $srv['visible']) {
                      $srv_imgs[] = $srv['bg_image'];
                  }
              }
          }
          if (empty($srv_imgs)) {
              $srv_imgs[] = "https://images.unsplash.com/photo-1600334129128-685c5582fd35?q=80&w=800&auto=format&fit=crop";
          }
          ?>
          <div class="visual-main-box" data-images="<?php echo htmlspecialchars(json_encode($srv_imgs)); ?>">
            <img src="<?php echo htmlspecialchars($srv_imgs[0]); ?>" alt="Therapeutic Healing at Advance Chiropractic" class="visual-img-main" id="hero-carousel-img" style="transition: opacity 0.5s ease-in-out;">
          </div>
          <div class="visual-card visual-card-1">
            <span class="card-num"><?php echo htmlspecialchars($data['trust_bar']['stat1_num']); ?></span>
            <span class="card-lbl"><?php echo htmlspecialchars($data['trust_bar']['stat1_lbl']); ?></span>
          </div>
          <div class="visual-card visual-card-2">
            <svg viewBox="0 0 24 24" class="card-icon"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            <div>
              <span class="card-title"><?php echo htmlspecialchars($data['trust_bar']['stat3_num']); ?></span>
              <span class="card-sub"><?php echo htmlspecialchars($data['trust_bar']['stat3_lbl']); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- --- 4. TRUST BAR --- -->
  <div class="trust-bar">
    <div class="container trust-wrapper">
      <div class="trust-item">
        <div class="trust-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
        <div class="trust-number"><?php echo htmlspecialchars($data['trust_bar']['stat1_num']); ?></div>
        <div class="trust-label"><?php echo htmlspecialchars($data['trust_bar']['stat1_lbl']); ?></div>
      </div>
      <div class="trust-item">
        <div class="trust-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
        </div>
        <div class="trust-number"><?php echo htmlspecialchars($data['trust_bar']['stat2_num']); ?></div>
        <div class="trust-label"><?php echo htmlspecialchars($data['trust_bar']['stat2_lbl']); ?></div>
      </div>
      <div class="trust-item">
        <div class="trust-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
        </div>
        <div class="trust-number"><?php echo htmlspecialchars($data['trust_bar']['stat3_num']); ?></div>
        <div class="trust-label"><?php echo htmlspecialchars($data['trust_bar']['stat3_lbl']); ?></div>
      </div>
      <div class="trust-item">
        <div class="trust-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
        </div>
        <div class="trust-number"><?php echo htmlspecialchars($data['trust_bar']['stat4_num']); ?></div>
        <div class="trust-label"><?php echo htmlspecialchars($data['trust_bar']['stat4_lbl']); ?></div>
      </div>
    </div>
  </div>

  <!-- --- 5. SERVICES SECTION (PARALLAX SLIDES) --- -->
  <section id="services" class="services-section" aria-label="Our Treatments and Therapies">
    
    <!-- Section Header (Static, scrolls away naturally to prevent overlaps) -->
    <div class="services-header">
      <h2>Our Treatments</h2>
    </div>
    
    <div class="services-wrapper">
      <?php 
      $service_index = 1;
      foreach ($data['services'] as $service): 
        if (isset($service['visible']) && !$service['visible']) continue;
        
        $slide_bg_class = ($service_index % 2 !== 0) ? 'bg-green' : 'bg-yellow';
        $btn_style_class = ($service_index % 2 !== 0) ? 'btn-accent' : 'btn-primary-style';
        $benefit_icon_color = ($service_index % 2 !== 0) ? '#ffefb3' : '#013e37';
        
        $srv_whatsapp_link = "https://wa.me/" . $whatsapp_number . "?text=" . urlencode("Hi! I would like to book a session for " . $service['name'] . " at Advance Chiropractic.");
      ?>
      <div class="service-slide <?php echo $slide_bg_class; ?>" id="slide-<?php echo $service['id']; ?>" data-index="<?php echo sprintf('%02d', $service_index); ?>">
        <div class="slide-number-backdrop"><?php echo sprintf('%02d', $service_index); ?></div>
        <div class="container">
          <div class="slide-grid">
            <div class="slide-info">
              <span class="slide-tag">Treatment <?php echo sprintf('%02d', $service_index); ?> of 05</span>
              <h3><?php echo htmlspecialchars($service['name']); ?></h3>
              <p><?php echo htmlspecialchars($service['description']); ?></p>
              
              <ul class="slide-benefits">
                <?php foreach ($service['benefits'] as $benefit): ?>
                <li class="benefit-item">
                  <span class="benefit-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                  </span>
                  <?php echo htmlspecialchars($benefit); ?>
                </li>
                <?php endforeach; ?>
              </ul>
              
              <div class="slide-ctas">
                <a href="<?php echo $srv_whatsapp_link; ?>" class="btn <?php echo $btn_style_class; ?> track-whatsapp" aria-label="Book <?php echo htmlspecialchars($service['name']); ?> on WhatsApp">
                  Book This Service
                </a>
              </div>
            </div>
            
            <div class="slide-image-box">
              <img src="<?php echo htmlspecialchars($service['bg_image']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?> Clinical Treatment Session" class="slide-image" loading="lazy">
            </div>
          </div>
        </div>
      </div>
      <?php 
        $service_index++;
      endforeach; 
      ?>
    </div>
  </section>

  <!-- --- 6. WHY CHOOSE US SECTION --- -->
  <section id="about" class="why-us" aria-label="Why Choose Our Clinic">
    <div class="container">
      <div class="section-intro">
        <h2>Why Choose Us</h2>
        <p>A unique, comprehensive combination of modern chiropractic correction and ancient restorative cupping treatments.</p>
      </div>
      
      <div class="why-grid">
        <?php 
        // Unique, premium, therapy-relevant SVG icons for all 4 "Why Choose Us" items
        $why_icons = [
            // 1. Certified Practitioners: Premium verified certification badge/medal
            '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>',
            
            // 2. Personalized Treatment Plans: Clipboard representing a customized wellness plan
            '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>',
            
            // 3. Modern & Traditional Combined: Organic therapy leaf nested in a clinical circle (synergy)
            '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M17 8C8 10 5.9 16.17 7.02 19c2.83 1.12 9-.98 11-10c.24-1.08.35-2.07.38-3-.93.03-1.92.14-3 .38zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5.88 10.66c-2.3 5.43-7.55 7.15-9.88 6.22-1.09-.43-.72-2.58.74-5.32 1.94-3.66 4.96-6.68 8.62-8.62 2.74-1.46 4.89-1.83 5.32-.74.93 2.33-.79 7.58-6.22 9.88h-.58z"/></svg>',
            
            // 4. Convenient Location in Airoli: Location Pin with a clinic center silhouette
            '<svg viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>'
        ];
        $idx = 0;
        foreach ($data['why_choose_us'] as $why): 
            $icon = isset($why_icons[$idx]) ? $why_icons[$idx] : $why_icons[0];
        ?>
        <article class="why-card">
          <div class="why-card-icon" aria-hidden="true">
            <?php echo $icon; ?>
          </div>
          <div class="why-card-content">
            <h3><?php echo htmlspecialchars($why['title']); ?></h3>
            <p><?php echo htmlspecialchars($why['desc']); ?></p>
          </div>
        </article>
        <?php 
          $idx++;
        endforeach; 
        ?>
      </div>
    </div>
  </section>

  <!-- --- 7. TESTIMONIALS SECTION --- -->
  <section id="testimonials" class="testimonials" aria-label="Patient Success Stories">
    <div class="container">
      <div class="section-intro">
        <h2>What Our Patients Say</h2>
        <p>Hear from real people in Navi Mumbai who restored their mobility and found natural relief from pain at our clinic.</p>
      </div>
    </div>
    
    <div class="carousel-viewport">
      <!-- Double track in DOM for seamless infinite marquee loop -->
      <div class="carousel-track marquee-animation" id="testimonials-track">
        <?php 
        // Render double list of cards to ensure infinite loop overflow coverage
        for ($loop = 0; $loop < 2; $loop++):
          foreach ($data['testimonials'] as $review): 
            if (isset($review['visible']) && !$review['visible']) continue;
        ?>
        <div class="testimonial-card">
          <div>
            <div class="review-stars" aria-label="Rating: <?php echo $review['rating']; ?> stars">
              <?php for($i=0; $i<$review['rating']; $i++): ?>★<?php endfor; ?>
            </div>
            <blockquote class="review-quote">
              <?php echo htmlspecialchars($review['quote']); ?>
            </blockquote>
          </div>
          <div class="reviewer-meta">
            <div class="reviewer-name"><?php echo htmlspecialchars($review['name']); ?></div>
            <div class="reviewer-treatment"><?php echo htmlspecialchars($review['treatment']); ?></div>
          </div>
        </div>
        <?php 
          endforeach; 
        endfor;
        ?>
      </div>
    </div>
  </section>

  <!-- --- 8. INSTAGRAM FEED SECTION --- -->
  <section class="instagram-section" aria-label="Instagram Feed">
    <div class="container">
      <div class="section-intro">
        <h2>Follow us on Instagram</h2>
        <a href="<?php echo htmlspecialchars($data['contact']['instagram_url']); ?>" class="insta-handle track-instagram" target="_blank" rel="noopener noreferrer" aria-label="Visit clinic Instagram profile @chiroairoli">
          <?php echo htmlspecialchars($data['contact']['instagram']); ?>
        </a>
      </div>
      
      <div class="insta-grid">
        <?php foreach ($data['gallery'] as $post): ?>
        <a href="<?php echo htmlspecialchars($data['contact']['instagram_url']); ?>" class="insta-item track-instagram" target="_blank" rel="noopener noreferrer" aria-label="Visit our Instagram page @chiroairoli: <?php echo htmlspecialchars($post['caption']); ?>">
          <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['caption']); ?>" class="insta-img" loading="lazy">
          <div class="insta-overlay">
            <div class="insta-overlay-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </div>
            <p class="insta-caption"><?php echo htmlspecialchars($post['caption']); ?></p>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- --- 9. CONTACT / BOOKING SECTION --- -->
  <section id="contact" class="contact-section" aria-label="Clinic Map and Contact Information">
    <div class="container contact-grid">
      
      <div class="contact-info">
        <h3>Start Your Healing Journey</h3>
        <p style="margin-bottom: 2rem;">Get in touch with us to schedule an appointment. We accept walks-ins, but strongly advise pre-booking to avoid waiting times.</p>
        
        <ul class="contact-list">
          <li class="contact-item">
            <div class="contact-icon-box" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            </div>
            <div class="contact-details">
              <span class="contact-label">Address</span>
              <span class="contact-value">
                <a href="<?php echo htmlspecialchars($data['contact']['map_link']); ?>" target="_blank" rel="noopener noreferrer">
                  <?php echo htmlspecialchars($data['contact']['address']); ?>
                </a>
              </span>
            </div>
          </li>
          
          <li class="contact-item">
            <div class="contact-icon-box" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57a1.02 1.02 0 0 0-1.02.24l-2.2 2.2a15.045 15.045 0 0 1-6.59-6.59l2.2-2.2c.28-.28.36-.67.25-1.02A11.36 11.36 0 0 1 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1z"/></svg>
            </div>
            <div class="contact-details">
              <span class="contact-label">WhatsApp Bookings</span>
              <span class="contact-value">
                <a href="<?php echo $whatsapp_link; ?>" class="track-whatsapp">
                  <?php echo htmlspecialchars($data['contact']['whatsapp']); ?>
                </a>
              </span>
            </div>
          </li>
          
          <li class="contact-item">
            <div class="contact-icon-box" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            </div>
            <div class="contact-details">
              <span class="contact-label">Email</span>
              <span class="contact-value">
                <a href="mailto:<?php echo htmlspecialchars($data['contact']['email']); ?>">
                  <?php echo htmlspecialchars($data['contact']['email']); ?>
                </a>
              </span>
            </div>
          </li>
        </ul>
        
        <div>
          <a href="<?php echo $whatsapp_link; ?>" class="btn btn-accent btn-primary-style track-whatsapp" style="padding: 1rem 2.5rem;" aria-label="Directly Chat on WhatsApp to book session">
            Book on WhatsApp
          </a>
        </div>
      </div>
      
      <div class="map-container" aria-label="Interactive Map of Apple Hospital Airoli region">
        <iframe src="<?php echo htmlspecialchars($data['contact']['map_embed_url']); ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Advance Chiropractic and Cupping Therapy Location Map"></iframe>
      </div>
      
    </div>
  </section>

  <!-- --- 10. FOOTER --- -->
  <footer>
    <div class="container footer-grid">
      <div class="footer-brand">
        <a href="#home" class="footer-logo" aria-label="Footer Brand Logo" style="display: flex; align-items: center; gap: 12px;">
          <svg viewBox="0 0 200 200" width="48" height="48" aria-hidden="true" style="filter: drop-shadow(0 2px 8px rgba(0,0,0,0.15));">
            <!-- Outer Circle Boundary -->
            <circle cx="100" cy="100" r="95" fill="#013e37" stroke="#ffefb3" stroke-width="2.5" />
            <circle cx="100" cy="100" r="88" fill="none" stroke="#ffefb3" stroke-width="1" stroke-dasharray="3 3" opacity="0.7" />
            
            <!-- Curved Text Paths -->
            <defs>
              <path id="foot-text-path-top" d="M 24 100 A 76 76 0 0 1 176 100" fill="none" />
              <path id="foot-text-path-bottom" d="M 176 100 A 76 76 0 0 1 24 100" fill="none" />
            </defs>
            
            <!-- Top Text: ADVANCE CHIROPRACTIC & CUPPING -->
            <text font-family="'Poppins', sans-serif" font-size="10.5" font-weight="700" fill="#ffefb3" letter-spacing="0.5">
              <textPath href="#foot-text-path-top" startOffset="50%" text-anchor="middle">
                ADVANCE CHIROPRACTIC &amp; CUPPING
              </textPath>
            </text>
            
            <!-- Bottom Text: WE MOVE THE BONE, YOU HEAL THE BODY -->
            <text font-family="'Poppins', sans-serif" font-size="7.5" font-weight="600" fill="#ffefb3" letter-spacing="0.3">
              <textPath href="#foot-text-path-bottom" startOffset="50%" text-anchor="middle">
                ✦ WE MOVE THE BONE, YOU HEAL THE BODY ✦
              </textPath>
            </text>
            
            <!-- Stylized Cross Swoosh Waves behind Spine -->
            <path d="M 60 145 C 65 110, 100 70, 112 40 C 98 75, 78 115, 78 145 Z" fill="#ffefb3" opacity="0.6"/>
            <path d="M 140 145 C 135 110, 100 70, 88 40 C 102 75, 122 115, 122 145 Z" fill="#ffefb3" opacity="0.3"/>
            <path d="M 45 130 C 75 125, 125 105, 155 90 C 125 98, 75 118, 45 130 Z" fill="#ffefb3" opacity="0.4"/>
            <path d="M 45 115 C 75 110, 125 90, 155 75 C 125 83, 75 103, 45 115 Z" fill="#ffefb3" opacity="0.2"/>
            
            <!-- Two Glass Cupping Therapy Cups on the Left -->
            <g fill="none" stroke="#ffefb3" stroke-width="1.8" stroke-linecap="round" opacity="0.8">
              <!-- Cup 1 (Large) -->
              <path d="M 52 112 C 52 101, 67 101, 67 112" />
              <path d="M 55 101 C 55 98.5, 64 98.5, 64 101" />
              <line x1="50" y1="112" x2="69" y2="112" />
              
              <!-- Cup 2 (Small) -->
              <path d="M 71 117 C 71 108.5, 83 108.5, 83 117" />
              <line x1="73.5" y1="108.5" x2="80.5" y2="108.5" />
              <path d="M 73.5 108.5 C 73.5 106, 80.5 106, 80.5 108.5" />
              <line x1="69" y1="117" x2="85" y2="117" />
            </g>
            
            <!-- Highly Stylized Anatomical Spine Column in the Center -->
            <g fill="#ffffff" stroke="#013e37" stroke-width="1.2" stroke-linejoin="round" transform="translate(2, 0)">
              <rect x="96" y="44" width="8" height="4.5" rx="2" transform="rotate(6 100 46)"/>
              <rect x="95.5" y="50.5" width="9" height="5" rx="2" transform="rotate(4 100 53)"/>
              <rect x="95" y="57.5" width="10" height="5.5" rx="2" transform="rotate(2 100 60)"/>
              <rect x="94" y="65" width="11" height="6" rx="2.5" transform="rotate(0 100 68)"/>
              <rect x="93" y="73" width="12" height="6.5" rx="3" transform="rotate(-2 100 76)"/>
              <rect x="92" y="81.5" width="13" height="7" rx="3" transform="rotate(-4 100 85)"/>
              <rect x="91.2" y="90.5" width="13.8" height="7.5" rx="3.5" transform="rotate(-5 100 94)"/>
              <rect x="91" y="100" width="14.2" height="8" rx="3.5" transform="rotate(-4 100 104)"/>
              <rect x="91.5" y="110" width="14.6" height="8.5" rx="4" transform="rotate(-2 100 114)"/>
              <rect x="92.5" y="120.5" width="15" height="9" rx="4" transform="rotate(1 100 125)"/>
              <rect x="94.2" y="131.5" width="15.6" height="9.5" rx="4.5" transform="rotate(3 100 136)"/>
              <rect x="96.5" y="143" width="16.2" height="10" rx="4.5" transform="rotate(5 100 148)"/>
              <rect x="99" y="155" width="16.8" height="10.5" rx="5" transform="rotate(6 100 160)"/>
            </g>
          </svg>
          <div style="display: flex; flex-direction: column;">
            <span class="logo-main" style="line-height: 1.1;">Advance Chiropractic</span>
            <span class="logo-sub" style="line-height: 1.1; margin-top: 2px;">&amp; Cupping Therapy</span>
          </div>
        </a>
        <p class="footer-tagline">Providing professional, non-surgical relief and natural holistic wellness. Your healing journey starts here.</p>
        <div class="footer-socials">
          <a href="<?php echo htmlspecialchars($data['contact']['instagram_url']); ?>" class="social-btn track-instagram" target="_blank" rel="noopener noreferrer" aria-label="Clinic Instagram Profile">
            <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
          </a>
          <a href="<?php echo $whatsapp_link; ?>" class="social-btn track-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Clinic WhatsApp Chat">
            <svg viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.5-5.739-1.446L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.966a9.721 9.721 0 0 0-6.979-2.889c-5.439 0-9.865 4.37-9.87 9.799-.002 1.802.49 3.562 1.424 5.129l-.955 3.486 3.58-.941zM17.5 13.9c-.3-.15-1.7-.85-2.0-.95-.25-.1-.45-.15-.65.15-.2.3-.75.95-.9.1-.15-.15-.45-.45-1.6-.9-1.2-.4-2.1-1.4-2.5-1.25-.4-.1-.1-.3-.15-.45-.15-.1-.4-.6-.45-.75-.05-.15-.05-.25 0-.3.05-.05.25-.3.4-.45.1-.15.15-.25.2-.4.05-.15 0-.3 0-.35s-.6-1.5-.85-2.1c-.2-.6-.45-.5-.65-.5-.15 0-.35-.05-.55-.05s-.5.05-.75.3c-.25.3-.95.95-.95 2.3s1.0 2.65 1.15 2.85c.15.2 1.9 2.9 4.6 4.1.65.3 1.15.5 1.55.6.65.2 1.25.15 1.75.05.55-.05 1.7-.7 1.95-1.35.25-.65.25-1.2.15-1.35-.1-.15-.35-.25-.65-.4z"/></svg>
          </a>
        </div>
      </div>
      
      <nav class="footer-nav" aria-label="Footer Navigation">
        <h4>Treatments</h4>
        <ul class="footer-links">
          <li><a href="#services" class="footer-link">Chiropractic Adjustment</a></li>
          <li><a href="#services" class="footer-link">Cupping Therapy</a></li>
          <li><a href="#services" class="footer-link">Deep Tissue Massage</a></li>
          <li><a href="#services" class="footer-link">Dry Needling</a></li>
          <li><a href="#services" class="footer-link">Ice Bath Recovery</a></li>
        </ul>
      </nav>
      
      <nav class="footer-nav" aria-label="Footer Quick Links">
        <h4>Quick Links</h4>
        <ul class="footer-links">
          <li><a href="#home" class="footer-link">Home</a></li>
          <li><a href="#about" class="footer-link">About Us</a></li>
          <li><a href="#testimonials" class="footer-link">Testimonials</a></li>
          <li><a href="#contact" class="footer-link">Location</a></li>
        </ul>
      </nav>
      
    </div>
    
    <div class="container footer-bottom">
      <div class="footer-copy">
        &copy; <?php echo date("Y"); ?> Advance Chiropractic Treatment and Cupping Therapy. All rights reserved.
      </div>
      <div class="footer-copy">
        Airoli, Navi Mumbai, India
      </div>
    </div>
  </footer>

  <!-- --- 11. FLOATING UTILITIES --- -->
  
  <!-- Back to Top Trigger -->
  <button id="back-to-top" aria-label="Back to top of page" title="Scroll back to top">
    <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
      <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
    </svg>
  </button>
  
  <!-- Floating WhatsApp Bubble -->
  <a href="<?php echo $whatsapp_link; ?>" class="whatsapp-bubble track-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="Direct WhatsApp Booking Bubble" title="Direct WhatsApp booking button">
    <svg viewBox="0 0 24 24">
      <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.262 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.5-5.739-1.446L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.966a9.721 9.721 0 0 0-6.979-2.889c-5.439 0-9.865 4.37-9.87 9.799-.002 1.802.49 3.562 1.424 5.129l-.955 3.486 3.58-.941zM17.5 13.9c-.3-.15-1.7-.85-2.0-.95-.25-.1-.45-.15-.65.15-.2.3-.75.95-.9.1-.15-.15-.45-.45-1.6-.9-1.2-.4-2.1-1.4-2.5-1.25-.4-.1-.1-.3-.15-.45-.15-.1-.4-.6-.45-.75-.05-.15-.05-.25 0-.3.05-.05.25-.3.4-.45.1-.15.15-.25.2-.4.05-.15 0-.3 0-.35s-.6-1.5-.85-2.1c-.2-.6-.45-.5-.65-.5-.15 0-.35-.05-.55-.05s-.5.05-.75.3c-.25.3-.95.95-.95 2.3s1.0 2.65 1.15 2.85c.15.2 1.9 2.9 4.6 4.1.65.3 1.15.5 1.55.6.65.2 1.25.15 1.75.05.55-.05 1.7-.7 1.95-1.35.25-.65.25-1.2.15-1.35-.1-.15-.35-.25-.65-.4z"/>
    </svg>
  </a>

  <!-- --- SCRIPTS & LIBS --- -->
  <!-- GSAP Animation Library CDN + ScrollTrigger Plugin CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

  <!-- Firebase Scripts (Only if using real-time synchronization on client side) -->
  <!-- Using standard modular compatible CDN scripts for effortless real-time sync -->
  <script type="text/javascript">
    // Custom global configurations passed to our JS
    window.CLINIC_CONFIG = {
      firebaseActive: false, // Default false, will activate if admin adds configuration
      statsEndpoint: 'api/log_click.php'
    };
  </script>

  <!-- Main JavaScript File -->
  <script src="assets/js/main.js"></script>

</body>
</html>
