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

function validateDigit(event) {
    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode === 8) {
        return true;
    } else if ( key < 48 || key > 57 ) {
        return false;
    }
    
    return true;
};

function validateFloat(event, t, id) {
    var key = window.event ? event.keyCode : event.which;

    if (event.keyCode === 8) {
        return true;
    } else if (event.keyCode == 46) {
        var prevInput = document.getElementById(id).value;
        return !prevInput.includes('.') ? true : false;
    } else if ( key < 48 || key > 57 ) {
        return false;
    }

    return true;
};

function validateMoney(event, t, id) {
    var key = window.event ? event.keyCode : event.which;
    var prevInput = document.getElementById(id).value;
    var periodIndex = prevInput.indexOf('.');

    if (event.keyCode === 8) {
        return true;
    } else if (periodIndex > -1) {
    	if (event.keyCode === 46) {
    		return false;
    	}

    	return prevInput.length - periodIndex < 3;
    } else if ((key < 48 || key > 57) && key != 46) {
        return false;
    }

    return true;
};