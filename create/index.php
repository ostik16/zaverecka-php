<?php
    session_start();
    if(!isset($_SESSION["logged"])) header("Location: /");
    include "../boots.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
</head>
<body>
    <div class="container mt-5">
        <a href="/" class="btn btn-primary m-3">Home</a>
        <?php if(isset($_GET["type"]) && $_GET["type"] == "interpret"){}else{ echo '<a href="../create/?type=interpret" class="btn btn-primary m-3" >Add interpret</a>';} ?>
        <?php if(isset($_GET["type"]) && $_GET["type"] == "album"){}else{ echo '<a href="../create/?type=album" class="btn btn-primary m-3">Add album</a>';} ?>
        <?php if(isset($_GET["type"]) && $_GET["type"] == "song"){}else{ echo '<a href="../create/?type=song" class="btn btn-primary m-3">Add song</a>';} ?>
        <?php if(isset($_GET["type"]) && $_GET["type"] == "genre"){}else{ echo '<a href="../create/?type=genre" class="btn btn-primary m-3">Add genre</a>';} ?>
        <div class="display-1 text-center m-5">Create <?php echo $_GET["type"]; ?></div>

    <?php

        $servername = "sql2.webzdarma.cz";
        $username = "zavereckaxfc4050";
        $password = "oB0@0*7v9CQ4HoMob^1d";
        $dbname = "zavereckaxfc4050";
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        mysqli_set_charset($conn, "utf8");

        if(isset($_GET["type"])){
            if($_GET["type"] == "interpret"){
                if(isset($_GET["confirm"])){
                    if($_POST["nazev"] != "" || $_POST["lang"] != ""){
                        mysqli_query($conn, 'INSERT INTO interpret(nazev_interpret, jazyk) VALUES ("'.$_POST["nazev"].'","'.$_POST["lang"].'")');
                        echo '<div class="alert alert-success" role="alert">
                            Interpret "'.$_POST["nazev"].'" added!
                        </div>
                        <a href="/" class="btn btn-primary m-3">Home</a>
                        ';
                    }else{
                        echo '
                            <div class="alert alert-danger" role="alert">
                                Name and/or jazyk cannot be empty
                            </div>';
                    }
                }
                echo '
                    <form action="?type=interpret&confirm=true" method="POST">
                        <input type="hidden" name="type" value="interpret" />
                        <label for="nazev">Name</label>
                        <input type="text" class="form-control" name="nazev" />
                        <label for="lang">Jazyk</label>
                        <input type="text" class="form-control" name="lang" />
                        <button type="submit" class="btn btn-primary mt-3">Add</button>
                    </form>
                ';
            }else if($_GET["type"] == "album"){
                if(isset($_GET["confirm"])){
                    if($_POST["nazev"] != "" || $_POST["rok"] != "" || $_POST["url"] != ""){
                        mysqli_query($conn, 'INSERT INTO album(nazev_album, rok_album, url_album) VALUES ("'.$_POST["nazev"].'","'.$_POST["rok"].'","'.$_POST["url"].'")');
                        echo '<div class="alert alert-success" role="alert">
                            Song "'.$_POST["nazev"].'" added!
                        </div>
                        <a href="/" class="btn btn-primary m-3">Home</a>
                        ';
                    }else{
                        echo '
                            <div class="alert alert-danger" role="alert">
                                Name, rok or url cannot be empty
                            </div>';
                    }
                }
                echo '
                    <form action="?type=album&confirm=true" method="POST">
                        <input type="hidden" name="type" value="album" />
                        <label for="nazev">Name</label>
                        <input type="text" class="form-control" name="nazev" />
                        <label for="rok">Rok</label>
                        <input type="number" class="form-control" name="rok" />
                        <label for="url">Url</label>
                        <input type="text" class="form-control" name="url" />
                        <button type="submit" class="btn btn-primary mt-3">Add</button>
                    </form>
                ';
            }else if($_GET["type"] == "song"){
                if(isset($_GET["confirm"])){
                    if($_POST["nazev"] != "" || $_POST["delka"] != "" || $_POST["rok"] != "" || $_POST["url"] != "" || $_POST["interpret"] != "" || $_POST["album"] != ""){
                        $inter = mysqli_query($conn, 'SELECT * FROM interpret WHERE nazev_interpret LIKE ' . $_POST["interpret"]);
                        $alb = mysqli_query($conn, 'SELECT * FROM album WHERE nazev_album LIKE '. $_POST["album"]);
                        $error = "";
                        if($inter == ""){
                            echo '
                            <div class="alert alert-danger" role="alert">
                                Interpret '.$_POST["interpret"].' not found. If you wish to create one, click <a href="../create/?type=interpret" class="btn">here</a>
                            </div>';
                            $error = "no interpret;";
                        }
                        if($_POST["nazev"] == $_POST["album"] && $inter != ""){
                            mysqli_query($conn, 'INSERT INTO album(nazev_album, rok_album, url_album) VALUES ("'.$_POST["nazev"].'","'.$_POST["rok"].'","'.$_POST["url"].'")');
                            echo '<div class="alert alert-success" role="alert">
                                Album '.$_POST["nazev"].' added!
                            </div>
                            ';
                        }else if($_POST["nazev"] == $_POST["album"]){

                        }else{
                            echo '
                            <div class="alert alert-danger" role="alert">
                                Album '.$_POST["album"].' not found. If you wish to create one, click <a href="../create/?type=album" class="btn">here</a>
                            </div>';
                            $error .= "no album;";
                        }
                        if($error == ""){
                            $interpret = mysqli_fetch_assoc($inter);
                            $album = mysqli_fetch_assoc($alb);
                            mysqli_query($conn, 'INSERT INTO skladba(nazev_skladba, delka, rok_skladba, url_skladba, id_interpret, id_album) VALUES ("'.$_POST["nazev"].'","'.$_POST["delka"].'","'.$_POST["rok"].'","'.$_POST["url"].'","'.$interpret["id_interpret"].'","'.$album["id_album"].'")');
                            echo '<div class="alert alert-success" role="alert">
                                Skladba '.$_POST["nazev"].' added!
                            </div>
                            <a href="/" class="btn btn-primary m-3">Home</a>
                            ';
                        }
                    }else{
                        echo '
                            <div class="alert alert-danger" role="alert">
                                Name, delka, release year, url, interpret or album cannot be empty
                            </div>';
                    }
                }
                echo '
                    <form action="?type=song&confirm=true" method="POST">
                        <input type="hidden" name="type" value="song" />
                        <label for="nazev">Name</label>
                        <input type="text" class="form-control" name="nazev" />
                        <label for="delka">Delka</label>
                        <input type="number" class="form-control" name="delka" />
                        <label for="rok">Release year</label>
                        <input type="number" class="form-control" name="rok" />
                        <label for="url">Url</label>
                        <input type="text" class="form-control" name="url" />
                        <label for="interpret">Interpret</label>
                        <input type="text" class="form-control" name="interpret" />
                        <label for="album">Album</label>
                        <input type="text" class="form-control" name="album" />
                        <button type="submit" class="btn btn-primary mt-3">Add</button>
                    </form>
                ';
            }else if($_GET["type"] == "genre"){
                if(isset($_GET["confirm"])){
                    if($_POST["nazev"] != ""){
                        mysqli_query($conn, 'INSERT INTO zanr(nazev_zanr) VALUES ("'.$_POST["nazev"].'")');
                        echo '<div class="alert alert-success" role="alert">
                            Genre "'.$_POST["nazev"].'" added!
                        </div>
                        <a href="/" class="btn btn-primary m-3">Home</a>
                        ';
                    }else{
                        echo '
                            <div class="alert alert-danger" role="alert">
                                Genre cannot be empty
                            </div>';
                    }
                }
                echo '
                    <form action="?type=genre&confirm=true" method="POST">
                        <input type="hidden" name="type" value="genre" />
                        <label for="nazev">Name</label>
                        <input type="text" class="form-control" name="nazev" />
                        <button type="submit" class="btn btn-primary mt-3">Add</button>
                    </form>
                ';
            }
        }

    ?>

    </div>
</body>
</html>