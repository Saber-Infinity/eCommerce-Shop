<nav class="navbar navbar-inverse"> <!-- I made it navbar-inverse instead of navbar-default [black color] -->
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav"
      aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php"><?php echo lang('Homepage') ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav">
          <li><a href="categories.php"> <?php echo lang('CATEGORISE') ?> </a></li>
          <li><a href="items.php"> <?php echo lang('ITEMS') ?> </a></li>
          <li><a href="members.php"> <?php echo lang('MEMBERS') ?> </a></li>
          <li><a href="comments.php"> <?php echo lang('COMMENTS') ?> </a></li>
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <?php echo $_SESSION['Username']; ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../index.php" target="_blank"> Visit Shop </a></li>
            <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>"> Edit Profile </a></li>
            <li><a href="#"> Settings </a></li>
            <li><a href="logout.php"> Logout </a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>