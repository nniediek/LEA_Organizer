$(document).ready(function() {
			
	var bigTexts = document.getElementsByClassName("bigTextArea");
	
	for(var i=0; i<bigTexts.length; i++){
		bigTexts[i].style.display = "none";
	}
			
	var modal = document.getElementById('dialogbox');// WENN BUTTON GEÄNDeRt WIRD, DANN HIER!
	
	//Get the button that opens the modal
	var btns = document.getElementsByClassName("button_edit");
	
	// Get the <span> element that closes the modal
	var span = document.getElementById("close");

	// When the user clicks on the button, open the modal 
	for(var i = 0; i < btns.length; i++){
		btns[i].onclick = function() {
			var split = this.id.split("__");
			var title = split[2];
			
			if(title === "title"){
				document.getElementById("popupTitle").innerHTML = "Arbeitstitel bearbeiten";
			}
			else if(title === "task"){
				document.getElementById("popupTitle").innerHTML = "Kurzbeschreibung bearbeiten";
			}
			else{
				document.getElementById("popupTitle").innerHTML = "Beschreibung bearbeiten";
			}
			
			if (document.getElementById("sqlTable") != null)
				document.getElementById("sqlTable").value = split[1];
			
			if (document.getElementById("sqlColumn") != null)
				document.getElementById("sqlColumn").value = title;
			
			var textArea = document.getElementById(title + "BigText");
			textArea.style.display = "block";
			
			modal.style.display = "block";
		}
	}

	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
		modal.style.display = "none";
	
		for(var i=0; i<bigTexts.length; i++){
			bigTexts[i].style.display = "none";
		}
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modal) {
			modal.style.display = "none";
	
			for(var i=0; i<bigTexts.length; i++){
				bigTexts[i].style.display = "none";
			}
		}
	}
	
	/* $( function() {
		$( "#datepicker" ).datepicker();
	}); */
});