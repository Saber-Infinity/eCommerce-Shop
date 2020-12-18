<?php

/*
================================================
== Manage Members Page
== You Can Add | Edit | Delete Members From Here
================================================
*/

    session_start();

    $pageTitle = "Members";

    if ( isset ($_SESSION['Username']) ) {
        
            include "init.php";
        
            $do = isset ( $_GET["do"] ) ? $_GET["do"] : "Manage" ;

            // Start Manage Page

            if ( $do == "Manage" )  {
                
                    $query = "";
                    
                    if ( isset($_GET['page']) && $_GET['page'] == 'Pending' ) {
                        
                        $query = "AND RegStatus = 0";
                        
                    }
                                     
                    // Select All Users Except Admins
                                     
                    $stmt = $conn -> prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
                                     
                    $stmt -> execute();
                                
                    $rows = $stmt -> FETCHALL();
                
                    if ( ! empty($rows) ) {
                                    
    ?>
                    <h1 class="text-center"> Manage Members </h1>

                    <div class="container">
                        <div class="table-responsive">
                            <table class="main-table manage-image table table-bordered text-center">
                            <tr>
                                <td>#ID</td>
                                <td>Avatar</td>
                                <td>Username</td>
                                <td>Email</td>
                                <td>Full name</td>
                                <td>Registered Date</td>
                                <td>Control</td>
                            </tr>
            <?php
                    foreach ($rows as $row) {

                        echo "<tr>";
                             echo "<td>" . $row['UserID'] . "</td>";
                             echo "<td>";
                                if ( empty($row['Avatar']) ) {
                                    echo "<img src='uploads/avatars/user.jpg' alt='Default' />";
                                } else {
                                    echo "<img src='uploads/avatars/".$row['Avatar']." ' />";
                                }
                                
                             echo "</td>";
                             echo "<td>" . $row['Username'] . "</td>";
                             echo "<td>" . $row['Email'] . "</td>";
                             echo "<td>" . $row['Fullname'] . "</td>";
                             echo "<td>" . $row['Date'] . "</td>";
                             echo "<td>
                             <a href='members.php?do=Edit&userid=" .$row["UserID"]." ' class='btn btn-success'>
                             <i class='fas fa-edit'></i> Edit </a>
                             <a href='members.php?do=Delete&userid=" .$row["UserID"]." ' class='btn btn-danger confirm'>
                             <i class='fas fa-window-close'></i> Delete </a>";

                             if ($row['RegStatus'] == 0) {

                                 echo "<a 
                                         href='members.php?do=Activate&userid=" .$row["UserID"]. " '
                                         class='btn btn-info activate confirm'> 
                                         <i class='fas fa-check'></i> Activate </a>";
                             }

                             echo "</td>";
                        echo "</tr>";
                    }

            ?>
                            </table>
                        </div>
                        <a href='members.php?do=Add' class="btn btn-primary add-member-btn"> <i class="fas fa-plus"></i> New Member </a>
                    </div>

        <?php } else {
                        echo "<div class='container'>";
                            echo "<div class='alert alert-info text-center'><strong>There Are No Members To Show</strong></div>";
                            echo "<a href='members.php?do=Add' class='btn btn-primary'> <i class='fas fa-plus'></i> New Member </a>";
                        echo "</div>";
                 } ?>

    <?php } elseif ($do == "Add") {   // Add New Member ?>
                
                <h1 class="text-center"> Add New Member </h1>

                <div class="container">
                    <form class="members form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
            
                        <!-- Start Username Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Username </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="username" class="form-control" autocomplete="off" required placeholder="Write Username" />
                            </div>
                        </div>
                        <!-- End Username Field -->

                        <!-- Start Password Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Password </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="password" name="password" class="password form-control" autocomplete="new-password" required placeholder="Write Complex Password" />
                                <i class="show-pass fas fa-eye fa-2x"></i>
                            </div>
                        </div>
                        <!-- End Password Field -->

                        <!-- Start Email Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Email </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="email" name="email" class="form-control" autocomplete="off" required placeholder="Write Valid E-mail" />
                            </div>
                        </div>
                        <!-- End Email Field -->

                        <!-- Start Fullname Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Full name </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="fullname" class="form-control" autocomplete="off" required placeholder="Write Full name" />
                            </div>
                        </div>
                        <!-- End Fullname Field -->
                        
                        <!-- Start Profile Image (Avatar) Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> User Avatar </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="file" name="avatar" class="form-control" required />
                            </div>
                        </div>
                        <!-- End Profile Image (Avatar) Field -->

                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4 col-sm-10 col-sm-offset-2">
                                <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                        <!-- End Submit Button -->
                    </form>
                </div>
                

        <?php }  elseif ( $do == "Insert" ) {   
                

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        
                        echo "<h1 class='text-center'> Insert Member </h1>"; 

                        echo "<div class='container'>";
                        
                    // Upload Variables
                        
                        $avatarName     = $_FILES['avatar']['name'];
                        
                        $avatarSize     = $_FILES['avatar']['size'];
                        
                        $avatarTmp      = $_FILES['avatar']['tmp_name'];
                        
                        $avatarType     = $_FILES['avatar']['type'];
                        
                    //  List Of Allowed File Types To Upload
                        
                        $allowedExtension = array("jpeg", "jpg", "png", "gif");
                        
                    // Get Avatar Extension
                        
                        $avtarExtension = strtolower( end ( explode(".", $avatarName) ) );     // end() -> Bring Out The Last Element In Array
                        
                    // Get The Variables From The Form

                        $username = $_POST['username'];

                        $password = $_POST['password'];

                        $hashedPass = sha1($password);

                        $email = $_POST['email'];

                        $fullname = $_POST['fullname'];


                    // Validate The Form 

                    $formErrors = array() ;

                    if ( strlen ($username) < 4 ) {

                        $formErrors[] = "The Username Can't Be less than <strong> 4 characters </strong>";
                    }

                    if ( strlen ($username) > 20 ) {

                        $formErrors[] = "The Username Can't Be more than <strong> 20 characters </strong>";
                    }

                    if ( empty ($username) ) {

                        $formErrors[] = "Please Enter Username, Username Can't Be <strong> Empty </strong>";
                    }

                    if ( empty ($password) ) {

                        $formErrors[] = "Please Enter Password, Password Can't Be <strong> Empty </strong>";
                    }

                    if ( empty ($email) ) {

                        $formErrors[] = "Please Enter Email, Email Can't Be <strong> Empty </strong>";
                    }

                    if ( empty ($fullname) ) {

                        $formErrors[] = "Please Enter Full name, Full name Can't Be <strong> Empty </strong>";
                    }
                        
                    if ( empty($avatarName) ) {
                        
                        $formErrors[] = "Please Upload A <strong> Profile Picture </strong>";
                    }
                        
                    if ( ! empty($avatarName) && ! in_array($avtarExtension, $allowedExtension) ) {
                            
                        $formErrors[] = "This Extension Is <strong> Not Allowed </strong>";
                    }
                        
                    if ( $avatarSize > 1048576 ) {  // 1MB = 1 x 1024(KB) x 1024 (Byte)
                        
                        $formErrors[] = "The Size Of Picture Can't Be Larger Than <strong> 1MB </strong>";
                    }

                    foreach ($formErrors as $error)
                        echo "<div class='alert alert-danger'>" . $error . "</div>";

                        
                        // Check If There Are No Errors, Proceed The Insert Operation

                        if ( empty ( $formErrors ) ) {

                            $avatar = rand(0, 1000000000) . "_" . $avatarName;
                            
                        // Move The Files From The Temporary Path To (The Path I choose)
                            
                            move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);    
                        
                        // Check If Username Is Exists In Database

                        $check = chkItems("Username", "users", $username);

                        if ($check > 0) {   // == 1

                            $theMsg = "<div class='alert alert-danger'> Sorry This Username <strong>" . $username .           " </strong> Is Exists Before </div>";

                            redirect($theMsg, "Back");

                        }  else {

                                // Insert Userinfo. In Database

                                $stmt = $conn -> prepare("INSERT INTO 
                                                          users(Username, Password, Email, Fullname, RegStatus, Date, Avatar)
                                                          VALUES(:zuser, :zpass, :zmail, :zfull, 1, now(), :zavatar) 
                                                        ");

                               $stmt -> execute( array (

                                    'zuser'     => $username,
                                    'zpass'     => $hashedPass,
                                    'zmail'     => $email,
                                    'zfull'     => $fullname,
                                    'zavatar'   => $avatar

                               ));

                               // Echo Success Message

                              $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Inserted </div>";

                              redirect($theMsg, "Back");

                              }
                        }
                
                }  else {

                    echo "<div class='container'>";

                    $errMsg = "<div class='alert alert-danger'> Sorry You Can't Browse This Page Directly! </div>";

                    redirect($errMsg, "");

                    echo "</div>";
                }

                echo "</div>";  // closed div container
                
                
            }  elseif ( $do == "Edit" )  {   // Edit Page 

                
              // echo "Welcome To Edit Page Your id = " . $_GET['userid'] ; //userid from Edit Profile in navbar page 
                
              $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            
              $stmt = $conn -> prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1") ;
                
              $stmt -> execute( array($userid) );
                
              $row = $stmt -> FETCH();
                
              $count = $stmt -> rowCount();
                
              if ($stmt -> rowCount() > 0) { ?>
        
                <h1 class="text-center"> Edit Members </h1>

                <div class="container">
                    <form class="members form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                        <input class="hidden" name="userid" value="<?php echo $userid ?>" />
                        <!-- Start Username Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Username </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="username" value="<?php echo $row['Username'] ?>" class="form-control" autocomplete="off" required />
                            </div>
                        </div>
                        <!-- End Username Field -->

                        <!-- Start Password Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Password </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                                <input type="password" name="newpassword" class="form-control" autocomplete="new-password" />
                            </div>
                        </div>
                        <!-- End Password Field -->

                        <!-- Start Email Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Email </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" autocomplete="off" required />
                            </div>
                        </div>
                        <!-- End Email Field -->

                        <!-- Start Fullname Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Full name </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="fullname" value="<?php echo $row['Fullname'] ?>" class="form-control" autocomplete="off" required />
                            </div>
                        </div>
                        <!-- End Fullname Field -->
                        
                        <!-- Start Profile Image (Avatar) Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> User Avatar </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="file" name="avatar" class="form-control" value="<?php echo $row['Avatar'] ?>" required />
                            </div>
                        </div>
                        <!-- End Profile Image (Avatar) Field -->

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
                  
           } else  {
                      echo "<div class='container'>";
                    
                      $theMsg = "<div class='alert alert-danger'> There Is No Such ID </div>";
                  
                      redirect($theMsg, "", 5);
                  
                      echo "</div>";
                  } 
                
             }  elseif ( $do == "Update" ) {
                
                // Update Page
                
                echo "<h1 class='text-center'> Update Member </h1>"; 
                
                echo "<div class='container'>";
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    
                    //  Upload Variables
                    
                        $avatarName     = $_FILES['avatar']['name'];
                        
                        $avatarSize     = $_FILES['avatar']['size'];
                        
                        $avatarTmp      = $_FILES['avatar']['tmp_name'];
                        
                        $avatarType     = $_FILES['avatar']['type'];
                    
                        $allowedExtension = array("jpeg", "jpg", "png", "gif");
                    
                        $avatarExtension = strtolower( end( explode(".", $avatarName) ) );
                    
                    // Get The Variables From The Form
                    
                        $id = $_POST['userid'];

                        $username = $_POST['username'];

                        $email = $_POST['email'];

                        $fullname = $_POST['fullname'];
                    
                    // Password Trick 
                    
                        $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1( $_POST['newpassword'] );
                
                    // Validate The Form 
                    
                    $formErrors = array() ;
                    
                    if ( strlen ($username) < 4 ) {
                        
                        $formErrors[] = "<div class='alert alert-danger'>The Username Can't be less than
                                         <strong> 4 characters </strong> </div>";
                    }
                    
                    if ( strlen ($username) > 20 ) {
                        
                        $formErrors[] = "<div class='alert alert-danger'>The Username Can't be more than
                                         <strong> 20 characters </strong>";
                    }
                    
                    if ( empty ($username) ) {
                        
                        $formErrors[] = "<div class='alert alert-danger'> Please Enter Username </div>";
                    }
                    
                    if ( empty ($email) ) {
                        
                        $formErrors[] = "<div class='alert alert-danger'> Please Enter Email </div>";
                    }
                    
                    if ( empty ($fullname) ) {
                        
                        $formErrors[] = "<div class='alert alert-danger'> Please Enter Full name </div>";
                    }
                    
                    if ( empty($avatarName) ) {
                        
                        $formErrors[] = "Please Upload A <strong> Profile Picture </strong>";
                    }
                        
                    if ( ! empty($avatarName) && ! in_array($avatarExtension, $allowedExtension) ) {
                            
                        $formErrors[] = "This Extension Is <strong> Not Allowed </strong>";
                    }
                        
                    if ( $avatarSize > 1048576 ) {  // 1MB = 1 x 1024(KB) x 1024 (Byte)
                        
                        $formErrors[] = "The Size Of Picture Can't Be Larger Than <strong> 1MB </strong>";
                    }
                    
                    foreach ($formErrors as $error)
                        echo "<div class='alert alert-danger'>" . $error . "</div>";
                    
                        // Check If There Are No Errors, Proceed The Update Operation
                    
                        if ( empty ( $formErrors ) ) {
                            
                            $stmt2 = $conn -> prepare("SELECT * FROM users WHERE Username = ? AND UserID != ? ");
                            
                            $stmt2 -> execute( array($username, $id) );
                            
                            $count = $stmt2 -> rowCount();
                            
                            if ( $count > 0 ) {
                                
                                $theMsg = "<div class='alert alert-danger'> Sorry This Username <strong>" . $username . " </strong> Is Exists Before </div>";

                                redirect($theMsg, "Back");
                                
                            } else {
                                
                                $avatar = rand(0, 1000000000) . "_" . $avatarName;
                                
                                move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);
                                
                                // Update The Database With This Info.

                                $stmt = $conn -> prepare("UPDATE users SET Username = ?, Password = ?, Email = ?, Fullname = ?, Avatar = ? WHERE UserID = ?");

                                $stmt -> execute( array($username, $pass, $email, $fullname, $avatar, $id) ) ;

                                // Echo Success Message

                                $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Updated </div>";

                                redirect($theMsg, "Back");   // Back Is Any Name I Supposed
                        }
                        
                 }

                }  else {
                    
                    echo "<div class='container'>";
                    
                        $theMsg = "<div class='alert alert-danger'> Sorry You Can't Browse This Page Directly! </div>";

                        redirect($theMsg, "", 5);
                    
                    echo "<div>";
                }
                
                echo "</div>";  // closed div container
                
                
            }   elseif ($do == "Delete") {  
                
                    // Delete Member Page
                
                    echo "<h1 class='text-center'> Delete Member </h1>";

                    echo "<div class='container'>";

                        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                
                        $check = chkItems("userid", "users", $userid);

                        if ($check > 0) {  // If There is UserID In Database Certainly

                            $stmt = $conn -> prepare("DELETE FROM users WHERE UserID = :zuserid");

                            $stmt -> bindParam(':zuserid', $userid);

                            $stmt -> execute();

                            $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Deleted </div>";

                            redirect($theMsg, "Back");

                        } else {

                                $theMsg = "<div class='alert alert-danger'> This ID Is Not Exists </div>"; 

                                redirect($theMsg, "Back", 5);
                          }

                    echo "</div>";
           
                }  elseif ( $do == "Activate" ) {   // Activate Member Page 
                
                         echo "<h1 class='text-center'> Activate Member </h1>";
                
                         echo "<div class='container'>";
                    
                             $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
                
                             $check = chkItems("userid", "users", $userid);

                         if ($check > 0) {  // If There is UserID In Database Certainly

                                $stmt = $conn -> prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

                                $stmt -> execute( array($userid) );

                                $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Activated            </div>";

                                redirect($theMsg, "Back", 5);

                        } else {

                                $theMsg = "<div class='alert alert-danger'> This ID Is Not Exists </div>"; 

                                redirect($theMsg, "Back", 5);
                          }

                         echo "</div>";
                
                }
            
        
        include $tpl . "footer.php";
        
        }  else {

            header("Location: index.php");

            exit();
        }