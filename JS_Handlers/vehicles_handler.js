// Handle page load with query parameters
window.onload = function() {
    // Allows data to be shown based on inputs from url
    var params = new URLSearchParams(window.location.search);
    if (params.has('OdoMin')) {
        document.getElementById('odometerMin').value = params.get('OdoMin');
    }
    if (params.has('OdoMax')) {
        document.getElementById('odometerMax').value = params.get('OdoMax');
    }
    if (params.has('vehicleType')) {
        document.getElementById('selectedVehicleType').textContent = params.get('vehicleType');
    }
    if (params.has('rego')) {
        document.getElementById('rego').value = params.get('rego').toUpperCase();
    }
    if (params.has('page')) {
        page = params.get('page');
    } else {
        page = 1;
    }

    // load Vehicle data on page load
    getVehicleData(page);
};

var lastRequestTimestamp = 0;

function getVehicleData(page = 1) {

    // track time of latest request
    var requestTimestamp = Date.now();
    lastRequestTimestamp = requestTimestamp;

    // Get input values from html elements
    var odometerMin = document.getElementById('odometerMin').value;
    var odometerMax = document.getElementById('odometerMax').value;
    var vehicleType = document.getElementById('selectedVehicleType').textContent === "ALL" ? "" : document.getElementById('selectedVehicleType').textContent;
    var commissionedDate = document.getElementById('commissioned').value;
    var decommissionedDate = document.getElementById('decommissioned').value;
    var rego = document.getElementById('rego').value.toUpperCase();
    var requiresMaintenance = document.getElementById('requiresMaintenance').checked;

    // Check for valid Odometer inputs
    var validOdometerMin = odometerMin && !isNaN(odometerMin) ? odometerMin : '';
    var validOdometerMax = odometerMax && !isNaN(odometerMax) ? odometerMax : '';

    // Initialize limit and offset
    var limit = 25;
    var offset = (page - 1) * limit;

    // construct request URL

    var requestUrl = `PHP_Handlers/vehicles_handler.php?type=data&offset=${offset}&limit=${limit}`;
    var request = ``;

    var url = `vehicles.php?page=${page}`;

    // Add Minimum Odometer to query url and display url if there is a valid input
    if (validOdometerMin) {
        request += `&OdoMin=` + encodeURIComponent(validOdometerMin);
        url += `&OdoMin=` + encodeURIComponent(validOdometerMin);
    }

    // Add Maximum Odometer to query url and display url if there is a valid input
    if (validOdometerMax) {
        request += `&OdoMax=` + encodeURIComponent(validOdometerMax);
        url += `&OdoMax=` + encodeURIComponent(validOdometerMax);
    }

    // Add Vehicle Type to query url and display url if there is an input
    if (vehicleType) {
        request += `&vehicleType=` + encodeURIComponent(vehicleType);
        url += `&vehicleType=` + encodeURIComponent(vehicleType);
    }

    // Add Rego to query url and display url if there is an input
    if (rego !== "") {
        request += `&rego=` + encodeURIComponent(rego);
        url += `&rego=` + encodeURIComponent(rego);
    }

    // Add Commissioned Date to query url if there is input
    if (commissionedDate) {
        request += `&com=` + encodeURIComponent(commissionedDate);
    }

    // Add Decommissioned Date to query url if there is input
    if (decommissionedDate) {
        request += '&decom=' + encodeURIComponent(decommissionedDate);
    }

    if (requiresMaintenance) {
        request += '&requires=' + encodeURIComponent(requiresMaintenance);
    }

    // Update URL
    window.history.pushState({}, '', url);

    console.log(requestUrl + request);  // Output the full request URL to check correctness


    // Make AJAX request to get vehicle data
    var xhr = new XMLHttpRequest();
    xhr.open("GET", requestUrl + request, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);
            console.log(data);

            // Check if this is the latest request before processing data
            if (requestTimestamp === lastRequestTimestamp) {
                var output = '';

                // Update vehicle count
                document.getElementById('vehicle-count').textContent = data.count;

                // Display the data in the table
                data.data.forEach(function (vehicle) {
                    output += `
                    <div class="vehicle"> 
                        Vehicle_rego: <a href="vehicle.php?rego=${vehicle.vehicle_rego}">${vehicle.vehicle_rego}</a> <br>
                        Vehicle Category: ${vehicle.vehicle_category}<br>
                        Odometer: ${vehicle.odometer}<br> 
                        Commissioned Date: ${vehicle.commissioned_date}<br>`;
                    if (vehicle.decommissioned_date) {
                        output += `Decommissioned: ${vehicle.decommissioned_date}<br>`;
                    }
                    if (vehicle.distance_since_maintenance != null) {
                        output += `Distance Since Maintenance: ${vehicle.distance_since_maintenance}<br>`;
                    }
                    output += `</div>`;
                });
                document.getElementById("data").innerHTML = output;

                // Handle pagination
                handlePagination(page, data.totalPages);
            }
        }
    };
    xhr.send();
}

