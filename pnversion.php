<?php
/**
 * @package      Invitation
 * @version      $Id$
 * @author       Florian Schie�l
 * @link         http://www.ifs-net.de
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

$dom = ZLanguage::getModuleDomain('Invitation');

$modversion['name']         = 'Invitation';
$modversion['displayname']  = 'Invitation';
$modversion['description']  = __('Let your users invite others to your community!',$dom);
$modversion['version']      = '1.3';
$modversion['credits']      = 'pndocs/credits.txt';
$modversion['help']         = 'pndocs/help.txt';
$modversion['changelog']    = 'pndocs/changelog.txt';
$modversion['license']      = 'pndocs/license.txt';
$modversion['official']     = 0;
$modversion['admin']        = 1;
$modversion['author']       = 'Florian Schie�l';
$modversion['contact']      = 'http://www.ifs-net.de';
// module dependencies
$modversion['dependencies'] = array(
	array(	'modname'    => 'MyProfile',
			'minversion' => '2.2', 'maxversion' => '',
            'status'     => PNMODULE_DEPENDENCY_RECOMMENDED),
	array(	'modname'    => 'ContactList',
			'minversion' => '1.6', 'maxversion' => '',
            'status'     => PNMODULE_DEPENDENCY_RECOMMENDED)
);