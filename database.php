<?php
class Database extends mysqli
{

    public function __construct(){
        
        parent::__construct("localhost", "root", "", "routesdb");


        
        if($this->connect_error){
            echo "Failed to connect to database" . $this->connect_error;
        }
        else{
            echo "Connected to database <br>";
        }
    }

    public function CreateTables($connect){
        mysqli_query($connect, "DROP TABLE geocodes;");
        mysqli_query($connect, "DROP TABLE beers;");
        mysqli_query($connect, "DROP TABLE braweries;");
        mysqli_query($connect, "DROP TABLE categories;");
        mysqli_query($connect, "DROP TABLE styles;");

        mysqli_query($connect, "CREATE TABLE geocodes (
            id int,
            brewery_id int,
            latitude double,
            longitude double,
            accuracy varchar(255) 
        );");
        mysqli_query($connect, "CREATE TABLE breweries (
            id int,
            name varchar(255),
            address1 varchar(255),
            address2 varchar(255),
            city varchar(255),
            state varchar(255),
            code int,
            country varchar(255),
            phone varchar(255),
            website varchar(255),
            filepath varchar(255),
            descript varchar(255),
            add_user int,
            last_mode datetime 
        );");
        mysqli_query($connect, "CREATE TABLE beers (
            id int,
            brewery_id int,
            name varchar(255),
            cat_id int,
            style_id int,
            abv double,
            ibu double,
            srm int,
            upc int,
            filepath varchar(255),
            describ varchar(255),
            add_user int,
            last_mode datetime 
        );");
        mysqli_query($connect, "CREATE TABLE categories (
            id int,
            cat_mode varchar(255),
            last_mode datetime 
        );");
        mysqli_query($connect, "CREATE TABLE styles (
            id int,
            cat_id int,
            style_name varchar(255),
            last_mode datetime 
        );");
    }
    public function importStyles($file, $connect){
        $values = array();  
        $file = fopen($file, 'r');
        while($row = fgetcsv($file)){
            $id = mysqli_real_escape_string($connect, $row[0]);  
            $cat_id = mysqli_real_escape_string($connect, $row[1]);  
            $style_name = mysqli_real_escape_string($connect, $row[2]);  
            $last_mode = mysqli_real_escape_string($connect, $row[3]);  

            $values[] = "('$id', '$cat_id', '$style_name', '$last_mode')";  
        }
        $sql = "INSERT INTO styles(id, cat_id, style_name, last_mode) VALUES ";  
        $sql .= implode(', ', $values);  
        mysqli_query($connect, $sql); 

        $firstRowQuery = "DELETE FROM styles WHERE id = 0;";  
        mysqli_query($connect, $firstRowQuery); 
    }
    public function importCategories($file, $connect){
        $values = array();  
        $file = fopen($file, 'r');
        while($row = fgetcsv($file)){
            $id = mysqli_real_escape_string($connect, $row[0]);  
            $cat_mode = mysqli_real_escape_string($connect, $row[1]);  
            $last_mode = mysqli_real_escape_string($connect, $row[2]);  

            $values[] = "('$id', '$cat_mode', '$last_mode')";  
        }
        $sql = "INSERT INTO categories(id, cat_mode, last_mode) VALUES ";  
        $sql .= implode(', ', $values);  
        mysqli_query($connect, $sql); 

        $firstRowQuery = "DELETE FROM categories WHERE id = 0;";  
        mysqli_query($connect, $firstRowQuery); 
    }
    public function importBeers($file, $connect){
        ini_set('mysqli.connect_timeout',300);
        ini_set('default_socket_timeout',300);
        $values = array();  
        $file = fopen($file, 'r');
        while($row = fgetcsv($file)){
            $id = mysqli_real_escape_string($connect, $row[0]);  
            $brewery_id = mysqli_real_escape_string($connect, $row[1]);  
            $name = mysqli_real_escape_string($connect, $row[2]);  
            $cat_id = mysqli_real_escape_string($connect, $row[3]);  
            $style_id = mysqli_real_escape_string($connect, $row[4]);
            $abv = mysqli_real_escape_string($connect, $row[5]);
            $ibu = mysqli_real_escape_string($connect, $row[6]);
            $srm = mysqli_real_escape_string($connect, $row[7]);
            $upc = mysqli_real_escape_string($connect, $row[8]);
            $filepath = mysqli_real_escape_string($connect, $row[9]);
            $describ = mysqli_real_escape_string($connect, $row[10]);
            $add_user = mysqli_real_escape_string($connect, $row[11]);
            $last_mode = mysqli_real_escape_string($connect, $row[12]);
            $values[] = "('$id', '$brewery_id', '$name', '$cat_id', '$style_id', '$abv', '$ibu', '$srm', '$upc', '$filepath', '$describ', '$add_user', '$last_mode')";  
        }
        $firstStackOfData = array();  
        $secoundStackOfData = array();

        for($i = 0; $i<=3000; $i++){
            $firstStackOfData[] = $values[$i];
        }
        $countOfValues = count($values);
        for($i = 3000; $i<=count($values) - 1; $i++){
            $secoundStackOfData[] = $values[$i];
        }

        $sql = "INSERT INTO beers(id, brewery_id, name, cat_id, style_id, abv, ibu, srm, upc, filepath, describ, add_user, last_mode) VALUES ";  
        $sql .= implode(', ', $firstStackOfData);
        mysqli_query($connect, $sql); 

        $sql = "INSERT INTO beers(id, brewery_id, name, cat_id, style_id, abv, ibu, srm, upc, filepath, describ, add_user, last_mode) VALUES ";  
        $sql .= implode(', ', $secoundStackOfData);
        mysqli_query($connect, $sql);
        
        $firstRowQuery = "DELETE FROM beers WHERE id = 0;";  
        mysqli_query($connect, $firstRowQuery); 
    }

