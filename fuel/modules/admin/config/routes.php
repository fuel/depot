<?php
return array(
	'admin/(:segment)'  => 'admin/admin/$1/index',				// admin module index pages
	'admin/(:segment)/(:num)'  => 'admin/admin/$1/index/$2',	// paginated index pages
	'admin/(:any)'  => 'admin/admin/$1',						// all other admin module pages
);
