<?php

	ob_start();		// Output Buffering Start

	session_start();

	$pageTitle = 'Create New Item';

    include "init.php"; 

    if (isset($_SESSION['user'])) {

    	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

    		$formErrors = array();

    		$name 			= filter_var($_POST['name'], FILTER_SANITIZE_STRING);

    		$description 	= filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    		$price 			= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);

    		$country 		= filter_var($_POST['country_made'], FILTER_SANITIZE_STRING);

    		$status 		= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);

    		$category 		= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);

    		$tags 			= filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

    		if ( empty($name) ) {

    			$formErrors[] = "Item Title Can't Be Empty";
    		}

    		if ( strlen($name) < 4 ) {

    			$formErrors[] = "Item Title Must Be At Least 4 Characters";
    		}

    		if ( empty($description) ) {

    			$formErrors[] = "Item Description Can't Be Empty";
    		}

    		if ( strlen($description) < 10 ) {

    			$formErrors[] = "Item Description Must Be At Least 10 Characters";
    		}

    		if ( empty($country) ) {

    			$formErrors[] = "Country Of Made Can't Be Empty";
    		}

    		if ( strlen($country) < 2 ) {

    			$formErrors[] = "Country Of Made Must Be At Least 2 Characters";
    		}

    		if ( ! intval($price) ) {

    			$formErrors[] = "Item Price Must Be Integer";
    		}

    		if ( empty($price) ) {

    			$formErrors[] = "Item Price Can't Be Empty";
    		}

    		if ( empty($status) ) {

    			$formErrors[] = "Item Status Can't Be Empty";
    		}

    		if ( empty($category) ) {

    			$formErrors[] = "Item Category Can't Be Empty";
    		}

    		// Check If There Are No Errors

    		if ( empty($formErrors) ) {

    			// Insert The Data Into The Database

    			$stmt = $conn -> prepare("INSERT INTO 
    												 items(Name, Description, Price, Add_Date, Country_Made, Status, memberID, catID, tags)
    									   VALUES(:zname, :zdesc, :zprice, now(), :zcountry, :zstatus, :zmemid, :zcid, :ztags)
    									");

    			$stmt -> execute( array(

    						'zname' 		=> $name,
    						'zdesc' 		=> $description,
    						'zprice'	 	=> $price,
    						'zcountry' 		=> $country,
    						'zstatus' 		=> $status,
    						'zmemid'		=> $_SESSION['uid'],
    						'zcid' 			=> $category,
    						'ztags'			=> $tags
    			) );

    			// Echo Success Message

    			if ($stmt) {

    				$successMsg = "Item Added Successfully"; 
    			}

    		}
    	}
