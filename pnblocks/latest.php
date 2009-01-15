<?php
/**
 * initialise block
 * 
 */
function Invitation_latestblock_init()
{
    // Security
    pnSecAddSchema('Invitation:latestblock:', 'Block title::');
}

/**
 * get information on block
 * 
 * @return       array       The block information
 */
function Invitation_latestblock_info()
{
    return array('text_type'      => 'latest',
                 'module'         => 'Invitation',
                 'text_type_long' => 'Show new invited users',
                 'allow_multiple' => true,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}

/**
 * display block
 * 
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
function Invitation_latestblock_display($blockinfo)
{
    // Check if the Invitation module is available. 
    if (!pnModAvailable('Invitation')) return false;

    // Security check
    if (!SecurityUtil::checkPermission('Invitation:birthdayblock', "$blockinfo[title]::", ACCESS_READ)) return false;
    
    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    $numitems=$vars['numitems'];
    
    if (!isset($numitems)) $numitems = 10;

    // Create output object
    $render =  pnRender::getInstance('Invitation');
	
    $render->caching = true;
    $render->cache_lifetime = 3600; // cache for 1 hour

	$tables = pnDBGetTables();
	$column = $tables['invitation_cache_column'];
	$where = "tbl.".$column['iuid']." > 0";
	$orderby = "tbl.".$column['id']." DESC";

	// Table join information to join userpictures table with users table to retrieve the usernames
	// This join information is the second join information so we have to use the prefix a. in the following where parts
	$joinInfo = array();
	$joinInfo[] = array (	'join_table'          =>  'users',			// table for the join
							'join_field'          =>  'uname',			// field in the join table that should be in the result with
                         	'object_field_name'   =>  'uname',			// ...this name for the new column
                         	'compare_field_table' =>  'uid',			// regular table column that should be equal to
                         	'compare_field_join'  =>  'uid');			// ...the table in join_table
	$joinInfo[] = array (	'join_table'          =>  'users',			// table for the join
							'join_field'          =>  'uname',			// field in the join table that should be in the result with
                         	'object_field_name'   =>  'iuname',			// ...this name for the new column
                         	'compare_field_table' =>  'iuid',			// regular table column that should be equal to
                         	'compare_field_join'  =>  'uid');			// ...the table in join_table


    $items=DBUtil::selectExpandedObjectArray('invitation_cache',$joinInfo,$where,$orderby,0,($numitems));
    if (count($items)== 0) {
	  	return false;
	}
    $render->assign('items', $items);
    // Populate block info and pass to theme
    $blockinfo['content'] = $render->fetch('invitation_block_latest.htm');

    return themesideblock($blockinfo);
}


/**
 * modify block settings
 * 
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function Invitation_latestblock_modify($blockinfo)
{
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // Defaults
    if (empty($vars['numitems'])) {
        $vars['numitems'] = 10;
    }

	// Load language
	pnModLangLoad('Invitation','latest');

    // Create output object
    $render = pnRender::getInstance('Invitation');

    // As Admin output changes often, we do not want caching.
    $render->caching = false;

    // assign the approriate values
    $render->assign('numitems', $vars['numitems']);

    // Return the output that has been generated by this function
    return $render->fetch('invitation_block_latest_modify.htm');
}


/**
 * update block settings
 * 
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function Invitation_latestblock_update($blockinfo)
{
  	return Invitation_latestblock_display();
}

?>