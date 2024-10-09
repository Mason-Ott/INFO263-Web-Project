// Function to get the data for the histogram
function getHistogramData() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "PHP_Handlers/vehicle_lifetime_handler.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            renderHistogram(data);
        }
    };
    xhr.send();
}

// Function to get the data for the vehicle table
function getVehicleData(page, value) {
    var limit = 25;
    var offset = (page - 1) * limit;

    if (!value) {
        value = localStorage.getItem('binValue');
    }

    var xhr = new XMLHttpRequest();
    var url = "PHP_Handlers/vehicle_lifetime_handler.php?value=" + encodeURIComponent(value) + "&offset=" + encodeURIComponent(offset) + "&limit=" + encodeURIComponent(limit);
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            var output = "";
            data.data.forEach(function (vehicle) {
                // Output vehicle data
                output += `
                    <div class="vehicle"> 
                        Vehicle_rego: <a href="vehicle.php?rego=${vehicle.vehicle_rego}">${vehicle.vehicle_rego}</a> <br>
                        Days of Service: ${vehicle.service_days}<br>
                        Number of Trips: ${vehicle.no_of_trips}<br>
                        Vehicle Category: ${vehicle.vehicle_category}<br> 
                    </div>`;
            });
            document.getElementById("data").innerHTML = output;
            handlePagination(page, data.totalPages);
        }
    };
    xhr.send();
}

function renderHistogram(data) {
    var serviceDays = data;
    var binSize = 100;
    var binStart = 0;

    var trace = {
        x: serviceDays,
        type: 'histogram',
        autobinx: false,
        xbins: {
            end: 2000,
            size: binSize,
            start: binStart
        },
        marker: {
            color: 'rgba(34, 78, 200)'
        }
    };

    var layout = {
        title: 'Service Days Histogram',
        xaxis: { title: 'Service Days' },
        yaxis: { title: 'Count', rangemode: 'tozero', dtick: 200 },
        autosize: true,
        responsive: true
    };

    Plotly.newPlot('histogramChart', [trace], layout, { responsive: true });

    var histogramDiv = document.getElementById('histogramChart');
    histogramDiv.on('plotly_click', function(data) {
        var binValue = data.points[0].x;
        localStorage.setItem('binValue', binValue);
        // Get vehicle data for selected bin
        getVehicleData(page, binValue);
    });
}

window.addEventListener('resize', function() {
    Plotly.Plots.resize(document.getElementById('histogramChart'));
});

// Load the histogram when the page is loaded
window.onload = function() {
    page = 1;
    getHistogramData();
};

// Pagination handler
function handlePagination(currentPage, totalPages) {
    var paginationDiv = document.querySelector('.pagination');
    paginationDiv.innerHTML = ''; // Clear existing pagination links

    // Handle 'Page 1' Link
    if (currentPage > 1) {
        var firstPageLink = document.createElement('a');
        firstPageLink.href = '#';
        firstPageLink.textContent = 'Page 1';
        firstPageLink.onclick = (e) => {
            e.preventDefault();
            getVehicleData(1);
        };
        paginationDiv.appendChild(firstPageLink);
        paginationDiv.appendChild(document.createTextNode(' '));
    }

    // Handle Previous Page Link
    if (currentPage > 1 & (currentPage - 1) != totalPages) {
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
            getVehicleData(currentPage - 1);
        };
        paginationDiv.appendChild(prevLink);
        paginationDiv.appendChild(document.createTextNode(' '));
    }

    // Page Number Text
    var pageNumber = document.createElement('span');
    pageNumber.textContent = currentPage;
    pageNumber.classList.add('pagination-text');
    paginationDiv.appendChild(pageNumber);
    paginationDiv.appendChild(document.createTextNode(' '));

    // Handle Next Page Link
    if (currentPage < totalPages & (currentPage + 1) != totalPages) {
        var nextLink = document.createElement('a');
        nextLink.href = '#';
        nextLink.textContent = 'Next Page';
        nextLink.onclick = (e) => {
            e.preventDefault();
            getVehicleData(currentPage + 1);
        };
        paginationDiv.appendChild(nextLink);
        paginationDiv.appendChild(document.createTextNode(' '));
    }

    // Handle Last Page Link
    if (currentPage < totalPages) {
        // Add Ellipsis between Last page and Next page
        var RightEllipsis = document.createElement('span');
        RightEllipsis.textContent = '...';
        RightEllipsis.classList.add('pagination-text');
        paginationDiv.appendChild(RightEllipsis);
        paginationDiv.appendChild(document.createTextNode('    '));

        var lastPageLink = document.createElement('a');
        lastPageLink.href = '#';
        lastPageLink.textContent = 'Page ' + totalPages;
        lastPageLink.onclick = (e) => {
            e.preventDefault();
            getVehicleData(totalPages);
        };
        paginationDiv.appendChild(lastPageLink);
    }
}

