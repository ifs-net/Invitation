<?php

$modversion['name']           = 'Invitation';
$modversion['displayname']    = 'Invitation';
$modversion['description']    = 'Invitation module';
$modversion['version'] = '1.0';
$modversion['credits'] = 'pndocs/credits.txt';
$modversion['help'] = 'pndocs/help.txt';
$modversion['changelog'] = 'pndocs/changelog.txt';
$modversion['license'] = 'pndocs/license.txt';
$modversion['official'] = 0;
$modversion['author'] = 'Florian Schießl';
$modversion['contact'] = 'http://www.ifs-net.de';
// module dependencies
$modversion['dependencies'] = array(
	array(	'modname'    => 'MyProfile',
			'minversion' => '1.1', 'maxversion' => '',
            'status'     => PNMODULE_DEPENDENCY_RECOMMENDED)
            );