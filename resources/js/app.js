/**
 * PAWFECTMATCH - Main JavaScript
 * Core functionality for the application
 */

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  initAOS();
  initNavbar();
  initVideoHero();
  initCounters();
  initMobileMenu();
  initFormLabels();
});

/**
 * Initialize AOS (Animate On Scroll)
 */
function initAOS() {
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      easing: 'cubic-bezier(0.22, 1, 0.36, 1)',
      once: true,
      offset: 100,
    });
  }
}

/**
 * Navbar scroll effect
 */
function initNavbar() {
  const navbar = document.getElementById('navbar');
  if (!navbar) return;
  
  window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
}

/**
 * Video hero load handler
 */
function initVideoHero() {
  const video = document.querySelector('.hero-video');
  if (!video) return;
  
  video.addEventListener('loadeddata', function() {
    video.classList.add('loaded');
  });
}

/**
 * Animated counters (number count up)
 */
function initCounters() {
  const counters = document.querySelectorAll('[data-counter]');
  if (counters.length === 0) return;
  
  const observerOptions = {
    threshold: 0.3
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const counter = entry.target;
        const target = parseInt(counter.getAttribute('data-counter'));
        animateCounter(counter, 0, target, 2000);
        observer.unobserve(counter);
      }
    });
  }, observerOptions);
  
  counters.forEach(counter => observer.observe(counter));
}

function animateCounter(element, start, end, duration) {
  const range = end - start;
  const increment = range / (duration / 16);
  let current = start;
  
  const timer = setInterval(function() {
    current += increment;
    if (current >= end) {
      element.textContent = formatNumber(end);
      clearInterval(timer);
    } else {
      element.textContent = formatNumber(Math.floor(current));
    }
  }, 16);
}

function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

/**
 * Mobile menu toggle
 */
function initMobileMenu() {
  const toggle = document.getElementById('mobileToggle');
  const menu = document.getElementById('navbarMenu');
  
  if (!toggle || !menu) return;
  
  toggle.addEventListener('click', function() {
    menu.classList.toggle('active');
  });
  
  // Close on outside click
  document.addEventListener('click', function(e) {
    if (!toggle.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.remove('active');
    }
  });
}

/**
 * Floating labels for forms
 */
function initFormLabels() {
  const inputs = document.querySelectorAll('input, textarea');
  
  inputs.forEach(input => {
    // Add floating class on focus
    input.addEventListener('focus', function() {
      const label = this.previousElementSibling;
      if (label && label.tagName === 'LABEL') {
        label.classList.add('floating');
      }
    });
    
    // Remove if empty on blur
    input.addEventListener('blur', function() {
      const label = this.previousElementSibling;
      if (label && label.tagName === 'LABEL' && !this.value) {
        label.classList.remove('floating');
      }
    });
    
    // Keep floating if has value
    if (input.value) {
      const label = input.previousElementSibling;
      if (label && label.tagName === 'LABEL') {
        label.classList.add('floating');
      }
    }
  });
}

/**
 * Toast notification system
 */
