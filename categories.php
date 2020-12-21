<?php
        $pageTitle = 'Categories';
        include "init.php"; 
 ?> 

    <div class="container">
    	<h1 class="text-center"> Show Category Items </h1>
    	<div class="row">
    	<?php 

            $getID = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : "0";

            $allItems = getAllFrom("*", "items", "WHERE catID = $getID", "AND Approve = 1", "ItemID");

    		if (! empty($allItems)) {

    		foreach ($allItems as $item) {
    			
    			echo "<div class='col-md-4 col-sm-6'>";
    				echo "<div class='thumbnail item-box'>";
    					echo "<span class='price'>$".$item['Price']."</span>";
    					if ( empty($item['Image'])) {
                            echo "<img class='img-responsive' src='admin/layout/imgs/user.jpg' alt='Default' />";
                        } else {
                            echo "<img class='img-responsive' src='admin/layout/imgs/".$item['Image']."' alt='Item' />";
                        }
    					echo "<div class='caption'>";
    						echo "<h3>"."<a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a> </h3>";
    						$piece1 = substr($item['Description'], 0, 30);
						$theRest = substr($item['Description'], 30);
						echo "<p>". $piece1;
							echo "<span id='hide'>" . $theRest . "</span>"; 
							echo "<span id='show-hide'>...See More</span>";
						echo "</p>";
                            echo "<div class='date'>".$item['Add_Date']."</div>";
    					echo "</div>";
    				echo "</div>";
    			echo "</div>";
    		}

    	} else {
	    		echo "<div class='container'>";
	    			echo "<div class='alert alert-info text-center'><strong>There Are No Items To Show</strong></div>";
	    		echo "</div>";
    	}
    	?>
    	</div>
    </div>
  		
<?php include $tpl . "footer.php"; ?>
