<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Category-list</title>
</head>

<body>
<?php 

if($cmd = filter_input(INPUT_POST,'cmd')){

	if($cmd == 'create_category'){

		$cnam = filter_input(INPUT_POST, 'categoryname')
			or die('Missing/Illegal categoryname parameter');

		require_once('dbcon.php');
		$sql = 'INSERT INTO category (name) VALUES (?);';
		$stmt = $link->prepare($sql);
		$stmt->bind_param('s',$cnam); // only if params
		$stmt->execute();

		if($stmt->affected_rows > 0){
			echo 'Created category '.$cnam.' with id: '.$stmt->insert_id; 
		} 
		else {
			echo 'Error creating category';
		}

	} 

	elseif($cmd == 'delete_category'){

		$cid = filter_input(INPUT_POST, 'categoryid', FILTER_VALIDATE_INT)
			or die('Missing/Illegal categoryid parameter');

		require_once('dbcon.php');
		$sql = 'DELETE FROM category WHERE category_id=?';
		$stmt = $link->prepare($sql);
		$stmt->bind_param('i',$cid); // only if params
		$stmt->execute();

		if($stmt->affected_rows > 0){
			echo 'Category '.$cid.' deleted';
		} 
		else {
			echo 'Error creating category';
		}

	} 
	else {
		die('Unknown cmd parameter');
	}

}


?>

	<h1>Categories</h1>
	
	<ul>
<?php
		require_once('dbcon.php');
		$sql = 'SELECT category_id, name FROM category';
		$stmt = $link->prepare($sql);
		// $stmt->bind_param('',); // only if params
		$stmt->execute();
		$stmt->bind_result($cid, $nam);
		while($stmt->fetch()){ ?>
			<li>
				<a href="filmlist.php?categoryid=<?=$cid?>"><?=$nam?></a>
				<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
					<button name="cmd" value="delete_category" type="submit">Delete</button>
					<input type="hidden" name="categoryid" placeholder="Categoryid" value="<?=$cid?>"required>
				</form>
				<a href="renamecategory.php?categoryid=<?=$cid?>">Rename</a>
			</li>
<?php	} ?>
	</ul>


	<hr>
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
		<fieldset>
		<legend>Create a category</legend>
			<input type="text" name="categoryname" placeholder="Categoryname" required>
			<button name="cmd" value="create_category" type="submit">Create it</button>
		</fieldset>
	</form>


</body>
</html>