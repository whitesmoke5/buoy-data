<?php
class BuoyData_Station {
	private $station_id;
	private $owner_id;
	private $ttype;
	private $hull;
	private $name;
	private $payload;
	private $location;
	private $note;
	
	public function __construct() {}
	
	public function setStationId($id) {
		$this->station_id = $id;
	}
	
	public function getStationId() {
		return $this->station_id;
	}
	
	public function setOwnerId($id) {
		$this->owner_id = $id;
	}
	
	public function getOwnerId() {
		return $this->owner_id;
	}
	
	public function setTType($type) {
		$this->ttype = $type;
	}
	
	public function getTType() {
		return $this->ttype;
	}
	
	public function setHull($hull) {
		$this->hull = $hull;
	}
	
	public function getHull() {
		return $this->hull;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setPayload($payload) {
		$this->payload = $payload;
	}
	
	public function getPayload() {
		return $this->payload;
	}
	
	public function setLocation(Ode_Geo_LatLng $coords) {
		$this->location = $coords;
	}
	
	public function getLocation() {
		return $this->location;
	}
	
	public function setNote($note) {
		$this->note = $note;
	}
	
	public function getNote() {
		return $this->note;
	}
}
?>