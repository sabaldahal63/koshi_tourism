/**
 * KoshiApp - Core application module
 * Handles: booking modal, localStorage persistence, badge counter, cancel booking
 */

(function () {
  'use strict';

  // ── Storage helpers ──────────────────────────────────────────────────────
  const STORAGE_KEY = 'koshi_bookings_v2';

  function getBookings() {
    try {
      return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    } catch (e) {
      return [];
    }
  }

  function saveBookings(list) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
  }

  // ── Badge counter ────────────────────────────────────────────────────────
  function updateBadge() {
    const badge = document.getElementById('booking-count-badge');
    if (!badge) return;
    const count = getBookings().length;
    if (count > 0) {
      badge.textContent = count;
      badge.style.display = 'inline-flex';
    } else {
      badge.style.display = 'none';
    }
  }

  // ── Toast notification ───────────────────────────────────────────────────
  function showToast(msg) {
    const toast = document.getElementById('toast-notif');
    if (!toast) return;
    const msgEl = toast.querySelector('.toast-msg');
    if (msgEl) msgEl.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }

  // ── Modal state ──────────────────────────────────────────────────────────
  let _currentBooking = {};

  function openBooking(type, id, name, image, pricePerUnit) {
    _currentBooking = { type, id, name, image, pricePerUnit };

    // Populate modal summary
    const imgEl   = document.getElementById('book-summary-img');
    const titleEl = document.getElementById('book-summary-title');
    const metaEl  = document.getElementById('book-summary-meta');
    const label   = document.getElementById('book-nights-label');

    if (imgEl)   imgEl.src = image;
    if (titleEl) titleEl.textContent = name;
    if (metaEl)  metaEl.textContent  = type === 'guide'
      ? `Per Day Rate: Rs. ${pricePerUnit.toLocaleString()}`
      : `Per Night / Per Traveler: Rs. ${pricePerUnit.toLocaleString()}`;
    if (label)   label.textContent   = type === 'guide' ? 'Days' : 'Nights';

    updateCalcTotal();

    const modal = document.getElementById('booking-modal');
    if (modal) {
      modal.classList.add('visible');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeModal() {
    const modal = document.getElementById('booking-modal');
    if (modal) {
      modal.classList.remove('visible');
      document.body.style.overflow = '';
    }
  }

  function updateCalcTotal() {
    const guests = parseInt(document.getElementById('book-guests')?.value || 1);
    const nights = parseInt(document.getElementById('book-nights')?.value || 1);
    const price  = (_currentBooking.pricePerUnit || 0) * guests * nights;
    const el     = document.getElementById('calc-total');
    if (el) el.textContent = 'Rs. ' + price.toLocaleString();
  }

  // ── Cancel booking ───────────────────────────────────────────────────────
  function cancelBooking(idx) {
    if (!confirm('Cancel this reservation?')) return;
    const list = getBookings();
    list.splice(idx, 1);
    saveBookings(list);
    showToast('Reservation cancelled.');
    updateBadge();
    // Refresh the bookings page display
    const container = document.getElementById('bookings-container');
    if (container) {
      // Trigger a page reload so the list re-renders cleanly
      window.location.reload();
    }
  }

  // ── Initialise modal events ──────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', () => {
    // Close button
    const closeBtn = document.getElementById('modal-close-btn');
    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    // Click outside modal
    const modalOverlay = document.getElementById('booking-modal');
    if (modalOverlay) {
      modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) closeModal();
      });
    }

    // Recalculate totals on input change
    ['book-guests', 'book-nights'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('input', updateCalcTotal);
    });

    // Form submit — save booking to localStorage
    const form = document.getElementById('book-modal-form');
    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();

        const name   = document.getElementById('book-name').value.trim();
        const email  = document.getElementById('book-email').value.trim();
        const date   = document.getElementById('book-date').value;
        const guests = parseInt(document.getElementById('book-guests').value);
        const nights = parseInt(document.getElementById('book-nights').value);
        const total  = (_currentBooking.pricePerUnit || 0) * guests * nights;

        const booking = {
          id:           _currentBooking.id,
          type:         _currentBooking.type,
          title:        _currentBooking.name,
          image:        _currentBooking.image,
          pricePerUnit: _currentBooking.pricePerUnit,
          name,
          email,
          date,
          guests,
          nights,
          total,
          bookedAt: new Date().toISOString(),
        };

        const list = getBookings();
        list.push(booking);
        saveBookings(list);

        closeModal();
        form.reset();
        updateBadge();
        showToast(`✓ Reservation confirmed for "${booking.title}"!`);
      });
    }

    // Update badge on load
    updateBadge();
  });

  // ── Public API ───────────────────────────────────────────────────────────
  window.KoshiApp = {
    openBooking,
    cancelBooking,
    getBookings,
  };

})();
