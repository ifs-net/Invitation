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

class Invitation_Controller_Admin extends Zikula_AbstractController
{
    /**
     * the main user function
     *
     * @return string HTML output string
     */
    public function main()
    {
        // Security check
        $this->throwForbiddenUnless(SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN));

        // Create new Form reference
        $view = FormUtil::newForm($this->name, $this);

        // Execute form using supplied template and page event handler
        return $view->execute('admin/main.htm', new Invitation_Form_Handler_Admin_Main());
    }
}
