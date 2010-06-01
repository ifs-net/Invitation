<?php
/**
 * @package      Invitation
 * @version      $Id$
 * @author       Florian Schießl
 * @link         http://www.ifs-net.de
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * This function is called internally by the core whenever the module is
 * loaded.  It adds in the information
 */
function Invitation_pntables()
{
    // Initialise table array
    $pntable = array();

    // Main Invitation column
    $pntable['invitation'] = DBUtil::getLimitedTablename('invitation');
    $pntable['invitation_column'] = array(
										'id'		=> 'id',
										'uid'       => 'uid',
										'email'     => 'email',
										'date'      => 'date'
										);
    $pntable['invitation_column_def'] = array(
										'id'		=> 'I AUTOINCREMENT PRIMARY',
										'uid'		=> "I NOTNULL DEFAULT '0'",
										'email'		=> "C(35) NOTNULL DEFAULT ''",
										'date'		=> 'T DEFAULT NULL');
    $pntable['invitation_column_idx'] = array (
										'uid'  		=> 'uid',
										'email'		=> 'email');
	// Successfull invitatione
    $pntable['invitation_cache'] = DBUtil::getLimitedTablename('invitation_cache');
    $pntable['invitation_cache_column'] = array(
    									'id'		=> 'id',
										'uid'		=> 'uid',
										'iuid'      => 'iuid'
										);
    $pntable['invitation_cache_column_def'] = array(
										'id'		=> 'I AUTOINCREMENT PRIMARY',
										'uid'		=> "I NOTNULL DEFAULT '0'",
										'iuid'		=> "I NOTNULL DEFAULT '0'");
    $pntable['invitation_cache_column_idx'] = array (
										'uid'  		=> 'uid',
										'iuid'		=> 'iuid');
    // Return the table information
    return $pntable;
}