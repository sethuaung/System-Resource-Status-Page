<?php
require 'vendor/autoload.php'; // Include Composer's autoload file

use MongoDB\Client;

function getSystemData() {
    $data = [];

    // CPU Usage
    exec('ps aux', $output, $status);
    $totalCpu = 0;
    if ($status === 0) {
        foreach ($output as $index => $line) {
            if ($index === 0) continue; // Skip header line
            $columns = preg_split('/\s+/', $line);
            $totalCpu += floatval($columns[2]); // CPU usage column
        }
    }
    $data['cpuUsage'] = round($totalCpu, 2);

    // Memory Usage
    $memoryInfo = shell_exec("free -m");
    $lines = explode("\n", $memoryInfo);
    $memoryValues = preg_split('/\s+/', $lines[1]);
    $totalMemory = intval($memoryValues[1]);
    $usedMemory = intval($memoryValues[2]);
    $data['memoryUsage'] = round(($usedMemory / $totalMemory) * 100, 2);

    // HDD Usage
    $hddInfo = shell_exec("df -h --output=pcent / | tail -1");
    $data['hddUsage'] = intval(trim($hddInfo, '%'));

    // Hardware Information
    $data['hardwareInfo'] = [
        'cpu' => shell_exec("lscpu"),
        'ram' => shell_exec("free -h"),
        'hdd' => shell_exec("df -h")
    ];

    // Open Ports
    $data['openPorts'] = shell_exec("netstat -tuln") ?: shell_exec("ss -tuln");

    // Web Service Ports
    $data['webPorts'] = shell_exec("netstat -tuln | grep ':80\\|:443\\|:8080\\|:8443'") ?: shell_exec("ss -tuln | grep ':80\\|:443\\|:8080\\|:8443'");

    // Installed Packages
    if (shell_exec("command -v dpkg")) {
        $data['installedPackages'] = shell_exec("dpkg -l");
    } elseif (shell_exec("command -v rpm")) {
        $data['installedPackages'] = shell_exec("rpm -qa");
    } elseif (shell_exec("command -v pacman")) {
        $data['installedPackages'] = shell_exec("pacman -Q");
    } elseif (shell_exec("command -v apk")) {
        $data['installedPackages'] = shell_exec("apk info");
    } else {
        $data['installedPackages'] = "Unsupported package manager.";
    }

    return $data;
}

function storeInMongoDB($data) {
    try {
        // Connect to MongoDB
        $client = new Client("mongodb+srv://<yourname>:<xxxx@mongodb.net/");
        $collection = $client->systemMonitoring->metrics; // Database: systemMonitoring, Collection: metrics

        // Insert data into MongoDB
        $data['timestamp'] = new MongoDB\BSON\UTCDateTime(); // Add timestamp
        $collection->insertOne($data);

        echo "Data successfully stored in MongoDB!\n";
    } catch (Exception $e) {
        echo "Error connecting to MongoDB: " . $e->getMessage() . "\n";
    }
}

// Collect system data
$systemData = getSystemData();

// Store the data in MongoDB
storeInMongoDB($systemData);
?>
