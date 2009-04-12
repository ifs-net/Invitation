<?php
/**
 * @package      Invitation
 * @version      $Id$
 * @author       Florian Schießl
 * @link         http://www.ifs-net.de
 * @copyright    Copyright (C) 2008
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * initialise the Invitation module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 *
 * @return bool true if successful, false otherwise
 */
function Invitation_init()
{
    // create the three tables
    $tables = array('invitation','invitation_cache');
    foreach ($tables as $table) {
        if (!DBUtil::createTable($table)) {
            return false;
        }
    }

    // Initialisation successful
    return true;
}

/**
 * upgrade the Invitation module from an old version
 * This function can be called multiple times
 * @return bool true if successful, false otherwise
 */
function Invitation_upgrade($oldversion)
{
  	// to be filled later... perhaps :-)
  	switch ($oldversion) {
	    case '1.1':
	    case '1.0':
	    	return true;
	    default:
		    return false;
	}
}

/**
 * delete the Invitation module
 * This function is only ever called once during the lifetime of a particular
 * module instance
 * @return bool true if successful, false otherwise
 */
function Invitation_delete()
{
    // drop the three tables for the module
    $tables = array('invitation','invitation_cache');
    foreach ($tables as $table) {
        if (!DBUtil::dropTable($table)) {
            return false;
        }
    }

    // delete all module vars
    pnModDelVar('Invitation');

    // Delete successful
    return true;
}