<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    

    if(isset($_GET["logout"]) && $_GET["logout"]){
        $_SESSION = array();
        session_destroy();
        echo '<div class="alert alert-warning" role="alert">
            Logged out
        </div>';
    } 
    if(isset($_GET["auth"]) && $_GET["auth"]){
        if($_POST["password"] == "."){
            $_SESSION["logged"] = 1;
            echo '<div class="alert alert-success" role="alert">
                Logged in!
            </div>';
        }else{
            echo '<div class="alert alert-danger" role="alert">
                Wrong password!
            </div>';
        }
    }

    if(isset($_SESSION["logged"])){
        echo '
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#login">
                Log out
            </button>
            <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Log out</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    You are currently logged in. Click log out to log out or close this window.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="?logout=true" type="button" class="btn btn-danger">Log out</a>
                    </div>
                </div>
                </div>
            </div>
        ';
    }else{
        echo '
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#login">
                Log in
            </button>
            <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Log in</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="?auth=true" method="POST">
                        <input type="password" name="password" value="." />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Log in</button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        ';
    }
?>