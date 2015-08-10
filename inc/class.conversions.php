<?php
class Conversions {
	/**
	 * Converts degree direction to compass direction
	 *
	 * @access public
	 * @param integer $degrees
	 * @return string Compass direction
	 */
	function getCompassDirection($degrees) {
		if($degrees == 0 || $degrees == 360) {
			$dir = 'North';
		} else if($degrees > 0 && $degrees < 90) {
			$dir = 'Northeast';
		} else if($degrees == 90) {
			$dir = 'East';
		} else if($degrees > 90 && $degrees < 180) {
			$dir = 'Southeast';
		} else if($degrees == 180) {
			$dir = 'South';
		} else if($degrees > 180 && $degrees < 270) {
			$dir = 'Southwest';
		} else if($degrees == 270) {
			$dir = 'West';
		} else if($degrees > 270 && $degrees < 360) {
			$dir = 'Northwest';
		}
		
		return $dir;
	}

	/**
	 * Gets the height of the waves
	 *
	 * @access public
	 * @param float $height Wave height
	 * @return array If reported, returns metric/standard measurement in meters/feet respectively
	 */
	function getWaveHeight($height) {
		$ary = array("metric" => "", "stnd" => "");
		
		if($height != "MM") {	// height has been reported
			$ary['metric'] = $height;
			$ary['stnd'] = round((3.28 * $height), 1);	
		}
		
		return $ary;
	}
	
	/**
	 * Get the wind speed
	 *
	 * @access public
	 * @param float $speed
	 * @return float If reported, returns wind speed in nautical miles/hour
	 */
	function getWindSpeed($speed) {
		if($speed != "MM") {
			return $speed;
		} else {
			return '';
		}
	}
	
	/**
	 * Gets the actual wind direction
	 *
	 * @access public
	 * @param integer $winddir
	 * @return array If reported, returns degree/compass direction of the wind
	 */
	function getWindDirection($winddir) {
		if($winddir != "MM") {
			$ary['degree'] = $winddir;
			$ary['compass'] = Conversions::getCompassDirection($winddir);
		} else {
			$ary['degree'] = '';
			$ary['compass'] = '';
		}
		
		return $ary;
	}
	
	/**
	 * Gets the length of the frequency
	 *
	 * @access public
	 * @param float $period
	 * @return float If reported returns length of the wave frequency
	 */
	function getWavePeriod($period) {
		if($period != "MM") {
			return $period;
		} else {
			return '';
		}
	}
	
	/**
	 * Gets the average reported wave direction
	 *
	 * @access public
	 * @param integer $dir Degree of direction
	 * @return array If reported returns degree/compass direction
	 */
	function getMeanWaveDirection($dir) {
		$ary = array("degree" => "", "compass" => "");
		
		if($dir != "MM") {
			$ary['degree'] = $dir;
			$ary['compass'] = Conversions::getCompassDirection($dir);
		}
		
		return $ary;
	}
	
	/**
	 * Get air pressure reading
	 *
	 * @access public
	 * @param float $reading Barometric pressure in millibars
	 * @return float If reported, returns pressure reading
	 */
	function getBarometer($reading) {
		if($reading != "MM") {
			return $reading;
		} else {
			return '';
		}
	}
	
	/**
	 * Gets temperature reading
	 *
	 * @access public
	 * @param $reading Temperature
	 * @return array If reading is given, returns the fahrenheit and celsius readings
	 */
	function getTemp($reading) {
		$ary = array("celsius" => "", "fahr" => "");
		
		if($reading != "MM") {
			$ary['celsius'] = floatval($reading);
			$ary['fahr']    = floatval((($reading * 1.8) + 32));
		}
		
		return $ary;
	}
	
	/**
	 * Gets the visibilty
	 * 
	 * @access public
	 * @param string $miles
	 * @return integer Visibiltity in miles; string if not reported
	 */
	function getVisibility($miles) {
		if($miles != "MM") {
			return $miles;
		} else {
			return '';
		}
	}

	/**
	 * Tide height
	 *
	 * @access public
	 * @param string $val The reported tide height
	 * @return string If string equals 'MM', then the tide has not been reported,
	 *					else the tide height will be returned.
	 */
	function getTide($val) {
		if($val != "MM") {
			return $val;
		} else {
			return '';
		}
	}
	
	/**
	 * Gets the pressure trend.
	 *
	 * @access public
	 * @param string $val Pressure trend
	 * @return integer Trend value, or if it is not reported, a string.
	 */
	function getPressureTrend($val) {
		if($val != "MM") {
			return $val;
		} else {
			return '';
		}
	}
}
?>