<?php
include("funkcionalumas.php");
$lat =  $_POST['lat'];
$long =  $_POST['long'];
echo "Įveskite LAT koordinates: " . $lat . "<br />";
echo "Įveskite LONG koordinates: " .$long . "<br />";

$connect = mysqli_connect("localhost", "root", "", "routesdb");  

$sql = "SELECT brewery_id, latitude, longitude FROM geocodes";
$result = $connect->query($sql);
$values = array();  

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $values[] = $row;
    }
} else {
    echo "0 results";
}

    $startLat = $_POST['lat'];
    $startLong = $_POST['long'];
    $nueitasKelias = 0;
    $visasKelias = 0;
    $keliasNamo = 0;
    $marsrutas = array();    
    while($visasKelias <= 2000){
        $minValue = array();
        $min = 9999999999;        
        for($i = 0; $i <= count($values) - 1; $i++){
            if(empty($marsrutas)){
                $distanceFirstRoute = distance($startLat, $startLong, $values[$i]["latitude"], $values[$i]["longitude"]);
                if($distanceFirstRoute < $min){
                    $min = $distanceFirstRoute;
                    $minValue = $values[$i];
                }
            }
            else{
                $marsrutasLatitude = $marsrutas[count($marsrutas)-1]["latitude"];
                $marsrutasLongitude = $marsrutas[count($marsrutas)-1]["longitude"];

                if(!empty($values[$i])){
                    $valuesLatitude = $values[$i]["latitude"];
                    $valuesLongitude = $values[$i]["longitude"];
                }

                if(!empty($marsrutas[count($marsrutas) - 1])){
                    if($marsrutasLatitude != $valuesLatitude && $marsrutasLongitude != $valuesLongitude){
                        $distanceFirstRoute = distance($marsrutasLatitude, $marsrutasLongitude, $valuesLatitude, $valuesLongitude);
                        if($distanceFirstRoute < $min){
                            $min = $distanceFirstRoute;
                            $minValue = $values[$i];
                        }
                    }
                }
            }
        }

        for($i = 0; $i <= count($values) - 1; $i++){
            if(!empty($values[$i])){
                if($minValue["latitude"] == $values[$i]["latitude"]){
                    unset($values[$i]);
                }
            }
        }
        $marsrutas[] = $minValue;
        $nueitasKelias += $min;
        $ilgis = count($marsrutas) - 1;
        if(!empty($marsrutas[count($marsrutas) - 1])){
            $keliasNamo = distance($startLat, $startLong, $marsrutas[$ilgis]["latitude"], $marsrutas[$ilgis]["longitude"]);
        }
        $visasKelias = $nueitasKelias + $keliasNamo;
    }
    unset($marsrutas[count($marsrutas) - 1]);
    spauzdinti($marsrutas, $startLat, $startLong);
    SpauzdintiSurinktusAlus($marsrutas);
?>  