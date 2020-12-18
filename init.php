<?php

        // Error Reporting

        ini_set('display_error', 'On');

        error_reporting(E_ALL);

        // Import Connection File From Admin's Files

        include "admin/connect.php" ;

        // I Will Depend On $_SESSION['user'] So, I Made It As Variable

        $sessionUser = '';

        if (isset($_SESSION['user'])) {

                $sessionUser = $_SESSION['user'];
        }

        // Routes 

        $tpl = "includes/templates/";         // The Path Of Template Directory

        $css = "layout/css/" ;                // The Path Of Css Directory

        $js = "layout/js/" ;                  // The Path Of Js Directory

        $langs = "includes/languages/" ;       // The Path Of Languages Directory

        $func = "includes/functions/" ; // The Path Of Functions Directory


        // Include The Imporatant Files [The File Of Languages Is First]

        include $func . "functions.php" ;

        include $langs . "english.php" ;

        include $tpl . "header.php" ; 


        
    