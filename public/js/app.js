/**
 * KoshiApp - Core application module for Koshi Province Tourism Hub
 * Handles: Full-stack database bookings, 2-step modal with payment (eSewa/Khalti/Mobile Banking)
 */

(function () {
  'use strict';

  const getBaseUrl = () => (window.APP_URL || '').replace(/\/+$/, '');
  const getCsrfToken = () => window.CSRF_TOKEN || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  // Toast notification helper
  function showToast(msg, isError = false) {
    const toast = document.getElementById('toast-notif');
    if (!toast) return;
    const msgEl = toast.querySelector('.toast-msg');
    const iconEl = toast.querySelector('.toast-icon');
    if (msgEl) msgEl.textContent = msg;
    if (iconEl) iconEl.textContent = isError ? '✕' : '✓';
    toast.style.borderColor = isError ? 'var(--danger, #ef4444)' : 'rgba(16,185,129,0.3)';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 4000);
  }

  // Update navigation badge count from server
  async function updateBadge() {
    const badge = document.getElementById('booking-count-badge');
    if (!badge) return;
    try {
      const res = await fetch(`${getBaseUrl()}/api/bookings`, { headers: { 'Accept': 'application/json' } });
      if (res.ok) {
        const json = await res.json();
        const count = Array.isArray(json.data) ? json.data.length : 0;
        if (count > 0) {
          badge.textContent = count;
          badge.style.display = 'inline-flex';
        } else {
          badge.style.display = 'none';
        }
      }
    } catch (err) {}
  }

  // Active modal state
  let _currentBooking = {};
  let _selectedPayment = null;
  let _pendingPayload = null;

  // ─── Step Navigation ────────────────────────────────────────────────────────

  function goToStep(step) {
    const step1 = document.getElementById('booking-step-1');
    const step2 = document.getElementById('booking-step-2');
    const pill1 = document.getElementById('step-pill-1');
    const pill2 = document.getElementById('step-pill-2');
    const pill2Num = document.getElementById('step2-num');
    const headerIcon = document.getElementById('modal-header-icon');
    const headerTitle = document.getElementById('modal-header-title');

    if (step === 1) {
      if (step1) step1.style.display = 'block';
      if (step2) step2.style.display = 'none';
      if (pill1) { pill1.style.color = 'var(--primary)'; pill1.style.borderBottomColor = 'var(--primary)'; }
      if (pill2) { pill2.style.color = 'var(--text-muted)'; pill2.style.borderBottomColor = 'transparent'; }
      if (pill2Num) { pill2Num.style.background = 'rgba(255,255,255,0.1)'; pill2Num.style.color = 'var(--text-muted)'; }
      if (headerIcon) headerIcon.textContent = '📝';
      if (headerTitle) headerTitle.textContent = 'Reserve Now';
      _selectedPayment = null;
      resetPaymentUI();
    } else if (step === 2) {
      if (step1) step1.style.display = 'none';
      if (step2) step2.style.display = 'block';
      if (pill1) { pill1.style.color = 'rgba(255,255,255,0.5)'; pill1.style.borderBottomColor = 'transparent'; }
      if (pill2) { pill2.style.color = 'var(--primary)'; pill2.style.borderBottomColor = 'var(--primary)'; }
      if (pill2Num) { pill2Num.style.background = 'var(--primary)'; pill2Num.style.color = '#030712'; }
      if (headerIcon) headerIcon.textContent = '💳';
      if (headerTitle) headerTitle.textContent = 'Select Payment';

      // Fill payment summary bar
      const amount = _pendingPayload ? _pendingPayload.total : 0;
      const pTitle = document.getElementById('pay-summary-title');
      const pAmount = document.getElementById('pay-summary-amount');
      if (pTitle) pTitle.textContent = _currentBooking.name || 'Booking';
      if (pAmount) pAmount.textContent = 'Rs. ' + Number(amount).toLocaleString();
    }
  }

  function resetPaymentUI() {
    ['esewa','khalti','mobile_banking'].forEach(m => {
      const card = document.getElementById('pay-' + (m === 'mobile_banking' ? 'mobile' : m));
      const panel = document.getElementById('pay-panel-' + m);
      if (card) { card.style.borderColor = 'rgba(255,255,255,0.08)'; card.style.background = 'rgba(7,11,20,0.5)'; card.style.transform = ''; }
      if (panel) panel.style.display = 'none';
    });
    const btn = document.getElementById('pay-confirm-btn');
    if (btn) { btn.disabled = true; btn.style.opacity = '0.5'; }
  }

  // ─── Payment Method Selection ────────────────────────────────────────────────

  function selectPayment(method) {
    _selectedPayment = method;
    resetPaymentUI();

    // Highlight selected card
    const idMap = { esewa: 'pay-esewa', khalti: 'pay-khalti', mobile_banking: 'pay-mobile' };
    const colorMap = { esewa: 'rgba(96,187,70,0.35)', khalti: 'rgba(92,45,145,0.35)', mobile_banking: 'rgba(29,78,216,0.35)' };
    const card = document.getElementById(idMap[method]);
    if (card) {
      card.style.borderColor = colorMap[method];
      card.style.background = colorMap[method].replace('0.35', '0.12');
      card.style.transform = 'scale(1.03)';
    }

    // Show detail panel
    const panel = document.getElementById('pay-panel-' + method);
    if (panel) panel.style.display = 'block';

    // Enable confirm button
    const btn = document.getElementById('pay-confirm-btn');
    if (btn) { btn.disabled = false; btn.style.opacity = '1'; }
  }

  // ─── Confirm Payment & Save Booking ─────────────────────────────────────────

  async function confirmPayment() {
    if (!_selectedPayment || !_pendingPayload) {
      showToast('Please select a payment method to continue.', true);
      return;
    }

    // Collect payment detail
    let paymentDetail = '';
    let transactionId = 'TXN-' + Date.now();

    if (_selectedPayment === 'esewa') {
      const num = document.getElementById('esewa-number')?.value.trim();
      if (!num) { showToast('Please enter your eSewa mobile number.', true); return; }
      paymentDetail = 'eSewa: ' + num;
    } else if (_selectedPayment === 'khalti') {
      const num = document.getElementById('khalti-number')?.value.trim();
      if (!num) { showToast('Please enter your Khalti mobile number.', true); return; }
      paymentDetail = 'Khalti: ' + num;
    } else if (_selectedPayment === 'mobile_banking') {
      const bank = document.getElementById('bank-name')?.value;
      const acc  = document.getElementById('bank-account')?.value.trim();
      if (!bank) { showToast('Please select your bank.', true); return; }
      if (!acc)  { showToast('Please enter your account/mobile number.', true); return; }
      paymentDetail = bank + ': ' + acc;
    }

    const confirmBtn = document.getElementById('pay-confirm-btn');
    if (confirmBtn) { confirmBtn.disabled = true; confirmBtn.textContent = 'Processing payment...'; }

    // Simulate payment processing delay (1.5s)
    await new Promise(r => setTimeout(r, 1500));

    // Build final payload
    const payload = {
      ..._pendingPayload,
      payment_method: _selectedPayment,
      payment_status: 'paid',
      transaction_id: transactionId,
    };

    try {
      const res = await fetch(`${getBaseUrl()}/api/bookings`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(payload)
      });

      const json = await res.json();

      if (res.ok && json.success) {
        closeModal();
        document.getElementById('book-modal-form')?.reset();
        _pendingPayload = null;
        _selectedPayment = null;

        const methodLabel = { esewa: 'eSewa', khalti: 'Khalti', mobile_banking: 'Mobile Banking' }[payload.payment_method] || '';
        showToast(`✓ Payment via ${methodLabel} successful! Booking confirmed.`);
        updateBadge();

        if (window.location.pathname.includes('/bookings') || window.location.pathname.includes('/dashboard')) {
          setTimeout(() => window.location.reload(), 1200);
        }
      } else {
        showToast(json.error || json.message || 'Payment failed. Please try again.', true);
        if (confirmBtn) { confirmBtn.disabled = false; confirmBtn.textContent = '🔒 Pay & Confirm Booking'; confirmBtn.style.opacity = '1'; }
      }
    } catch (err) {
      console.error('Payment error:', err);
      showToast('Could not connect to server. Please ensure XAMPP is running.', true);
      if (confirmBtn) { confirmBtn.disabled = false; confirmBtn.textContent = '🔒 Pay & Confirm Booking'; confirmBtn.style.opacity = '1'; }
    }
  }

  // ─── Open Booking Modal ──────────────────────────────────────────────────────

  function openBooking(type, id, name, image, pricePerUnit) {
    _currentBooking = { type, id, name, image, pricePerUnit: Number(pricePerUnit) || 0 };
    _selectedPayment = null;
    _pendingPayload = null;

    const imgEl   = document.getElementById('book-summary-img');
    const titleEl = document.getElementById('book-summary-title');
    const metaEl  = document.getElementById('book-summary-meta');
    const labelEl = document.getElementById('book-nights-label');
    const unitRate = document.getElementById('calc-unit-rate');

    if (imgEl)   imgEl.src = image || '';
    if (titleEl) titleEl.textContent = name;
    if (metaEl)  metaEl.textContent  = type === 'guide'
      ? `Per Day Guide Rate: Rs. ${_currentBooking.pricePerUnit.toLocaleString()}`
      : `Rate: Rs. ${_currentBooking.pricePerUnit.toLocaleString()} / person`;
    if (labelEl) labelEl.textContent = type === 'guide' ? 'Days' : 'Nights';
    if (unitRate) unitRate.textContent = 'Rs. ' + _currentBooking.pricePerUnit.toLocaleString();

    // Default today's date
    const dateInput = document.getElementById('book-date');
    if (dateInput && !dateInput.value) {
      dateInput.value = new Date().toISOString().split('T')[0];
    }

    // Auto-fill logged-in user details
    if (window.CURRENT_USER) {
      const nameInp  = document.getElementById('book-name');
      const emailInp = document.getElementById('book-email');
      if (nameInp && !nameInp.value)   nameInp.value  = window.CURRENT_USER.name  || '';
      if (emailInp && !emailInp.value) emailInp.value = window.CURRENT_USER.email || '';
    }

    updateCalcTotal();
    goToStep(1);

    const modal = document.getElementById('booking-modal');
    if (modal) { modal.classList.add('visible'); document.body.style.overflow = 'hidden'; }
  }

  function closeModal() {
    const modal = document.getElementById('booking-modal');
    if (modal) { modal.classList.remove('visible'); document.body.style.overflow = ''; }
    goToStep(1);
    resetPaymentUI();
  }

  function updateCalcTotal() {
    const guests = parseInt(document.getElementById('book-guests')?.value || 1, 10) || 1;
    const nights = parseInt(document.getElementById('book-nights')?.value || 1, 10) || 1;
    const unit   = _currentBooking.pricePerUnit || 0;
    const total  = unit * guests * nights;
    const el = document.getElementById('calc-total');
    if (el) el.textContent = 'Rs. ' + total.toLocaleString();
  }

  // ─── Cancel Booking ──────────────────────────────────────────────────────────

  async function cancelBooking(id) {
    if (!confirm('Are you sure you want to cancel this reservation?')) return;
    try {
      const res = await fetch(`${getBaseUrl()}/api/bookings/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() }
      });
      const json = await res.json();
      if (res.ok && json.success) {
        showToast('Reservation cancelled successfully.');
        updateBadge();
        const card = document.getElementById(`booking-card-${id}`);
        if (card) {
          card.style.opacity = '0'; card.style.transform = 'scale(0.95)'; card.style.transition = 'all 0.3s ease';
          setTimeout(() => { card.remove(); if (document.querySelectorAll('[id^=booking-card-]').length === 0) window.location.reload(); }, 300);
        } else { window.location.reload(); }
      } else { showToast(json.message || 'Failed to cancel reservation.', true); }
    } catch (err) { showToast('Network error while cancelling.', true); }
  }

  // ─── DOM Ready ───────────────────────────────────────────────────────────────

  document.addEventListener('DOMContentLoaded', () => {

    // Close modal triggers
    document.getElementById('modal-close-btn')?.addEventListener('click', closeModal);
    document.getElementById('booking-modal')?.addEventListener('click', (e) => {
      if (e.target === document.getElementById('booking-modal')) closeModal();
    });

    // Live price calc
    ['book-guests','book-nights'].forEach(id => {
      document.getElementById(id)?.addEventListener('input', updateCalcTotal);
    });

    // Step 1 form submit → go to Step 2
    document.getElementById('book-modal-form')?.addEventListener('submit', (e) => {
      e.preventDefault();
      const name   = document.getElementById('book-name').value.trim();
      const email  = document.getElementById('book-email').value.trim();
      const date   = document.getElementById('book-date').value;
      const guests = parseInt(document.getElementById('book-guests').value, 10) || 1;
      const nights = parseInt(document.getElementById('book-nights').value, 10) || 1;
      const unit   = _currentBooking.pricePerUnit || 0;
      const total  = unit * guests * nights;

      if (!name || !email || !date) { showToast('Please fill in all required fields.', true); return; }

      // Cache payload for step 2
      _pendingPayload = {
        type:           _currentBooking.type || 'destination',
        item_id:        String(_currentBooking.id || ''),
        title:          _currentBooking.name || 'Booking',
        image:          _currentBooking.image || '',
        price_per_unit: unit,
        name, email, date, guests, nights, total,
      };

      goToStep(2);
    });

    updateBadge();
  });

  // ─── Global Exports ──────────────────────────────────────────────────────────

  window.KoshiApp = { openBooking, closeModal, cancelBooking, updateBadge, selectPayment, confirmPayment, goToStep };
  window.showToast = showToast;

})();
