<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shareride</title>
     <!-- Link to CSS file -->
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="assets/header.css">
</head>
<body>
    
<header>
    <nav class="navbar navbar-toggleable-md fixed-top navbar-transparent" color-on-scroll="100">
        <div class="container">
        <div class="navbar-translate">
        <button class="navbar-toggler navbar-toggler-right navbar-burger" type="button"
                        data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarTogglerDemo02"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar"></span>
                        <span class="navbar-toggler-bar"></span>
                        <span class="navbar-toggler-bar"></span>
                    </button>
                    
            <a class="navbar-brand">
                <a href="<?php echo "index.php"; ?>">
                    <img src="<?php echo "assets/images/logo.jpg"; ?>" alt="Shareride logo" width="100px" height="100px">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbarToggler">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" rel="tooltip" title="" data-placement="bottom"
                                href="index.php" >Home
                                
                                
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" rel="tooltip" title="" data-placement="bottom"
                                href="about.php" >About Us
                                
                                
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" rel="tooltip" title="" data-placement="bottom"
                                href="contact.php" >Contact Us
                                
                               
                            </a>
                        </li>
  
                        <li><a href="login.php" class="btn btn-danger btn-round">Login</a></li>
                   
                 
                    </ul>
                </div>
                
            </nav>
            
        </div>
      
                    
    </header>
                    </body>
                    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
    <script src="assets/header.js"></script>
    <main>

