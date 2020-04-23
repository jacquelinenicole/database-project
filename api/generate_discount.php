<?php
	include("./config/database.php");	
	$response = new \stdClass();
	$obj = json_decode(file_get_contents('php://input'), true);
	$database = new Database();
	$db = $database->mysqliConnection();
	//$database->createSession();
	session_start();
	
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(mysqli_connect_errno($db))
		{
			$repsonse->text = "Error with DB connect";
		}
		else
		{
			$response->transmit = $obj;
			
			//json error catch
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
			
			//get itemId
			$itemid = $obj['itemid'];
			//get today's date
			$startdate = date("Y-m-d");
			//all discounts expire in 20 days, for reasons
			$enddate = date("Y-m-d", strtotime('+20 day'));
			//unique alphanumeric string for the discount
			$code = generate_string($permitted_chars, 5);
			
			$response->day = $startdate;
			$response->endDay = $enddate;
			$response->code = $code;
			
			//get latest formula
			$stmt = $db->prepare("select MAX(fnum) from formula;");
			if($stmt->execute())
			{
				$stmt->bind_result($fnum);
				$stmt->store_result();
				
				if($stmt->num_rows() > 0)
				{
					$response->status = true;
					while($stmt->fetch())
					{
						$formula = $fnum;
					}
				}
			}
			else
			{
				$response->text .= " failed to find a formula. ";
			}
			$response->fnum = $formula;
			//insert new discount
			
			$stmt2 = $db->prepare("insert into discount (dItem_id, dFormula_id, dCode, dStart, dEnd) values (?,?,?,?,?);");
			$stmt2->bind_param("iisss", $itemid, $formula, $code, $startdate, $enddate);
			
			if($stmt2->execute())
			{
				$response->status = true;
			}
			else
			{
				$response->status = false;
				$response->text .= " failed to insert discount";
			}

		}
		echo json_encode($response);
		exit();
	}
	

	function generate_string($input, $strength = 16) 
	{
		$input_length = strlen($input);
		$random_string = '';
		for($i = 0; $i < $strength; $i++) 
		{
			$random_character = $input[random_int(0, $input_length - 1)];
			$random_string .= $random_character;
		}
 
		return $random_string;
	}
?>