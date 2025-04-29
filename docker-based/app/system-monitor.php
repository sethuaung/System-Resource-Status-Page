require 'vendor/autoload.php';
use MongoDB\Client;

$mongoUri = getenv('MONGO_URI');
$client = new Client($mongoUri);
$collection = $client->systemMonitoring->metrics;

// Your existing system monitoring logic here...
