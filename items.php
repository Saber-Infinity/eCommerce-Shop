<?php

	ob_start();		// Output Buffering Start

	session_start();

	$pageTitle = 'Show Items';

    include "init.php"; 

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : "";

    // Select All Data Depend On This ID

    $stmt = $conn -> prepare("SELECT 
    								items.*, categories.Name AS `Category Name`, users.Username
							  FROM 
							  		items
							  INNER JOIN 
							  		categories
							  ON
							  	 	items.catID = categories.ID
							  INNER JOIN
							  		users
							  ON 
							  		items.memberID = users.UserID
							  WHERE 
							  		ItemID = ?
							  AND 
							  		Approve = 1
							");

    $stmt -> execute( array($itemid) );

    $count = $stmt -> rowCount();

    if ($count > 0) {

    	$items = $stmt -> FETCH();
   
?>
		<h1 class="text-center"> <?php echo $items['Name']; ?> </h1>
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<?php
					if ( empty($items['Image'])) {
						echo "<img class='img-responsive img-thumbnail' src='admin/layout/imgs/user.jpg' alt='Default' />";
					} else {
						echo "<img class='img-responsive img-thumbnail' src='admin/layout/imgs/".$items['Image']."' alt='Item' />";
					}
					?>
				</div>
				<div class="col-md-9 item-info">
					<h2> <?php echo $items['Name']; ?> </h2>
					<p> <?php echo $items['Description']; ?> </p>
					<ul class="list-unstyled">
						<li> 
							<i class="far fa-calendar-alt fa-fw"></i>
							<span> Added Date </span> : <?php echo $items['Add_Date']; ?>
						</li>
						<li>
							<i class="fas fa-money-bill fa-fw"></i>
							<span> Price </span> : $<?php echo $items['Price']; ?> 
						</li>
						<li> 
							<i class="fas fa-home fa-fw"></i>
							<span> Made In </span> : <?php echo $items['Country_Made']; ?> 
						</li>
						<li>
							<i class="fas fa-tags fa-fw"></i> 
							<span> Category </span> : <a href="categories.php?pageid=<?php echo $items['catID']; ?>"> <?php echo $items['Category Name']; ?> </a> 
						</li>
						<li> 
							<i class="fas fa-user-check fa-fw"></i> 
							<span> Added By </span> : <a href="#"> <?php echo $items['Username']; ?> </a> 
						</li>
						<li class="tags-items">
							<i class="fas fa-user-check fa-fw"></i> 
							<span> Tags </span> : 
							<?php 
								$allTags = explode(",", $items['tags']);

								foreach ($allTags as $tag) {

									$taged = str_replace(" ", "", $tag);

									if ( ! empty($taged) ) {
										echo "<a href='tags.php?name=$taged'>". $taged . "</a>";
									}
								}
							?>
						</li>
					</ul>
				</div>
			</div>
			<hr class="custom-hr">
			<?php if ( isset($_SESSION['user']) ) { ?>
						<!-- Start Add Comment -->
						<div class="row">
							<div class="col-md-offset-3">
								<div class="add-comment">
									<h3>Add Your Comment</h3>
									<form action="<?php echo $_SERVER['PHP_SELF'] . "?itemid=".$items['ItemID']."" ?>" method="POST">
										<textarea class="form-control" name="comment" placeholder="Write A Comment" required></textarea>
										<input type="submit" class="btn btn-primary" value="Add Comment">
									</form>
									<br>
									<?php

										if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

											$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

											$userid = $_SESSION['uid'];

											$itemid = $items['ItemID'];

											if ( ! empty($comment) ) {

												// Put Data In Database
												
												$stmt = $conn -> prepare("INSERT INTO 
																		   comments(Comment, Status, commDate, item_id, user_id)
																		   VALUES(:zcomment, 0, now(), :zitemid, :zuserid)	
																		 ");

												$stmt -> execute( array(

															'zcomment' 	=> $comment,
															'zitemid' 	=> $itemid,
															'zuserid' 	=> $userid
														) );

												if ($stmt) {	// If The Execution Is Done

													echo "<div class='alert alert-success'>Comment Added Successfully</div>";
												}
												
											} else {

												echo "<div class='alert alert-danger'>Please Write A Comment</div>";
											}
										}

									?>
								</div>
							</div>
						</div>
						<!-- End Add Comment -->
			<?php } else {

				echo "<a href='login.php'>Login To Add Comment</a>";

			} ?> 
			<hr class="custom-hr">
			<?php 
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
                              		  AND 
                              		  		Status = 1
                                      ORDER BY
                                            commID DESC
                                    ");
                             
                $stmt -> execute( array($items['ItemID']) );
                            
                $comments = $stmt -> FETCHALL();
			?>

		<?php foreach($comments as $comment) { ?>
				<div class="comment-box">
					<div class="row">
	                	<div class='col-sm-2 text-center'>
	                		<img src="user.jpg" class="img-responsive img-thumbnail img-circle center-block" alt="User">
	                		<?php echo $comment['Username'] ?> 
	                	</div> 
	                	<div class='col-sm-10'> 
	                		<p class="lead"><?php echo $comment['Comment'] ?></p>
	                	</div>
            		</div> 
				</div>
				<hr class="custom-hr">
        <?php } ?>
<?php	
	} else {
		echo "<div class='container'>";
			echo "<div class='alert alert-danger'> ID <strong>" . $itemid . " </strong> Does Not Exists Or This Item Is Waiting For Approval </div>";
		echo "</div>";
	}
	
  	ob_end_flush();		//Release The Output
 ?>