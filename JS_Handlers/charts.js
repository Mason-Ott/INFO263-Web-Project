// Function to show the Quarterly Indicators Chart
function showQuarterlyIndicatorsChart(data) {
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels, // Time periods (e.g., quarterly)
            datasets: [
                {
                    label: 'Hire Revenue',
                    data: data.hire_revenue,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Vehicle Purchasing',
                    data: data.vehicle_purchasing,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Maintenance Expenses',
                    data: data.maintenance_expenses,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Relocation Expenses',
                    data: data.relocation_expenses,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Upgrade Losses',
                    data: data.upgrade_losses,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Profit',
                    data: data.profit,
                    backgroundColor: 'rgba(75, 192, 75, 0.2)',
                    borderColor: 'rgba(75, 192, 75, 1)',
                    borderWidth: 1
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
