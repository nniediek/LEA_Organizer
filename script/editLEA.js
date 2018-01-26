// milestone validation 23-01-2018
		$(document).ready(function(){
	
		
		var milestonePopUp = document.getElementById("dialogbox2");
		milestonePopUp.style.display = "none";
		
		//Get the button that opens the milestonePopUp
		var addMilestoneButton = document.getElementById("AddMilestone");

		// Get the <span> element that closes the milestonePopUp
		var closeMilestonePopUp = document.getElementsByClassName("close2")[0];

		// When the user clicks on the button, open the milestonePopUp 

		addMilestoneButton.onclick = function() {
			milestonePopUp.style.display = "block";
		}

		// When the user clicks on <span> (x), close the milestonePopUp
		closeMilestonePopUp.onclick = function() {
			milestonePopUp.style.display = "none";
		}

		// When the user clicks anywhere outside of the milestonePopUp, close it
		window.onclick = function(event) {
			if (event.target == milestonePopUp) {
				milestonePopUp.style.display = "none";
			}
		}
		
			var sub = document.getElementById("milestoneSub");
			var input = document.getElementById("description");
			var deadline = document.getElementById("deadline");
			
			sub.onclick = function(){
				
			if(description.value.length > 0 && deadline.value.length > 0){
				document.getElementById("deadline").style.border = "1px solid black";
				document.getElementById("description").style.border = "1px solid black";
			return true;
			}
			else{
				if(description.value.length == 0)
				{	
					if(deadline.value != ""){
					document.getElementById("deadline").style.border = "1px solid black";
					}
				document.getElementById("description").style.border = "1px solid red";
				document.getElementById("description").placeholder="Bitte geben Sie eine Meilensteinbeschreibung ein!";
				return false;
				}
				else{
					if(deadline.value == ""){
						if(description.value != 0){
						document.getElementById("description").style.border = "1px solid red";
						}
						
					document.getElementById("description").style.border = "1px solid black";	
					document.getElementById("deadline").style.border = "1px solid red";
					return false;
					}
				}
			}
			}
		});		
		