(function(window){
	'use strict';	
	
	function define_library(){
        var Library = {};
        var name    = "AWConfig";
        var access_token = '';

        Library.getAccessToken = function() {
        	return sessionStorage.getItem('access_token');
        }

        Library.setAccessToken = function(accessToken) {
        	sessionStorage.setItem('access_token', accessToken);
        }
		
		return Library;
	}
	//define globally if it doesn't already exist
    if(typeof(AWConfig) === 'undefined'){
        window.AWConfig = define_library();
    }
    else{
        console.log("AWConfig already defined.");
    }
})(window);