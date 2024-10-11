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
    if (params.has('location')) {
        document.getElementById('selectedLocation').textContent = params.get('location');
    }
    if (params.has('rego')) {
        document.getElementById('rego').value = params.get('rego').toUpperCase();
    }
    if (params.has('sort')) {
        document.getElementById('selectedSortBy').setAttribute('data-category', params.get('sort'));
    }
    if (params.has(`dir`)) {
        if (params.get('dir') === 'asc') currentDirection = 'asc';
        else currentDirection = 'desc';
    }
    if (params.has('page')) {
        page = params.get('page');
    } else {
        page = 1;
    }

    // load Maintenance data on page load
    getMaintenanceData(page);
};

var lastRequestTimestamp = 0;

// Set default sort direction
var currentDirection = "asc";

function getMaintenanceData(page = 1) {

    // track time of latest request
    var requestTimestamp = Date.now();
    lastRequestTimestamp = requestTimestamp;

    // Get input values from html elements
    var odometerMin = document.getElementById('odometerMin').value;
    var odometerMax = document.getElementById('odometerMax').value;
    var vehicleType = document.getElementById('selectedVehicleType').textContent === "ALL" ? "" : document.getElementById('selectedVehicleType').textContent;
    var location = document.getElementById('selectedLocation').textContent === "ANY" ? "" : document.getElementById('selectedLocation').textContent;
    var startDate = document.getElementById('startDate').value;
    var endDate = document.getElementById('endDate').value;
    var rego = document.getElementById('rego').value.toUpperCase();
    var sortBy = document.getElementById('selectedSortBy').getAttribute('data-category') || 'Rego';


    // Check for valid Odometer inputs
    var validOdometerMin = odometerMin && !isNaN(odometerMin) ? odometerMin : '';
    var validOdometerMax = odometerMax && !isNaN(odometerMax) ? odometerMax : '';

    // Initialize limit and offset
    var limit = 25;
    var offset = (page - 1) * limit;

    // construct request URL
    var requestUrl = `PHP_Handlers/maintenance_handler.php?type=data&offset=${offset}&limit=${limit}`;
    var request = ``;

    var url = `maintenance.php?page=${page}`;

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

    // Add Location to query url and display url if there is an input
    if (location !== "") {
        request += `&location=` + encodeURIComponent(location);
        url += `&location=` + encodeURIComponent(location);
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

    // Add Sort by and Sort direction to query and display url
    request += `&sort=` + encodeURIComponent(sortBy) + `&dir=` + encodeURIComponent(currentDirection);
    url += `&sort=` + encodeURIComponent(sortBy) + `&dir=` + encodeURIComponent(currentDirection);

    // Update URL
    window.history.pushState({}, '', url);

    // Make AJAX request to get maintenance data
    var xhr = new XMLHttpRequest();
    xhr.open("GET", requestUrl + request, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);

            // Check if this is the latest request before processing data
            if (requestTimestamp === lastRequestTimestamp) {
                console.log(data);
                var output = '';

                // Update maintenance count
                document.getElementById('maintenance-count').textContent = data.count;

                // Display the data in the table
                data.data.forEach(function (maintenance) {
                    output += `
                    <div class="maintenance"> 
                        Maintenance ID: ${maintenance.maintenance_id}<br> 
                        Start_date: ${maintenance.start_date}<br>
                        End_date: ${maintenance.end_date}<br> 
                        Location: ${maintenance.location}<br> 
                        Odometer: ${maintenance.mileage} <br>
                        Vehicle_category: ${maintenance.vehicle_category}<br> 
                        Vehicle_rego: <a href="vehicle.php?rego=${maintenance.vehicle_rego}">${maintenance.vehicle_rego}</a> <br>
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
    var currentPage = parseInt(currentPage);
    var totalPages = parseInt(totalPages);
    var paginationDiv = document.querySelector('.pagination');
    paginationDiv.innerHTML = ''; // Clear existing pagination links

    // Handle 'Page 1' Link
    if (currentPage > 1) {
        var prevLink = document.createElement('a');
        prevLink.href = '#';
        prevLink.textContent = 'Page 1';
        prevLink.onclick = (e) => {
            e.preventDefault();
            getMaintenanceData(1);
        };
        paginationDiv.appendChild(prevLink);
        paginationDiv.appendChild(document.createTextNode('    '));

    }

    // Handle 'Previous page' Link
    if (currentPage > 2) {
        // Add Ellipsis between First page and Previous page
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
            getMaintenanceData(currentPage - 1);
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
            getMaintenanceData(currentPage + 1);
        };
        paginationDiv.appendChild(nextLink);
        paginationDiv.appendChild(document.createTextNode('    '));
    }

    // Handle Last Page Link
    if (currentPage < totalPages) {
        // Add Ellipsis between Last page and Next page
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
            getMaintenanceData(totalPages, false);
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
            getMaintenanceData(1);
        }
    });
});

// Handles Location Dropdown Button
document.addEventListener("DOMContentLoaded", function() {
    var locationMenu = document.getElementById('locationMenu');
    var selectedLocation = document.getElementById('selectedLocation');

    // Add event listener to all dropdown items
    locationMenu.addEventListener('click', function(event) {
        // Check if a dropdown item was clicked
        if (event.target.classList.contains('location-item')) {
            // Get the origin from the clicked item's data-category attribute
            var location = event.target.getAttribute('data-category');

            // Update the displayed text in the dropdown button and update trip date
            selectedLocation.innerText = location;
            getMaintenanceData(1);
        }
    });
});


// Handles Sort by Dropdown Button
document.addEventListener("DOMContentLoaded", function() {
    var sortMenu = document.getElementById('sortMenu');
    var selectedSortBy = document.getElementById('selectedSortBy');

    // Add event listener to all dropdown items
    sortMenu.addEventListener('click', function(event) {
        // Check if a dropdown item was clicked
        if (event.target.classList.contains('sort-item')) {
            // Update the displayed text in the dropdown button and update vehicle data
            selectedSortBy.innerText = event.target.innerText;
            selectedSortBy.setAttribute('data-category', event.target.getAttribute('data-category'));
            getMaintenanceData(1);
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    var sortDirection = document.getElementById('sort-direction');
    var sortImage = document.getElementById('sort-image');

    sortDirection.addEventListener('click', function(event) {
        // Toggle the sort direction
        if (currentDirection === "asc") {
            currentDirection = "desc";
            sortImage.src = "Resources/descending-sort.png";
        } else {
            currentDirection = "asc";
            sortImage.src = "Resources/ascending-sort.png";
        }

        // Fetch vehicle data with the new sort direction
        getMaintenanceData(1);
    });
});