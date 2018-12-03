<?php

/**
 * Docxpresso plugin main class
 *
 * @copyright  Copyright (c) No-nonsense Labs (http://www.nononsenselabs.com)
 */

/*
Plugin Name: Docxpresso
Plugin URI: http://www.docxpresso.com
Description: Docxpresso inserts content from a document file (.odt).
Version: 2.1
Author: No-nonsense Labs
License: GPLv2 or later
*/

namespace Docxpresso;


use Docxpresso\ODF2HTML5 as Odf2html5;

require_once dirname(__FILE__) . '/classes/AutoLoader.inc';
AutoLoader::load();


class CreateDocument
{ 
    /**
     * this array holds the data parsed from the config.ini file
     * 
     * @var array
     * @access public
     * @static
     */
    public static $config;

    /**
     * It can be 'text' for .odt files or 'spreadsheet' for .ods files
     * @var string
     * @access public
     */
    public static $docType;
       
    /**
     * holds all the XMLs and media of the document zip file
     * @var array
     * @access public
     */
    public $template;
    
    /**
     * holds all the DOMDocument instances of the XMLs of the document
     * @var array
     * @access private
     */
    private $_dom;
    
    
    /**
     * Construct
     *
     * @access public
     */
    public function __construct($params = array())
    {
        //initialize certain required variables
        //parse the config.ini file
        self::$config = \parse_ini_file( 'config.ini', true); 
        //extract the relevant data from the template
        $path = $params['template'];
        //get extension to define the doc type
        $extension = $extension = substr($path, -4);
        $allowedTemplateExtensions = array('.odt' => true, 
                                           '.ods' => true);
        if (!isset($allowedTemplateExtensions[$extension])) { 
            exit('Invalid template extension');
        }

        if ($extension == '.ods') {
            self::$docType = 'spreadsheet';
        } else {
            self::$docType = 'text';
        }

        //extract into memory all files from the template
        $template = new \ZipArchive();

        $openZip = $template->open($path);

        $this->template = self::extractTemplateFiles($template);
        $template->close();
        //process the relevant files
        //content.xml
        $this->_dom['content.xml'] = new \DOMDocument();
        $this->_dom['content.xml']->loadXML($this->template['content.xml']);
        //style.xml
        $this->_dom['styles.xml'] = new \DOMDocument();
        $this->_dom['styles.xml']->loadXML($this->template['styles.xml']);
        //META-INF/manifest.xml
        $this->_dom['META-INF/manifest.xml'] = new \DOMDocument();
        $this->_dom['META-INF/manifest.xml']
             ->loadXML($this->template['META-INF/manifest.xml']);  

    }
    
    /**
     * returns the array that holds reference to the associated 
     * DOMDocument objects (content, styles, metas, manifest)
     * 
     * @return array
     * @access public
     */
    public function getDOM() 
    {
        return $this->_dom;
    }

    
    /**
     * Converts a document into HTML5 + CSS + JS
     * 
     * @param string $path the path to the resulting HTML5 file. The path
     * should include the extension (html or htm)
     * @param array $options an array with the following keys and values:
     *  'charts' => (boolean) if true renders the chart via JavaScript.
     *   The default value is true.
     *  'css' => (string) path to an external CSS stylesheet.
     *  'download' => (bolean) if true saves the file and flush its contents to
     *   the browser.
     *  'format' => (string) the values can be:
     *      standard: (default) the styles and images are stored in a folder
     *      with the same name of the HTML5 file
     *      single-file: the styles are integrated within the <head> tag 
     *      element and the images are base64 encoded an integrated within the
     *      HTML5 file.
     *  'meta' => (mixed) It can be boolean value: if true the metadata will
     *   be extracted automatically from the document and if false (default) no 
     *   metadata will be included, or an array with key-value pairs the  
     *   property name and its corresponding value.
     *  'SVG' => (boolean) if true renders the drawings as SVG. The default
     *   value is false.
     * 
     * @return string
     * @access public
     * @ignore
     */
    public function ODF2HTML5($path, $options = array()) 
    {
        //preset values
        if (empty($options['format'])) {
            $options['format'] = 'standard';
        }
        $options['docType'] = self::$docType;
        $html =  new Odf2html5\ODF2HTML5($this, $path, $options);
        $data = array();
        $data =  $html->renderHTML();
        return $data;
    }
    
    
    /**
     * Extracts all files from a ZipArchive object 
     * 
     * @param ZipArchive $zip
     * @return array
     * @access public
     * @static
     * 
     */
    public static function extractTemplateFiles($zip)
    {
        $zipContent = array();

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $zipContent[$stat['name']] = $zip->getFromName($stat['name']);
        }
        
        return $zipContent;
    }
       
}