document.addEventListener('DOMContentLoaded', function() {
    // Get user's current location
    document.getElementById('nearest_store').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLon = position.coords.longitude;

            const storeSelector = document.getElementById('store-selector');
            const storeOptions = storeSelector.options;
            let nearestStore = null;
            let nearestDistance = Infinity;

            for (let i = 1; i < storeOptions.length; i++) { // Start from 1 to skip "Select a Store"
                const storeData = storeOptions[i].getAttribute('data-location');
                if (storeData) {
                    const [storeLat, storeLon] = storeData.split(',').map(Number);
                    const distance = haversineDistance(userLat, userLon, storeLat, storeLon);

                    // Check if this store is the nearest one
                    if (distance < nearestDistance) {
                        nearestDistance = distance;
                        nearestStore = storeOptions[i].value;
                    }
                }
            }

            // Set the nearest store as selected
            if (nearestStore) {
                storeSelector.value = nearestStore;
                document.cookie = "selected_store=" + nearestStore + "; path=/";
            }

        }, function(error) {
            console.error('Error getting location:', error);
        });
    } else {
        console.log("Geolocation is not supported by this browser.");
    }
    location.reload();
    });

});

// Haversine formula to calculate the distance
function haversineDistance(lat1, lon1, lat2, lon2) {
    const toRad = (angle) => (angle * Math.PI) / 180; // Convert degrees to radians
    const R = 6371; // Radius of the Earth in kilometers

    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);

    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

    return R * c; // Distance in kilometers
}

document.getElementById('store-selector').addEventListener('change', function() {
    document.cookie = "selected_store=" + this.value + "; path=/";
    location.reload(); // Reload the page to apply the filter
});
