<?php
require('php/db.php');
session_start();

class Helpers {

	function loggedIn() {
		if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
			return true;
		} else {
			return false;
		}
	}

	// returns true if the currently logged in user has pending requests
	function hasRequests() {
		if(!Helpers::loggedIn()) return false;

		$requests = Helpers::getRequests();
		return empty($requests) ? false : true;
	}

	// returns all the requests of the id specified or the current user
	function getRequests($id = null) {
		$id = isset($id) ? $id : $_SESSION['id'];
		global $db;
		$requests = array();

		$sql = "select user, faculty, name, size, status, type from requests where user='$id'";
		$result = mysqli_query($db, $sql);

		if($result)
			while($row = mysqli_fetch_assoc($result))
				$requests[] = $row;

		return $requests;
	}

	// returns all the requests for a given faculty
	// only initial space requests?
	function getFacultyRequests($faculty) {
		global $db;
		$requests = array();

		$sql = "select uid, user, name, size, status from requests where faculty='$faculty' and type=0";
		$result = mysqli_query($db, $sql);

		if($result)
			while($row = mysqli_fetch_assoc($result))
				$requests[] = $row;

		return $requests;
	}

	// returns all the drives for the given faculty
	function getFacultyDrives($faculty) {
		global $db;
		$drives = array();

		$sql = "select name, capacity, used from drives where faculty='$faculty'";
		$result = mysqli_query($db, $sql);

		if($result)
			while($row = mysqli_fetch_assoc($result))
				$drives[] = $row;

		return $drives;
	}

	// returns true if the currently logged in used is a member of some drives
	function hasDrives() {
		if(!Helpers::loggedIn()) return false;

		$drives = Helpers::getAll(); // silly php
		return empty($drives) ? false : true;
	}

	// returns all the drives the user is a member of
	function getAll($id = null) {
		$id = isset($id) ? $id : $_SESSION['id'];
		return array_merge(Helpers::getR($id), Helpers::getD($id), Helpers::getP($id));
	}

	// returns all the drives the user is a researcher of
	function getR($id = null) {
		$id = isset($id) ? $id : $_SESSION['id'];
		return Helpers::getDrives('researchers', $id);
	}

	// returns all the drives the user is a data manager of
	function getD($id = null) {
		$id = isset($id) ? $id : $_SESSION['id'];
		return Helpers::getDrives('managers', $id);
	}

	// returns all the drives the user is a principal investigator of
	function getP($id = null) {
		$id = isset($id) ? $id : $_SESSION['id'];
		return Helpers::getDrives('principals', $id);
	}

	// get the drives in which the user is a specific role of
	function getDrives($role, $id) {
		global $db;
		$drives = array();

		$sql = "select drive from $role where id='$id'";
		$result = mysqli_query($db, $sql);

		if($result) {
			while($row = mysqli_fetch_assoc($result)) {
				$row['role'] = Helpers::getReadableRole($role); // add user role to the drive for listing later
				$drives[] = $row;
			}
		}

		return $drives;
	}

	// retrun info about a single drive
	function getDrive($uid) {
		global $db;
		$drive = array();

		$sql = "select * from drives where uid='$uid'";
		$result = mysqli_query($db, $sql);

		if($result)
			$drive = mysqli_fetch_assoc($result);

		return $drive;
	}

	// returns which faculty this user is an approver of, if any
	function getApprover($id = null) {
		$id = isset($id) ? $id : $_SESSION['id'];
		global $db;
		$faculty = null;

		$sql = "select faculty from approvers where id='$id'";
		$result = mysqli_query($db, $sql);

		if($result) {
			$faculty = mysqli_fetch_array($result)[0];
		}

		return $faculty;
	}

	// returns info about a specified user
	function getUser($id) {
		global $db;
		$user = null;

		$sql = "select first, last, id from users where id='$id'";
		$result = mysqli_query($db, $sql);

		if($result)
			$user = mysqli_fetch_assoc($result);

		return $user;
	}

	// return the actual role rather than just the table name
	function getReadableRole($role) {
		$roles = array(
			"principals" => "Principal Investigator",
			"managers" => "Data Manager",
			"researchers" => "Researcher"
		);

		return $roles[$role];
	}

	// safely insert into the database
	function in($i) {
		global $db;	

		$i = mysqli_real_escape_string($db, $i);
		//$i = htmlentities($i);

		return $i;
	}

	// safely output to html
	function out($i) {
		return htmlspecialchars($i);
	}

	// return random greeting
	function hi() {
		$hi = array('hi', 'hey', 'sup', 'yo', 'howdy', 'ahoy');
		return array_rand(array_flip($hi));
	}

	// create a random name eg. zombie-plumber
	function name($n = 2, $s = '-') {
		$string = file_get_contents('php/words.txt');
		$words = explode("\n", $string);

		$name = array();

		for ($i=0; $i < $n; $i++) { 
			array_push($name, array_rand(array_flip($words)));
		}

		return implode($s, $name);
	}

	// returns a nice number i think
	function niceNumber($n) {
		if(!is_numeric($n)) return $n;

		if($n > 1000000) return number_format($n/1000000,2). ' TB';
		else if($n > 1000) return number_format($n/1000,2) . ' GB';
		else return number_format($n) . ' MB';
	}
}

?>