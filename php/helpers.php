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

	function hi() {
		$hi = array('hi', 'hey', 'sup', 'yo', 'howdy', 'ahoy');
		return array_rand(array_flip($hi));
	}

	function name($n = 2, $s = '-') {
		$string = file_get_contents('php/words.txt');
		$words = explode("\n", $string);

		$name = array();

		for ($i=0; $i < $n; $i++) { 
			array_push($name, array_rand(array_flip($words)));
		}

		return implode($s, $name);
	}
}

?>