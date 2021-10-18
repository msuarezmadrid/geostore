(function(window){
    'use strict';


	function define_library(){
        var Library = {};
		var name = "AWApi";
		var filename = "archivo";
        var authorization = "Bearer " + AWConfig.getAccessToken();
        
        var myHeaders =  new Headers({
    		'Authorization': authorization,
  		});

        Library.greet = function(){
            alert("Hello from the " + name + " library.");
        }

        //TODO
        Library.getAjax = function(URL, callback) {
        	$.ajax({
		         url: URL,
		         type: "GET",
		         beforeSend: function(xhr){
		         	xhr.setRequestHeader('Authorization', authorization);
		         },
		         success: callback
		    });
        }
		
		Library.get = function(URL, callback) {
			var request = new Request(URL, {
				headers: myHeaders,
				method: 'GET'
			});

			var result = fetch(request).then(function(response) {
				console.debug(response);
				return response.json();
			}).then(function(data) {
    			callback(data);	
			}).catch(function(err) {
				console.error(err);
			});
		}

		Library.post = function(URL, data, callback){

			var result = fetch(URL,{
				headers: myHeaders,
  				method: 'POST',
  				body: data
			}).then(function(response) {
				return response.json();
			}).then(function(data) {
    			callback(data);
			}).catch(function(err) {
				console.error(err);
			});
		}


		Library.put = function(URL, data, callback){
			data.append('_method','PUT');
			var result = fetch(URL,{
				headers: myHeaders,
  				method: 'POST',
  				body: data
			}).then(function(response) {
				return response.json();
			}).then(function(data) {
    			callback(data);	
			}).catch(function(err) {
				console.error(err);
			});
		}

		Library.delete = function(URL, callback){
			var result = fetch(URL,{
				headers: myHeaders,
  				method: 'DELETE'
			}).then(function(response) {
				return response.json();
			}).then(function(data) {
    			callback(data);	
			}).catch(function(err) {
				console.error(err);
			});
		}

		Library.download = function(URL, callback) {
            var request = new Request(URL, {
                headers: myHeaders,
                method: 'GET'
            });
            var result = fetch(request).then(function(response) {
                var disposition = response.headers.get('Content-Disposition');
				var matches = disposition.split("filename=");

                /*var filenameRegex = /filename[^;=\n]=((['"]).?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);*/
                if (matches != null && matches[1]) {
                    filename = matches[1].replace(/['"]/g, '');
                }
                return response.blob();
            }).then(function(data){
                var url = window.URL.createObjectURL(data);
                var a = document.createElement('a');
                a.href = url;
                a.download = filename;
				a.click();
            });
        }



        return Library;
    }
    //define globally if it doesn't already exist
    if(typeof(AWApi) === 'undefined'){
        window.AWApi = define_library();
    }
    else{
        console.log("AWApi already defined.");
    }

})(window);