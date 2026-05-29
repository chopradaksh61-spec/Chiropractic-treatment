/* ==========================================================================
   ADVANCE CHIROPRACTIC & CUPPING THERAPY - CORE JS ENGINE
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  
  // --- 1. PRELOADER & CINEMATIC GATE REVEAL ---
  const preloader = document.getElementById('preloader');
  
  window.addEventListener('load', () => {
    // Add brief buffer for loading transitions to complete smoothly
    setTimeout(() => {
      if (preloader) {
        preloader.classList.add('loaded');
        
        // Mark body as active to trigger hero typography slide-in animations
        document.body.classList.add('ready');
        
        // Completely destroy preloader in DOM after shutter panels slide open
        setTimeout(() => {
          preloader.style.display = 'none';
        }, 1200); // matches the 1.2s shutter transition in CSS
      }
    }, 800); // pulses center logo for 0.8s
  });

  // Backup load in case window.load takes too long (Core Web Vitals fail-safe)
  setTimeout(() => {
    if (preloader && !preloader.classList.contains('loaded')) {
      preloader.classList.add('loaded');
      document.body.classList.add('ready');
      setTimeout(() => {
        preloader.style.display = 'none';
      }, 1200);
    }
  }, 3500);

  // --- 2. STICKY NAVBAR BACKDROP ---
  const header = document.getElementById('header');
  
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });

  // --- 3. SCROLL PROGRESS INDICATOR ---
  const scrollProgress = document.getElementById('scroll-progress');
  
  window.addEventListener('scroll', () => {
    const totalScroll = document.documentElement.scrollHeight - window.innerHeight;
    if (totalScroll > 0) {
      const scrollPercentage = (window.scrollY / totalScroll) * 100;
      scrollProgress.style.width = `${scrollPercentage}%`;
    }
  });

  // --- 4. BACK TO TOP WIDGET ---
  const backToTopBtn = document.getElementById('back-to-top');
  
  window.addEventListener('scroll', () => {
    const halfPageHeight = document.documentElement.scrollHeight / 2;
    if (window.scrollY > halfPageHeight) {
      backToTopBtn.classList.add('visible');
    } else {
      backToTopBtn.classList.remove('visible');
    }
  });

  backToTopBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  // --- 5. MOBILE DRAWER NAVIGATION (HAMBURGER) ---
  const hamburger = document.getElementById('hamburger-toggle');
  const navMenu = document.querySelector('.nav-menu');
  const navLinks = document.querySelectorAll('.nav-link');

  hamburger.addEventListener('click', () => {
    const isExpanded = hamburger.getAttribute('aria-expanded') === 'true';
    hamburger.setAttribute('aria-expanded', !isExpanded);
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
  });

  // Close mobile drawer when user navigates to a section anchor
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      hamburger.classList.remove('active');
      hamburger.setAttribute('aria-expanded', 'false');
      navMenu.classList.remove('active');
    });
  });

  // --- 6. LOCAL PERFORMANCE & CLICK ANALYTICS ---
  const logAnalytics = (actionName) => {
    const endpoint = window.CLINIC_CONFIG?.statsEndpoint || 'api/log_click.php';
    fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ action: actionName })
    })
    .then(res => res.json())
    .catch(err => console.warn('Local analytics log offline:', err));
  };

  // Log active page load view
  logAnalytics('page_view');

  // Catch clicks on WhatsApp Book Now elements
  document.addEventListener('click', (e) => {
    const trackWa = e.target.closest('.track-whatsapp');
    const trackInsta = e.target.closest('.track-instagram');
    
    if (trackWa) {
      logAnalytics('whatsapp_click');
    }
    if (trackInsta) {
      logAnalytics('instagram_click');
    }
  });

  // --- 7. GSAP SCROLLTRIGGER PARALLAX & SLIDE COUNTERS ---
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    // 0. Trust Bar Stagger Reveal & Count-Up Animations
    gsap.set('.trust-item', { opacity: 0, y: 30 }); // Set initial hidden state cleanly for scroll animation
    
    ScrollTrigger.create({
      trigger: '.trust-bar',
      start: 'top 85%',
      onEnter: () => {
        // Stagger fade items up
        gsap.to('.trust-item', {
          opacity: 1,
          y: 0,
          duration: 0.8,
          stagger: 0.15,
          ease: 'power2.out',
          onComplete: () => {
            // Count up numbers
            const numElements = document.querySelectorAll('.trust-number');
            numElements.forEach(el => {
              const text = el.textContent.trim();
              const numMatch = text.match(/\d+/); // extracts numbers (e.g., 500, 5)
              
              if (numMatch) {
                const targetVal = parseInt(numMatch[0]);
                const suffix = text.replace(numMatch[0], ''); // extracts '+'
                
                const countObj = { val: 0 };
                gsap.to(countObj, {
                  val: targetVal,
                  duration: 1.8,
                  ease: 'power1.out',
                  onUpdate: () => {
                    el.textContent = Math.floor(countObj.val) + suffix;
                  }
                });
              } else {
                // Blur and pop non-numeric elements (e.g., "Certified")
                gsap.fromTo(el,
                  { scale: 0.8, filter: 'blur(2px)' },
                  { scale: 1, filter: 'blur(0px)', duration: 1.2, ease: 'back.out(1.5)' }
                );
              }
            });
          }
        });
      },
      once: true
    });

    const slides = gsap.utils.toArray('.service-slide');
    const counterBadge = document.getElementById('slide-counter');

    slides.forEach((slide) => {
      // 1. Scroll Counter Update Trigger
      ScrollTrigger.create({
        trigger: slide,
        start: 'top 50%',
        end: 'bottom 50%',
        onToggle: (self) => {
          if (self.isActive) {
            const indexStr = slide.getAttribute('data-index');
            const totalStr = String(slides.length).padStart(2, '0');
            if (counterBadge) {
              counterBadge.innerText = `${indexStr} / ${totalStr}`;
            }
          }
        }
      });

      // 2. Vertical Parallax drift on Slide Images
      const img = slide.querySelector('.slide-image');
      if (img) {
        gsap.fromTo(img, 
          { yPercent: -15 }, 
          { 
            yPercent: 15,
            ease: 'none',
            scrollTrigger: {
              trigger: slide,
              start: 'top bottom',
              end: 'bottom top',
              scrub: true
            }
          }
        );
      }
      
      // 3. Subtle text entrance slide shifts
      const slideInfo = slide.querySelector('.slide-info');
      if (slideInfo) {
        gsap.fromTo(slideInfo,
          { opacity: 0.8, y: 30 },
          {
            opacity: 1,
            y: 0,
            ease: 'power2.out',
            scrollTrigger: {
              trigger: slide,
              start: 'top 80%',
              end: 'top 20%',
              scrub: 1
            }
          }
        );
      }
    });

    // 4. Why Choose Us staggered scale-fade ScrollTrigger
    gsap.set('.why-us .section-intro', { opacity: 0, y: 30 });
    gsap.set('.why-card', { opacity: 0, y: 40, scale: 0.95 });

    ScrollTrigger.create({
      trigger: '.why-us',
      start: 'top 80%',
      onEnter: () => {
        // Animate Intro block
        gsap.to('.why-us .section-intro', {
          opacity: 1,
          y: 0,
          duration: 0.8,
          ease: 'power2.out'
        });
        
        // Stagger cards reveal
        gsap.to('.why-card', {
          opacity: 1,
          y: 0,
          scale: 1,
          duration: 0.8,
          stagger: 0.15,
          ease: 'power3.out',
          delay: 0.15
        });
      },
      once: true
    });
  }

  // --- 7.5. HERO SLIDESHOW CAROUSEL (CYCLES SERVICES IMAGES) ---
  const heroImg = document.getElementById('hero-carousel-img');
  const visualMainBox = document.querySelector('.visual-main-box');
  
  if (heroImg && visualMainBox) {
    const imagesData = visualMainBox.getAttribute('data-images');
    if (imagesData) {
      try {
        const images = JSON.parse(imagesData);
        if (images && images.length > 1) {
          let currentIndex = 0;
          setInterval(() => {
            // Smooth fade out
            heroImg.style.opacity = '0';
            
            setTimeout(() => {
              currentIndex = (currentIndex + 1) % images.length;
              heroImg.src = images[currentIndex];
              
              // Fade back in when loaded
              heroImg.onload = () => {
                heroImg.style.opacity = '1';
              };
              
              // Fallback
              setTimeout(() => {
                heroImg.style.opacity = '1';
              }, 50);
            }, 500); // Wait for 0.5s fade out to complete
          }, 2000); // Rotate every 2 seconds
        }
      } catch (e) {
        console.warn('Failed parsing hero slideshow images:', e);
      }
    }
  }

  // --- 8. REAL-TIME DOM SYNCHRONIZER (FIREBASE CLIENT OVERLAY) ---
  window.updateWebsiteDOM = (contentData) => {
    if (!contentData) return;

    // 1. Update Hero
    if (contentData.hero) {
      const headline = document.querySelector('.hero-title');
      const subheadline = document.querySelector('.hero-subtitle');
      const heroBg = document.querySelector('.hero-bg');
      const ctaPrimary = document.querySelector('.hero-ctas .btn-accent');
      const ctaSecondary = document.querySelector('.hero-ctas .btn-outline-accent');
      
      if (headline) headline.textContent = contentData.hero.headline;
      if (subheadline) subheadline.textContent = contentData.hero.subheadline;
      if (heroBg && contentData.hero.bg_image) heroBg.src = contentData.hero.bg_image;
      if (ctaPrimary) ctaPrimary.textContent = contentData.hero.cta_primary;
      if (ctaSecondary) ctaSecondary.textContent = contentData.hero.cta_secondary;
    }

    // 2. Update Trust Bar
    if (contentData.trust_bar) {
      const numbers = document.querySelectorAll('.trust-number');
      const labels = document.querySelectorAll('.trust-label');
      
      if (numbers.length >= 4) {
        numbers[0].textContent = contentData.trust_bar.stat1_num;
        numbers[1].textContent = contentData.trust_bar.stat2_num;
        numbers[2].textContent = contentData.trust_bar.stat3_num;
        numbers[3].textContent = contentData.trust_bar.stat4_num;
      }
      if (labels.length >= 4) {
        labels[0].textContent = contentData.trust_bar.stat1_lbl;
        labels[1].textContent = contentData.trust_bar.stat2_lbl;
        labels[2].textContent = contentData.trust_bar.stat3_lbl;
        labels[3].textContent = contentData.trust_bar.stat4_lbl;
      }
    }

    // 3. Update Contact
    if (contentData.contact) {
      const address = document.querySelector('.contact-item:nth-child(1) .contact-value a');
      const whatsapp = document.querySelector('.contact-item:nth-child(2) .contact-value a');
      const email = document.querySelector('.contact-item:nth-child(3) .contact-value a');
      const footerTagline = document.querySelector('.footer-tagline');
      
      if (address) {
        address.textContent = contentData.contact.address;
        address.href = contentData.contact.map_link;
      }
      if (whatsapp) {
        whatsapp.textContent = contentData.contact.whatsapp;
        const rawNum = contentData.contact.whatsapp_raw.replace(/[^0-9]/g, '');
        const links = document.querySelectorAll('.track-whatsapp');
        links.forEach(link => {
          link.href = `https://wa.me/${rawNum}?text=Hi!%20I%20would%20like%20to%20book%20a%20session%20at%20Advance%20Chiropractic.`;
        });
      }
      if (email) {
        email.textContent = contentData.contact.email;
        email.href = `mailto:${contentData.contact.email}`;
      }
      if (footerTagline) {
        footerTagline.textContent = "Providing professional, non-surgical relief and natural holistic wellness. Your healing journey starts here.";
      }
    }
  };
});
