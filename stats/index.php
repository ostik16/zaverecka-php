<?php
    session_start();
    include "../boots.php";

    $servername = "sql2.webzdarma.cz";
    $username = "zavereckaxfc4050";
    $password = "oB0@0*7v9CQ4HoMob^1d";
    $dbname = "zavereckaxfc4050";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($conn, "utf8");

    $interpret_count = mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM interpret'));
    $max = 0;
    $interpret_id = 0;
    for($i = 1; $i < $interpret_count + 1; $i++){
        $song_count = mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM skladba WHERE id_interpret='.$i));
        if($song_count > $max){
            $max = $song_count;
            $interpret_id = $i;
        }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
</head>
<body>
    <div class="container mt-5">
        <a href="/" class="btn btn-primary m-3">Home</a>
        <div class="display-1 text-center m-5">Statistics</div>
        <div class="row">
            <div class="col-4 text-center">
                <h1>Counts</h1>
                <div class="col"><b>Songs:</b> <?php echo mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM skladba')); ?></div>
                <div class="col"><b>Albums:</b> <?php echo mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM album')); ?></div>
                <div class="col"><b>Interprets:</b> <?php echo mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM interpret')); ?></div>
                <div class="col"><b>Genres:</b> <?php echo mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM zanr')); ?></div>
            </div>        
            <div class="col-4 text-center">
                <h1>Song duration</h1>
                <div class="col"><b>Minimum:</b> <?php echo mysqli_fetch_assoc(mysqli_query($conn, 'SELECT min(delka) FROM skladba WHERE delka>0'))["min(delka)"]." s"; ?></div>
                <div class="col"><b>Maximum:</b> <?php echo mysqli_fetch_assoc(mysqli_query($conn, 'SELECT max(delka) FROM skladba'))["max(delka)"]." s"; ?></div>
                <div class="col"><b>Average:</b> <?php echo round(mysqli_fetch_assoc(mysqli_query($conn, 'SELECT avg(delka) FROM skladba'))["avg(delka)"])." s"; ?></div>
                <div class="col"><b>All songs duration:</b> <?php echo mysqli_fetch_assoc(mysqli_query($conn, 'SELECT sum(delka) FROM skladba'))["sum(delka)"]." s"; ?></div>
            </div>
            <div class="col-4 text-center">
                <h1>Interpret stats</h1>
                <div class="col"><b>Most songs by one iterpret:</b> <?php echo $max; ?></div>
                <div class="col"><b>Name of the interpret:</b> <?php echo mysqli_fetch_assoc(mysqli_query($conn, 'SELECT * FROM interpret WHERE id_interpret='.$interpret_id))["nazev_interpret"]; ?></div>
                <div class="col"><b>Newest release year:</b> <?php echo mysqli_fetch_assoc(mysqli_query($conn, 'SELECT max(rok_skladba) FROM skladba WHERE id_interpret='.$interpret_id))["max(rok_skladba)"]; ?></div>
                <div class="col"><b>Oldest release year:</b> <?php echo mysqli_fetch_assoc(mysqli_query($conn, 'SELECT min(rok_skladba) FROM skladba WHERE id_interpret='.$interpret_id))["min(rok_skladba)"]; ?></div>
            </div>
        </div>
    </div>
</body>
</html>