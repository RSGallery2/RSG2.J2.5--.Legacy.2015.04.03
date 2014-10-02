<?php
/**
* This file contains the install routine for RSGallery2
* @version $Id: install.rsgallery2.php 1011 2011-01-26 15:36:02Z mirjam $
* @package Rsg2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
**/

// no direct access
defined('_JEXEC') or die;

// /includes/defines.php
require_once (JPATH_BASE.'/includes/defines.php' );

// Include the JLog class.
// ToDo: USE: To check whether the Website is in the debug mode, test the JDEBUG variable:
if(JDEBUG)
{
	//whatever debugging code you want to run
}
jimport('joomla.log.log');

// Get the date for log file name
$date = JFactory::getDate()->format('Y-m-d');

// Add the logger.
JLog::addLogger(
     // Pass an array of configuration options
    array(
            // Set the name of the log file
            //'text_file' => substr($application->scope, 4) . ".log.php",
            'text_file' => 'rsg2.install.log.'.$date.'.php',

            // (optional) you can change the directory
            'text_file_path' => 'logs'
     ) 
);

// start logging...
JLog::add('-------------------------------------------------------');
JLog::add('Starting to log install.rsg2.php for installation');
		
class com_rsg2InstallerScript
{

	/*-------------------------------------------------------------------------
	preflight
	---------------------------------------------------------------------------
	This is where most of the checking should be done before install, update 
	or discover_install. Preflight is executed prior to any Joomla install, 
	update or discover_install actions. Preflight is not executed on uninstall. 
	A string denoting the type of action (install, update or discover_install) 
	is passed to preflight in the $type operand. Your code can use this string
	to execute different checks and responses for the three cases. 
	-------------------------------------------------------------------------*/

// ToDO: #__schemas" Tabelle reparieren ??? -> http://vi-solutions.de/de/enjoy-joomla-blog/116-knowledgbase-tutorials

	function preflight($type, $parent)
	{
		JLog::add('preflight: '.$type);

		// this component does not work with Joomla releases prior to 3.0
		// abort if the current Joomla release is older
		$jversion = new JVersion();
		
		// Installing component manifest file version
		$this->newRelease = $parent->get( "manifest" )->version;
		$this->oldRelease = $this->getParam('version');

        // Manifest file minimum Joomla version
        $this->minimum_joomla_release = $parent->get( "manifest" )->attributes()->version;   
		$this->actual_joomla_release = $jversion->getShortVersion();
//'<p>' . '</p>'
        // Show the essential information at the install/update back-end
        echo '<br/>Installing component manifest file version = ' . $this->newRelease;
        JLog::add('Installing component manifest file version = ' . $this->newRelease);
        if ( $type == 'update' ) {
			echo '<br/>Old/current component version (manifest cache) = ' . $this->oldRelease;
			JLog::add('Old/current component version (manifest cache) = ' . $this->oldRelease);
		}
        //echo 'Installing component manifest file minimum Joomla version = ' . $this->minimum_joomla_release;
        JLog::add('<br/>Installing component manifest file minimum Joomla version = ' . $this->minimum_joomla_release);
        //echo 'Current Joomla version = ' . $this->actual_joomla_release; 
        JLog::add('<br/>Current Joomla version = ' . $this->actual_joomla_release);
 
        // Abort if the current Joomla release is older
        if (version_compare( $this->actual_joomla_release, $this->minimum_joomla_release, 'lt' )) {
            echo '    Installing component manifest file minimum Joomla version = ' . $this->minimum_joomla_release;
            echo '    Current Joomla version = ' . $this->actual_joomla_release;
            Jerror::raiseWarning(null, 'Cannot install com_rsg2 in a Joomla release prior to '.$this->minimum_joomla_release);
            return false;
        }

		JLog::add('After version compare');

        if ( $type == 'update' ) {
		
			JLog::add('-> pre update');
			$rel = $this->oldRelease . ' to ' . $this->newRelease;
			
			// abort if the component being installed is not newer or not equal (overwrite) than the currently installed version
			//if ( version_compare( $this->release, $oldRelease, 'le' ) ) {
			if ( version_compare( $this->newRelease, $this->oldRelease, 'lt' ) ) {
					Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
					return false;
			}
			/**/

			// ??? Install options ?

			echo '<br/>' . JText::_('COM_RSG2_PREFLIGHT_UPDATE_TEXT') . ' ' . $rel;		
        }
        else 
		{ // $type == 'install'
			JLog::add('-> pre freshInstall');
			$rel = $this->newRelease; 
			
			// Remove accidentally left overs (Image Files or Database) -> uncomment for use
			// Only for developers use !!!
			// RemoveManualInstallationParts ()			
			
			// ??? Install options ?

			echo '<br/>' . JText::_('COM_RSG2_PREFLIGHT_INSTALL_TEXT') . ' ' . $rel;		
		}

 		JLog::add('exit preflight');
	}

