<?php
$inputFile = $argv[1] ?? false;
$outputFile = $argv[2] ?? false;
$apiKey = $argv[3] ?? false;
if (!$inputFile)
    die("Missing input and output files\n");
if (!$outputFile)
    die("Missing output file\n");
if (!$apiKey)
    die("Missing API Key\n");
$inputHandler = fopen($inputFile, 'r');
$outputHandler = fopen($outputFile, 'w');
$inputExtension = pathinfo($inputFile, PATHINFO_EXTENSION);
$outputExtension = pathinfo($outputFile, PATHINFO_EXTENSION);
if (!$inputHandler || empty($inputExtension) || strtolower($inputExtension) != 'csv')
    die("Invalid input file\n");
if (!$outputHandler || empty($outputExtension) || strtolower($outputExtension) != 'csv')
    die("Invalid output file\n");
$linecount = 0;
while(!feof($inputHandler)){
    $line = fgets($inputHandler);
    $linecount++;
}
echo "$linecount addresses detected\nStart fetching data...\n";
rewind($inputHandler);
fputcsv($outputHandler, ['ADDRESS', 'LAT', 'LNG'], ";");
$current = 1;
while($data = fgetcsv($inputHandler, 10000, ";")) {
    $address = implode('', $data);
    $data = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key='.$apiKey);
    $geocoding = json_decode($data);
    $lat = '';
    $lng = '';
    if (isset($geocoding->results[0])) {
        $lat = $geocoding->results[0]->geometry->location->lat;
        $lng = $geocoding->results[0]->geometry->location->lng;
        echo "$current/$linecount fetched\n";
    } else {
        echo "$current/$linecount skipped\n";
    }
    fputcsv($outputHandler, [$address, $lat, $lng], ";");
    $current++;
}
fclose($inputHandler);
fclose($outputHandler);
echo "Done\n";