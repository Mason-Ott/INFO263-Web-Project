function showQuarterlyIndicatorsChart(data) {
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Hire Revenue',
                    data: data.hire_revenue,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Vehicle Purchasing',
                    data: data.vehicle_purchasing,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Maintenance Expenses',
                    data: data.maintenance_expenses,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Relocation Expenses',
                    data: data.relocation_expenses,
                    borderColor: 'rgba(255, 206, 86, 1)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Upgrade Losses',
                    data: data.upgrade_losses,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Profit',
                    data: data.profit,
                    borderColor: 'rgba(54, 162, 235, 0.5)',
                    fill: false,
                    tension: 0.1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Quarterly Indicators',
                    font: {
                        size: 20
                    }
                }
            }
        }
    });
}


function showTripsChart(data) {
    Chart.defaults.datasets.line.fill = false;

    var ctx = document.getElementById('tripsChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'AUCKLAND Outgoing',
                    data: data.auckland_outgoing,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false,
                    tension: 0.1,
                    pointRadius: 3
                },
                {
                    label: 'AUCKLAND Incoming',
                    data: data.auckland_incoming,
                    borderColor: 'rgba(255, 99, 132, 0.5)',
                    fill: false,
                    tension: 0.1,
                    borderDash: [5, 5],
                    pointRadius: 3
                },
                {
                    label: 'CHRISTCHURCH Outgoing',
                    data: data.christchurch_outgoing,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false,
                    tension: 0.1,
                    pointRadius: 3
                },
                {
                    label: 'CHRISTCHURCH Incoming',
                    data: data.christchurch_incoming,
                    borderColor: 'rgba(54, 162, 235, 0.5)',
                    fill: false,
                    tension: 0.1,
                    borderDash: [5, 5],
                    pointRadius: 3
                },
                {
                    label: 'DUNEDIN Outgoing',
                    data: data.dunedin_outgoing,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false,
                    tension: 0.1,
                    pointRadius: 3
                },
                {
                    label: 'DUNEDIN Incoming',
                    data: data.dunedin_incoming,
                    borderColor: 'rgba(75, 192, 192, 0.5)',
                    fill: false,
                    tension: 0.1,
                    borderDash: [5, 5],
                    pointRadius: 3
                },
                {
                    label: 'QUEENSTOWN Outgoing',
                    data: data.queenstown_outgoing,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    fill: false,
                    tension: 0.1,
                    pointRadius: 3
                },
                {
                    label: 'QUEENSTOWN Incoming',
                    data: data.queenstown_incoming,
                    borderColor: 'rgba(153, 102, 255, 0.5)',
                    fill: false,
                    tension: 0.1,
                    borderDash: [5, 5],
                    pointRadius: 3
                },
                {
                    label: 'WELLINGTON Outgoing',
                    data: data.wellington_outgoing,
                    borderColor: 'rgba(255, 206, 86, 1)',
                    fill: false,
                    tension: 0.1,
                    pointRadius: 3
                },
                {
                    label: 'WELLINGTON Incoming',
                    data: data.wellington_incoming,
                    borderColor: 'rgba(255, 206, 86, 0.5)',
                    fill: false,
                    tension: 0.1,
                    borderDash: [5, 5],
                    pointRadius: 3
                }
            ]
        },
        options: {
            elements: {
                line: {
                    fill: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Outgoing/Incoming Trips'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month (YYYY-MM)'
                    },
                    ticks: {
                        autoSkip: false,
                        callback: function(value, index, values) {

                            return this.getLabelForValue(value);
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Outgoing/Incoming Trips per Location',
                    font: {
                        size: 20
                    }
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
}