const Toast = {
  container: null,
  
  init: function() {
    if (!this.container) {
      this.container = document.createElement('div');
      this.container.className = 'toast-container';
      this.container.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 10px;
      `;
      document.body.appendChild(this.container);
    }
  },
  
  show: function(message, type = 'info', duration = 3000) {
    this.init();
    
    const toast = document.createElement('div');
    toast.style.cssText = `
      background: white;
      padding: 1rem 1.5rem;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      border-left: 4px solid ${type === 'success' ? '#4ECDC4' : type === 'error' ? '#FF6B6B' : '#1A2332'};
      opacity: 0;
      transform: translateX(100%);
      transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    `;
    toast.textContent = message;
    
    this.container.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
      toast.style.opacity = '1';
      toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Remove after duration
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(100%)';
      setTimeout(() => toast.remove(), 300);
    }, duration);
  },
  
  success: function(message, duration) {
    this.show(message, 'success', duration);
  },
  
  error: function(message, duration) {
    this.show(message, 'error', duration);
  }
};

window.Toast = Toast;

/**
 * Pet Detail - Gallery
 */
function initPetGallery() {
  const mainImage = document.querySelector('.gallery-main img');
  const thumbnails = document.querySelectorAll('.gallery-thumbnail');
  
  if (!mainImage || thumbnails.length === 0) return;
  
  thumbnails.forEach(thumb => {
    thumb.addEventListener('click', function() {
      const newSrc = this.querySelector('img').src;
      
      mainImage.style.opacity = '0';
      setTimeout(() => {
        mainImage.src = newSrc;
        mainImage.style.opacity = '1';
      }, 200);
      
      thumbnails.forEach(t => t.classList.remove('active'));
      this.classList.add('active');
    });
  });
  
  if (thumbnails.length > 0) {
    thumbnails[0].classList.add('active');
  }
}

// Initialize gallery if on pet detail page
if (document.querySelector('.gallery-main')) {
  initPetGallery();
}

/**
 * Favorite toggle (AJAX)
 */
function toggleFavorite(petId, button) {
  fetch(`/favorites/toggle/${petId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const icon = button.querySelector('svg');
      if (data.favorited) {
        icon.style.fill = '#FF6B6B';
        icon.style.stroke = '#FF6B6B';
        button.classList.add('is-favorited');
        Toast.success('Added to favorites!');
      } else {
        icon.style.fill = 'none';
        icon.style.stroke = 'currentColor';
        button.classList.remove('is-favorited');
        Toast.show('Removed from favorites');
      }
    }
  })
  .catch(err => {
    Toast.error('Please sign in to save favorites');
  });
}

window.toggleFavorite = toggleFavorite;

/**
 * Initialize favorite hearts on page load.
 * Fetches the user's favorited pet IDs and fills hearts red.
 */
function initFavoriteHearts() {
  fetch('/favorites/ids', {
    headers: { 'Accept': 'application/json' }
  })
  .then(res => {
    if (!res.ok) return null; // Not authenticated
    return res.json();
  })
  .then(data => {
    if (!data || !data.ids) return;
    window._favoritedPetIds = new Set(data.ids.map(id => Number(id)));
    applyFavoriteHearts();
  })
  .catch(() => {
    // Silently fail — user is not logged in
  });
}

/**
 * Apply red fill to all favorite buttons whose pet ID is in the favorited set.
 * Can be called after dynamic content loads (e.g. AJAX pet grid).
 */
function applyFavoriteHearts() {
  if (!window._favoritedPetIds || window._favoritedPetIds.size === 0) return;

  document.querySelectorAll('.card-fav-btn').forEach(btn => {
    // Extract pet ID from the onclick attribute
    const onclickAttr = btn.getAttribute('onclick') || '';
    const match = onclickAttr.match(/toggleFavorite\((\d+)/);
    if (!match) return;

    const petId = Number(match[1]);
    if (window._favoritedPetIds.has(petId)) {
      const icon = btn.querySelector('svg');
      if (icon) {
        icon.style.fill = '#FF6B6B';
        icon.style.stroke = '#FF6B6B';
      }
      btn.classList.add('is-favorited');
    }
  });
}

window.applyFavoriteHearts = applyFavoriteHearts;

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', initFavoriteHearts);

/**
 * Filter form auto-submit
 */
function initFilters() {
  const filterSelects = document.querySelectorAll('.filter-select');
  
  filterSelects.forEach(select => {
    select.addEventListener('change', function() {
      this.closest('form').submit();
    });
  });
}

if (document.querySelector('.filters-section')) {
  initFilters();
}

/**
 * Admin - Charts
 */
function initAdminCharts() {
  // Adoptions line chart
  const adoptionsCtx = document.getElementById('adoptionsChart');
  if (adoptionsCtx && typeof Chart !== 'undefined') {
    new Chart(adoptionsCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Adoptions',
          data: [12, 19, 15, 25, 22, 30],
          borderColor: '#FF6B6B',
          backgroundColor: 'rgba(255, 107, 107, 0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });
  }
  
  // Species doughnut chart
  const speciesCtx = document.getElementById('speciesChart');
  if (speciesCtx && typeof Chart !== 'undefined') {
    new Chart(speciesCtx, {
      type: 'doughnut',
      data: {
        labels: ['Dogs', 'Cats'],
        datasets: [{
          data: [60, 40],
          backgroundColor: ['#FF6B6B', '#4ECDC4']
        }]
      }
    });
  }
}

// Initialize admin charts if on admin page
if (document.querySelector('#adoptionsChart')) {
  initAdminCharts();
}