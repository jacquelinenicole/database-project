<?php
	
	include("./config/database.php");	
	$response = new \stdClass();
	$obj = json_decode(file_get_contents('php://input'), true);

	//$response format:
	//['text'] = (string) log of what occured
	//['transmit'] = copy of object recieved
	//['errorlog'] = any error caught
	//['status'] = (boolean) operation successful - true, or failed - false
	$database = new Database();
	$db = $database->mysqliConnection();
	$response->sessiontest = $database->createSession();

	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(mysqli_connect_errno($db))
		{
			$repsonse->text = "Error with DB connect";
		}
		else
		{
			$response->transmit = "default";
			$response->text = "Triggered Post. ";
			//$response->foo = $obj['foo'];
			//$response->footype = gettype($obj['foo']);
			$response->transmit = $obj;
			
			switch (json_last_error()) 
			{
				case JSON_ERROR_NONE:
					$response->errorlog = ' - No errors';
				break;
				case JSON_ERROR_DEPTH:
					$response->errorlog = ' - Maximum stack depth exceeded';
				break;
				case JSON_ERROR_STATE_MISMATCH:
					$response->errorlog = ' - Underflow or the modes mismatch';
				break;
				case JSON_ERROR_CTRL_CHAR:
					$response->errorlog = ' - Unexpected control character found';
				break;
				case JSON_ERROR_SYNTAX:
					$response->errorlog = ' - Syntax error, malformed JSON';
				break;
				case JSON_ERROR_UTF8:
					$response->errorlog = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
				break;
				default:
					$response->errorlog = ' - Unknown error';
				break;
			}
			
			switch($obj['foo'])
			{
				
				case "addCart":
					$response->text .= "add item switch";
					add_item($obj);
					break;
				case "getCart":
					$response->text .= "get cart switch";
					get_cart($obj);
					break;
				default:
					$response->text .= "unable to case switch.";
					break;
			}
			
		}
		//$response->text = "not zero";
		echo json_encode($response);
	}
	
	function add_item($transmit)
	{
		global $db, $response;
		$response->text .= "Made it to addItems in cart.php. ";
		$response->sessionSuccess = session_start();
		if (!isset($_SESSION['cart']))
			$_SESSION['cart'] = array();
		
		$found = false;

		for ($i = 0; $i < count($_SESSION['cart']); $i++ )
		{
			if ($_SESSION['cart'][$i]['id'] == $transmit['id'])
			{
				$response->arraySet = "true";
				$_SESSION['cart'][$i]['quantity'] += $transmit['quantity'];
				$found = true;
				break;
			}
		}
		if (!$found){
			$response->arraySet = "false";
			$item = array();
			$item['id'] = intval($transmit['id']);
			$item['quantity'] = intval($transmit['quantity']);
			array_push($_SESSION['cart'], $item);
		}
		$response->session = $_SESSION['cart'];

	}

	function get_cart($transmit)
	{
		global $db, $response;

		$response->sessionSuccess = session_start();
		$response->test =isset($_SESSION['cart']);
		$empty = isset($_SESSION['cart']) ? false : true;
		//$response->cart = $_SESSION['cart'];
		if ($empty)
		{
			$response->emptyCart = true;
			return;
		}
		else {
			$response->emptyCart = false;
			$response->items = array(array());
			$j = 0;
			for ($i = 0; $i < count($_SESSION['cart']); $i++ )
			{
				$stmt = $db->prepare("Select * from items i where i.iNum = (?);");
				$stmt->bind_param("i", $_SESSION['cart'][$i]['id']);
				$stmt->execute();
			
				$stmt->bind_result($id, $name, $desc, $cost, $image);
				$stmt->store_result();
			
				if($stmt->num_rows() > 0)
				{
					
					
					
					$stmt->fetch();
					$response->items[$j] = new \stdClass();
					$response->items[$j]->id = $id;
					$response->items[$j]->name = $name;
					$response->items[$j]->desc = $desc;
					$response->items[$j]->cost = $cost;
					$response->items[$j]->image = $image;
					$j++;
					
				}
			}
			
			return;
		}
	}
?>