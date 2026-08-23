@extends('admin.layout')
@section('title', 'Overview & Bookings | Koshi Tourism Admin')
@section('header_title', 'Analytics & Reservations Portal')
@section('header_subtitle', 'Real-time overview of revenue in Nepali Rs. and live customer bookings.')

@section('content')

<!-- KPI Overview Grid -->
<div class="kpi-grid">
  
  <div class="kpi-card" style="--card-accent: var(--admin-primary);">
    <div class="kpi-info">
      <h4>Gross Booked Revenue</h4>
      <div class="kpi-value">Rs. {{ number_format($totalRevenue) }}</div>
      <div class="kpi-sub">{{ $confirmedCount + $completedCount }} active/completed orders</div>
    </div>
    <div class="kpi-icon">💰</div>
  </div>

  <div class="kpi-card" style="--card-accent: var(--admin-blue);">
    <div class="kpi-info">
      <h4>Total Reservations</h4>
      <div class="kpi-value">{{ $totalBookings }}</div>
      <div class="kpi-sub">Across all 14 Koshi districts</div>
    </div>
    <div class="kpi-icon">📋</div>
  </div>

  <div class="kpi-card" style="--card-accent: var(--admin-emerald);">
    <div class="kpi-info">
      <h4>Hotel Reservations</h4>
      <div class="kpi-value">{{ $hotelBookings }}</div>
      <div class="kpi-sub">Rs. {{ number_format($hotelRevenue) }} booked value</div>
    </div>
    <div class="kpi-icon">🏨</div>
  </div>

  <div class="kpi-card" style="--card-accent: var(--admin-purple);">
    <div class="kpi-info">
      <h4>Local Guide Hires</h4>
      <div class="kpi-value">{{ $guideBookings }}</div>
      <div class="kpi-sub">Rs. {{ number_format($guideRevenue) }} booked value</div>
    </div>
    <div class="kpi-icon">🧭</div>
  </div>

</div>

<!-- Revenue Distribution Summary -->
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">
      <span style="font-size:20px;">📊</span>
      <div>
        <h3>Category Revenue Breakdown</h3>
        <p>Distribution of bookings across hospitality, certified mountain guides, and trekking destinations.</p>
      </div>
    </div>
  </div>
  <div class="revenue-breakdown-grid">
    
    @php
      $tot = $totalRevenue > 0 ? $totalRevenue : 1;
      $hotelPct = round(($hotelRevenue / $tot) * 100);
      $guidePct = round(($guideRevenue / $tot) * 100);
      $destPct  = round(($destRevenue / $tot) * 100);
    @endphp

    <div class="breakdown-card">
      <h5>🏨 Hotels & Resorts ({{ $hotelPct }}%)</h5>
      <div class="amount">Rs. {{ number_format($hotelRevenue) }}</div>
      <div class="progress-bar-bg">
        <div class="progress-bar-fill" style="width: {{ $hotelPct }}%; background: var(--admin-emerald);"></div>
      </div>
    </div>

    <div class="breakdown-card">
      <h5>🧭 Mountain & Safari Guides ({{ $guidePct }}%)</h5>
      <div class="amount">Rs. {{ number_format($guideRevenue) }}</div>
      <div class="progress-bar-bg">
        <div class="progress-bar-fill" style="width: {{ $guidePct }}%; background: var(--admin-purple);"></div>
      </div>
    </div>

    <div class="breakdown-card">
      <h5>🏔️ Destination Packages ({{ $destPct }}%)</h5>
      <div class="amount">Rs. {{ number_format($destRevenue) }}</div>
      <div class="progress-bar-bg">
        <div class="progress-bar-fill" style="width: {{ $destPct }}%; background: var(--admin-primary);"></div>
      </div>
    </div>

  </div>
</div>