    public function importBreweries($file, $connect){

        $values = array();  
        $file = fopen($file, 'r');
        while($row = fgetcsv($file)){
            $id = mysqli_real_escape_string($connect, $row[0]);  
            $name = mysqli_real_escape_string($connect, $row[1]);  
            $address1 = mysqli_real_escape_string($connect, $row[2]);  
            $address2 = mysqli_real_escape_string($connect, $row[3]);  
            $city = mysqli_real_escape_string($connect, $row[4]);
            $state = mysqli_real_escape_string($connect, $row[5]);
            $code = mysqli_real_escape_string($connect, $row[6]);
            $country = mysqli_real_escape_string($connect, $row[7]);
            $phone = mysqli_real_escape_string($connect, $row[8]);
            $website = mysqli_real_escape_string($connect, $row[9]);
            $filepath = mysqli_real_escape_string($connect, $row[10]);
            $descript = mysqli_real_escape_string($connect, $row[11]);
            $add_user = mysqli_real_escape_string($connect, $row[12]);
            $last_mode = mysqli_real_escape_string($connect, $row[13]);
    
            $values[] = "('$id', '$name', '$address1', '$address2', '$city', '$state', '$code', '$country', '$phone', '$website', '$filepath', '$descript', '$add_user', '$last_mode')";  
        }
        $sql = "INSERT INTO breweries(id, name, address1, address2, city, state, code, country, phone, website, filepath, descript, add_user, last_mode) VALUES ";  
        $sql .= implode(', ', $values);  
        mysqli_query($connect, $sql);

        $firstRowQuery = "DELETE FROM breweries WHERE id = 0;";  
        mysqli_query($connect, $firstRowQuery); 
    }

    public function importGeocodes($file, $connect){
        $values = array();  
        $file = fopen($file, 'r');
        while($row = fgetcsv($file)){
            $id = mysqli_real_escape_string($connect, $row[0]);  
            $brewery_id = mysqli_real_escape_string($connect, $row[1]);  
            $latitude = mysqli_real_escape_string($connect, $row[2]);  
            $longitude = mysqli_real_escape_string($connect, $row[3]);  
            $accuracy = mysqli_real_escape_string($connect, $row[4]);  
            $values[] = "('$id', '$brewery_id', '$latitude', '$longitude', '$accuracy')";  
        }
        $sql = "INSERT INTO geocodes(id, brewery_id, latitude, longitude, accuracy) VALUES ";  
        $sql .= implode(', ', $values);  
        mysqli_query($connect, $sql);

        $firstRowQuery = "DELETE FROM geocodes WHERE id = 0;";  
        mysqli_query($connect, $firstRowQuery); 
    }

    public function ImportData($beersFile, $breweriesFile, $categoriesFile, $geocodesFie, $stylesFile, $categories){
        $this->CreateTables($categories);
        $this->importBeers($beersFile, $categories);
        $this->importBreweries($breweriesFile, $categories);
        $this->importCategories($categoriesFile, $categories);
        $this->importGeocodes($geocodesFie, $categories);
        $this->importStyles($stylesFile, $categories);
    }
}
?>