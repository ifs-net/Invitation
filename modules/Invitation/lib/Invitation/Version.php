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

class Invitation_Version extends Zikula_AbstractVersion {

    public function getMetaData() {

        $meta = array();
        $meta['displayname'] = $this->__('Invitation'); 
        $meta['description'] = $this->__("Let your users invite others to your community"); 
        //! module name that appears in URL 
        $meta['url'] = $this->__('Invitation'); 
        $meta['version'] = '3.0.0'; 
        $meta['securityschema'] = array('Invitation::' => '::'); 
        return $meta;
    }
}