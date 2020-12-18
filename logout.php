<?php

    session_start();    

    session_unset();    // Unset The Data
    
    session_destroy();  // Destroy The Session

    header("Location: index.php");

    exit();