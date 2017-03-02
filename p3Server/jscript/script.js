
////////// REFERENCE FUNCTIONS ABOVE ///////////////

function searchText(){
	
	document.getElementById("searchDrop").style.visibility = "hidden";
}

function searchRate(){
	
	document.getElementById("searchDrop").style.visibility = "visible";

}

function test(){
	window.alert("test");
}












function validation(){// function called on submission to check all the fields in the registration are in order
	//window.alert("test");
	
	var a = validateFirstName('firstname'); //First name validate, validate for each individual field, allows for each other to continue checking even if one fails 
	var b = validateLastName('lastname'); //Last name validate,
	var c = validateEmail('email'); //email format validate,
	var d = validatePass('pass'); //password validate,
	var e = confpass(); //pass conf validate,
	//var f = validatePhone('phonenum'); //phoen number validate,
	//var g = validatePC('postcode'); //ontario postal code validate,
	
	//var e = confPass();
	if (a && b && c && d && e){ // the function checking that each validate returns true
		window.alert("All Fields are Valid!");
		return true; // if true, true
	}else{
		return false; //is false, return false, error messages should already be dispalyed
	}	
	
}

function removeError(elID){ //removes the error message on chagnes
	document.getElementById(elID).style.visibility = "hidden";

}





function validateFirstName(elID){//validate first name
	
	var name = document.getElementById(elID).value;
	
	var str = elID.concat("Missing"); //creating the string for the error message
	
	

		if (name == "" || name.length < 2 || !(/[a-zA-Z \\s]{2,}$/.test(name)) ){ //if the string is letters and longer than 2 in length returns true
			document.getElementById(str).style.visibility = "visible";
			return false;
		}else{ //else returns false and displays the error message
			
			document.getElementById(str).style.visibility = "hidden";
			return true;
		}
}


function validateLastName(elID){
	var name = document.getElementById(elID).value;
	var str = elID.concat("Missing");
	

		if (name == "" || name.length < 2 || !(/[a-zA-Z \\s]{2,}$/.test(name))){ //if the string is letters and longer than 2 in length returns true
			document.getElementById(str).style.visibility = "visible";
			return false;
		}else{ //else returns false and displays the error message
			
			document.getElementById(str).style.visibility = "hidden";
			return true;
		}
}

function validateEmail(elID){
	var name = document.getElementById(elID).value;
	
	var str = elID.concat("Missing");
	
	

		if (name == "" || name.length < 2 || !(/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(name)) ){ //ensure the string matches the email pattern matching
			document.getElementById(str).style.visibility = "visible";
			return false;
		}else{ //else returns false and displays the error message
			 
			document.getElementById(str).style.visibility = "hidden";
			return true;
		}

}



function validatePass(elID){
	var val = document.getElementById(elID).value;
	var str0 = "pass";
	var str1 = str0.concat("Missing");
	
		if ((val.length < 8) || (val == "")){ //ensures the password length is longer than 8 characters

			document.getElementById(str1).style.visibility = "visible";
			return false;
		}else{ //else returns false and displays the error message
			
			document.getElementById(str1).style.visibility = "hidden";
			return true;
		}

}

function confpass(){
	var pass1 = document.getElementById("pass").value; //OG password
	var pass2 = document.getElementById("confpass").value;//confirmation password
	
	
	
	if ((pass1 == pass2) && (pass1 != "")){ //ensure both the password fields match
		document.getElementById("confpassMissing").style.visibility = "hidden";
		return true;
	}else{ //else returns false and displays the error message
		document.getElementById("confpassMissing").style.visibility = "visible";
		return false;
	}
	
	

}

function validatePhone(elID){
// 
	var name = document.getElementById(elID).value;
	var str = elID.concat("Missing");
	
	

		if (name == "" || name.length != 10 || !(/^[0-9]{10,}$/.test(name)) ){ //ensure the string matches the 10 charcter ontario phone number length
			document.getElementById(str).style.visibility = "visible";
			return false;
		}else{ //else returns false and displays the error message
			
			document.getElementById(str).style.visibility = "hidden";
			return true;
		}
}
function validatePC(elID){
	var name = document.getElementById(elID).value;
	var str = elID.concat("Missing");
	
	

		if (name == "" || name.length != 6 || !(/^[a-zA-z]+[0-9]+[a-zA-z]+[0-9]+[a-zA-z]+[0-9]$/.test(name)) ){ //ensure the string is of A0A 0A0 postal code formatting
			document.getElementById(str).style.visibility = "visible";
			return false;
		}else{ //else returns false and displays the error message
			
			document.getElementById(str).style.visibility = "hidden";
			return true;
		}

}




function getLocation() {
	//document.getElementById("lat").value = "testLat";
    //document.getElementById("lon").value = "testLong";
    if (navigator.geolocation) {
       myPos = navigator.geolocation.getCurrentPosition(showLocation);
       
    }
}

function showLocation(position) {
    document.getElementById("lat").value = position.coords.latitude;
    document.getElementById("lon").value = position.coords.longitude;
}


