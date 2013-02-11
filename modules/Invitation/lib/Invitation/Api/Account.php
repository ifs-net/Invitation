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
 * This class provides information for the user account panel
 */

class Invitation_Api_Account extends Zikula_AbstractApi
{

    /**
     * Return an array of items that should be shown on the user's settings pannel *
     *
     * @return array 
     */ 

    public function getall()
    {
        $items = array(
            array(
                'url' => ModUtil::url($this->name,'user','main'),
                'title' => $this->__('Invite friends'),
                'icon' => 'admin.png' 
                )
            );
        // Return the items
        return $items;
    }
}