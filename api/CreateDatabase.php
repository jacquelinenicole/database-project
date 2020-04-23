<?php
	include("./config/database.php");
	include("LoremIpsum.php");
	
	$directory = '../FrontEnd/HTML/images';
	$filenames = scandir($directory);
	$connection = mysqli_connect('127.0.0.1:3312', 'root', 'root', 'cop4710');
	if(mysqli_connect_errno($connection))
	{
		echo("failed to connect to db");
	}
	else
	{
		//$scriptDB = fopen("CreateDataBaseScript.sql", "w") or die("Failed to open/create file");
		//start of script
		$lipsum = new joshtronic\LoremIpsum();
		echo "<center><h3>Lorem Ipsum Item Creator</h3></center><br>";
		echo "<center>Created 30 random items</center>";
		echo "<center>Possible images: " . implode($filenames);
		for($i=0; $i<30 ; $i++)
		{
			
			//item name should be 1 - 3 words
			$randname = rand(1,3);
			$itemname = $lipsum->words($randname);
			echo ("Name: " . $itemname);
			echo ("<br>");
			//cost be $0.10 to $100
			$itemcost = rand(0.1, 100.00);
			echo ("$" . $itemcost);
			echo ("<br>");
			//description will be 10 - 30 words
			$randdesc = rand(10,30);
			$itemdesc = $lipsum->words($randdesc);
			echo("Description: " . $itemdesc);
			echo("<br>");
			
			//random image2wbmp
			$randImage = rand(0, count($filenames));
			$itemimage = $filenames[$randImage];
			echo("Image name: " . $itemimage);
			echo("<br>");
				
			//$query = "insert into items (iname, iDesc, iCost) values (" . $itemname . "," . $itemdesc ."," . $itemcost . ")";
			//echo("<br>Query: " . $query);
			$statement = $connection->prepare("insert into items (iname, iDesc, iCost,iImage) values (?,?,?,?)");
			echo("<br>prepared");
			$statement->bind_param("ssds", $itemname, $itemdesc, $itemcost, $itemimage);
			echo("<br>bound");
			$statement->execute();
			echo("<br>excuted");
			if(!($connection->insert_id == 0))
			{
				echo("Success");
			}
			else
			{
				echo("Failure");
			}
			echo("<br>");
			echo("<br>");
		}
	}
?>