<?php
/**
 * Stores data from a Buoy_Data object into a database.
 *
 * Database schema
 * CREATE TABLE buoys (
 * 	id int(11) NOT NULL auto_increment,
 * 	buoy_id varchar(50) NOT NULL default '',
 * 	wind_dir int(11) NOT NULL default '0',
 * 	wind_spd float(3,2) NOT NULL default '0.00',
 * 	wave_height float(3,2) default '0.00',
 * 	water_temp float(3,2) NOT NULL default '0.00',
 * 	reading_time int(30) NOT NULL default '0',
 * 	insert_stamp int(30) NOT NULL default '0',
 * 	update_stamp int(30) NOT NULL default '0',
 * 	PRIMARY KEY  (id)
 * ) TYPE=MyISAM;
 *
 * @access public
 * @author C.J. Walsh <cj@odewebdesigns.com>
 * @copyright OdeWeb Designs 2005
 * @link http://www.odewebdesigns.com/
 * @package Buoy_Data
 * @version 0.1
 */
class Buoy_Data_DB {
	
	/**
	 * The database object.
	 * @var object
	 */
	var $_db;
	
	/**
	 * The table name to store information
	 * @var string
	 */
	var $_tbl = 'buoys';
	
	/**
	 * Constructor
	 *
	 * @access public
	 * @param object $db The database object. Only utilizes the PEAR DB class.
	 * @return void
	 */
	function Buoy_Data_DB(&$db) {
		$this->_db = $db;
	}	
	
	/**
	 * SQL query method used to insert buoy data.
	 *
	 * @access private
	 * @param string $buoy_id Station ID number
	 * @param integer $wind_dir Wind direction reading
	 * @param float $wind_spd Wind speed reading
	 * @param float $wave_height Wave height reading
	 * @param float $water_temp Water temperature reading
	 * @param integer $time Time of reading
	 * @return boolean Successful insert query
	 */
	function insertBuoyData($buoy_id, $wind_dir, $wind_spd, $wave_height, $water_temp, $time) {
		$sql = "INSERT INTO
					" . $this->_tbl . " (
						buoy_id,
						wind_dir,
						wind_spd,
						wave_height,
						water_temp,
						reading_time,
						insert_stamp
					) VALUES (
						?, ?, ?, ?, ?, ?, ?
					)";
		
		$sth = $this->_db->prepare($sql);
		if(DB::isError($sth)) {
			exit($sth->getMessage() . ": " . __FILE__ . ": " . __LINE__);
		}
		
		$data = array(
			$buoy_id,
			$wind_dir,
			$wind_spd,
			$wave_height,
			$water_temp,
			$time,
			time()
		);
		
		$res = $this->_db->execute($sth, $data);
		if(DB::isError($res)) {
			exit($res->getDebugInfo() . ": " . __FILE__ . ": " . __LINE__);
		}
		
		return true;
	}
	
	/**
	 * Checks to see if current reading is already stored in the database.
	 * 	If not, will store it.
	 *
	 * @access private
	 * @param string $buoy_id Station ID number
	 * @param integer $wind_dir Wind direction reading
	 * @param float $wind_spd Wind speed reading
	 * @param float $wave_height Wave height reading
	 * @param float $water_temp Water temperature reading
	 * @param integer $time Time of reading
	 * @return void
	 */
	function logData($buoy_id, $wind_dir, $wind_spd, $wave_height, $water_temp, $time) {
			if(!$this->isLogged($buoy_id, $time)) {
				$this->insertBuoyData($buoy_id, $wind_dir, $wind_spd, $wave_height, $water_temp, $time);
			}
	}
	
	/**
	 * Checks to see whether a reading has already been logged.
	 *
	 * @access private
	 * @param string $buoy_id Station ID number
	 * @param integer Time of reading
	 * @return boolean True if reading is logged
	 */
	function isLogged($buoy_id, $time) {
		$sql = "SELECT
					id
				FROM
					" . $this->_tbl . "
				WHERE
					buoy_id = '" . $buoy_id . "'
				AND
					reading_time = " . $time;
		
		$res = $this->_db->getOne($sql);
		if(DB::isError($res)) {
			exit($res->getDebugInfo() . ": " . __FILE__ . ": " . __LINE__);
		}
		
		if(isset($res)) {
			return true;
		} else {
			return false;
		}
	}
}
?>