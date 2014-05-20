<?

/*
 * IMPORTANTish - there's no checking to make sure you aren't a hacker
 * and that you should be able to do the things that you're trying to do
 * but the UI doesn't allow you to do them so it'll do for something that
 * doesn't need to be secure as it isn't a proper app
 */

if($_SERVER["REQUEST_METHOD"] === "POST") {
	if($_POST['action'] && $_POST['id'] && $_POST['drive']) {
		// re-setup things because we're just posting ajax to this file
		if(!isset($_SESSION)) {
			//session_start();
			require('db.php');
			require('helpers.php');
		}

		$me = $_SESSION['id'];
		$id = $_POST['id'];
		$drive = $_POST['drive'];
		$action = in_array($_POST['action'], array("Promote", "Demote", "Remove")) ? $_POST['action'] : false;

		$d = Helpers::getDrive($drive);

		if(!$action) return; // invalid form so quit

		if($action === 'Promote') {
			mysqli_query($db, "start transaction");
			$r1 = mysqli_query($db, "insert into managers(id, drive)
									 values('$id', '$drive');");
			$r2 = mysqli_query($db, "delete from researchers where id='$id' and drive='$drive'");

			if($r1 && $r2) {
				mysqli_query($db, "commit");
				echo "commit";
				Helpers::sendMail($id, 'You were promoted to Data Manager on drive \''.$d['name'].'\'.');
			} else {
				mysqli_query($db, "rollback");
				var_dump($r1);
				var_dump($r2);
				echo "rollback";
			}
		} else if($action === 'Demote') {
			mysqli_query($db, "start transaction");
			$r1 = mysqli_query($db, "insert into researchers(id, drive)
									 values('$id', '$drive');");
			$r2 = mysqli_query($db, "delete from managers where id='$id' and drive='$drive'");

			if($r1 && $r2) {
				mysqli_query($db, "commit");
				echo "commit";
				Helpers::sendMail($id, 'You were demoted to Researcher on drive \''.$d['name'].'\'.');
			} else {
				mysqli_query($db, "rollback");
				var_dump($r1);
				var_dump($r2);
				echo "rollback";
			}
		} else if($action === 'Remove') {
			$result = mysqli_query($db, "delete from researchers where id='$id' and drive='$drive'");
			if($result) {
				echo "commit";
				Helpers::sendMail($id, 'You were removed from drive \''.$d['name'].'\'.');
			}
		}

		exit();
	}
}

?>