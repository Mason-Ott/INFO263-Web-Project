window.onload = function() {
    const params = new URLSearchParams(window.location.search);
    if (params.has('rego')) {
        // Update page title and rego Header
        rego = params.get('rego');
        document.getElementById('regoHeader').textContent = rego;
        document.getElementById('title').textContent = rego;
    }
    page1 = 1;
    page2 = 1;
    page3 = 1;
    getVehicleData();
    getVehicleChartData()
};

function getVehicleData(page1=1, page2=1, page3=1) {

    // Set offset values for pagination
    const limit = 20;
    var offset1 = (page1 - 1) * limit;
    var offset2 = (page2 - 1) * limit;
    var offset3 = (page3 - 1) * limit;

    // Create request to retrieve Vehicle data
    $.ajax({
        url: `PHP_Handlers/vehicle_handler.php?rego=${rego}&offset1=${offset1}&offset2=${offset2}&offset3=${offset3}`,
        method: "GET",
        success: function(data) {
            var vehicleOutput = "";
            var tripOutput = "";
            var relocationOutput = "";
            var maintenanceOutput = "";

            //Output vehicle data
            if (data.vehicle) {
                vehicleOutput = `
                    <div class="vehicle"> 
                        <h3>Vehicle Type:</h3> ${data.vehicle.vehicle_category}<br> 
                        <h3>Odometer:</h3> ${data.vehicle.odometer}<br>
                        <h3>Commissioned:</h3> ${data.vehicle.commissioned_date}<br>`
                if (data.vehicle.decommissioned_date) {
                    vehicleOutput += `<h5>Decommissioned:</h5> ${data.vehicle.decommissioned_date}<br>`;
                }
                if (data.vehicle.distance_since_maintenance >= 20000) {
                    vehicleOutput += `<div class="needingMaintenance">
                            <h5>Requires Maintenance</h5><br>
                        </div> 
                        <h5>Distance Since Maintenance:</h5> ${data.vehicle.distance_since_maintenance}<br>`;
                }
                vehicleOutput += `</div>`;
            }

            // Output Trips data
            if (data.trips.length > 0) {
                data.trips.forEach(function (trip) {
                    tripOutput += `
                    <div class="cell">
                    <div class="nowrap">
                        <h5>Start Date:</h5> ${trip.start_date}<br>
                        <h5>End Date:</h5> ${trip.end_date}<br>
                    </div> 
                        <h5>Origin:</h5> ${trip.origin}<br> 
                        <h5>Destination:</h5> ${trip.destination}<br> 
                        <h5>Distance:</h5> ${trip.distance} km<br>
                    </div>`;
                });
                handlePagination(data.tripCount.currentPage, data.tripCount.totalPages, 'pagination1', 1);
            } else {
                // Hide Trip section if no data
                document.getElementById("tripHeader").style.display = 'none';
                document.getElementById("pagination1").style.display = 'none';
                document.getElementById("tripData").style.display = 'none';
            }

            if (data.relocations.length > 0) {
                data.relocations.forEach(function (relocation) {
                    relocationOutput += `
                    <div class="cell">
                    <div class="nowrap">
                        <h5>Start Date:</h5> ${relocation.start_date}<br>
                        <h5>End Date:</h5> ${relocation.end_date}<br>
                    </div> 
                        <h5>Origin:</h5> ${relocation.origin}<br> 
                        <h5>Destination:</h5> ${relocation.destination}<br> 
                        <h5>Distance:</h5> ${relocation.distance} km<br>
                    </div>`;
                });
                handlePagination(data.relocationCount.currentPage, data.relocationCount.totalPages, 'pagination2', 2);
            } else {
                // Hide Relocation section if no data
                document.getElementById("relocationHeader").style.display = 'none';
                document.getElementById("pagination2").style.display = 'none';
                document.getElementById("relocationData").style.display = 'none';
            }

            // Output Maintenance data
            if (data.maintenance.length > 0) {
                data.maintenance.forEach(function (maintenance) {
                    maintenanceOutput += `
                    <div class="cell"> 
                        <div class="nowrap">
                            <h5>Start Date:</h5> ${maintenance.start_date}<br>
                            <h5>End Day:</h5> ${maintenance.end_date}<br>
                        </div>
                        <h5>Location:</h5> ${maintenance.location}<br> 
                        <h5>Odometer:</h5> ${maintenance.mileage} km<br> 
                    </div>`;
                });
                handlePagination(data.maintenanceCount.currentPage, data.maintenanceCount.totalPages, 'pagination3', 3);
            } else {
                // Hide Maintenance section if no data
                document.getElementById("maintenanceHeader").style.display = 'none';
                document.getElementById("pagination3").style.display = 'none';
                document.getElementById("maintenanceData").style.display = 'none';
            }

            // Update data outputs
            document.getElementById("vehicleData").innerHTML = vehicleOutput;
            document.getElementById("tripData").innerHTML = tripOutput;
            document.getElementById("relocationData").innerHTML = relocationOutput;
            document.getElementById("maintenanceData").innerHTML = maintenanceOutput;
        }
    });
}

