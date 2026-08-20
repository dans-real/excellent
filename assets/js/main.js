/* ============================================================
   UKM-F EXCELLENT — main.js v3
   Awwwards + Godly + SiteInspire + Mobbin design principles
   ============================================================ */
'use strict';

/* ── THEME ────────────────────────────────────────────────── */
const html     = document.documentElement;
const themeBtn = document.getElementById('themeBtn');
const themeBtnM = document.getElementById('themeBtnM');

(function initTheme() {
  const saved = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-theme', saved);
  setThemeIcon(saved);
})();

function setThemeIcon(theme) {
  const icon = theme === 'dark' ? '☀️' : '🌙';
  if (themeBtn)  themeBtn.textContent = icon;
  if (themeBtnM) themeBtnM.textContent = icon + '  ' + (theme === 'dark' ? 'Light Mode' : 'Dark Mode');
}

function toggleTheme() {
  const cur  = html.getAttribute('data-theme');
  const next = cur === 'dark' ? 'light' : 'dark';
  html.setAttribute('data-theme', next);
  localStorage.setItem('theme', next);
  setThemeIcon(next);
}

if (themeBtn) themeBtn.addEventListener('click', toggleTheme);

/* ── NAVBAR SCROLL ────────────────────────────────────────── */
const navbar = document.getElementById('navbar');
let lastScrollY = 0;

window.addEventListener('scroll', () => {
  const y = window.scrollY;
  if (navbar) navbar.classList.toggle('scrolled', y > 20);
  lastScrollY = y;
}, { passive: true });

/* ── HAMBURGER ────────────────────────────────────────────── */
const hamburger  = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobileMenu');

function toggleMenu() {
  if (!hamburger || !mobileMenu) return;
  hamburger.classList.toggle('open');
  mobileMenu.classList.toggle('open');
  document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
}
if (hamburger) hamburger.addEventListener('click', toggleMenu);

// Close on outside click
document.addEventListener('click', e => {
  if (mobileMenu?.classList.contains('open') &&
      !mobileMenu.contains(e.target) &&
      !hamburger?.contains(e.target)) {
    toggleMenu();
  }
});

/* ── SCROLL REVEAL ────────────────────────────────────────── */
function initReveal() {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) {
    document.querySelectorAll('.reveal').forEach(el => el.classList.add('visible'));
    return;
  }
  const els = document.querySelectorAll('.reveal:not(.visible)');
  if (!els.length) return;
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
  els.forEach(el => obs.observe(el));
}
document.addEventListener('DOMContentLoaded', initReveal);

/* ── COUNTER ANIMATION ────────────────────────────────────── */
function animateCounter(el, target, suffix, duration = 1400) {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    el.textContent = target + suffix; return;
  }
  let start = null;
  const step = ts => {
    if (!start) start = ts;
    const prog = Math.min((ts - start) / duration, 1);
    const ease = 1 - Math.pow(1 - prog, 3); // cubic ease-out
    el.textContent = Math.floor(ease * target) + suffix;
    if (prog < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}

document.addEventListener('DOMContentLoaded', () => {
  const stats = [
    { id: 'stat1', suffix: '+' },
    { id: 'stat2', suffix: '+' },
    { id: 'stat3', suffix: '' },
  ];
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        stats.forEach(s => {
          const el = document.getElementById(s.id);
          if (el && !el.dataset.animated) {
            el.dataset.animated = 'true';
            const target = parseInt(el.dataset.target || '0');
            if (prefersReduced) { el.textContent = target + s.suffix; }
            else { setTimeout(() => animateCounter(el, target, s.suffix), 300); }
          }
        });
        obs.disconnect();
      }
    });
  }, { threshold: 0.5 });

  const heroStats = document.querySelector('.hero-stats');
  if (heroStats) obs.observe(heroStats);
});

/* ── LIGHTBOX ─────────────────────────────────────────────── */
function openLightbox(title, desc, imgSrc, imgBg) {
  const lb     = document.getElementById('lightbox');
  const lbImg  = document.getElementById('lb-img');
  const lbT    = document.getElementById('lb-title');
  const lbD    = document.getElementById('lb-desc');
  if (!lb) return;
  if (lbT) lbT.textContent = title;
  if (lbD) lbD.textContent = desc;
  if (lbImg) {
    if (imgSrc) {
      lbImg.innerHTML = `<img src="${imgSrc}" alt="${title}" loading="lazy">`;
    } else {
      lbImg.style.background = imgBg || '#1E3A5F';
      lbImg.innerHTML = '<span style="font-size:5rem">🖼️</span>';
    }
  }
  lb.classList.add('open');
  document.body.style.overflow = 'hidden';
  lb.querySelector('.lb-close')?.focus();
}

function closeLightbox() {
  document.getElementById('lightbox')?.classList.remove('open');
  document.body.style.overflow = '';
}

document.addEventListener('click', e => {
  const lb = document.getElementById('lightbox');
  if (lb && e.target === lb) closeLightbox();
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeLightbox();
});

/* ── FLASH TOAST ──────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  const toast = document.getElementById('flash-toast');
  if (!toast) return;
  toast.style.transition = 'opacity .4s ease, transform .4s ease';
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(-8px)';
    setTimeout(() => toast.remove(), 420);
  }, 3500);
});

/* ── FORM ENHANCEMENT ─────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  // Floating label effect on focus
  document.querySelectorAll('.form-input, .form-textarea, .form-select').forEach(el => {
    el.addEventListener('focus', () => {
      el.parentElement?.classList.add('focused');
    });
    el.addEventListener('blur', () => {
      el.parentElement?.classList.remove('focused');
    });
  });

  // Auto slug from title (admin pages)
  const titleInput = document.querySelector('[name="title"]');
  const slugInput  = document.querySelector('[name="slug"]');
  if (titleInput && slugInput && !slugInput.value) {
    titleInput.addEventListener('input', () => {
      slugInput.value = titleInput.value
        .toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s]+/g, '-');
    });
  }
});

/* ── CARD TILT (subtle, desktop only) ─────────────────────── */
if (window.matchMedia('(hover: hover) and (pointer: fine)').matches &&
    !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.event-card, .member-card').forEach(card => {
      card.addEventListener('mousemove', e => {
        const r = card.getBoundingClientRect();
        const x = ((e.clientX - r.left) / r.width  - 0.5) * 6;
        const y = ((e.clientY - r.top)  / r.height - 0.5) * 6;
        card.style.transform = `translateY(-5px) rotateY(${x}deg) rotateX(${-y}deg)`;
      });
      card.addEventListener('mouseleave', () => {
        card.style.transform = '';
      });
    });
  });
}

/* ── SMOOTH ACTIVE NAV INDICATOR ─────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  const currentPath = window.location.pathname;
  document.querySelectorAll('.nav-links a').forEach(a => {
    const href = a.getAttribute('href') || '';
    if (currentPath.endsWith(href) || (href === 'index.php' && currentPath.endsWith('/'))) {
      a.classList.add('active');
    }
  });
});

/* ── KEYBOARD NAVIGATION ──────────────────────────────────── */
document.addEventListener('keydown', e => {
  if (e.key === 'Tab') document.body.classList.add('using-keyboard');
});
document.addEventListener('mousedown', () => {
  document.body.classList.remove('using-keyboard');
});
