<?php
require_once 'includes/config.php';

// Get search parameters
$medicine_name = isset($_GET['medicine']) ? sanitize_input($_GET['medicine']) : '';
$location = isset($_GET['location']) ? sanitize_input($_GET['location']) : '';

// Include header
include 'header.php';
?>

<!-- Add Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--primary-color), #4a90e2);;
        color: white;
        padding: 2.5rem 0;
        margin-top: 3rem;
        position: relative;
        overflow: hidden;
        margin-left: 200px;
        margin-right: 200px;
        border-radius: 20px;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('../assets/images/pattern.png') repeat;
        opacity: 0.1;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .hero-content h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: white;
    }

    .hero-content p {
        font-size: 1rem;
        margin-bottom: 1.5rem;
        opacity: 0.9;
        color: white;
    }

    .hero-content .search-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .hero-content .search-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: white;
        border-radius: 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        padding: 0.5rem;
        transition: all 0.3s ease;
        height: 50px;
    }

    .hero-content .search-container:focus-within {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
    }

    .hero-content .search-container input {
        flex: 1;
        border: none;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
        outline: none;
        background: transparent;
        height: 100%;
    }

    .hero-content .search-container input::placeholder {
        color: #999;
    }

    .hero-content .search-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-weight: 500;
        height: 45px;
    }

    .hero-content .search-btn:hover {
        background: #4a6be9;
        transform: translateY(-2px);
    }

    .hero-content .search-btn i {
        font-size: 1.2rem;
    }

    /* Main Content */
    .pharmacy-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .pharmacy-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 3rem;
        margin-top: 2rem;
        padding: 0 1rem;
    }

    .pharmacy-card {
        background: var(--white);
        border-radius: 15px;
        padding: 2.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
        min-width: 600px;
    }

    .pharmacy-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .pharmacy-card h2 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-size: 1.6rem;
        font-weight: 600;
    }

    .pharmacy-card .description {
        color: var(--light-text);
        margin-bottom: 2rem;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .pharmacy-card .address {
        background: #f8f9fa;
        padding: 1.2rem;
        border-radius: 10px;
        margin-bottom: 1.2rem;
        font-size: 1rem;
        border-left: 4px solid var(--primary-color);
    }

    .pharmacy-card .contact {
        margin-bottom: 1.2rem;
        font-size: 1rem;
        line-height: 1.6;
    }

    .pharmacy-card .hours {
        background: #e3f2fd;
        padding: 1.2rem;
        border-radius: 10px;
        margin-top: auto;
        font-size: 1rem;
        border-left: 4px solid #2196f3;
    }

    .action-btn {
        background: var(--primary-color);
        color: var(--white);
        border: none;
        padding: 1rem 2rem;
        border-radius: 25px;
        cursor: pointer;
        margin-top: 2rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        background: #4a6be9;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(74, 107, 233, 0.3);
    }

    .action-btn i {
        font-size: 1.1rem;
    }

    /* Map Section */
    .map-section {
        margin-top: 3rem;
        padding: 2rem;
        background: var(--white);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .map-section h2 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-size: 1.6rem;
        font-weight: 600;
        text-align: center;
    }

    #map {
        height: 600px;
        width: 1200px;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        align-items: center;
    }

    @media (max-width: 1400px) {
        .pharmacy-card {
            min-width: 500px;
        }
    }

    @media (max-width: 1200px) {
        .pharmacy-card {
            min-width: 450px;
        }
    }

    @media (max-width: 992px) {
        .pharmacy-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .pharmacy-card {
            min-width: 100%;
            padding: 2rem;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 2rem 0;
            margin-left: 1rem;
            margin-right: 1rem;
        }

        .hero-content h1 {
            font-size: 2rem;
        }

        .hero-content p {
            font-size: 1rem;
        }

        .pharmacy-container {
            padding: 1rem;
        }

        .pharmacy-card {
            padding: 1.5rem;
        }

        .hero-content .search-container {
            max-width: 100%;
            margin: 0 1rem;
        }

        #map {
            height: 400px;
        }
    }
</style>

<!-- Pharmacy Content -->
<div class="hero-section">
    <div class="hero-content">
        <h1>Find Pharmacies</h1>
        <p>Locate nearby pharmacies and check their availability</p>
    </div>
</div>

