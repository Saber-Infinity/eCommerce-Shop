<?php

    /*
    =========================================
    === Items Page
    =========================================
    */

    ob_start();     // Output Buffering Start

    session_start();

    $pageTitle = "Items";

    if ( isset($_SESSION['Username']) ) {
        
        include "init.php";
        
        $do = isset($_GET['do']) ? $_GET['do'] : "Manage";
        
        if ($do == "Manage") {
            
            // Select All Users Except Admins

            $stmt = $conn -> prepare("SELECT items.*,
                                             categories.Name AS Category_Name,
                                             users.Username AS Member_Name
                                      FROM 
                                             items
                                      INNER JOIN
                                             categories
                                      ON
                                             categories.ID = items.catID
                                      INNER JOIN
                                             users
                                      ON
                                             users.UserID = items.memberID
                                      ORDER BY
                                            ItemID DESC
                                    ");

            $stmt -> execute();

            $items = $stmt -> FETCHALL();
            
            if ( ! empty($items) ) {
                                    
    ?>
                <h1 class="text-center"> Manage Items </h1>

                <div class="container main-container">
                    <div class="table-responsive">
                        <table class="main-table table table-bordered text-center">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Username</td>
                            <td>Category</td>
                            <td>Control</td>
                        </tr>
        <?php
            
                foreach ($items as $item) {

                    echo "<tr>";
                         echo "<td>" . $item['ItemID'] . "</td>";
                         echo "<td>" . $item['Name'] . "</td>";
                         echo "<td>" . $item['Description'] . "</td>";
                         echo "<td>" . $item['Price'] . "</td>";
                         echo "<td>" . $item['Add_Date'] . "</td>";
                         echo "<td>" . $item['Member_Name'] . "</td>";
                         echo "<td>" . $item['Category_Name'] . "</td>";
                         echo "<td>
                         <a href='items.php?do=Edit&itemid=" . $item["ItemID"] ." ' class='btn btn-success'>
                         <i class='fas fa-edit'></i> Edit </a>
                         <a href='items.php?do=Delete&itemid=" .$item["ItemID"] ." ' class='btn btn-danger confirm'>
                         <i class='fas fa-window-close'></i> Delete </a>";
                         if ( $item['Approve'] == 0 ) {

                             echo "<a
                                     href='items.php?do=Approve&itemid= ". $item['ItemID'] ." ' 
                                     class='btn btn-info activate confirm'>
                                     <i class='fas fa-check'></i> Approve </a>";
                         }

                        echo "</td>";
                    echo "</tr>";
                }                       
            ?>
                            </table>
                        </div>
                        <a href='items.php?do=Add' class="btn btn-primary add-item-btn"> <i class="fas fa-plus"></i> New Item </a>
                    </div>

           <?php } else {
                        echo "<div class='container'>";
                            echo "<div class='alert alert-info text-center'><strong>There Are No Items To Show</strong></div>";
                            echo "<a href='items.php?do=Add' class='btn btn-primary'> <i class='fas fa-plus'></i> New Item </a>";
                        echo "</div>";
                    } ?>
      
<?php } elseif ($do == "Add") { ?>
            
            <h1 class="text-center"> Add New Item </h1>

            <div class="container">
                <form class="members form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">

                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label"> Name </label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" name="name" class="form-control" required placeholder="Name Of Item" />
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label"> Description </label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" name="description" class="form-control" required placeholder="Description Of Item" />
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label"> Price </label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" name="price" class="form-control" required placeholder="Price Of Item" />
                        </div>
                    </div>
                    <!-- End Price Field -->

                    <!-- Start Country_Made Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label"> Country </label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" name="country_made" class="form-control" required placeholder="Country Of Made" />
                        </div>
                    </div>
                    <!-- End Country_Made Field -->

                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label"> Status </label>
                        <div class="col-md-8 col-sm-10">
                            <select name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->

                    <!-- Start Member Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label"> Member </label>
                        <div class="col-md-8 col-sm-10">
                            <select name="member">
                                <option value="0">...</option>
                                <?php
                                    
                                    $allUsers = getAllFrom("*", "users", "", "", "UserID");

                                    foreach($allUsers as $user) {

                                        echo "<option value=' ".$user['UserID']." '> ".$user['Username']." </option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Member Field -->

                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label"> Category </label>
                        <div class="col-md-8 col-sm-10">
                            <select name="category">
                                <option value="0">...</option>
                                <?php
                                    
                                    $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID");

                                    foreach($allCats as $cat) {

                                        echo "<option value=' ".$cat['ID']." '> ".$cat['Name']." </option>";
                                        
                                        $childCats = getAllFrom("*", "categories", "WHERE Parent = {$cat['ID']}", "", "ID");
                                        
                                        foreach ($childCats as $child) {
                                            
                                            echo "<option value=' ".$child['ID']." '>--- ".$child['Name']." </option>";
                                        }
                                    }

                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->
                    
                    <!-- Start Tags Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label"> Tags </label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma ',' No Spaces" />
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    
                    <!-- Start Item Image Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label"> Image </label>
                        <div class="col-md-8 col-sm-10">
                            <input type="file" name="img" class="form-control" required />
                        </div>
                    </div>
                    <!-- End Item Image Field -->

                    <!-- Start Submit Button -->
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4 col-sm-10 col-sm-offset-2">
                            <input type="submit" value="Add Item" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End Submit Button -->
                </form>
            </div>
<?php  
        } elseif ($do == "Insert") {
            
    
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        
                        echo "<h1 class='text-center'> Insert Item </h1>"; 

                        echo "<div class='container'>";
                
                    // Upload Variables
                        
                        $avatarName     = $_FILES['img']['name'];
                        
                        $avatarSize     = $_FILES['img']['size'];
                        
                        $avatarTmp      = $_FILES['img']['tmp_name'];
                        
                        $avatarType     = $_FILES['img']['type'];
                        
                    //  List Of Allowed File Types To Upload
                        
                        $allowedExtension = array("jpeg", "jpg", "png", "gif");
                        
                    // Get Avatar Extension
                        
                        $avatarExtension = strtolower( end ( explode(".", $avatarName) ) );     // end() -> Bring Out The Last Element In Array

                    // Get The Variables From The Form

                        $name           = $_POST['name'];

                        $description    = $_POST['description'];

                        $price          = $_POST['price'];

                        $country        = $_POST['country_made'];
                
                        $status         = $_POST['status'];
                
                        $member         = $_POST['member'];
                
                        $category       = $_POST['category'];
                
                        $tags           = $_POST['tags'];


                    // Validate The Form 

                    $formErrors = array() ;

                    if ( empty($name) ) {

                        $formErrors[] = "The Name Of Item Can't Be <strong> Empty </strong>";
                    }

                    if ( empty($description) ) {

                        $formErrors[] = "The Description Of Item Can't Be <strong> Empty </strong>";
                    }

                    if ( empty($price) ) {

                        $formErrors[] = "The Price Of Item Can't Be <strong> Empty </strong>";
                    }

                    if ( empty($country) ) {

                        $formErrors[] = "Country Of Made Can't Be <strong> Empty </strong>";
                    }

                    if ( $status == 0 ) {

                        $formErrors[] = "Not Valid!, Please Choose The Valid <strong> Status </strong>";
                    }
                
                    if ( empty($member) ) {

                            $formErrors[] = "Member Can't Be <strong> Empty </strong>";
                        }

                    if ( empty($category) ) {

                            $formErrors[] = "Category Can't Be <strong> Empty </strong>";
                        }
                    
                    if ( empty($avatarName) ) {
                        
                        $formErrors[] = "Please Upload An <strong> Image </strong>";
                    }
                        
                    if ( ! empty($avatarName) && ! in_array($avatarExtension, $allowedExtension) ) {
                            
                        $formErrors[] = "This Extension Is <strong> Not Allowed </strong>";
                    }
                        
                    if ( $avatarSize > 1048576 ) {  // 1MB = 1 x 1024(KB) x 1024 (Byte)
                        
                        $formErrors[] = "The Size Of Picture Can't Be Larger Than <strong> 1MB </strong>";
                    }

                    foreach ($formErrors as $error) {
                        
                        $theMsg = "<div class='alert alert-danger'>" . $error . "</div>";
                        
                        redirect($theMsg, "Back");
                        
                    }

                        // Check If There Are No Errors, Proceed The Insert Operation

                        if ( empty ( $formErrors ) ) {
                            
                            $img = rand(0, 1000000000) . "_" . $avatarName;
                            
                        // Move The Files From The Temporary Path To (The Path I choose)
                            
                            move_uploaded_file($avatarTmp, "layout\imgs\\" . $img); 

                            // Insert Userinfo. In Database

                            $stmt = $conn -> prepare("INSERT INTO 
                                          items(Name, Description, Price, Country_Made, Image, Status, Add_Date, memberID, catID, tags)
                                          VALUES(:zname, :zdesc, :zprice, :zcountry, :zimg, :zstatus, now(), :zmemid, :zcatid, :ztags)");

                           $stmt -> execute( array (

                                'zname'      => $name,
                                'zdesc'      => $description,
                                'zprice'     => $price,
                                'zcountry'   => $country,
                                'zimg'       => $img,
                                'zstatus'    => $status,
                                'zmemid'     => $member,
                                'zcatid'     => $category,
                                'ztags'      => $tags

                           ));

                           // Echo Success Message

                          $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Inserted </div>";

                          redirect($theMsg, "Back");
                    }

                }  else {

                    echo "<div class='container'>";

                    $errMsg = "<div class='alert alert-danger'> Sorry You Can't Browse This Page Directly! </div>";

                    //redirect($errMsg, "");

                    echo "</div>";
                }

                echo "</div>";  // closed div container

        } elseif ($do == "Edit") {
             
              // Check If Get Request Item Is Numeric & Get Its Integer Value
                    
              $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
            
              $stmt = $conn -> prepare("SELECT * FROM items WHERE ItemID = ?") ;
                
              $stmt -> execute( array($itemid) );
                
              $item = $stmt -> FETCH();
                
              $count = $stmt -> rowCount();
                
              if ($count > 0) { ?>
        
                <h1 class="text-center"> Edit Items </h1>

                <div class="container">
                    <form class="members form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="itemid" value="<?php echo $itemid; ?>" />
                        <!-- Start Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Name </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="name" class="form-control" required placeholder="Name Of Item" value="<?php echo $item['Name']; ?>" />
                            </div>
                        </div>
                        <!-- End Name Field -->
                        
                        <!-- Start Description Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Description </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="description" class="form-control" required placeholder="Description Of Item" value="<?php echo $item['Description']; ?>" />
                            </div>
                        </div>
                        <!-- End Description Field -->
                        
                        <!-- Start Price Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Price </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="price" class="form-control" required placeholder="Price Of Item" value="<?php echo $item['Price']; ?>" />
                            </div>
                        </div>
                        <!-- End Price Field -->
                        
                        <!-- Start Country_Made Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Country </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="country_made" class="form-control" required placeholder="Country Of Made" value="<?php echo $item['Country_Made']; ?>" />
                            </div>
                        </div>
                        <!-- End Country_Made Field -->
                        
                        <!-- Start Status Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Status </label>
                            <div class="col-md-8 col-sm-10">
                                <select name="status">
                                    <option value="1" <?php if( $item['Status'] == 1 ) { echo "selected"; } ?> >New</option>
                                    <option value="2" <?php if( $item['Status'] == 2 ) { echo "selected"; } ?> >Like New</option>
                                    <option value="3" <?php if( $item['Status'] == 3 ) { echo "selected"; } ?> >Used</option>
                                    <option value="4" <?php if( $item['Status'] == 4 ) { echo "selected"; } ?> >Old</option>
                                </select>
                            </div>
                        </div>
                        <!-- End Status Field -->
                        
                        <!-- Start Member Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Member </label>
                            <div class="col-md-8 col-sm-10">
                                <select name="member">
                                    <?php
                               
                                        $allUsers = getAllFrom("*", "users", "", "", "UserID");
            
                                        foreach($allUsers as $user) {
                                            
                                            echo "<option value=' ".$user['UserID']." ' ";
                                            if( $item['memberID'] == $user['UserID'] ) { echo "selected"; }
                                            echo ">" .$user['Username']. "</option>";
                                            
                                        }
                                    
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Member Field -->
                        
                        <!-- Start Categories Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Category </label>
                            <div class="col-md-8 col-sm-10">
                                <select name="category">
                                    <?php
                                        
                                        $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID");
            
                                        foreach($allCats as $cat) {
                                            
                                            echo "<option value=' ".$cat['ID']." ' ";
                                            if ( $item['catID'] == $cat['ID'] ) { echo "selected"; }
                                            echo ">" .$cat['Name']. "</option>";  
                                            
                                            $childCats = getAllFrom("*", "categories", "WHERE Parent = {$cat['ID']}", "", "ID");
                                        
                                            foreach ($childCats as $child) {

                                                echo "<option value=' ".$child['ID']." '>--- ".$child['Name']." </option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Categories Field -->
                        
                        <!-- Start Tags Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Tags </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="tags" class="form-control" value="<?php echo $item['tags']; ?>" placeholder="Separate Tags With Comma ',' No Spaces" />
                            </div>
                        </div>
                        <!-- End Tags Field -->
                        
                        <!-- Start Item Image Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Image </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="file" name="img" class="form-control" required />
                            </div>
                        </div>
                        <!-- End Item Image Field -->
                 
                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4 col-sm-10 col-sm-offset-2">
                                <input type="submit" value="Save Item" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                        <!-- End Submit Button -->
                    </form>
                
        <?php   /* Start Section Comments In Edit Items Page */
            
                    $stmt = $conn -> prepare("SELECT 
                                                    comments.*, users.Username
                                              FROM
                                                    comments
                                              INNER JOIN
                                                    users
                                              ON
                                                    users.UserID = comments.user_id
                                              WHERE
                                                    item_id = ?
                                            ");
                                     
                    $stmt -> execute( array($itemid) );
                                
                    $rows = $stmt -> FETCHALL(); 
                               
                    if ( ! empty($rows) ) {
    ?>
        
                <h1 class="text-center"> Manage [ <?php echo $item['Name']; ?> ] Comments </h1>
                <div class="table-responsive">
                    <table class="main-table table table-bordered text-center">
                    <tr>
                        <td>Comment</td>
                        <td>Member</td> <!-- Member That Added The Comments -->
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
    <?php

                foreach ($rows as $row) {

                    echo "<tr>";
                         echo "<td>" . $row['Comment'] . "</td>";
                         echo "<td>" . $row['Username'] . "</td>";
                         echo "<td>" . $row['commDate'] . "</td>";
                         echo "<td>
                         <a href='comments.php?do=Edit&commid=" .$row["commID"]." ' class='btn btn-success'>
                         <i class='fas fa-edit'></i> Edit </a>
                         <a href='comments.php?do=Delete&commid=" .$row["commID"]." ' class='btn btn-danger confirm'>
                         <i class='fas fa-window-close'></i> Delete </a>";

                         if ($row['Status'] == 0) {

                             echo "<a 
                                     href='comments.php?do=Approve&commid=" .$row["commID"]. " '
                                     class='btn btn-info activate confirm'> 
                                     <i class='fas fa-check'></i> Approve </a>";
                         }

                         echo "</td>";
                    echo "</tr>";
                }
    ?>
                    </table>
                </div>
            <?php } ?>  <!-- Close Bracket Of The Condtion -->
                <!-- End Section Comments In Edit Items Page -->
                
                </div>  <!-- Closed Container -->
                  
     <?php  } else  {
                      echo "<div class='container'>";
                    
                      $theMsg = "<div class='alert alert-danger'> There Is No Such ID </div>";
                  
                      redirect($theMsg, "", 5);
                  
                      echo "</div>";
                  } 
                
        } elseif ($do == "Update") {
            
                echo "<h1 class='text-center'> Update Items </h1>"; 
                
                echo "<div class='container'>";
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    
                    // Upload Variables
                        
                        $avatarName     = $_FILES['img']['name'];
                        
                        $avatarSize     = $_FILES['img']['size'];
                        
                        $avatarTmp      = $_FILES['img']['tmp_name'];
                        
                        $avatarType     = $_FILES['img']['type'];
                        
                    //  List Of Allowed File Types To Upload
                        
                        $allowedExtension = array("jpeg", "jpg", "png", "gif");
                        
                    // Get Avatar Extension
                        
                        $avatarExtension = strtolower( end ( explode(".", $avatarName) ) );     // end() -> Bring Out The Last Element In Array

                    
                    // Get The Variables From The Form
                    
                        $itemid         = $_POST['itemid'];

                        $name           = $_POST['name'];

                        $description    = $_POST['description'];

                        $price          = $_POST['price'];
                        
                        $country        = $_POST['country_made'];
                    
                        $status         = $_POST['status'];
                    
                        $member         = $_POST['member'];
                            
                        $category       = $_POST['category'];
                    
                        $tags           = $_POST['tags'];
                    
                    // Validate The Form 

                    $formErrors = array() ;

                    if ( empty($name) ) {

                        $formErrors[] = "The Name Of Item Can't Be <strong> Empty </strong>";
                    }

                    if ( empty($description) ) {

                        $formErrors[] = "The Description Of Item Can't Be <strong> Empty </strong>";
                    }

                    if ( empty($price) ) {

                        $formErrors[] = "The Price Of Item Can't Be <strong> Empty </strong>";
                    }

                    if ( empty($country) ) {

                        $formErrors[] = "Country Of Made Can't Be <strong> Empty </strong>";
                    }

                    if ( empty($member) ) {

                            $formErrors[] = "Member Can't Be <strong> Empty </strong>";
                        }

                    if ( empty($category) ) {

                            $formErrors[] = "Category Can't Be <strong> Empty </strong>";
                        }
                    
                    if ( empty($avatarName) ) {
                        
                        $formErrors[] = "Please Upload An <strong> Image </strong>";
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
                            
                            $img = rand(0, 1000000000) . "_" . $avatarName;
                            
                        // Move The Files From The Temporary Path To (The Path I choose)
                            
                            move_uploaded_file($avatarTmp, "layout\imgs\\" . $img); 

                        // Update The Database With This Info.

                        $stmt = $conn -> prepare("UPDATE 
                                                        items
                                                  SET
                                                        Name            = ?,
                                                        Description     = ?,
                                                        Price           = ?,
                                                        Country_Made    = ?,
                                                        Image           = ?,
                                                        Status          = ?,
                                                        memberID        = ?,
                                                        catID           = ?,
                                                        tags            =?
                                                        
                                                  WHERE
                                                        ItemID = ?"
                                                );

                        $stmt -> execute( 
                                          array (
                                              $name,
                                              $description,
                                              $price,
                                              $country,
                                              $img,
                                              $status,
                                              $member,
                                              $category,
                                              $tags,
                                              $itemid
                                               )
                                      ) ;

                        // Echo Success Message

                        $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Updated </div>";
                            
                        redirect($theMsg, "Back");   // Back Is Any Name I Supposed

                    }
                    
                }  else {
                    
                    echo "<div class='container'>";
                    
                    $theMsg = "<div class='alert alert-danger'> Sorry You Can't Browse This Page Directly! </div>";
                    
                    redirect($theMsg, "", 5);
                    
                    echo "<div>";
                }
                
                echo "</div>";  // closed div container
            
        } elseif ($do == "Delete") {
            
            echo "<h1 class='text-center'>Delete Item</h1>";
            
            echo "<div class='container'>";
            
                $itemid = isset( $_GET['itemid'] ) && is_numeric( $_GET['itemid'] ) ? intval( $_GET['itemid'] ) : 0;

                // Select All Data Depend On That ID

                $check = chkItems ("ItemID", "items", $itemid);
            
               // Check If ItemID Is Exists In The Database

                if ( $check > 0 ) {

                    $stmt = $conn -> prepare("DELETE FROM items WHERE ItemID = :zid");

                    $stmt -> bindParam(":zid", $itemid);

                    $stmt -> execute();

                    $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Deleted </div>";

                    redirect($theMsg, "Back", 5);

                } else {

                    $theMsg = "<div class='alert alert-danger'>ID <strong> " . $itemid . " </strong> Does Not Exists In Database</div>";

                    redirect($theMsg, "Back", 5);
                }
            
            echo "</div>";
            
        } elseif ($do == "Approve") {
            
            
                 echo "<h1 class='text-center'> Approve Item </h1>";
                
                 echo "<div class='container'>";

                     $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

                     $check = chkItems("itemid", "items", $itemid);

                 if ($check > 0) {  // If There is ItemID In Database Certainly

                        $stmt = $conn -> prepare("UPDATE items SET Approve = 1 WHERE ItemID = ?");

                        $stmt -> execute( array($itemid) );

                        $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Approved </div>";

                        redirect($theMsg, "Back");

                } else {

                        $theMsg = "<div class='alert alert-danger'>Does Not Exists ID <strong> " . $itemid . " </strong> In Database</div>";
 
                        redirect($theMsg, "Back", 5);
                  }

                 echo "</div>";

            
        }
        
        include $tpl . "footer.php";
        
    } else {
        
        header ("Location: index.php");
        
        exit();
    }
    
    ob_end_flush();     //Release The Output

?>