// Pagination handler
function handlePagination(currentPage, totalPages) {
    var paginationDiv = document.querySelector('.pagination');
    paginationDiv.innerHTML = ''; // Clear existing pagination links

    // Handle 'Page 1' Link
    if (currentPage > 1) {
        var prevLink = document.createElement('a');
        prevLink.href = '#';
        prevLink.textContent = 'Page 1';
        prevLink.onclick = (e) => {
            e.preventDefault();
            getVehicleData(1);
        };
        paginationDiv.appendChild(prevLink);
        paginationDiv.appendChild(document.createTextNode('    '));

    }

    // Handle 'Previous page' Link
    if (currentPage > 2) {
        var leftEllipsis = document.createElement('span');
        leftEllipsis.textContent = '...';
        leftEllipsis.classList.add('pagination-text');
        paginationDiv.appendChild(leftEllipsis);
        paginationDiv.appendChild(document.createTextNode('    '));

        var prevLink = document.createElement('a');
        prevLink.href = '#';
        prevLink.textContent = 'Previous Page';
        prevLink.onclick = (e) => {
            e.preventDefault();
            getVehicleData(currentPage - 1);
        };
        paginationDiv.appendChild(prevLink);
        paginationDiv.appendChild(document.createTextNode('    '));
    }

    // Page Number text
    var pageNumber = document.createElement('span');
    pageNumber.textContent = currentPage;
    pageNumber.classList.add('pagination-text');
    paginationDiv.appendChild(pageNumber);
    paginationDiv.appendChild(document.createTextNode('    '));

    // Handle 'Next page' Link
    if (currentPage < (totalPages - 1)) {
        var nextLink = document.createElement('a');
        nextLink.href = '#';
        nextLink.textContent = 'Next Page';
        nextLink.onclick = (e) => {
            e.preventDefault();
            getVehicleData(currentPage + 1);
        };
        paginationDiv.appendChild(nextLink);
        paginationDiv.appendChild(document.createTextNode('    '));
    }

    // Handle Last Page Link
    if (currentPage < totalPages) {
        var RightEllipsis = document.createElement('span');
        RightEllipsis.textContent = '...';
        RightEllipsis.classList.add('pagination-text');
        paginationDiv.appendChild(RightEllipsis);
        paginationDiv.appendChild(document.createTextNode('    '));

        var nextLink = document.createElement('a');
        nextLink.href = '#';
        nextLink.textContent = 'Page ' + totalPages;
        nextLink.onclick = (e) => {
            e.preventDefault();
            getVehicleData(totalPages, false);
        };
        paginationDiv.appendChild(nextLink);
    }
}

// Handles Vehicle Category Dropdown Button
document.addEventListener("DOMContentLoaded", function() {
    var vehicleMenu = document.getElementById('vehicleMenu');
    var selectedVehicleType = document.getElementById('selectedVehicleType');

    // Add event listener to all dropdown items
    vehicleMenu.addEventListener('click', function(event) {
        // Check if a dropdown item was clicked
        if (event.target.classList.contains('vehicle-item')) {
            // Get the vehicle category from the clicked item's data-category attribute
            var vehicleCategory = event.target.getAttribute('data-category');

            // Update the displayed text in the dropdown button and update vehicle data
            selectedVehicleType.innerText = vehicleCategory;
            getVehicleData(1);
        }
    });
});
