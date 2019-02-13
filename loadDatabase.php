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