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
	$database->createSession();
	
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
					$response->text .= "add item switch";
					add_item($obj);
					break;
				case 'del_item':
					$response->text .= "del item switch. ";
					del_item($obj);
					break;
				case 'new_discount':
					$response->text .= "new discount switch. ";
					new_discount($obj);
					break;
				case 'discount_report':
					$response->text .= "discount report switch. ";
					discount_report($obj);
					break;
				case 'suggest_report':
					$response->text .= "suggest report switch. ";
					suggest_report($obj);
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
			http_response_code(200);
		}
		else
		{
			$response->status = false;
		}
		
		return;
	}
	
	function del_item($transmit)
	{
		global $response, $db;
		$response->text .= "Function del_item(). ";
		
		$itemName = $transmit['item_name'];
		
		$stmt = $db->prepare("DELETE from items where iname = ?");
		$stmt->bind_param("s", $itemName);
		
		if($stmt->execute() && ($stmt->affected_rows > 0))
		{
			$response->status = true;
			http_response_code(200);
		}
		else
		{
			$response->status = false;
		}
		
		return;
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
			http_response_code(200);
		}
		else
		{
			$response->status = false;
		}
		
		return;
	}
	
	function discount_report($transmit)
	{
		global $response, $db;
		$response->text .= "function discount_report. ";
		
		$discountCode = $transmit['discount_code'];
		
		$stmt = $db->prepare("select * from orders where oDiscount_id =( select dNum from discount where dCode = ?)");
		$stmt->bind_param("s", $discountCode);
		
		if($stmt->execute())
		{
			$response->status = true;
			
			$stmt->bind_result($order_id, $discount_id, $item_id, $quantity, $CusName, $CusPhone, $CusEmail);
			$stmt->store_result();
			
			$response->numOrders = $stmt->num_rows();
			$response->orders = array(array());
			if($stmt->num_rows() > 0)
			{
				$i = 0;
				while($stmt->fetch())
				{
					$response->orders[$i] = new \stdClass();
					//customer info
					$response->orders[$i]->CusName = $CusName;
					$response->orders[$i]->CusPhone = $CusPhone;
					$response->orders[$i]->CusEmail = $CusEmail;
					$response->orders[$i]->itemId = $item_id;
					//get item info here
					$itemResults = get_item_info($item_id);
					$response->orders[$i]->itemName = $itemResults[0];
					$response->orders[$i]->itemPrice = $itemResults[1];
					$response->orders[$i]->quantity = $quantity;
					//discount info here
					$discountResults = get_discount_info($discount_id);
					
					$i++;
				}
			}
			http_response_code(200);
		}
		else
		{
			$response->status = false;
		}

		return;
		
	}
	
	function suggest_report($transmit)
	{
		global $response, $db;
		$response->text .+ "function suggest_report. ";
	}
	
	function get_item_info($itemId)
	{
		global $db;
		$ret_info = array();
		
		$stmt = $db->prepare("select * from items where inum = ?");
		$stmt->bind_param("i", $itemId);
		if($stmt->execute())
		{
			$stmt->bind_result($inum, $iname, $idesc, $icost, $iimage);
			$stmt->store_result();
			
			if($stmt->num_rows() > 0)
			{
				while($stmt->fetch())
				{
					array_push($ret_info, $iname, $icost);
				}
			}
		}
		else
		{
			array_push($ret_info, "ERROR:No Item", 0);
		}
		
		//array_push($ret_info, "testname", 13.37);
		
		return $ret_info;
		
	}
	
	function get_discount_info($discountId)
	{
		global $db;
		$ret_info = array();
		
		$stmt = $db->prepare("select * from formula where fnum = (select dFormula_id from discount where dnum = ?);");
		$stmt->bind_param("i", $discountId);
		if($stmt->execute())
		{
			$stmt->bind_result($fnum, $ftimeleft, $fquantitystep, $fdiscountstep, $fmaxdiscount $fsteptype, $fmaxtype);
			$stmt->store_result();
			
			if($stmt->num_rows() > 0 )
			{
				while($stmt->fetch())
				{
					array_push($ret_info, $
				}
			}
		}
		else
		{
			array_push($ret_info, "No Discount");
		}
		
		return $ret_info;
	}
?>