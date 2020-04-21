<?php
	include("./config/database.php");
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$database = new Database();
		$conn = $database->mysqliConnection();
		//$conn = mysqli_connect('127.0.0.1:3312', 'root', 'root', 'cop4710');
		$response = new \stdClass();
		
		if(mysqli_connect_errno($conn))
		{
			exit();
		}
		else
		{
			$stmt = $conn->prepare("Select * from items");
			$stmt->execute();
			
			$stmt->bind_result($id, $name, $desc, $cost, $image);
			$stmt->store_result();
			
			if($stmt->num_rows() > 0)
			{
				$response->items = array(array());
				$i = 0;
				while($stmt->fetch())
				{
					$response->items[$i] = new \stdClass();
					$response->items[$i]->name = $name;
					$response->items[$i]->desc = $desc;
					$response->items[$i]->cost = $cost;
					$response->items[$i]->image = $image;
					$i++;
				}
			}
		}
		echo json_encode($response);
		exit();
	}

?>