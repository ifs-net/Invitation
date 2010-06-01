<?php
/**
* @package AboutMe?
* @version $Id: pnaccountapi.php 4 2009-01-25 14:49:14Z quan $
* @author Florian Schießl
* @link  http://www.ifs-net.de
* @copyright Copyright (C) 2010
* @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
*/

/**

    * Return an array of items that should be shown on the user's settings pannel *
    * @param uname string
    * @return array */ 

function Invitation_accountapi_getall($args)
{
    $items = null;
    // only show the options to logged in users of course!
    if (!pnUserLoggedIn()) return $items;

    // Language Domain
    $dom = ZLanguage::getModuleDomain('Invitation');

    if (SecurityUtil::checkPermission('Invitation::','::', ACCESS_COMMENT)) {

        pnModLangLoad('Invitation','user');
        $items = array(array(

            'url' => pnModURL('Invitation','user','main'),
            'title' => __('Invite friends',$dom),
            'icon' => 'admin.png' ));

    }
    return $items;
}