(function(window){
	'use strict';	
	
	function define_library(){
        var Library = {};
        var name    = "AWValidator";
		
		Library.error = function(field,message)
		{

			$("#"+field).parent().addClass("has-error");
			$("#"+field).prop('title',message);
            $("#"+field).attr('data-toggle','tooltip');
            $(function () {
                $('[data-toggle="tooltip"]').tooltip({
                    template : '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="background: rgba(215,40,40,0.9);padding:5px"></div></div>',
                    //container:'body'
                    placement: 'bottom'
                })
            })
		}
		
		Library.clean = function (id)
		{

			$("#"+id+" div.form-group").removeClass("has-error");
            $('*').tooltip('destroy');
		}
		
		return Library;
	}
	//define globally if it doesn't already exist
    if(typeof(AWValidator) === 'undefined'){
        window.AWValidator = define_library();
    }
    else{
        console.log("AWValidator already defined.");
    }
	
	
	
})(window);