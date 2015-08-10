<?php
require_once('class.buoy_data.php');

/**
 * Retrieves hourly data from the National Buoy Data Center
 *
 * @access public
 * @author C.J. Walsh <cj@odewebdesigns.com>
 * @copyright OdeWeb Designs 2005
 * @link http://www.odewebdesigns.com/
 * @package Buoy_Data
 * @version 0.1
 */

class Buoy_Hourly extends Buoy_Data {
	
	/**
	 * The hour to start from.
	 * @var integer
	 */
	var $_hour_from;
	
	/**
	 * The hour to stop at.
	 * @var integer
	 */
	var $_hour_to;
	
	/**
	 * Files to be used
	 * @var array
	 */
	var $_files;
	
	/**
	 * Actual hour-by-hour readings
	 * @var array
	 */
	var $_readings;
	
	/**
	 * Constructor
	 *
	 * @access public
	 * @param string $buoy_id Buoy station ID number
	 * @param integer $hr_from Beginning hour in 24 hour time
	 * @param integer $hr_to Ending hour in 24 hour in 24 hour time
	 * @return void
	 */
	function Buoy_Hourly($buoy_id, $hr_from = '0', $hr_to = '23') {
		$this->Buoy_Data($buoy_id);
		
		$this->setTimeRange($hr_from, $hr_to);
		
		$this->setFiles();
		
		$this->setData();
		
		$this->setReadings();
	}
	
	/**
	 * Sets the time range of hours to read for the day
	 *
	 * @access private
	 * @param integer $from Beginning hour
	 * @param integer $to Ending hour
	 * @return void
	 */
	function setTimeRange($from, $to) {
		if($from >= $to) {
			exit("FROM must be less than and not equal to TO.");
		} else {
			$this->_hour_from = ($from - 1);
			$this->_hour_to = ($to - 1);
		}
	}
	
	/**
	 * Sets all the files for each hour in the range
	 *
	 * @access private
	 * @return void
	 */
	function setFiles() {
		for($i=$this->_hour_from; $i<=$this->_hour_to; $i++) {
			$hour = ($i < 10) ? "0" . $i : $i;
			
			$this->_files[] = $this->_dataPath . 'hourly2/hour_' . sprintf($hour) . '.txt';
		}	
	}
	
	/**
	 * Sets the data from each of the hours
	 *
	 * @access private
	 * @return void
	 */
	function setData() {
		foreach($this->_files as $i => $val) {
			$file = file($val);
			
			foreach($file as $i => $val) {
				// skip the first line,
				// which is data header
				if($i > 0) {
					$station = substr($val, 0, 5);	
					
					if($station == $this->_buoyId) {
						$this->_data[] = explode("|", preg_replace("#\s+#", "|", $val, 19));
						break;
					}
				}
			}	
		}	
	}
	
	function setReadings() {
		$readings = $this->_data;

		foreach($readings as $i => $val) {
			$this->_readings[] = $this->_set($val);	
		}
	}
	
	function _set($val) {
		$ary['year']    			 = $val[1]; // YYYY
		$ary['month']   			 = $val[2]; // MM
		$ary['day']     			 = $val[3]; // DD
		$ary['hour']    		     = $val[4]; // hh
		$ary['min']     			 = $val[5]; // mm
		$ary['timestamp']			 = mktime($val[4], $val[5], 0, $val[2], $val[3], $val[1]);
		$ary['wind_direction']       = Conversions::getWindDirection($val[6]); // WD
		$ary['wind_speed']           = Conversions::getWindSpeed($val[7]); // WSPD
		$ary['gusts']                = Conversions::getWindSpeed($val[8]); // GST
		$ary['wave_height']          = Conversions::getWaveHeight($val[9]); // WVHT
		$ary['dominant_wave_period'] = Conversions::getWavePeriod($val[10]); // DPD
		$ary['avg_wave_period']      = Conversions::getWavePeriod($val[11]); // APD
		$ary['mean_wave_direction']  = Conversions::getMeanWaveDirection($val[12]); // MWD
		$ary['barometer']            = Conversions::getBarometer($val[13]); // BARO
		$ary['air_temp']             = Conversions::getTemp($val[14]); // ATMP
		$ary['water_temp']			 = Conversions::getTemp($val[15]); // WTMP
		$ary['dewpoint']			 = Conversions::getTemp($val[16]); // DEWP
		$ary['visibility']			 = Conversions::getVisibility($val[17]); // VIS
		$ary['pressure_tendency']    = Conversions::getPressureTrend($val[18]); // PTDY
		$ary['tide']				 = Conversions::getTide(str_replace("|", "", $val[19])); // TIDE
		
		return $ary;
	}
	
	function getData() {
		return $this->_readings;
	}
}
?>