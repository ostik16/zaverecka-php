<?php
    session_start();
    if(!isset($_SESSION["logged"])) header("Location: /");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../boots.php" ?>
    
    <title>Uprav</title>
</head>
<body>
<div class="container mt-5">
    <a href="/" class="btn btn-primary m-3">Home</a>
    <?php echo '<a href="../detail/?id='.$_GET["id"].'" class="btn btn-primary m-3">Back</a>'; ?>

    <div class="text-center">
    <?php
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

        if(!isset($_GET["id"])){
            header('Location: /');
        }

        if(isset($_POST['remove'])){
            $del = "DELETE FROM skladba WHERE id_skladba=" . $_GET['id'];
            if(mysqli_query($conn, $del)){
              header('Location: /?removed=1');
            }else{
                echo "Error" . mysqli_error($conn);
            }
        }
        if(isset($_POST['nazev'])){
            $interpret = mysqli_fetch_assoc(mysqli_query($conn, '(SELECT `id_interpret` FROM `interpret` WHERE `nazev_interpret` LIKE "' . $_POST["interpret"] . '")'))["id_interpret"];
            $album = mysqli_fetch_assoc(mysqli_query($conn, '(SELECT `id_album` FROM `album` WHERE `nazev_album` LIKE "' . $_POST["album"] . '")'))["id_album"];
            if($interpret == "" || $album == ""){
                echo '<div class="alert alert-danger" role="alert">
                Interpret and/or Album does not exist. You can create them <a class="btn" href="./create/">here</a>
                </div>';
            }else{
                $upd = "UPDATE `skladba` SET 
                `nazev_skladba`= '" . $_POST["nazev"] . "',
                `delka`= '" . $_POST["delka"] . "',
                `rok_skladba`= '" . $_POST["rok"] . "',
                `id_interpret`= '" . $interpret . "',
                `id_album`= '" . $album . "'
                WHERE id_skladba = " . $_GET["id"] ;

                // $g = mysqli_fetch_assoc(mysqli_query($conn, 'SELECT * FROM zanr'));
                $a = mysqli_query($conn, 'SELECT * FROM zanr');
                for($i = 0; $i < mysqli_num_rows(mysqli_query($conn, 'SELECT * FROM zanr')); $i++){
                    $g = mysqli_fetch_assoc($a);
                    if(isset($_POST[$g['nazev_zanr']])){
                        mysqli_query($conn, 'INSERT INTO `zanry_skladeb` (`id_zanr`, `id_skladba`) VALUES ('.$g["id_zanr"].','.$_GET["id"].')');
                    }else{
                        mysqli_query($conn, 'DELETE FROM zanry_skladeb WHERE id_zanr =' . $g["id_zanr"]) . 'AND id_skladba =' . $_GET["id"];
                    }
                }

                if(mysqli_query($conn, $upd)){
                    echo '<div class="alert alert-success" role="alert">
                    Song have been edited successfully.
                  </div>';
                }else{
                    echo "Error" . mysqli_error($conn);
                }
            }



        }
        $sql = "SELECT skladba.nazev_skladba, skladba.rok_skladba, skladba.url_skladba, skladba.delka, album.nazev_album, album.id_album, interpret.nazev_interpret, interpret.id_interpret, skladba.id_skladba
        FROM (skladba INNER JOIN album ON skladba.id_album = album.id_album)
        INNER JOIN interpret ON skladba.id_interpret = interpret.id_interpret
        WHERE skladba.id_skladba=" . $_GET["id"];

        $result = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($result) > 0){   
            $row = mysqli_fetch_assoc($result);
            echo '<form action="../uprav/?id=' . $_GET["id"] . '" method="POST" >
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Song</th>
                    <th scope="col">Release year</th>
                    <th scope="col">Length</th>
                    <th scope="col">Interpret</th>
                    <th scope="col">Album</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>';

            echo "<tr>
                    <td><input type='text' class='form-control' name='nazev' value='" . $row["nazev_skladba"] . "' /></td>
                    <td><input type='number' class='form-control' name='rok' value='" . $row["rok_skladba"] . "' /></td>
                    <td><input type='number' class='form-control' name='delka' value='" . $row["delka"] . "' /></td>
                    <td><input type='text' class='form-control' name='interpret' value='" . $row["nazev_interpret"] . "' /></td>
                    <td><input type='text' class='form-control' name='album' value='" . $row["nazev_album"] . "' /></td>
                </tr><tr>
                ";
                $genres = mysqli_query($conn, "SELECT * FROM zanr");
                if(mysqli_num_rows($genres) > 0){
                    while($loop1 = mysqli_fetch_assoc($genres)){
                        $true = mysqli_query($conn, "SELECT id_zanr FROM (zanry_skladeb INNER JOIN skladba ON skladba.id_skladba=zanry_skladeb.id_skladba) WHERE skladba.id_skladba =". $_GET["id"]);
                        $skip = true;
                        if(mysqli_num_rows($true) > 0){
                            while($loop2 = mysqli_fetch_assoc($true)){
                                if($loop2['id_zanr'] == $loop1['id_zanr']){
                                    echo "<td>".$loop1['nazev_zanr']."<input type='checkbox' class='form-control' name='".$loop1['nazev_zanr']."' checked /></td>";
                                    $skip = false;
                                    break;
                                }
                            }
                        }
                        if($skip) echo "<td>".$loop1['nazev_zanr']."<input type='checkbox' class='form-control' name='".$loop1['nazev_zanr']."' /></td>";
                    }
                }
            echo "</tr>
                    <td class='row'><input class='btn btn-success' type='submit' value='Save' />
                    </form>
                    <form action='../uprav/?id=" . $_GET["id"] . "' method='POST' >
                    <input type='hidden' name='remove' value='1'     />
                    <input class='btn btn-danger' type='submit' value='Remove' /></td>
                </tr>";

            echo "</tbody>
                </table>
                </form>";
        }else{
            die("No data chosen!");
        }


    ?>
    </div>
    </div>
</body>
</html>