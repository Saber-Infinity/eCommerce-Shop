<?php

    /*
    ====    Get All Function v2.0
    ====    Function To Get All Records From Any Database Table
    */

    function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {
        
        global $conn;

        $getAll = $conn -> prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
        
        $getAll -> execute();
        
        $all = $getAll -> FETCHALL();
        
        return $all;
    }



   /*
    ===     Title Function That Echo The Page Title In Case
    ===     The Page Has The Variable $pageTitle And Echo Default Title For Other Pages
   */


    function getTitle() {
        
        global $pageTitle ;
        
        if ( isset ($pageTitle) ) {
            
            echo $pageTitle ;
            
        } else {
            
            echo "Default" ;
        }
    }


/*
====    Home Redirect Function v1.0
====    This Function Accept Parameters 
====    $errorMsg = echo The Error Message
====    $seconds = Seconds Before Redirecting
        -------------------------------------
====    Home Redirect Function v2.0
====    This Function Accept Parameters 
====    $theMsg = echo The Error Message [ Error | Success | Warning ]
====    $url = The Link You Want To Redirect To
====    $seconds = Seconds Before Redirecting
*/

    function redirect ($theMsg, $url = NULL, $seconds = 3) {
        
        if ($url === NULL) {
            
            $url = "index.php";
            
        }  else {
            
            if ( isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] <> '' ) {  // <> Is Equal To !==
                
                $url = $_SERVER['HTTP_REFERER'];    // هيرجعني ع الصفحه اللي قبل اللي أنا فيها على طول 
                
                $link = "Previous Page";
                
            } else {
                
                $url = "index.php";   
                
                $link = "Homepage";
            }

        }
        
        echo $theMsg ;
        
        echo "<div class='alert alert-info'> You Will Be Redirected To $link After " . $seconds . " Seconds. </div>";
        
        header("REFRESH: $seconds; URL=$url");
        
        exit();
        
    }



/*
====    Check Items Function v1.0
====    Function To Check Items In Database [ This Function Accept Parameters ]
====    $select = The Item To Be Selected [ Example: Select user, Select Items, Select Category, ... ]
====    $from = The Table I Will Select From It [ Example: From users, From items, From categories, ... ]
====    $value = The Value To Be Selected 
*/


    function chkItems ($select, $from, $value) {
        
        global $conn ;
        
        $statement = $conn -> prepare("SELECT $select FROM $from WHERE $select = ?");
        
        $statement -> execute( array($value) );
        
        $count = $statement -> rowCount();
        
        return $count;
        
    }


/*
====    Count Number Of Items Function v1.0
====    Function To Count Number Of Items Rows
====    $item = The Item To Be Counted
====    $table = The Table To Choose From
*/

    function countItems($item, $table) {
        
        global $conn;
        
        $stmt1 = $conn -> prepare("SELECT COUNT($item) FROM $table");
        
        $stmt1 -> execute();
        
        return $stmt1 -> fetchColumn();
        
    }

/*
====    Get Latest Records Function v0.1
====    Function To Get Latest Items From Database [Users, Items, Comments]
====    $select = The Field To Be Selected
====    $table = The Table To Choose From 
====    $limit = The Number Of Records To Get
====    $orderBy = The Col. I Order By It
*/

    function getLatest($select, $table, $orderBy, $limit = 5) {
        
        global $conn;
        
        $getStmt = $conn -> prepare("SELECT $select FROM $table ORDER BY $orderBy DESC LIMIT $limit");
        
        $getStmt -> execute();
        
        $rows = $getStmt -> FETCHALL();
        
        return $rows;
        
    }



