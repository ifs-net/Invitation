<?php
/**
 * Invitation
 *
 * @copyright Florian Schießl
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package Invitation
 * @author Florian Schießl <info@ifs-net.de>.
 * @link http://www.ifs-net.de
 */


function Invitation_tables()
{
    // Initialise table array
    $tables = array();

    // Tables first..
    $tables['invitation']       = 'invitation';
    $tables['invitation_cache'] = 'invitation_cache';
    
    // Table to store hash for Email address (potential new users)
    $tables['invitation_column'] = array(
        'id'    => 'id',
	'uid'   => 'uid',
	'email' => 'email',
	'date'  => 'date'
	);
    $tables['invitation_column_def'] = array(
        'id'    => 'I AUTOINCREMENT PRIMARY',
        'uid'   => "I NOTNULL DEFAULT '0'",
        'email' => "C(35) NOTNULL DEFAULT ''",
        'date'  => 'T DEFAULT NULL'
        );
    $tables['invitation_column_idx'] = array (
        'uid'   => 'uid',
        'email' => 'email'
        );

    // Table for successful invitations
    $tables['invitation_cache_column'] = array(
        'id'    => 'id',
        'uid'   => 'uid',
        'iuid'  => 'iuid'
        );
    $tables['invitation_cache_column_def'] = array(
        'id'    => 'I AUTOINCREMENT PRIMARY',
        'uid'   => "I NOTNULL DEFAULT '0'",
        'iuid'  => "I NOTNULL DEFAULT '0'"
        );
    $tables['invitation_cache_column_idx'] = array (
        'uid'   => 'uid',
        'iuid'  => 'iuid'
        );

    // Return the table information
    return $tables;
}
