<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- First Mobile Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> <?php echo getTitle() ; ?> </title>
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">
    <script src="https://kit.fontawesome.com/26d4a64054.js" crossorigin="anonymous"></script> 
    <link rel="stylesheet" href="<?php echo $css; ?>frontend.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid Serif">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:wght@900&display=swap">
</head>
<body>

    <div class="upper-bar">
        <div class="container">
            <?php 

                if ( isset($_SESSION['user']) ) {   ?>

                    <a href="profile.php"> <img class="my-avatar img-circle img-thumbnail" src="user.jpg" alt="My Pic" /> </a>
                    <div class="btn-group my-info">
                        <span class="btn dropdown-toggle" data-toggle="dropdown">
                            <?php echo $sessionUser; ?> <!-- $sessionUser Is In init.php File -->
                            <span class="caret"></span> <!-- class='caret' Create An Arrow -->
                        </span>
                        <ul class="dropdown-menu">
                            <li><a href="profile.php"> My Profile </a></li>
                            <li><a href="newads.php"> New Item </a></li>
                            <li><a href="profile.php#ads"> My Items </a></li>
                            <li><a href="logout.php"> Logout </a></li>
                        </ul>
                    </div>

            <?php 
                } else {
            ?>
                    <a href="login.php">
                        <span class="pull-right">Login/Signup</span>
                    </a>
                <?php } ?>
        </div>
    </div>

    <nav class="navbar navbar-inverse"> <!-- I made it navbar-inverse instead of navbar-default [black color] -->
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav"
          aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Homepage</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="app-nav">
          <ul class="nav navbar-nav navbar-right">
              <?php 
                    $myCategories = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID", "ASC");

                    foreach($myCategories as $cat) {

                        echo "<li>
                                <a href='categories.php?pageid=".$cat['ID']." '> ". $cat['Name'] ." </a>
                             </li>";
                    }
              ?>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container -->
    </nav>