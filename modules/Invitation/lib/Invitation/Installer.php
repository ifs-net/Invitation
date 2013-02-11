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


/**
 * initialise the module
 *
 * @return       bool       true on success, false otherwise
 */

 class Invitation_Installer extends Zikula_AbstractInstaller
 {

    /**
     * initialise the Invitation module
     * This function is only ever called once during the lifetime of a particular
     * module instance
     *
     * @return bool true if successful, false otherwise
     */
    public function install()
    {
        // create the three tables
        $tables = array(
            'invitation',
            'invitation_cache'
            );
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
    public function upgrade($oldversion)
    {
        // to be filled later... perhaps :-)
        switch ($oldversion) {
            case '1.0':
            case '1.1':
            case '1.2':
            case '2.0':
            case '2.1':
            default:
                return true;
        }
    }

    /**
     * delete the Invitation module
     * This function is only ever called once during the lifetime of a particular
     * module instance
     * @return bool true if successful, false otherwise
     */
    public function uninstall()
    {
        // drop the three tables for the module
        $tables = array(
            'invitation',
            'invitation_cache'
            );
        foreach ($tables as $table) {
            if (!DBUtil::dropTable($table)) {
                return false;
            }
        }

        // delete all module vars
        $this->delVars();

        // Delete successful
        return true;
    }
}
