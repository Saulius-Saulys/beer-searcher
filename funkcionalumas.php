<?php
function distance($lat1, $lon1, $lat2, $lon2) {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
      return 0;
    }
    else {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $kilometers = $dist * 60 * 1.1515 * 1.609344;
      return $kilometers;
    }
}
function SpauzdintiKeliaNuoNamu(){

}
function Spauzdinti($marsrutas, $startLat, $startLong){
    echo "<br>Found " . count($marsrutas) . " beer factories: <br>";
    echo "-> HOME: " . $startLat . " , " . $startLong . " distance 0 km <br>";

    $fullDistance = distance($startLat, $startLong, $marsrutas[0]["latitude"], $marsrutas[0]["longitude"]);
    
    for($i = 0; $i <= count($marsrutas) - 1; $i++){
        $connect = mysqli_connect("localhost", "root", "", "routesdb");  
        $idbrewery = $marsrutas[$i]["brewery_id"];
        $sql = "SELECT name FROM breweries WHERE id = $idbrewery";
        $result = $connect->query($sql);
        $rezulatasMano = $result->fetch_assoc();

        if($i == 0){
            echo "-> [" . $idbrewery . "] " . $rezulatasMano["name"] . " " . $marsrutas[$i]["latitude"] . " , " . $marsrutas[$i]["longitude"];
            echo " Distance: " . round(distance($startLat, $startLong, $marsrutas[$i]["latitude"], $marsrutas[$i]["longitude"])) . " km<br>";
            $fullDistance += distance($startLat, $startLong, $marsrutas[$i]["latitude"], $marsrutas[$i]["longitude"]);
        }
        else{
            $idbrewery = $marsrutas[$i]["brewery_id"];
            echo "-> [" . $idbrewery . "] " . $rezulatasMano["name"] . " " . $marsrutas[$i]["latitude"] . " , " . $marsrutas[$i]["longitude"];
            echo " Distance: " . round(distance($marsrutas[$i - 1]["latitude"], $marsrutas[$i - 1]["longitude"], $marsrutas[$i]["latitude"], $marsrutas[$i]["longitude"])) . " km<br>";
            $fullDistance += distance($marsrutas[$i - 1]["latitude"], $marsrutas[$i - 1]["longitude"], $marsrutas[$i]["latitude"], $marsrutas[$i]["longitude"]);
        }
    }
    echo "<- HOME: " . $startLat . " , " . $startLong . " distance: " . round(distance($startLat, $startLong, $marsrutas[count($marsrutas) - 1]["latitude"], $marsrutas[count($marsrutas) - 1]["longitude"])) . " km<br>";
    $fullDistance += distance($startLat, $startLong, $marsrutas[count($marsrutas) - 1]["latitude"], $marsrutas[count($marsrutas) - 1]["longitude"]);
    echo "<br>Full distance: " . round($fullDistance);
}

function SpauzdintiSurinktusAlus($marsrutas){
    $connect = mysqli_connect("localhost", "root", "", "routesdb");
    $values = array();  
    
    for($i = 0; $i <= count($marsrutas) - 1; $i++){
        $idbrewery = $marsrutas[$i]["brewery_id"];
        $sql = "SELECT name FROM beers WHERE brewery_id = $idbrewery";
        $result = $connect->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $values[] = $row;
            }
        }
    }
    echo "<br><br>Collected " . count($values) . " beer types: <br>";
    
    for($i = 0; $i <= count($values) - 1; $i++){
        echo "<br>->" . $values[$i]["name"];
    }
    
}
?>