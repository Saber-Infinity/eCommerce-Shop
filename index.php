<?php

	ob_start();		// Output Buffering Start

	session_start();

	$pageTitle = 'Homepage';

    include "init.php"; 
?>
	<div class="container">
		<div class="row">
	<?php 
	        $allItems = getAllFrom("*", "items", "WHERE Approve = 1", "", "ItemID");

			if (! empty($allItems)) {

				foreach ($allItems as $item) {
					
					echo "<div class='col-md-4 col-sm-6 col-xs-12'>";
						echo "<div class='thumbnail item-box'>";
							echo "<span class='price'>$".$item['Price']."</span>";
							if ( empty($item['Image']) ) {
	    						echo "<img class='img-responsive' src='admin/layout/imgs/user.jpg' alt='Default' />";
	    					} else {
	    						echo "<img class='img-responsive' src='admin/layout/imgs/".$item['Image']."' alt='Item' />";
	    					}
							echo "<div class='caption'>";
								echo "<h3>"."<a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a> </h3>";
								$piece1 = substr($item['Description'], 0, 40);
								$theRest = substr($item['Description'], 40);
						    	echo "<p>". $piece1;
						    		echo "<span id='hide'>" . $theRest . "</span>"; 
						    		echo "<span class='show-hide'>...See More</span>";
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
<?php
  	include $tpl . "footer.php";
  	ob_end_flush();		//Release The Output
 ?>