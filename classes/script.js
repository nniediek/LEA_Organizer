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
        if($("#aClasses option").is(":selected")){   
            var opt = document.createElement("option");
            opt.innerHTML = $("#aClasses option:selected").text();
            $("#rClasses").append(opt);
            $("#aClasses option:selected").remove();
        }
      
	});
	
	$("#delClass").click(function(){
        if($("#rClasses option").is(":selected")){
            $("#delClass").show();
            var opt = document.createElement("option");
            opt.innerHTML = $("#rClasses option:selected").text();
            $("#aClasses").append(opt);
            $("#rClasses option:selected").remove();
        }
	});
});					