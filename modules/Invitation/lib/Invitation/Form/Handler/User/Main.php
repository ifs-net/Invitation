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

class Invitation_Form_Handler_User_Main extends Zikula_Form_AbstractHandler
{

    public function initialize(Zikula_Form_View $view) {
        // Nothing to initialize
        $this->view->assign('sitename', System::getVar('sitename'));
        return true;
    }
    public function handleCommand(Zikula_Form_View $view, &$args) {
        // Get data from form
        $formData = $this->view->getValues();

        // Check for action
        if ($args['commandName'] == 'cancel') {
            // Just set a status message
            LogUtil::registerStatus($this->__('Action aborted'));
        } else if ($args['commandName'] == 'invite') {

            // Validate input
            if (!$this->view->isValid()) {
                return false;
            }

            // Construct recipient array
            $recipients[]=$formData['email'];
            for ($i=2;$i<9;$i++) {
                if ($formData['email'.$i] != '') {
                    $recipients[]=$formData['email'.$i];
                }
            }

            // Send email and write status message
            $c = ModUtil::apiFunc('Invitation','user','invite',array(
                    'recipients'    => $recipients,
                    'text'          => $formData['text'],
                    'name'          => $formData['name']
                    )
                    );
            if ($c >= 0) {
                LogUtil::registerStatus($c." ".$this->__('Invitation emails sent!'));
            } else {
                LogUtil::registerError($this->__('Invitation mails could not be sent!'));
            }
            // Return main page with not outfilled form
 //           return true;
            return System::redirect(ModUtil::url($this->name));
        }
    }
}