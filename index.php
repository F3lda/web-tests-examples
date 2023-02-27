<!DOCTYPE html>
<html>
	<head>
		<title>Web Tests and Examples</title>
	</head>
	<body>
		<h1>Web Tests and Examples:</h1>
		<ul>
			<?php 
                $array = scandir('.');
                $array = array_diff($array, array('.', '..', 'index.php'));
                asort($array, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
				foreach ($array as $item)
				{
					echo '<li><a href="./'.$item.'">'.$item.'</a></li>';
				}
			?>
		</ul>
	</body>
</html>
