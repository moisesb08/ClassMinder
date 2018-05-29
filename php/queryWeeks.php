<?php
require_once("../common/connection.php");

function byWeek()
{
	$nConn = new Connection();
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	$studentID = $_POST['studentID'];
	$classroomID = $_POST['classroomID'];
	$days = daysBetween($startDate, $endDate);
	$nQuery = "SELECT dayname(date) as day, date_format(date, '%M %e, %Y') as date, sum(
		CASE 
			WHEN recordedTime IS NULL THEN 0
			WHEN recordedTime IS NOT NULL AND isPositive=1 THEN 1
			ELSE 0
		END) as posBehaviors,
		sum(
		CASE 
			WHEN recordedTime IS NULL THEN 0
			WHEN recordedTime IS NOT NULL AND isPositive=0 THEN 1
			ELSE 0
		END) as negBehaviors
	from
	(
	SELECT DATE_ADD('".$startDate."/', INTERVAL @rn:=@rn+1 DAY) as date from (select @rn:=-1)t, STUDENT_BEHAVIOR limit $days
	) d left join (SELECT DISTINCT STUDENT_BEHAVIOR.recordedTime, BEHAVIOR.isPositive, STUDENT_BEHAVIOR.BehaviorID FROM STUDENT_BEHAVIOR 
	JOIN STUDENT ON STUDENT.studentID=STUDENT_BEHAVIOR.studentID AND STUDENT.studentID = $studentID
	JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
	JOIN CLASSROOM ON CLASSROOM.classroomID=STUDENT_CLASS.classroomID";
	if($classroomID!='')
		$nQuery .= " AND STUDENT_BEHAVIOR.classroomID = $classroomID";
	$nQuery .= " JOIN BEHAVIOR ON BEHAVIOR.behaviorID=STUDENT_BEHAVIOR.behaviorID) x
	on date(recordedTime)=date
	WHERE dayofweek(date) between 2 and 6
	group by date;";
	$records = $nConn->getQuery($nQuery);
	$data = array();
	foreach($records as $record)
	{
		$data[] = $record;
	}
	print(json_encode($data));
}

function byNoClass()
{
	$nConn = new Connection();
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	$studentID = $_POST['studentID'];
	$days = daysBetween($startDate, $endDate);
	$nQuery = "SELECT dayname(date) as day, date_format(date, '%M %e, %Y') as date, sum(
		CASE 
			WHEN recordedTime IS NULL THEN 0
			WHEN recordedTime IS NOT NULL AND isPositive=1 THEN 1
			ELSE 0
		END) as posBehaviors,
		sum(
		CASE 
			WHEN recordedTime IS NULL THEN 0
			WHEN recordedTime IS NOT NULL AND isPositive=0 THEN 1
			ELSE 0
		END) as negBehaviors
	from
	(
	SELECT DATE_ADD('".$startDate."/', INTERVAL @rn:=@rn+1 DAY) as date from (select @rn:=-1)t, STUDENT_BEHAVIOR limit $days
	) d left join (SELECT DISTINCT STUDENT_BEHAVIOR.recordedTime, BEHAVIOR.isPositive, STUDENT_BEHAVIOR.behaviorID FROM STUDENT_BEHAVIOR 
	JOIN STUDENT ON STUDENT.studentID=STUDENT_BEHAVIOR.studentID AND STUDENT.studentID = $studentID
	JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
	JOIN CLASSROOM ON CLASSROOM.classroomID=STUDENT_CLASS.classroomID
	JOIN BEHAVIOR ON BEHAVIOR.behaviorID=STUDENT_BEHAVIOR.behaviorID) x
	on date(recordedTime)=date
	WHERE dayofweek(date) between 2 and 6
	group by date;";
	$records = $nConn->getQuery($nQuery);
	$data = array();
	foreach($records as $record)
	{
		$data[] = $record;
	}
	print(json_encode($data));
}

function daysBetween($date1, $date2)
{
	$date1 = new DateTime($date1);
	$date2 = new DateTime($date2);
	return $date2->diff($date1)->format("%a")+1;
}

