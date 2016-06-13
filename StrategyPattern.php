<?php

include_once ('databasehandling.php');

class StrategyUser {
    private $strategy = NULL;
    
    public function __construct($strategy_ind_id) {
        switch ($strategy_ind_id) {
            case "L":
                $this->strategy = new StrategyLog();
                break;
            case "A":
                $this->strategy = new StrategyAdmin();
                break;
        }
    }
    public function showWebContent() {
        return $this->strategy->showContent();
    }
}
interface StrategyInteface {
    public function showContent();
}

class StrategyLog implements StrategyInteface {
    public function showContent() {
        $data  = "<table style='border:1px solid red;";
        $data .= "border-collapse:collapse' border='1px'>";
        $data .= "<thead>";
        $data .= "<tr>";
        $data .= "<th>Location</th>";
        $data .= "<th>Date</th>";
        $data .= "<th>Band</th>";
        $data .= "</tr>";
        $data .= "</thead>";
        $data .= "<tbody>";
        
        $db = $_SESSION['DB'];
        $festdata = $_SESSION['festivaldata'];
        $c = new MongoClient();
        $collection = $c->$db->$festdata;
        $cursor = $collection->find();
        foreach($cursor as $document){
            $data .= "<tr>";
            $data .= "<td>" . $document["location"] . "</td>";
            $data .= "<td>" . $document["date"]."</td>";
            $data .= "<td>" . $document["band"]."</td>";
            $data .= "</tr>";
        }
        $data .= "</tbody>";
        $data .= "</table>";
        echo $data;;
    }
}

class StrategyAdmin implements StrategyInteface {
    public function showContent() {
        $_SESSION['DB'] = 'festival';
        $_SESSION['userlogs'] = 'userlogs';
        $data  = "<table style='border:1px solid red;";
        $data .= "border-collapse:collapse' border='1px'>";
        $data .= "<thead>";
        $data .= "<tr>";
        $data .= "<th>Username</th>";
        $data .= "<th>Status</th>";
        $data .= "</tr>";
        $data .= "</thead>";
        $data .= "<tbody>";
        
        $c = new MongoClient();
        $db = $c->festival;
        $collection = $db->userlogs;
        $cursor = $collection->find();
        
        foreach($cursor as $document){
            $data .= "<tr>";
            $data .= "<td>" . $document["username"] . "</td>";
            $data .= "<td>" . $document["details"]."</td>";
            $data .= "</tr>";
        }
        $data .= "</tbody>";
        $data .= "</table>";
        echo $data;
    }
}

?>