// Pagination handler
function handlePagination(currentPage, totalPages, paginationDiv, type) {
    var currentPage = parseInt(currentPage);
    var totalPages = parseInt(totalPages);
    var paginationDiv = document.getElementById(paginationDiv);
    paginationDiv.innerHTML = ''; // Clear existing pagination links

    // Handle 'Page 1' Link
    if (currentPage > 1) {
        var prevLink = document.createElement('a');
        prevLink.href = '#';
        prevLink.textContent = 'Page 1';
        prevLink.onclick = (e) => {
            e.preventDefault();
            if (type === 1) getVehicleData(1, page2, page3);
            if (type === 2) getVehicleData(page1, 1, page3);
            if (type === 3) getVehicleData(page1, page2, 1);
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
            if (type === 1) getVehicleData(currentPage - 1, page2, page3);
            if (type === 2) getVehicleData(page1, currentPage - 1, page3);
            if (type === 3) getVehicleData(page1, page2, currentPage - 1);
        };
        paginationDiv.appendChild(prevLink);
        paginationDiv.appendChild(document.createTextNode('    '));
    }

    // Page Number text
    var pageNumber = document.createElement('span');
    pageNumber.textContent = currentPage;
    pageNumber.classList.add('pagination-current');
    paginationDiv.appendChild(pageNumber);
    paginationDiv.appendChild(document.createTextNode('    '));

    // Handle 'Next page' Link
    if (currentPage < (totalPages - 1)) {
        var nextLink = document.createElement('a');
        nextLink.href = '#';
        nextLink.textContent = 'Next Page';
        nextLink.onclick = (e) => {
            e.preventDefault();
            if (type === 1) getVehicleData(currentPage + 1, page2, page3);
            if (type === 2) getVehicleData(page1, currentPage + 1, page3);
            if (type === 3) getVehicleData(page1, page2, currentPage + 1);
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
            if (type === 1) getVehicleData(totalPages, page2, page3);
            if (type === 2) getVehicleData(page1, totalPages, page3);
            if (type === 3) getVehicleData(page1, page2, totalPages);
        };
        paginationDiv.appendChild(nextLink);
    }
}

function getVehicleChartData() {

    fetch(`PHP_Handlers/vehicle_chart_handler.php?rego=${rego}`)  // Adjust vehicle_id as needed
        .then(response => response.json())
        .then(data => {
            // Parse the dates from commissioned, movements, and maintenance
            const commissionedDate = new Date(data.dates[0].commissioned_date);  // Vehicle commission date
            var labels = [commissionedDate];
            var odometerReadings = [0];
            var dictionary = [];
            dictionary[data.dates[0].commissioned_date] = [,'COMMISSIONED', data.start_odometer.start_odometer,,];
            if (data.dates[0].decommissioned_date !== null) {
                dictionary[data.dates[0].decommissioned_date] = [,'DECOMMISSIONED', data.dates[0].odometer,,];
            }
            // Process movements (Trips and Relocations)
            data.movements.forEach(movement => {
                dictionary[movement.start_date] = [movement.end_date, movement.movement_type, movement.distance, movement.origin, movement.destination];
            });

            // Process maintenance data
            data.maintenance.forEach(maintenance => {
                dictionary[maintenance.start_date] = [maintenance.end_date, 'MAINTENANCE', maintenance.mileage, maintenance.location, maintenance.location];
            });

            const sortedDictionary = sortDictionary(dictionary);
            console.log(sortedDictionary);
            processChartData(sortedDictionary);


        });
}

function sortDictionary(dictionary) {
    var entries = Object.entries(dictionary);
    entries.sort((a, b) => new Date(a[0]) - new Date(b[0]));
    var sortedDictionary = Object.fromEntries(entries);
    console.log(sortedDictionary);
    return sortedDictionary;
}


