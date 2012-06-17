<?php
return array(
	'api/import/(:version)/(:xml)'  => 'api/import/index',  // capture calls to the API controller
	'api/(:any)'  => 'api/index/$1',  // capture calls to the API controller
);
