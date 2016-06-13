<?php
include_once ('StrategyPattern.php');
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="main.css">
</head>
<body>
	<header>
            <img src="festivallogo.png" alt="logo" />
    </header>
  	<nav>
    	<ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </nav>
    <section id="intro">
    	<header>
        <h2>Welcome to this Web Page!!</h2>
        </header>
        <p>This web page will operate using the Observer, Strategy and Facade pattern!</p>
        <p> Try Dave & password7 to sign in as admin, Phillip7 and password1 to sign in as user</p>
    </section>
    
<div id="content">
    <div id="mainContent">
    	<section>
        	<article>
                    <?php
                    // Checks if session first exists then checks to see if the paramater
                    // matches
                    if(isset($_SESSION["userloginattempt"]) == 'TRUE') {
                    ?>
                    
                    <div><p>Welcome to the website! You can now see the festival times and dates below</p></div>
                    
                    <?php
                    // If paramater matches display Logged content
                      $strategyUser = new StrategyUser('L');
                      echo($strategyUser->showWebContent());
                      // Check if user is also an admin
                    if(isset($_SESSION["userrights"]) == 'Admin') {
                        ?>
                    <div><p>Hello Admin, here are the recent user logs</p></div>
                    <?php
                    // If admin display adminstative content
                      $strategyAdmin = new StrategyUser('A');
                      echo($strategyAdmin->showWebContent());
                    }
                    }
                    // Otherwise display welcome message
                    else {   
                    ?>
                    <div>
                        <p> Welcome to the festival web site! Here you can check to see the latest festivals times, dates and locations!</p>
                        <p> In order to discover the latest festival times and dates, please sign in or register.
                    <img src="festival.jpg" alt="festival" />       
                    </div>
                    <?php
                    } ?>
                </article>
    	</section>
    </div>
    <aside>
    <section>
        
<!--    If user is signed in display signed in message with username -->
        <?php if (isset($_SESSION["userloginattempt"]) == 'TRUE') : ?>
        <h3>Hello <?php print_r($_SESSION["username"]) ?> <br/> You are signed in</h3>
        <p>Would you like to sign out?</p>
        <form action="index.php" method="POST">
        <input name="submitLogout" id="submitLogout" type="submit" value="Logout" />
        </form>
        <?php else : ?>
<!--        If the user is not signed in display login and registration -->
        <h3>Enter Login Details:</h3>
        <form action="ObserverPattern.php" method="POST">
        Username:<br><input type="text" id="usr_name" name="usr_name" /><br>
        <input type="hidden" name="usrerror"/>
        Password:<br><input type="password" id="usr_password" name="usr_password" /><br>
        <input type="hidden" name="usrpasserror"/>
        <input name="submitForm" id="submitForm" type="submit" value="Login" />
        </form>
        <?php endif ?>
	</section>

<?php if (isset($_SESSION["userloginattempt"]) == 'TRUE') : else :?>
    <section>
        
        <h3>Register here:</h3>
        <form action="ObserverPattern.php" method="POST">
            Username:<br><input type="text" id="usr_namereg" name="usr_namereg" /><br>
            Password:<br><input type="password" id="usr_passreg" name="usr_passreg" /><br>
            <input name="submitReg" id="submitReg" type="submit" value="Register" />
        </form>
        
    </section>
<?php endif ?>
    </aside>        
    <?php
    if(isset($_POST['submitLogout']) and $_POST['submitLogout'] === "Logout")
    {
        session_destroy();
        // Once the user logs out, destroy all sessions
    }
    ?>
</div>
<footer>
        <div>
                <section id="placesToVisit">
                        <header>
                                <h3>Footer</h3>
                        </header>
                        <p>Hope you enjoyed the demonstration of design patterns!.</p>
                </section>
        </div>
</footer>
    
</body>
</html>
