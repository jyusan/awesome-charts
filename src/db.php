<?php
 require_once 'config/.connection.php'; //MySQL connection info

//TODO move all queries here?
class DB {
	private $SQL_CREATE_SEASON_TABLE = "CREATE TABLE IF NOT EXISTS season_{ID}_stats (rank int(11) NOT NULL, char_id int(11) NOT NULL, PRIMARY KEY(rank)) ";
	private $SQL_GET_CURRENT_SEASON_IDS = "SELECT id,leaderboard_id FROM seasons WHERE start_date <= now() and end_date >= now()";
	private $SQL_GET_LEADERBOARD_ID = "SELECT leaderboard_id FROM seasons WHERE id = {ID}";
    
	private $connection; ///mysqli connectionc
	
	    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar Singleton $instance The *Singleton* instances of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new DB();
        }
        return $instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
		global $mysql_host, $mysql_user, $mysql_password, $mysql_database;
		$this->connection = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);
    }
	
    private function __clone()
    {
    }
    private function __wakeup()
    {
    }
	
	private function getSingleData($result) {
		$data = NULL;
		while($row = $result->fetch_assoc()) {
			$data = $row;
		}
		mysqli_free_result($result);
		return $data;
	}
	
	private function getAllData($result) {
		$data = array();
		while($row = $result->fetch_assoc()) {
			array_push($data,$row);
		}
		mysqli_free_result($result);
		return $data;
	}
	
	public function getCharacterData() {
		$this->connection->query("SET NAMES utf8"); //for skolldir.. :D
		if ($result = $this->connection->query("SELECT * FROM characters")) {			
			return $this->getAllData($result);
		} else {			
			error_log("Couldn't load character data [".$this->connection->error."]");
			return NULL;
		}
	}

	public function getLeaderboardId($seasonid) {
		$sql = str_replace("{ID}",$seasonid,$this->SQL_GET_LEADERBOARD_ID);
		if($result = $this->connection->query($sql)) {			
			return $this->getSingleData($result)["leaderboard_id"];
		} else {
			error_log("Couldn't get leaderboard id for $seasonid [".$this->connection->error."]");
			return NULL;
		}
	}
	
	public function getCurrentSeasonAndLeaderboardId() {
		if($result = $this->connection->query($this->SQL_GET_CURRENT_SEASON_IDS)) {			
			return $this->getSingleData($result);		
		} else {
			error_log("Couldn't get current season ids [".$this->connection->error."]");
			return NULL;
		}
	}
	
	//For historic seasons
	public function saveLeaderboardsDataForSeason($season_id, $data) {
		//Saving old season data to separate table		
		//Create table
		$sql_create = str_replace("{ID}",$season_id,$this->SQL_CREATE_SEASON_TABLE);
		if ($this->connection->query($sql_create) === TRUE) {
			//Table successfully created, can save data
			$tableToSaveTo = "season_".$season_id."_stats";
			return $this->saveLeaderboardsData($tableToSaveTo,$data);
		} else {
			error_log("Couldn't create season leaderboard table ".$this->connection->error);
			return FALSE;
		}
	}
	
	//For current season
	public function saveLeaderboardsDataForCurrent($data) {
		return saveLeaderboardsData("current_leaderboard",$data);
	}
	
	private function saveLeaderboardsData($table_name,$data) {
		$success = TRUE;
		$stmt = $this->connection->prepare("INSERT INTO ".$table_name."(rank,char_id) VALUES(?,?) ON DUPLICATE KEY UPDATE char_id=?;");
		$stmt->bind_param("iii",$r,$c1,$c2);
		
		foreach ( $data as $rank => $char) {
			$r=$rank;
			$c1=$char;
			$c2=$char;
			$success = $success && $stmt->execute();
		}
		
		if ($success) {
			return true;
		} else {
			error_log('Couldn\'t save leaderboards data [' . $this->connection->error . ']');
			return false;
		}
		
		$stmt->close();
	}
}
?>