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
 * the main user function
 *
 * @return string HTML output string
 */
function invitation_user_main()
{
    // Security check
    if (!SecurityUtil::checkPermission('invitation::', '::', ACCESS_COMMENT) || !pnUserLoggedIn()) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
	$render = FormUtil::newpnForm('Invitation');

	// Return output
	return $render->pnFormExecute('invitation_user_main.htm', new invitation_user_mainhandler());
}

/* ********** HANDLER CLASSES ********** */

class invitation_user_mainhandler
{
    function initialize(&$render)
    {
      	// Nothing to initialize
		return true;
    }
    function handleCommand(&$render, &$args)
    {
        // Language Domain
        $dom = ZLanguage::getModuleDomain('Invitation');
		if ($args['commandName']=='invite') {
		    if (!$render->pnFormIsValid()) return false;
		    $obj = $render->pnFormGetValues();

			// Construct recipient array
			$recipients[]=$obj['email'];
			for ($i=2;$i<9;$i++) {
			  	if ($obj['email'.$i] != '') {
				    $recipients[]=$obj['email'.$i];
				}
			}
			
            // Send email and write status message
            $c = pnModAPIFunc('Invitation','user','invite',array(
					'recipients'	=> $recipients,
					'text' 			=> $obj['text'],
					'name'			=> $obj['name']
				));
			if ($c >= 0) {
			  	LogUtil::registerStatus($c." ".__('Invitation emails sent!',$dom));
			}
			else LogUtil::registerError(__('Invitation mails could not be sent!'));
			
			// Return main page with not outfilled form
			return pnRedirect(pnModURL('Invitation'));
		}
	return false;	
    }
}