$(document).ready(function(){
    
	$("#delStudent").hide();
	
	
    $("#aStudents").focus(function(){
        $("#addStudent").show();
        $("#delStudent").hide();
    });
    
    $("#rStudents").focus(function(){
        $("#delStudent").show();
        $("#addStudent").hide();
    });
    
	$("#addStudent").click(function(){
		if(document.getElementById("rStudents").children.length < 2){
			var dataList = document.getElementById("students");
			var input = document.getElementById("aStudents");
			var options = dataList.children;
			var length = options.length;
			
			for(var i = 0; i < length; i++){
				if(input.value == options[i].value){
					var opt = options[i];
					document.getElementById("rStudents").append(opt);
					input.value = "";
					break;
				}
			}
		}
	});
	
	$("#delStudent").click(function(){
		
		var select = document.getElementById("rStudents");
		var opt = select.options[select.selectedIndex];
		if(opt != null) document.getElementById("students").append(opt);
	});
	
	$("#submitTeam").click(function(){
		var select = document.getElementById("rStudents");
		for(var i = 0; i < select.options.length; i++){
			select.options[i].selected = true;
		}
	});
	
	
});