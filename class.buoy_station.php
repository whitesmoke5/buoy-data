<?php
require_once('inc/stations.inc.php');

class Station {
	/**
	 * Station's ID number
	 * @var string
	 */
	var $_stationId;
	
	/**
	 * Array list of all buoy stations
	 * @var array
	 */
	var $_stationList;
	
	/**
	 * Array of station information
	 * @var array
	 */
	var $_stationInfo;
	
	function Station($id) {
		global $stations;
		
		$this->_stationId = $id;
		
		$this->_stationList = $stations;
		
		$this->setStation();
	}	
	
	function setStation() {
		$ary = array();
		
		foreach($this->_stationList as $i => $val) {
			if($i == $this->_stationId) {
				$ary = 	$val;
				break;
			}
		}
		
		if(empty($ary)) {
			exit("Station, " . $this->_stationId . ", does not exist.");
		}
		
		$this->_stationInfo = $ary;
	}
	
	function getLatitude() {
		return $this->_stationInfo['lat'];
	}
	
	function getLongitude() {
		return $this->_stationInfo['long'];	
	}
	
	function getDescription() {
		return $this->_stationInfo['location'];	
	}
	
	function getShoreDistance() {
		return $this->_stationInfo['distance'];
	}
	
	function getShoreDirection() {
		return $this->_stationInfo['dir_from'];	
	}
	
	function getDepth() {
		return $this->_stationInfo['depth'];
	}
}
?>