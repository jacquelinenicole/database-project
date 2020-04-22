<!DOCTYPE html>
<html>
<body>
<?php
	echo "<h2>This is a test php page</h2>";
	echo "<br> if you see this then php is working correctly";
	
	$database = mysqli_connect('127.0.0.1:3312', 'root', 'root', 'cop4710');
	
	if(!$database)
	{
		
		echo "<br><h3>There was an error connecting to the MySQL Database</h3>";
		$error = mysqli_connect_errno();
		echo "<br>Error Code:  {$error}";
		
	}
	else
	{
		
		$statement = $database->prepare("SELECT * FROM items");
		$statement->execute();
		
		$statement->bind_result($num,$name,$desc,$cost,$image);
		$statement->store_result();
		echo "<br> Successfully connected to the MySQL Database";
		
		if($statement->num_rows() < 1)
		{
			echo "<br> MySQL returned an empty set";
		}
		else
		{
			echo "<table style='width:100%'>";
			echo '<tr><th>number</th><th>name</th><th>description</th><th>cost</th><th>image</th></tr>';
			while($statement->fetch())
			{
				echo "<tr>";
				echo "<th>{$num}</th>";
				echo "<th>{$name}</th>";
				echo "<th>{$desc}</th>";
				echo "<th>{$cost}</th>";
				echo "<th>{$image}</th>";
				echo "</tr>";
			}
		}
	}
	
	echo "<br><h2>If everything worked there should be a table below(?) </h2>";
	
	
?>
</body>
</html>