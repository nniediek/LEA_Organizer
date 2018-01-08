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
    
});					