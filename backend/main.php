<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include("api_util.php");
include("database.php");
// echo '{"o": 123, "t": 1234567}'

$connection = connect_database("127.0.0.1", "root", "", "Stock");


if (isset($_GET["symbol"])){
    $symbol = $_GET["symbol"];
}else{
    echo '{"Error": "No Symbol Provided"}';
    exit;
}
$allData = get_stock_data($connection, $symbol);
if (count($allData) == 0){
    $existingData = null;
}
else{
    $lastIndex = count($allData)-1;
    $existingData = $allData[$lastIndex];
}

if (isset($_GET["history"])){
    echo json_encode($allData);
    exit;
}




$refreshTime = 3600;
if ($existingData){
    $dataTimeStamp = 0;
    if(isset($existingData["timestamp"])){
        $dataTimeStamp = $existingData["timestamp"];
    }
    
    $currentTime = time();
    // echo ($currentTime - $dataTimeStamp);
    if ($currentTime - $dataTimeStamp > $refreshTime){
        $newData = fetch_current_data($symbol);

        if ($newData){
            $result = insert_stock_data($connection, $symbol, $newData);
            if ($result){
                $databaseFormatData = ["timestamp" => $newData["t"], "price" => $newData["o"], "symbol" => $symbol];
                echo json_encode($databaseFormatData);
            }
            else{
                echo '{"Error": "Data could not be inserted"}';
            }
        }
        else{
            echo '{"Error": "Data could not be fetched"}';
            exit;
        }
    }else{
        echo json_encode($existingData);
        exit;
    }
}else{
    $newData = fetch_current_data($symbol);

    if ($newData){
        $result = insert_stock_data($connection, $symbol, $newData);
        if ($result){
            $databaseFormatData = ["timestamp" => $newData["t"], "price" => $newData["o"], "symbol" => $symbol];
            echo json_encode($databaseFormatData);
        }
        else{
            echo '{"Error": "Data could not be insert"}';
        }
    }
    else{
        echo '{"Error": "Data could not be fetched"}';
        exit;
    }
}

$data = fetch_current_data("AAPL");
insert_stock_data($connection, "AAPL", $data);


?>