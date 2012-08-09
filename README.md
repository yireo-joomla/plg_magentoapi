MagentoApi
==========

This project includes a Joomla! plugin to load Magento code within Joomla! 2.5 or later. With this plugin, you can write
PHP-code that refers to Magento classes and objects within Joomla! extensions. To use the plugin, install it in Joomla!
using the Extension Manager, configure the Magento folder under the plugin-parameter "Magento root" and enable the plugin.
Make sure the Magento folder is readable from within Joomla! (read-permissions, open_basedir setting, etcetera).

Joomla! 1.5 is not supported and never will be, due to incompatibile.

Disclaimer: Yireo is also the creator of MageBridge, which forms a bridge between Joomla! and Magento, and which main
feature is to integrate Magento into Joomla! using an HTTP-based API. MageBridge is not needed to use this MagentoApi
plugin. The only relationship between the two projects is that this MagentoApi plugin is perhaps used in MageBridge 2 as
alternative to the HTTP-based API.
