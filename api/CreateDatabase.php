<?php
	include("./config/database.php");
	include("LoremIpsum.php");
	
	
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
		for($i=0; $i<30 ; $i++)
		{
			//item name should be 1 - 3 words
			$randname = rand(1,3);
			$itemname = $lipsum->words($randname);
			echo ("Name: " . $itemname);
			echo ("<br>");
			//cost be $0.10 to $100
			$itemcost = rand(1, 100);
			echo ("$" . $itemcost);
			echo ("<br>");
			//description will be 10 - 30 words
			$randdesc = rand(10,30);
			$itemdesc = $lipsum->words($randdesc);
			echo("Description: " . $itemdesc);
			echo("<br>");

			
			//$query = "insert into items (iname, iDesc, iCost) values (" . $itemname . "," . $itemdesc ."," . $itemcost . ")";
			//echo("<br>Query: " . $query);
			$statement = $connection->prepare("insert into items (iname, iDesc, iCost) values (?,?,?)");
			echo("<br>prepared");
			$statement->bind_param("ssi", $itemname, $itemdesc, $itemcost);
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