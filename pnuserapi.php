<?php
/**
 * send invitation emails
 *
 * @param    array	$args['recipients']
 * @return   bool	
 */
function Invitation_userapi_invite($args)
{
  	$recipients = $args['recipients'];
  	$name		= $args['name'];
  	$text		= $args['text'];
	// Load email language file
	pnModLangLoad('Invitation','email');
    // Optional arguments.
    $render = pnRender::getInstance('Invitation');
    $render->assign('sitename',			pnConfigGetVar('sitename'));
    $render->assign('baseurl',			pnGetBaseURL());
    $render->assign('uid',				pnUserGetVar('uid'));
    $render->assign('uname',			pnUserGetVar('uname'));
    $render->assign('name',				$name);
    $render->assign('text',				$text);
    $render->assign('invitationtext',	pnModGetVar('Invitation','invitationtext'));
    $body = $render->fetch('invitation_email_invite.htm');
    $subject = _INVITATION_INVITATIONFROM.': '.$name;
    $result = true;
	// get some db information
   	$tables 	= pnDBGetTables();
   	$column 	= $tables['invitation_column'];
   	$ucolumn 	= $tables['users_column'];
   	// send mails now
   	$c=0;
    foreach ($recipients as $email) {
      	// did this user already recieve a mail from us?
      	$where = "tbl.".$column['email']." = '".md5($email)."'";
      	$uwhere = "tbl.".$ucolumn['email']." = '".$email."'";
      	$invited = DBUtil::selectObjectCount('invitation',$where);
      	$registered = DBUtil::selectObjectCount('users',$uwhere);
      	if (($invited > 0) || ($registered > 0)) {
			LogUtil::registerStatus(_INVITATION_ALREADYREGISTERED.' '.$email);
		}
		else {
		  	// send email
		  	pnMail($email, $subject, $body, array('header' => '\nMIME-Version: 1.0\nContent-type: text/plain'), false);
		  	// insert entry into database 
		  	$obj = array (
		  			'uid'	=> pnUserGetVar('uid'),
		  			'email'	=> md5(strtolower($email)),
		  			'date'	=> date("Y-m-d H:i:s")
			  	);
			DBUtil::insertObject($obj,'invitation');
			// Increase Counter
		  	$c++;
		}
	}
	// We will delete old invitation requests (older than 120 days) now..
	$where = "date < '".date("Y-m-d H:i:s",(time()-60*60*24*120))."'";
	DBUtil::deleteWhere('invitation',$where);
    return $c;
}

/**
 * This function returns the html code to show who invited the person shown on a profile page (for example)
 *
 * @param	$args['uid']	int		user id
 * @return	output
 */
function Invitation_userapi_getCode($args) {
    // Security check - show output only for users having the ACCESS_COMMENT permission
    if (!SecurityUtil::checkPermission('invitation::', '::', ACCESS_COMMENT) || !pnUserLoggedIn()) {
        return "";
    }
    // get user's id whoose invited information should be displayed
  	$uid = (int)$args['uid'];
  	if (!($uid > 1)) return "";
  	// first check: is there an entry in the cache?
  	$obj = DBUtil::selectObjectByID('invitation_cache',$uid,'uid');
  	if ( (!is_array($obj)) || (!(count($obj) > 0)) ) {
	    // No Cache entry found
	    // Check for invitation
  		$email = pnUserGetVar('email',$uid);
  		$email = md5(strtolower($email));
   		$invitation = DBUtil::selectObjectByID('invitation',$email,'email');
  		if ( (!is_array($invitation)) || (!(count($invitation) > 0)) ) {
			$iuid = -1;
			$returnuid = -1;
		}
		else {
			$returnuid = $invitation['uid'];
		    // delete old invitation entry from database to increase performance
		    DBUtil::deleteObject($invitation,'invitation');
		}
		// write dummy (-1) or real entry into cache so next time
		// there will be a result with the first query!
	  	$resultobj = array (
	  			'uid'	=> $uid,
	  			'iuid'	=> $returnuid
		  	);
	    DBUtil::insertObject($resultobj,'invitation_cache');
	}
  	else {
  	  	// Cache entry found
	    $returnuid = (int)$obj['iuid'];
	}
	if ($returnuid > 1) {
		// now get user data of once inviting person
		$uname = pnUserGetVar('uname',$returnuid);
		if (!isset($uname) || ($uname == '')) {
		  	return '';
		}
		else {
		  	pnModLangLoad('Invitation','user');
		  	$render = pnRender::getInstance('Invitation');
		  	$render->assign('inv_uname',	$uname);
		  	$render->assign('inv_uid',		$returnuid);
		  	$render->assign('inv_avatar',	pnUserGetVar('_YOURAVATAR',$returnuid));
		  	$output = $render->display('invitation_user_plugin.htm');
			return $output;
		}
	}
}