	/*-------------------------------------------------------------------------
	install
	---------------------------------------------------------------------------
	Install is executed after the Joomla install database scripts have 
	completed. Returning 'false' will abort the install and undo any changes 
	already made. It is cleaner to abort the install during preflight, if 
	possible. Since fewer install actions have occurred at preflight, there 
	is less risk that that their reversal may be done incorrectly. 
	-------------------------------------------------------------------------*/
	function install($parent)
	{
		JLog::add('install');

		// Path definitions
		// may not be needed change it
		// require_once( JPATH_SITE . '/administrator/components/com_rsg2/classes/rsg2_Paths.php' );
	
		// Remove accidentally left overs (Image files or database tables) -> uncomment for use
		// Only for developers use !!!
//		RemoveManualInstallationParts ();
				
		JLog::add('freshInstall');
        //echo '<h2>'.JText::_('COM_RSG2_FRESH_INSTALL').'</h2>';
        echo '<b>'.JText::_('COM_RSG2_FRESH_INSTALL').'</b>';

		//--- Create new directories -----------------------------
		
		/*
		$imgPaths = new rsg2_Paths ();
		JLog::add('rsg2_Paths: ' . $imgPaths.text());
        
		$imgPaths.createRsg2ImagePaths ();
		$imgPaths.createIndexHtmlFilesInImagePaths ();
		*/
		
		//--- Handle config -----------------------------
		
        // save config to populate database with default config values
        // $rsgConfig->saveConfig();

		JLog::add('exit install');
	}

	/*-------------------------------------------------------------------------
	update
	---------------------------------------------------------------------------
	Update is executed after the Joomla update database scripts have completed. 
	Returning 'false' will abort the update and undo any changes already made. 
	It is cleaner to abort the update during preflight, if possible. Since 
	fewer update actions have occurred at preflight, there is less risk that 
	that their reversal may be done incorrectly. 
	-------------------------------------------------------------------------*/
	function update($parent)
	{
		JLog::add('function update');
		

		JLog::add('view update text');
		echo '<p>' . JText::_('COM_RSG2_UPDATE_TEXT') . '</p>';

		JLog::add('exit update');
	}

	/*-------------------------------------------------------------------------
	postflight
	---------------------------------------------------------------------------
	Postflight is executed after the Joomla install, update or discover_update 
	actions have completed. It is not executed after uninstall. Postflight is 
	executed after the extension is registered in the database. The type of 
	action (install, update or discover_install) is passed to postflight in 
	the $type operand. Postflight cannot cause an abort of the Joomla 
	install, update or discover_install action. 
	-------------------------------------------------------------------------*/
	function postflight($type, $parent)
	{
		JLog::add('postflight');
		echo '<p>' . JText::_('COM_RSG2_POSTFLIGHT_' . strtoupper($type) . '_TEXT') . '</p>';

        if ( $type == 'update' ) {
			JLog::add('-> post update');
			
			$this->installComplete(JText::_('COM_RSG2_UPGRADE_SUCCESS'));
        }
        else 
		{ // $type == 'install'
			JLog::add('-> post freshInstall');
			
			$this->installComplete(JText::_('COM_RSG2_INSTALLATION_OF_RSGALLERY_IS_COMPLETED'));
		}
 
		JLog::add('exit postflight');
	}

