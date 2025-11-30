<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { height: 600px; border-radius: 1rem; }
        .driver-popup {
            max-width: 300px;
        }
        .driver-marker {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 3px solid white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-10 w-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Dashboard</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3 px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50">
                        @if(Auth::user()->isDriver())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-purple-500 to-pink-500 text-white">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Driver
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-indigo-500 to-purple-500 text-white">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Client
                            </span>
                        @endif
                        <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-medium rounded-lg hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 transform hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-2xl overflow-hidden mb-8">
                <div class="px-8 py-12 text-white">
                    <h2 class="text-4xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-indigo-100 text-lg">
                        @if(Auth::user()->isDriver())
                            You're currently {{ Auth::user()->is_available ? 'available' : 'offline' }} for deliveries
                        @else
                            Find drivers near you on the map below
                        @endif
                    </p>
                </div>
            </div>

            @if(Auth::user()->isClient())
            <!-- Map Section for Clients -->
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        Available Drivers Near You
                    </h3>
                    <div class="flex items-center space-x-2 bg-green-100 px-4 py-2 rounded-lg">
                        <div class="h-3 w-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-green-700 font-medium text-sm">{{ $drivers->count() }} Online</span>
                    </div>
                </div>
                <div id="map"></div>
            </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Profile Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Profile</h3>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><span class="font-medium">Email:</span> {{ Auth::user()->email }}</p>
                        <p><span class="font-medium">Phone:</span> {{ Auth::user()->phone ?? 'Not provided' }}</p>
                    </div>
                </div>

                <!-- Location Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-r from-green-500 to-teal-500 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Location</h3>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><span class="font-medium">City:</span> {{ Auth::user()->city ?? 'Not provided' }}</p>
                        <p><span class="font-medium">Country:</span> {{ Auth::user()->country ?? 'Not provided' }}</p>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-semibold text-gray-900">Activity</h3>
                    </div>
                    <div class="space-y-2 text-sm text-gray-600">
                        @if(Auth::user()->isDriver())
                            <p><span class="font-medium">Deliveries:</span> {{ Auth::user()->total_deliveries }}</p>
                            <p><span class="font-medium">Rating:</span> ⭐ {{ number_format(Auth::user()->rating, 1) }}/5.0</p>
                        @else
                            <p><span class="font-medium">Orders:</span> 0</p>
                            <p><span class="font-medium">Points:</span> 100</p>
                        @endif
                    </div>
                </div>
            </div>

            @if(Auth::user()->isDriver())
            <!-- Driver Specific Info -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Driver Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Vehicle Information</h4>
                        <div class="space-y-3 text-sm text-gray-600">
                            <p><span class="font-medium">Type:</span> {{ ucfirst(Auth::user()->vehicle_type ?? 'N/A') }}</p>
                            <p><span class="font-medium">Model:</span> {{ Auth::user()->vehicle_model ?? 'N/A' }}</p>
                            <p><span class="font-medium">Plate:</span> {{ Auth::user()->vehicle_plate ?? 'N/A' }}</p>
                            <p><span class="font-medium">License:</span> {{ Auth::user()->license_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">About Me</h4>
                        <p class="text-sm text-gray-600">
                            {{ Auth::user()->bio ?? 'No bio provided yet.' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if(Auth::user()->isClient())
    <script>
        // Initialize map
        const map = L.map('map').setView([{{ Auth::user()->latitude ?? 33.5731 }}, {{ Auth::user()->longitude ?? -7.5898 }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Custom marker icon for drivers
        const driverIcon = L.divIcon({
            className: 'driver-marker',
            html: `<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                           width: 40px; height: 40px; border-radius: 50%; 
                           border: 3px solid white; 
                           display: flex; align-items: center; justify-content: center;
                           box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                    <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                  </div>`,
            iconSize: [40, 40],
            iconAnchor: [20, 20]
        });

        // Current user marker
        @if(Auth::user()->latitude && Auth::user()->longitude)
        const userIcon = L.divIcon({
            className: 'user-marker',
            html: `<div style="background: #3b82f6; 
                           width: 30px; height: 30px; border-radius: 50%; 
                           border: 3px solid white; 
                           display: flex; align-items: center; justify-content: center;
                           box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                    <svg style="width: 16px; height: 16px; color: white;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                  </div>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15]
        });

        L.marker([{{ Auth::user()->latitude }}, {{ Auth::user()->longitude }}], {icon: userIcon})
            .addTo(map)
            .bindPopup(`<div class="text-center p-2">
                <strong class="text-blue-600">You are here</strong><br>
                <small>{{ Auth::user()->city }}</small>
            </div>`);
        @endif

        // Add driver markers
        const drivers = @json($drivers);
        
        drivers.forEach(driver => {
            if (driver.latitude && driver.longitude) {
                const vehicleEmoji = {
                    'car': '🚗',
                    'motorcycle': '🏍️',
                    'van': '🚐',
                    'truck': '🚚',
                    'bicycle': '🚴'
                };

                const stars = '⭐'.repeat(Math.round(driver.rating));
                
                const popupContent = `
                    <div class="driver-popup p-4">
                        <div class="text-center mb-3">
                            <div class="inline-block w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-2">
                                ${driver.name.charAt(0).toUpperCase()}
                            </div>
                            <h3 class="font-bold text-lg text-gray-900">${driver.name}</h3>
                            <p class="text-sm text-gray-600">${vehicleEmoji[driver.vehicle_type] || '🚗'} ${driver.vehicle_type}</p>
                        </div>
                        
                        <div class="space-y-2 text-sm border-t border-gray-200 pt-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rating:</span>
                                <span class="font-medium">${stars} ${driver.rating}/5</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Deliveries:</span>
                                <span class="font-medium">${driver.total_deliveries}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Vehicle:</span>
                                <span class="font-medium">${driver.vehicle_model}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Location:</span>
                                <span class="font-medium">${driver.city}</span>
                            </div>
                        </div>
                        
                        ${driver.bio ? `
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-600 italic">"${driver.bio}"</p>
                        </div>
                        ` : ''}
                        
                        <div class="mt-4">
                            <button onclick="contactDriver(${driver.id})" 
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2 px-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-medium">
                                Contact Driver
                            </button>
                        </div>
                    </div>
                `;

                L.marker([driver.latitude, driver.longitude], {icon: driverIcon})
                    .addTo(map)
                    .bindPopup(popupContent, {
                        maxWidth: 300,
                        className: 'custom-popup'
                    });
            }
        });

        function contactDriver(driverId) {
            alert('Contact functionality coming soon! Driver ID: ' + driverId);
            // Implement your contact logic here
        }
    </script>
    @endif
</body>
</html>