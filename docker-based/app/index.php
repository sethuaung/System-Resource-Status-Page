<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Monitoring Dashboard</title>
    <meta name="description" content="A dashboard for monitoring system resources, hardware, network, and installed packages.">
    <link rel="icon" type="image/png" href="favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { color: #333; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow: auto; }
        canvas { max-width: 600px; margin: 20px auto; }
    </style>
</head>
<body>
    <h1>System Monitoring Dashboard</h1>
    <h2>System Resource Usage</h2>
    <canvas id="resourceChart" width="400" height="200"></canvas>
    <h2>Hardware Information</h2>
    <pre id="hardwareInfo">Loading...</pre>
    <h2>Open TCP/UDP Ports</h2>
    <pre id="openPorts">Loading...</pre>
    <h2>Web Service Ports</h2>
    <pre id="webPorts">Loading...</pre>
    <h2>Installed Packages</h2>
    <pre id="installedPackages">Loading...</pre>

    <script>
        const ctx = document.getElementById('resourceChart').getContext('2d');
        const resourceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['CPU Usage (%)', 'Memory Usage (%)', 'HDD Usage (%)'],
                datasets: [{
                    label: 'System Resource Usage',
                    data: [0, 0, 0],
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });

        function fetchData() {
            $.getJSON('system-monitor.php', function(data) {
                resourceChart.data.datasets[0].data = [
                    data.cpuUsage, data.memoryUsage, data.hddUsage
                ];
                resourceChart.update();
                $('#hardwareInfo').text(data.hardwareInfo);
                $('#openPorts').text(data.openPorts);
                $('#webPorts').text(data.webPorts);
                $('#installedPackages').text(data.installedPackages);
            });
        }

        setInterval(fetchData, 5000);
        fetchData();
    </script>
</body>
</html>
