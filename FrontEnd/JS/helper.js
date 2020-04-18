function alertMissingFields(missing) {
	if (missing.length == 1) {
		alert("Missing field: " + missing[0]);
		return;
	}

	var message = "Missing fields:";

	for (let i = 0 ; i < missing.length-1 ; i++) {
		message += " " + missing[i] + ",";
	}

	if (missing.length == 2) { // remove comma
		message = message.slice(0, -1);
	}

	message += " and " + missing[missing.length-1];

	alert(message);
}