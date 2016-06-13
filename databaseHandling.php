<?php
include_once ('ObserverPattern.php');

// Due to MongoDB Find() cursor array, the best way to handle the database was found
// through sessions. Changing the content of these sessions would change the connection
// of the whole application.
$_SESSION['DB'] = 'festival';
$_SESSION['userlogs'] = 'userlogs';
$_SESSION['users'] = 'users';
$_SESSION['festivaldata'] = 'festivaldata';

class Database_Handler
{
    
    public function setDBToUsers() {
        $db = $_SESSION['DB'];
        $users = $_SESSION['users'];
        $connection = new MongoClient();
        $collection = $connection->$db->$users;
        return $collection;
    }
    
    public function setDBToUserLogs() {
        $db = $_SESSION['DB'];
        $userlogs = $_SESSION['userlogs'];
        $connection = new MongoClient();
        $collection = $connection->$db->$userlogs;
        return $collection;
    }
    
    public function setDBToFestivalData() {
        $db = $_SESSION['DB'];
        $festdata = $_SESSION['festivaldata'];
        $connection = new MongoClient();
        $collection = $connection->$db->$festdata;
        return $collection;
    }
    
    public function LogUser($doc){
        $connection = new Database_Handler();
        $connection->setDBToUserLogs()->insert($doc);
    }
    
    public function RegUser($doc1, $doc2) {     
        $connection = new Database_Handler();
        $connection->setDBToUsers()->insert($doc1);
        $connection2 = new Database_Handler();
        $connection2->setDBToUserLogs()->insert($doc2);
    }
}
?>
