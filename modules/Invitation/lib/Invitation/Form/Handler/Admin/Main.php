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
class Invitation_Form_Handler_Admin_Main extends Zikula_Form_AbstractHandler
{
    public function initialize(Zikula_Form_View $view)
    {
        // Assign some variables to the template
        $this->view->assign('sitename', System::getVar('sitename'));
        $this->view->assign('baseurl', System::getBaseUrl());
        $this->view->assign('uid', UserUtil::getVar('uid'));
        $this->view->assign('uname', UserUtil::getVar('uname'));
        $this->view->assign('invitationtext_html', ModUtil::getVar('Invitation','invitationtext_html'));
        $this->view->assign('invitationtext_plain', ModUtil::getVar('Invitation','invitationtext_plain'));
        $this->view->assign('text', $this->__('This is a default text for the preview function'));
        // That's all for now...
        return true;
    }

    public function handleCommand(Zikula_Form_View $view, &$args) {
        // Get data from form
        $formData = $this->view->getValues();
        
        // Validate input
        if (!$this->view->isValid()) {
            return false;
        }

        // Check for action
        if ($args['commandName'] == 'update') {

            // Store module variables
            if ((ModUtil::setVar('Invitation','invitationtext_plain',$formData['invitationtext_plain'])) && (ModUtil::setVar('Invitation','invitationtext_html',$formData['invitationtext_html']))) {
                LogUtil::registerStatus($this->__('Settings updated!'));
            } else {
                LogUtil::registerError($this->__('Settings could not be updated!'));
            }
            return System::redirect(ModUtil::url($this->name,'admin'));
        } else if ($args['commandName'] == 'cancel') {
            LogUtil::registerStatus($this->__('Action aborted'));
        }
        return false;
    }    
}