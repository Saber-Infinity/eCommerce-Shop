<?php
    session_start();
   
    $noNavbar = "";

    $pageTitle = "Login" ;

    if ( isset ($_SESSION['Username']) ) {
        
        header("Location: dashboard.php") ;     // Redirect To Dashboard Page
    }

    include "init.php"; 

    // Check If The User Is Coming From HTTP Post Request

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashedPass = sha1($password);
        
    // Check If The User[Admin] Exists In Database
        
    $stmt = $conn -> prepare("SELECT
                                    Username, Password, UserID
                              FROM 
                                    users 
                              WHERE 
                                    Username = ? 
                              AND 
                                    Password = ? 
                              AND 
                                    GroupID = 1
                              LIMIT 1
                            ");  
    
    $stmt -> execute( array($username, $hashedPass) );
    
    $row = $stmt -> FETCH();
        
    $count = $stmt -> rowCount();
                
   // If Count > 0 This Means That The Database Contain Record About This Username
        
    if ($count > 0) {
        
        $_SESSION['Username'] = $username ;     // Loged in Session ( Register Session Name )
        
        $_SESSION['ID'] = $row['UserID'] ;      // Register Session ID
        
        header("Location: dashboard.php") ;      // Redirect To dashboard Page
        
        exit() ;
    }
}

?>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off" />
        <input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password" />
        <input class="btn btn-primary btn-block" type="submit" value="Login" />
    </form>

<?php include $tpl . "footer.php"; ?>