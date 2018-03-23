<?php

require 'vendor/autoload.php';
use GuzzleHttp\Client;

header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="tinkFile.csv"');

// do not cache the file
header('Pragma: no-cache');
header('Expires: 0');

// create a file pointer connected to the output stream
$file = fopen('php://output', 'w');
fputcsv($file, array('places_available', 'activity_duration_in_minutes','product_id','activity_start_datetime'));


try
{
    $client = new Client(['base_uri'=>'http://www.mocky.io/v2/']);
    $response = $client->get(
        '58ff37f2110000070cf5ff16');

    $result = $response->getBody();
    $apiData = json_decode($result, true);
}

catch (\GuzzleHttp\Exception\ClientException $e)
{
    echo $e.getCode() . "\r\n";
    echo $e.getMessage() . "\r\n";
}

catch (\GuzzleHttp\Exception\ServerException $e)
{
    echo $e.getCode() . "\r\n";
    echo $e.getMessage() . "\r\n";
}


if (!empty ($apiData['product_availabilities']))
{


foreach($apiData['product_availabilities'] as $product)

{
    if ($product['places_available'] >= 1 && $product['places_available'] <= 30 &&
        $product['activity_start_datetime'] >= "2017-10-30T11:59" && $product['activity_start_datetime'] <= "2017-11-31T11:59")

    {
        $products[]=$product;

    }
}

foreach($products as $item)
{
    fputcsv($file, $item);

}

}
else
{

    echo "No Data Exist";
}

fclose($file);





