<?php

	require_once('mysql.php');
	
	$args = array(
		'user' => 'root',
		'password' => 'mydbpassword',
		'host' => 'localhost',
		'database' => 'mysql_driver_test'
	);
	
	$db = Database::init($args); 
	
	$users = $db->query_as_object('SELECT * FROM users');
	$db->run('INSERT INTO users(name, age) VALUES("john", 25)');
	
?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>MySQL Database Driver Test</title>
  <meta name="description" content="">

  <meta name="viewport" content="width=device-width">

</head>
<body>
  <header>
	<h1>MySQL Database Driver Test</h1>
  </header>
  <div role="main">
  	<table align="center">
  		<tr>
	  		<th>ID</th>
	  		<th>Name</th>
	  		<th>Age</th>
	  	</tr>
		<?php foreach($users as $user): ?>
			<tr>
	  			<td><?php echo $user->id; ?></td>
	  			<td><?php echo $user->name; ?></td>
	  			<td><?php echo $user->age; ?></td>
	  		</tr>
		<?php endforeach; ?>
	</table>
  </div>
  <footer>

  </footer>
</body>
</html>