function processChartData(sortedDictionary) {
    var labels = [];
    var odometerReadings = [];
    var eventTypes = [];  // Array to store event types with counts
    var eventProgress = [];
    var eventLocation = [];
    var lastOdometer = 0;

    // Counters for event types
    var tripCount = 0;
    var relocationCount = 0;
    var maintenanceCount = 0;

    for (const [startDate, entry] of Object.entries(sortedDictionary)) {
        var [endDate, type, value, origin, destination] = entry;
        value = Number(value);

        // Add the date and odometer reading
        labels.push(startDate);
        if (type === 'COMMISSIONED' || type === 'DECOMMISSIONED') {
            odometerReadings.push(value)
        } else {
            odometerReadings.push(lastOdometer);  // Odometer before the event
        }


        // Update event count and store event type with count
        if (type === 'TRIP') {
            tripCount++;
            eventTypes.push(`Trip${tripCount}`);
            eventProgress.push('Start');
            eventLocation.push(origin);
        } else if (type === 'RELOCATION') {
            relocationCount++;
            eventTypes.push(`Relocation${relocationCount}`);
            eventProgress.push('Start');
            eventLocation.push(origin);
        } else if (type === 'MAINTENANCE') {
            maintenanceCount++;
            eventTypes.push(`Maintenance${maintenanceCount}`);
            eventProgress.push('Start');
            eventLocation.push(origin);
        } else if (type === 'COMMISSIONED') {
            eventTypes.push('Commissioned');
            eventProgress.push('null');
            eventLocation.push('null');
        } else if (type === 'DECOMMISSIONED') {
            eventTypes.push('Decommissioned');
            eventProgress.push('null');
            eventLocation.push('null');
        }

        // Update odometer after event
        if (type === 'TRIP' || type === 'RELOCATION' ) {
            lastOdometer += value;
        } else if (type === 'COMMISSIONED' || type === 'DECOMMISSIONED' || type === 'MAINTENANCE') {
            lastOdometer = value;
        }

        // Add end date and updated odometer after event
        if (type === 'TRIP' || type === 'RELOCATION' || type === 'MAINTENANCE') {
            labels.push(endDate);
            odometerReadings.push(lastOdometer);
            // Repeat event type for the end date
            if (type === 'TRIP') {
                eventTypes.push(`Trip${tripCount}`);
                eventProgress.push('End');
                eventLocation.push(destination);
            } else if (type === 'RELOCATION') {
                eventTypes.push(`Relocation${relocationCount}`);
                eventProgress.push('End');
                eventLocation.push(destination);
            } else if (type === 'MAINTENANCE') {
                eventTypes.push(`Maintenance${maintenanceCount}`);
                eventProgress.push('End');
                eventLocation.push(destination);
            }
        }
    }

    console.log(odometerReadings);
    console.log(labels);
    console.log(eventTypes);
    // Pass event types to the chart creation function
    createLineChart(labels, odometerReadings, eventTypes, eventProgress, eventLocation);
}

function createLineChart(labels, odometerReadings, eventTypes, eventProgress, eventLocation) {
    const pointColors = eventTypes.map(type => {
        if (type.includes('Commissioned')) return 'rgba(255, 0, 0, 1)';  // Bright red
        if (type.includes('Decommissioned')) return 'rgba(255, 165, 0, 1)';  // Bright orange
        if (type.includes('Maintenance')) return 'rgba(0, 0, 255, 1)';  // Bright blue
        if (type.includes('Trip')) return 'rgba(0, 100, 20, 0.3)';  // Faded green
        if (type.includes('Relocation')) return 'rgba(100, 200, 120, 0.3)';  // Faded light green
        return 'rgba(0, 0, 0, 0.1)';  // Default color
    });

    const pointSizes = eventTypes.map(type => {
        if (type.includes('Commissioned')) return 8;  // Larger points
        if (type.includes('Decommissioned')) return 8;
        if (type.includes('Maintenance')) return 6;
        if (type.includes('Trip') || type.includes('Relocation')) return 3;
        return 1;  // Default
    });

    const ctx = document.getElementById('odometerChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Odometer Readings',
                data: odometerReadings,
                borderColor: 'rgba(0, 100, 20)',  // Line color
                fill: false,
                pointBackgroundColor: pointColors,  // Point colors
                pointRadius: pointSizes,  // Point sizes
                borderWidth: 2  // Line thickness
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Odometer Reading Over Time',
                    font: {
                        size: 20,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const index = tooltipItem.dataIndex;
                            const eventType = eventTypes[index];
                            const reading = odometerReadings[index];
                            const progress = eventProgress[index];
                            const location = eventLocation[index];
                            var output = `${eventType}`;
                            if (progress === 'Start' || progress === 'End') output += ` ${progress} in ${location}`;
                            output += `: ${reading} km`;
                            return output;
                        }
                    }
                },
                legend: {
                    labels: {
                        generateLabels: function(chart) {
                            // Define the legend labels and colors manually
                            const manualLabels = [
                                { text: 'Commissioned', color: 'rgba(255, 0, 0, 1)' },
                                { text: 'Decommissioned', color: 'rgba(255, 165, 0, 1)' },
                                { text: 'Trip', color: 'rgba(0, 100, 20, 0.3)' },
                                { text: 'Relocation', color: 'rgba(100, 200, 120, 0.3)' },
                                { text: 'Maintenance', color: 'rgba(0, 0, 255, 1)' },
                            ];
                            return manualLabels.map(label => ({
                                text: label.text,
                                fillStyle: label.color,
                                strokeStyle: label.color,
                                hidden: false,  // Ensure legend is always visible
                                lineCap: 'round',
                                lineJoin: 'round'
                            }));
                        }
                    }
                }
            },
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'month',  // Change this to 'month' or 'week' to reduce clutter
                        tooltipFormat: 'MMM dd, yyyy',  // Format used in tooltips
                        displayFormats: {
                            week: 'MMM yyyy',  // Format for displaying dates on the x-axis
                        },
                    },
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    beginAtZero: false,
                    title: {
                        display: true,
                        text: 'Odometer Reading (KM)'
                    }
                }
            }
        }
    });
}