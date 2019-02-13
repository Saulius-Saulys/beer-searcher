<?php
echo "LAT: " . 51.355468 . "<br>";
echo "LONG: " . 11.100790 . "<br>";
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
 
<form action="rezultatas.php" method="post" class="col-md-6 mb-3">
    <label for="firstName">LAT: </label>
    <input type="number" step="0.000001" class="form-control" id="firstName" placeholder="" value="" required="" name = "lat">
    <label for="firstName">LONG: </label>
    <input type="number" step="0.000001" class="form-control" id="firstName" placeholder="" value="" required="" name = "long">
    <div class="pt-3">
        <button type="submit" class="btn btn-primary" name="submit">Primary</button>  
    </div>
</form>

<form action="loadDatabase.php" method="post" class="col-md-6 mb-3">
    <label for="firstName">Load Database: </label>
    <div class="pt-3">
        <button type="submit" class="btn btn-primary" name="submit">Load</button>  
    </div>
</form>