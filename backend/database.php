<?php 

function connect_database($server, $username, $password, $db){
    $connection = null;
    try {
        $connection = new mysqli($server, $username, $password, $db);
        if ($connection -> connect_errno){
            echo '{"error": "Database Connection Failed!"}';
        }

        return $connection;
    } catch (Exception $th) {
        
    }
}

function get_stock_data($connection, $stock_symbol){
    try {
       $result =  $connection -> query('SELECT * FROM stocks WHERE symbol= "'.$stock_symbol.'" ORDER BY timestamp;');
       if ($result){
        $data = $result -> fetch_all(MYSQLI_ASSOC);
        return $data;
       }
       else{
        return null;
       }

    } catch (Exception $th) {
        echo $th;
    }
}

function insert_stock_data($connection, $stock_symbol, $stock_data){
    try {
        $time_stamp = $stock_data["t"];
        $price = $stock_data["o"];

        $result =  $connection -> query('INSERT INTO stocks VALUES (
            '.$time_stamp.',
            "'.$stock_symbol.'",
            '.$price.'
        );');

        if ($result){
            return True;
        }
        else{
            return False;
        }
    } catch (Exception $th) {
        return Null;
    }

}
?>