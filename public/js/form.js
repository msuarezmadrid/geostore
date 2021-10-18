(function(window){
	'use strict';	
	
	function define_library()
	{
        var Library = {};
        var name    = "AWForm";
		
		Library.create = function(params)
		{
			var content  = '<div class="box box-primary">';
			content		+= 	'<div class="box-header">';
			content 	+=		'<h3 class="box-title">'+params.title+'</h3>';
			content 	+=		'<div class="box-tools pull-right">';
			content		+=			'<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>';
			content		+=		'</div>';
			content		+=	'</div> ';
			content		+=	'<div class="box-body">';
			
			for (var x in params.rows)
			{
				content +=		'<div class="row">';
				
				for (var j in params.rows[x])
				{
					content	+=			'<div class="col-xs-'+params.rows[x][j].size+'">';
					content +=				'<div class="form-group">';
					content += 					'<label for="'+params.rows[x][j].id+'">'+params.rows[x][j].field+'</label>';
					
					switch (params.rows[x][j].type)
					{
						case 'text':
							content += 			'<input type="text"  class="form-control"  id="'+params.rows[x][j].id+'" placeholder="'+params.rows[x][j].field+'">';
						break;
					}
					
					content +=				'</div>';
					content	+=			'</div>';
				}
				content +=		'</div>';
			}
			
			content		+= 	'</div>';
			
			content		+=	'<div class="box-footer">';
			
			for (x in params.buttons)
			{
				content +=		'<button type="button" id="'+params.buttons[x].id+'" class="btn '+params.buttons[x].cls+'">'+params.buttons[x].name+'</button>';
			}
			
			content		+=	'</div>';
			content		+= '</div>';
			
			$(params.id).append(content);
			
		}
		
		return Library;
	}
	
	if(typeof(AWForm) === 'undefined'){
        window.AWForm = define_library();
    }
    else{
        console.log("AWForm already defined.");
    }
	
	
	
})(window);	
	
	