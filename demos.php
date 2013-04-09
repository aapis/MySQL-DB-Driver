<?php
	
	//set error reporting
	ini_set('display_errors', 1);
	
	//setup app
	require_once('mysql.php');
	
	$args = array(
		'user' => 'root',
		'password' => 'mydbpassword',
		'host' => 'localhost',
		'database' => 'mysql_driver_test'
	);
	
	$db = MA_Database::getInstance($args); 
	
	//Example of a boolean query (INSERT, DELETE, TRUNCATE, etc)
	//$db->run('INSERT INTO users(name, age) VALUES("marz barz", 25)');

	//Example of a query that returns a result
	$users = $db->loadObjectList('SELECT * FROM users order by id ASC');
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>MySQL Database Driver Test</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <style>
  	* {margin: 0px; padding: 0px;}
  	body {font-family: Courier; background: #000; color: #ccc; padding: 20px;}
  	table {margin: auto;}
  	.user-message {background: #B03060; color: #fff; padding: 10px; border-radius: 10px; text-shadow: 1px 1px 1px #000; margin-bottom: 20px;}
  </style>

</head>
<body>
  <header>
	<h1>MySQL Database Driver Test</h1>
  </header>
  <div id="main" role="main">
  	<table align="center">
  		<tr>
	  		<th>ID</th>
	  		<th>Name</th>
	  		<th>Age</th>
	  	</tr>
	  	<?php if($users): ?>
			<?php foreach($users as $user): ?>
				<tr>
		  			<td><?php echo $user->id; ?></td>
		  			<td><?php echo $user->name; ?></td>
		  			<td><?php echo $user->age; ?></td>
		  		</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<p>Nope</p>
		<?php endif; ?>
	</table>
  </div>
  <footer>

  </footer>
</body>
</html>