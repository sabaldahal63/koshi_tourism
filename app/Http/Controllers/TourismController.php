<?php

namespace App\Http\Controllers;

class TourismController extends Controller
{
    private $destinations = [
        ['id'=>'everest','name'=>'Mount Everest & Sagarmatha Park','category'=>'adventure','location'=>'Solukhumbu','image'=>'assets/everest.png','rating'=>4.9,'reviews'=>1250,'elevation'=>'8,848m','desc'=>'The ultimate trekker\'s pilgrimage. Home to the world\'s highest peak, spectacular glaciers, high-altitude alpine terrain, and deep Sherpa Buddhist culture.','bestTime'=>'March to May, Sept to Nov','features'=>['Trekking','Sherpa Culture','Glaciers','Monasteries'],'price'=>199500],
        ['id'=>'ilam','name'=>'Kanyam & Ilam Tea Gardens','category'=>'nature','location'=>'Ilam','image'=>'assets/ilam.png','rating'=>4.7,'reviews'=>680,'elevation'=>'1,600m','desc'=>'Famous for its lush green tea gardens, beautiful mist-covered hills, and pleasant climate. Ideal for couples and photography enthusiasts.','bestTime'=>'October to December, Feb to April','features'=>['Tea Garden Tour','Horse Riding','Homestays','Nature Trails'],'price'=>46500],
        ['id'=>'koshi_tappu','name'=>'Koshi Tappu Wildlife Reserve','category'=>'wildlife','location'=>'Sunsari / Saptari','image'=>'assets/koshi_tappu.png','rating'=>4.6,'reviews'=>420,'elevation'=>'80m','desc'=>'A paradise for bird watchers and wildlife lovers. Known for the wild water buffalo (Arna) and 500+ species of migratory birds.','bestTime'=>'November to February','features'=>['Bird Watching','Jeep Safari','Rafting','Buffalo Watching'],'price'=>59800],
        ['id'=>'pathibhara','name'=>'Pathibhara Devi Temple','category'=>'spiritual','location'=>'Taplejung','image'=>'assets/pathivara.png','rating'=>4.8,'reviews'=>910,'elevation'=>'3,794m','desc'=>'One of the most sacred pilgrimage sites in Nepal. Breathtaking views of Mount Kanchenjunga from the goddess Pathibhara Devi temple.','bestTime'=>'March to June, Sept to Nov','features'=>['Pilgrimage','Scenic Trek','Kanchenjunga Views','Rhododendron Forests'],'price'=>79500],
        ['id'=>'bhedetar','name'=>'Bhedetar & Charles Point','category'=>'nature','location'=>'Dhankuta','image'=>'assets/bhedetar.png','rating'=>4.5,'reviews'=>580,'elevation'=>'1,420m','desc'=>'A beautiful hill station near Dharan, famous for cool weather, heavy fog, and panoramic Himalayan views from Charles Tower.','bestTime'=>'Year-round','features'=>['Cool Breeze','Charles Tower View','Hiking','Local Street Food'],'price'=>26600],
        ['id'=>'halesi','name'=>'Halesi Mahadev Temple','category'=>'spiritual','location'=>'Khotang','image'=>'assets/halesi.png','rating'=>4.7,'reviews'=>730,'elevation'=>'1,120m','desc'=>'The Pashupatinath of Eastern Nepal — a holy cave temple sacred to both Hindus and Buddhists, revered as where Lord Shiva hid from Bhasmasura.','bestTime'=>'September to May','features'=>['Cave Exploration','Sacred Pilgrimage','Peaceful Atmosphere'],'price'=>53200],
    ];

    private $hotels = [
        ['id'=>'hotel_everest','name'=>'Himalayan View Luxury Lodge','location'=>'Namche Bazaar, Solukhumbu','image'=>'assets/himalayanhotel.png','price'=>24000,'rating'=>4.8,'reviews'=>142,'amenities'=>['Free Wi-Fi','Oxygen Bar','Indoor Heating','Hot Showers','Local Guide Services'],'type'=>'Luxury'],
        ['id'=>'resort_ilam','name'=>'Kanyam Tea Garden Resort','location'=>'Ilam','image'=>'assets/kanyam.png','price'=>10000,'rating'=>4.6,'reviews'=>98,'amenities'=>['Free Wi-Fi','Organic Tea Tasting','Restaurant','Balcony View','Bonfire Area'],'type'=>'Moderate'],
        ['id'=>'camp_koshi','name'=>'Koshi Tappu River Glamping Camp','location'=>'Koshi Tappu Reserve','image'=>'assets/koshi.png','price'=>12500,'rating'=>4.7,'reviews'=>64,'amenities'=>['Tented Cabins','All-Inclusive Dining','Wildlife Guide','Boating Access','Solar Electricity'],'type'=>'Luxury'],
        ['id'=>'hotel_bhedetar','name'=>'Bhedetar Hillside Vista Hotel','location'=>'Bhedetar, Dhankuta','image'=>'assets/hotel.png','price'=>6000,'rating'=>4.4,'reviews'=>120,'amenities'=>['Free Wi-Fi','Rooftop Restaurant','Hot Water','Parking','Mountain Views'],'type'=>'Economy'],
        ['id'=>'hotel_taplejung','name'=>'Taplejung Pilgrim Inn','location'=>'Taplejung Bazaar','image'=>'assets/taplejung.png','price'=>4500,'rating'=>4.3,'reviews'=>55,'amenities'=>['Restaurant','Hot Showers','Luggage Storage','Local Guide Booking'],'type'=>'Economy'],
    ];

    private $guides = [
        ['id'=>'guide_pemba','name'=>'Pemba Sherpa','specialty'=>'High-Altitude Trekking & Peaks','experience'=>'12 Years','rating'=>4.9,'languages'=>['Sherpa','Nepali','English','Tibetan'],'rate'=>6000,'image'=>'assets/everest.png'],
        ['id'=>'guide_ramesh','name'=>'Ramesh Chaudhary','specialty'=>'Wildlife & Ornithology (Birds)','experience'=>'8 Years','rating'=>4.8,'languages'=>['Nepali','English','Hindi','Tharu'],'rate'=>4000,'image'=>'assets/koshi_tappu.png'],
        ['id'=>'guide_lhamo','name'=>'Lhamo Tamang','specialty'=>'Spiritual Caving & Cultural Walks','experience'=>'6 Years','rating'=>4.7,'languages'=>['Nepali','English','Tibetan'],'rate'=>3500,'image'=>'assets/ilam.png'],
    ];

    public function index()   { return view('home',    ['featured' => array_slice($this->destinations, 0, 3)]); }
    public function explore() { return view('explore', ['destinations' => $this->destinations]); }
    public function stay()    { return view('stay',    ['hotels' => $this->hotels]); }
    public function planner() { return view('planner'); }
    public function guides()  { return view('guides',  ['guides' => $this->guides]); }
    public function advisor() { return view('advisor'); }
    public function bookings(){ return view('bookings'); }

    public function getDestinationsApi() { return response()->json($this->destinations); }
    public function getHotelsApi()       { return response()->json($this->hotels); }
}
