<?php
/**
* This file contains the install routine for RSGallery2
* @version $Id: install.rsgallery2.php 1011 2011-01-26 15:36:02Z mirjam $
* @package RSGallery2
* @copyright (C) 2003 - 2006 RSGallery2
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* RSGallery is Free Software
**/

// no direct access
defined('_JEXEC') or die;

// Get the date for log file name
$date = JFactory::getDate()->format('Y-m-d');

// Include the JLog class.
jimport('joomla.log.log');

// Add the logger.
JLog::addLogger(
     // Pass an array of configuration options
    array(
            // Set the name of the log file
            //'text_file' => substr($application->scope, 4) . ".log.php",
            'text_file' => 'rsgallery2.install.log.'.$date.'.php',

            // (optional) you can change the directory
            'text_file_path' => 'logs'
     ) 
);

// start logging...
JLog::add('-------------------------------------------------------');
JLog::add('Starting to log install.rsgallery2.php for installation');
		
class com_rsgallery2InstallerScript
{

	// ToDo: use information on links and use it on all following functions
	// http://docs.joomla.org/J2.5:Managing_Component_Updates_%28Script.php%29

	// http://www.joomla-wiki.de/dokumentation/Joomla!_Programmierung/Programmierung/Aktualisierung_einer_Komponente/Teil_3

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

        // Show the essential information at the install/update back-end
        echo 'Installing component manifest file version = ' . $this->newRelease;
        JLog::add('Installing component manifest file version = ' . $this->newRelease);
        if ( $type == 'update' ) {
			echo 'Old/current component version (manifest cache) = ' . $this->oldRelease;
			JLog::add('Old/current component version (manifest cache) = ' . $this->oldRelease);
		}
        //echo 'Installing component manifest file minimum Joomla version = ' . $this->minimum_joomla_release;
        JLog::add('Installing component manifest file minimum Joomla version = ' . $this->minimum_joomla_release);
        //echo 'Current Joomla version = ' . $this->actual_joomla_release;
        JLog::add('Current Joomla version = ' . $this->actual_joomla_release);
 
       // Abort if the current Joomla release is older
        if (version_compare( $this->actual_joomla_release, $this->minimum_joomla_release, 'lt' )) {
            echo '    Installing component manifest file minimum Joomla version = ' . $this->minimum_joomla_release;
            echo '    Current Joomla version = ' . $this->actual_joomla_release;
            Jerror::raiseWarning(null, 'Cannot install com_rsgallery2 in a Joomla release prior to '.$this->minimum_joomla_release);
            return false;
        }

		JLog::add('After version compare');

        // abort if the component being installed is not newer or not equal (overwrite) than the currently installed version
        if ( $type == 'update' ) {
		
			JLog::add('-> type update');
			$rel = $this->$oldRelease . ' to ' . $this->$newRelease;
			
			//if ( version_compare( $this->release, $oldRelease, 'le' ) ) {
			if ( version_compare( this->$newRelease, this->$oldRelease, 'lt' ) ) {
					Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
					return false;
			}
			/**/
        }
        else 
		{ 
			JLog::add('-> type freshInstall');
			$rel = $this->newRelease; 
			
			/* ToDo: use RemoveAccidentallyLeftovers or do it directly
			
		   //Delete images and directories if exist
			$exceptions = array(".", "..");
			$this->deleteGalleryDir(JPATH_SITE.$this->galleryDir, $exceptions, false);

			//Delete database tables
			foreach ($this->tablelistNew as $table)
			{
				$this->deleteTable($table);
			}        
			*/

		}

 		JLog::add('COM_RSGALLERY2_PREFLIGHT_' . strtoupper($type). '_TEXT');
        echo '<p>' . JText::_('COM_RSGALLERY2_PREFLIGHT_' . strtoupper($type). '_TEXT') . ' ' . $rel . '</p>';		
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
		
		require_once( JPATH_SITE . '/administrator/components/com_rsgallery2/includes/install.class.php' );

		JLog::add('freshInstall');

		//Initialize install
		$rsgInstall = new rsgInstall();		
		$rsgInstall->freshInstall();

		echo '<p>' . JText::_('COM_RSGALLERY2_INSTALL_TEXT') . '</p>';
		JLog::add('Before redirect');
		
		// Jump directly to the newly installed component configuration page
        // $parent->getParent()->setRedirectURL('index.php?option=com_rsgallery2');

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
		
		require_once( JPATH_SITE . '/administrator/components/com_rsgallery2/includes/install.class.php' );
		
		// now that we know a previous rsg2 was installed, we need to reload it's config
		global $rsgConfig;
		$rsgConfig = new rsgConfig();

	
		//Initialize install
		$rsgInstall = new rsgInstall();		
		/** /
		JLog::add('freshInstall');
		$rsgInstall->freshInstall();
		
		JLog::add('After freshInstall');
		if (false)
		/*
		{*/
		$rsgInstall->writeInstallMsg( JText::sprintf('COM_RSGALLERY2_MIGRATING_FROM_RSGALLERY2', $rsgConfig->get( 'version' )) , 'ok');

		JLog::add('Before migrate');

		//Initialize rsgallery migration
		$migrate_com_rsgallery = new migrate_com_rsgallery();
		
		JLog::add('Do migrate');
		//Migrate from earlier version
		$result = $migrate_com_rsgallery->migrate();
		
		if( $result === true ){
			$rsgInstall->writeInstallMsg( JText::sprintf('COM_RSGALLERY2_SUCCESS_NOW_USING_RSGALLERY2', $rsgConfig->get( 'version' )), 'ok');
		}
		else{
			$result = print_r( $result, true );
			$rsgInstall->writeInstallMsg( JText::_('COM_RSGALLERY2_FAILURE')."\n<br><pre>$result\n</pre>", 'error');
		}

		
		JLog::add('view update text');
		echo '<p>' . JText::_('COM_RSGALLERY2_UPDATE_TEXT') . '</p>';

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
		echo '<p>' . JText::_('COM_RSGALLERY2_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
		
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
		echo '<p>' . JText::_('COM_RSGALLERY2_UNINSTALL_TEXT') . '</p>';
		JLog::add('exit uninstall');
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam( $name ) {
			$db = JFactory::getDbo();
			$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_rsgallery2"');
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
					$db->setQuery('SELECT params FROM #__extensions WHERE name = "com_rsgallery2"');
					$params = json_decode( $db->loadResult(), true );
					// add the new variable(s) to the existing one(s)
					foreach ( $param_array as $name => $value ) {
							$params[ (string) $name ] = (string) $value;
					}
					// store the combined new and existing values back as a JSON string
					$paramsString = json_encode( $params );
					$db->setQuery('UPDATE #__extensions SET params = ' .
							$db->quote( $paramsString ) .
							' WHERE name = "com_rsgallery2"' );
							$db->execute();
			}
	}
}
