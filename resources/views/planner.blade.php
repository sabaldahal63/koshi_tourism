@extends('layouts.app')
@section('title','Itinerary Planner | Koshi Province')
@section('content')
<section class="view-section active">
  <div class="container">
    <div class="section-header">
      <h2>Build Your Koshi Itinerary</h2>
      <p>Create a custom day-by-day trip plan across Koshi's most stunning landscapes.</p>
    </div>

    <div class="planner-layout">
      <div class="planner-form-panel glass-panel">
        <h3 style="margin-bottom:20px;font-size:22px;">Trip Preferences</h3>
        <form id="itinerary-form">
          <div class="form-group">
            <label for="plan-name">Your Name</label>
            <input type="text" id="plan-name" placeholder="e.g. Rohit Sharma" required>
          </div>
          <div class="form-group">
            <label for="plan-days">Trip Duration (Days)</label>
            <input type="number" id="plan-days" min="2" max="21" value="7" required>
          </div>
          <div class="form-group">
            <label for="plan-start">Start Date</label>
            <input type="date" id="plan-start" required>
          </div>
          <div class="form-group">
            <label>Interests (select all that apply)</label>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:6px;">
              <label class="checkbox-label"><input type="checkbox" value="adventure" checked> High Altitude</label>
              <label class="checkbox-label"><input type="checkbox" value="wildlife"> Wildlife</label>
              <label class="checkbox-label"><input type="checkbox" value="nature"> Nature / Tea</label>
              <label class="checkbox-label"><input type="checkbox" value="spiritual"> Spiritual</label>
            </div>
          </div>
          <div class="form-group">
            <label for="plan-budget">Budget Level</label>
            <select id="plan-budget">
              <option value="economy">Economy</option>
              <option value="moderate" selected>Moderate</option>
              <option value="luxury">Luxury</option>
            </select>
          </div>
          <div class="form-group">
            <label for="plan-notes">Special Requests</label>
            <textarea id="plan-notes" rows="3" placeholder="Vegetarian food, oxygen support, accessibility needs..."></textarea>
          </div>
          <button type="submit" class="booking-submit-btn">⚡ Generate My Itinerary</button>
        </form>
      </div>

      <div class="planner-output-panel" id="planner-output">
        <div class="planner-placeholder">
          <div style="font-size:60px;margin-bottom:16px;">🗺️</div>
          <h3>Your itinerary will appear here</h3>
          <p>Fill the form and click generate to create a personalized day-by-day plan.</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('scripts')
<script>
const PLANS_DB = {
  adventure:  ['Arrive in Kathmandu. Rest and acclimatize.','Fly Lukla, begin Phakding hike.','Namche Bazaar — explore, acclimatize.','Acclimatization hike to Syangboche.','Tengboche monastery — views of Ama Dablam.','Dingboche. Rest. Tea House dinner.','Lobuche. Rest.','Everest Base Camp visit. Celebrate!','Kala Patthar sunrise. Return to Namche.','Fly back to Kathmandu. Souvenir shopping.'],
  wildlife:   ['Arrive Biratnagar. Check in to resort.','Drive to Koshi Tappu Reserve. Bird walk.','Dawn jeep safari — wild buffalo, gharials.','Boating on Koshi barrage wetlands.','Visit Rajarani Lake bird sanctuary.','Jhapa tea garden walk. Lush scenery.','Departure from Biratnagar.'],
  nature:     ['Arrive Birtamod. Check-in homestay.','Kanyam tea garden sunrise tour.','Fikkal bazar and cheese factory.','Sandakpur ridge hike. Himalayan views.','Ilam bazar, local thangka art.','Depart.'],
  spiritual:  ['Arrive Taplejung. Acclimatize.','Local monastery tour.','Trek to Pathibhara base area.','Pathibhara Devi temple worship.','Visit Halesi Mahadev sacred caves.','Return journey. Reflection.'],
};

document.getElementById('itinerary-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const name   = document.getElementById('plan-name').value;
  const days   = parseInt(document.getElementById('plan-days').value);
  const start  = document.getElementById('plan-start').value;
  const budget = document.getElementById('plan-budget').value;
  const checks = [...document.querySelectorAll('.checkbox-label input:checked')].map(i=>i.value);
  const theme  = checks.length ? checks[Math.floor(Math.random()*checks.length)] : 'adventure';
  const pool   = PLANS_DB[theme] || PLANS_DB.adventure;

  let html = `<div class="itinerary-result">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
      <div>
        <h3 style="font-size:24px;margin:0;">✈️ ${name}'s Koshi Itinerary</h3>
        <p style="opacity:.6;margin-top:4px;">${days} Days · ${budget.charAt(0).toUpperCase()+budget.slice(1)} · ${theme.charAt(0).toUpperCase()+theme.slice(1)}</p>
      </div>
      <button class="card-btn" onclick="window.print()">🖨 Print</button>
    </div>
    <div class="day-list">`;
  for(let i=0;i<days;i++){
    const date = start ? new Date(new Date(start).getTime() + i*864e5).toLocaleDateString('en-NP',{weekday:'short',month:'short',day:'numeric'}) : `Day ${i+1}`;
    const act  = pool[i % pool.length];
    html += `<div class="day-item"><div class="day-num">Day ${i+1}<span>${date}</span></div><div class="day-activity">${act}</div></div>`;
  }
  html += '</div></div>';
  document.getElementById('planner-output').innerHTML = html;
});
</script>
@endsection
