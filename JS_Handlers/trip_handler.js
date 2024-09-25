// Handle page load with query parameters
window.onload = function() {
    // Allows data to be shown based on inputs from url
    var params = new URLSearchParams(window.location.search);
    if (params.has('DistMin')) {
        document.getElementById('distanceMin').value = params.get('DistMin');
    }
    if (params.has('DistMax')) {
        document.getElementById('distanceMax').value = params.get('DistMax');
    }
    if (params.has('vehicleType')) {
        document.getElementById('selectedVehicleType').textContent = params.get('vehicleType');
    }
    if (params.has('origin')) {
        document.getElementById('selectedOrigin').textContent = params.get('origin');
    }
    if (params.has('destination')) {
        document.getElementById('selectedDestination').textContent = params.get('destination');
    }
    if (params.has('rego')) {
        document.getElementById('rego').value = params.get('rego').toUpperCase();
    }
    if (params.has('page')) {
        page = params.get('page');
    } else {
        page = 1;
    }

    // load trip data on page load
    getTripData(page);
};

var lastRequestTimestamp = 0;

function getTripData(page = 1) {

    // track time of latest request
    var requestTimestamp = Date.now();
    lastRequestTimestamp = requestTimestamp;

    // Get input values from html elements
    var distanceMin = document.getElementById('distanceMin').value;
    var distanceMax = document.getElementById('distanceMax').value;
    var vehicleType = document.getElementById('selectedVehicleType').textContent === "ALL" ? "" : document.getElementById('selectedVehicleType').textContent;
    var origin = document.getElementById('selectedOrigin').textContent === "ANY" ? "" : document.getElementById('selectedOrigin').textContent;
    var destination = document.getElementById('selectedDestination').textContent === "ANY" ? "" : document.getElementById('selectedDestination').textContent;
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;
    var rego = document.getElementById('rego').value.toUpperCase();

    // Check for valid Distance inputs
    var validDistanceMin = distanceMin && !isNaN(distanceMin) ? distanceMin : '';
    var validDistanceMax = distanceMax && !isNaN(distanceMax) ? distanceMax : '';

    // Initialize limit and offset
    var limit = 25;
    var offset = (page - 1) * limit;

    // construct request URL

    var requestUrl = `PHP_Handlers/trip_handler.php?type=data&offset=${offset}&limit=${limit}`;
    var request = ``;

    var url = `trips.php?page=${page}`;

    // Add Minimum Distance to query url and display url if there is a valid input
    if (validDistanceMin) {
        request += `&DistMin=` + encodeURIComponent(validDistanceMin);
        url += `&DistMin=` + encodeURIComponent(validDistanceMin);
    }

    // Add Maximum Distance to query url and display url if there is a valid input
    if (validDistanceMax) {
        request += `&DistMax=` + encodeURIComponent(validDistanceMax);
        url += `&DistMax=` + encodeURIComponent(validDistanceMax);
    }

    // Add Vehicle Type to query url and display url if there is an input
    if (vehicleType) {
        request += `&vehicleType=` + encodeURIComponent(vehicleType);
        url += `&vehicleType=` + encodeURIComponent(vehicleType);
    }

    // Add Origin to query url and display url if there is an input
    if (origin !== "") {
        request += `&origin=` + encodeURIComponent(origin);
        url += `&origin=` + encodeURIComponent(origin);
    }

    // Add Destination to query url and display url if there is an input
    if (destination !== "") {
        url += `&destination=` + encodeURIComponent(destination);
        request += `&destination=` + encodeURIComponent(destination);
    }

    // Add Rego to query url and display url if there is an input
    if (rego !== "") {
        request += `&rego=` + encodeURIComponent(rego);
        url += `&rego=` + encodeURIComponent(rego);
    }

    // Add Start Date to query url if there is input
    if (startDate) {
        request += `&start=` + encodeURIComponent(startDate);
    }

    // Add End Date to query url if there is input
    if (endDate) {
        request += '&end=' + encodeURIComponent(endDate);
    }

    // Update URL
    window.history.pushState({}, '', url);

    // Make AJAX request to get trip data
    var xhr = new XMLHttpRequest();
    xhr.open("GET", requestUrl + request, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);

            // Check if this is the latest request before processing data
            if (requestTimestamp === lastRequestTimestamp) {
                console.log(data);
                var output = '';

                // Update trip count
                document.getElementById('trip-count').textContent = data.count;

                // Display the data in the table
                data.data.forEach(function (trip) {
                    output += `
                    <div class="trip"> 
                        Trip ID: ${trip.trip_id}<br> 
                        Start_date: ${trip.start_date}<br>
                        End_date: ${trip.end_date}<br> 
                        Origin: ${trip.origin}<br> 
                        Destination: ${trip.destination}<br> 
                        Distance: ${trip.distance} <br>
                        Vehicle_category: ${trip.vehicle_category}<br> 
                        Vehicle_rego: <a href="vehicle.php?rego=${trip.vehicle_rego}">${trip.vehicle_rego}</a> <br>
                    </div>`;
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
            getTripData(1);
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
            getTripData(currentPage - 1);
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
            getTripData(currentPage + 1);
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
            getTripData(totalPages, false);
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

            // Update the displayed text in the dropdown button and update trip date
            selectedVehicleType.innerText = vehicleCategory;
            getTripData(1);
        }
    });
});

// Handles Origin Dropdown Button
document.addEventListener("DOMContentLoaded", function() {
    var originMenu = document.getElementById('originMenu');
    var selectedOrigin = document.getElementById('selectedOrigin');

    // Add event listener to all dropdown items
    originMenu.addEventListener('click', function(event) {
        // Check if a dropdown item was clicked
        if (event.target.classList.contains('origin-item')) {
            // Get the origin from the clicked item's data-category attribute
            var origin = event.target.getAttribute('data-category');

            // Update the displayed text in the dropdown button and update trip date
            selectedOrigin.innerText = origin;
            getTripData(1);
        }
    });
});

// Handles Destination Dropdown Button
document.addEventListener("DOMContentLoaded", function() {
    var destinationMenu = document.getElementById('destinationMenu');
    var selectedDestination = document.getElementById('selectedDestination');

    // Add event listener to all dropdown items
    destinationMenu.addEventListener('click', function(event) {
        // Check if a dropdown item was clicked
        if (event.target.classList.contains('destination-item')) {
            // Get the destination from the clicked item's data-category attribute
            var destination = event.target.getAttribute('data-category');

            // Update the displayed text in the dropdown button and update trip data
            selectedDestination.innerText = destination;
            getTripData(1);
        }
    });
});
