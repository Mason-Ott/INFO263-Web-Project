$(document).ready(function() {
    $("#offset, #limit, #rego-input, #commissioned-date, #decommissioned-date, #vehicle-categories, #toSlider, #fromSlider").on('change', function() {
        filterVehicles();
    })
    $("#offset").trigger('change');
});

function getVehicleData(url) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url || 'vehicle.php', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var data = JSON.parse(xhr.responseText);
                var output = '';
                console.log(data);
                data.forEach(function(vehicle) {
                    output += `<div class='vehicle'>
                                Rego: ${vehicle.vehicle_rego}<br>
                                Type: ${vehicle.vehicle_category}<br>
                                Odometer reading: ${vehicle.odometer}<br>
                                Commissioned: ${vehicle.commissioned}<br>
                                ${vehicle.decommissioned ? "Decommissioned: " + vehicle.decommissioned + "<br>" : ""}
                               </div>`;
                });
                document.getElementById("data").innerHTML = output;
            } else {
                console.error('Error fetching vehicle data:', xhr.statusText);
            }
        }
    };
    xhr.send();
}



function filterVehicles() {
    // Get values from inputs
    var offset = document.getElementById('offset').textContent;
    var limit = document.getElementById('limit').textContent;

    var rego = document.getElementById('rego-input').value;
    var commissionedDate = document.getElementById('commissioned-date').value;
    var decommissionedDate = document.getElementById('decommissioned-date').value;
    var categories = document.getElementById('vehicle-categories').value;
    var odometerMin = document.getElementById('fromSlider').value;
    var odometerMax = document.getElementById('toSlider').value;
    console.log(offset);
    console.log(limit);
    console.log(rego);
    console.log(commissionedDate);
    console.log(decommissionedDate);
    console.log(categories);
    console.log(odometerMin);
    console.log(odometerMax);

    // Construct URL with parameters
    var url = `objects/vehicleCount.php?type=vehicleData&offset=${offset}&limit=${limit}`;

    if (rego) {
        url += "&rego=" + encodeURIComponent(rego);
    }

    if (commissionedDate) {
        url += "&commissioned=" + encodeURIComponent(commissionedDate);
    }

    if (decommissionedDate) {
        url += "&decommissioned=" + encodeURIComponent(decommissionedDate);
    }

    if (categories) {
        url += "&category=" + encodeURIComponent(categories.trim());
    }

    if (odometerMin) {
        url += "&omin=" + encodeURIComponent(odometerMin);
    }

    if (odometerMax) {
        url += "&omax=" + encodeURIComponent(odometerMax);
    }

    // Fetch the filtered vehicle data
    getVehicleData(url);
    // Update vehicle count
    getVehicleCount();
}


function getVehicleCount() {
    // Get values from inputs with null checks
    var regoInput = document.getElementById('rego-input');
    var commissionedDate = document.getElementById('commissioned-date');
    var decommissionedDate = document.getElementById('decommissioned-date');
    var vehicleCategories = document.getElementById('vehicle-categories');
    var fromSlider = document.getElementById('fromSlider');
    var toSlider = document.getElementById('toSlider');


    var rego = regoInput.value;
    var commissionedDateValue = commissionedDate.value;
    var decommissionedDateValue = decommissionedDate.value;
    var categories = vehicleCategories.value;
    var odometerMin = fromSlider.value;
    var odometerMax = toSlider.value;

    // Construct URL with parameters
    var url = `objects/vehicleCount.php?type=vehicleCount&omin=${odometerMin}&omax=${odometerMax}`;

    if (rego) {
        url += "&rego=" + encodeURIComponent(rego);
    }

    if (commissionedDateValue) {
        url += "&commissioned=" + encodeURIComponent(commissionedDateValue);
    }

    if (decommissionedDateValue) {
        url += "&decommissioned=" + encodeURIComponent(decommissionedDateValue);
    }

    if (categories) {
        url += "&category=" + encodeURIComponent(categories);
    }

    // Create XMLHttpRequest to get vehicle count
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            var response = JSON.parse(xhr.responseText); // Parse JSON response
            console.log('Vehicle count: ' + response.vehicle_count);

            // Update the count on the page
            document.getElementById('vehicle-count').textContent = response.vehicle_count;
        }
    };
    xhr.send();
}



