<!DOCTYPE html>
<html>
	<head>
		<title>PHP - Dirs and Files in current Dir</title>
	</head>
	<body>
		<h1>PHP - Dirs and Files in current Dir:</h1>
		<ul>
			<?php 
				foreach (array_diff(scandir('.'), array('.', '..')) as $item)
				{
					echo '<li><a href="./'.$item.'">'.$item.'</a></li>';
				}
			?>
		</ul>
	</body>
</html>
