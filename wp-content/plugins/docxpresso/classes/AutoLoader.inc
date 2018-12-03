<?php

/**
 * Autoloader
 *
 */

namespace Docxpresso;

/**
 * Autoloader
 *
 * @package    Docxpresso
 * @subpackage Core
 */

class AutoLoader
{
    /**
     * generic load
     * 
     * @access public
     * @static
     */
    public static function load()
    {
        spl_autoload_register(__NAMESPACE__ . '\\AutoLoader::load_Docxpresso');
    }
    
    /**
     * load Docxpresso package
     * 
     * @param string $className
     * @access public
     * @static
     */
    public static function load_Docxpresso($className)
    {
        $className = str_replace('\\', '/', $className);
        $className = str_replace('Docxpresso/', '', $className);
        if (file_exists(dirname(__FILE__) . '/' . $className . '.inc')) {
            require_once dirname(__FILE__) . '/' . $className . '.inc';
        }
    }
}
