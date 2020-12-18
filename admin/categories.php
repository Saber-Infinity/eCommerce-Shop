<?php

    /*
    =========================================
    === Categories Page
    =========================================
    */

    ob_start();     // Output Buffering Start

    session_start();

    $pageTitle = "Categories";

    if ( isset($_SESSION['Username']) ) {
        
        include "init.php";
        
        $do = isset($_GET['do']) ? $_GET['do'] : "Manage";
        
        if ($do == "Manage") {
            
            $sort = 'ASC';  // Default Sorting [Ascending]
            
            $sort_array = array ('ASC', 'DESC');
            
            if ( isset($_GET['sort']) && in_array( $_GET['sort'], $sort_array ) ) {
                
                $sort = $_GET['sort'];
            }
            
            $stmt = $conn -> prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort");
            
            $stmt -> execute();
            
            $cats = $stmt -> FETCHALL();
            
            if ( ! empty($cats) ) {
    ?>
            <h1 class="text-center">Manage Categories</h1>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fas fa-edit"></i> Manage Categories
                        <div class="option pull-right">
                            <i class="fas fa-sort"></i> Ordering: [
                            <a class="<?php if($sort == 'ASC') { echo 'active'; } ?>" href="?sort=ASC">Asc</a> |
                            <a class="<?php if($sort == 'DESC') { echo 'active'; } ?>" href="?sort=DESC">Desc</a> ] 
                            <i class="fas fa-eye"></i> View: [
                            <span class="active" data-view="full">Full</span> |
                            <span data-view="classic">Classic</span> ]
                        </div>
                    </div>
                        <div class="panel-body categories">
                            <?php
                            foreach($cats as $cat) {
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-buttons'>";
                                        echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] ."' class='btn btn-xs btn-primary'><i class='fas fa-edit'></i> Edit </a>";
                                        echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] ."' class='confirm btn btn-xs btn-danger'><i class='fas fa-window-close'></i> Delete </a>";
                                    echo "</div>";
                                
                                    echo "<h3>" . $cat['Name'] . "</h3>";
                                    echo "<div class='full-view'>";    
                                        echo "<p>"; if ( $cat['Description'] == '' ) { echo "There Is No Description"; } else { echo $cat['Description']; } echo "</p>";
                                        if ( $cat['Visibility'] == 1 ) { echo "<span class='visibility'><i class='fas fa-eye'></i> Visibility Hidden </span>"; }
                                        if ( $cat['Allow_Comment'] == 1 ) { echo "<span class='commenting'><i class='fas fa-window-close'></i> Commenting Disabled </span>"; }
                                        if ( $cat['Allow_Ads'] == 1 ) { echo "<span class='advertising'><i class='fas fa-window-close'></i> Ads Disabled </span>"; }
                                        // Get Child(Sub) Category

                                        $childCats = getAllFrom("*", "categories", "WHERE Parent = {$cat['ID']}", "", "ID", "ASC");

                                        if ( ! empty($childCats) ) {

                                            echo "<h4 class='child-head'> Child Categories </h4>";

                                            echo "<ul class='list-unstyled child-cats'>";

                                            foreach($childCats as $c) {

                                                    echo "<li class='edit-link'>
                                                                <a href='categories.php?do=Edit&catid=".$c['ID']." '>". $c['Name'] . "</a>
                                                                <a href='categories.php?do=Delete&catid=" . $c['ID'] ."' class='confirm show-delete'> Delete </a>
                                                          </li>";
                                            }

                                            echo "</ul>";
                                        }

                                    echo "</div>"; // closed class='full-view'

                                    
                                echo "</div>"; // closed class='cat'
                                echo "<hr>";
                            }
                            ?>
                        </div>
                </div>
                <a href="categories.php?do=Add" class="btn btn-primary add-category"><i class="fas fa-plus"></i> New Category </a>
            </div>
        
            <?php } else {
                        echo "<div class='container'>";
                            echo "<div class='alert alert-info text-center'><strong>There Are No Categories To Show</strong></div>";
                            echo "<a href='categories.php?do=Add' class='btn btn-primary'> <i class='fas fa-plus'></i> New Category </a>";
                        echo "</div>";
                    } ?>
    <?php
            
        } elseif ($do == "Add") { ?>
            
            <h1 class="text-center"> Add New Category </h1>

                <div class="container">
                    <form class="members form-horizontal" action="?do=Insert" method="POST">
            
                        <!-- Start Name Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Name </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="name" class="form-control" autocomplete="off" required placeholder="Name Of The Category" />
                            </div>
                        </div>
                        <!-- End Name Field -->

                        <!-- Start Description Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Description </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="text" name="description" class="form-control"
                                       placeholder="Describe The Category" />
                            </div>
                        </div>
                        <!-- End Description Field -->

                        <!-- Start Ordering Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Ordering </label>
                            <div class="col-md-8 col-sm-10">
                                <input type="number" name="ordering" class="form-control" autocomplete="off"  placeholder="Number To Arrange The Categories" />
                            </div>
                        </div>
                        <!-- End Ordering Field -->

                        <!-- Start Category Type -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Parent ? </label>
                            <div class="col-md-8 col-sm-10">
                                <select name="parent">
                                    <option value="0">None</option>
                                    <?php 
                                        $allCategories = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID", "ASC");

                                        foreach ($allCategories as $cat) {

                                            echo "<option value=' ".$cat['ID']." '>" . $cat['Name'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Category Type -->

                        <!-- Start Visibility Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Visibility </label>
                            <div class="col-md-8 col-sm-10">
                                <div>
                                    <input id="visible-yes" type="radio" name="visibility" value="0" checked />
                                    <label for="visible-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="visible-no" type="radio" name="visibility" value="1" />
                                    <label for="visible-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Visibility Field -->
                        
                        <!-- Start Comments Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Allow Commenting </label>
                            <div class="col-md-8 col-sm-10">
                                <div>
                                    <input id="comment-yes" type="radio" name="commenting" value="0" checked />
                                    <label for="comment-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="comment-no" type="radio" name="commenting" value="1" />
                                    <label for="comment-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Comments Field -->
                        
                        <!-- Start Ads Field -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Allow Ads </label>
                            <div class="col-md-8 col-sm-10">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" />
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Ads Field -->


                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4 col-sm-10 col-sm-offset-2">
                                <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                        <!-- End Submit Button -->
                    </form>
                </div>
            
        <?php
        } elseif ($do == "Insert") {
            
            
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                
                echo "<h1 class='text-center'>Insert Category</h1>";
            
                echo "<div class='container'>";
                
                // Get Variable From The Form
                
                $name = $_POST['name'];
                
                $description = $_POST['description'];
                
                $ordering = $_POST['ordering'];

                $parent = $_POST['parent'];
                
                $visibility = $_POST['visibility'] ;
                
                $commenting = $_POST['commenting'];
                
                $ads = $_POST['ads'];
                
                // Check If Name Of The Category Is Not Empty
                
                if ( !empty($name) ) {
                    
                    // Check If Name Of The Category Is Exists In Database
                    
                    $check = chkItems ("Name", "categories", $name);
                    
                    if ($check > 0) {
                        
                        $theMsg = "<div class='alert alert-danger'> Sorry This Name <strong>" . $name . "
                                   </strong> Is Exists Before </div>";
                        
                        redirect($theMsg, "Back", 5);
                        
                    } else {
                        
                    // Insert Category Info. In Database
                    
                    $stmt = $conn -> prepare("INSERT INTO
                                                     categories(Name, Description, Ordering, Parent, Visibility, Allow_Comment, Allow_Ads)
                                                     
                                                     VALUE(:zname, :zdesc, :zorder, :zparent, :zvis, :zcomm, :zads)");
                    
                    $stmt -> execute( array(
                                     
                                     'zname'    =>      $name, 
                                     'zdesc'    =>      $description, 
                                     'zorder'   =>      $ordering, 
                                     'zparent'   =>      $parent, 
                                     'zvis'     =>      $visibility, 
                                     'zcomm'     =>      $commenting, 
                                     'zads'     =>      $ads
                                ) );
                        
                    // Echo Success Message
                        
                    $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Inserted </div>";
                      
                    redirect($theMsg, "Back");
                }
                    
                } else {
                    
                        $theMsg = "<div class='alert alert-danger'> The Name Can't Be Empty, Please Write The Name Of The Category </div>";
                    
                        redirect($theMsg,"");
                    
                    }
                
        } else {
                
            echo "<div class='container'>";

            $theMsg = "<div class='alert alert-danger'>Sorry You Can't Access This Page Directly</div>";

            redirect($theMsg,"");

            echo "</div>";

            }
            
            echo "</div>";
            
        } elseif ($do == "Edit") { 
            
          // Check If Get Request catid Is Numeric & Get Its Integer Value 

          $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0; 
            
         // Select All Data Depend On That ID
            
          $stmt = $conn -> prepare("SELECT * FROM categories WHERE ID = ? ");

          $stmt -> execute( array ($catid) );

          $cat = $stmt -> FETCH();
        
          $count = $stmt -> rowCount();
            
          // If There Is Such ID, Show The Form
            
          if ($count > 0) { ?>
            
              <h1 class="text-center">Edit Category</h1>

              <div class="container">
                <form class="members form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $cat['ID'] ?>" />
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                    <label class="col-md-4 col-sm-2 control-label">Name</label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" name="name" value="<?php echo $cat['Name']; ?>" class="form-control" required placeholder="Name Of The Category" />
                        </div>
                    </div>
                    <!-- End Name Field -->

                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label">Description</label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" name="description" value="<?php echo $cat['Description'] ?>" class="form-control" placeholder="Describe The Category" />
                        </div>
                    </div>
                    <!-- End Description Field -->

                    <!-- Start Ordering Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label">Ordering</label>
                        <div class="col-md-8 col-sm-10">
                            <input type="number" name="ordering" value="<?php echo $cat['Ordering'] ?>" class="form-control" placeholder="Number To Arrange The Categories" />
                        </div>
                    </div>
                    <!-- End Ordering Field -->

                    <!-- Start Category Type -->
                        <div class="form-group form-group-lg">
                            <label class="col-md-4 col-sm-2 control-label"> Parent ? </label>
                            <div class="col-md-8 col-sm-10">
                                <select name="parent">
                                    <option value="0">None</option>
                                    <?php 
                                        $allCategories = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID", "ASC");

                                        foreach ($allCategories as $c) {

                                            echo "<option value=' ".$c['ID']." ' ";
                                                if ( $cat['Parent'] == $c['ID'] ) { echo "selected"; }
                                            echo ">" . $c['Name'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- End Category Type -->

                    <!-- Start Visibility Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label">Visibility</label>
                        <div class="col-md-8 col-sm-10">
                            <div>
                                <input id="visible-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0) { echo "checked"; } ?> />
                                <label for="visible-yes">Yes</label>
                            </div>
                            <div>
                                <input id="visible-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1) { echo "checked"; } ?> />
                                <label for="visible-no">No</label>
                            </div> 
                        </div>
                    </div>
                    <!-- End Visibility Field -->

                    <!-- Start Commenting Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-md-8 col-sm-10">
                            <div>
                                <input id="comm-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0) { echo "checked"; } ?> />
                                <label for="comm-yes">Yes</label>
                            </div>
                             <div>
                                <input id="comm-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1) { echo "checked"; } ?> />
                                <label for="comm-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Commenting Field -->
                    <!-- Start Ads Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-md-4 col-sm-2 control-label">Allow Ads</label>
                        <div class="col-md-8 col-sm-10">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0) { echo "checked"; } ?> />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1) { echo "checked"; } ?> />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads Field -->

                    <!-- Start Submit Field -->
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4 col-sm-10 col-sm-offset-2">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
              </div>
       <?php     
            
            // If There Is No Such ID, Show The Error Message
            
     } else {
              
              echo "<div class='container'>";

              $theMsg = "<div class='alert alert-danger'> There Is No Such ID </div>";

              redirect($theMsg, "", 5);

              echo "</div>";  

         }
        
        } elseif ($do == "Update") {
            
                echo "<h1 class='text-center'> Update Category </h1>";
                
                echo "<div class='container'>";
            
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                $catid = $_POST['catid'];
                
                $name = $_POST['name'];
                
                $description = $_POST['description'];
                
                $ordering = $_POST['ordering'];

                $parent = $_POST['parent'];
                
                $visibility = $_POST['visibility'];
                
                $commenting = $_POST['commenting'];
                
                $ads = $_POST['ads'];
                
                $stmt = $conn -> prepare("UPDATE 
                                                categories
                                          SET
                                                Name = ?,
                                                Description = ?,
                                                Ordering = ?,
                                                Parent = ?,
                                                Visibility = ?,
                                                Allow_Comment = ?,
                                                Allow_Ads = ?
                                          WHERE
                                                ID = ?");
                
                $stmt -> execute( array($name, $description, $ordering, $parent, $visibility, $commenting, $ads, $catid) );
                
                $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Updated </div>";
                
                redirect($theMsg, "Back");
                
            } else {
                
                echo "<div class='container'>";
                
                $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Directly</div>";
                
                redirect($theMsg, "Back");
                
                echo "</div>";
            }
            
            echo "</div>";  // Closed Div Container
            
        } elseif ($do == "Delete") {
            
            echo "<h1 class='text-center'> Delete Category </h1>";
            
            echo "<div class='container'>";
            
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

                $check = chkItems("ID", "categories", $catid);

                if ($check > 0) {

                    $stmt = $conn -> prepare("DELETE FROM categories WHERE ID = :zid");

                    $stmt -> bindParam("zid", $catid);

                    $stmt -> execute();

                    $theMsg = "<div class='alert alert-success'>" . $stmt -> rowCount() . " Record Deleted </div>";

                    redirect($theMsg, "Back");

                } else {
                    
                    $theMsg = "<div class='alert alert-danger'> There Is No Such ID </div>";

                    redirect($theMsg, "Back");
                }
            
            echo "</div>"; // Closed Div Container
        }
        
        include $tpl . "footer.php";
        
    } else {
        
        header ("Location: index.php");
        
        exit();
    }
    
    ob_end_flush();     //Release The Output