<!-- Bookings Management Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">
      <span style="font-size:20px;">🛎️</span>
      <div>
        <h3>Customer Reservations</h3>
        <p>Manage, search, and update order statuses in real-time. Payment status shown for each booking.</p>
      </div>
    </div>
    <div class="admin-card-actions">
      <input type="text" id="tableSearch" class="table-search-input" placeholder="🔍 Search guest, email, hotel...">
      
      <select id="typeFilter" class="table-filter-select">
        <option value="all">All Types</option>
        <option value="hotel">Hotels</option>
        <option value="guide">Guides</option>
        <option value="destination">Destinations</option>
      </select>

      <select id="statusFilter" class="table-filter-select">
        <option value="all">All Statuses</option>
        <option value="confirmed">Confirmed</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
      </select>

      <select id="paymentFilter" class="table-filter-select">
        <option value="all">All Payments</option>
        <option value="paid">Paid</option>
        <option value="pending">Unpaid / Pending</option>
      </select>

      <button class="admin-btn" onclick="exportTableToCSV('koshi-bookings.csv')">
        📥 Export CSV
      </button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="admin-table" id="bookingsTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Item / Service</th>
          <th>Lead Guest</th>
          <th>Check-in</th>
          <th>Duration</th>
          <th>Amount (NPR)</th>
          <th>💳 Payment</th>
          <th>Booking Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="bookingsTableBody">
        @forelse($bookings as $b)
          @php
            $typeIcon = $b->type === 'hotel' ? '🏨' : ($b->type === 'guide' ? '🧭' : '🏔️');
            $imgSrc   = $b->image ? asset($b->image) : asset('assets/everest.png');

            // Payment method label & color
            $payMethod = $b->payment_method ?? 'unpaid';
            $payStatus = $b->payment_status ?? 'pending';
            $txnId     = $b->transaction_id ?? null;

            $payLabel = match($payMethod) {
              'esewa'          => '🟢 eSewa',
              'khalti'         => '🟣 Khalti',
              'mobile_banking' => '🔵 Mobile Bank',
              default          => '⚪ Unpaid',
            };
            $payStatusColor = $payStatus === 'paid'
              ? 'background:rgba(16,185,129,0.15);color:#34d399;border:1px solid rgba(16,185,129,0.3);'
              : 'background:rgba(239,68,68,0.12);color:#f87171;border:1px solid rgba(239,68,68,0.25);';
          @endphp
          <tr id="admin-row-{{ $b->id }}"
              data-type="{{ $b->type }}"
              data-status="{{ $b->status }}"
              data-payment="{{ $payStatus }}"
              data-search="{{ strtolower($b->title . ' ' . $b->name . ' ' . $b->email . ' ' . $b->id . ' ' . $payMethod) }}">
            
            <td style="font-weight:700;color:var(--admin-primary);">#{{ $b->id }}</td>
            
            <td>
              <div class="table-booking-info">
                <img src="{{ $imgSrc }}" alt="{{ $b->title }}" class="table-booking-img" onerror="this.src='{{ asset('assets/everest.png') }}'">
                <div class="table-booking-title">
                  <strong>{{ $b->title }}</strong>
                  <span>{{ $typeIcon }} {{ ucfirst($b->type) }}</span>
                </div>
              </div>
            </td>

            <td>
              <strong style="color:#fff;">{{ $b->name }}</strong>
              <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $b->email }}</div>
            </td>

            <td>
              <span style="font-weight:600;color:#fff;">{{ date('M d, Y', strtotime($b->date)) }}</span>
              <div style="font-size:11px;color:#9ca3af;margin-top:2px;">{{ $b->guests }} Guests · {{ $b->nights }} {{ $b->type === 'guide' ? 'Days' : 'Nights' }}</div>
            </td>

            <td>
              <strong style="color:var(--admin-primary);font-size:15px;">Rs. {{ number_format($b->total) }}</strong>
            </td>

            <!-- Payment Status Column -->
            <td>
              <div style="display:flex;flex-direction:column;gap:5px;min-width:120px;">
                {{-- Payment Method Badge --}}
                <span style="font-size:12px;font-weight:700;color:#e5e7eb;">{{ $payLabel }}</span>

                {{-- Paid / Unpaid badge --}}
                <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;{{ $payStatusColor }}">
                  {{ $payStatus === 'paid' ? '✓ PAID' : '✕ UNPAID' }}
                </span>

                {{-- Transaction ID --}}
                @if($txnId)
                  <span style="font-size:10px;color:#6b7280;font-family:monospace;" title="{{ $txnId }}">
                    {{ substr($txnId, 0, 16) }}…
                  </span>
                @endif
              </div>
            </td>

            <td>
              <select class="status-select-inline" onchange="updateBookingStatus({{ $b->id }}, this.value)">
                <option value="confirmed" {{ $b->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="pending"   {{ $b->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $b->status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $b->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
              </select>
            </td>

            <td>
              <div class="table-actions">
                <button class="btn-icon-danger" onclick="deleteBooking({{ $b->id }})" title="Delete Reservation">
                  🗑
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr id="noBookingsRow">
            <td colspan="9" style="text-align:center;padding:48px 20px;color:#9ca3af;">
              <div style="font-size:36px;margin-bottom:8px;">📋</div>
              <h4 style="color:#fff;margin:0 0 4px 0;">No bookings recorded yet</h4>
              <p style="margin:0;font-size:13px;">New reservations created on the site will automatically appear here.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@section('scripts')
<script>
// Real-time Search and Filtering
document.addEventListener('DOMContentLoaded', () => {
  const searchInput   = document.getElementById('tableSearch');
  const typeFilter    = document.getElementById('typeFilter');
  const statusFilter  = document.getElementById('statusFilter');
  const paymentFilter = document.getElementById('paymentFilter');
  const rows          = document.querySelectorAll('#bookingsTableBody tr[data-search]');

  function applyFilters() {
    const query   = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const type    = typeFilter ? typeFilter.value : 'all';
    const status  = statusFilter ? statusFilter.value : 'all';
    const payment = paymentFilter ? paymentFilter.value : 'all';

    rows.forEach(row => {
      const matchSearch  = !query || row.dataset.search.includes(query);
      const matchType    = type === 'all' || row.dataset.type === type;
      const matchStatus  = status === 'all' || row.dataset.status === status;
      const matchPayment = payment === 'all'
        || (payment === 'paid'    && row.dataset.payment === 'paid')
        || (payment === 'pending' && row.dataset.payment !== 'paid');

      row.style.display = (matchSearch && matchType && matchStatus && matchPayment) ? '' : 'none';
    });
  }

  if (searchInput)   searchInput.addEventListener('input', applyFilters);
  if (typeFilter)    typeFilter.addEventListener('change', applyFilters);
  if (statusFilter)  statusFilter.addEventListener('change', applyFilters);
  if (paymentFilter) paymentFilter.addEventListener('change', applyFilters);
});

// Update Status via AJAX
async function updateBookingStatus(id, newStatus) {
  try {
    const res = await fetch(`${window.APP_URL}/admin/bookings/${id}/status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': window.CSRF_TOKEN
      },
      body: JSON.stringify({ status: newStatus })
    });

    const data = await res.json();
    if (res.ok && data.success) {
      showAdminToast(`✓ Booking #${id} marked as ${newStatus.toUpperCase()}`);
      const row = document.getElementById(`admin-row-${id}`);
      if (row) row.dataset.status = newStatus;
    } else {
      showAdminToast(data.error || 'Failed to update status', true);
    }
  } catch (err) {
    console.error(err);
    showAdminToast('Network error while updating status', true);
  }
}

// Delete Booking via AJAX
async function deleteBooking(id) {
  if (!confirm(`Are you sure you want to permanently delete reservation #${id}?`)) return;

  try {
    const res = await fetch(`${window.APP_URL}/admin/bookings/${id}`, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': window.CSRF_TOKEN
      }
    });

    const data = await res.json();
    if (res.ok && data.success) {
      showAdminToast(`✓ Reservation #${id} deleted from database.`);
      const row = document.getElementById(`admin-row-${id}`);
      if (row) {
        row.style.opacity = '0';
        row.style.transform = 'scale(0.95)';
        row.style.transition = 'all 0.3s ease';
        setTimeout(() => row.remove(), 300);
      }
    } else {
      showAdminToast(data.error || 'Failed to delete reservation', true);
    }
  } catch (err) {
    console.error(err);
    showAdminToast('Network error while deleting', true);
  }
}

// Export CSV Helper
function exportTableToCSV(filename) {
  const rows = document.querySelectorAll('#bookingsTable tr');
  let csv = [];
  for (let i = 0; i < rows.length; i++) {
    if (rows[i].style.display === 'none') continue;
    let row = [], cols = rows[i].querySelectorAll('td, th');
    for (let j = 0; j < cols.length - 1; j++) {
      let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/g, ' ').trim();
      row.push('"' + text.replace(/"/g, '""') + '"');
    }
    csv.push(row.join(','));
  }
  const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
  const downloadLink = document.createElement('a');
  downloadLink.download = filename;
  downloadLink.href = window.URL.createObjectURL(csvFile);
  downloadLink.style.display = 'none';
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}
</script>
@endsection
