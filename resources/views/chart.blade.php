<!DOCTYPE html>
<html>

<head>
    <title>Motel Scores Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <style>
        #chart-container {
            width: 90%;
            /* or 400px, 600px, etc. */
            margin: 0 auto;
            height: 300px;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Motel Scores</h2>
    <div id="chart-container">
        <canvas id="motelChart"></canvas>
    </div>

    <script>
        const motelNames = @json($scores->pluck('name'));
        const motelScores = @json($scores->pluck('score'));

        const ctx = document.getElementById('motelChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($scores->pluck('name')),
        datasets: [{
            label: 'Motel Score',
            data: @json($scores->pluck('score')),
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            datalabels: {
                anchor: 'end',
                align: 'start',
                color: '#000',
                font: {
                    weight: 'bold',
                    size: 12
                },
                formatter: function(value) {
                    return value.toFixed(2);
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 30
            }
        },
        responsive: true,
        maintainAspectRatio: false
    },
    plugins: [ChartDataLabels]
});

    </script>
</body>

</html>
