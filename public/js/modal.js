(function(window){
	'use strict';	
	
	function define_library(){
        var Library = {};
        var name    = "AWModal";
		
		Library.create = function(id,params,type, buttonName="")
		{
			var total   = 12;


			var  content = '<div class="modal fade" id="' + id + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">';
			content += '<div class="modal-dialog" role="document">';
			content += '<div class="modal-content">';
			content += '<div class="modal-header">';
			content    += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			content    += '<h4 class="modal-title" id="myModalLabel">'+params.title+'</h4>';
			content    += '</div>';
			
			content    += '<div class="modal-body">';
			for (var x in params.rows)
			{
				content += '<div class="row">';
					for (var j in params.rows[x])
					{
						if (params.rows[x][j].type !== 'hidden')
						{
						
							content += '<div class="col-xs-'+Math.round(total/params.rows[x].length)+'">';
								   content += '<div class="form-group">';
								   if(params.rows[x][j].field != '')
								   {
									   content += '<label for="'+params.rows[x][j].id+'">'+params.rows[x][j].field+'</label>';
								   }
										
								   switch(params.rows[x][j].type)
								   {
										case 'text':
											content += '<input type="text"  class="form-control"  id="'+params.rows[x][j].id+'" placeholder="'+params.rows[x][j].field+'">';
										break;
										
										case 'password':
											content += '<input type="password"  class="form-control"  id="'+params.rows[x][j].id+'" placeholder="'+params.rows[x][j].field+'">';
										break;
										
										case 'combo':
											content += '<select class="form-control"  id="'+params.rows[x][j].id+'"></select>';
										break;

										case 'number':
											content += '<input type="number" min="0" class="form-control"  id="'+params.rows[x][j].id+'" placeholder="'+params.rows[x][j].field+'">';
										break;
									   case 'date':
										   content += '<input type="text"  class="form-control"  id="'+params.rows[x][j].id+'" placeholder="'+params.rows[x][j].field+'">';
										   break;
										case 'file':
											content += '<input type="file" name="file" id="'+params.rows[x][j].id+'">';
										break;
										/*case 'hidden':
											content += '<input type="hidden"  id="'+params.rows[x][j].id+'" >';
										break;*/
										case 'textarea':
											content += '<textarea class="form-control" id="'+params.rows[x][j].id+'" placeholder="'+params.rows[x][j].field+'"  ></textarea>';
										break;
										case 'color':
											content += '<div id="color_'+params.rows[x][j].id+'" class="input-group colorpicker-component">';
											content += '	<input type="text" id="'+params.rows[x][j].id+'"  class="form-control" >';
											content += '	<span class="input-group-addon"><i></i></span>';
											content += '</div>';
										break;
										case 'time':
											content += '<div class="input-group bootstrap-timepicker timepicker">';
											content += '	<input id="'+params.rows[x][j].id+'" type="text" class="form-control input-small">';
											content += '	<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>';
											content += '</div>';
										break;

										case 'grid':

	                                       content += '<br>';
	                                       //content += '<div class="box box-primary" style="border-top-color: darkgray !important;">';
	                                       //content += '<div class="box-body">';
	                                       content += '<table id="'+params.rows[x][j].id+'" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">';
	                                       content += '<thead><tr>';
	                                       $.each(params.rows[x][j].data, function (k, val) {
	                                           content += '<th>'+val+'</th>';
	                                       });
										   content +='</tr></thead>';
										   content +='</table>';
										   //content +='</div>';
										   //content +='</div>';
									   break;
									   
									   case 'imghid':
										   content += '<img id="'+params.rows[x][j].id+'" class="form-control" hidden style="height:180px;width:180px;">';
									   break;

								   }
								   content += '</div>';
							content += '</div>';
						}
						else
						{
							content += '<input type="hidden"  id="'+params.rows[x][j].id+'" >';
						}							
					}						
					 
				content += '</div>';
			}
			//content    += '</form>';
			content    += '</div>';

			content    += '<div class="modal-footer">';
			content    += '<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>';
			
			if (typeof type != 'undefined')
			{
				if (type == 'update')
				{
					content    += '<button id="'+id+'_update" type="button" class="btn btn-primary">Actualizar</button>';
				}
				if (type == 'custom')
				{
					content    += '<button id="'+id+'_' + buttonName + '" type="button" class="btn btn-primary">' + buttonName + '</button>';
				}
			}
			else content    += '<button id="'+id+'_create" type="button" class="btn btn-primary">Crear</button>';
			content    += '</div>';
			content    += '</div>';
			content    += '</div>';
			content    += '</div>';

			$("html").append(content);

		}
		
		return Library;
	}
	//define globally if it doesn't already exist
    if(typeof(AWModal) === 'undefined'){
        window.AWModal = define_library();
    }
    else{
        console.log("AWModal already defined.");
    }
	
	
	
})(window);