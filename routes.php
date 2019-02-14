<?php
include("logic.php");

$startLat =  $_POST['lat'];
$startLong =  $_POST['long'];
echo "Įveskite LAT koordinates: " . $startLat . "<br />";
echo "Įveskite LONG koordinates: " .$startLong . "<br />";

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
$currentDistance = 0;
$fullDistance = 0;
$homeDistance = 0;
$route = array();    
while($fullDistance <= 2000){
    $minValue = array();
    $shortestWay = 9999999999;        
    for($i = 0; $i <= count($values) - 1; $i++){
        if(empty($route)){
            $distanceFirstRoute = Distance($startLat, $startLong, $values[$i]["latitude"], $values[$i]["longitude"]);
            if($distanceFirstRoute < $shortestWay){
                $shortestWay = $distanceFirstRoute;
                $minValue = $values[$i];
            }
        }
        else{
            $routeLatitude = $route[count($route)-1]["latitude"];
            $routeLongitude = $route[count($route)-1]["longitude"];

            if(!empty($values[$i])){
                $valuesLatitude = $values[$i]["latitude"];
                $valuesLongitude = $values[$i]["longitude"];
            }

            if(!empty($route[count($route) - 1])){
                if($routeLatitude != $valuesLatitude && $routeLongitude != $valuesLongitude){
                    $distanceFirstRoute = Distance($routeLatitude, $routeLongitude, $valuesLatitude, $valuesLongitude);
                    if($distanceFirstRoute < $shortestWay){
                        $shortestWay = $distanceFirstRoute;
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
    $route[] = $minValue;
    $currentDistance += $shortestWay;
    $ammountOfBreweries = count($route) - 1;
    if(!empty($route[count($route) - 1])){
        $homeDistance = Distance($startLat, $startLong, $route[$ammountOfBreweries]["latitude"], $route[$ammountOfBreweries]["longitude"]);
    }
    $fullDistance = $currentDistance + $homeDistance;
}
unset($route[count($route) - 1]);
PrintBreweries($route, $startLat, $startLong);
PrintCollectedBeers($route);
?>  

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

<form action="index.php" method="post" class="col-md-6 mb-3">
    <label for="firstName"><br><br>Find another route: </label>
    <div class="pt-3">
        <button type="submit" class="btn btn-primary" name="submit">Back</button>  
    </div>
</form>