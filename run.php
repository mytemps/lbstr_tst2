<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('max_execution_time', '300'); //300 seconds = 5 minutes



function my_autoloader($class)
{
    include 'Classes/' . $class . '.php';
}

spl_autoload_register('my_autoloader');

$importApp = new App();

if(isset($_GET['statistics_mean'])){
    $importApp->statistics_mean = intval($_GET['statistics_mean']);
}
if(isset($_GET['max_days'])){
    $importApp->max_days = intval($_GET['max_days']);
}

try {
    $results = $importApp->startEmulation();
} catch (Exception $e) {
    die($e->getMessage());
}


// echo ['status'=>'success','response'=>json_encode($results, JSON_UNESCAPED_UNICODE)];

echo '<pre>';
print_r($results);
echo '</pre>';
