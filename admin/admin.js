/* ==========================================================================
   ADVANCE CHIROPRACTIC & CUPPING THERAPY - CMS ADMIN JS
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  
  // Cache structural variables
  const db = window.WEBSITE_DATABASE;
  
  // --- 1. TAB MENU NAVIGATION ---
  const menuItems = document.querySelectorAll('.menu-item');
  const tabContents = document.querySelectorAll('.tab-content');
  const tabTitle = document.getElementById('panel-tab-title');

  menuItems.forEach(item => {
    item.addEventListener('click', () => {
      // Deactivate all
      menuItems.forEach(mi => mi.classList.remove('active'));
      tabContents.forEach(tc => tc.classList.remove('active'));

      // Activate clicked
      item.classList.add('active');
      const tabId = item.getAttribute('data-tab');
      const activeTab = document.getElementById(tabId);
      activeTab.classList.add('active');

      // Update Top Bar Title
      const rawTitle = item.querySelector('button').textContent;
      tabTitle.textContent = rawTitle;
    });
  });

  // --- 2. GLOBAL SERVER WRITE CALL ---
  const saveContentToServer = () => {
    return fetch('../api/save_content.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(db)
    })
    .then(res => res.json())
    .then(response => {
      if (response.status === 'success') {
        showGlobalAlert('Website content published successfully!');
        return true;
      } else {
        showGlobalAlert('Server Write Error: ' + response.message, true);
        return false;
      }
    })
    .catch(err => {
      showGlobalAlert('Network error saving changes to XAMPP local server.', true);
      console.error(err);
      return false;
    });
  };

  // Helper Alert Banner
  const showGlobalAlert = (msg, isError = false) => {
    const alertDiv = document.createElement('div');
    alertDiv.style.position = 'fixed';
    alertDiv.style.bottom = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.backgroundColor = isError ? '#e53e3e' : '#013e37';
    alertDiv.style.color = isError ? '#ffffff' : '#ffefb3';
    alertDiv.style.padding = '1rem 1.75rem';
    alertDiv.style.borderRadius = '8px';
    alertDiv.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
    alertDiv.style.fontWeight = '600';
    alertDiv.style.fontSize = '0.9rem';
    alertDiv.style.zIndex = '99999';
    alertDiv.style.transition = 'all 0.3s ease';
    alertDiv.textContent = msg;

    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
      alertDiv.style.opacity = '0';
      alertDiv.style.transform = 'translateY(10px)';
      setTimeout(() => alertDiv.remove(), 300);
    }, 3000);
  };

  // --- 3. CORE SUB-MODULE FORM SUBMISSIONS ---
  
  // A. Hero Banner Form
  const heroForm = document.getElementById('hero-editor-form');
  if (heroForm) {
    heroForm.addEventListener('submit', (e) => {
      e.preventDefault();
      db.hero.headline = document.getElementById('hero-headline').value;
      db.hero.subheadline = document.getElementById('hero-subheadline').value;
      db.hero.cta_primary = document.getElementById('hero-cta-primary').value;
      db.hero.cta_secondary = document.getElementById('hero-cta-secondary').value;
      db.hero.bg_image = document.getElementById('hero-bg').value;
      
      saveContentToServer();
    });
  }

  // B. Contact & Locations Form
  const contactForm = document.getElementById('contact-editor-form');
  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();
      db.contact.clinic_name = document.getElementById('contact-name').value;
      db.contact.address = document.getElementById('contact-address').value;
      db.contact.whatsapp = document.getElementById('contact-whatsapp').value;
      db.contact.whatsapp_raw = document.getElementById('contact-whatsapp-raw').value;
      db.contact.email = document.getElementById('contact-email').value;
      db.contact.instagram = document.getElementById('contact-ig-handle').value;
      db.contact.map_link = document.getElementById('contact-map-link').value;
      db.contact.map_embed_url = document.getElementById('contact-map-embed').value;

      saveContentToServer();
    });
  }

  // C. Trust stats Ribbon Form
  const trustForm = document.getElementById('trust-editor-form');
  if (trustForm) {
    trustForm.addEventListener('submit', (e) => {
      e.preventDefault();
      db.trust_bar.stat1_num = document.getElementById('trust-s1-num').value;
      db.trust_bar.stat1_lbl = document.getElementById('trust-s1-lbl').value;
      db.trust_bar.stat2_num = document.getElementById('trust-s2-num').value;
      db.trust_bar.stat2_lbl = document.getElementById('trust-s2-lbl').value;
      db.trust_bar.stat3_num = document.getElementById('trust-s3-num').value;
      db.trust_bar.stat3_lbl = document.getElementById('trust-s3-lbl').value;
      db.trust_bar.stat4_num = document.getElementById('trust-s4-num').value;
      db.trust_bar.stat4_lbl = document.getElementById('trust-s4-lbl').value;

      saveContentToServer();
    });
  }

  // D. SEO Configurations Form
  const seoForm = document.getElementById('seo-editor-form');
  if (seoForm) {
    seoForm.addEventListener('submit', (e) => {
      e.preventDefault();
      db.seo.title = document.getElementById('seo-title').value;
      db.seo.meta_desc = document.getElementById('seo-desc').value;
      db.seo.keywords = document.getElementById('seo-keywords').value;
      db.seo.favicon = document.getElementById('seo-favicon').value;
      db.seo.og_image = document.getElementById('seo-og').value;

      saveContentToServer();
    });
  }

  // --- 4. SERVICES MANAGER OPERATIONS ---
  const servicesList = document.getElementById('services-sortable-list');
  
  // Re-draw service cards inside CMS
  const renderServicesList = () => {
    if (!servicesList) return;
    servicesList.innerHTML = '';
    
    db.services.forEach((srv, idx) => {
      const isChecked = (!isset(srv.visible) || srv.visible === true) ? 'checked' : '';
      const card = document.createElement('div');
      card.className = 'service-item-card';
      card.setAttribute('draggable', 'true');
      card.setAttribute('data-array-index', idx);
      
      card.innerHTML = `
        <div class="service-item-left">
          <span class="drag-handle">☰</span>
          <div class="service-item-info">
            <h4>${escapeHtml(srv.name)}</h4>
            <p>Slide Backdrop: ${escapeHtml(srv.number)}</p>
          </div>
        </div>
        <div class="service-item-actions">
          <button class="btn-edit-item edit-srv-trigger" data-index="${idx}">Edit Details</button>
          <label class="toggle-switch">
            <input type="checkbox" class="service-visibility-toggle" data-array-index="${idx}" ${isChecked}>
            <span class="slider"></span>
          </label>
        </div>
      `;
      servicesList.appendChild(card);
    });
    
    bindServicesEvents();
  };

  // Helper utility checks
  const isset = (val) => typeof val !== 'undefined';
  const escapeHtml = (text) => {
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return String(text).replace(/[&<>"']/g, m => map[m]);
  };

  // Bind visibility listeners & Drag/Drop anchors
  const bindServicesEvents = () => {
    // 1. Double click or edit triggers
    document.querySelectorAll('.edit-srv-trigger').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const idx = e.target.getAttribute('data-index');
        openServiceModal(idx);
      });
    });

    // 2. Visibility toggles
    document.querySelectorAll('.service-visibility-toggle').forEach(chk => {
      chk.addEventListener('change', (e) => {
        const idx = e.target.getAttribute('data-array-index');
        db.services[idx].visible = e.target.checked;
        saveContentToServer();
      });
    });

    // 3. Drag and Drop Sortable implementations
    let dragIndex = null;
    const cards = document.querySelectorAll('#services-sortable-list .service-item-card');
    
    cards.forEach(card => {
      card.addEventListener('dragstart', (e) => {
        dragIndex = parseInt(card.getAttribute('data-array-index'));
        e.dataTransfer.effectAllowed = 'move';
      });

      card.addEventListener('dragover', (e) => {
        e.preventDefault();
      });

      card.addEventListener('drop', (e) => {
        e.preventDefault();
        const dropIndex = parseInt(card.getAttribute('data-array-index'));
        
        if (dragIndex !== null && dragIndex !== dropIndex) {
          // Reposition element inside JS database
          const draggedItem = db.services.splice(dragIndex, 1)[0];
          db.services.splice(dropIndex, 0, draggedItem);
          
          dragIndex = null;
          renderServicesList();
        }
      });
    });
  };

  // Drag and drop sorting publish trigger
  window.saveServicesOrder = () => {
    saveContentToServer();
  };

  // Modal control functions
  window.openServiceModal = (idx) => {
    const modal = document.getElementById('modal-service');
    const form = document.getElementById('service-item-editor-form');
    const mTitle = document.getElementById('srv-modal-title');
    const eIdx = document.getElementById('srv-edit-index');

    form.reset();

    if (idx === 'new') {
      mTitle.textContent = 'Add New Treatment';
      eIdx.value = 'new';
    } else {
      const srv = db.services[idx];
      mTitle.textContent = `Edit Details: ${srv.name}`;
      eIdx.value = idx;

      document.getElementById('srv-edit-name').value = srv.name;
      document.getElementById('srv-edit-num').value = srv.number;
      document.getElementById('srv-edit-desc').value = srv.description;
      document.getElementById('srv-edit-b1').value = srv.benefits[0] || '';
      document.getElementById('srv-edit-b2').value = srv.benefits[1] || '';
      document.getElementById('srv-edit-b3').value = srv.benefits[2] || '';
      document.getElementById('srv-edit-bg').value = srv.bg_image;
    }

    modal.classList.add('active');
  };

  window.saveServiceItemForm = (e) => {
    e.preventDefault();
    const idx = document.getElementById('srv-edit-index').value;
    
    const srvData = {
      id: idx === 'new' ? 'srv_' + Date.now() : db.services[idx].id,
      name: document.getElementById('srv-edit-name').value,
      number: document.getElementById('srv-edit-num').value,
      description: document.getElementById('srv-edit-desc').value,
      benefits: [
        document.getElementById('srv-edit-b1').value,
        document.getElementById('srv-edit-b2').value,
        document.getElementById('srv-edit-b3').value
      ],
      bg_image: document.getElementById('srv-edit-bg').value,
      visible: idx === 'new' ? true : (isset(db.services[idx].visible) ? db.services[idx].visible : true)
    };

    if (idx === 'new') {
      db.services.push(srvData);
    } else {
      db.services[idx] = srvData;
    }

    closeModal('modal-service');
    renderServicesList();
    saveContentToServer();
  };

  // --- 5. TESTIMONIALS MANAGER CRUD ---
  const testimonialsList = document.getElementById('testimonials-cms-list');

  const renderTestimonialsList = () => {
    if (!testimonialsList) return;
    testimonialsList.innerHTML = '';

    db.testimonials.forEach((test, idx) => {
      const isChecked = (!isset(test.visible) || test.visible === true) ? 'checked' : '';
      const card = document.createElement('div');
      card.className = 'service-item-card';
      card.style.cursor = 'default';
      card.innerHTML = `
        <div class="service-item-left">
          <div class="service-item-info">
            <h4>${escapeHtml(test.name)} (${escapeHtml(test.treatment)})</h4>
            <p style="font-style: italic;">"${escapeHtml(test.quote.substring(0, 70))}..."</p>
          </div>
        </div>
        <div class="service-item-actions">
          <button class="btn-edit-item edit-test-trigger" data-index="${idx}">Edit</button>
          <button class="btn-edit-item delete-test-trigger" data-index="${idx}" style="color: #e53e3e;">Delete</button>
          <label class="toggle-switch">
            <input type="checkbox" class="testimonial-visibility-toggle" data-array-index="${idx}" ${isChecked}>
            <span class="slider"></span>
          </label>
        </div>
      `;
      testimonialsList.appendChild(card);
    });

    bindTestimonialEvents();
  };

  const bindTestimonialEvents = () => {
    // 1. Edit modal trigger
    document.querySelectorAll('.edit-test-trigger').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const idx = e.target.getAttribute('data-index');
        openTestimonialModal(idx);
      });
    });

    // 2. Delete review trigger
    document.querySelectorAll('.delete-test-trigger').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const idx = e.target.getAttribute('data-index');
        deleteTestimonial(idx);
      });
    });

    // 3. Toggle Visibility
    document.querySelectorAll('.testimonial-visibility-toggle').forEach(chk => {
      chk.addEventListener('change', (e) => {
        const idx = e.target.getAttribute('data-array-index');
        db.testimonials[idx].visible = e.target.checked;
        saveContentToServer();
      });
    });
  };

  window.openTestimonialModal = (idx) => {
    const modal = document.getElementById('modal-testimonial');
    const form = document.getElementById('testimonial-item-editor-form');
    const mTitle = document.getElementById('test-modal-title');
    const eIdx = document.getElementById('test-edit-index');

    form.reset();

    if (idx === 'new') {
      mTitle.textContent = 'Add Patient Review';
      eIdx.value = 'new';
    } else {
      const review = db.testimonials[idx];
      mTitle.textContent = `Edit Review: ${review.name}`;
      eIdx.value = idx;

      document.getElementById('test-edit-name').value = review.name;
      document.getElementById('test-edit-treatment').value = review.treatment;
      document.getElementById('test-edit-rating').value = review.rating;
      document.getElementById('test-edit-quote').value = review.quote;
    }

    modal.classList.add('active');
  };

  window.saveTestimonialItemForm = (e) => {
    e.preventDefault();
    const idx = document.getElementById('test-edit-index').value;

    const testData = {
      name: document.getElementById('test-edit-name').value,
      treatment: document.getElementById('test-edit-treatment').value,
      rating: parseInt(document.getElementById('test-edit-rating').value),
      quote: document.getElementById('test-edit-quote').value,
      visible: idx === 'new' ? true : (isset(db.testimonials[idx].visible) ? db.testimonials[idx].visible : true)
    };

    if (idx === 'new') {
      db.testimonials.push(testData);
    } else {
      db.testimonials[idx] = testData;
    }

    closeModal('modal-testimonial');
    renderTestimonialsList();
    saveContentToServer();
  };

  window.deleteTestimonial = (idx) => {
    if (confirm('Are you sure you want to permanently delete this review?')) {
      db.testimonials.splice(idx, 1);
      renderTestimonialsList();
      saveContentToServer();
    }
  };

  window.saveTestimonialsConfig = () => {
    saveContentToServer();
  };

  // --- 6. GALLERY & INSTAGRAM FALLBACK Grid ---
  const galleryList = document.getElementById('gallery-cms-list');

  const renderGalleryList = () => {
    if (!galleryList) return;
    galleryList.innerHTML = '';

    db.gallery.forEach((post, idx) => {
      const card = document.createElement('div');
      card.className = 'service-item-card';
      card.style.cursor = 'default';
      card.innerHTML = `
        <div class="service-item-left" style="width: 80%;">
          <img src="${escapeHtml(post.image)}" alt="thumbnail" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
          <div class="service-item-info" style="width: 100%;">
            <input type="text" class="form-input gallery-caption-input" data-index="${idx}" value="${escapeHtml(post.caption)}" placeholder="Caption this clinical picture" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; width: 90%;">
          </div>
        </div>
        <div class="service-item-actions">
          <input type="hidden" class="gallery-image-url" data-index="${idx}" value="${escapeHtml(post.image)}">
          <button class="btn-edit-item delete-gal-trigger" data-index="${idx}" style="color: #e53e3e;">Delete</button>
        </div>
      `;
      galleryList.appendChild(card);
    });

    bindGalleryEvents();
  };

  const bindGalleryEvents = () => {
    document.querySelectorAll('.delete-gal-trigger').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const idx = e.target.getAttribute('data-index');
        deleteGalleryItem(idx);
      });
    });
  };

  window.deleteGalleryItem = (idx) => {
    if (confirm('Remove this photo from the landing page?')) {
      db.gallery.splice(idx, 1);
      renderGalleryList();
      saveContentToServer();
    }
  };

  window.saveGalleryModifications = () => {
    const inputs = document.querySelectorAll('.gallery-caption-input');
    inputs.forEach(input => {
      const idx = input.getAttribute('data-index');
      db.gallery[idx].caption = input.value;
    });

    saveContentToServer();
  };

  window.openGalleryModal = () => {
    document.getElementById('gallery-item-add-form').reset();
    document.getElementById('modal-gallery').classList.add('active');
  };

  window.addGalleryItem = (e) => {
    e.preventDefault();
    const newUrl = document.getElementById('gal-add-url').value;
    const newCaption = document.getElementById('gal-add-caption').value;

    db.gallery.push({
      image: newUrl,
      caption: newCaption
    });

    closeModal('modal-gallery');
    renderGalleryList();
    saveContentToServer();
  };

  // --- 7. MODALS CLOSURES UTILITIES ---
  window.closeModal = (id) => {
    document.getElementById(id).classList.remove('active');
  };

  // --- 8. ANNEALING STATS REFRESHER & MOCK FIREBASE CONFIGS ---
  const fetchStats = () => {
    fetch('../api/get_stats.php')
    .then(res => res.json())
    .then(payload => {
      if (payload.status === 'success') {
        document.getElementById('stat-views').textContent = payload.data.page_views;
        document.getElementById('stat-wa').textContent = payload.data.whatsapp_clicks;
        document.getElementById('stat-ig').textContent = payload.data.instagram_clicks;
      }
    })
    .catch(err => console.warn('Local analytics log statistics fetch offline.'));
  };

  // Trigger stats on load and set auto-polling intervals
  fetchStats();
  setInterval(fetchStats, 10000); // Poll local stats every 10 seconds for real-time dashboard updates

  const fbForm = document.getElementById('firebase-credentials-form');
  if (fbForm) {
    fbForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const apiKey = document.getElementById('fb-apikey').value;
      const projectId = document.getElementById('fb-projectid').value;
      
      if (apiKey && projectId) {
        localStorage.setItem('fb_active', 'true');
        document.getElementById('fb-status-badge').textContent = 'Cloud Firebase Synchronized';
        document.getElementById('fb-status-badge').classList.remove('offline');
        showGlobalAlert('Firebase configuration linked. Cloud integration is active!');
      } else {
        showGlobalAlert('Please fill in complete Firebase credentials.', true);
      }
    });

    // Check localStorage on load
    if (localStorage.getItem('fb_active') === 'true') {
      document.getElementById('fb-status-badge').textContent = 'Cloud Firebase Synchronized';
      document.getElementById('fb-status-badge').classList.remove('offline');
      document.getElementById('fb-apikey').value = 'AIzaSyA1S_PreloadedMockCredentials';
      document.getElementById('fb-projectid').value = 'chiro-airoli-navi-mumbai';
    }
  }

  // --- 9. INITIAL LIFECYCLE SEEDING ---
  // Run on dashboard open to load existing contents inside managers
  renderServicesList();
  renderTestimonialsList();
  renderGalleryList();
});
