<?php
require_once('class.buoy_data.php');

/**
 * Retrieves data from the National Buoy Data Center's real-time data files
 *
 * @access public
 * @author C.J. Walsh <cj@odewebdesigns.com>
 * @copyright OdeWeb Designs 2005
 * @link http://www.odewebdesigns.com/
 * @package Buoy_Data
 * @version 0.1
 */

class Buoy_Real_Time extends Buoy_Data {
	
	/**
	 * Constructor
	 *
	 * @access public
	 * @param string $buoy_id Buoy station ID number
	 * @return void
	 */
	function Buoy_Real_Time($buoy_id) {
		$this->Buoy_Data($buoy_id);	
		
		$this->setFile();
		
		$this->setData();
	}	
	
	/**
	 * Retrieves the text data file from the NOAA website
	 *
	 * @access private
	 * @return void
	 */
	function setFile() {
		$this->_dataFile = $this->_dataPath . 'realtime2/' . $this->_buoyId . '.txt';
	}
	
	/**
	 * Sets up the data array
	 *
	 * @access private
	 * @return void
	 */
	function setData() {
		$file = file($this->_dataFile);
		
		$line = explode("|", preg_replace("#\s+#", "|", $file[1]));
		
		$this->_data = $this->parseBuoyData($line);
	}
	
	/**
	 * Retrieves the data array
	 *
	 * @access private
	 * @return array Buoy data
	 */
	function getData() {
		return $this->_data;	
	}
	
	/**
	 * Parses and assigns data file's information into readable format
	 *
	 * @access public
	 * @param array $val The buoy data array of information
	 * @return array An array of buoy data
	 */
	function parseBuoyData($val) {
		$ary['year']    			 = $val[0]; // YYYY
		$ary['month']   			 = $val[1]; // MM
		$ary['day']     			 = $val[2]; // DD
		$ary['hour']    		     = $val[3]; // hh
		$ary['min']     			 = $val[4]; // mm
		$ary['timestamp']			 = mktime($val[3], $val[4], 0, $val[1], $val[2], $val[0]);
		$ary['wind_direction']       = Conversions::getWindDirection($val[5]); // WD
		$ary['wind_speed']           = Conversions::getWindSpeed($val[6]); // WSPD
		$ary['gusts']                = Conversions::getWindSpeed($val[7]); // GST
		$ary['wave_height']          = Conversions::getWaveHeight($val[8]); // WVHT
		$ary['dominant_wave_period'] = Conversions::getWavePeriod($val[9]); // DPD
		$ary['avg_wave_period']      = Conversions::getWavePeriod($val[10]); // APD
		$ary['mean_wave_direction']  = Conversions::getMeanWaveDirection($val[11]); // MWD
		$ary['barometer']            = Conversions::getBarometer($val[12]); // BARO
		$ary['air_temp']             = Conversions::getTemp($val[13]); // ATMP
		$ary['water_temp']			 = Conversions::getTemp($val[14]); // WTMP
		$ary['dewpoint']			 = Conversions::getTemp($val[15]); // DEWP
		$ary['visibility']			 = Conversions::getVisibility($val[16]); // VIS
		$ary['pressure_tendency']    = Conversions::getPressureTrend($val[17]); // PTDY
		$ary['tide']				 = Conversions::getTide(str_replace("|", "", $val[18])); // TIDE
		
		return $ary;
	}
}
?>