<div class="pharmacy-container">
    <div class="map-section">
        <h2>Pharmacy Locations</h2>
        <div id="map"></div>
    </div>
    <div class="pharmacy-grid" id="pharmacyList">
        <!-- Pharmacy cards will be dynamically added here -->
    </div>
</div>

<!-- Add Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Initialize map variables
    let map;
    let markers = [];
    let userMarker;
    let infoWindow;

    // Initialize map when the page loads
    window.onload = function() {
        initMap();
    };

    function initMap() {
        console.log("Initializing map...");
        
        // Check if map container exists
        const mapContainer = document.getElementById('map');
        if (!mapContainer) {
            console.error("Map container not found!");
            return;
        }
        
        try {
            // Initialize map with OpenStreetMap
            map = L.map('map').setView([20.5937, 78.9629], 15); // Default to India's coordinates
            
            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Initialize info window
            infoWindow = L.popup();

            // Get user's location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        map.setView([userLocation.lat, userLocation.lng], 15);
                        addUserMarker(userLocation);
                        searchPharmacies(userLocation);
                    },
                    function(error) {
                        console.error('Error getting location:', error);
                        // Default to a central location if geolocation fails
                        map.setView([20.5937, 78.9629], 15); // India's coordinates
                        searchPharmacies({ lat: 20.5937, lng: 78.9629 });
                    }
                );
            } else {
                console.log("Geolocation is not supported by this browser.");
                searchPharmacies({ lat: 20.5937, lng: 78.9629 });
            }

            // Initialize location search
            const locationInput = document.getElementById('locationInput');
            if (locationInput) {
                // Add event listener for location input
                locationInput.addEventListener('change', function() {
                    const location = this.value;
                    if (location) {
                        searchLocation(location);
                    }
                });
            }
        } catch (error) {
            console.error("Error initializing map:", error);
        }
    }

    function addUserMarker(location) {
        // Remove existing user marker if any
        if (userMarker) {
            map.removeLayer(userMarker);
        }

        // Add new user marker
        userMarker = L.marker([location.lat, location.lng], {
            icon: L.divIcon({
                className: 'user-marker',
                html: '<i class="fas fa-user-circle"></i>',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            })
        }).addTo(map);

        // Add popup to user marker
        userMarker.bindPopup('Your Location').openPopup();
    }

    function searchLocation(location) {
        // Use OpenStreetMap Nominatim API for geocoding
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(location)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const location = {
                        lat: parseFloat(data[0].lat),
                        lng: parseFloat(data[0].lon)
                    };
                    map.setView([location.lat, location.lng], 15);
                    addUserMarker(location);
                    searchPharmacies(location);
                } else {
                    alert('Location not found. Please try a different search term.');
                }
            })
            .catch(error => {
                console.error('Error searching location:', error);
                alert('Error searching location. Please try again.');
            });
    }

    function searchPharmacies(location) {
        // Clear existing markers
        markers.forEach(marker => map.removeLayer(marker));
        markers = [];

        // Update pharmacy list
        updatePharmacyList(pharmacies);
    }

    function updatePharmacyList(pharmacies) {
        const pharmacyList = document.getElementById('pharmacyList');
        pharmacyList.innerHTML = '';

        pharmacies.forEach(pharmacy => {
            const card = document.createElement('div');
            card.className = 'pharmacy-card';
            card.innerHTML = `
                <h2>${pharmacy.name}</h2>
                <div class="description">${pharmacy.description || '24/7 Pharmacy Service'}</div>
                <div class="address">
                    <i class="fas fa-map-marker-alt"></i> ${pharmacy.address}
                </div>
                <div class="contact">
                    <i class="fas fa-phone"></i> ${pharmacy.phone}
                </div>
                <div class="hours">
                    <i class="fas fa-clock"></i> ${pharmacy.hours || 'Open 24/7'}
                </div>
                <button class="action-btn" onclick="getDirections(${pharmacy.lat}, ${pharmacy.lng})">
                    <i class="fas fa-directions"></i> Get Directions
                </button>
            `;
            pharmacyList.appendChild(card);
        });
    }

    function getDirections(lat, lng) {
        if (userMarker) {
            const userLat = userMarker.getLatLng().lat;
            const userLng = userMarker.getLatLng().lng;
            window.open(`https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${lat},${lng}&travelmode=driving`);
        } else {
            alert('Please allow location access to get directions.');
        }
    }
</script>

<?php
// Include footer
include 'footer.php';
?> 