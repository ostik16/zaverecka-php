<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "boots.php" ?>
    <title>Music web</title>
</head>
<body>
    <?php
        if(isset($_GET['filter'])){
            $filter = $_GET['filter'];
        }else{
            $filter="";
        }

    ?>
    <div class="container mt-5">
        <?php
            include "user.php";
            if(isset($_SESSION["logged"])){
                echo '
                    <a href="./create/?type=interpret" class="btn btn-primary m-3">Add interpret</a>
                    <a href="./create/?type=album" class="btn btn-primary m-3">Add album</a>
                    <a href="./create/?type=song" class="btn btn-primary m-3">Add song</a>
                    <a href="./create/?type=genre" class="btn btn-primary m-3">Add genre</a>
                ';
            }
        ?>
        <a href="./stats/" class="btn btn-primary m-3">Statistics</a>
        <div class="display-1 text-center m-5">Seznam skladeb</div>
            <form action="" method="GET">
                <div class="input-group mb-3">
                    <input type="text" name="filter" class="form-control" placeholder="Song or Artist name or Release year" aria-label="Song or Artist name or Release year" aria-describedby="button-addon2" value=<?php echo $filter; ?> >
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" id="button-addon2">Filter</button>
                        <a href="/" class="btn btn-danger" >Clear</a>
                    </div>
                </div>
            </form>
        <?php 

        if(isset($_GET['removed'])){
            echo '<div class="alert alert-warning" role="alert">
            Song have been deleted!
            </div>';
        }
        

        if(isset($_GET['skladba'])){
            echo '<div class="alert alert-danger" role="alert">
            No song was chosen! Please choose one from the list below.
            </div>';
        }

        // to do list
        // 1-pripojit se k mysql serveru
        

        $servername = "sql2.webzdarma.cz";
        $username = "zavereckaxfc4050";
        $password = "oB0@0*7v9CQ4HoMob^1d";
        $dbname = "zavereckaxfc4050";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        mysqli_set_charset($conn, "utf8");

        // 2-poslat vyberovy dotaz a precist odpoved

        $sql = "SELECT skladba.nazev_skladba, skladba.rok_skladba, skladba.url_skladba, skladba.delka, album.nazev_album, interpret.nazev_interpret, skladba.id_skladba
                FROM (skladba INNER JOIN album ON skladba.id_album=album.id_album)
                INNER JOIN interpret ON skladba.id_interpret=interpret.id_interpret
                WHERE nazev_skladba LIKE '%" . $filter . "%' OR nazev_interpret LIKE '%" . $filter . "%' OR rok_skladba LIKE '%" . $filter . "%'
                ORDER BY skladba.nazev_skladba, interpret.nazev_interpret";
        $result = mysqli_query($conn, $sql);

        // 3-vykreslit odpoved jako tabulku na strance

        if (mysqli_num_rows($result) > 0) {

            
            
            // output data of each row
            echo '<table class="table">
            <thead>
              <tr>
                <th scope="col">Song</th>
                <th scope="col">Interpret</th>
                <th scope="col">Release year</th>
                <th scope="col">Length</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>';
            while($row = mysqli_fetch_assoc($result)) {
                $delka = round((($row["delka"] - ($row["delka"] % 60)) / 60 ), 0, PHP_ROUND_HALF_DOWN) . ":";
                if($row["delka"] % 60 < 10){ $delka .= "0".$row["delka"]%60;}else{$delka.=$row["delka"]%60;}
                echo "<tr><td>" . "<a href='"; 
                if(strlen($row['url_skladba']) > 15){
                    echo $row["url_skladba"];
                }else{
                    echo "https://www.youtube.com/results?search_query=" . $row["nazev_skladba"] . "+" . $row["nazev_interpret"];
                }
                echo "' target='_blank'>" . $row["nazev_skladba"] . "</a>" . "</td><td>" . $row["nazev_interpret"] . "</td><td>" . $row["rok_skladba"] . "</td><td>" . $delka  . "</td><td>" . "<a class='btn btn-outline-info' href='./detail/?id=" . $row["id_skladba"] . "'>Detail" . "</a></td></tr>";
            }
            echo'
            </tbody>
            </table>';
        } else {
            echo    '<div class="alert alert-danger" role="alert">
                        No results were found!
                    </div>';
        }

        ?>
    </div>
</body>
</html>