	/*-------------------------------------------------------------------------
	uninstall
	---------------------------------------------------------------------------
	The uninstall method is executed before any Joomla uninstall action, 
	such as file removal or database changes. Uninstall cannot cause an 
	abort of the Joomla uninstall action, so returning false would be a 
	waste of time
	-------------------------------------------------------------------------*/
	function uninstall($parent)
	{
		JLog::add('uninstall');
		echo '<p>' . JText::_('COM_RSG2_UNINSTALL_TEXT') . '</p>';
		JLog::add('exit uninstall');
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_rsg2"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}

	/*
	 * sets parameter values in the component's row of the extension table
	 */
	function setParams($param_array) {
		if ( count($param_array) > 0 ) {
			// read the existing component value(s)
			$db = JFactory::getDbo();
			$db->setQuery('SELECT params FROM #__extensions WHERE name = "com_rsg2"');
			$params = json_decode( $db->loadResult(), true );
			// add the new variable(s) to the existing one(s)
			foreach ( $param_array as $name => $value ) {
					$params[ (string) $name ] = (string) $value;
			}
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode( $params );
			$db->setQuery('UPDATE #__extensions SET params = ' .
					$db->quote( $paramsString ) .
					' WHERE name = "com_rsg2"' );
					$db->execute();
		}
	}
	
	function RemoveManualInstallationParts () {
	
		//--- Delete images and directories if exist ----------------
		//$imgPaths = new rsg2_Paths ();
		//JLog::add('rsg2_Paths: ' . $imgPaths.text());
        
		// $imgPaths.deleteRsg2ImagePaths ():

		//--- Delete database tables ----------------------
		/*foreach ($this->tablelistNew as $table)
		{
			$this->deleteTable($table);
		}        
		*/
	}
	
     /**
      * Shows the "Installation complete" box with a link to the control panel
      */
    function installComplete($msg = null){
		if($msg == null) 
		{
			$msg = JText::_('COM_RSG2_INSTALLATION_OF_RSGALLERY_IS_COMPLETED', $this->newRelease);
		}
		// $icon = preg_replace('#\.[^.]*$#', '', $icon);
		// $icon = preg_replace('#\.[^.]*$#', '', JUri::base().'components/com_rsg2/images/icon-48-config.png');
		$icon = str_replace('\\', '/', JUri::base().'components/com_rsg2/images/icon-48-config.png');
		?>
		<br/>Icon: <?php echo $icon; ?> <br/>
		<br/>
		<div align="center">
			<table width="500"><tr><td>
				<table class="adminlist" border="1">
				<tr>
					<td colspan="2">
						<div align="center">
							<h2><?php echo $msg; ?></h2> 
							<?php echo JText::_('COM_RSG2_INSTALL_STATUS_MSGS'); ?>
							<br>
							<a href="index.php?option=com_rsg2">
								<img src="<?php echo $icon;?>" alt=" <?php echo JText::_('COM_RSG2_CONTROL_PANEL') ?>" width="48" height="48" border="0">
								<h2>
									<?php echo JText::_('COM_RSG2_CONTROL_PANEL'); ?>
								</h2>
							</a>
						</div>
					</td>
				</tr>
			</table>
			</td></tr></table>
			
			<br/>
			<?php echo "JPATH_ADMINISTRATOR".": ".JPATH_ADMINISTRATOR; ?><br/>
			<?php echo "JPATH_BASE".": ".JPATH_BASE; ?><br/>
			<?php echo "JPATH_CACHE".": ".JPATH_CACHE; ?><br/>
			<?php echo "JPATH_COMPONENT".": ".JPATH_COMPONENT; ?><br/>
			<?php echo "JPATH_COMPONENT_ADMINISTRATOR".": ".JPATH_COMPONENT_ADMINISTRATOR; ?><br/>
			<?php echo "JPATH_COMPONENT_SITE".": ".JPATH_COMPONENT_SITE; ?><br/>
			<?php echo "JPATH_CONFIGURATION".": ".JPATH_CONFIGURATION; ?><br/>
			<?php echo "JPATH_INSTALLATION".": ".JPATH_INSTALLATION; ?><br/>
			<?php echo "JPATH_LIBRARIES".": ".JPATH_LIBRARIES; ?><br/>
			<?php echo "JPATH_PLUGINS".": ".JPATH_PLUGINS; ?><br/>
			<?php echo "JPATH_ROOT".": ".JPATH_ROOT; ?><br/>
			<?php echo "JPATH_SITE".": ".JPATH_SITE; ?><br/>
			<?php echo "JPATH_THEMES".": ".JPATH_THEMES; ?><br/>
			<!-- ?php echo "JPATH_XMLRPC".": ".JPATH_XMLRPC; ?><br/ -->	
		</div>
        <?php
    }
	 
	
	
	
	
}