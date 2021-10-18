<?php 
   
 return [

  'api_key'					=>	env('GMAPS_API_KEY',''),
  'geocode'					=>  'https://maps.googleapis.com/maps/api/geocode/json?',
  'directions'				=>  'https://maps.googleapis.com/maps/api/directions/json',
  'max_accuracy'			=>  150,
  'diff_seconds'			=>  0,
  'min_meters_subsidiary'	=>  50 
	
];