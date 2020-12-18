<?php

/*
================================================
== Manage Comments Page
== You Can Edit | Delete | Approve Comments From Here
================================================
*/

    session_start();

    $pageTitle = "Comments";

    if ( isset ($_SESSION['Username']) ) {
        
            include "init.php";
        
            $do = isset ( $_GET["do"] ) ? $_GET["do"] : "Manage" ;

            // Start Manage Page

            if ( $do == "Manage" )  {
                                     
                    $stmt = $conn -> prepare("SELECT 
                                                    comments.*, items.Name AS `Item Name`, users.Username
                                              FROM
                                                    comments
                                              INNER JOIN
                                                    items
                                              ON
                                                    items.ItemID = comments.item_id
                                              INNER JOIN
                                                    users
                                              ON
                                                    users.UserID = comments.user_id
                                              ORDER BY
                                                    commID DESC
                                            ");
                                     
                    $stmt -> execute();
                                
                    $comments = $stmt -> FETCHALL();
                    
                    if ( ! empty($comments) ) {                                    
    ?>
                        <h1 class="text-center"> Manage Comments </h1>

                        <div class="container">
                            <div class="table-responsive">
                                <table class="main-table table table-bordered text-center">
                                <tr>
                                    <td>ID</td> <!-- Comment ID -->
                                    <td>Comment</td>
                                    <td>Item Name</td>
                                    <td>Member</td> <!-- Member That Added The Comments -->
                                    <td>Added Date</td>
                                    <td>Control</td>
                                </tr>
            <?php
                        foreach ($comments as $comment) {

                            echo "<tr>";
                                 echo "<td>" . $comment['commID'] . "</td>";
                                 echo "<td>" . $comment['Comment'] . "</td>";
                                 echo "<td>" . $comment['Item Name'] . "</td>";
                                 echo "<td>" . $comment['Username'] . "</td>";
                                 echo "<td>" . $comment['commDate'] . "</td>";
                                 echo "<td>
                                 <a href='comments.php?do=Edit&commid=" .$comment["commID"]." ' class='btn btn-success'>
                                 <i class='fas fa-edit'></i> Edit </a>
                                 <a href='comments.php?do=Delete&commid=" .$comment["commID"]." ' class='btn btn-danger confirm'>
                                 <i class='fas fa-window-close'></i> Delete </a>";

                                 if ($comment['Status'] == 0) {

                                     echo "<a 
                                             href='comments.php?do=Approve&commid=" .$comment["commID"]. " '
                                             class='btn btn-info activate confirm'> 
                                             <i class='fas fa-check'></i> Approve </a>";
                                 }

                                 echo "</td>";
                            echo "</tr>";
                        }

                    ?>
                                    </table>
                                </div>
                            </div>

                <?php } else {
                              echo "<div class='container'>";
                                echo "<div class='alert alert-info text-center'><strong>There Are No Comments To Show</strong></div>";
                              echo "</div>";
                    } ?>

    <?php } 
              elseif ( $do == "Edit" )  {   // Edit Page 
                  
                
                      $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;

                      $stmt = $conn -> prepare("SELECT * FROM comments WHERE commID = ? LIMIT 1") ;

                      $stmt -> execute( array($commid) );

                      $row = $stmt -> FETCH();

                      $count = $stmt -> rowCount();

                      if ($stmt -> rowCount() > 0) { ?>

                            <h1 class="text-center"> Edit Comment </h1>

                            <div class="container">
                                <form class="members form-horizontal" action="?do=Update" method="POST">
                                    <input class="hidden" name="commid" value="<?php echo $commid ?>" />
                                    <!-- Start Comment Field -->
                                    <div class="form-group form-group-lg">
                                        <label class="col-md-4 col-sm-2 control-label"> Comment </label>
                                        <div class="col-md-8 col-sm-10">
                                           <textarea class="form-control" name="comment"> <?php echo $row['Comment']; ?> </textarea>
                                        </div>
                                    </div>
                                    <!-- End Comment Field -->

                                    <!-- Start Submit Button -->
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4 col-sm-10 col-sm-offset-2">
                                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                                        </div>
                                    </div>
                                    <!-- End Submit Button -->
                                </form>
                            </div>
     <?php
                  
           } else {
                      echo "<div class='container'>";

                      $theMsg = "<div class='alert alert-danger'>ID <strong> " . $commid . " </strong> Does Not Exists In Database</div>";

                      redirect($theMsg, "", 5);

                      echo "</div>";
                  } 
                
             }  elseif ( $do == "Update" ) {
                
                // Update Page
                
                echo "<h1 class='text-center'> Update Comment </h1>"; 
                
                echo "<div class='container'>";
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    
                        // Get The Variables From The Form
                    
                        $commid = $_POST['commid'];

                        $comment = $_POST['comment'];
                    

                        // Update The Database With This Info.

                        $stmt = $conn -> prepare("UPDATE comments SET Comment = ? WHERE commID = ?");

                        $stmt -> execute( array($comment, $commid) ) ;

                        // Echo Success Message

                        $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Updated </div>";
                            
                        redirect($theMsg, "Back");   // Back Is Any Name I Supposed
                    
              } else {
                    
                    echo "<div class='container'>";
                    
                    $theMsg = "<div class='alert alert-danger'> Sorry You Can't Browse This Page Directly! </div>";
                    
                    redirect($theMsg, "", 5);
                    
                    echo "<div>";
                    
                }
                  
                echo "</div>";  // closed div container
                
            }   elseif ($do == "Delete") {  
                
                    // Delete Member Page
                
                    echo "<h1 class='text-center'> Delete Comment </h1>";

                    echo "<div class='container'>";

                        $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;
                
                        $check = chkItems("commID", "comments", $commid);

                        if ($check > 0) {  // If There is commID In Database Certainly

                            $stmt = $conn -> prepare("DELETE FROM comments WHERE commID = :zcommid");

                            $stmt -> bindParam(':zcommid', $commid);

                            $stmt -> execute();

                            $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Deleted </div>";

                            redirect($theMsg, "Back", 5);

                        } else {

                                $theMsg = "<div class='alert alert-danger'>ID <strong> " . $commid . " </strong> Does Not Exists In Database</div>";

                                redirect($theMsg, "Back", 5);
                          }

                    echo "</div>";
           
                }  elseif ( $do == "Approve" ) {   // Activate Member Page 
                
                         echo "<h1 class='text-center'> Approve Comment </h1>";
                
                         echo "<div class='container'>";
                    
                             $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;
                
                             $check = chkItems("commID", "comments", $commid);

                         if ($check > 0) {  // If There is commID In Database Certainly

                                $stmt = $conn -> prepare("UPDATE comments SET Status = 1 WHERE commID = ?");

                                $stmt -> execute( array($commid) );

                                $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Comment Approved </div>";

                                redirect($theMsg, "Back");

                        } else {

                                $theMsg = "<div class='alert alert-danger'>ID <strong> " . $commid . " </strong> Does Not Exists In Database</div>";

                                redirect($theMsg, "Back");
                          }

                         echo "</div>";
                
                }
            
        
        include $tpl . "footer.php";
        
        } else {

            header("Location: index.php");

            exit();
        }