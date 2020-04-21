<?php
	include("./config/database.php");	
	$response = new \stdClass();
	$obj = json_decode(file_get_contents('php://input'), true);
	
	
	//$obj format:
	//$obj['key'] = (string) value;
	//all keys are lowercase with '_' seperating words
	
	//$response format:
	//['text'] = (string) log of what occured
	//['transmit'] = copy of object recieved
	//['errorlog'] = any error caught
	//['status'] = (boolean) operation successful - true, or failed - false
	$database = new Database();
	$db = $database->mysqliConnection();
	
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
				
				case "add_item":
					add_item($obj);
					break;
				case 'del_item':
					break;
				case 'new_discount':
					$response->text .= "Case switch. ";
					new_discount($obj);
					break;
				case 'discount_report':
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
		global $response, $db;
		$response->text .= "Function add_item(). ";
		
		$itemName = $transmit['item_name'];
		$itemDesc = $transmit['item_desc'];
		$itemCost = floatval($transmit['item_cost']);
		$itemImage = $transmit['item_image'];
		
		$stmt = $db->prepare("insert into items (iname,idesc, icost, iImage) values (?,?,?,?);");
		$stmt->bind_param("ssds", $itemName, $itemDesc, $itemCost, $itemImage);
		if($stmt->execute())
		{
			$response->status = true;
		}
		else
		{
			$response->status = false;
		}
		
		return;
	}
	
	function del_item()
	{
	}
	
	function new_discount($transmit)
	{
		global $response, $db;
		$response->text .= "Function new_discount(). ";
		$response->text .= $transmit['discount_step'];
		$discountTime = 20;
		$discountQuantity = intval($transmit['discount_quantity']);
		$discountStep = floatval($transmit['discount_step']);
		$discountMax = floatval($transmit['discount_max']);
		$discountStepType = $transmit['discount_step_type'];
		$discountMaxType= $transmit['discount_max_type'];
		
		$stmt = $db->prepare("Insert into formula (fTimeLeft, fQuantityStep, fDiscountStep, fMaxDiscount, fStepType, fMaxType) values (?,?,?,?,?,?);");
		$stmt->bind_param("iiddss", $discountTime, $discountQuantity, $discountStep, $discountMax, $discountStepType, $discountMaxType);
		//$response->stmt = $stmt;
		$response->values = array();
		array_push($response->values, $discountTime, $discountQuantity, $discountStep,$discountMax,$discountStepType,$discountMaxType);
		
		if($stmt->execute())
		{
			$response->status = true;
		}
		else
		{
			$response->status = false;
		}
		
		return;
	}
	
	function discount_report()
	{
	}
	
	function suggest_report()
	{
	}
?>