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
            }
        }
    });
}
