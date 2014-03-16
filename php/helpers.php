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

	function in($i) {
		global $db;	

		$i = mysqli_real_escape_string($db, $i);
		//$i = htmlentities($i);

		return $i;
	}

	function out($i) {
		return htmlspecialchars($i);
	}
}

?>