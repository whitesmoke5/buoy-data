<?php
require_once('db/class.buoy_data_db.php');
require_once('inc/class.conversions.php');
require_once('class.buoy_station.php');

/**
 * Establishes an object for reading buoy data stored in flat text files
 * 	on NOAA's National Buoy Data Center website
 *
 * @access public
 * @author C.J. Walsh <cj@odewebdesigns.com>
 * @copyright OdeWeb Designs 2005
 * @link http://www.odewebdesigns.com/
 * @package Buoy_Data
 * @version 0.1
 */

class Buoy_Data {
	
	/**
	 * The buoy's station ID number
	 * @var string
	 */
	var $_buoyId;
	
	/**
	 * URL path of NBDC's data files
	 * @var string
	 */
	var $_dataPath = 'https://www.ndbc.noaa.gov/data/';
	
	/**
	 * The current data file in use by the class.
	 * @var string
	 */
	var $_dataFile;
	
	/**
	 * Parsed data from the data file.
	 * @var array
	 */
	var $_data;
	
	/**
	 * The database object. Used to establish the Buoy_Data_DB object
	 * @var object
	 */
	var $_dbObj;
	
	/**
	 * Store the UTC timestamp
	 * @var integer
	 */
	var $_utc;
	
	/**
	 * UTC offset of local time in seconds
	 * @var integer
	 */
	var $_timeOffset;
	
	/**
	 * Store local timestamp
	 * @var integer
	 */
	var $_localTime;
	
	/**
	 * Establishes the station information object
	 * @var object
	 */
	var $_stationObj;
	
	/**
	 * Constructor
	 *
	 * @access private
	 * @param string $buoy_id Buoy station ID number
	 * @return void
	 */
	function Buoy_Data($buoy_id) {
		$this->setBuoyId($buoy_id);
		
		$this->_stationObj = new Station($buoy_id);
		
		$this->setUTC();
		
		$this->setLocalTime();
	}	
	
	/**
	 * Sets the class var for the station's buoy ID number
	 *
	 * @access private
	 * @param string $id Buiy station ID number
	 */
	function setBuoyId($id) {
		$this->_buoyId = $id;	
	}
	
	/**
	 * Returns the local time and the GMT time relative to
	 * the server's GMT locale
	 *
	 * @access public
	 * @param 
	 * @return array Local time/GMT time in Unix timestamp format ($array['gmt'], $array['local'])
	 */
	function unixGMT($type = 'local', $timestamp = null) {
		$tod = gettimeofday();
		
		if(is_null($timestamp)) {
			$unixgmt['gmt'] = ($tod['sec'] + ($tod['minuteswest'] * 60));
			$unixgmt['local'] = $tod['sec'];
		} else {
			$unixgmt['gmt'] = '';
			$unixgmt['local'] = ($timestamp - ($tod['minuteswest'] * 60));	
		}
		
		/*print("<font color=\"#ffffff\">Unix time: " . $tod['sec'] . "<br>");
		print("Time: " . date("m/d/Y - h:i:s a", $tod['sec']) . "<br>");
		print("GMT Unix time: " . ($tod['sec'] + ($tod['minuteswest'] * 60)) . "<br>");
		print("Greenwich Mean Time: " . date("m/d/Y - h:i:s a", ($tod['sec'] + ($tod['minuteswest']) * 60)) . "<br><br></font>");*/
		
		return $unixgmt[$type];
	}
	
	function setUTC() {
		$tod = gettimeofday();
		
		$this->_timeOffset = $tod['minuteswest'] * 60;
		$this->_utc = $tod['sec'];
	}
	
	function setLocalTime() {
		$tod = gettimeofday();
		
		$this->_localTime = $this->_utc - $this->_timeOffset;
	}
	
	function utc2LocalTime($timestamp) {
		return $timestamp - $this->_timeOffset;
	}
	
	/**
	 * Sets the Buoy_Data_DB object
	 *
	 * @access private
	 * @param object $db The database object used by the site
	 * @return void
	 */
	function setDatabase(&$obj) {
		$this->_dbObj = new Buoy_Data_DB($obj);
	}
}
?>
