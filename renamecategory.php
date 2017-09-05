<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>
</head>

<body>

<?php 	
if($cmd = filter_input(INPUT_POST,'cmd')){

	if($cmd == 'rename_category'){
		$cid = filter_input(INPUT_POST, 'categoryid', FILTER_VALIDATE_INT)
			or die('Missing/illegal categoryid parameter');
		$cnam = filter_input(INPUT_POST, 'categoryname')
			or die('Missing/illegal categoryname parameter');
		
		require_once('dbcon.php');
		$sql = 'UPDATE category SET name=? WHERE category_id=?';
		$stmt = $link->prepare($sql);
		$stmt->bind_param('si', $cnam, $cid);
		$stmt->execute();
		
		if($stmt->affected_rows >0){
			echo 'Category name updated to '.$cnam;
		}
		else {
			echo 'Could not change name of category '.$cid;
		}
	}
	else {
		die('Unknow cmd parameter: '.$cmd);
	}
}




if(empty($cid)) {
	$cid = filter_input(INPUT_GET, 'categoryid', FILTER_VALIDATE_INT)
		or die('Missing/illegal categoryid parameter');
}
	
	require_once('dbcon.php');
	$sql = 'SELECT name FROM category WHERE category_id=?';
	$stmt = $link->prepare($sql);
	$stmt->bind_param('i', $cid);
	$stmt->execute();
	$stmt->bind_result($cnam);
	while($stmt->fetch()) {}
	
  ?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset>
		<legend>Rename Category</legend>
		<input type="hidden" name="categoryid" value="<?=$cid?>" />
		<input type="text" name="categoryname" value="<?=$cnam?>" placeholder="Categoryname" required />
		<button name="cmd" value="rename_category" type="submit">Update category name</button>
	</fieldset>
</form>

<hr>
	<a href="categorylist.php"> See all categories </a>
</body>
</html>