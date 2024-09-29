window.onload = function() {
    const params = new URLSearchParams(window.location.search);
    if (params.has('rego')) {
        document.getElementById('regoTitle').textContent = params.get('rego');
        rego = params.get('rego');  // Get rego from URL params
    }
    getVehicleData();
};

function getVehicleData() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `PHP_Handlers/vehicle_handler.php?rego=${rego}`, true);
    xhr.onreadystatechange = function () {
        console.log(xhr.responseText);
        if (xhr.readyState == 4 && xhr.status == 200) {
            const data = JSON.parse(xhr.responseText);
            let vehicleOutput = "";
            let tripOutput = "";
            let maintenanceOutput = "";

            // Vehicle data (should be a single object, not array)
            if (data.vehicle) {
                vehicleOutput = `
                    <div class="vehicle"> 
                        Type: ${data.vehicle.vehicle_category}<br> 
                        Odometer: ${data.vehicle.odometer}<br>
                        Commissioned: ${data.vehicle.commissioned_date}<br> 
                        Decommissioned: ${data.vehicle.decommissioned_date}<br>`;
                if (data.vehicle.distance_since_maintenance >= 20000) {
                    vehicleOutput += `<div class="needingMaintenance">
                            Requires Maintenance<br>
                        </div> 
                        Distance Since Maintenance: ${data.vehicle.distance_since_maintenance}<br>`;
                }
                vehicleOutput += `</div>`;
            }

            // Trips data (array)
            if (data.trips.length > 0) {
                data.trips.forEach(function (trip) {
                    tripOutput += `
                    <div class="vehicle"> 
                        Start Date: ${trip.start_date}<br>
                        End Date: ${trip.end_date}<br> 
                        Origin: ${trip.origin}<br> 
                        Destination: ${trip.destination}<br> 
                        Distance: ${trip.distance} <br>
                    </div>`;
                });
            }

            // Maintenance data (array)
            if (data.maintenance.length > 0) {
                data.maintenance.forEach(function (maintenance) {
                    maintenanceOutput += `
                    <div class="vehicle"> 
                        Start Day: ${maintenance.start_date}<br> 
                        End Day: ${maintenance.end_date}<br>
                        Location: ${maintenance.location}<br> 
                        Odometer: ${maintenance.mileage}<br> 
                    </div>`;
                });
            }

            document.getElementById("vehicleData").innerHTML = vehicleOutput;
            document.getElementById("tripData").innerHTML = tripOutput;
            document.getElementById("maintenanceData").innerHTML = maintenanceOutput;
        }
    }
    xhr.send();
}
