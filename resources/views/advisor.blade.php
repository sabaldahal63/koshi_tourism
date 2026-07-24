@extends('layouts.app')
@section('title','Travel Advisor | Koshi Province')
@section('content')
<section class="view-section active">
  <div class="container">
    <div class="section-header">
      <h2>AI Travel Advisor</h2>
      <p>Ask any question about Koshi Province — seasonal tips, equipment, safety, cultural customs and more.</p>
    </div>

    <div class="advisor-layout">
      <div class="advisor-sidebar glass-panel">
        <h4 style="margin-bottom:12px;">Quick Questions</h4>
        <div class="quick-qs">
          <button class="quick-q-btn" data-q="What is the best time to trek to Everest Base Camp?">Best time for EBC trek?</button>
          <button class="quick-q-btn" data-q="What should I pack for a high altitude trek in Koshi?">Packing list for Koshi</button>
          <button class="quick-q-btn" data-q="Tell me about Koshi Tappu wildlife reserve.">Koshi Tappu wildlife info</button>
          <button class="quick-q-btn" data-q="What food is famous in Koshi Province?">Local Koshi cuisine</button>
          <button class="quick-q-btn" data-q="How do I reach Ilam tea gardens from Kathmandu?">Getting to Ilam</button>
          <button class="quick-q-btn" data-q="Is Koshi safe for solo female travelers?">Safety for solo women</button>
          <button class="quick-q-btn" data-q="What is the history of Pathibhara temple?">Pathibhara temple history</button>
        </div>
      </div>

      <div class="advisor-chat">
        <div class="chat-messages" id="chat-messages">
          <div class="chat-bubble bot-bubble">
            <div class="bubble-avatar">⛰️</div>
            <div class="bubble-content">
              <strong>KoshiBot</strong>
              <p>Namaste! 🙏 I'm your Koshi Province travel expert. Ask me anything about destinations, trekking routes, permits, weather, culture, and local tips across Solukhumbu, Ilam, Koshi Tappu, Taplejung, and more!</p>
            </div>
          </div>
        </div>
        <form id="advisor-form" class="chat-input-row">
          <input type="text" id="advisor-input" placeholder="Type your question about Koshi Province..." autocomplete="off">
          <button type="submit" class="chat-send-btn">➤</button>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
