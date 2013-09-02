<?php
// File  		:	ResetChest.php
// Input 		: 	group_id
// Created by 	:	Samuel C.
// Created date :	28 agustus 2013

include ('dragon_ball_config.php');

if (!isset($_POST['group_id']))
{
	$result['status'] = 'failed';
	$result['description'] = 'parameter `group_id` required';
	echo (json_encode($result));
	return;
}

$group_id = $_POST['group_id'];

$dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

$sql = 'SELECT * FROM `group_ball` WHERE group_id="'.$group_id.'"';
$statement = $dbh->prepare($sql);
$statement->execute();
$group = $statement->fetchAll(PDO::FETCH_ASSOC);
if (count($group) == 0)
{
	$result['status'] = 'failed';
	$result['description'] = 'invalid group_id';
	echo (json_encode($result));
	return;
}

$sql = 'UPDATE `ball` SET validity="1" WHERE ';
$count = count($group);
for ($i = 0; $i < $count; $i++)
{
	$sql .= 'id="'.$group[$i]['ball_id'].'"';
	if ($i != $count - 1)
	{
		$sql .=  ' OR ';
	}
}
$exec = $dbh->exec($sql);

if ($dbh->errorCode() == SQLITE_OK)
{
	$result['status'] = 'success';
	echo (json_encode($result));
}
else
{
	$result['status'] = 'failed';
	$result['description'] = 'database error';
	echo (json_encode($result));
}
?>