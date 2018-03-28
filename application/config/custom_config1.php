<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| local_country
|--------------------------------------------------------------------------
|
| The local country
|
|
*/

$config['local_country'] = 'Uganda';

/*
|--------------------------------------------------------------------------
| event_rates
|--------------------------------------------------------------------------
|
| Event rates in USD
|
|
*/

$config['event_rates'] = array(
	'MEM_RSRT_BEACH' => 1800000,
	'NON-MEM_RSRT_BEACH' => 1950000,
	'MEM_GOLF_VW' => 1700000,
	'NON-MEM_GOLF_VW' => 1850000,
	'MEM_NON_RESIDENT' => 1000000,
	'NON-MEM_NON_RESIDENT' => 1150000,
	'MEM_ACCOMPANYING_PERSON' => 300000,
	'NON-MEM_ACCOMPANYING_PERSON' => 300000,
);

$config['event_item_labels'] = array(
	'MEM_RSRT_BEACH' => 'Imperial Resort Beach Hotel',
	'NON-MEM_RSRT_BEACH' => 'Non Member - Imperial Resort Beach Hotel',
	'MEM_GOLF_VW' => 'Imperial Golf View Hotel',
	'NON-MEM_GOLF_VW' => 'Non Member - Imperial Golf View Hotel',
	'MEM_NON_RESIDENT' => 'Non Resident',
	'NON-MEM_NON_RESIDENT' => 'Non Member - Non Resident',
	'MEM_ACCOMPANYING_PERSON' => 'Accompanying Person',
	'NON-MEM_ACCOMPANYING_PERSON' => 'Non Member - Accompanying Person',
);



/*
|--------------------------------------------------------------------------
| local_rate_countries
|--------------------------------------------------------------------------
|
| Countries that get the local rate
|
|
*/

$config['local_rate_countries'] = array('Uganda','Kenya','Tanzania','Rwanda','Burundi');


/*
|--------------------------------------------------------------------------
| local_rate_accountancy_bodies
|--------------------------------------------------------------------------
|
| Accountancy bodies that get the local rate
|
|
*/

$config['local_rate_accountancy_bodies'] = array(
	"Ordre des Professionnels Comptables du Burundi (OPC)",
	"Institute of Certified Public Accountants of Kenya (ICPAK)",
	"Institute of Certified Public Accountants of Rwanda (ICPAR)",
	"National Board of Accountants and Auditors Tanzania (NBAA)",
	"Institute of Certified Public Accountants of Uganda (ICPAU)",
);


/*
|--------------------------------------------------------------------------
| cp_profile_id
|--------------------------------------------------------------------------
|
*/

$config['cp_profile_id'] = '4E5DEA68-B332-4F65-9F47-E9E49B4CE3C3';


/*
|--------------------------------------------------------------------------
| cp_access_key
|--------------------------------------------------------------------------
|
*/

$config['cp_access_key'] = 'a53f19a3f8293cca8320f54cb8c3f60e';

?>