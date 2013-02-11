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
 * This class provides the Api
 */

class Invitation_Api_User extends Zikula_AbstractApi
{

    /**
     * send invitation emails
     *
     * @param    array	$args['recipients']
     * @return   integer    number of invited users
     */
    public function invite($args)
    {
        // create view instance and assign Variables to output
        $view = Zikula_View::getInstance($this->name, false);
        $viewArgs=array(
            'sitename'      => System::getVar('sitename'),
            'baseurl'       => System::getBaseUrl(),
            'uid'           => UserUtil::getVar('uid'),
            'uname'         => UserUtil::getVar('uname'),
            'name'          => $args['name'],
            'text'          => $args['text'],
            'url'           => ModUtil::url($this->name, 'user', 'main', array(), null, null, true)
        );
        $view->assign($viewArgs);
        $htmlBody = $view->fetch('user/email_invite_html.htm');
        $plainTextBody = $view->fetch('user/email_invite_plain.htm');
        // Send message via Mailer module

        $subject = $this->__('Invitation from user').': '.$name;

        // get some db information
        $tables = DBUtil::getTables();
        $column = $tables['invitation_column'];
        $ucolumn = $tables['users_column'];

        // send mails now
        $c=0;
        foreach ($args['recipients'] as $email) {
            // did this user already recieve a mail from us?
            $where = "tbl.".$column['email']." = '".md5($email)."'";
            $uwhere = "tbl.".$ucolumn['email']." = '".$email."'";
            $invited = DBUtil::selectObjectCount('invitation',$where);
            $registered = DBUtil::selectObjectCount('users',$uwhere);
            if (($invited > 0) || ($registered > 0)) {
                LogUtil::registerStatus(__('Your invitation to the following email address was not sent because the user is already a registered user or another invitation has already be sent by another user:').' '.$email);
            } else {
                // send email
                $emailMessageSent = ModUtil::apiFunc('Mailer', 'user', 'sendMessage', array(
                    'toaddress' => $email,
                    'subject'   => $this->__f('%s sent an invitation for you', UserUtil::getVar('uname')),
                    'body'      => $htmlBody,
                    'altbody'   => $plainTextBody
                ));
                // Check if mail was sent successfully
                if (!$emailMessageSent) {
                    $this->registerError($this->__('Error! Unable to send e-mail message.'));
                }

                // insert entry into database 
                $obj = array (
                                'uid'	=> UserUtil::getVar('uid'),
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

        // We will return the numbers of sent invitations
        return $c;
    }

    /**
     * This function returns the html code to show who invited the person shown on a profile page (for example)
     *
     * @param	$args['uid']	int		user id
     * @return	output
     */
    public function getCode($args) {
        
        // Security check - show output only for users having the ACCESS_COMMENT permission
        if (!SecurityUtil::checkPermission('invitation::', '::', ACCESS_COMMENT) || !UserUtil::isLoggedIn()) {
            return "";
        }
        // get user's id whoose invited information should be displayed
        $uid = (int)$args['uid'];
        if (!($uid > 1)) {
            return "";
        }
        // first check: is there an entry in the cache?
        $obj = DBUtil::selectObjectByID('invitation_cache',$uid,'uid');
        if ( (!is_array($obj)) || (!(count($obj) > 0)) ) {
            // No Cache entry found
            // Check for invitation
            $email = UserUtil::getVar('email',$uid);
            $email = md5(strtolower($email));
            $invitation = DBUtil::selectObjectByID('invitation',$email,'email');
            if ( (!is_array($invitation)) || (!(count($invitation) > 0)) ) {
                    $iuid = -1;
                    $returnuid = -1;
            } else {
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
            // Automatically create ContactList link
            if (ModUtil::available('ContactList')) {
                $isBuddy = ModUtil::apiFunc('ContactList','user','isBuddy',array('uid1' => $returnuid, 'uid2' => $uid));
                if (!$isBuddy) {
                    ModUtil::apiFunc('ContactList','user','create',array('force' => 1, 'uid' => $returnuid, 'bid' => $uid));
                }
            }
        } else {
            // Cache entry found
            $returnuid = (int)$obj['iuid'];
        }
        if ($returnuid > 1) {
            // now get user data of once inviting person
            $uname = UserUtil::getVar('uname',$returnuid);
            if (!isset($uname) || ($uname == '')) {
                    return '';
            } else {
                // Create rendering instance
                $view = Zikula_View::getInstance($this->name, false);
                // Create data
                $data = array(
                    'inv_uname' => $uname,
                    'inv_uid' => $returnuid,
                    'inv_avatar' => UserUtil::getVar('_YOURAVATAR', $returnuid)
                );
                // Assign data to template
                $view->assign($data);
                // get Template
                $output = $view->display('user/plugin.htm');
                // return output. The output will be placed where the plugin was called
                return $output;
            }
        }
    }
}