@section('scripts')
<script>
const KB = [
  {k:['best time','when to visit','season','month','trek'],a:'The best time to trek in Koshi Province is <strong>March–May</strong> (Spring – rhododendrons bloom) and <strong>September–November</strong> (Autumn – crystal clear Himalayan views). Avoid June–August (monsoon), as trails can be slippery and cloud-covered.'},
  {k:['pack','packing','gear','equipment','bring'],a:'For high-altitude zones (Everest area): down jacket, thermal base layers, waterproof boots, trekking poles, sleeping bag (rated -10°C), sunscreen SPF50+, headlamp, first aid kit, and altitude sickness pills (Diamox). For lower Ilam or Koshi Tappu: lighter gear is fine.'},
  {k:['koshi tappu','wildlife reserve','bird','buffalo'],a:'<strong>Koshi Tappu Wildlife Reserve</strong> (175 km²) is a UNESCO Ramsar Wetland. It protects the endangered Wild Water Buffalo (Arna), Gangetic Dolphin, gharials, and 500+ bird species. Best visited Nov–Feb for migratory birds. Entry fee: Rs. 1,000/person.'},
  {k:['food','cuisine','eat','famous dish'],a:'Koshi Province has amazing cuisine: <strong>Sherpa Stew</strong> (Tsampa soup), <strong>Dal Bhat</strong> (rice & lentils — unlimited refills on trails!), <strong>Ilam Tea</strong>, <strong>Gundruk</strong> (fermented greens), <strong>Sel Roti</strong> (rice doughnut), and local Rai & Limbu meat specialties (pork, chicken).'},
  {k:['ilam','tea garden','how to reach','getting to'],a:'From Kathmandu, fly to <strong>Bhadrapur Airport</strong> (Jhapa District) — 55-minute flight. Then take a local taxi/bus to Ilam (~3 hrs). Alternatively, drive 780 km via Prithvi & BP Highway (12–14 hrs). Ilam is at 1,600m and has a beautiful climate.'},
  {k:['safe','solo female','solo women','women','safety'],a:'Koshi Province is generally <strong>very safe for solo female travelers</strong>. Tea houses on trekking routes are welcoming and family-run. Recommended safety tips: always trek with a licensed guide in remote zones, register at TIMS checkpoints, share your location with family, and avoid trekking solo on off-season trails.'},
  {k:['pathibhara','temple','goddess','sacred'],a:'<strong>Pathibhara Devi Temple</strong> (Taplejung, 3,794m) is one of Nepal\'s most revered shrines. Goddess Pathibhara (Durga) is believed to fulfill wishes. The 2–3 day hike offers stunning views of Kangchenjunga. Peak pilgrimage seasons are Chaitra (March–April) and Kartik (Oct–Nov). Entry is free.'},
  {k:['permit','trekking permit','payment','fees'],a:'Permit requirements vary: <strong>Sagarmatha National Park Permit</strong> (Everest area) costs Rs. 3,000 for Nepali nationals. Foreign trekkers need TIMS card (~USD 10–20) + NP entry permit (~USD 30). Koshi Tappu Reserve entry costs Rs. 1,000/person for Nepali. Hire a licensed guide — they handle all permits.'},
  {k:['everest base camp','ebc','sagarmatha'],a:'<strong>Everest Base Camp Trek</strong>: The classic 14-day route from Lukla passes through Namche Bazaar, Tengboche, Dingboche, Lobuche, and Gorak Shep to EBC (5,364m). Best months: March–May, Sept–Nov. Altitude sickness is a real risk above 3,000m — acclimatize carefully.'},
  {k:['budget','cost','expensive','money'],a:'Estimated daily budget in Koshi Province: <strong>Economy</strong>: Rs. 2,500–4,000 (tea house, dal bhat). <strong>Moderate</strong>: Rs. 5,000–10,000 (mid-range hotel, guided day tour). <strong>Luxury</strong>: Rs. 15,000–30,000+ (premium lodge, private guide). EBC full trek budget: Rs. 80,000–200,000+ including transport.'},
];

function findAnswer(q) {
  const ql = q.toLowerCase();
  const match = KB.find(e => e.k.some(kw => ql.includes(kw)));
  return match ? match.a : `Great question! For "<em>${q}</em>" — I recommend checking with the Koshi Province Tourism Board or ask our team. They will guide you on permits, routes, and local guides. Our certified guides are available at <strong>Rs. 3,500–6,000/day</strong> and know every trail in the region! <a href="/guides" style="color:var(--primary);">View local guides →</a>`;
}

const messagesDiv = document.getElementById('chat-messages');
function addBubble(text, who) {
  const div = document.createElement('div');
  div.className = `chat-bubble ${who}-bubble`;
  div.innerHTML = who === 'bot'
    ? `<div class="bubble-avatar">⛰️</div><div class="bubble-content"><strong>KoshiBot</strong><p>${text}</p></div>`
    : `<div class="bubble-content user-msg"><p>${text}</p></div>`;
  messagesDiv.appendChild(div);
  messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

document.querySelectorAll('.quick-q-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const q = btn.dataset.q;
    addBubble(q, 'user');
    setTimeout(() => addBubble(findAnswer(q), 'bot'), 500);
  });
});

document.getElementById('advisor-form').addEventListener('submit', e => {
  e.preventDefault();
  const inp = document.getElementById('advisor-input');
  const q = inp.value.trim();
  if (!q) return;
  addBubble(q, 'user');
  inp.value = '';
  setTimeout(() => addBubble(findAnswer(q), 'bot'), 600);
});
</script>
@endsection
