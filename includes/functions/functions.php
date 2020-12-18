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
    ====    Check If User Is Not Activated Function v1.0
    ====    Function To Check The RegStatus Of The User
    */ 


    function checkUserStatus($user) {

        global $conn;

        $stmt = $conn -> prepare("SELECT 
                                        Username, RegStatus 
                                  FROM 
                                        users 
                                  WHERE 
                                        Username = ? 
                                  AND 
                                        RegStatus = 0 
                                 ");

        $stmt -> execute( array($user) );

        $status = $stmt -> rowCount();

        return $status;

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
    ====    Title Function That Echo The Page Title In Case
    ====    The Page Has The Variable $pageTitle And Echo Default Title For Other Pages
   */

    function getTitle() {
        
        global $pageTitle ;
        
        if ( isset ($pageTitle) ) {
            
            echo $pageTitle ;
            
        } else {
            
            echo "Default" ;
        }
    }