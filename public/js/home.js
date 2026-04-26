/* ==============================================
   PAWFECTMATCH - HOMEPAGE JAVASCRIPT
   Stats Counter Animation & Scroll Effects
   ============================================== */

(function () {
  'use strict';

  /* ============================================
     ANIMATED STATS COUNTER
     Uses IntersectionObserver to trigger count-up
     animation when stats section scrolls into view
     ============================================ */
  function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    if (!counters.length) return;

    const duration = 2000; // 2 seconds

    // Ease-out quart for smooth deceleration
    function easeOutQuart(t) {
      return 1 - Math.pow(1 - t, 4);
    }

    function countUp(el) {
      const target = parseInt(el.getAttribute('data-target'), 10);
      if (isNaN(target)) return;

      const start = performance.now();

      function update(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = easeOutQuart(progress);
        const current = Math.round(eased * target);

        el.textContent = current.toLocaleString();

        if (progress < 1) {
          requestAnimationFrame(update);
        }
      }

      requestAnimationFrame(update);
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            counters.forEach(countUp);
            observer.disconnect(); // Only animate once
          }
        });
      },
      { threshold: 0.3 }
    );

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
      observer.observe(statsSection);
    }
  }

  /* ============================================
     SCROLL-TRIGGERED FADE-IN ANIMATIONS
     ============================================ */
  function initScrollAnimations() {
    const elements = document.querySelectorAll('.fade-in-up');
    if (!elements.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15, rootMargin: '0px 0px -50px 0px' }
    );

    elements.forEach((el) => observer.observe(el));
  }

  /* ============================================
     NAVBAR SCROLL EFFECT
     Adds background to navbar on scroll
     ============================================ */
  function initNavbarScroll() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    function onScroll() {
      if (window.scrollY > 80) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll(); // Check initial state
  }

  /* ============================================
     VIDEO HERO - handle fallback
     ============================================ */
  function initHeroVideo() {
    const video = document.querySelector('.hero-video');
    if (!video) return;

    // Gracefully handle missing video
    video.addEventListener('error', function () {
      this.style.display = 'none';
    });
  }

  /* ============================================
     MAGNETIC BUTTONS
     ============================================ */
  function initMagneticButtons() {
    const magneticElements = document.querySelectorAll('[data-magnetic]');
    
    magneticElements.forEach((btn) => {
      btn.addEventListener('mousemove', function(e) {
        const bounds = btn.getBoundingClientRect();
        const x = e.clientX - bounds.left - bounds.width / 2;
        const y = e.clientY - bounds.top - bounds.height / 2;
        const pull = 0.3; 
        
        btn.style.transform = `translate(${x * pull}px, ${y * pull}px)`;
      });
      
      btn.addEventListener('mouseleave', function() {
        btn.style.transform = '';
        btn.style.transition = 'transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1)';
      });
      
      btn.addEventListener('mouseenter', function() {
        btn.style.transition = 'transform 0.1s linear';
      });
    });
  }

  /* ============================================
     HERO INITIAL ANIMATION
     ============================================ */
  function initHeroAnimation() {
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
      setTimeout(() => {
        heroContent.classList.add('is-loaded');
      }, 100);
    }
  }

  /* ============================================
     INITIALIZE ON DOM READY
     ============================================ */
  document.addEventListener('DOMContentLoaded', function () {
    animateCounters();
    initScrollAnimations();
    initNavbarScroll();
    initHeroVideo();
    initHeroAnimation();
    initMagneticButtons();
  });
})();
