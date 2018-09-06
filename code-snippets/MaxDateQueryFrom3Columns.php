<?php
/*
 * Returns a query to select things from the table lifeEvent.
 * Only selects from the entries where opt_selectable_year is the maximum for a given uuid
 *
 * $number is just an integer so that the auxiliary tables have different name
 */
function selectFromMaxYear($number, $select, $name, $uuid) {
	return "SELECT ".$select ." ".$name."
				FROM [ixcrimelab].[dbo].[AF-lifeEvent] 
				WHERE
					uuid='".$uuid."'
				AND
					CASE
						WHEN isnumeric(opt_selectable_year) = 1 THEN CAST(opt_selectable_year AS INT)
						ELSE -1
					END
					=	(SELECT MAX(AUX".$number.".YEAR) 
						FROM  	(SELECT DISTINCT
									CASE 
										WHEN isnumeric(opt_selectable_year) = 1 THEN CAST(opt_selectable_year AS INT)
										ELSE -1
									END	YEAR
								FROM [ixcrimelab].[dbo].[AF-lifeEvent]
								WHERE
									uuid='".$uuid."') AUX".$number.")";
}

/*
 * Returns a query to select things from the table lifeEvent.
 * Only selects from the entries where opt_selectable_month is the maximum 
 * from all the entries where opt_selectable_year is the maximum for a given uuid
 *
 * $number is just an integer so that the auxiliary tables have different name
 */
function selectFromMaxMonth($number, $select, $name, $uuid) {
	return "SELECT ".$select ." ".$name."
				FROM (".selectFromMaxYear($number+10, "*", "", $uuid).") AUX".$number."
				WHERE
					CASE
						WHEN isnumeric(opt_selectable_month) = 1 THEN CAST(opt_selectable_month AS INT)
						ELSE -1
					END
					=	(SELECT MAX(AUXY.MONTH)
						 FROM (".selectFromMaxYear($number+12, "opt_selectable_month", "MONTH", $uuid).") AUXY)";
}

function select($conn, $sql) {
	$stmt = sqlsrv_query($conn, $sql); 

	while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
		$data[] = $row;
	}
	
	return json_encode($data);
}

function escapePostStrings() {
	foreach ($_POST as $index => $value) {
		$_POST[$index] = str_replace("'", "", $value);
		$_POST[$index] = str_replace('"', "", $_POST[$index]);
		$_POST[$index] = str_replace("\\", "", $_POST[$index]);
	}
}

$myServer = "RATSS-ALI"; 
$myUser = "APPFORM";
$myPass = "YO-J0ker!113"; 
$myDB = "ixcrimelab"; 
$conn = sqlsrv_connect($myServer, array('UID'=>$myUser, 'PWD'=>$myPass, 'Database'=>$myDB))
or die("Couldn't connect to SQL Server on $myServer");

$gid=$_POST["gid"];

switch ($_POST["action"]) {
	case "GetEvents":
		$sql = "SELECT id, uuid, eventtype, opt_selected_year, opt_selected_month, opt_selected_day, opt_selectable_year, opt_selectable_month, opt_selectable_day FROM [ixcrimelab].[dbo].[AF-lifeEvent] WHERE uuid='$gid' ORDER BY CAST(opt_selected_year AS INT) DESC , CAST(opt_selected_month AS INT) DESC , opt_selected_day DESC";
		$answer = select($conn, $sql);
		break;
	
	case "GetLastEvent":
		/*
		 * Selects the events where opt_selectable_day is the maximum from the events where opt_selectable_month
		 * is the maximum from the events where opt_selectable_year is the maximum
		 */
		$sql = "SELECT *
				FROM (".selectFromMaxMonth(20, "*", "", $gid).") AUX1
				WHERE
					CASE
						WHEN isnumeric(opt_selectable_day) = 1 THEN CAST(opt_selectable_day AS INT)
						ELSE -1
					END
					=	(SELECT MAX(AUXX.DAY)
						 FROM (".selectFromMaxMonth(21, "opt_selectable_day", "DAY", $gid).") AUXX)";		 
		
		$answer = select($conn, $sql);
		break;
	case "UploadNewEvent":
		escapePostStrings();
		$sql = "INSERT INTO [ixcrimelab].[dbo].[AF-LifeEventGap] (uuid, event_type, start_date, end_date, title, email, mobile, description)
				VALUES ('".$gid."', '".$_POST["type"]."', '".$_POST["start_date"]."', '".$_POST["end_date"]."', 
				'".$_POST["title"]."', '".$_POST["email"]."', '".$_POST["phone"]."', '".$_POST["description"]."')";
		if(sqlsrv_query($conn, $sql))
			$answer = "success";
		else
			$answer = $sql;
		break;
	default:
		$answer = "ERROR";
		break;
}

echo $answer;

sqlsrv_close($conn);
?>