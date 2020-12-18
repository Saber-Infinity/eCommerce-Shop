<?php

        include "connect.php" ;

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

        if ( !isset ($noNavbar) ) { include $tpl . "navbar.php" ; }

        
    