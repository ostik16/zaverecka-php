<?php
    session_start();
    $servername = "sql2.webzdarma.cz";
    $username = "zavereckaxfc4050";
    $password = "oB0@0*7v9CQ4HoMob^1d";
    $dbname = "zavereckaxfc4050";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, "utf8");

    if(isset($_GET["id"])){
        $detail = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT * FROM (skladba INNER JOIN album ON skladba.id_album = album.id_album) INNER JOIN interpret ON skladba.id_interpret = interpret.id_interpret WHERE id_skladba = ' . $_GET["id"]));
    }else{
        header("Location: /?skladba=none");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../boots.php" ?>

    <title>Detail</title>
</head>
<body>
    <div class="container mt-5">
        <a href="/" class="btn btn-primary m-3">Home</a>
        <?php if(isset($_SESSION["logged"])) echo '<a href="../uprav/?id='.$_GET["id"].'" class="btn btn-primary">Uprav</a>' ?>
        <div class="display-1 text-center m-5">Detail skladby <?php echo $detail["nazev_skladba"] ?></div>

        <?php
            if($detail["url_skladba"] != ""){
                $videoid = explode("=",$detail["url_skladba"]);
                if(count($videoid) > 0){
                    echo '
                        <p>Name: '.$detail["nazev_skladba"].'</p>
                        <p>Length: '.$detail["delka"].'</p>
                        <p>Release year: '.$detail["rok_skladba"].'</p>
                        <p>Link: <a href="'.$detail["url_skladba"].'">'.$detail["url_skladba"].'</a></p>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/'.$videoid[1].'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <p>Interpret: '.$detail["nazev_interpret"].'</p>
                        <p>Album: '.$detail["nazev_album"].'</p>
                        <ul>
                    ';
                }

            }else{
                echo '
                    <p>Name: '.$detail["nazev_skladba"].'</p>
                    <p>Length: '.$detail["delka"].'</p>
                    <p>Release year: '.$detail["rok_skladba"].'</p>
                    <p>Interpret: '.$detail["nazev_interpret"].'</p>
                    <p>Album: '.$detail["nazev_album"].'</p>
                    <ul>
                ';
            }
            $genres = mysqli_query($conn, "SELECT * FROM zanr");
            if(mysqli_num_rows($genres) > 0){
                while($loop1 = mysqli_fetch_assoc($genres)){
                    $true = mysqli_query($conn, "SELECT id_zanr FROM (zanry_skladeb INNER JOIN skladba ON skladba.id_skladba=zanry_skladeb.id_skladba) WHERE skladba.id_skladba =". $_GET["id"]);
                    $skip = true;
                    if(mysqli_num_rows($true) > 0){
                        while($loop2 = mysqli_fetch_assoc($true)){
                            if($loop2['id_zanr'] == $loop1['id_zanr']){
                                echo "<li>".$loop1['nazev_zanr']."</li>";
                                $skip = false;
                                break;
                            }
                        }
                    }
                }
            }
            echo '
                </ul>
            ';

        ?>
    
    </div>
    
</body>
</html>