function topBehaviors()
{
	$nConn = new Connection();
	$d=strtotime("today");
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	$studentID = $_POST['studentID'];
	$classroomID = $_POST['classroomID'];
	$isPositive = $_POST['isPositive'];
	$endDate = date( 'Y-m-d', strtotime( $endDate . ' + 1 day' ) );
	if($_POST['isPositive'] == 2)
	{
		$nQuery = "SELECT b.title, sb.studentID, sb.behaviorID, sb.recordedTime, b.isPositive, count(*) as total
		FROM STUDENT_BEHAVIOR sb JOIN BEHAVIOR b ON sb.behaviorID=b.behaviorID
		WHERE sb.recordedTime between '$startDate' and '$endDate'
		AND sb.studentID = $studentID";
		if($classroomID!='')
			$nQuery .= " AND sb.classroomID = $classroomID";
		$nQuery .= " GROUP BY b.title, sb.behaviorID, sb.recordedTime
		ORDER BY total DESC, b.isPositive, recordedTime DESC limit 5;";
	}
	else
	{
		$nQuery = "SELECT b.title, sb.studentID, sb.behaviorID, sb.recordedTime, b.isPositive, count(*) as total
		FROM STUDENT_BEHAVIOR sb JOIN BEHAVIOR b ON sb.behaviorID=b.behaviorID
		WHERE sb.recordedTime between '$startDate' and '$endDate'
		AND sb.studentID = $studentID AND isPositive=$isPositive";
		if($classroomID!='')
			$nQuery .= " AND sb.classroomID = $classroomID";
		$nQuery .= " GROUP BY b.title, sb.behaviorID, sb.recordedTime
		ORDER BY total DESC, b.isPositive, recordedTime DESC limit 5;";
	}
	$records = $nConn->getQuery($nQuery);
	$data = array();
	foreach($records as $record)
	{
		$data[] = $record;
	}
	print(json_encode($data));
}

function last2Weeks()
{
	$nConn = new Connection();
	$d=strtotime("today");
	$currDate = date("Y-m-d", $d);
	$days = 14;
	$startDate = date( 'Y-m-d', strtotime( $currDate . ' -'.($days-1).' day' ) );
	$studentID = $_POST['studentID'];
	$classroomID = $_POST['classroomID'];
	$nQuery = "SELECT dayname(date) as day, date_format(date, '%M %e, %Y') as date, sum(
		CASE 
			WHEN recordedTime IS NULL THEN 0
			WHEN recordedTime IS NOT NULL AND isPositive=1 THEN 1
			ELSE 0
		END) as posBehaviors,
		sum(
		CASE 
			WHEN recordedTime IS NULL THEN 0
			WHEN recordedTime IS NOT NULL AND isPositive=0 THEN 1
			ELSE 0
		END) as negBehaviors
	from
	(
	SELECT DATE_ADD('".$startDate."/', INTERVAL @rn:=@rn+1 DAY) as date from (select @rn:=-1)t, STUDENT_BEHAVIOR limit $days
	) d left join (SELECT DISTINCT STUDENT_BEHAVIOR.recordedTime, BEHAVIOR.isPositive, STUDENT_BEHAVIOR.behaviorID FROM STUDENT_BEHAVIOR 
	JOIN STUDENT ON STUDENT.studentID=STUDENT_BEHAVIOR.studentID AND STUDENT.studentID = $studentID
	JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
	JOIN CLASSROOM ON CLASSROOM.classroomID=STUDENT_CLASS.classroomID
	JOIN BEHAVIOR ON BEHAVIOR.behaviorID=STUDENT_BEHAVIOR.behaviorID) x
	on date(recordedTime)=date
	WHERE dayofweek(date) between 2 and 6
	group by date;";
	$records = $nConn->getQuery($nQuery);
	$data = array();
	foreach($records as $record)
	{
		$data[] = $record;
	}
	print(json_encode($data));
}

if(isset($_POST['topBehaviors']))
{
	topBehaviors();
}
else if(isset($_POST['studentProfile']))
{
	last2Weeks();
}
else if (isset($_POST["classroomID"]))
{
	byWeek();
}
else
{
	byNoClass();
}
?>