<?php



function fetch_current_data($symbol){
    try {
        $url = "https://finnhub.io/api/v1/quote?symbol={$symbol}&token=cml7fc9r01qmnetgls4gcml7fc9r01qmnetgls50";
        $dataString = file_get_contents($url);
        // echo $data;
        $data = json_decode($dataString, true);
        return $data;
    } catch (Exception $th) {
        return null;
    }
}





?>