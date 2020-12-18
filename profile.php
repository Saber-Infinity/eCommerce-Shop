<?php

	ob_start();		// Output Buffering Start

	session_start();

	$pageTitle = 'Profile';

    include "init.php"; 

    if (isset($_SESSION['user'])) {


    	$getUser = $conn -> prepare("SELECT * FROM users WHERE Username = ?");

    	$getUser -> execute( array($sessionUser) );		// In init.php File

    	$info = $getUser -> FETCH();

    	$userid = $info['UserID'];

?>
		<h1 class="text-center"> My Profile </h1>

	    <div class="information block">
	    	<div class="container">
	    		<div class="panel panel-primary">
	    			<div class="panel-heading">
	    				My Information
	    			</div>
	    			<div class="panel-body">
	    				<ul class="list-unstyled">
		    				<li>
		    					<i class="fas fa-unlock-alt fa-fw"></i>		<!-- fa-fw [Fixed Width] -->
		    					<span> Name </span> : <?php echo $info['Username']; ?> 
		    				</li>
		    				<li>
		    					<i class="fas fa-envelope-open fa-fw"></i>
		    					<span> Email </span> : <?php echo $info['Email']; ?> 
		    				</li>
		    				<li>
		    					<i class="fas fa-user fa-fw"></i>
		    					<span> Fullname </span> : <?php echo $info['Fullname']; ?> 
		    				</li>
		    				<li>
		    					<i class="far fa-calendar-alt fa-fw"></i>
		    					<span> Registered Date </span> : <?php echo $info['Date']; ?> 
		    				</li>
	    				</ul>

	    			</div>
	    		</div>
	    	</div>
	    </div>

	    <div id="ads" class="Ads block">
	    	<div class="container">
	    		<div class="panel panel-primary">
	    			<div class="panel-heading">
	    				My Items
	    			</div>
	    			<div class="panel-body">
	    				<div class="row">
					    	<?php 
					            $myItems = getAllFrom("*", "items", "WHERE memberID = $userid", "", "ItemID");

					    		if (! empty($myItems)) {

						    		foreach ($myItems as $item) {
						    			echo "<div class='col-md-4 col-sm-6'>";
						    				echo "<div class='thumbnail item-box'>";
						    					if ($item['Approve'] == 0) {
						    						 echo "<span class='approve-status'>Waiting Approval</span>";
						    					}

						    					echo "<span class='price'>$".$item['Price']."</span>";
						    					if ( empty($item['Image'])) {
						    						echo "<img class='img-responsive' src='admin/layout/imgs/user.jpg' alt='Default' />";
						    					} else {
						    						echo "<img class='img-responsive' src='admin/layout/imgs/".$item['Image']."' alt='Item' />";
						    					}
						    					
						    					echo "<div class='caption'>";
						    						echo "<h3><a href='items.php?itemid=".$item['ItemID']."'>".$item['Name']."</a></h3>";
						    						$piece = substr($item['Description'], 0, 30);
						    						echo "<p>".$piece."<span>...See More</span>"."</p>";
						    						echo "<div class='date'>".$item['Add_Date']."</div>";
						    					echo "</div>";
						    				echo "</div>";
						    			echo "</div>";
						    		}

						    	} else {
						    			 echo "<div class='change'>";
							    		 echo "<div class='alert alert-info text-center'>
							    		 	   <strong> There Are No Ads To Show <br /> 
							    		 	   Create <a href='newads.php' class='btn btn-info add-ads-btn'> New Ads </a> </strong></div>";
						    			echo "</div>";
						    		}
					    	?>
    					</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>

	    <div class="comments block">
	    	<div class="container">
	    		<div class="panel panel-primary">
	    			<div class="panel-heading">
	    				Latest Comments
	    			</div>
	    			<div class="panel-body">
	    				<?php

	    					$myComments = getAllFrom("Comment", "comments", "WHERE user_id = $userid", "", "CommID", "ASC");

	    					if (! empty($myComments)) {

						    		foreach ($myComments as $comment) {

										echo "<p>" . $comment['Comment'] . "</p>";
						    		}

					    	} else {
						    		 echo "<div class='alert alert-info text-center'><strong>There Are No Comments To Show</strong></div>";
					    		}
	    				?>
	    			</div>
	    		</div>
	    	</div>
	    </div>

<?php

	} else {

		header("Location: login.php");

		exit();
	}

  	include $tpl . "footer.php";

  	ob_end_flush();		//Release The Output
 ?>