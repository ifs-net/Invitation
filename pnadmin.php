<?php
/**
 * the main user function
 *
 * @return string HTML output string
 */
function invitation_admin_main()
{
    // Security check
    if (!SecurityUtil::checkPermission('invitation::', '::', ACCESS_COMMENT) || !pnUserLoggedIn()) {
        return LogUtil::registerPermissionError();
    }

    // Create output object
	$render = FormUtil::newpnForm('Invitation');

	// Load email language file
	pnModLangLoad('Invitation','email');
	
	// Assign variables
    $render->assign('sitename',			pnConfigGetVar('sitename'));
    $render->assign('baseurl',			pnGetBaseURL());
    $render->assign('uid',				pnUserGetVar('uid'));
    $render->assign('uname',			pnUserGetVar('uname'));
    $render->assign('invitationtext',	pnModGetVar('Invitation','invitationtext'));
		
	// Return output
	return $render->pnFormExecute('invitation_admin_main.htm', new invitation_admin_mainhandler());
}

/* ********** HANDLER CLASSES ********** */

class invitation_admin_mainhandler
{
    function initialize(&$render)
    {
      	// Nothing to initialize
		return true;
    }
    function handleCommand(&$render, &$args)
    {
		if ($args['commandName']=='update') {
		    if (!$render->pnFormIsValid()) return false;
		    $obj = $render->pnFormGetValues();
		    
			if (pnModSetVar('Invitation','invitationtext',$obj['invitationtext'])) {
			  	LogUtil::registerStatus(_INVITATION_PREFSUPDATED);
			}
			else {
			  	LogUtil::registerError(_INVITATION_PREFSUPDATEFAILURE);
			}
			return pnRedirect(pnModURL('Invitation','admin'));
		}
	return false;	
    }
}