<?php
/**
 * @package      Invitation
 * @version      $Id$
 * @author       Florian Schießl
 * @link         http://www.ifs-net.de
 * @copyright    Copyright (C) 2010
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

function Invitation_pntables()
{
    // Initialise table array
    $tables = array();

    // Tables first..
    $tables['invitation']       = DBUtil::getLimitedTablename('invitation');
    $tables['invitation_cache'] = DBUtil::getLimitedTablename('invitation_cache');
    
    // Table to store hash for Email address (potential new users)
    $tables['invitation_column'] = array(
										'id'		=> 'id',
										'uid'       => 'uid',
										'email'     => 'email',
										'date'      => 'date'
										);
    $tables['invitation_column_def'] = array(
										'id'		=> 'I AUTOINCREMENT PRIMARY',
										'uid'		=> "I NOTNULL DEFAULT '0'",
										'email'		=> "C(35) NOTNULL DEFAULT ''",
										'date'		=> 'T DEFAULT NULL');
    $tables['invitation_column_idx'] = array (
										'uid'  		=> 'uid',
										'email'		=> 'email');
	// Table for successful invitations
    $tables['invitation_cache_column'] = array(
    									'id'		=> 'id',
										'uid'		=> 'uid',
										'iuid'      => 'iuid'
										);
    $tables['invitation_cache_column_def'] = array(
										'id'		=> 'I AUTOINCREMENT PRIMARY',
										'uid'		=> "I NOTNULL DEFAULT '0'",
										'iuid'		=> "I NOTNULL DEFAULT '0'");
    $tables['invitation_cache_column_idx'] = array (
										'uid'  		=> 'uid',
										'iuid'		=> 'iuid');
    // Return the table information
    return $tables;
}