?>
		<h1 class="text-center"> <?php echo $pageTitle; ?> </h1>

	    <div class="create-ads block">
	    	<div class="container">
	    		<div class="panel panel-primary">
	    			<div class="panel-heading">
	    				<?php echo $pageTitle; ?> 
	    			</div>
	    			<div class="panel-body">
	    				<div class="row">
	    					<div class="col-md-8">
	    						<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	    							<!-- Start Name Field -->
	    							<div class="form-group form-group-lg">
	    								<label class="col-sm-3 control-label"> Name </label>
	    								<div class="col-sm-9">
	    									<input 
	    										  type="text"
	    										  pattern=".{4,}"
	    										  title="The Name Must Be At Least 4 Characters" 
	    										  name="name" 
	    										  class="form-control live" 
	    										  data-class=".live-title" 
	    										  placeholder="Item's Name" 
	    										  autocomplete="off" 
	    										  required 
	    										  />
	    								</div>
	    							</div>
	    							<!-- End Name Field -->

	    							<!-- Start Description Field -->
	    							<div class="form-group form-group-lg"> 
	    								<label class="col-sm-3 control-label"> Description </label>
	    								<div class="col-sm-9">
	    									<input
	    										  pattern=".{10,}"
	    										  title="The Description Must Be At Least 10 Characters" 
	    										  type="text" 
	    										  name="description" 
	    										  class="form-control live" 
	    										  data-class=".live-description" 
	    										  placeholder="Item's Description" 
	    										  autocomplete="off" 
	    										  required 
	    										  />
	    								</div>
	    							</div>
	    							<!-- End Description Field -->

	    							<!-- Start Price Field -->
	    							<div class="form-group form-group-lg">
	    								<label class="col-sm-3 control-label"> Price </label>
	    								<div class="col-sm-9">
	    									<input 
	    										  type="text" 
	    										  name="price" 
	    										  class="form-control live" 
	    										  data-class='.live-price' 
	    										  placeholder="Item's Price" 
	    										  autocomplete="off" 
	    										  required 
	    										  />
	    								</div>
	    							</div>
	    							<!-- End Price Field -->

	    							<!-- Start Country Field -->
	    							<div class="form-group form-group-lg">
	    								<label class="col-sm-3 control-label"> Country </label>
	    								<div class="col-sm-9">
	    									<input 
	    										  type="text" 
	    										  name="country_made" 
	    										  class="form-control" 
	    										  placeholder="Country Of Made" 
	    										  autocomplete="off" 
	    										  required 
	    										  />
	    								</div>
	    							</div>
	    							<!-- End Country Field -->

	    							<!-- Start Status Field -->
	    							<div class="form-group form-group-lg">
	    								<label class="col-sm-3 control-label"> Status </label>
	    								<div class="col-sm-9">
	    									<select name="status" required>
	    										<option value="">...</option>
	    										<option value="1">New</option>
	    										<option value="2">Like New</option>
	    										<option value="3">Used</option>
	    										<option value="4">Old</option>
	    									</select>
	    								</div>
	    							</div>
	    							<!-- End Status Field -->

	    							<!-- Start Category Field -->
	    							<div class="form-group form-group-lg">
	    								<label class="col-sm-3 control-label"> Category </label>
	    								<div class="col-sm-9">
	    									<select name="category" required>
	    										<option value="">...</option>
	    										<?php
	    										
	    											$cats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID");

				                                    foreach($cats as $cat) {

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
	    							<!-- End Category Field -->

	    							<!-- Start Tags Field -->
			                        <div class="form-group form-group-lg">
			                            <label class="col-sm-3 control-label"> Tags </label>
			                            <div class="col-sm-9">
			                                <input type="text" name="tags" class="form-control" placeholder="Separate Tags With Comma ',' " />
			                            </div>
			                        </div>
			                        <!-- End Tags Field -->

	    							<!-- Start Submit Button -->
	    							<div class="form-group">
	    								<div class="col-sm-9 col-sm-offset-3">
	    									<input type="submit" value="Add Item" class="btn btn-primary">
	    								</div>
	    							</div>
	    							<!-- End Submit Button -->
	    						</form>
	    					</div>
	    					<div class="col-md-4">
	    						<div class='item-box live-preview'>
			    					 <span class='price'> 
			    					 	$<span class="live-price">0</span>  
			    					 </span> 
			    					 <img class='img-responsive' src='item.jpg' alt='Item' />
		    					 	 <div class='caption'>
			    						<h3 class='text-center live-title'> Title </h3>
			    						<p class='text-center live-description'> Description </p>
		    						 </div>
    							</div>
	    					</div>
	    				</div>
	    			</div>	<!-- End panel-body -->
	    			<!-- Start Looping Through $formErrors -->
	    			<?php
	    				if ( ! empty($formErrors) ) {

	    					foreach ($formErrors as $error) {

	    						echo "<div class='alert alert-danger text-center'>" . $error . "</div>";
	    					}
	    				}

	    				if ( isset($successMsg) ) {

	    					echo "<div class='alert alert-success text-center'><strong>" . $successMsg . "</strong></div>";
	    				}
	    			?>
	    			<!-- End Looping Through $formErrors -->
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