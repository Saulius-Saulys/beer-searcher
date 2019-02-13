<?php
include("database.php");
$db = new Database();
$geocodesFile = 'dumps/geocodes.csv';
$beersFile = 'dumps/beers.csv';
$breweriesFile = 'dumps/breweries.csv';
$categoriesFile = 'dumps/categories.csv';
$stylesFile = 'dumps/styles.csv';

$connect = mysqli_connect("localhost", "root", "", "routesdb");  
$db->ImportData($beersFile, $breweriesFile, $categoriesFile, $geocodesFile, $stylesFile, $connect);


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

// echo "1 brewery ID: " . $values[0]["brewery_id"]. " <br>";
// echo "1 longitude" . $values[0]["longitude"]. " <br>";
// echo "2 brewery ID: " . $values[1]["brewery_id"]. " <br>";
// echo "2 longitude" . $values[1]["longitude"]. " <br>";

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
    $startLat = 51.355468;
    $startLong = 11.100790;
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
    var_dump($marsrutas);

    for($i = 0; $i <= count($marsrutas) - 1; $i++){
        if(!empty($marsrutas[count($marsrutas) - 1])){
            echo "Brawery name: " . $marsrutas[$i]["brewery_id"] . " Latutude: " . $marsrutas[$i]["latitude"] . " Longitude: " . $marsrutas[$i]["longitude"] ."<br>";
        }
    }
?>  


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
 
 
 <form action="rezultatas.php" method="post">
     <div class="col-md-6 mb-3">
         <label for="firstName">LAT: </label>
         <input type="number" step="0.000001" class="form-control" id="firstName" placeholder="" value="" required="" name = "lat">
  
         <label for="firstName">LONG: </label>
         <input type="number" step="0.000001" class="form-control" id="firstName" placeholder="" value="" required="" name = "long">
         <div class="pt-3">
             <button type="submit" class="btn btn-primary" name="submit">Primary</button>  
         </div>
     </div>
 </form>
