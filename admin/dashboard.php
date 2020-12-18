<?php
    session_start();
   
    if ( isset ($_SESSION['Username']) ) {

            $pageTitle = "Dashboard" ;

            include "init.php";

            /* Start Dashboard Page */ 
        
            $numUsers = 5;      // Number Of Latest Users
        
            $latestUsers = getLatest("*", "users", "UserID",  $numUsers);   // Latest Users Array
        
            $numItems = 5;      // Number Of Latest Items
        
            $latestItems = getLatest("*", "items", "ItemID", $numItems);    // Latest Items Array
        
            $numComments = 5;   // Number Of Latest Comments    
  ?>
            <div class="container text-center home-stats">
                <h1> Dashboard </h1>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat st-members">
                            <i class="fas fa-users"></i>
                            <div class="info">
                                Total Members
                                <span> 
                                    <a href="members.php"> <?php echo countItems("UserID", "users"); ?> </a> 
                                </span>
                            </div>
                        </div>
                    </div>
                    
                     <div class="col-md-3">
                        <div class="stat st-pending">
                           <i class="fas fa-user-plus"></i>
                           <div class="info">
                                 Pending Members
                                <span> <a href="members.php?do=Manage&page=Pending">
                                       <?php echo chkItems("RegStatus", "users", 0) ?> 
                                       </a>
                                </span>
                           </div>
                        </div>
                    </div>
                    
                     <div class="col-md-3">
                        <div class="stat st-items">
                            <i class="fas fa-tag"></i>
                            <div class="info">
                                Total Items
                                <span> 
                                <a href="items.php"> <?php echo countItems("ItemID", "items"); ?> </a> 
                                </span>
                            </div>
                        </div>
                    </div>
                    
                     <div class="col-md-3">
                        <div class="stat st-comments">
                            <i class="fas fa-comments"></i>
                            <div class="info">
                                Total Comments
                                <span> 
                                    <a href="comments.php"> <?php echo countItems("commID", "comments"); ?> </a> 
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container latest">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-users"></i> Latest <?php echo $numUsers; ?> Registered Users
                                <span class="toggle-info pull-right">
                                    <i class="fas fa-plus"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                <?php
                                    if ( ! empty($latestUsers) ) {
                                        
                                        foreach($latestUsers as $user) {
                                            echo "<li>";
                                                echo $user['Username'];
                                                echo "<a href='members.php?do=Edit&userid=" .$user['UserID']. "'>";
                                                     echo "<span class='btn btn-success pull-right'>";
                                                     echo "<i class='fas fa-edit'></i> Edit ";
                                            
                                                if ($user['RegStatus'] == 0) {
                                                            echo "<a 
                                                                    href='members.php?do=Activate&userid=".$user["UserID"]."' 
                                                                    class='btn btn-info confirm pull-right'>
                                                                    <i class='fas fa-check'></i> Activate </a>";
                                                            }
                                                     echo "</span>";
                                                echo "</a>";
                                            echo "</li>";
                                        }
                                    } else {
                                        
                                            echo "<div class='alert alert-info text-center'><strong>There Are No Users To Show</strong></div>";
                                    }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fas fa-tag"></i> Latest <?php echo $numItems; ?> Items
                                <span class="toggle-info pull-right">
                                    <i class="fas fa-plus"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                <?php 
                                    if ( ! empty($latestItems) ) {
                                        
                                        foreach($latestItems as $item) {
                                            echo "<li>";
                                                echo $item['Name'];
                                                echo "<a href='items.php?do=Edit&itemid=" .$item['ItemID']. "'>";
                                                     echo "<span class='btn btn-success pull-right'>";
                                                     echo "<i class='fas fa-edit'></i> Edit ";
                                            
                                                if ($item['Approve'] == 0) {
                                                            echo "<a 
                                                                    href='items.php?do=Approve&itemid=".$item["ItemID"]."' 
                                                                    class='btn btn-info confirm pull-right'>
                                                                    <i class='fas fa-check'></i> Approve </a>";
                                                            }
                                                     echo "</span>";
                                                echo "</a>";
                                            echo "</li>";
                                        }
                                    } else {
                                        
                                            echo "<div class='alert alert-info text-center'><strong>There Are No Items To Show</strong></div>";

                                    }
                                ?>
                                </ul>
                                
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start Latest Comments -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="far fa-comments"></i> Latest <?php echo $numComments; ?> Comments
                                <span class="toggle-info pull-right">
                                    <i class="fas fa-plus"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                 <?php
                                    $stmt = $conn -> prepare("SELECT 
                                                                    comments.*, users.Username
                                                              FROM
                                                                    comments
                                                              INNER JOIN
                                                                    users
                                                              ON
                                                                    users.UserID = comments.user_id
                                                              ORDER BY
                                                                    commID DESC
                                                              LIMIT
                                                                    $numComments
                                                             ");

                                    $stmt -> execute();

                                    $comments = $stmt -> FETCHALL(); 
            
                                    if ( ! empty($comments) ) {
                                        
                                        foreach($comments as $comment) {
                                            echo "<div class='comment-box'>";
                                                echo "<span class='member-name'>
                                                      <a href='members.php?do=Edit&userid= ".$comment['user_id']." '>
                                                     " . $comment['Username'] . "</a></span>";
                                                echo "<p class='member-comment'>" . $comment['Comment'] . "</p>";
                                            echo "</div>";
                                        }
                                    } else {
                                        
                                            echo "<div class='alert alert-info text-center'><strong>There Are No Comments To Show</strong></div>";

                                    }
                                 ?>   
                            </div>
                        </div>
                    </div>
                </div>
            <!-- End Latest Comments -->
            </div>
    <?php
            /* End Dashboard Page */

            include $tpl . "footer.php";
        
    }  else {
        
           // echo "You are not authorized to view this page" ;

            header('Location: index.php');

            exit();
    }