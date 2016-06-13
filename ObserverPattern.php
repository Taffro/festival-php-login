<?php
session_start();
?>
<?php
include_once ('databaseHandling.php');

// No need to declare the interface as SPL handles it in the background
class Login implements SplSubject
{
    private $storage;
    
    function __construct()
    {
        $this->storage = new SplObjectStorage();
    }
    
    function attach (SplObserver $observer)
    {
        $this->storage->attach($observer);
    }
    function detach (SplObserver $observer)
    {
        $this->storage->detach($observer);
    }
    function notify()
    {
        foreach ($this->storage as $obs) {
            $obs->update($this);
        }
    }
    function handleLogin($username, $password)
    {
        $error = array();
        
        if (empty($username)) {
            $error[] = "Empty/Incorrect username";
        }

        if (empty($password)) {
            $error[] = "Empty/Incorrect password";
        }

        // Checks for blanks in the username/password
        if (count($error) == 0) {
            $con = new MongoClient();
            if ($con) {
                $qry = array("username" => $username, "password" => $password);
                $qry2 = array("username" => $username, "rights" => 'admin');
                $db = $_SESSION['DB'];
                $collection = $_SESSION['users'];
                $result = $con->$db->$collection->findOne($qry);

                if ($result) {
                    $this->SetDetails('Succesful log', $username, $password);
                    $_SESSION["userloginattempt"] = "TRUE";
                    $_SESSION["username"] = $username;
                    $result2 = $con->festival->users->findOne($qry2);
                    if ($result2)
                    {
                        $_SESSION["userrights"] = "Admin";
                    }
                }
                else
                {
                    $this->SetDetails('Unsuccesful log', $username, $password);
                }

                $this->notify();
            }
            else {
                die("Cannot connect to MongoDB");
            }
        }
    }
    
    function handleReg($username, $password)
    {
        $error = array();
        
        if (empty($username)) {
            $error[] = "Empty/Incorrect username";
        }

        if (empty($password)) {
            $error[] = "Empty/Incorrect password";
        }

        if (count($error) == 0) {
            $con = new Mongo();
            if ($con) { 
                $this->SetDetails('New user logged', $username, $password);
                $_SESSION["userloginattempt"] = "TRUE";
                $_SESSION["username"] = $username;
                $this->notify();
            }
            else {
                die("Cannot connect to MongoDB");
            }
        }
    }
    
    private function SetDetails ($status, $username, $password) {
        $this->status = $status;
        $this->username = $username;
        $this->password = $password;
    }
    
    function GetStatus () {
        return $this->status;
    }
    function GetUsername () {
        return $this->username;
    }
    function GetPassword () {
        return $this->password;
    }
    
}

abstract class LoginObserver implements SplObserver {
    private $login;
    function __construct(Login $login) {
        $this->login = $login;
        $login->attach($this);
    }
    
    function update(SplSubject $subject) {
        if ($subject === $this->login) {
            $this->doUpdate($subject);
        }
    }
    
    abstract function doUpdate(Login $login);
}

class LogUserToDatabase extends LoginObserver {
    function doUpdate(Login $login) {
        $status = $login->GetStatus();
        $username = $login->GetUsername();
        $doc = array(
           "details"=> $status,
           "username"=> $username,
        );
        $databaseHandler = new Database_Handler();
        $databaseHandler->LogUser($doc);
    }
}

class RegUserToDatabase extends LoginObserver {
    function doUpdate(Login $login) {
        $status = $login->GetStatus();
        $username = $login->GetUsername();
        $password = $login->GetPassword();
        
        $doc1 = array(
            "username"=> $username,
            "password"=> $password,
            "rights"=> 'user',
        );
        $doc2 = array (
            "details"=> $status,
            "username"=> $username,
        );
        
        $databaseHandler = new Database_Handler();
        $databaseHandler->RegUser($doc1, $doc2);
    }
}

class Redirect {
    function RedirectTo($page) {
        header('location: ' . $page, true, 301);
        die();
    }
}

    if (isset($_POST['submitForm']) and $_POST['submitForm'] === "Login")
    {
        $username = ($_POST['usr_name']);
        $password = ($_POST['usr_password']);
        $login = new Login();
        new LogUserToDatabase($login);
        $login->handleLogin($username, $password);
        $redirect = new Redirect();
        $redirect->RedirectTo('index.php');
    }
    
    if (isset($_POST['submitReg']) and $_POST['submitReg'] === "Register")
    {
        $username = ($_POST['usr_namereg']);
        $password = ($_POST['usr_passreg']);
        $login = new Login();
        new RegUserToDatabase($login);
        $login->handleReg($username, $password);
        $redirect = new Redirect();
        $redirect->RedirectTo('index.php');
    }
?>