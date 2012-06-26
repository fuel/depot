<?php
return array(
	'api/import/(:version)/(:xml)'  => 'api/import/index',  // capture calls to the API controller
	'api/(:segment)/(:any)' => 'api/$1/index/$2',
	'api/(:segment)/(:num)/(:any)' => 'api/$1/index/$2/$3',
);
