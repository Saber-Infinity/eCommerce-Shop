<?php
        $pageTitle = 'Categories';
        include "init.php"; 
 ?> 

    <div class="container">
    	<div class="row">
    	<?php 

            if ( isset($_GET['name']) ) {

                $tagName = $_GET['name'];

                echo "<h1 class='text-center'>". $tagName . "</h1>";

                $tagItems = getAllFrom("*", "items", "WHERE tags LIKE '%$tagName%' ", "AND Approve = 1", "ItemID");

        		foreach ($tagItems as $item) {
        			
        			echo "<div class='col-md-4 col-sm-6'>";
        				echo "<div class='thumbnail item-box'>";
        					echo "<span class='price'>".$item['Price']."</span>";
        					if ( empty($item['Image'])) {
                                echo "<img class='img-responsive img-thumbnail' src='admin/layout/imgs/user.jpg' alt='Default' />";
                            } else {
                                echo "<img class='img-responsive img-thumbnail' src='admin/layout/imgs/".$item['Image']."' alt='Item' />";
                            }
        					echo "<div class='caption'>";
        						echo "<h3>"."<a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a> </h3>";
        						$piece = substr($item['Description'], 0, 30);
                                echo "<p>".$piece."<span>...See More</span>"."</p>";
                                echo "<div class='date'>".$item['Add_Date']."</div>";
        					echo "</div>";
        				echo "</div>";
        			echo "</div>";
        		}

            }  else {

                echo "<div class='container'>";
                    echo "<div class='alert alert-danger text-center'><strong>You Must Enter The Tag Name</strong></div>";
                echo "</div>";
            }


    	   
    	?>
    	</div>
    </div>
  		
<?php include $tpl . "footer.php"; ?>