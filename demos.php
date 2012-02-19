<?php

	require_once('mysql.php');
	
	$args = array(
		'user' => 'root',
		'password' => '',
		'host' => 'localhost',
		'database' => 'mysql_driver_test'
	);
	
	$db = Database::init($args);
	
	$users = $db->query_as_object('SELECT * FROM users');
	
	
?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title></title>
  <meta name="description" content="">

  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="template/css/style.css">

  <script src="template/js/libs/modernizr-2.5.2.min.js"></script>
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


  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>

  <script src="js/plugins.js"></script>
  <script src="js/script.js"></script>

  <script>
    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
  </script>
</body>
</html>