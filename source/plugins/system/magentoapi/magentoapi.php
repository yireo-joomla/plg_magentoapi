<?php
/**
 * Joomla! System plugin - Magento API
 * 
 * Allows to load the Magento framework-classes within the Joomla! environment
 *
 * @author Yireo (info@yireo.com)
 * @copyright Copyright 2015
 * @license GNU Public License
 * @link http://www.yireo.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// Import the parent class
jimport( 'joomla.plugin.plugin' );

/**
 * MagentoAPI System Plugin
 */
class plgSystemMagentoAPI extends JPlugin
{
    /**
     * Event onAfterInitialise
     *
     * @access public
     * @param null
     * @return null
     */
    public function onAfterInitialise()
    {
        $application = JFactory::getApplication();
        if ($application->isSite() == false && $application->isAdmin() == false) {
            return false;
        } else if ($application->isSite() == true && $this->params->get('enable_frontend', 1) == 0) {
            return false;
        } else if ($application->isAdmin() == true && $this->params->get('enable_backend', 1) == 0) {
            return false;
        }

        // Check for the Magento-folder
        $folder = $this->params->get('folder', null);
        if (empty($folder) || is_dir($folder) == false) {
            return false;
        }

        // Check if the folder is readable
        if (is_readable($folder) == false) {
            if ($this->params->get('debug', 0) == 1) {
                $application->enqueueMessage(JText::sprintf('Failed to read from folder "%s"', $folder), 'error');
            }
            return false;
        }

        // Check for the main application-file
        if (is_file($folder.DS.'app'.DS.'Mage.php') == false) {
            if ($this->params->get('debug', 0) == 1) {
                $application->enqueueMessage(JText::sprintf('No Magento found in "%s"', $folder), 'error');
            }
            return false;
        }

        // Do not display errors
        error_reporting(E_ALL ^ E_NOTICE);

        // Set the umask()
        umask(0022);

        // Set the include path
        set_include_path(get_include_path().':'.$folder);

        // Change the folder to the Magento root
        chdir($folder);

        // Include the application
        require_once 'app'.DS.'Mage.php';

        // Fail if the class Mage does not exist
        if(class_exists('Mage') == false) {
            if ($this->params->get('debug', 0) == 1) {
                $application->enqueueMessage(JText::_('Loaded Magento, but class "Mage" still does not exist'), 'error');
            }
        }

        // Start the application
        Mage::app();

        if ($this->params->get('debug', 0) == 1) {
            try {
                $customer = Mage::getModel('customer/session')->getCustomer();
                $application->enqueueMessage(JText::_('Success loading Magento classes'));
            } catch(Exception $e) {
                $application->enqueueMessage(JText::_('Failed to load Magento classes'), 'error');
            }
        }
    }
}
