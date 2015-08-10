<?php
require_once('class.buoy_hourly.php');

$buoy = new Buoy_Hourly('44017', 9, 12);
print('<pre>');
print_r($buoy->getData());
print('</pre>');
?>