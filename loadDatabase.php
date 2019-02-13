
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
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

<form action="index.php" method="post" class="col-md-6 mb-3">
    <label for="firstName">Data from csv successfuly loaded!!! <br><br> Back to home page: </label>
    <div class="pt-1">
        <button type="submit" class="btn btn-primary" name="submit">Back</button>  
    </div>
</form>