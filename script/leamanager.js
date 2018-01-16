$(document).ready(function(){
    
    $("#aClasses").focus(function(){
        $("#pickClass").show();
        $("#delClass").hide();
    });
    
    $("#rClasses").focus(function(){
        $("#delClass").show();
        $("#pickClass").hide(); 
    });
    
	$("#pickClass").click(function(){
        $("#aClasses").children("option").each(function(){
        if($(this).is(":selected")){   
                var opt = document.createElement("option");
                opt.id = $(this).attr('id');
                opt.innerHTML = $(this).text();
                $("#rClasses").append(opt);
                $(this).remove();
            }
       });
	});
	
	$("#delClass").click(function(){
        $("#rClasses").children("option").each(function(){
            if($(this).is(":selected")){   
                var opt = document.createElement("option");
                opt.id = $(this).attr('id');
                opt.innerHTML = $(this).text();
                $("#aClasses").append(opt);
                $(this).remove();
            }
        });
	}); 
    
        
    $("#saveLEA").focus(function(){
        $("#aClasses").children("option").each(function(){
            $(this).prop("selected",false);
        });
        
        $("#ms_list").children("option").each(function(){
            $(this).prop("selected",false);
        });
                                               
        $("#rClasses").children("option").each(function(){
            $(this).prop("selected",true);
        });                        
    });
	
		//milestone validation
		
		var temp = document.getElementById("milestoneSub");
		temp.onclick=function(){
			
			document.getElementById("description").style.background = "RED";
			document.getElementById("description").placeholer="BLA";
			return false;
		
		};
		
		
		var modal = document.getElementById("dialogbox2");
		modal.style.display = "none";
		//Get the button that opens the modal
		var btn = document.getElementById("AddMilestone");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close2")[0];

		// When the user clicks on the button, open the modal 

		btn.onclick = function() {
			modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
		
		$( function() {
			$( "#datepicker" ).datepicker();
		});
		
    
});					