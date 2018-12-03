<?php

/**
 * ODF2HTML5
 *
 * @copyright  Copyright(c) No-nonsense Labs (http://www.nononsenselabs.com)
 */

/*
Plugin Name: Docxpresso
Plugin URI: http://www.docxpresso.com
Description: Docxpresso inserts content from a document file (.odt).
Version: 2.1
Author: No-nonsense Labs
License: GPLv2 or later
*/

namespace Docxpresso\ODF2HTML5;


use Docxpresso;


class ODF2HTML5
{
    
    /**
     * Stores default cell styles for table columns
     * 
     * @var array
     * @access public
     * @static
     */
    public static $colCellStyles = array();
    /**
     * Stores list numbering info
     * 
     * @var array
     * @access public
     * @static
     */
    public static $listLevel = array();
    /**
     * Stores information about the stroke scaling factor for rendering SVGs
     * 
     * @var array
     * @access public
     * @static
     */
    public static $strokeScales = array();
    /**
     * Stores background images for SVG rendering
     * 
     * @var array
     * @access private
     */
    private $_backImages;
    /**
     * HTML5 body element
     * 
     * @var DOMDocument
     * @access private
     */
    private $_body;
    /**
     * The JS engine used to render charts
     * 
     * @var string
     * @access private
     */
    private $_chartJS;
    /**
     * Stores chart names
     * 
     * @var array
     * @access private
     */
    private $_charts;
    /**
     * Stores the comment nodes
     * 
     * @var array
     * @access private
     */
    private $_comments;
    /**
     * Generated CSS string
     * 
     * @var string
     * @access private
     */
    private $_CSS;
    /**
     * The current form name
     * 
     * @var int
     * @access private
     */
    private $_currentForm;
    /**
     * The current master style
     * 
     * @var string
     * @access private
     */
    private $_currentMasterStyle;
     /**
     * An integer that counts the parsed nodes
     * 
     * @var int
     * @access private
     */
    private $_currentNode;
    /**
     * The current HTML page layout identifier
     * 
     * @var string
     * @access private
     */
    private $_currentHTMLPage;
    /**
     * sets the default font size
     * 
     * @var string
     * @access private
     */
    private $_defaultFontSize;
    /**
     * Reference to the ODF document
     * 
     * @var CreateDocument
     * @access private
     */
    private $_doc;
    /**
     * Type of document to be parsed
     * 
     * @var string
     * @access private
     */
    private $_docType;
    /**
     * Stores all the required DOMDocument objects
     * 
     * @var DOMDocument
     * @access private
     */
    private $_dom;
    /**
     * This array stores the required path info about drop caps in paragraphs
     * 
     * @var array
     * @access private
     */
    private $_dropcaps;
    /**
     * ending variable delimiter
     * 
     * @var string
     * @access private
     */
    private $_end;
    /**
     * Keeps track of the endnote numbering
     * 
     * @var int
     * @access private
     */
    private $_endnoteCounter;
    /**
     * Stores the endnotes nodes
     * 
     * @var array
     * @access private
     */
    private $_endnotes;
    /**
     * Global page CSS properties
     * 
     * @var string
     * @access private
     */
    private $_externalCSS;
    /**
     * Keeps track of the footnote numbering
     * 
     * @var int
     * @access private
     */
    private $_footnoteCounter;
    /**
     * Stores the footnotes nodes
     * 
     * @var array
     * @access private
     */
    private $_footnotes;
    /**
     * Stores the form info
     * 
     * @var array
     * @access private
     */
    private $_forms;
    /**
     * This array stores the xml of the body child nodes together with the 
     * associated master style
     * 
     * @var array
     * @access private
     */
    private $_HTML;
    /**
     * This array stores all the required info to build the HTML body element:
     * the HTML code, page style, header and footer for each different layout
     * section of the final HTML5 document
     * 
     * @var array
     * @access private
     */
    private $_HTMLStructure;
    /**
     * This array stores the images used in the document
     * 
     * @var array
     * @access private
     */
    private $_images;
    /**
     * if true loads JQuery
     * 
     * @var boolean
     * @access private
     */
    private $_loadJQuery;
    /**
     * This array stores the relationship among master and layout styles
     * 
     * @var array
     * @access private
     */
    private $_masterLayout;
    /**
     * A boolean that states if there are math formulas to parse
     * 
     * @var boolean
     * @access private
     */
    private $_math;
    /**
     * Metadata information
     * 
     * @var mixed
     * @access private
     */
    private $_metadata;
    /**
     * This array stores all different page styles used in the document
     * 
     * @var array
     * @access private
     */
    private $_pageStyle;
    /**
     * This array stores all the required path info for the resulting html
     * 
     * @var array
     * @access private
     */
    private $_path;
    /**
     * This array stores all ODF style inheritance
     * 
     * @var array
     * @access private
     */
    private $_parentStyle;
    /**
     * Determines if charts should be parsed
     * 
     * @var boolean
     * @access private
     */
    private $_parseCharts;
    /**
     * Determines if comments should be parsed
     * 
     * @var boolean
     * @access private
     */
    private $_parseComments;
    /**
     * if set to true tries to parse URLs pointing to external services like
     * youtube or Google forms
     * 
     * @var boolean
     * @access private
     */
    private $_parseExternalServiceLinks;
    /**
     * Determines if document layout is parsed
     * 
     * @var boolean
     * @access private
     */
    private $_parseLayout;
    /**
     * Stores info about paragraphs with a border
     * 
     * @var array
     * @access public
     * @static
     */
    private $_pBorders = array();
    /**
     * Stores info about paragraphs with a margin
     * 
     * @var array
     * @access public
     * @static
     */
    private $_pMargins = array();
    /**
     * prefix used as a namespace for styles
     * 
     * @var string
     * @access private
     */
    private $_prefix;
    /**
     * The path where the resultin HTML file should be saved
     * 
     * @var string
     * @access private
     */
    private $_rawPath;
    /**
     * Scale factor to harmonize line height rendering in Office and
     * Internet Browsers
     * 
     * @var integer
     * @access private
     */
    private $_scaleLineHeight;
    /**
     * List of external services we wish to parse
     * 
     * @var array
     * @access private
     */
    private $_services;
    /**
     * True if we want the ouput to be a single file
     * 
     * @var bool
     * @access private
     */
    private $_singleFile;
    /**
     * ",." for english locale or ".," for spanish locale
     * 
     * @var string
     * @access private
     */
    private $_sortNumberFormat;
    /**
     * True if we want the tables to be sortable
     * 
     * @var bool
     * @access private
     */
    private $_sortTables;
    /**
     * starting varaible delimiter
     * 
     * @var string
     * @access private
     */
    private $_start;
    /**
     * This array stores all styles
     * 
     * @var array
     * @access private
     */
    private $_style;
    /**
     * This array stores the relationship among styles and master styles
     * 
     * @var array
     * @access private
     */
    private $_style2master;
    /**
     * This array stores the child parent relationship among styles
     * 
     * @var array
     * @access private
     */
    private $_style2style;
    /**
     * If true drawings are parsed
     * 
     * @var boolean
     * @access private
     */
    private $_SVG;
    /**
     * An instance of the SVGParser object
     * 
     * @var SVGParser
     * @access private
     */
    private $_SVGParser;
    /**
     * If true the result will be tabbed (only spreadsheets)
     * 
     * @var boolean
     * @access private
     */
    private $_tabs;
    /**
     * The DOMXPath object obtained from $this->_dom
     * 
     * @var DOMXPath
     * @access private
     */
    private $_xpath;
    
    
    /**
     * Construct
     *
     * @param CreateDocument $doc
     * @param string $path
     * @param array $options
     * @access public
     */
    public function __construct($doc, $path, $options)
    {          
        //initialize variables
        $this->_prefix = 'h5p_' . uniqid() . ' ';
        $this->_rawPath = $path;
        $this->_services = array(
            'https://youtu.be/' => array('youtube', '/https:\/\/youtu.be\/([a-zA-Z0-9?=&%_\-]*)/'),
            'https://goo.gl/forms/' => array('googleForms', 
                                             '/https:\/\/goo.gl\/forms\/([a-zA-Z0-9?=&%_\-]*)/'),
            'https://docs.google.com/document/' => array('googleDocs', 
                                             '/https:\/\/docs.google.com\/document\/d\/([a-zA-Z0-9?=&%_\-\/]*)/')
        );
        if (isset($options['docType'])) {
            $this->_docType = $options['docType'];
        } else {
            $this->_docType = 'text';
        }
        if (isset($options['tabs'])) {
            $this->_tabs = $options['tabs'];
        } else {
            $this->_tabs = false;
        }
        if (isset($options['sortTables'])) {
            $this->_sortTables = $options['sortTables'];
        } else {
            $this->_sortTables = true;
        }
        if (isset($options['sortNumberFormat'])) {
            $this->_sortNumberFormat = $options['sortNumberFormat'];
        } else {
            $this->_sortNumberFormat = Docxpresso\CreateDocument::$config['locale']['numFormat'];
        }
        $this->_download = true;
		if (isset($options['linkTargetToBlank'])) {
            $this->_linkTargetToBlank = $options['linkTargetToBlank'];
        } else {
            $this->_linkTargetToBlank = false;
        }
        if (isset($options['parseExternalServiceLinks'])) {
            $this->_parseExternalServiceLinks = $options['parseExternalServiceLinks'];
        } else {
            $this->_parseExternalServiceLinks = true;
        }
        if (isset(Docxpresso\CreateDocument::$config['ODF2HTML5']['scaleLineHeight'])) {
            $this->_scaleLineHeight = Docxpresso\CreateDocument::$config['ODF2HTML5']['scaleLineHeight'];
        } else {
            $this->_scaleLineHeight = 1;
        }
        if (!isset($options['parseLayout']) || $options['parseLayout'] == true) {
            $this->_parseLayout = true;
        } else {
            $this->_parseLayout = false;
        }
        //set global chart parsing defaults
        if (!isset($options['charts']) || $options['charts'] == true) {
            $this->_chartJS = Docxpresso\CreateDocument::$config['charts']['js'];
        } else {
            $this->_chartJS = false;
        }
        //set global comment parsing defaults
        if (!isset($options['comments']) || $options['comments'] == true) {
            $this->_commentJS = true;
        } else {
            $this->_commentJS = false;
        }

        if (isset($options['css'])) {
            $CSSPath = $options['css'];
        } else {   
            if($this->_docType == 'spreadsheet'){         
            	$CSSPath = __DIR__ . '/defaultODS.css';
            } else {
                $CSSPath = __DIR__ . '/default.css';
            }
        }
        //take into account external CSS props
        try {
            $extCSS = \file_get_contents($CSSPath);
            $this->_externalCSS = \str_replace('.h5p_page',
                                               'div#' . \trim($this->_prefix),
                                               $extCSS);
            if (empty($this->_externalCSS)) {
                throw new \Exception('Error while trying to get global styles');
            }
        } catch (\Exception $e) {
            
        }
        if (isset($options['metadata'])) {
            $this->_metadata = $options['metadata'];
        } else {
            $this->_metadata = false;
        }
        self::$listLevel = array();
        $this->_charts = array();
        $this->_comments = array();
        $this->_footnotes = array();
        $this->_endnotes = array();
        $this->_defaultFontSize = NULL;
        $this->_defaultStyle= array();
        $this->_forms = array();
        $this->_HTML = array();
        $this->_HTMLStructure = array();
        $this->_images = array();
        $this->_noteCounter = 0;
        $this->_math = false;
        $this->_masterLayout = array();
        $this->_style = array();
        $this->_style2master = array();
        $this->_style2style = array();
        $this->_pageStyle = array();
        $this->_parentStyle = array(); 
        $this->_dropcaps = array();
        
        //Chart parsing
        if (isset($options['parseCharts'])) {
            $this->_parseCharts = $options['parseCharts'];
        } else {
            $this->_parseCharts = true;
        }
        
        //Comment parsing
        if (isset($options['parseComments']) && $options['parseComments'] == 'true') {
            $this->_parseComments = true;
        } else {
            $this->_parseComments = false;
        }
        
        
        $this->_CSS = '';
        $this->_currentHTMLPage = 0;
        $this->_currentMasterStyle = '';
        $this->_currentNode = 0;
        if (isset($options['format']) && $options['format'] == 'single-file') {
            $this->_singleFile = true;
        } else {
            $this->_singleFile = false;
            //we extract the name so we can create the folders containing
            //the images, styles and javascript if any
            $this->_path = \pathinfo($path);
        }
        //get the required document info
        $this->_doc = $doc;
        $this->_dom = $doc->getDOM();
        $this->_xpath = new \DOMXPath($this->_dom['content.xml']);
        $this->_xpath->registerNamespace('table',
        'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $this->_xpath->registerNamespace('text',
        'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $this->_xpath->registerNamespace('style',
        'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $this->_xpath->registerNamespace('form',
        'urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0');
        //define some required objects to start to build the HTML5 file
        $this->_body = new \DOMDocument();
        $this->_body->loadXML('<body />');
        
        //SVG parsing
        if (isset($options['parseSVG'])) {
           $this->_SVG = $options['parseSVG'];
        } else {
            $this->_SVG = true;
        }
        if ($this->_SVG){
            $this->_SVGParser = new SVGParser($this->_doc);
            $this->_backImages = array();
        }
        
        if ($this->_SVG) {
            //look for draw background images
            $bImages = $this->_dom['styles.xml']
                            ->getElementsByTagName('fill-image');
            foreach ($bImages as $img) {
                $imgName = $img->getAttribute('draw:name');
                $path = $img->getAttribute('xlink:href');
                $this->_backImages[$imgName] = $path;
            }
        }
        //to properly take into account continuous numbering of lists we
        //should preparse the lists
        $this->_preParseLists();
        //we have to check if there default cell styles for columns
        $this->_preParseColCellStyles();
        if ($this->_parseExternalServiceLinks) {
            $this->_preParse4Services();
        }
        //build the different HTML5 document portions
        $this->_generateStyle();
        $this->_mergeParentStyles(); 
        $this->_generateBody();
    }
      
    /*Getters and Setters*/
    
    /**
     * Gets the HTML5 body element as a string
     *
     * @return string
     * @access public
     */
    public function getBody() 
    {
        return $this->_body->saveXML();
    }
    
    /**
     * Gets the current HTML page reference
     *
     * @return int
     * @access public
     */
    public function getCurrentHTMLPage() 
    {
        return $this->_currentHTMLPage;
    } 
    
    /**
     * Sets the current HTML page reference
     *
     * @param int $page
     * @return void
     * @access public
     */
    public function setCurrentHTMLPage($page) 
    {
        $this->_currentHTMLPage = $page;
    }
    
    /**
     * Gets the HTML5 style element as a string
     *
     * @return string
     * @access public
     */
    public function getStyle() 
    {
        return $this->_style->saveXML();
    }
    
    /**
     * Some ODF properties have a different behaviour in CSS so we need to
     * filter them to avoid beraking the rendering in a browser
     *
     * @param string $prop
     * @param string $value
     * @param bool $important
     * @return void
     * @access private
     */
    private function _filterProp($prop, $value, $important = false)
    {
        $style = $prop . ': ' . $value;
        if ($important) {
            $style .= ' !important;';
        } else {
            $style .= ';';
        }
        if ($prop == 'margin' && \strpos($value, '%') !== false) {
            $style = '';
        }
        return $style;
    }
    
    /**
     * Takes care of the positioning of image, chart and math elements
     *
     * @param DOMNode $newNode
     * @param DOMNode $frame
     * @return void
     * @access private
     */
    private function _framePositioning($newNode, $frame, $type = 'image')
    {
        //respecting positioning there is an special case that we should take 
        //into account frams containing text-boxes
        $firstChild = $frame->firstChild;
        $fcName = $firstChild->nodeName;
        $style = 'z-index: 100;';
        if ($type == 'image'){
            $width = $frame->getAttribute('svg:width');
            if (!empty($width)) {
                $style .= 'width: ' . $width . ';';
            }
        }
        $height = $frame->getAttribute('svg:height');
        if (!empty($height) && $fcName != 'draw:text-box') {
            $style .= 'height: ' . $height . ';';
        }
        $width = $frame->getAttribute('svg:width');
        if (!empty($width)) {
            $style .= 'width: ' . $width . ';';
        }
        //check at svg:x for horizontal positioning
        $anchorType = $frame->getAttribute('text:anchor-type');
        if ($anchorType != 'as-char') {
            //$style .= 'display: block';
        } else if ($fcName == 'draw:text-box') {
            $style .= 'display: inline-block';
        } else if ($type != 'object') {
            $style .= 'display: inline;';
        }
        
        if ($type == 'frame') {
            $class= '';
            $class .= $frame->getAttribute('draw:class-names');
            if (!empty($class)) {
                $class .= ' ' . $frame->getAttribute('draw:style-name');
            } else {
                $class .= $frame->getAttribute('draw:style-name');
            }
            if (!empty($class)) {
                $newNode->setAttribute('class', $class);
            }
        }
        //check at svg:x for horizontal positioning
        $class = $frame->getAttribute('draw:style-name');
        $x = $frame->getAttribute('svg:x');
        if (!empty($x)) {
            $offset = self::convertUnits('pt', $x);
            if ($offset > 100) {
                //for frames displaced more than 100pt to the right we assume
                //float: right
                if (isset($this->_style['div.' . $class])
                    && strpos($this->_style['div.' . $class], 'float: left') !== false) {
                    $this->_style['div.' . $class] = 
                            \str_replace('float: left',
                                         'float: right',
                                         $this->_style['div.' . $class]);
                }
            } else {
                //$style .= 'margin-left: ' . $offset . 'pt;';
            }
        }
        //check if there is a transform attribute
        $transform = $frame->getAttribute('draw:transform');
        if (!empty($transform)){
            if(empty($width)){
                $width = 0;
            } else {
                $width = $this->_resize(0.5, $width);
            }
            if(empty($height)){
                $height = 0;
            } else {
                $height = $this->_resize(0.5, $height);
            }
            //we need to use width and height because in the browser the
            //axis of rotation is located in the center of the image while in the
            //odt is in the top left corner
            $style .= $this->_parseTransform('transform', $transform, $width, $height);
        }
        //apply the style
        $newNode->setAttribute('style', $style);
    }
    
    /**
     * This method allows to convert Open Document format content into HTML5
     * content. All HTML5 tags store a reference to the original ODF tag element
     * to simplify going back and forth abetween both standards
     *
     * @return void
     * @access private
     */
    private function _generateBody()
    {       
             
        //get the office:text node to start the parsing
        $content = $this->_dom['content.xml'];
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:office:1.0';
        if ($this->_docType == 'spreadsheet') {
            $root = $content->getElementsByTagNameNS($ns, 'spreadsheet')->item(0);
        } else {
            $root = $content->getElementsByTagNameNS($ns, 'text')->item(0);
        }
        $rootChilds = $root->childNodes;
        $body = $this->_body->documentElement;
        $n = 0;
        foreach ($rootChilds as $child) {
            if ($child->nodeType == 1) {
                $parsedNode = $this->_parseODFNode($child, $body, false);
                if (!empty($parsedNode)) {
                    if ($parsedNode->nodeName == 'table') {
                        //for responsiveness we are going to wrapp the tables
                        //within a div
                        $divNode = $this->_body->createElement('div');
                        $divNode->setAttribute('class', 'dxoResponsiveTable');
                        $divNode->appendChild($parsedNode);
                        $parsedNode = $divNode;
                    }
                    $this->_HTML[$n]['xml'] = $parsedNode;
                    $n++;
                    $this->_currentNode = $n;
                }
            }
        }
    }
    
    /**
     * This method generates HTML metadata from the document metadata o from
     * the user supplied metadata
     *
     * @return string
     * @access private
     */
    public function generateMetadata()
    {
        $parsable = array(  'dc:creator' => true,
                            'dc:date' => true,
                            'dc:description' => true,
                            'dc:language' => true,
                            'dc:subject' => true,
                            'dc:title' => true,
                            'meta:auto-reload' => false,
                            'meta:creation-date' => true,
                            'meta:document-statistic' => false,
                            'meta:editing-cycles' => false,
                            'meta:editing-duration' => false,
                            'meta:generator' => false,
                            'meta:hyperlink-behaviour' => false,
                            'meta:initial-creator' => true,
                            'meta:keyword' => true,
                            'meta:print-date' => false,
                            'meta:printed-by' => false,
                            'meta:template' => false, 
                            'meta:user-defined' => true,  
                        );
        $meta = '';
        $metadata = array();
        if (\is_array($this->_metadata)) {
            $metadata = $this->_metadata;
        } else if ($this->_metadata == true) {
            $dom =  new \DOMDocument;
            $dom->loadXML($this->_doc->template['meta.xml']);
            $base = $dom->getElementsByTagName('meta')->item(0);
            $childs = $base->childNodes;
            foreach ($childs as $child) {
                if ($child->nodeName == 'meta:user-defined') {
                    $name = $child->getAttribute('meta:name');
                    $value = $child->nodeValue;
                    $metadata[$name] = $value;
                } else if (isset($parsable[$child->nodeName]) 
                           && $parsable[$child->nodeName]) {
                    $temp = \explode(':', $child->nodeName);
                    $name = \array_pop($temp);
                    if ($name == 'keyword') {
                        if (isset($metadata['keyword'])) {
                            $value = ',' . $child->nodeValue;
                            $metadata['keyword'] .= $value;
                        } else {
                            $value = $child->nodeValue;
                            $metadata['keyword'] = $value;
                        }
                    } else {
                    $value = $child->nodeValue;
                    $metadata[$name] = $value;
                    }
                }
            }
        } else {
            return $meta;
        }
            foreach ($metadata as $name => $content) {
                $meta .= '<meta name="' . $name . '" ';
                $meta .= 'content="' . $content . '">' . PHP_EOL;
            }
        return $meta;
    }
    
    /**
     * This method allows to translate the ODF styles into CSS styles
     *
     * @return void
     * @access private
     */
    private function _generateStyle()
    { 
        //get the automatic style nodes from content.xml
        $this->_generateStyleFromSource($this->_dom['content.xml'], 
                                        'automatic-styles');
        //get the default style
        $this->_generateStyleFromSource($this->_dom['styles.xml'], 'styles');
        if ($this->_SVG && isset($this->_style['img'])) {
            //$this->_style['svg'] = $this->_style['img'];   
        }
        //get the automatic styles from style.xml
        $this->_generateStyleFromSource($this->_dom['styles.xml'], 
                                        'automatic-styles');
        //get the master styles from style.xml
        $this->_generateStyleFromSource($this->_dom['styles.xml'], 
                                        'master-styles');
        //get the automatic style nodes from content.xml
        $this->_generateStyleFromSource($this->_dom['content.xml'], 
                                        'automatic-styles');
        
        //we also should update now the info regarding the style2master array
        //to include inheritance recorded in the style2style array
        $this->_updateStyleMasterRelationships($this->_style2style);
    }
    
    /**
     * Translates the styles from a given source
     *
     * @param DOMDocument $src
     * @param string $nodeName
     * @return void
     * @access private
     */
    private function _generateStyleFromSource($src, $nodeName)
    { 
        //get the default style nodes;
        $rootElements = $src->getElementsByTagName($nodeName);
        //let us try to extract the default font size for text if any
        if ($nodeName == 'styles') {
            $xpath = new \DOMXpath($src);
            $query = '//style:default-style[@style:family="paragraph"]';
            $query .= '/style:text-properties';
            $def = $xpath->query($query);
            $length = $def->length;
            if ($length > 0) {
                $defSize = $def->item($length -1)->getAttribute('fo:font-size');
            }       
            if (!empty($defSize)) {
                $this->_defaultFontSize = $defSize;
            }
        }
        if ($rootElements->length > 0) {
            $root = $rootElements->item(0);
            $rootChilds = $root->childNodes;
            foreach ($rootChilds as $child) {
                if($child->nodeType == 1) {
                    $this->_parseODFStyle($child);
                }
            }
        }
    }
    
    /**
     * Generates the path to the image from the ODF source and the options and
     * stores the image
     *
     * @param string $srcODF
     * @param boolean $css
     * @return void
     * @access private
     */
    private function _imagePath($srcODF, $css = false)
    {
        if (strpos($srcODF, 'http://') === 0){
            return $srcODF;
        }
        if ($this->_singleFile !== true) {
            //get the image name
            $temp = \explode('/', $srcODF);
            $fileName = \array_pop($temp);
            $path = $this->_path['filename'] . '-img/' . $fileName;
            //check if the folder exists otherwise create it
            if (\file_exists($this->_path['filename'] . '-img') === false) {
                //create the folder
                \mkdir($this->_path['filename'] . '-img');
            }
            //copy there the image data
            $fp = \fopen($path, 'w');
            \fwrite($fp, $this->_doc->template[$srcODF]);
            \fclose($fp);
            if ($css) {
                $path = '../' . $path;
            }
        } else {
            $img = \base64_encode($this->_doc->template[$srcODF]);
            $temp = \explode('.', $srcODF);
            $extension = \array_pop($temp);
            $path = 'data:image/' . $extension . ';base64,';
            $path .= $img;
        }
        
        return $path;
    }
    
    /**
     * This method generates the required h* styles
     *
     * @param int $level
     * @param string $style
     * @return DOMNode
     * @access private
     */
    private function _addHeadingStyle($level, $style)
    {
        $p = 'p.' . $style;
        $span = $p . ' span';
        $h = 'h' . $level . '.' . $style;
        $hSpan = $h . ' span';
        if (isset($this->_style[$p])) {
            $this->_style[$h] = $this->_style[$p];
        } else {
            //generate the style even if empty due to possible inheritance
            $this->_style[$h] = '';
        }
        if (isset($this->_style[$span])) {
            $this->_style[$hSpan] = $this->_style[$span];
        } else {
            //generate the style even if empty due to possible inheritance
            $this->_style[$hSpan] = '';
        }
        //do the same for the parents
        if (isset($this->_style2style[$style])) {
            //explicitely set that the new heading styles should inehrit
            //properties from their corresponding parent styles
            $parent = $this->_style2style[$style];
            $this->_parentStyle[$h] = 'h' . $level . '.' . $parent;
            $this->_parentStyle[$hSpan] = $this->_parentStyle[$h] . ' span';
            $this->_addHeadingStyle($level, $parent);
        }       
    }
    
    /**
     * This method append the footnotes and endnotes at the the end of the
     * current section
     *
     * @param int $level
     * @param string $style
     * @return DOMNode
     * @access private
     */
    private function _appendNotes($node, $section = 0)
    {
        if (\count($this->_footnotes) > 0){
            //TODO: parse the footnote-sep layout style node
            $sep = $this->_body->createElement('div', ' ');
            $sepStyle = 'margin: 5px 0; width: 33%; border-bottom: 1pt solid #333;';
            $sep->setAttribute('style', $sepStyle);
            $node->appendChild($sep);
            foreach ($this->_footnotes as $note){
                $node->appendChild($note);
            }
        }
        if (\count($this->_endnotes) > 0){
            //TODO: different styling than footnotes?
            $sep = $this->_body->createElement('div', ' ');
            $sepStyle = 'margin: 5px 0; width: 33%; border-bottom: 1pt solid #333;';
            $sep->setAttribute('style', $sepStyle);
            $node->appendChild($sep);
            foreach ($this->_endnotes as $note){
                $node->appendChild($note);
            }
        }
        if (\count($this->_comments) > 0){
            foreach ($this->_comments as $comment){
                $node->appendChild($comment['content']);
            }
        }
    }

    /**
     * This method updates the master style property of the current $_HTML array
     * entry
     *
     * @param string $style
     * @return DOMNode
     * @access private
     */
    private function _check4MasterStyle($style)
    {
        $n = $this->_currentNode;
        if (isset($this->_style2master[$style])) {
            $this->_HTML[$n]['master'] = $this->_style2master[$style];
            $this->_currentMasterStyle = $this->_style2master[$style];
        } else {
            $this->_HTML[$n]['master'] = $this->_currentMasterStyle;
        }
    }
    
    /**
     * This method parses for links to external services not within <a> tags
     *
     * @param DOMNode $node
     * @param DOMDocument $html
     * @return DOMNode
     * @access private
     */
    private function _check4Services($node, $html)
    {
        $sizes = array(
            'youtube' => array(640, 360),
            'googleForms' => array('100%', 480),
            'googleDocs' => array('100%', 640),
        );
        $str = $node->nodeValue;
        $totalLength = strlen($str);
        $service = false;
        $found = false;
        foreach ($this->_services as $key => $value){
            $initial = stripos($str, $key);
            if ($initial !== false){
                $service = $value;
                break;
            }
        }

        if (!empty($service)) {
            //get the required info via a regular expression
            $regex = $service[1];
            preg_match_all($regex, $str, $matches);
            if (isset($matches[1]) && !empty($matches[1][0])){
                $length = strlen($matches[0][0]);
                $container = $html->createElement('span');
                if ($initial != 0) {
                    $substr = substr($str, 0, $initial);
                    $text = $html->createTextNode($substr);
                    $container->appendChild($text);
                }
                //insert the iframe
                //first try to extract width and height info
                $tmp = explode('?', $matches[0][0]);
                $src = $tmp[0];
                if (!empty($tmp[1])) {
                    $prewidth = $this->_getIframeSize($tmp[1], 'width');
                    $preheight = $this->_getIframeSize($tmp[1], 'height');
                } else {
                    $prewidth = false;
                    $preheight = false;
                }
                
                if ($service[0] == 'youtube') {
                    //in order to embed youtube videos we have to generate
                    //an embedding url
                    $src = 'https://www.youtube-nocookie.com/embed/';
                    $srctmp = explode('?', $matches[1][0]);
                    $src .= $srctmp[0];
                    $src .= '?rel=0';
                    //check if there is a defined size otherwise make it 
                    //responsive
                    if (!empty($prewidth) || !empty($preheight)){
                        if($prewidth){
                            $width = $prewidth;
                        } else {
                            $width = $sizes[$service[0]][0];
                        }
                        if($preheight){
                            $height = $preheight;
                        } else {
                            $height = $sizes[$service[0]][1];
                        }
                        $iframe = $html->createElement('iframe', ' ');
                        $iframe->setAttribute('src', $src);
                        $iframe->setAttribute('width', $width);
                        $iframe->setAttribute('height', $height);
                        $iframe->setAttribute('allowfullscreen', true);
                        $container->appendChild($iframe);
                    } else {
                        $video = $html->createElement('div');
                        $video->setAttribute('class', 'youtube');
                        $iframe = $html->createElement('iframe', ' ');
                        $iframe->setAttribute('src', $src);
                        $iframe->setAttribute('allowfullscreen', true);
                        $video->appendChild($iframe);
                        $container->appendChild($video);
                    }  
                } else {
                    if($prewidth){
                        $width = $prewidth;
                    } else {
                        $width = $sizes[$service[0]][0];
                    }
                    if($preheight){
                        $height = $preheight;
                    } else {
                        $height = $sizes[$service[0]][1];
                    }
                    $iframe = $html->createElement('iframe', ' ');
                    $iframe->setAttribute('src', $src);
                    $iframe->setAttribute('width', $width);
                    $iframe->setAttribute('height', $height);
                    $container->appendChild($iframe);
                } 
                
                if ($totalLength > $initial + $length +1) {
                    $substr = substr($str, $initial + $length);
                    $text = $html->createTextNode($substr);
                    $container->appendChild($text);
                }
                return $container;
            }
        }
        return $html->createTextNode($node->nodeValue);
    }
    
    /**
     * This method gets the width or height passed to an external service
     *
     * @param string $str
     * @param string $dimension
     * @return mixed
     * @access private
     */
    private function _getIframeSize($str, $dimension)
    {
        $regex = '/' . $dimension. '=([0-9%]*)/';
        preg_match_all($regex, $str, $matches);
        if (isset($matches[1]) && !empty($matches[1][0])){
            $size = $matches[1][0];
        } else {
            if ($dimension == 'width'){
                $size = false;
            } else {
                $size = false;
            }
        }
        return $size;
    }
    
    
    /**
     * Searches for paddings in borderless paragraphs and keep border
     * information for further parsing
     *
     * @param string $style
     * @return string
     * @access private
     */
    private function _repairParagraphStyles($style, $class)
    {
        //TODO: check if for the plugin is always better to
        //remove the paddings following standard Word behaviour
        $paddings2remove = array();
        //check if there are borders set
        $regex = '/(border-*[^;]*):([^;]+);?/i';
        \preg_match_all($regex, $style, $matches);
        if (isset($matches[1]) && isset($matches[2])) {
            $num = \count($matches[2]);
            for($j=0 ; $j < $num; $j++) {
                if (\trim($matches[2][$j]) == 'none'){
                    $paddings2remove[] = $matches[1][$j];
                } else {
                    if (!isset($this->_pBorders[$class])) {
                        $this->_pBorders[$class] = array();
                    }
                        $this->_pBorders[$class][] = $matches[0][$j];
                }
            }
        } 
        //check if there are margins set (important for bordered paragraphs)
        $regex = '/(margin-*[^;]*):([^;]+);?/i';
        \preg_match_all($regex, $style, $matches);
        if (isset($matches[1]) && isset($matches[2])) {
            $num = \count($matches[2]);
            for($j=0 ; $j < $num; $j++) {
                if (!isset($this->_pMargins[$class])) {
                    $this->_pMargins[$class] = array();
                }
                $arrayMargins = explode(':', $matches[0][$j]);
                $km = trim($arrayMargins[0]);
                $kv = trim($arrayMargins[1]);
                $this->_pMargins[$class][$km] = $kv;
            }
        }
        if (\strpos($style, "border:") === false
            || \strpos($style, "border: none") !== false  ){
            $regex = '/padding:[^;]+;?/i';
            $style = \preg_replace($regex, '', $style);
            return $style;
        }
        if (\strpos($style, "padding") === false){
            return $style;
        } else {
            if (\count($paddings2remove) > 0) {
                return $this->_removePadding($style, $paddings2remove);
            } else {
                return $style;
            }
        }
    }
    
    /**
     * This method inserts the HTML node
     *
     * @param DOMNode $odfNode
     * @param DOMNode $htmlNode
     * @param bool $append
     * @return DOMNode
     * @access private
     */
    private function _createHTMLNode($odfNode, $htmlNode, $append = true)
    { 
        //define a boolean variable for enabling tagging
        $tag = true;
        //define the new node container
        $newNode = NULL;
        $odfTag = $odfNode->nodeName;
        $html = $htmlNode->ownerDocument;
        $attr =  $odfNode->attributes;
        switch ($odfTag) {
            case '#text':
                //to preserve correct style inheritance we force all text to be
                //wrapped within a <span> node
                if ($htmlNode->nodeName != 'span'){
                    $span = $html->createElement('span');
                    $htmlNode = $htmlNode->appendChild($span);
                }
                if ($this->_parseExternalServiceLinks) {
                    $newNode = $this->_check4Services($odfNode, $html);
                } else {
                    $newNode = $html->createTextNode($odfNode->nodeValue);
                }
                break;
            case 'text:s':
                $num = $odfNode->getAttribute('text:c');
                if (empty($num)) {
                    $num = 1;
                } 
                for ($j=1; $j < $num; $j++){
                    $newNode = $html->createEntityReference('nbsp');
                    $htmlNode->appendChild($newNode);
                    $wbr = $html->createElement('wbr');
                    $htmlNode->appendChild($wbr);
                }
                $wbr = $html->createElement('wbr');
                $htmlNode->appendChild($wbr);
                $newNode = $html->createEntityReference('nbsp');
                break;
            case 'text:tab':
                $newNode = $html->createEntityReference('emsp');
                $htmlNode->appendChild($newNode);
                break;
            case 'text:a':
                if ($odfNode->hasChildNodes()){
                    $newNode = $html->createElement('a');
                } else {
                    $newNode = $html->createElement('a', ' ');
                }
                //we need to check if there is a containing span that
                //incorporates underline-text: none and if so set the
                //style attribute accordingly otherwise the browsers will
                //display the underlining
                $decor = $this->_spanDecoration($odfNode);
                if ($decor == 'none') {
                    $newNode->setAttribute('style', 
                                           'text-decoration: none');
                }
                break;
            case 'text:bookmark':
            case 'text:bookmark-start':
                $newNode = $html->createElement('span');
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $id = $odfNode->getAttributeNS($ns, 'name');
                $newNode->setAttribute('id', $id);
                break;
            case 'text:bookmark-end':               
                $newNode = $htmlNode;
                $append = false;
                $tag = false;
                break;
            case 'text:h':
                $l = $odfNode->getAttribute('text:outline-level');
                if (empty($l)) {
                    //use the default ODF level style
                    $l = 1;
                }
                $pChilds = $odfNode->hasChildNodes();
                if ($l > 0 && $l < 7){
                    $newNode = $html->createElement('h' . $l);
                    if (!$pChilds){
                        $newNode = $html->createElement('h' . $l, '&nbsp;');
                    } else {
                        $newNode = $html->createElement('h' . $l);
                    }
                    //Like the styles for a ODF header belong to the same family
                    //than normal paragraphs we have to add the corresponding
                    //styles to the $_style array
                    $hStyle = $odfNode->getAttribute('text:style-name');
                    if (!empty($hStyle)) {
                        $this->_addHeadingStyle($l, $hStyle);
                    }
                } else {
                    if (!$pChilds){
                        $newNode = $html->createElement('p', '&nbsp;');
                    } else {
                        $newNode = $html->createElement('p');
                    }
                }
                //check if the element is the child of a list-item and proceed
                //accordingly
                $this->_parentListStyle($odfNode, $newNode);
                //take care of inheritance in the case of numbered headings
                $hSt = $odfNode->getAttribute('text:style-name');
                if (!empty($hStyle)
                    && isset($this->_style['h' . $l . ':before'])
                    && isset($this->_style['h' . $l . '.' . $hSt . ' span'])) {
                    $st = $this->_style['h' . $l . '.' . $hSt . ' span'];
                    //remove text decorations with a hack
                    $st = \str_replace('text-decoration', 'textdecor', $st);
                    $this->_style['h' . $l . ':before'] .= $st;
                }  
                
                break;
            case 'text:p':
            case 'text:numbered-paragraph':
                $newNode = $html->createElement('p');
                //if it is an empty pargraph node we have to insert a &nbsp; so
                //it is rendered in the browser
                $pChilds = $odfNode->hasChildNodes();
                if (!$pChilds){
                    $newNode = $html->createElement('p', '&nbsp;');
                } else {
                    $newNode = $html->createElement('p');
                }
                //check if the element is the child of a list-item and proceed
                //accordingly
                $this->_parentListStyle($odfNode, $newNode);
                break;
            case 'text:span':
                $newNode = $html->createElement('span');
                break;
            case 'text:line-break':
                //this gets somehow messy because a text-line element at the
                //end of a paragraph is not ignored like in HTML browsers
                //let us first check if it is the las "br" at the end of a 
                //paragraph
                $parentNode = $odfNode->parentNode;
                if ($parentNode->nodeName == 'text:span'){
                    //check the number of childs
                    $childNum = $parentNode->childNodes->length;
                    if ($childNum == 1) {
                        //the line-break is the only node so if the parent span
                        //is the last span of the paragraph make it display like
                        //a block element
                        $granpaNode = $parentNode->parentNode->nodeName;
                        if (!empty($granpaNode) &&
                            $granpaNode != 'text:span' && 
                            empty($parentNode->nextSibling)){
                            $htmlNode->setAttribute('style', 'display:block');
                        }
                    }
                }
                $newNode = $html->createElement('br');
                break;
            case 'text:section':
                $newNode = $html->createElement('div');
                break;
            case 'table:table':
                if ($append) {
                    //for responsiveness we are going to wrapp the table with a 
                    //div
                    $divNode = $html->createElement('div');
                    $divNode->setAttribute('class', 'dxoResponsiveTable');
                    $htmlNode = $htmlNode->appendChild($divNode);
                }
                $newNode = $html->createElement('table');
                break;
            case 'table:table-column':
                $newNode = $html->createElement('col');
                break;
            case 'table:table-columns':
                $newNode = $html->createElement('colgroup');
                break;
            case 'table:table-header-rows':
                $newNode = $html->createElement('thead');
                break;
            case 'table:table-row':
                $newNode = $html->createElement('tr');
                break;
            case 'table:table-cell':
                $rowType = $odfNode->parentNode->parentNode->nodeName;
                if ($rowType == 'table:table-header-rows' 
                    && $this->_sortTables) {
                    //we use td instead of th in order not to duplicate all
                    //td styles
                    $newNode = $html->createElement('td');
                    $textContent = \strip_tags($odfNode->nodeValue);
                    if (substr($textContent, 0, 1) == '@') {
                        //we want to sort table values by this column
                        $newNode->setAttribute('data-sorting', '1');
                        $newNode->setAttribute('data-sortingOrder', 'ASC');
                    }
                } else {
                    $newNode = $html->createElement('td');
                }
                break;
            case 'text:list':
                $newNode = $html->createElement('ul');
                break;
            case 'text:list-item':
                $newNode = $html->createElement('li');
                //in ODF the lis-item paragraph styles are overwritten by
                //the child paragraph styles so we have to deal with that
                //reverse inheritance that has no CSS counterpart
                $p = $odfNode->firstChild;
                $pStyle = $p->getAttribute('text:style-name');
                $st = 'color: initial; font-family: initial;';
                if (!empty($pStyle)
                    && isset($this->_style['p span'])) {
                    $st .= $this->_style['p span'];
                }
                if (!empty($pStyle)
                    && isset($this->_style['p.' . $pStyle . ' span'])) {
                    $st .= $this->_style['p.' . $pStyle . ' span'];
                }
                if (!empty($pStyle)
                    && isset($this->_style['p.' . $pStyle ])) {
                    $st .= $this->_style['p.' . $pStyle];
                }
                //remove text decorations and margin-left/(text-indent) 
                //with a hack
                $st = \str_replace('text-decoration', 'textdecor' , $st);
                $st = \str_replace('margin-left', 'mleft' , $st);
                //$st = \str_replace('text-indent', 'tindent' , $st);
                $newNode->setAttribute('style', $st);
                break;
            case 'text:note':
                $newNode = $html->createElement('sup');
                $noteData = $this->_note($odfNode);
                $noteLink = $html->createElement('a', $noteData['ref']);
                $noteLink->setAttribute('href', '#' . $noteData['id']);
                $newNode->appendChild($noteLink);  
                break;
            case 'office:annotation':
                if ($this->_parseComments) {
                    $newNode = $html->createElement('span', ' ');
                    $cName = 'c_' . $odfNode->getAttribute('office:name');
                    $newNode->setAttribute('id', $cName);
                    $newNode->setAttribute('class', 'h5p_comment');
                    $htmlNode->appendChild($newNode);
                    $this->_comments[$cName] = $this->_comment2HTML($odfNode,
                                                                    $cName);
                }
                return NULL;
                break;
            case 'draw:a':
                $newNode = $html->createElement('a');
                break;
            case 'draw:frame':
                //The frames are not rendered unless they are childs of the
                //following nodes
                $fParents = array('text:p' => true,
                                  'text:h' => true,
                                  'text:a' => true, 
                                  'text:span' => true,
                                  //TODO: check that table-cell works for odt
                                  'table:table-cell' => true,
                                  'draw:g' => true,
                                  'draw:a' => true,
                                  'table:shapes' => true,
                    );
                $parent = $odfNode->parentNode->nodeName;                
                if (isset($fParents[$parent])) {
                    $display = $odfNode->getAttribute('text:anchor-type');
                    $firstChild = $odfNode->firstChild;
                    if ($display != 'as-char' 
                        || $firstChild->nodeName == 'draw:text-box') {
                        $newNode = $html->createElement('div');
                        $newNode->setAttribute('data-anchor-type', $display);
                        //let us define the source
                        $this->_framePositioning($newNode, $odfNode, 'frame');
                    } else {
                        $newNode = $html->createElement('span');
                        /*$newNode = $html->createElement('div');
                        $newNode->setAttribute('style', 
                                               'display: inline !important');
                        $class = $odfNode->getAttribute('draw:style-name');
                        $newNode->setAttribute('class', $class);*/
                    }
                } else {
                    return NULL;
                }
                break;
            case 'draw:image':
                //we have to take some info from its draw:frame parent node
                $frame = $odfNode->parentNode;
                //first make sure that it is not an "object replacement"
                $objs = $frame->getElementsByTagName('object');
                if ($objs->length > 0) {
                    //we silently leave
                    $newNode = $htmlNode;
                    $append = false;
                    $tag = false;
                    break;
                }
                $newNode = $html->createElement('img');
                //let us define the source
                $srcODF = $odfNode->getAttribute('xlink:href');
                $src = $this->_imagePath($srcODF);
                $newNode->setAttribute('src', $src);
                $this->_framePositioning($newNode, $frame);
                //look for title and desc attributes
                //svg:title maps to the title attribute
                $titNodes = $frame->getElementsByTagName('title');
                if ($titNodes->length > 0) {
                   $title = $titNodes->item(0)->nodeValue; 
                   $newNode->setAttribute('title', $title);
                }
                //svg:desc is mapped to the alt attribute
                $descNodes = $frame->getElementsByTagName('desc');
                if ($descNodes->length > 0) {
                   $desc = $descNodes->item(0)->nodeValue; 
                   $newNode->setAttribute('alt', $desc);
                } else {
                   $newNode->setAttribute('alt', '');
                }
                break;
            case 'draw:object':
                //let us get the object source
                $srcODF = $odfNode->getAttribute('xlink:href');
                $src = $this->_objPath($srcODF);
                if (\is_array($src) && $src['object'] == 'chart') {
                    //we have to take some info from its draw:frame parent node
                    $frame = $odfNode->parentNode;
                    $name = $frame->getAttribute('draw:name');
                    if (empty($name)) {
                        $name = \str_replace('.', '', \uniqid('', true));
                    }
                    $newNode = $html->createElement('div', ' ');
                   $opt = array();
                    $opt['name'] = 'chart-';
                    $opt['name'] .= \str_replace('.', '', \uniqid('', true));
                    $newNode->setAttribute('id', $opt['name']);
                    $this->_framePositioning($newNode, $frame, 'object');
                    $opt['js'] = $this->_chartJS;                    
                    $chart = new ChartParser($src['data'],
                                             $this->_dom['content.xml'],
                                             $this->_docType);                    
                    $this->_charts[$opt['name']] = $chart->render($opt);
                } else if (\is_array($src) && 
                    ($src['object'] == 'math' || $src['object'] == 'oomath')) {
                    $this->_math = true;
                    $mathDOM = new \DOMDocument();
                    $mathDOM->loadXML($src['data']);
                    $mathNodes = $mathDOM->getElementsByTagName('math');
                    if ($mathNodes->length > 0) {
                        $mathNode = $mathNodes->item(0);
                        $mNode = $html->importNode($mathNode,true);
                        if ($src['object'] == 'oomath') {
                            $block = $this->_check4BlockMath($odfNode);
                            if ($block) {
                                $mNode->setAttribute('display', 'block');
                                //we need to check for positioning to avoid
                                //centering all block math by default
                                $ancestor = $this->_getFrameBlockAncestor($odfNode);
                                if ($ancestor->nodeName == 'text:p') {
                                    $mstyle = $ancestor->getAttribute('text:style-name');
                                } else {
                                    $mstyle = $ancestor->getAttribute('table:style-name');
                                }
                                if (!empty($mstyle)) {
                                    $textAlign = $this->_getMathTextAlign($mstyle);
                                    if (!empty($textAlign)) {
                                        $mNode->setAttribute('indentalign', $textAlign);
                                    }
                                }
                            }
                        }
                        $newNode = $html->createElement('span');
                        $newNode->setAttribute('class', 'doxMathFormula');
                        $newNode->appendChild($mNode);
                    } else {
                        //we silently leave
                        $newNode = $htmlNode;
                        $append = false;
                        $tag = false;
                        break;
                    }
                } else {
                    //we silently leave
                    $newNode = $htmlNode;
                    $append = false;
                    $tag = false;
                    break;
                }
                break;
            case 'text:table-of-content':
                $newNode = $html->createElement('div');
                break;
            case 'form:form':
                $newNode = $html->createElement('form');
                break;
            case 'draw:control':
                $control = $odfNode->getAttribute('draw:control');
                $setWidth = true;
                if (isset($this->_forms[$control])) {
                    $form =& $this->_forms[$control];
                    if ($form['tag'] == 'datalist') {
                        $newNode = $html->createElement('input');
                        $newNode->setAttribute('name', $form['name']);
                        $newNode->setAttribute('list', $form['name']);
                        $newNode->setAttribute('value', $form['value']);
                        $newNode->setAttribute('form', $form['form']);
                        $dataList = $html->createElement($form['tag']);
                        $dataList->setAttribute('id', $form['name']);
                        $htmlNode->appendChild($dataList);
                    } else {
                        $newNode = $html->createElement($form['tag']);
                        $newNode->setAttribute('id', $form['name']);
                        $newNode->setAttribute('name', $form['name']);
                        if (isset($form['type'])) {
                        $newNode->setAttribute('type', $form['type']);
                        }
                        $newNode->setAttribute('value', $form['value']);
                        $newNode->setAttribute('form', $form['form']);
                        }
                }
                if ($form['tag'] == 'textarea' 
                    || $form['tag'] == 'label'
                    || $form['tag'] == 'button') {
                    if (empty($form['value'])) {
                        //to avoid empty textarea node
                        $form['value'] = ' ';
                    }
                    $newNode->nodeValue = $form['value'];
                } else if ($form['tag'] == 'datalist') {
                    foreach ($form['options'] as $opt){
                        $option = $html->createElement('option');
                        $option->setAttribute('value', $opt);
                        $dataList->appendChild($option);
                    }
                    //change the $form['tag'] value to style the associated
                    //input element
                    $form['tag'] = 'input';
                } else if ($form['tag'] == 'select') {
                    if ($form['multi'] == "true") {
                        $newNode->setAttribute('multiple', 'true');
                    }
                    foreach ($form['options'] as $opt){
                        $option = $html->createElement('option', $opt[0]);
                        $option->setAttribute('value', $opt[1]);
                        if ($opt[2] == "selected") {
                            $option->setAttribute('selected', 'selected');
                        }
                        $newNode->appendChild($option);
                    }
                } else if ($form['type'] == 'checkbox') {
                    $setWidth = false;
                    if ($form['state'] == 'checked'
                        || $form['curState'] == 'checked') {
                        $newNode->setAttribute('checked', 'checked');
                    }
                    if (!empty($form['label'])) {
                        $formLabel = $html->createElement('label', 
                                                          $form['label']);
                    }
                }  else if ($form['type'] == 'radio') {
                    $setWidth = false;
                    if ($form['selected'] == 'true'
                        || $form['curSelected'] == 'true') {
                        $newNode->setAttribute('checked', 'checked');
                    }
                    if (!empty($form['label'])) {
                        $formLabel = $html->createElement('label', 
                                                          $form['label']);
                    }
                } else if ($form['type'] == 'image') {
                    $src = $this->_imagePath($form['image']);
                    $newNode->setAttribute('src', $src);
                }
                //we need to parse here the styles because we need to clone
                //graphic and text styles and also take into account the height
                //only for textareas
                $graph = $odfNode->getAttribute('draw:style-name');
                if (isset($this->_style['div.' . $graph . ' *'])) {
                    $this->_style[$form['tag'] . '.' . $graph] = 
                            $this->_style['div.' . $graph . ' *'];
                }
                $class = $graph . ' ';
                $text = $odfNode->getAttribute('draw:text-style-name');
                if (isset($this->_style['p.' . $text . ' span'])) {
                    $this->_style[$form['tag'] . '.' . $text] = 
                            $this->_style['p.' . $text . ' span'];
                }
                $class .= $text;
                //check for labels and get their style
                if (isset($formLabel) 
                    && isset($this->_style['p.' . $text . ' span'])) {
                    $formLabel->setAttribute('style', 
                                      $this->_style['p.' . $text . ' span']);
                }
                //also run for the select and datalist options
                if ($form['tag'] == 'select' || $form['tag'] == 'select'){
                    if (isset($this->_style['p.' . $text . ' span'])) {
                        $optNodes = $newNode->childNodes;
                        foreach ($optNodes as $optNode) {
                            $optNode->setAttribute('style', 
                                      $this->_style['p.' . $text . ' span']);
                        }
                    }
                }
                //set the generic class tag
                $newNode->setAttribute('class', $this->_rep($class));
                $style = '';
                $width = $odfNode->getAttribute('svg:width');
                if (!empty($width) && $setWidth) {
                    $style .= 'width: ' .  $width .';';
                }
                $height = $odfNode->getAttribute('svg:height');
                if (!empty($height) && $form['tag'] == 'textarea') {
                    $style .= 'height: ' .  $height .';';
                }
                $anchorType = $odfNode->getAttribute('text:anchor-type');
                if ($anchorType != 'as-char') {
                    $style .= 'display: block';
                } else {
                    $style .= 'display: inline; float: none !important';
                }
                if (!empty($style)) {
                    $newNode->setAttribute('style', $style);
                }
                break;
            case 'form:formatted-text':
                $odfTag = 'form:text';
            case 'form:number':               
            case 'form:text':
            case 'form:password':
            case 'form:file':
            case 'form:image':
            case 'form:date':
            case 'form:time':
            case 'form:checkbox':
            case 'form:radio':
                $type = \substr($odfTag, 5);
                $input_name = $odfNode->getAttribute('form:name');
                $control_id = $odfNode->getAttribute('form:id');
                //overwrite it if the xml:id attribute is defined
                $control_id = $odfNode->getAttribute('xml:id');
                $value = $odfNode->getAttribute('form:value');
                $state = $odfNode->getAttribute('form:state');
                $curState = $odfNode->getAttribute('form:current-state');
                $selected = $odfNode->getAttribute('form:selected');
                $curSelected = $odfNode->getAttribute('form:current-selected');
                $image = $odfNode->getAttribute('form:image-data');
                $label = $odfNode->getAttribute('form:label');
                $this->_forms[$control_id] = array();
                $form =& $this->_forms[$control_id];
                $form['form'] = $this->_currentForm;
                $form['tag'] = 'input';
                $form['type'] = $type;
                $form['name'] = $input_name;
                $form['value'] = $value;
                $form['state'] = $state;
                $form['curState'] = $curState;
                $form['selected'] = $selected;
                $form['curSelected'] = $curSelected;
                $form['image'] = $image;
                $form['label'] = $label;
                if ($type == 'text') {
                    //we need to check if it is multiline to overwrite
                    //certain values
                    $query = './form:properties/form:property';
                    $query .= '[@form:property-name="MultiLine"]';
                    $multiline = $this->_xpath->query($query, $odfNode);
                    if ($multiline->length > 0) {
                       $form['tag'] = 'textarea'; 
                    }
                }
                $newNode = $htmlNode;
                $append = false;
                $tag = false;
                break;
            case 'form:textarea':
                $input_name = $odfNode->getAttribute('form:name');
                $control_id = $odfNode->getAttribute('form:id');
                //overwrite it if the xml:id attribute is defined
                $control_id = $odfNode->getAttribute('xml:id');
                $value = $odfNode->getAttribute('form:value');
                $this->_forms[$control_id] = array();
                $form =& $this->_forms[$control_id];
                $form['form'] = $this->_currentForm;
                $form['tag'] = 'textarea';
                $form['name'] = $input_name;
                $form['value'] = $value;
                $newNode = $htmlNode;
                $append = false;
                $tag = false;
                break;
            case 'form:fixed-text':
                $input_name = $odfNode->getAttribute('form:name');
                $control_id = $odfNode->getAttribute('form:id');
                //overwrite it if the xml:id attribute is defined
                $control_id = $odfNode->getAttribute('xml:id');
                $value = $odfNode->getAttribute('form:value');
                $this->_forms[$control_id] = array();
                $form =& $this->_forms[$control_id];
                $form['form'] = $this->_currentForm;
                $form['tag'] = 'label';
                $form['name'] = $input_name;
                $form['value'] = $value;
                $newNode = $htmlNode;
                $append = false;
                $tag = false;
                break;
            case 'form:combobox':
                $input_name = $odfNode->getAttribute('form:name');
                $control_id = $odfNode->getAttribute('form:id');
                //overwrite it if the xml:id attribute is defined
                $control_id = $odfNode->getAttribute('xml:id');
                $value = $odfNode->getAttribute('form:value');
                $this->_forms[$control_id] = array();
                $form =& $this->_forms[$control_id];
                $form['form'] = $this->_currentForm;
                $form['tag'] = 'datalist';
                $form['name'] = $input_name;
                $form['value'] = $value;
                //let us get now the datalist options
                $form['options'] = array();
                $itemNodes = $odfNode->getElementsByTagName('item');
                foreach ($itemNodes as $item) {
                    $label = $item->getAttribute('form:label'); 
                    $form['options'][] = $label;
                }
                $newNode = $htmlNode;
                $append = false;
                $tag = false;
                break;
            case 'form:listbox':
                $input_name = $odfNode->getAttribute('form:name');
                $control_id = $odfNode->getAttribute('form:id');
                //overwrite it if the xml:id attribute is defined
                $control_id = $odfNode->getAttribute('xml:id');
                $value = $odfNode->getAttribute('form:value');
                $this->_forms[$control_id] = array();
                $form =& $this->_forms[$control_id];
                $form['form'] = $this->_currentForm;
                $form['tag'] = 'select';
                $form['name'] = $input_name;
                $form['value'] = $value;
                //check if it is multivalued
                $form['multi'] = $odfNode->getAttribute('form:multiple');
                //let us get now the select options
                $form['options'] = array();
                $itemNodes = $odfNode->getElementsByTagName('option');
                foreach ($itemNodes as $item) {
                    $label = $item->getAttribute('form:label'); 
                    $val = $item->getAttribute('form:value'); 
                    $sel = $item->getAttribute('form:selected');
                    $curSel = $item->getAttribute('form:current-selected');
                    if ($sel == 'true' || $curSel == 'true') {
                        $selected = 'selected'; 
                    } else {
                        $selected = '';
                    }
                    $form['options'][] = array($label, $val, $selected);
                }
                $newNode = $htmlNode;
                $append = false;
                $tag = false;
                break;
            case 'form:button':
                $input_name = $odfNode->getAttribute('form:name');
                $control_id = $odfNode->getAttribute('form:id');
                //overwrite it if the xml:id attribute is defined
                $control_id = $odfNode->getAttribute('xml:id');
                $value = $odfNode->getAttribute('form:label');
                $this->_forms[$control_id] = array();
                $form =& $this->_forms[$control_id];
                $form['form'] = $this->_currentForm;
                $form['tag'] = 'button';
                $form['type'] = 'submit';
                $form['name'] = $input_name;
                $form['value'] = $value;
                $newNode = $htmlNode;
                $append = false;
                $tag = false;
                break;
            case 'form:hidden':
                $input_name = $odfNode->getAttribute('form:name');
                $value = $odfNode->getAttribute('form:value');
                $newNode = $html->createElement('input');
                $newNode->setAttribute('type', 'hidden');
                $newNode->setAttribute('name', $input_name);
                $newNode->setAttribute('value', $value);
                break;
            case 'draw:g':
                if ($this->_SVG){
                    $baseNode = $html->createElement('div');                    
                    $newNode = $this->_SVGParser->render($baseNode, 
                                                         $odfNode,
                                                         $this->_style);
                } else {
                    $newNode = $html->createElement('span');
                }
                break;
            
            case 'draw:custom-shape':
                if ($this->_SVG){
                    $baseNode = $html->createElement('div');                    
                    $newNode = $this->_SVGParser->render($baseNode, 
                                                         $odfNode,
                                                         $this->_style);
                } else {
                    $newNode = $html->createElement('span');
                }
                break;
            
            case 'draw:connector':
            case 'draw:line':
                $baseNode = $html->createElement('svg');                    
                $newNode = $this->_SVGParser->render($baseNode, 
                                                     $odfNode,
                                                     $this->_style);
                break;
            
             
            default:
                $newNode = $htmlNode;
                $append = false;
                $tag = false;
        }
        
        if ($append) {
            $htmlNode->appendChild($newNode);
            if (isset($formLabel)) {
                $htmlNode->appendChild($formLabel);
            }
        } 
        if (($odfNode->hasAttributes() 
             || $odfNode->nodeName == 'text:list-item')
            && !empty($newNode)) {
            $this->_parseODFAttributes($odfNode, $newNode);
        }
        
        return $newNode;

    }
    
    /**
     * Recursively merges parent styles
     *
     * @return void
     * @access private
     */
    private function _mergeParentStyles()
    {
        $num = \count($this->_parentStyle);
        if ($num > 0) {
            foreach ($this->_parentStyle as $key => $value) {
                if (!isset($this->_parentStyle[$value])) {
                    //the parent style has not itself a parent style
                    if (isset($this->_style[$value])) {
                        if (isset($this->_style[$key])) {
                            $this->_style[$key] = $this->_style[$value] 
                                                  . $this->_style[$key];
                        } else {
                            $this->_style[$key] = $this->_style[$value];
                        }
                    }
                    //we have to take care of the case that there is no span
                    //but the parent has an span style
                    if (!isset($this->_style[$key . ' span'])
                        && isset($this->_style[$value . ' span'])) {
                        $this->_style[$key . ' span'] = 
                                $this->_style[$value . ' span'];
                    }
                    unset($this->_parentStyle[$key]);
                }
            }
            $this->_mergeParentStyles();
        }
    }
    
    /**
     * Parses the footnote/endnote nodes
     *
     * @param DOMNode $node
     * @return void
     * @access private
     */
    private function _note($node)
    {
        $data = array();
        $data['id'] = $node->getAttribute('text:id');
        $data['type'] = $node->getAttribute('text:note-class');
        if ($data['type'] == 'endnote'){
            $this->_endnoteCounter++;
            $ref = self::rowLetter($this->_endnoteCounter);
        } else {
            $this->_footnoteCounter++;
            $ref = $this->_footnoteCounter;
        }
        $cits = $node->getElementsByTagName('note-citation');
        if ($cits->length > 0) {
            $citation = $cits->item(0)->nodeValue;
            if (empty($citation)) {
                $citation = $cits->item(0)->getAttribute('text:label');
            }
            if (empty($citation)) {
                $citation = $ref;
            }
        }
        $data['ref'] = $citation;
        
        $noteBody= $node->getElementsByTagName('note-body');
        if ($noteBody->length > 0) {
            $html = $this->_body->createElement('div');
            $html->setAttribute('id', $data['id']);
            $html->setAttribute('class', 'defaultNote');
            $html->setAttribute('style', 'clear:left');
            $reference = $this->_body->createElement('div');
            $refStyle = 'display:table-cell; font-size: 10pt;';
            $reference->setAttribute('style', $refStyle);
            $sup = $this->_body->createElement('sup', $data['ref']);
            $reference->appendChild($sup);
            $html->appendChild($reference);
            $container = $this->_body->createElement('div');
            $contStyle = 'display:table-cell';
            $container->setAttribute('style', $contStyle);
            $childs = $noteBody->item(0)->childNodes;
            foreach ($childs as $child) {
                $this->_parseODFNode($child, $container);
            }
            $html->appendChild($container);
            if ($data['type'] == 'endnote'){
                $this->_endnotes[] = $html;
            } else {
                $this->_footnotes[] = $html;
            }
        } 
        return $data;  
    }
    
    /**
     * Parses the comment nodes
     *
     * @param DOMNode $node
     * @return void
     * @access private
     */
    private function _comment2HTML($node, $cid)
    {
        $data = array();
        //creator
        $creator = '';
        $creatorNodes = $node->getElementsByTagName('creator');
        if ($creatorNodes->length > 0) {
            $creator = $creatorNodes->item(0)->nodeValue;
        }
        //date
        $date = '';
        $dateNodes = $node->getElementsByTagName('date');
        if ($dateNodes->length > 0) {
            $date = $dateNodes->item(0)->nodeValue;
        }
        /*if (empty($creator)) {
            $data['creator'] = 'Comment';
        } else {
            $data['creator'] = 'Comment by ' . $creator;
        }*/
        $data['date'] = $date;
        
        //parse the content
        $html = $this->_body->createElement('div');
        $html->setAttribute('id', 'div_' . $cid);
        $html->setAttribute('style', 'display: none');
        $childs = $node->childNodes;
        foreach ($childs as $child) {
            $this->_parseODFNode($child, $html);
        }
        $data['content'] = $html;
        
        //generate the required javascript
        $data['script'] = '$("#' . $cid . '").';
        $data['script'] .= 'webuiPopover({';
        if (isset($data['creator'])) {
            $data['script'] .= 'title:"' . $data['creator'] . '",';   
        }
        $data['script'] .= 'content: $("#div_' . $cid . '").html(),';
        $data['script'] .= 'trigger:"hover", backdrop: false});';
        
        return $data;
    }
    
    /**
     * Grabs the internal path to the object preparse the data and returns:
     *  NULL: if the object is not to be parsed
     *  an array with the type of object and the xml data
     *
     * @param string $srcODF
     * @return mixed
     * @access private
     */
    private function _objPath($srcODF)
    {
        //TODO: take into account other type of objects not just charts
        //preparse the path
        if (\substr($srcODF, 0, 2) == './') {
            $srcODF = \substr($srcODF, 2);
        }
        if (\substr($srcODF, -1) != '/') {
            $srcODF .= '/';
        }
        $path = $srcODF . 'content.xml';

        if (isset($this->_doc->template[$path])) {
            $data = $this->_doc->template[$path];
            //check if it is a chart
            if (\strpos($data, 'office:chart') !== false) {
                return array('object' => 'chart', 'data' => $data);
            } else if (\strpos($data, '<math') !== false) {
                return array('object' => 'math', 'data' => $data);
            } else if (\strpos($data, '<mml:math') !== false) {
                return array('object' => 'oomath', 'data' => $data);
            }
        } else {
            return NULL;
        }
        return NULL;
    }
    
    /**
     * Parses the text decoration options
     *
     * @param DOMNode $odfNode
     * @param DOMNode $htmlNode
     * @return string
     * @access private
     */
    private function _parentListStyle($odfNode, $htmlNode)
    {
        $parentNode = $odfNode->parentNode;
        $prevSibling = $odfNode->previousSibling;
        $listStyle = 'display: table-cell;';
        $listStyle .= 'margin-left:0 !important;';
        $listStyle .= 'text-indent: 0 !important;';
        if (!empty($parentNode) 
            && $parentNode->nodeName == 'text:list-item'
            && empty($prevSibling)) {
            $htmlNode->setAttribute('style', $listStyle);
            if (isset($this->_style['p span'])){
                $font = self::extractSingleProperty('font-family', 
                                                    $this->_style['p span']);
                $size = self::extractSingleProperty('font-size', 
                                                    $this->_style['p span']);
                $color = self::extractSingleProperty('color', 
                                                    $this->_style['p span']);
            }
        }

    }
    
    /**
     * Parses the background image path
     *
     * @param string $attr
     * @param string $src
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseBackgroundURL($attr, $src, $important = false)
    {   
        if (!empty($src)) {
            $style = $this->_imagePath($src);
            return 'background-image: url(' . $style . ');';
        }
    }
    
    /**
     * Parses the background image path
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseBackgroundRepeat($name, $value, $important = false)
    {   
        if ($value == 'stretch') {
            return 'background-size: cover;';
        } else if ($value == 'repeat') {
            return 'background-repeat: repeat;';
        } else if ($value == 'no-repeat') {
            return 'background-repeat: no-repeat;';
        }
    }
    
    /**
     * Parses the fo:border and fo:border-* because Chrome does not render
     * borders thinner than 1px
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseBorders($name, $value, $important = false)
    {   
        $temp = explode(':', $name);
        $prop = \array_pop($temp);
        $regex = '/([0-9\.\-]+)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)*/i';
        \preg_match($regex, $value, $matches);
        $data = '';
        if (isset($matches[1]) && isset($matches[2])) {
            $data .= $matches[1];
            $data .= $matches[2];
        }
        if (!empty($data)) {
           $width = self::convertUnits('px', $data);
           if ($width < 1 && $width > 0) {
               $value = \str_replace($data, '1px', $value);
           } else if ($width == 0) {
               return;
           }
        } 
        $style = $prop . ': ' . $value;
        if ($important) {
            $style .= ' !important;';
        } else {
            $style .= ';';
        }
        return $style;
    }
    
    /**
     * Parses the column count property
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseColumnCount($name, $value, $important = false)
    {   
        //we need a method to take care of the special case of 1 because
        //browsers get confused if there is a page-break:column afterwards
        //allowing for the creation of a new column even if the count is set
        //explicitely to 1
        if ($value == 1) {
            $style = 'column-count: initial';
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        } else {
            $style = 'column-count: ' . $value;
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        } 
        return $style;
    }
    
    /**
     * Parses the text decoration options
     *
     * @param string $line
     * @param string $data
     * @param boolean $important
     * @return string
     * @access private
     */
    private function _parseDecoration ($line, $data,  $important = false)
    {   
        $style = '';
        $lines = array('style:text-underline-style' => 'underline',
                       'style:text-overline-style' => 'overline', 
                       'style:text-line-through-style' => 'line-through', 
                       'style:text-line-through-text-style' => 'line-through', 
                       );
        if (isset($lines[$line]) && $data != 'none') {
            $style .= 'text-decoration: ' . $lines[$line];
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        } else if ($data == 'none') {
            $style .= 'text-decoration: none';
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        }
        $types = array('none' => 'none',
                       'dash' => 'dashed', 
                       'dot-dash' => 'dotted', 
                       'dot-dot-dash' => 'dotted',  
                       'dotted' => 'dotted',  
                       'long-dash' => 'dashed',  
                       'solid' => 'solid',  
                       'wave' => 'wavy',
                       );
        if (isset($types[$data])) {
            $style .= 'text-decoration-style: ' . $types[$data];
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        }
        return $style;
    }
    
    /**
     * Parses the fill SVG property
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseFill($name, $value, $important = false)
    {   
        if ($value == 'none') {
            $style = 'fill: none';
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        } else if ($value == 'bitmap') {
            $style = 'fill-opacity: 0';
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
            $style .= 'fill: transparent';
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        } else {
            $style = '';
        }
        return $style;
    }
    
    /**
     * Parses the font family property
     *
     * @param string $name
     * @param string $value
     * @param boolean $important
     * @return string
     * @access private
     */
    private function _parseFontFamily($name, $value, $important = false)
    {   
        //some systems introduce an extra number in standard font names so
        //we should remove them
        $numbers = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
        $value = str_replace($numbers, '', $value);
        $style = 'font-family: ' . $value;
        if ($important) {
            $style.= ' !important;';
        } else {
            $style .= ';';
        }
        return $style;
    }
    
    /**
     * Parses the style:mirror property
     *
     * @param string $name
     * @param string $value
     * @param boolean $important
     * @return string
     * @access private
     */
    private function _parseMirror($name, $value, $important = false)
    {   
        $style = '';
        if ($value == 'horizontal' ||
            $value == 'horizontal-on-even' ||
            $value == 'horizontal-on-odd') {
            $style = 'transform: scaleX(-1)';
        } else if ($value == 'vertical') {
            $style = 'transform: scaleY(-1)';
        }
        if (!empty($style) && $important) {
            $style.= ' !important;';
        } else if (!empty($style)) {
            $style .= ';';
        }
        return $style;
    }
    
    /**
     * Parses the text-position property
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseTextPosition($name, $value, $important = false)
    {   
        $tmp = \explode(' ', \trim($value));
        $pos = $tmp[0];
        $percentage = $tmp[1];
        if(!empty($tmp[1])) {
            $percentage = $tmp[1];
        } else {
            $percentage = '65%';
        }
        if ($pos == 'sub') {
            return 'vertical-align: sub; font-size: ' . $percentage . ' !important;';
        } else if ($pos == 'super') {
            return 'vertical-align: super; font-size: ' . $percentage . ' !important;';
        }
    }
    
    /**
     * Parses the headers and footers ODF nodes
     *
     * @param DOMNode $node
     * @param string $master
     * @param string $type
     * @return void
     * @access private
     */
    private function _parseHeaderFooter($node, $master, $type)
    {
        $nodes = $node->getElementsByTagName($type);
        if ($nodes->length > 0) {
            $header = $nodes->item(0);
            $display = $header->getAttribute('style:display');
            if ($display === false || $display == 'false') {
                $style = 'display: none';
            }
            $html = $this->_body->createElement($type);
            if (!empty($style)) {
                $html->setAttribute('style', $style);
            }
            $this->_parseODFNode($header, $html);
            $this->_masterLayout[$master][$type] = $html;
        }
    }
    
    /**
     * Parses the horizontal position property of a frame
     *
     * @param DOMNode $node
     * @return void
     * @access private
     */
    private function _parseHorizPos($node)
    {
        $wrap = $node->getAttribute('style:wrap');
        if ($wrap != 'none') {
            $horpos = $node->getAttribute('style:horizontal-pos');
            if ($horpos == 'right' || $horpos == 'outside') {
                $float = 'right';
            } else if ($horpos == 'center'){
                $style = 'float: none; display: block;';
                $style .= 'margin-left: auto; margin-right: auto;';
                return $style;
            }else {
                $float = 'left';
            }
            return 'float: ' . $float . ';';
        }         
    }
    
    /**
     * Generates an array that stores teh info related to teh clipping process
     *
     * @param DOMNode $node
     * @return void
     * @access private
     */
    private function _parseClip($node)
    {
        $clip = $node->getAttribute('fo:clip');
        $name = $node->parentNode->getAttribute('style:name');
        $this->_clippedImages[$name] = $clip;
    }
    
    /**
     * Generates a line property in CSS format: width|style|color
     *
     * @param DOMNode $node
     * @return void
     * @access private
     */
    private function _parseLine($node)
    {
        $values = array('color' => '#000000',
                        'width' => '1pt',
                        'style' => 'solid',
                        );
        
        $color = $node->getAttribute('style:color');
        if (empty($color)) {
            $color = $values['color'];
        }
        $style = $node->getAttribute('style:style');
        if (empty($style)) {
            //TODO: check for available CSS values
            $style = $values['style'];
        }
        $width = $node->getAttribute('style:width');
        if (empty($width)) {
            $width = $values['width'];
        }
        
        return $width . ' ' . $style . ' ' . $color;
    }
    
    /**
     * Generates a line-height property
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseLineHeight($name, $value, $important = false)
    {   
        $temp = explode(':', $name);
        $prop = \array_pop($temp);
        $regex = '/([0-9\.\-]+)\s*(%)*/i';
        \preg_match($regex, $value, $matches);
        if (isset($matches[1]) && isset($matches[2])) {
            $value = \floatval($matches[1])/100;
        }
		$regex = '/([0-9\.\-]+)\s*(px|em|rem|ex|in|cm|mm|pt|pc)*/i';
        \preg_match($regex, $value, $matches);
        if (isset($matches[1]) && isset($matches[2])) {
            $value = \floatval($this->_scaleLineHeight) * \floatval($matches[1]) . $matches[2];
        }
        $style = $prop . ': ' . $value;
        if ($important) {
            $style .= ' !important;';
        } else {
            $style .= ';';
        }

        return $style;
    }
    
    /**
     * This method parses the attributes of an ODF node and translates tehm into
     * HTML5 attributes
     *
     * @param DOMNode $odfNode
     * @param DOMNode $node
     * @return void
     * @access private
     */
    private function _parseODFAttributes($odfNode, $node)
    { 
        $odfTag = $odfNode->nodeName;
        $removeNumbering = false;
        //check for master styles
        switch ($odfTag) {
            case 'text:a':
            case 'draw:a':
                $href = $odfNode->getAttribute('xlink:href');
                $node->setAttribute('href', $href);
                $target = $odfNode->getAttribute('office:target-frame-name');
                $node->setAttribute('target', $target);
                $title = $odfNode->getAttribute('office:title');
                $node->setAttribute('title', $title);
                //TODO: parse text:visited-style-name 
                break;
            case 'text:h':
                $listHeader = $odfNode->getAttribute('text:is-list-header');
                if ($listHeader == true) {
                    $removeNumbering = true;
                }
                $restart = $odfNode->getAttribute('text:restart-numbering');
                if ($restart == true) {
                    $num = $odfNode->getAttribute('text:start-value');
                    //TODO
                    $reset = 'hh' . $odfNode->getAttribute('text:outline-level');
                    $reset .= ' ' . (intval($num) - 1);
                    $node->setAttribute('style', 'counter-reset:' . $reset);
                }
            case 'text:p':
            case 'text:numbered-paragraph':
            case 'text:span':
                $class = "";
                //because of the type of inheritance of ODF styles we should 
                //check if the current span is the child of another span field
                //and enforce style inheritance
                $parentNode = $odfNode->parentNode;
                if ($parentNode->nodeName == 'text:span') {
                    $parentStyle = $parentNode->getAttribute('text:style-name');
                    $parent = true;
                    $class .= $parentStyle . ' ';
                } else {
                    $parent = false;
                }
                $classes = $odfNode->getAttribute('text:class-names');
                if (!empty($classes)) {
                   $class .= $classes . ' ' ;
                }
                $style = $odfNode->getAttribute('text:style-name');
                if (!empty($style)) {
                    $class .= $style;
                    //because of a LibreOffice bug we have to check that the
                    //master style is not within a footnote because in that case
                    //it should be ignored
                    if ($parentNode->nodeName != 'text:note-body'){
                        $this->_check4MasterStyle($style);
                    }
                }
                if (!empty($class)) {
                    if ($removeNumbering){
                        $class .= ' removeNumbering ';
                    }
                    $node->setAttribute('class', $this->_rep($class));
                }
                //when we have parent classes we should enforce the last span
                //styles so we include a style attribute
                if ($parent 
                    && isset($this->_style['span.' . $style])) {
                    //check that first child is a span node or a text node
                    $fChild = $odfNode->firstChild;
                    if (!empty($fChild) &&
                        ($fChild->nodeName == 'text:span'
                        || $fChild->nodeName == '#text')){
                        $node->setAttribute('style', 
                                            $this->_style['span.' . $style]);
                    }
                }
                $id = $odfNode->getAttribute('xml:id');
                if (!empty($id)) {
                    $node->setAttribute('id', $id);
                }
                //TODO: if the attribute text:list-style-name is set we have
                //to overwrite the list styles with a > p selector of
                //higher specificity
                break;
            case 'text:section':
                $style = $odfNode->getAttribute('text:style-name');
                if (!empty($style)) {
                    $node->setAttribute('class', $this->_rep($style));
                    $this->_check4MasterStyle($style);
                }
                $id = $odfNode->getAttribute('xml:id');
                if (!empty($id)) {
                    $node->setAttribute('id', $id);
                }
                //overwrite the id attribute if the section is named
                $id = $odfNode->getAttribute('text:name');
                if (!empty($id)) {
                    $node->setAttribute('id', $id);
                }
                break;
            case 'table:table-cell':
                $format = $odfNode->getAttribute('office:value-type');
                if(!empty($format)){
                    $node->setAttribute('data-type', $format);
                }
                $rows = $odfNode->getAttribute('table:number-rows-spanned');
                if (!empty($rows)) {
                    $node->setAttribute('rowspan', $rows);
                }
                $col = (int) $odfNode->getAttribute('table:number-columns-spanned');
                $col += (int) $odfNode->getAttribute('table:number-columns-repeated');
                if (!empty($col)) {
                    $node->setAttribute('colspan', $col);
                }
            case 'table:table':
                $tableName = $odfNode->getAttribute('table:name');
                if (!empty($tableName)) {
                    $node->setAttribute('data-table-name', $tableName);
                }
            case 'table:table-row':
                $style = $odfNode->getAttribute('table:style-name');
                if (!empty($style)) {
                    $node->setAttribute('class', $this->_rep($style));
                    $this->_check4MasterStyle($style);
                }
                $id = $odfNode->getAttribute('xml:id');
                if (!empty($id)) {
                    $node->setAttribute('id', $id);
                }
                break;
            case 'table:table-column':
                $st = $odfNode->getAttribute('table:default-cell-style-name');
                if (!empty($st)) {
                    $node->setAttribute('class', $this->_rep($st));
                }
                //overwrite the default style if defined
                $style = $odfNode->getAttribute('table:style-name');
                if (!empty($style)) {
                    $prevStyle = $node->getAttribute('class');
                    $class = $prevStyle . ' ' . $this->_rep($style);
                    $node->setAttribute('class', $class);
                    $this->_check4MasterStyle($style);
                }
                $id = $odfNode->getAttribute('xml:id');
                if (!empty($id)) {
                    $node->setAttribute('id', $id);
                }
                $cols = $odfNode->getAttribute('table:number-columns-repeated');
                if (!empty($cols)) {
                    $node->setAttribute('span', $cols);
                }
                break;
            case 'text:list':
                $style = $odfNode->getAttribute('text:style-name');
                if (!empty($style)) {
                    $node->setAttribute('class', $this->_rep($style));
                    $this->_check4MasterStyle($style);
                }
                $id = $odfNode->getAttribute('xml:id');
                if (!empty($id)) {
                    $node->setAttribute('id', $id);
                }
                //take into account text:continue-list and
                //text:continue-numbering
                //Beware that although text:continue numbering is deprecated is
                //the one currently used by Word and Libre/Open Office
                $listName = $odfNode->getAttribute('text:parent-list');
                $listDepth = $odfNode->getAttribute('text:list-depth');
                $listCounter = $odfNode->getAttribute('text:list-counter');
                $satt = $node->getAttribute('style');
                $rcounter = 'counter-reset: ul' . $listName .'_';
                $rcounter .= (int) $listDepth + 1;
                $rcounter .= ' ' . $listCounter . ';';
                $satt = $rcounter . $satt;
                if (!empty($listName)) {
                    $node->setAttribute('style', $satt);
                }
                break;
            case 'text:list-item':
                //TODO: take into account text:start-value
                //We have to check if the first child node is a text:list
                //and if so enforce a custom style that removes the unwanted
                //additional bullet
                $firstChildtype = $odfNode->firstChild->nodeName;
                if ($firstChildtype == 'text:list') {
                    $rbf = true;
                } else {
                    $rbf = false;
                }
                $style = $odfNode->getAttribute('text:style-override');
                if (!empty($style)) {
                    if ($rbf) {
                        $node->setAttribute('class', 
                                        $this->_rep($style) . ' removeBullet');        
                    } else {
                        $node->setAttribute('class', $this->_rep($style));
                    }
                    $this->_check4MasterStyle($style);
                } else if ($rbf) {
                    $node->setAttribute('class', 'removeBullet');
                }
                $id = $odfNode->getAttribute('xml:id');
                if (!empty($id)) {
                    $node->setAttribute('id', $id);
                }
                break;
            case 'form:form':
                //get the form name
                $formName = $odfNode->getAttribute('form:name');
                if (empty($formName)) {
                    $formName = self::generateId('form_');
                }
                $this->_currentForm = $formName;
                $node->setAttribute('name', $formName);
                $node->setAttribute('id', $formName);
                //get the form action, method and target
                $action = $odfNode->getAttribute('xlink:href');
                $node->setAttribute('action', $action);
                $method = $odfNode->getAttribute('form:method');
                $node->setAttribute('method', $method);
                $target = $odfNode->getAttribute('office:target-frame');
                $node->setAttribute('target', $target);
                break;
            case 'draw:control':
                //the attributes have to be parsed with the generation of the 
                //node because they may depend on the original form tag
                break;
            case 'draw:g':
                $style = $odfNode->getAttribute('draw:style-name');
                /*if (isset($this->_style['img.' .$style])) {
                    $this->_style['div.' .$style] = 
                            $this->_style['img.' .$style];
                }*/
                
                break;
            case 'draw:custom-shape':
            case 'draw:connector':
            case 'draw:line':
                $style = $odfNode->getAttribute('draw:style-name');
                $pStyle = 'position: relative; z-index: 100;';
                $this->_style['div.' .$style . ' p'] = $pStyle;
                $this->_style['div.' .$style . ' ul'] = $pStyle;
                if (isset($this->_style['div.' .$style])) {
                    //if there was a textarea-horizontal-align different
                    //from left we remove left paddings
                    $rpl = \strpos($this->_style['div.' .$style], 'rpl;');
                    if ($rpl) {
                        $regex = '/padding-left:[\s0-9a-z\.]*!important;/';
                        $this->_style['div.' .$style] = \preg_replace($regex,
                                            '', $this->_style['div.' .$style]);
                        $this->_style['div.' .$style] = \str_replace('rpl;',
                                            '', $this->_style['div.' .$style]);
                    }
                    //if there was a textarea-vertical-align different
                    //from top we remove top paddings ansd relocate childs
                    $regex = '/vertical-align:\s*([a-z]+)\s*!important;/';
                    $exp = $this->_style['div.' .$style];
                    \preg_match($regex, $exp, $matches);
                    if (\count($matches) > 1) {
                        $va = $matches[1];
                        if ($va == 'middle') {
                            $css = 'top: 50%; transform: translateY(-50%);';
                            $this->_style['div.' .$style . ' p'] .= $css;
                            $regex = '/padding-top:\s*([a-z0-9\.]+)';
                            $regex .= '\s*!important;/';
                            $exp = \preg_replace($regex, '', $exp);
                        } else if ($va == 'bottom') {
                            //we use the same css because if not muiltiline
                            //content may move out its containing box
                            $css = 'top: 50%; transform: translateY(-50%);';
                            $this->_style['div.' .$style . ' p'] .= $css;
                            $regex = '/padding-top:\s*([a-z0-9\.]+)';
                            $regex .= '\s*!important;/';
                            $exp = \preg_replace($regex, '', $exp);
                        }                       
                    }
                    
                    $this->_style['div.' .$style] = $exp;
                    //we need now to rescale the stroke width by a factor
                    //in order to show it properly in a browser
                    $regex = '/stroke-width:\s*([0-9\.]*)([a-z !]*)/';
                    \preg_match($regex, $exp, $matches);
                    if (count($matches) > 2) {
                        $newDim = $matches[1] *  1.5;
                        $replace = 'stroke-width: ' . $newDim . $matches[2];
                        $exp = str_replace($matches[0], $replace, $exp);   
                    }
                    $this->_style['svg.' .$style] = $exp;
                    
                }
                break;
                
        }
    }
    
    /**
     * This method recursively translate ODF nodes into HTML5 nodes
     *
     * @param DOMNode $odfNode
     * @param DOMNode $htmlNode
     * @param bool $append
     * @return mixed
     * @access private
     */
    private function _parseODFNode($odfNode, $htmlNode, $append = true)
    { 
        $stopOdfNodes = array('text:note-body' => true,
                              'text:note-citation' => true,
                              'text:table-of-content-source' => true,
                              'form:text' => true,
                              'form:textarea' => true,
                              'form:password' => true,
                              'form:file' => true,
                              'form:formated-text' => true,
                              'form:number' => true,
                              'form:date' => true,
                              'form:time' => true,
                              'form:fixed-text' => true,
                              'form:combobox' => true,
                              'form:item' => true,
                              'form:listbox' => true,
                              'form:option' => true,
                              'form:button' => true,
                              'form:image' => true,
                              'form:checkbox' => true,
                              'form:radio' => true,
                              'form:frame' => true,
                              'form:image-frame' => true,
                              'form:hidden' => true,
                              'form:grid' => true,
                              'form:column' => true,
                              'form:value-range' => true,
                              'form:generic-control' => true,
                              'svg:desc' => true,
                              'svg:title' => true,
                              'dc:creator' => true,
                              'dc:date' => true,
                              'table:covered-table-cell' => true,
                              'draw:connector' => true,
                              'draw:line' => true,
                              );
        if (!$this->_SVG){
            $stopOdfNodes['draw:g'] = true;
            $stopOdfNodes['draw:custom-shape'] = true;
        }
        //TODO: check all stop tags and take care of possible  body childs that 
        //are not to be parsed
        $odfTag = $odfNode->nodeName;
        //create and insert the node
        $newNode = $this->_createHTMLNode($odfNode, $htmlNode, $append);
        if ($odfNode->hasChildNodes() 
            && !isset($stopOdfNodes[$odfTag])
            && $newNode !== NULL){
            $childs = $odfNode->childNodes;
            foreach ($childs as $child) {
                //we set $append to true to make sure that childs are inserted
                //in the node
                $this->_parseODFNode($child, $newNode, true);
            }
        }
        if ($append === false 
            && $newNode !== NULL
            && $newNode->nodeName != 'body') {
            return $newNode;
        }
    }
    
    /**
     * This method parses the attributes of the style nodes
     *
     * @param DOMNode $node
     * @param string $style
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseODFProps($node, $style = '', $important = false)
    {
        $parsedChilds = array('style:background-image' => true,
                              'style:columns' => true);
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $name = $attr->nodeName;
                $val = $attr->nodeValue;
                //$val = $attr->nodeValue;
                $temp = \explode(':', $name);
                $namespace = \array_shift($temp);
                $arrayName = $namespace . '_' . 'attributes';
                if (isset(Resources::$$arrayName)) {
                    $prop = Resources::$$arrayName;
                }
                if (isset($prop[$name]) && \is_string($prop[$name])) {
                    $style .= $this->_filterProp($prop[$name],
                                                 $val,
                                                 $important);
                } else if (isset($prop[$name]) && \is_array($prop[$name])) {
                    if (isset($prop[$name]['name'])
                        && isset($prop[$name][$attr->nodeValue])) {
                       $style .= $prop[$name]['name'] . ': ' . 
                                 $prop[$name][$val] ;
                       if ($important) {
                            $style .= ' !important;';
                        } else {
                            $style .= ';';
                        }
                    } else if (isset($prop[$name]['method'])) { 
                        $style .= $this->{$prop[$name]['method']}($name,
                                          $val,
                                          $important); 
                    } else if (isset($prop[$name]['composedProp'])) {
                        $style .= $this->{$prop[$name]['composedProp']}($node); 
                    }
                }
            }
        }
        if ($node->hasChildNodes()) {
            $childs = $node->childNodes;
            foreach ($childs as $child) {
               if (isset($parsedChilds[$child->nodeName])) {
                   //TODO: take care of the path to the image that now
                   //will be broken
                   $style = $this->_parseODFProps($child, $style, $important);
               } else if ($child->nodeName == 'style:drop-cap') {
                   $lines = $child->getAttribute('style:lines');
                   $class = $node->parentNode
                                 ->getAttribute('style:name');
                   if (!empty($lines) && $lines != 1 && !empty($class)){
                        $this->_dropcaps[$class] = $lines;
                   }
               }
            }
        }
        if ($node->nodeName == 'style:paragraph-properties') {
            //In the case of paragraph properties one should remove paddings
            //for borderless pargraphs because they are not rendered by
            //Word, Libre/Open Office
            //One also should take care of paragraph borders that are not 
            //properly rendered in HTML for contiguous paragraphs
            $class = $node->parentNode->getAttribute('style:name');
            $style = $this->_repairParagraphStyles($style, $class);
        }
        
        return $style;
    }
    
    /**
     * This method parses the attributes of the style nodes
     *
     * @param DOMNode $styleNode
     * @return void
     * @access private
     */
    private function _parseODFStyle($styleNode)
    {
        $type = $styleNode->nodeName;
        if ($type == 'style:master-page') {
            $this->_parseODFStyleMaster($styleNode);
        } else if ($type == 'style:page-layout') {
            $this->_parseODFStyleLayout($styleNode);
        } else if ($type == 'text:list-style') {
            $this->_parseODFStyleList($styleNode);
        } else if ($type == 'text:outline-style') {
            $this->_parseODFStyleOutline($styleNode);
        } else if ($type == 'style:default-style') {
            $this->_parseODFDefaultStyles($styleNode);
        } else {
            $this->_parseODFStyleGeneric($styleNode);
        }        
    }
    
    /**
     * This method parses the default document styles
     *
     * @param DOMNode $styleNode
     * @return void
     * @access private
     */
    private function _parseODFDefaultStyles($styleNode)
    {
        $tag = array ( 'table' => 'table',
                       'table-row' => 'tr',
                       'table-cell' => 'td, th',
                       'paragraph' => 'p', 
                       'section' => 'section'
        );
        
        $type = $styleNode->getAttribute('style:family');
        
        if (!isset($tag[$type])) {
            return;
        } 
        //let us now extract the properties from the different childs
        $childs = $styleNode->childNodes;
        foreach ($childs as $child) {
            $prop = $child->nodeName;
            if (isset(Resources::$style_childs[$prop])){
                if ($type == 'paragraph') {
                    if ($prop == 'style:paragraph-properties') {
                        $base = 'p';
                    } else if ($prop == 'style:text-properties') {
                        $base = 'p span';
                    } else {
                        $base = 'none';
                    }
                } else if ($type == 'table') {
                    if ($prop == 'style:paragraph-properties') {
                        $base = 'table p';
                    } else if ($prop == 'style:text-properties') {
                        $base = 'table p span';
                    } else if ($prop == 'style:table-properties') {
                        $base = 'table';
                    } else if ($prop == 'style:table-row-properties') {
                        $base = 'table tr';
                    } else if ($prop == 'style:table-cell-properties') {
                        $base = 'table td, table th';
                    } else {
                        $base = 'none';
                    }
                } else if ($type == 'table-row') {
                    if ($prop == 'style:paragraph-properties') {
                        $base = 'table tr p';
                    } else if ($prop == 'style:text-properties') {
                        $base = 'table tr p span';
                    } else if ($prop == 'style:table-row-properties') {
                        $base = 'table tr';
                    } else if ($prop == 'style:table-cell-properties') {
                        $base = 'table tr td, table tr th';
                    } else {
                        $base = 'none';
                    }
                } else if ($type == 'table-cell') {
                    if ($prop == 'style:paragraph-properties') {
                        $base = 'table td p, table th p';
                    } else if ($prop == 'style:text-properties') {
                        $base = 'table td p span, table th p span';
                    } else if ($prop == 'style:table-cell-properties') {
                        $base = 'table td, table th';
                    } else {
                        $base = 'none';
                    }
                } else if ($type == 'section'){
                    if ($prop == 'style:section-properties') {
                        $base = 'div';
                    }
                }
                $this->_defaultStyle[$base] = $this->_parseODFProps($child);
            }            
        }  
    }
    
    /**
     * This method parses the generic ODF styles
     *
     * @param DOMNode $styleNode
     * @return void
     * @access private
     */
    private function _parseODFStyleGeneric($styleNode)
    {
        //TODO: this has to be redone because if they are no child to the style
        //the inheritance is broken!!!!
        $type = $styleNode->nodeName;
        $family = $styleNode->getAttribute('style:family');
        if (isset(Resources::$style_family[$family])) {
            $tag = Resources::$style_family[$family];
        } else {
            $tag = 'div';
        }
        $class = $styleNode->getAttribute('style:name');
        if (!empty($class)) {
            $class = $this->_rep($class);
            $base = $tag . '.' . $class;
        } else {
            $base = $tag;            
        }
        $this->_style[$base] = '';
        //look for parent styles
        $parent  = $styleNode->getAttribute('style:parent-style-name');
        $list = $styleNode->getAttribute('style:list-style-name');
        //store the parent child relationship
        if (!empty($parent)) {
            $this->_parentStyle[$base] = $tag . '.' . $parent;
            $this->_style2style[$class] = $parent;
        }

        //let us now extract the attributes from the different childs
        $childs = $styleNode->childNodes;
        foreach ($childs as $child) {
            $prop = $child->nodeName;
            if (isset(Resources::$style_childs[$prop])
                && !isset(Resources::$stop_parsing_child[$type][$prop])){
                if(Resources::$style_childs[$prop] != $tag) {
                    $style = $base . ' ' . Resources::$style_childs[$prop];
                    $pStyle = Resources::$style_childs[$prop];
                } else {
                    $style = $base;
                    $pStyle = '';
                }
                if ($pStyle != '' || empty($class)) {
                    $this->_style[$style] = $this->_parseODFProps($child);
                } else {
                    //this has to be important props because if not the higher
                    //CSS specificity of other styles will overwrite this
                    //styles
                    $this->_style[$style] = $this->_parseODFProps($child, 
                                                                  '',
                                                                  true);
                }
                //let us look for col styles
                if($tag == 'td' && isset(self::$colCellStyles[$class])) {
                    foreach (self::$colCellStyles[$class] as $name => $val1){
                        //only tds with no class inherit the col attributes in ODF
                        $col = 'table.' . $name . ' tr td:not([class])';
                        foreach(self::$colCellStyles[$class][$name] as $key => $val2){
                            $col .= ':nth-child(' . $key . ')';
                            if (isset($this->_style[$col])) {
                                $this->_style[$col] .= $this->_parseODFProps($child);
                            } else {
                                $this->_style[$col] = $this->_parseODFProps($child);
                            }
                        }
                    }
                }
                if (!empty($parent) /*&& empty($list)*/) {                   
                    //we need to map all possible styles to the parent
                    //even if they are not defined to assure proper inheritance
                    $this->_parentStyle[$base] = $tag . '.' . $parent;
                    $subStyles = array('canvas',
                                       /*'img',*/
                                       'p',
                                       'div',
                                       'table',
                                       'td',
                                       'col',
                                       'tr',
                                       'span');
                    foreach ($subStyles as $value) {
                        if ($value != $tag) {
                            $key = $base . ' ' . $value;
                            $parentStyle = $tag . '.' . $parent . ' ' . $value;
                            $this->_parentStyle[$key] = $parentStyle;
                        }
                    }
                }
            }            
        }
        
    }
    
    /**
     * This method parses the attributes of generic/default style nodes
     *
     * @param DOMNode $styleNode
     * @return void
     * @access private
     */
    private function _parseODFStyleList($styleNode)
    {
        $tag = 'ul';
        $class = $styleNode->getAttribute('style:name');
        if (!empty($class)) {
            $class = $this->_rep($class);
            $base = $tag . '.' . $class;
        } else {
            $base = $tag;            
        }

        //let us now extract the attributes from the different childs
        $childs = $styleNode->childNodes;
        foreach ($childs as $child) {
            $listType = $child->nodeName;
            if ($listType == 'text:list-level-style-bullet') {
                $this->_parseODFStyleListBullet($child, $base);
            } else if ($listType == 'text:list-level-style-number') {
                $this->_parseODFStyleListNumber($child, $base);
            } else if ($listType == 'text:list-level-style-image') {
                $this->_parseODFStyleListImage($child, $base);
            }
        }  
    }
    
    /**
     * This method parses the outline styles
     *
     * @param DOMNode $styleNode
     * @return void
     * @access private
     */
    private function _parseODFStyleOutline($styleNode)
    {
        $childs = $styleNode->getElementsByTagName('outline-level-style');
        $listStyleType = array();
        foreach ($childs as $node) {
            //get the list level
            $level = $node->getAttribute('text:level');
            //get the number format
            $numFormat = $node->getAttribute('style:num-format');
            if (empty($numFormat)) {
                continue;
            }
            //list-style-type equivalences
            $translate = array( '1' => 'decimal',
                                'a' => 'lower-latin',
                                'A' => 'upper-latin',
                                'i' => 'lower-roman',
                                'I' => 'upper-roman',
                                );
            if (!empty($numFormat) && isset($translate[$numFormat])) {
                $listStyleType[$level] = $translate[$numFormat];
            } else {
                $listStyleType[$level] = 'decimal';
            }
            $suffix = $node->getAttribute('style:num-suffix');
            if (empty($suffix)) {
                $suffix = '.';
            }
            $prefix = $node->getAttribute('style:num-prefix');
            $displayLevels = $node->getAttribute('text:display-levels');
            if (empty($displayLevels)) {
                $displayLevels = 1;
            }
            $style = '';
            //in order to accomodate arbitrary numbering we are going to
            //use the before pseudoclass 
            $base = 'h' . $level;
            $baseBefore = $base . ':before';
            $this->_style[$base . ':first-of-type'] = 'counter-reset: ';
            $this->_style[$base . ':first-of-type'] .= 'h' . $base . ';';
            //reset lower level counters
            $clean = 'counter-reset: ';
            for ($k = $level +1; $k < 7; $k++) {
                $clean .= 'hh' . $k . ' ';
            }
            $clean .= ';';
            $this->_style[$base . ':not(:first-of-type)'] = $clean;
            //set the style counters
            $style .= 'counter-increment: h' . $base . ';';
            if (!empty($prefix)) {
                $style .= 'content: "' . $prefix . '" ';    
            } else {
                $style .= 'content: ';
            }
            $j_0 = (int) $level - (int) $displayLevels + 1;
            for ($j = $j_0; $j <= $level; $j++) {
                if (isset($listStyleType[$j])) {
                    $style .= 'counter(hh'. $j . ', ' . $listStyleType[$j].') ';
                    if ($j == $level && !empty($prefix) && $prefix != ' ') {
                        $style .= ' "' .$suffix . '\00a0 ";';
                    } else if ($j == $level) {
                        $style .= ' "' .$suffix . ' ";';
                    } else {
                        $style .= ' "' .$suffix . '" ';
                    }
                }
            }

            //now extract the styling info for the numbered items
            $childs = $node->childNodes;
            foreach ($childs as $child) { 
                $name = $child->nodeName;
                if ($name == 'style:list-level-properties'
                    && $child->hasChildNodes()) {
                    $mL = $child->firstChild->getAttribute('fo:margin-left');

                    if (!empty($mL)){
                        $hstyle = 'margin-left: ' . $mL . ';';
                        $this->_style[$base] = $hstyle;
                    } 
                    $tI = $child->firstChild->getAttribute('fo:text-indent');                      
                }
                $style .= 'display: inline-block;';
                $style .= 'margin-left:' . $tI . ';';
                $style .= 'min-width: ' . self::reverseSign($tI) . ';';
                $this->_style[$baseBefore] = $style;
                if (!empty($parentStyle)) {
                    $this->_parentStyle[$baseBefore] = $parentStyle;
                }
            }           
        } 
    }
    
    /**
     * This method parses the list style attributes for bulleted lists
     *
     * @param DOMNode $node
     * @param string $base
     * @return void
     * @access private
     */
    private function _parseODFStyleListBullet($node, $base)
    {
        //get the list level
        $level = $node->getAttribute('text:level');
        //get the char used as bullet (UTF-8 encoded)
        $content = $node->getAttribute('text:bullet-char');
        //The text style used for the bullet
        $parentStyle = 'span.' . $node->getAttribute('text:style-name');
        //in order to accomodate arbitrary bullets we are going to
        //use the li:before pseudoclass so we have to define no list-style-type
        //at the ul level
        $baseUL = $base;
        $baseLI = $base . ' li';
        $recount = str_replace('.', '', $base);
        if ($level == 1) {
            $this->_style[$baseUL] = 'margin-left: 0;';
            $this->_style[$baseUL] .= 'padding: 0; list-style-type: none;';
        }
        $baseLIBefore = $base . ' li:before';
        
        for ($j = 1; $j < $level; $j++) {
            $baseLI .= ' ul li';
        }
        for ($j = 1; $j <= $level; $j++) {
            $baseUL .= ' ul';
        }
        $baseLIBefore = $baseLI . ':before';
        if (!empty($content)) {
            $content = \json_encode($content);
            $content = \str_replace('u' , '', $content);
            if ($content == '"\f0a7"') {
                $content = '"\25aa"';
                $style = 'content: ' . $content . ';';
            } else if ($content == '"\f0b7"') {
                $content = '"\2022"';
                $style = 'content: ' . $content . ';';
                //$style .= 'font-family: Verdana !important;';
                $style .= 'font-family: Verdana;';
            } else {
                $style = 'content: ' . $content . ';';
            }
        } else {
            $style = '';
        }
        //set the style counters
        //even if this (sub)list is not numbered this bullets may be nested 
        //within a nuembered list and we should make sure that numbering
        //for the container list is not incremented
        $style .= 'counter-increment: ' . $recount . '_' . $level . ';';
        
        //now extract the styling info for the list items
        $childs = $node->childNodes;
        foreach ($childs as $child) {        
            $name = $child->nodeName;
            if ($name == 'style:list-level-properties'
                && $child->hasChildNodes()) {
                //TODO: check differences between values of the attribute
                //text:list-level-position-and-space-mode
                $mL = $child->firstChild->getAttribute('fo:margin-left');
                //we have to remove the margin-left because in CSS the margins
                //of nested li elements add up and this is not the standard
                //ODF behaviour
                if (!empty($mL)){
                    $ulStyle = 'margin-left: -' . $mL . ';';
                } else {
                    $ulStyle = 'margin-left: 0;';
                }
                $ulStyle .= 'padding: 0; list-style-type: none;';
                $this->_style[$baseUL] = $ulStyle;
                //build the li style
                $this->_style[$baseLI] = '';
                if (!empty($mL)) {
                    $this->_style[$baseLI] .= 'margin-left: ' . $mL . ';';
                }
                $tI = $child->firstChild->getAttribute('fo:text-indent');
                if (!empty($tI)) {
                    $this->_style[$baseLI] .= 'text-indent: ' .$tI . ';';
                    $style .= 'margin-left: ' .$tI . ';';
                }           
            } else if ($name == 'style:list-level-properties'
                && !$child->hasChildNodes()){
                //the properties are set like
                //text-indent: text:min-label-width
                //margin-left: text:space-before
                $mL = $child->getAttribute('text:space-before');
                //we have to remove the margin-left because in CSS the margins
                //of nested li elements add up and this is not the standard
                //ODF behaviour
                if (!empty($mL)){
                    $ulStyle = 'margin-left: -' . $mL . ';';
                } else {
                    $ulStyle = 'margin-left: 0;';
                }
                $ulStyle .= 'padding: 0; list-style-type: none;';
                $this->_style[$baseUL] = $ulStyle;
                //build the li style
                $this->_style[$baseLI] = '';
                
                $tI = $child->getAttribute('text:min-label-width');
                if (!empty($tI)) {
                    $this->_style[$baseLI] .= 'text-indent: -' .$tI . ';';
                    $style .= 'margin-left: -' .$tI . ';';
                }
                if (!empty($mL)) {
                    if (empty($tI)){
                      $marginLeft = $mL;
                    } else {
                      $marginLeft = self::sum(array($mL, $tI), 'pt', false); 
                    }
                    $this->_style[$baseLI] .= 'margin-left: ' . $marginLeft . ';';
                }
            }
            $style .= 'display: table-cell;';
            $this->_style[$baseLIBefore] = $style;
            if (!empty($parentStyle)) {
                $this->_parentStyle[$baseLIBefore] = $parentStyle;
            }
        }
        
    }
    
    /**
     * This method parses the list style attributes for lists with
     * "image bullets"
     *
     * @param DOMNode $styleNode
     * @param string $base
     * @return void
     * @access private
     */
    private function _parseODFStyleListImage($node, $base)
    {
    //get the list level
        $level = $node->getAttribute('text:level');
        //get the image source path
        $src = $node->getAttribute('xlink:href');
        //The text style used for the bullet
        $parentStyle = 'span.' . $node->getAttribute('text:style-name');
        //in order to accomodate arbitrary bullets we are going to
        //use the li:before pseudoclass so we have to define no list-style-type
        //at the ul level
        $baseUL = $base;
        $baseLI = $base . ' li';
        if ($level == 1) {
            $this->_style[$baseUL] = 'margin-left: 0;';
            $this->_style[$baseUL] .= 'padding: 0; list-style-type: none;';
        }
        $baseLIBefore = $base . ' li:before';
        for ($j = 1; $j < $level; $j++) {
            $baseLI .= ' ul li';
        }
        for ($j = 1; $j <= $level; $j++) {
            $baseUL .= ' ul';
        }
        $baseLIBefore = $baseLI . ':before';
        $style = 'content: " ";';
        if (!empty($src)) {
            $bg = 'url(' . $this->_imagePath($src, true) . ')';
            $style .= 'background-image: ' . $bg . ';';
            $style .= 'background-size: contain;';
            $style .= 'background-repeat: no-repeat;';
        }         
        
        //now extract the styling info for the list items
        $childs = $node->childNodes;
        foreach ($childs as $child) {        
            $name = $child->nodeName;
            if ($name == 'style:list-level-properties'
                && $child->hasChildNodes()) {
                //TODO: check differences between values of the attribute
                //text:list-level-position-and-space-mode
                //get the required attributes
                $height = $child->getAttribute('fo:height');
                if (!empty($height)) {
                    $style .= 'height: ' . $height . ';';
                }
                $width = $child->getAttribute('fo:width');
                if (!empty($height)) {
                    $style .= 'width: ' . $width . ';';
                }
                $padding = $child->getAttribute('text:space-before');
                if (!empty($padding)) {
                    $style .= 'padding-right: ' . $padding . ';';
                }
                $mL = $child->firstChild->getAttribute('fo:margin-left');
                //we have to remove the margin-left because in CSS the margins
                //of nested li elements add up and this is not the standard
                //ODF behaviour
                if (!empty($mL)){
                    $ulStyle = 'margin-left: -' . $mL . ';';
                } else {
                    $ulStyle = 'margin-left: 0;';
                }
                $ulStyle .= 'padding: 0; list-style-type: none;';
                $this->_style[$baseUL] = $ulStyle;
                //build the li style
                $this->_style[$baseLI] = '';
                if (!empty($mL)) {
                    $this->_style[$baseLI] .= 'margin-left: ' . $mL . ';';
                }
                $tI = $child->firstChild->getAttribute('fo:text-indent');
                if (!empty($tI)) {
                    $this->_style[$baseLI] .= 'text-indent: ' .$tI . ';';
                    $style .= 'margin-left: ' .$tI . ';';
                    //$pR = \str_replace('-', '', $tI);
                    //$style .= 'padding-right: ' . $pR. '; ';
                }           
            } 
            $style .= 'display: table-cell;'; 
            $this->_style[$baseLIBefore] = $style;
            if (!empty($parentStyle)) {
                $this->_parentStyle[$baseLIBefore] = $parentStyle;
            }
        }
    }
    
    /**
     * This method parses the list style attributes for numbererd lists
     *
     * @param DOMNode $styleNode
     * @param string $base
     * @return void
     * @access private
     */
    private function _parseODFStyleListNumber($node, $base)
    {
        //get the list level
        $level = $node->getAttribute('text:level');
        //get the number format
        $numFormat = $node->getAttribute('style:num-format');
        //list-style-type equivalences
        $translate = array( '1' => 'decimal',
                            'a' => 'lower-latin',
                            'A' => 'upper-latin',
                            'i' => 'lower-roman',
                            'I' => 'upper-roman',
                            );
        if (!empty($numFormat) && isset($translate[$numFormat])) {
            $listStyleType = $translate[$numFormat];
        } else {
            $listStyleType = 'decimal';
        }
        $suffix = $node->getAttribute('style:num-suffix');
        if (empty($suffix)) {
            $suffix = '.';
        }
        $prefix = $node->getAttribute('style:num-prefix');
        $displayLevels = $node->getAttribute('text:display-levels');
        if (empty($displayLevels)) {
            $displayLevels = 1;
        }
        $textStyle = $node->getAttribute('text:style-name');
        if (empty($textStyle)) {
            $style = '';
        } else if (isset($this->_style['span.' . $textStyle])) {
            $style = $this->_style['span.' . $textStyle];
        } else {
            $style = '';
        }
        //in order to accomodate arbitrary numbering we are going to
        //use the li:before pseudoclass so we have to define no list-style-type
        //at the ul level
        $baseUL = $base;
        $baseLI = $base . ' li';
        $recount = str_replace('.', '', $base);
        if ($level == 1) {
            $this->_style[$baseUL] = 'counter-reset: ' . $recount .'_1;';
            $this->_style[$baseUL] .= 'margin-left: 0;';
            $this->_style[$baseUL] .= 'padding: 0; list-style-type: none;';
            //$this->_style[$baseUL . ' ~ ' . $baseUL] = 'counter-reset: none !important; ';
        }
        $baseLIBefore = $base . ' li:before';
        for ($j = 1; $j < $level; $j++) {
            $baseLI .= ' ul li';
        }
        for ($j = 1; $j <= $level; $j++) {
            $baseUL .= ' ul';
        }
        $baseLIBefore = $baseLI . ':before';
        //set the style counters
        $style .= 'counter-increment: ' . $recount . '_' . $level . ';';
        if (!empty($prefix)) {
            $style .= 'content: "' . $prefix . '" ';    
        } else {
            $style .= 'content: ';
        }
        $j_0 = (int) $level - (int) $displayLevels + 1;
        for ($j = $j_0; $j <= $level; $j++) {
            $style .= 'counter(' . $recount . '_' . $j . ', ' . $listStyleType. ') ';
            if ($j == $level && !empty($prefix) && $prefix != ' ') {
                $style .= ' "' .$suffix . '\00a0 ";';
            } else if ($j == $level) {
                $style .= ' "' .$suffix . ' ";';
            } else {
                $style .= ' "' .$suffix . '" ';
            }
        }
        
        //now extract the styling info for the list items
        $childs = $node->childNodes;
        foreach ($childs as $child) { 
            $name = $child->nodeName;
            if ($name == 'style:list-level-properties'
                && $child->hasChildNodes()) {
                //TODO: check differences between values of the attribute
                //text:list-level-position-and-space-mode
                $mL = $child->firstChild->getAttribute('fo:margin-left');
                //we have to remove the margin-left because in CSS the margins
                //of nested li elements add up and this is not the standard
                //ODF behaviour
                if (!empty($mL)){
                    /*if(strpos($mL, '-') == 0){
                        //remove the minus sign
                        //$mL = substr($mL, 1);
                        $mL = 0;
                    } else {
                        $mL = '-' . $mL;
                    }*/
                    $ulStyle = 'margin-left: -' . $mL . ';';
                } else {
                    $ulStyle = 'margin-left: 0;';
                }
                $ulStyle .= 'padding: 0; list-style-type: none;';
                $this->_style[$baseUL] = 'counter-reset: ' . $recount .'_';
                $this->_style[$baseUL] .= ((int) $level + 1) . ';';
                $this->_style[$baseUL] .= $ulStyle;
                //build the li style
                $this->_style[$baseLI] = '';
                $tI = $child->firstChild->getAttribute('fo:text-indent');
                if (!empty($mL)) {
                    $this->_style[$baseLI] .= 'margin-left: ' . $mL . ';';
                } 
                if (!empty($tI) ) {
                    $style .= 'margin-left: ' .$tI . ';';
                    $this->_style[$baseLI] .= 'text-indent: ' .$tI . ';';
                }           
            } else if ($name == 'style:list-level-properties'
                && !$child->hasChildNodes()) {
                $mL = $child->getAttribute('text:space-before');
                //we have to remove the margin-left because in CSS the margins
                //of nested li elements add up and this is not the standard
                //ODF behaviour
                if (!empty($mL)){
                    $ulStyle = 'margin-left: -' . $mL . ';';
                } else {
                    $ulStyle = 'margin-left: 0;';
                }
                $ulStyle .= 'padding: 0; list-style-type: none;';
                $this->_style[$baseUL] = 'counter-reset: ' . $recount .'_';
                $this->_style[$baseUL] .= ((int) $level + 1) . ';';
                $this->_style[$baseUL] .= $ulStyle;
                //build the li style
                $this->_style[$baseLI] = '';
                $tI = $child->getAttribute('text:min-label-width');
                if (!empty($tI)) {
                    $this->_style[$baseLI] .= 'text-indent: -' .$tI . ';';
                    $style .= 'margin-left: -' . $tI . ';';
                }
                if (!empty($mL)) {
                    if (empty($tI)){
                      $marginLeft = $mL;
                    } else {
                      $marginLeft = self::sum(array($mL, $tI), 'pt', false); 
                    }
                    $this->_style[$baseLI] .= 'margin-left: ' . $marginLeft . ';';
                }
            }
            $style .= 'display: table-cell;'; 
            $this->_style[$baseLIBefore] = $style;
            if (!empty($parentStyle)) {
                $this->_parentStyle[$baseLIBefore] = $parentStyle;
            }
        }
    }
    
    /**
     * This method parses the layout style nodes
     *
     * @param DOMNode $styleNode
     * @return void
     * @access private
     */
    private function _parseODFStyleLayout($styleNode)
    {
        //this styles are associated with different page layouts
        $name= $styleNode->getAttribute('style:name');
        $childs = $styleNode->childNodes;
        foreach ($childs as $child) {
            $sType = $child->nodeName;
            switch ($sType) {
                case 'style:page-layout-properties':
                    $this->_pageStyle[$name]['page'] = 
                        $this->_parseODFProps($child);
                    //check for childs that are not just a background image 
                    //taht is already taken care by _parseODFProps
                    $columns = $child->getElementsByTagName('columns');
                    if ($columns->length > 0) {
                        $this->_pageStyle[$name]['page'] .= 
                            $this->_parseODFProps($columns->item(0));
                    }
                    $sep = $child->getElementsByTagName('column-sep');
                    if ($sep->length > 0) {
                        $rule = $this->_parseLine($sep->item(0));
                        $this->_pageStyle[$name]['page'] .= 
                            'column-rule: ' . $rule . ';';
                    }
                    break;
                case 'style:header-style':
                case 'style:footer-style':
                    $type = \substr($sType, 6, 6);
                    $properties = $child
                        ->getElementsByTagName('header-footer-properties');
                    if ($properties->length > 0) {
                        $this->_pageStyle[$name][$type] = 
                            $this->_parseODFProps($properties->item(0));
                    }
                    break;
            }
        }
    }
    
    /**
     * This method parses the master style nodes
     *
     * @param DOMNode $styleNode
     * @return void
     * @access private
     */
    private function _parseODFStyleMaster($styleNode)
    {
        
        //this styles are associated with different page Layouts
        $master= $styleNode->getAttribute('style:name');
        $layout = $styleNode->getAttribute('style:page-layout-name');
        $this->_masterLayout[$master]['layout'] = $layout;
        //we need to know which automatic styles are associated with
        //this master style
        $xpath = $this->_xpath;
        $query = '//style:style[@style:master-page-name="' . $master .'"]';
        $query .= ' | //style:style[@style:parent-style-name="' . $master .'"]';
        $sNodes = $xpath->query($query);
        foreach ($sNodes as $sNode) {
            $sName = $sNode->getAttribute('style:name');
            $this->_style2master[$sName] = $master;
        }
        //but unfortunately this is not all because we have to take into account
        //styles in the styles.xml file
        $xpath = new \DOMXPath($this->_dom['styles.xml']);
        $query = '//style:style[@style:master-page-name="' . $master .'"]';
        $sNodes = $xpath->query($query);
        foreach ($sNodes as $sNode) {
            $sName = $sNode->getAttribute('style:name');
            $this->_style2master[$sName] = $master;
        }
        //the master style may be a (paragraph) style by itself so we have
        //to add it to the list
        $this->_style2master[$master] = $master;
        //TODO: take into account the next-style-name attribute!!!
        //although the problem is that it is only used when the page is
        //filled and we can not control that :-(
        if ($this->_docType != 'spreadsheet') {
            $this->_parseHeaderFooter($styleNode, $master, 'header');
            $this->_parseHeaderFooter($styleNode, $master, 'footer');
        }
    }
    
    
    /**
     * Parses the opacity properties
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseOpacity($name, $value, $important = false)
    {   
        $val = \trim(\str_replace('%', '', $value));
        $val = $val/100;
        if ($name == 'svg:stroke-opacity') {
            $style = 'stroke-opacity: ' . $val;
        } else if ($name == 'draw:opacity') {
            $style = 'fill-opacity: ' . $val;
        }
        if ($important) {
            $style .= ' !important;';
        } else {
            $style .= ';';
        }
        return $style;
    }
    
    /**
     * position absolutely a frame depending on the props: style:run-thrugh
     * and style:flow-with-text
     *
     * @param DOMNode $node
     * @return string
     * @access private
     */
    private function _parseRunThrough($node)
    {   
        $flow = $node->getAttribute('style:horizontal-pos');
        if (empty($flow)){
            return 'position: absolute;';
        }
    }
    
    /**
     * Parses the background image path
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseSVGBackgroundImage($name, $value, $important = false)
    {   
        if (isset($this->_backImages[$value])) {
            $bg = $this->_imagePath($this->_backImages[$value], true);
            return 'background-image: url(' . $bg . ');';
        }
    }
    
    /**
     * Leaves a mark to later remove left padding for elements that are 
     * horizontally aligned in SVG
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseTAH($name, $value, $important = false)
    {   
        if ($value != 'left') {
            return 'rpl;';
        } else {
            //the following collides with horizontal positioning
            //return 'float: left !important;';
        }
    }
    
    /**
     * Generates a text-align property
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _parseTextAlign($name, $value, $important = false)
    {   
        $temp = explode(':', $name);
        $prop = \array_pop($temp);
        if ($value == 'end') {
            $value = 'right';
        } else if ($value == 'start') {
            $value = 'left';
        }

        $style = $prop . ': ' . $value;
        if ($important) {
            $style .= ' !important;';
        } else {
            $style .= ';';
        }
        return $style;
    }
    
    /**
     * Chosses the font color depending on the background
     *
     * @param DOMNode $node
     * @return string
     * @access private
     */
    private function _parseWindowFont($node)
    {   
        $value = $node->getAttribute('style:use-window-font-color');
        //We ignore this property if it is not an automatic style
        $type = $node->parentNode->parentNode->nodeName;
        //We set it to initial assuming is already set respect the
        //background
        if ($value == 'true' && $type == 'office:automatic-styles') {
            return 'color: initial !important;';
        }
    }
    
    /**
     * takes care of wrapping in frames
     *
     * @param DOMNode $node
     * @return string
     * @access private
     */
    private function _parseWrap($node)
    {
        $wrap = $node->getAttribute('style:wrap');
        
        if ($wrap == 'none') {
            return 'float: none;';
        } else if (!empty($wrap)) {
            $style = 'float: left;';
            $side = $node->getAttribute('style:horizontal-pos');
            if ($side == 'right') {
                $style = 'float: right;';
            } else if ($side == 'center') {
                $style = 'float: none; display: block;';
                $style .= 'margin-left: auto; margin-right: auto;';
            }
            return $style;
        } 
    }
    
    /**
     * takes care of writting mode (RTL)
     *
     * @param DOMNode $node
     * @return string
     * @access private
     */
    private function _parseWritingMode($node)
    {
        $wmode = $node->getAttribute('style:writing-mode');
        $style = 'writing-mode: ' . $wmode . ';';
        $direction = explode('-', $wmode);
        if ($direction[0] == 'rl' || $direction[0] == 'rl' || $wmode == 'rl') {
           $style .= 'direction: rtl;';
           //we need also to check if there is a text-align with value "start"
           //or end that is not recognised by IE
           $ta = $node->getAttribute('fo:text-align');
           if ($ta == 'start' || empty($ta)) {
               $style .= 'text-align: right !important;';
           } else if ($ta == 'end') {
               $style .= 'text-align: left !important;';
           }
        }
        return $style;
    }
    
    /**
     * Parses the document col styles
     *
     * @return void
     * @access private
     */
    private function _preParseColCellStyles ()
    {
        //get all the col cell default styles
        $query = '//table:table';
        $query .= '[//table:table-column[@table:default-cell-style-name]]';
        $tables = $this->_xpath->query($query);
        foreach ($tables as $table) {
            $name = $table->getAttribute('table:style-name');
            $cols = $table->getElementsByTagName('table-column');
            $counter = 1;
            if (!empty($name) && $cols->length > 0) {
                foreach ($cols as $col) {
                    $key = $col->getAttribute('table:default-cell-style-name');
                    $val = $col->getAttribute('table:style-name');
                    self::$colCellStyles[$key][$name][$counter] = $val;
                    $counter++;
                }
            }
        }
    }
    
    /**
     * Parses for special services like youtube video and/or google forms
     *
     * @return void
     * @access private
     */
    private function _preParse4Services ()
    {
        //first get all links
        $query = '//text:a';
        $links = $this->_xpath->query($query);
        foreach ($links as $link){
            $str = $link->getAttribute('xlink:href');
            foreach ($this->_services as $key => $value){
                $initial = stripos($str, $key);
                if ($initial !== false){
                    $this->_preParseLinkParagraph($link);
                    break;
                }
            }
        }
    }
    
    /**
     * Removes additional content in paragraphs with links to services
     *
     * @param DOMNode $link
     * @return void
     * @access private
     */
    private function _preParseLinkParagraph ($link)
    {
        //get the URL
        $url = $link->getAttribute('xlink:href');
        //get the span style
        $spans = $link->getElementsByTagName('span');
        if ($spans->length > 0){
            $style = $spans->item(0)->getAttribute('text:style-name');
        } else {
            $style = '';
        }
        //get the parent paragraph if any
        $query = 'ancestor::text:p';
        $ps = $this->_xpath->query($query, $link);
        if ($ps->length > 0){
            $p = $ps->item(0);
            //we should get all bookmark nodes
            $query = './/text:bookmark';
            $bmNodes = $this->_xpath->query($query, $p);
            $query = './/text:bookmark-start';
            $startNodes = $this->_xpath->query($query, $p);
            $query = './/text:bookmark-end';
            $endNodes = $this->_xpath->query($query, $p);
            //empty the node
            while ($p->hasChildNodes()) {
                $p->removeChild($p->firstChild);
            }
            //create a span node
            $url = str_replace('&', '&amp;', $url);
            $url = str_replace('%25', '%', $url);
            $sp = $link->ownerDocument->createElement('text:span', $url);
            $sp->setAttribute('text:style-name', $style);
            //create a text:a node
            $a = $link->ownerDocument->createElement('text:a');
            $a->setAttribute('xlink:href', $url);
            $a->appendChild($sp);
            //append it to the $p node but taking care of possible bookmarks
            foreach($startNodes as $node){
                $bm = $link->ownerDocument->createElement('text:bookmark-start');
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $attr = $node->getAttributeNS($ns, 'name');
                $bm->setAttributeNS($ns, 'text:name', $attr);
                $p->appendChild($bm);
            }
            foreach($bmNodes as $node){
                $bm = $link->ownerDocument->createElement('text:bookmark');
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $attr = $node->getAttributeNS($ns, 'name');
                $bm->setAttributeNS($ns, 'text:name', $attr);
                $p->appendChild($bm);
            }
            //insert the links
            $p->appendChild($a);
            //pending bookmarks
            foreach($endNodes as $node){
                $bm = $link->ownerDocument->createElement('text:bookmark-end');
                $ns = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
                $attr = $node->getAttributeNS($ns, 'name');
                $bm->setAttributeNS($ns, 'text:name', $attr);
                $p->appendChild($bm);
            }
        }
    }
    
    /**
     * Parses the document lists to allow for continuing numbering
     *
     * @return void
     * @access private
     */
    private function _preParseLists ()
    {
        $spath = new \DOMXPath($this->_dom['styles.xml']);
        //get all the lists included in the document
        $query = '//text:list[@text:style-name]';
        $lists = $this->_xpath->query($query);
        $found = array();
        foreach ($lists as $list) {
            $listName = $list->getAttribute('text:style-name');
            $continue = $list->getAttribute('text:continue-numbering');
            $continue2 = $list->getAttribute('text:continue-list');
            //define a level array
            $listOffset = array(0,0,0,0,0,0,0,0,0,0);
            //look for offsets in the list definition
            $qOffset = '//text:list-style[@style:name="'. $listName . '"]';
            $qOffset .= '/text:list-level-style-number';
            $nStyles = $this->_xpath->query($qOffset);
            $soff = 0;
            $listDepth = 0;
            if (!isset($found[$listName])){
                //this is the first appearance of this list and we have to check
                //that there is no jump in level that scramble the numbering
                $found[$listName] = true;
                $listDepth = $this->_computeListDepth($list);
                if ($listDepth > 0){
                    $soff = 1;
                } 
            }
            if ($nStyles->length == 0){
                //the style definition is in the styles.xml file
                $nStyles = $spath->query($qOffset);
            }
            foreach ($nStyles as $nStyle) {
                $tlevel = $nStyle->getAttribute('text:level');
                $tstart = $nStyle->getAttribute('text:start-value');
                if (!empty($tstart) && !empty($tlevel) && $tlevel <= $listDepth) {   
                    $listOffset[$tlevel - 1] = $tstart -1 + $soff;
                } else if (!empty($tstart) && !empty($tlevel)) {
                    $listOffset[$tlevel - 1] = $tstart -1;
                } else if (empty($tstart) && !empty($tlevel) && $tlevel <= $listDepth){
                    $listOffset[$tlevel - 1] = $soff;
                }
            }

            if ($continue == 'true' || !empty($continue2)) {
                if (!isset(self::$listLevel[$listName])) {
                    self::$listLevel[$listName] = $listOffset;
                } else {
                    self::$listLevel[$listName][0];
                }
                $list->setAttribute('text:parent-list', $listName);
                $list->setAttribute('text:list-depth', 0);
                $list->setAttribute('text:list-counter', 
                    self::$listLevel[$listName][0]);
                $this->_tagList($listName, $list, 0);
            } else {
                self::$listLevel[$listName] = $listOffset;
                
                $this->_tagList($listName, $list, 0);
            }
        }
    }
    
    /**
     * Insert additional attributes
     *
     * @param string $name
     * @param DOMNode $list
     * @param integer $level
     * @return void
     * @access private
     */
    private function _tagList ($name, $list, $level=0, $counter=0)
    {
        $items = $list->childNodes;
        foreach ($items as $item) {
            $nodeName = $item->nodeName;
            if ($nodeName == 'text:list-item') {
                $subItems = $item->childNodes;
                $subcounter = 0;
                $offset = (int) $item->getAttribute('text:start-value');
                foreach ($subItems as $subItem) {
                    if ($subItem->nodeName == 'text:list') {
                        $this->_tagList($name, $subItem, $level + 1);
                    } else {
                        if (!empty($offset)) {
                            $subcounter = $offset;
                        } else {
                            $subcounter = 1;
                        }
                        if ($subcounter > 0) {
                        //all lower levels have to be reset to zero
                        for ($j = $level + 1; $j < 11; $j++ ) {
                                self::$listLevel[$name][$j] = 0;
                            }
                        }
                    }  
                }
                $counter += $subcounter;
            }
        }
        $list->setAttribute('text:parent-list', $name);
        $list->setAttribute('text:list-depth', $level);
        $list->setAttribute('text:list-counter', 
                            self::$listLevel[$name][$level]);
        
        self::$listLevel[$name][$level] += $counter;
        
        
    }
    
    /**
     * This method renders the HTML
     *
     * @param array $options
     * @return void
     * @access public
     */
    public function renderHTML($options = array())
    {     
        // CSS   
        //Take into account parent styles inheritance that was not taken into
        //account after the first parent style merging
        $this->_mergeParentStyles();    
        //include default page styles;
        $this->_CSS .= $this->_externalCSS .PHP_EOL;
        if ($this->_parseLayout) {
            foreach ($this->_pageStyle as $key => $value) {
                //we should replace the margins by paddings for proper browser
                //rendering
                $css = $value['page'];
                $this->_CSS .= 'div#' . $this->_prefix . 'div.' . $key . ' {' . $css . '}' . PHP_EOL; 
				$width = self::extractSingleProperty('data-width', $css);
                if (!empty($width)) {
                    $w = 'width: ' . $width .';';
                    $this->_CSS .= '.h5p_page_'. $key .' {' . $w . '}' .PHP_EOL;
                } 
            }
        }
        foreach ($this->_style as $key => $value) {
            $posFontSize = \strpos($value, 'font-size:');
            $before = \strpos($key, ':before');
            if ($posFontSize === false 
                && $before == false
                && isset($this->_defaultFontSize)) {
                $value .= 'font-size: ' . $this->_defaultFontSize . ';';
            }
            $this->_CSS .= ' div#' . $this->_prefix . $key . ' {' . $value . '}' . PHP_EOL; 
        }
        foreach ($this->_defaultStyle as $key => $value) {
            $posFontSize = \strpos($value, 'font-size:');
            $before = \strpos($key, ':before');
            if ($posFontSize === false 
                && $before == false
                && isset($this->_defaultFontSize)) {
                $value .= 'font-size: ' . $this->_defaultFontSize . ';';
            }
            $this->_CSS .= ' div#' . $this->_prefix  . $key . ' {' . $value . '}' . PHP_EOL; 
        }
        if ($this->_math) {
            $html = '<html xmlns:mml="http://www.w3.org/1998/Math/MathML" xmlns:default="http://www.w3.org/1998/Math/MathML">' . PHP_EOL;
        } else {
            $html = '';
        }
		

        if ($this->_singleFile) {
            $html .= '<style>' . PHP_EOL . $this->_CSS . PHP_EOL . '</style>' . PHP_EOL;
        } else {
            if (\file_exists($this->_path['filename'] . '-styles') === false) {
                //create the folder
                \mkdir($this->_path['filename'] . '-styles');
            }
        }  
        if ($this->_math) {
			$mathCSS = \file_get_contents(dirname(__FILE__) . '/MathJax.css');
			$mathCSS = str_replace ('.h5p_page', ' div#' . $this->_prefix . ' section.h5p_page', $mathCSS);
            $html .= $mathCSS;
        }
        
        //HTML
        
        //start to generate the HTML5 body element
        $k = 0;
        $currentMaster = NULL;
        $layout =& $this->_masterLayout;
        foreach ($this->_HTML as $key => $value) {
            //get the master style
            //we have to hack the fact that whenever $k > 0 the standard page 
            //layout because when one changes from Word to Libre Office or 
            //Open Office spurious sections may pop out: unfortunately this may 
            //be implementation dependedant
            if (isset($value['master']) && ($value['master'] != 'Standard' || $k == 0)) {
                $master = $value['master'];    
            } else {
                $master = $currentMaster;
            }
            if ($k == 0 && empty($master) && empty($currentMaster)){
                $l = count($this->_HTML);
                for ($p = 1; $p < $l; $p++) {
                    if (!empty($this->_HTML[$p]['master'])) {
                        $master = $this->_HTML[$p]['master'];
                        break;
                    }
                }
            }
            if ($master !== $currentMaster) {
                if ($k > 0) {
                    if (!empty($layout[$currentMaster]['footer'])) {
                        $div->appendChild($layout[$currentMaster]['footer']);
                    }
                    $this->_body->documentElement->appendChild($section);
                }
                $section = $this->_body->createElement('section');
                $section->setAttribute('id', 'section_' . $k);
                if (isset($layout[$master]['layout'])) {
                    $class = 'h5p_layout h5p_page h5p_page_' . $layout[$master]['layout'];
                } else if (isset($layout['default'])) {
                    $class = 'h5p_layout h5p_page h5p_page_' . $layout['default'];
                } else {
                    $class = 'h5p_layout h5p_page';
                }
                $section->setAttribute('class', $class);
                $div = $this->_body->createElement('div');
                if (isset($layout[$master]['layout'])) {
                    $class = $layout[$master]['layout'];
                    $div->setAttribute('class', $class);
                } else {
                    //we use some default styles
                    $defStyle = 'border: none; padding: 2cm';
                    $div->setAttribute('style', $defStyle);
                }
                
                $section->appendChild($div);
                if (!empty($layout[$master]['header'])) {
                    $div->appendChild($layout[$master]['header']);
                }
                $currentMaster = $master;
            } 
            if (empty($div)) {
                $section = $this->_body->createElement('section');
                $section->setAttribute('id', 'section_' . $k);
                $class = 'h5p_layout h5p_page';
                $section->setAttribute('class', $class);
                $div = $this->_body->createElement('div');
                $div->setAttribute('class', 'h5p_default_layout');
                $div->appendChild($value['xml']);
                $section->appendChild($div);
                $this->_body->documentElement->appendChild($section);
            } else {
                $div->appendChild($value['xml']);
            }
            $k++;
        }
        //take into account the last section that is not properly handle in the
        //loop
        if (\count($this->_footnotes)  > 0 
            || \count($this->_endnotes) > 0
            || \count($this->_comments) > 0) {
                $this->_appendNotes($div);
            }
        if (!empty($layout[$master]['footer'])) {
            $div->appendChild($layout[$master]['footer']);
        }
        if (!empty($section)) {
            $this->_body->documentElement->appendChild($section);
        }
        //take now into account the scripts
        $addScripts = '';
        if (\count($this->_charts)){
            //TODO: load the javascript and CSS in a proper way :-)
            $addScripts .= '<style>';
			$c3CSS = \file_get_contents(dirname(__FILE__) . '/../../lib/vendor/js/c3.css');
			$c3CSS = \str_replace('.h5p_page', 'div#' . $this->_prefix . ' .h5p_page', $c3CSS);
            $addScripts .= $c3CSS;
            $addScripts .= '</style>';
            $addScripts .= '<script>';
            $addScripts .= \file_get_contents(dirname(__FILE__) . '/../../lib/vendor/js/c3.js');
            $addScripts .= '</script>';
            $addScripts .= '<script>';
            $addScripts .= \file_get_contents(dirname(__FILE__) . '/../../lib/vendor/js/d3.js');
            $addScripts .= '</script>';
            foreach ($this->_charts as $chart) {
                $addScripts .= $chart;
            }
        }
        if (\count($this->_comments)){
            $addScripts .= '<style>';
            $addScripts .= \file_get_contents(dirname(__FILE__) . '/../../lib/vendor/js/jquery.webui-popover.css');
            $addScripts .= '</style>';
            $addScripts .= '<script>' . PHP_EOL;
            $addScripts .= '(function( $ ) {' . PHP_EOL;
            $addScripts .= \file_get_contents(dirname(__FILE__) . '/../../lib/vendor/js/jquery.webui-popover.min.js');
            foreach ($this->_comments as $comment) {
                $addScripts .= $comment['script'];
            }
            $addScripts .= '})(jQuery);';
            $addScripts .= '</script>';
        }
        if ($this->_tabs) {
            $addScripts .= '<script>';
            $addScripts .= \file_get_contents(dirname(__FILE__) . '/../../lib/vendor/js/tabs.js');
            $addScripts .= '</script>';    
        }
        if (\count($this->_dropcaps) > 0) {
            $addScripts .= '<script>';
            $addScripts .= \file_get_contents(dirname(__FILE__) . '/../../lib/vendor/js/dropcaps.js');
            foreach($this->_dropcaps as $index => $value){
                $addScripts .= 'var ' . $index . '_dropcaps = document.querySelectorAll(".' . $index . '_dropcaps");' . PHP_EOL;
                $addScripts .= 'window.Dropcap.layout(' . $index . '_dropcaps, ' . $value . ');' . PHP_EOL;
            }
            $addScripts .= '</script>';
        }
        if ($this->_math) {
            $mathjax = Docxpresso\CreateDocument::$config['js']['MathJax'];
            $html .= '<script src="' . $mathjax . '" type="text/javascript"> </script>' . PHP_EOL; 
        }
        if ($this->_sortTables) {
            $addScripts .= '<script>' . PHP_EOL;;
            $addScripts .= 'var numFormat = "';
            $addScripts .= $this->_sortNumberFormat;
            $addScripts .= '";' . PHP_EOL;
            $addScripts .= \file_get_contents(dirname(__FILE__) . '/../../lib/vendor/js/tableSorter.js');
            $addScripts .= '</script>';    
        }
        
        //we should now repair the DOM for div's inside p's that are
        //generated for charts and images. We also take care of drop caps if any
        $this->_repairDOM($this->_body);
        
        $this->_body->formatOutput = false;//to avoid unwanted whitespaces
        $htmlBody = $this->_body->saveXML($this->_body->documentElement);

        if (\count($this->_charts) 
            || \count($this->_comments)
            || $this->_sortTables
            || $this->_tabs
            || \count($this->_dropcaps)){
            $htmlBody = \str_replace('</body>', 
                                     $addScripts . '</body>', 
                                     $htmlBody);
        }
        $html .= $htmlBody;
		if ($this->_math) {
			$html .= '</html>';
		}
                
        //this is a hack for <wbr> in IE
        $html = \str_replace('<wbr/>', '&#x200b;&#x200b;', $html);

        $html = \str_replace('<body>', 
                             '<div id="' . \trim($this->_prefix) . '">',
                             $html);
        $html = \str_replace('</body>', 
                             '</div>',
                             $html);
        
        if ($this->_download) {
            return $html;
        } 
  
    }
    
    /**
     * Removes padding from borderless paragraphs
     *
     * @param string $style
     * $param array $paddings
     * @return string
     * @access private
     */
    private function _removePadding($style, $paddings)
    {
        foreach ($paddings as $pad) {
            $regex = '/padding-' . $pad . '[^;]+;?/i';
            $style = \preg_replace($regex, '', $style);
        }
        return \trim($style);
    }
    
    /**
     * This method regroups similar bordered paragraphs
     *
     * @param array $pGroup
     * @access private
     */
    private function _reorderBorderedPs($pGroup)
    {
        //create a container div just before the first paragraph: the last 
        //one in the array
        $l = \count($pGroup);
        $last = $pGroup[$l -1];
        $class = $last->getAttribute('class');
        $div = $last->ownerDocument->createElement('div');
        $nSt = '';
        if (isset($this->_pBorders[$class])) {
            $bStyles = \implode(';', $this->_pBorders[$class]);
            $nSt .= $bStyles . ';margin-bottom:0;';
        }
        if (isset($this->_pMargins[$class]) 
            && !empty($this->_pMargins[$class]['margin-left'])) {
            $nSt .= 'margin-left: ' . $this->_pMargins[$class]['margin-left'];
            $nSt .= ';';
        }
        if (isset($this->_pMargins[$class]) 
            && !empty($this->_pMargins[$class]['margin-right'])) {
            $nSt .= 'margin-right: ' . $this->_pMargins[$class]['margin-right'];
            $nSt .= ';';
        }
        $div->setAttribute('style', $nSt);
        //clone, append
        for ($k = $l; $k > 0; $k--) {
           $p = $pGroup[$k - 1];
           $newP = $p->cloneNode(true);
           //remove the border and margins left and right from the paragraph
           $pSt = 'border: none !important;';
           $pSt .= 'margin-left: 0 !important;';
           $pSt .= 'margin-right: 0 !important;';
           $newP->setAttribute('style', $pSt);
           $div->appendChild($newP);
        }
        $last->parentNode->insertBefore($div, $last);
        //remove
        for ($k = $l; $k > 0; $k--) {
           $p->parentNode->removeChild($pGroup[$k - 1]);
        }
    }
    
    /**
     * This method removes dots from style names
     *
     * @param string $name
     * @return string
     * @access private
     */
    private function _rep($name)
    {
        $name = \str_replace('.', '_', $name);
        return $name;
    }
    
    /**
     * This method repairs the DOM
     *
     * @param DOMDocument $name
     * @access private
     */
    private function _repairDOM($dom)
    {
        //remove directly divs that are childs of a p node
        $xpath = new \DOMXPath($dom);
        $query = '//p[./div]';
        $ps = $xpath->query($query);
        foreach ($ps as $p) {
            $openP = false;
            $childs = $p->childNodes;
            foreach ($childs as $child) {
                if ($child->nodeName == 'div') {
                    $openP = false;
                    $newDiv = $child->cloneNode(true);
                    $p->parentNode->insertBefore($newDiv, $p);
                } else {
                    if ($openP === false){
                        $newP = $dom->createElement('p');
                        $class = $p->getAttribute('class');
                        $newP->setAttribute('class', $class);
                        $p->parentNode->insertBefore($newP, $p);
                        $openP = true;
                    }
                    $element = $child->cloneNode(true);
                    $newP->appendChild($element);
                }
            }
            $p->parentNode->removeChild($p);
        }
        //remove divs that are childs of a p node and are wrapped in a span
        $xpath = new \DOMXPath($dom);
        $query = '//p/span[./div]';
        $sps = $xpath->query($query);
        foreach ($sps as $sp) {
            $openP = false;
            $childs = $sp->childNodes;
            foreach ($childs as $child) {
                if ($child->nodeName == 'div') {
                    $openP = false;
                    $newDiv = $child->cloneNode(true);
                    $sp->parentNode
                       ->parentNode->insertBefore($newDiv, $sp->parentNode);
                } else {
                    if ($openP === false){
                        $newP = $dom->createElement('p');
                        $class = $sp->parentNode->getAttribute('class');
                        $newP->setAttribute('class', $class);
                        $sp->parentNode
                           ->parentNode->insertBefore($newP, $sp->parentNode);
                        $openP = true;
                    }
                    $element = $child->cloneNode(true);
                    $newP->appendChild($element);
                }
            }
            $sp->parentNode->removeChild($sp);
        }
        //remove divs that are childs of a p node and are wrapped in a span
        $xpath = new \DOMXPath($dom);
        $query = '//p/span[./div]';
        $sps = $xpath->query($query);
        foreach ($sps as $sp) {
            $openP = false;
            $childs = $sp->childNodes;
            foreach ($childs as $child) {
                if ($child->nodeName == 'div') {
                    $openP = false;
                    $newDiv = $child->cloneNode(true);
                    $sp->parentNode
                       ->parentNode->insertBefore($newDiv, $sp->parentNode);
                } else {
                    if ($openP === false){
                        $newP = $dom->createElement('p');
                        $class = $sp->parentNode->getAttribute('class');
                        $newP->setAttribute('class', $class);
                        $sp->parentNode
                           ->parentNode->insertBefore($newP, $sp->parentNode);
                        $openP = true;
                    }
                    $element = $child->cloneNode(true);
                    $newP->appendChild($element);
                }
            }
            $sp->parentNode->removeChild($sp);
        }
        //we should run now over contiguous paragraphs that may have
        //common borders
        $query2 = '//p';
        $ps2 = $xpath->query($query2);
        $l = $ps2->length;
        $pGroup = array();
        //run through all <p>s backwards
        for ($k = $l; $k > 0; $k--) {
            $paragraph = $ps2->item($k - 1);
            $class = $paragraph->getAttribute('class');
            if (isset($this->_pBorders[$class])) {
                $border = true;
            } else {
                $border = false;
            }
            if ($border) {
                //this p has a border
                if (\count($pGroup) == 0) {
                    $pGroup[] = $paragraph;
                } else {
                    $ll = \count($pGroup);
                    $lastP = $pGroup[$ll - 1];
                    //check if they share the same border styles
                    $lastC = $lastP->getAttribute('class');
                    $diff = \array_diff($this->_pBorders[$class],
                                        $this->_pBorders[$lastC]);
                    //check also that they are contiguous
                    $nextNode = $paragraph->nextSibling;
                    if (!empty($nextNode)){
                        $sameNode = $nextNode->isSameNode($lastP);
                    } else {
                        $sameNode = false;
                    }
                    if (\count($diff) == 0 && $sameNode) {
                        $pGroup[] = $paragraph;
                    } else {
                        //the border style has changed
                        if (\count($pGroup) > 1) {
                           $this->_reorderBorderedPs($pGroup); 
                        }
                        //restart pGroup;
                        $pGroup = array();
                    }
                }
            } else {
                //check if there are paragraph groups left
                if (\count($pGroup) > 1) {
                    $this->_reorderBorderedPs($pGroup); 
                }
                //restart pGroup;
                $pGroup = array();
            }
        }
        //check if there are paragraph groups left
        if (\count($pGroup) > 1) {
            $this->_reorderBorderedPs($pGroup); 
        }
        //now take care of drop caps if there are any
        foreach($this->_dropcaps as $key => $value){
            $query = '//p[contains(concat(" ", @class, " "), " '. $key . ' ")]'
                    . '/descendant::text()[1]';
            $nodes = $xpath->query($query);

            foreach ($nodes as $node) {
                    $val = $node->nodeValue;
                    $firstChar = substr($val, 0, 1);
                    $remain = substr($val, 1);
                    $node->nodeValue = $remain;
                    $new = $node->ownerDocument->createElement('span', $firstChar);
                    $new->setAttribute('class', $key . '_dropcaps dropcap');
                    if (!empty($node)) {
                        $node->parentNode->insertBefore($new, $node);
                    } else {
                        $node->appendChild($new);
                    }
            }
        }
        //we also have to remove the last empty paragraphs at the end of a table
        //cell that is preceded by a table element
        $tds = $dom->getElementsByTagName('td');
        foreach($tds as $td){
            $last = $td->lastChild;
            if (!empty($last) &&
                $last->nodeName == 'p' &&
                $last->nodeValue == '' &&    
                !empty($last->previousSibling) &&
                $last->previousSibling->nodeName == 'table') {
                $last->parentNode->removeChild($last);
            }
        }
        
        //in case we are instructed to open external links in a new window we
        //need some extra parsing
        if ($this->_linkTargetToBlank) {
            $query = '//a[contains(@href, "http://") or contains(@href, "https://")]';
            $nodes = $xpath->query($query);
            foreach ($nodes as $node) {
                $node->setAttribute('target', '_blank');
            }
        }
        //repair headers so they are always "clear" of body content
        $headers = $dom->getElementsByTagName('header');
        foreach($headers as $header){
            $cleaner = $dom->createElement('p', ' ');
            $pstyle = 'font-size: 1px; clear:both; margin: 0; padding:0';
            $cleaner->setAttribute('style', $pstyle);
            $header->appendChild($cleaner);
        }
        
        //SPREADSHEETS
        if($this->_docType == 'spreadsheet'){
            //tables are included withih a cell and that breaks table rendering
            //detect tables with charts
            $query = '//table[.//div[contains(@id, "chart-")]]';
            $nodes = $xpath->query($query);
            foreach ($nodes as $node) {
                $name = $node->getAttribute('data-table-name');
                $query2 = './/div[contains(@id, "chart-")]';
                $charts = $xpath->query($query2, $node);
                foreach ($charts as $chart){
                    $pchart = $chart->parentNode;
                    $newChart = $pchart->cloneNode(true);
                    $node->parentNode->insertBefore($newChart, $node);
                    $pchart->parentNode->removeChild($pchart);
                }
            }
            //remove tds with huge colspans that are generated by Excel
            $query = '//tr/td[position() = last() and @colspan]';
            $nodes = $xpath->query($query);
            foreach ($nodes as $node) {
                $colspan = $node->getAttribute('colspan');
                if ($colspan > 100) {
                    $node->parentNode->removeChild($node);
                }
            }
        }
        
        //check if there are clipped images
        //this is temporarily disabled
        return;
        foreach ($this->_clippedImages as $key => $value){
            //query for divs with that style
            $query = '//div[@class="' . $key . '"]';
            $divs = $xpath->query($query);
            foreach ($divs as $div) {
                $anchor = $div->getAttribute('data-anchor-type');
                $avoidAnchors = array('char' => true, 'as-char' => true);
                if (!isset($avoidAnchors[$anchor])) {
                    //get the img nodes
                    $imgs = $div->getElementsByTagName('img');
                    foreach ($imgs as $img) {
                        $clip = $this->_clippedImages[$key];
                        $style = $img->getAttribute('style');
                        $w = parser\CSSPropsLexer::extractSingleProperty('width', 
                                        $style);
                        $h = parser\CSSPropsLexer::extractSingleProperty('height', 
                                        $style);
                        $regex = '/([0-9\.\-]+)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)*/i';
                        \preg_match_all($regex, $clip, $matches);
                        if (count($matches[0] == 4)) {
                            $t = commons\Utilities::convert2rems($matches[0][0]);
                            $r = commons\Utilities::convert2rems($matches[0][1]);
                            $b = commons\Utilities::convert2rems($matches[0][2]);
                            $l = commons\Utilities::convert2rems($matches[0][3]);
                            //let us compute the clipping coordinates
                            $nt = $t;
                            $nr = floatval($w) - floatval($r);
                            $nr .= 'rem';
                            $nb = floatval($h) - floatval($b);
                            $nb .= 'rem';
                            $nl = $l;
                            $cl = 'clip: rect(' . $nt . ', ';
                            $cl .= $nr . ', ';
                            $cl .= $nb . ', ';
                            $cl .= $nl . ')';
                            $style .=  $cl;
                            $style .= '; position: absolute;';
                            $img->setAttribute('style', $style);
                        }
                    }
                }
            }
        }
        
    }
    
    /**
     * Gets the text-decoration attribute of the deepest span node
     *
     * @param DOMNode $node
     * @return string
     * @access private
     */
    private function _spanDecoration($node)
    {
        $decor = '';
        if ($node->hasChildNodes() 
            && $node->firstChild->nodeName == 'text:span') {
            if ($node->firstChild->hasChildNodes()
                && $node->firstChild->firstChild->nodeName == 'text:span') {
                $decor = $this->_spanDecoration($node->firstChild);
                return $decor;
            } else {
                $spanStyle = $node->firstChild
                            ->getAttribute('text:style-name');
                if (isset($this->_style['span.' . $spanStyle])) {
                    $decor = 
                    self::extractSingleProperty('text-decoration', 
                                        $this->_style['span.' . $spanStyle]);

                }
            }
        }
        return $decor;
    }
    
    /**
     * Parses the table:align property
     *
     * @param string $name
     * @param string $value
     * @param bool $important
     * @return string
     * @access private
     */
    private function _tableAlign($name, $value, $important = false)
    {
        /*center: table aligns to the center between left and right margins. 
        left: table aligns to the left margin. 
        margins: table fills all the space between the left and right margins. 
        right: table aligns to the right margin.*/
        $style = '';
        if ($value == 'center'){
            $style = 'margin-left: auto';
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
            $style .= 'margin-right: auto';
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        } else if ($value == 'right'){
            $style = 'margin-left: auto';
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        } else if ($value == 'margins'){
            $style = 'width: 100%';
            if ($important) {
                $style .= ' !important;';
            } else {
                $style .= ';';
            }
        }
        
        return $style;
    }
    
    /**
     * Tags HTML and ODF nodes for later correspondence
     *
     * @param DOMNode $nodeHTML
     * @param DOMNode $nodeODF
     * @return string
     * @access private
     */
    private function _tagElement($nodeHTML, $nodeODF)
    {
        $excluded = array( 'text:tab' => true,
                           'text:s' => true,
                           '#text' => true,                   
                          );
        $tag = $nodeODF->nodeName;
        if ($nodeODF->nodeType == 1 && !isset($excluded[$tag])) {
            if (isset($this->_globalCounter[$tag])) {
                $this->_globalCounter[$tag]++;   
            } else {
                $this->_globalCounter[$tag] = 1;
            }
            if (!empty($nodeHTML)) {
                $nodeHTML->setAttribute('data-h5p', 
                                        $tag . '=' . $this->_globalCounter[$tag]);
            }
            $nodeODF->setAttribute('data-h5p', 
                                   $tag . '=' . $this->_globalCounter[$tag]);
        }
    }
    
    /**
     * gets the first block type ancestor that accepts draw:frame
     * 
     * @param DOMNode $odfNode
     * @return DOMNode
     * @access private
     */
    private function _getFrameBlockAncestor($odfNode) 
    {
        $parents = array('text:p' => true, 
                         'table:table-cell' => true);
        $ancestor = $odfNode->parentNode;
        if ($ancestor != NULL) {
            if (isset($parents[$ancestor->nodeName])) {
                return $ancestor;
            } else {
                $ancestor = $this->_getFrameBlockAncestor($ancestor);
            }
        } 
        return $ancestor;
    }
	
    /**
     * cheks if a math formula should be of the block type
     * 
     * @param DOMNode $odfNode
     * @return boolean
     * @access private
     */
    private function _check4BlockMath($odfNode) 
    {
        $ancestor = $this->_getFrameBlockAncestor($odfNode);
        if (!empty($ancestor)){
            $childs = $ancestor->childNodes;
            if ($childs->length == 1) {
                return true;
            } else {
                $text = $odfNode->nodeValue;
                $ancestorText = $ancestor->nodeValue;
                if($text == $ancestorText){
                        return true;
                } else {
                        return false;
                }
            }
        } else {
            return false;
        }
    }
	
   /**
     * gets the fo:text-align property of a run of text
     * 
     * @param srting $mstyle
     * @return string
     * @access private
     */
    private function _getMathTextAlign($mstyle)
    {
        $ta = array (
                'start' => 'left',
                'center' => 'center',
                'end' => 'right',
                'justify' => 'center',
                'inside' => 'left',
                'outside' => 'right',
                'left' => 'left',
                'right' => 'right',
        );
        $textAlign = 'left';
        $nodes = $this->_xpath->query('//style:style[@style:name="' . $mstyle . '"]/style:paragraph-properties');
        foreach ($nodes as $node) {
            $prop = $node->getAttribute('fo:text-align');
        }
        if (!empty($prop) && isset($ta[$prop])) {
            $textAlign = $ta[$prop];
        }
        return $textAlign;
    }
    
    /**
     * This method udates the style2master array taking into acount inheritance
     *
     * @param array $style2style
     * @return string
     * @access private
     */
    private function _updateStyleMasterRelationships($style2style)
    {
        //COMMENT: it seems that Libre Office and open Office do not take into
        //account the inheritance for the master page layout
        
        /*foreach ($style2style as $key => $value ) {
            //run over the entries that have no parent themselves
            if (!isset($style2style[$value])) {
                if (isset($this->_style2master[$value])) {
                    $this->_style2master[$key] = $this->_style2master[$value];
                }
                unset($style2style[$key]);
            }
        }
        if (\count($style2style) > 0) {
            $this->_updateStyleMasterRelationships($style2style);
        }*/
    }
    
    /*Additional plugin utilities*/
    
    /**
     * returns the letter corresponding to a table row (a-z aa-az ba-bz ...)
     *
     * @param int $j
     * @return	string
     * @access	public
     * @static
     */

    public static function rowLetter($j)
    {
        $letter = '';
        $number = $j;
        while ($number > 0)
        {
            $currentLetterNumber = ($number - 1) % 26;
            $currentLetter = \chr($currentLetterNumber + 65);
            $letter = $currentLetter . $letter;
            $number = ($number - ($currentLetterNumber + 1)) / 26;
        }
        return $letter;
    }
    
    /**
     * Extracts the value of the last ocurrence of a CSS property or the one
     * that is tagged as !important if the property is given as a string,
     * otherwise the property is read from the corresponding array entry.
     *
     * @param string $prop the property we wish to extract
     * @param string $CSS the chain of CSS properties
     * @return string
     * @access public
     * @static
     */
    public static function extractSingleProperty($prop, $CSS)
    {
        $value = '';
        if (\is_string($CSS)) {
            //first check if there is an important property
            $regex = '/([^-]|^)' . $prop . '\s*:\s*([^;]*?)\s*!important/i';
            \preg_match_all($regex, $CSS, $matches);
            $results = count($matches);
            $value = \array_pop($matches[2]);
            $value = \trim($value);
            if (empty($value)) {
                //there is no !important value
                $regex = '/([^-]|^)' . $prop . '\s*:\s*([^;]*?)\s*(;|$)/i';
                \preg_match_all($regex, $CSS, $matches);
                $value = \array_pop($matches[2]);
                $value = \trim($value);
            }
        } else if (\is_array($CSS)) {
            if (isset($CSS[$prop]) && \is_string($CSS[$prop])) {
                $value = $CSS[$prop];
            } else if (isset($CSS[$prop]) && \is_array($CSS[$prop])) {
                $value = \implode('', $CSS[$prop]);
            }
        }
        
        return $value;
    }
    
    /**
     * gets a CSS property in arbitrary units and return a numeric value
     * in the requested units
     *
     * @param string $unit
     * @param string $prop
     * @return float
     * @access public
     * @static
     */
    public static function convertUnits($unit, $prop)
    {   
        if (empty($prop)) {
            return 0;
        }
        //This method does not really support relative units:
        // 1. percentages are ignored
        // 2. ems and rems are assumed to be 10pt (a reasonable estimate)
        $matches = array();
        $regex = '/([0-9]+\.?[0-9]*)\s*(px|em|rem|ex|in|cm|mm|pt|pc)?/i';
        \preg_match($regex, $prop, $matches);
        $rawVal = $matches[1];
        if (isset($matches[2])) {
            $rawUnits = $matches[2];
        } else {
            $rawUnits = 'pt';
        }
        if (!isset($rawVal)) {
            //regex failed
            return 0;
        }
        //normalize to points
        if ($rawUnits == 'pt') {
            $val = $rawVal;
        } else if ($rawUnits == 'px') {
            //px is the acronym for 'pixel' that corresponds to 0.75 points
            $val = $rawVal * 0.75;
        } else if ($rawUnits == 'cm') {
            //cm is the acronym for 'centimeter' that corresponds to 28.3465 pt
            $val = $rawVal * 28.3465;
        } else if ($rawUnits == 'mm') {
            //mm is the acronym for 'millimeter' that corresponds to 2.83465 pt
            $val = $rawVal * 283.465;
        } else if ($rawUnits == 'in') {
            //in is the acronym for 'inch' that corresponds to 72 pt
            $val = $rawVal * 72;
        } else if ($rawUnits == 'pc') {
            //pc is the acronym for 'pica' that corresponds to 12 points
            $val = $rawVal * 12;
        }  else if ($rawUnits == 'em' || $rawUnits == 'rem') {
            //em: this is just an estimate
            $val = $rawVal * 10;
        } else if ($rawUnits == 'ex') {
            //ex is the acronym for 'x-height' aproximately half em
            $val = $rawVal * 5;
        } 
        //return numeric value in the requested units
        if ($unit == 'pt') {
            return $val;
        } else if ($unit == 'px') {
            //px is the acronym for 'pixel' that corresponds to 0.75 points
            return  $val/0.75;
        } else if ($unit == 'cm') {
            //cm is the acronym for 'centimeter' that corresponds to 28.3465 pt
            return  $val/28.3465;
        } else if ($unit == 'mm') {
            //mm is the acronym for 'millimeter' that corresponds to 283.465 pt
            return  $val/2.83465;
        } else if ($unit == 'in') {
            //in is the acronym for 'inch' that corresponds to 72 pt
            return  $val/72;
        } else if ($unit == 'pc') {
            //pc is the acronym for 'pica' that corresponds to 12 points
            return  $val/12;
        }  else if ($unit == 'em' || $unit == 'rem') {
            //em: this is just an estimate
            return  $val/10;
        } else if ($unit == 'ex') {
            //ex is the acronym for 'x-height' aproximately half em
            return  $val/5;
        } else {
            return 0;
        }
    }
    
    /**
     * Gets a CSS property and chekcs for unit to be converted to rems
     * We assueme global units have been set so 1rem corresponds aprox to
     * 12px or 10pt
     *
     * @param string $prop
     * @return float
     * @access public
     * @static
     */
    public static function convert2rems($prop)
    {   
        $regex = '/([0-9]+\.?[0-9]*)\s*(px|em|rem|ex|in|cm|mm|pt|pc)/i';
        $result = \preg_replace_callback($regex, 'self::unit2rems', $prop);
        return $result;
    }
    
    /**
     * Gets a CSS property and chekcs for unit to be converted to rems
     * We assueme global units have been set so 1rem corresponds aprox to
     * 12px or 10pt
     *
     * @param string $prop
     * @return string
     * @access public
     * @static
     */
    public static function unit2rems($prop)
    {   
        $num = self::convertUnits('rem', $prop[0]);
        return $num . 'rem';
    }
    
    /**
     * generates a unique id
     *
     * @param string $prefix
     * @param int $length
     * @return string
     * @access public
     * @static
     */
    public static function generateId($prefix = 'style_')
    {
        Docxpresso\CreateDocument::$counter++;
        $prefix = Docxpresso\CreateDocument::$unid . '-' . $prefix;
        $id = $prefix . Docxpresso\CreateDocument::$counter;
        return $id;
    }
    
    /**
     * sums the given values taken into account their dimensions
     *
     * @param array $data
     * @param string $unit
     * @param boolean $number
     * @return string
     * @access public
     * @static
     */
    public static function sum($data, $unit, $number = true)
    {
        $raw = array();
        foreach ($data as $element){
            $raw[] = \floatval(self::convertUnits($unit, $element));
        }
        if ($number) {
            return array_sum($raw);
        } else {
            return array_sum($raw) . $unit;
        }
    }
	
    /**
     * Sanitize input to be included within an eval for computing a math formula
     *
     * @param string $str
     * @return string
     * @access public
     * @static
     */
    public static function sanitize4eval($str)
    {   
        $metachars = array('$', '{', '}', '[', ']', '`', ';', '"', '\'');
        $str = \str_replace($metachars, '', $str);
        return $str;
    }
    
    /**
     * reverses the sign of a CSS property
     *
     * @param string $str
     * @param complete $bool
     * @return string
     * @access public
     * @static
     */
    public static function reverseSign($str, $complete = false)
    {
        $str = \trim($str);
        $negative = false;
        if(strpos($str, '-') === 0){
            $negative = true;
        }
        
        if ($negative) {
            $str = \substr($str, 1);
        } else if (!$negative && $complete){
            $str = '-' . $str;
        }
        return $str;
    }
    
    /**
     * This method computes the number of nested lists
     *
     * @param DOMNode $list
     * @return integer
     * @access private
     */
    private function _computeListDepth($list, $depth = 0)
    {
        $child = $list->firstChild->firstChild;
        $tag = $child->nodeName;
        if($tag == 'text:list'){
            $depth++;
            return $this->_computeListDepth($child, $depth);
        } else {
            return $depth;
        }
       
    }
    
    /**
     * Sets the transform parameter following CSS standards
     *
     * @param string $transform
     * @return string
     * @access private
     */
    private function _parseTransform($name, $value, $width = 0, $height = 0) 
    {
        $transform = \trim($value);
        $regex = '/(\s?[^\(]*\([^\)]*\))/';
        preg_match_all($regex, $transform, $matches);
        $list = $matches[0];
        $l = \count($list);
        for ($j = 0; $j < $l; $j++) {
            $list[$j] = $this->_translate($list[$j]);
            $list[$j] = $this->_rotate($list[$j], $width, $height);
        }
        //the ODF standard changes the order
        $def = \array_reverse($list);
       
        $prop = \implode(' ', $def);
        return 'transform: ' . $prop . ';';
    }
    
    /**
     * Extracts the rotation parameters of a SVG transform
     *
     * @param string $transform
     * @return string
     * @access private
     */
    private function _rotate($transform, $width, $height) 
    {
        //we need the width and height to change the center of rotation
        $tregex = '/rotate\s?\(([^\)]*)\)/';

        preg_match_all($tregex, $transform, $matches);

        if (\count($matches[1])) {
            $replace = array();
            foreach($matches[1] as $value){
                    $value = preg_replace('/\s+/', ' ', $value);
                    //the factor shoud be 60 but it renders better
                    //with 57.296(!?)
                    $replace[] = $value * 57.2957 * (-1) . 'deg';
            }
            $transform = str_replace($matches[1], $replace, $transform);
            $transform = str_replace(' ', '', $transform);
            $pretrans = 'translate(-' . $width . ', -' . $height .') ';
            $postrans = ' translate(' . $width . ', ' . $height .')';
            $transform = $pretrans . $transform . $postrans;
        }
        
        return $transform;
        
    }
    
    /**
     * Extracts the translation parameters of a SVG transform
     *
     * @param string $transform
     * @return array
     * @access private
     */
    private function _translate($transform) 
    {
        $tregex = '/translate\s?\(([^\)]*)\)/';

        preg_match_all($tregex, $transform, $matches);

        if (\count($matches[1])) {
            $replace = array();
            foreach($matches[1] as $value){
                    $value = preg_replace('/\s+/', ' ', $value);
                    $replace[] = implode(',', explode(' ', $value));
            }
            $transform = str_replace($matches[1], $replace, $transform);
            $transform = str_replace(' ', '', $transform);
        }
        
        return $transform;
        
    }
    
    /**
     * resizes a given data by the provided factor
     *
     * @param float $factor
     * @param string $prop
     * @return string
     * @access private
     */
    private function _resize($factor, $prop)
    {   
        if (empty($prop)) {
            return 0;
        }
        $matches = array();
        $regex = '/([0-9]+\.?[0-9]*)\s*(px|em|rem|ex|in|cm|mm|pt|pc)?/i';
        \preg_match($regex, $prop, $matches);
        $rawVal = $matches[1];
        if (isset($matches[2])) {
            $rawUnits = $matches[2];
        } else {
            $rawUnits = 'pt';
        }
        if (!isset($rawVal)) {
            //regex failed
            return 0;
        }
        //rescale the value to points
        $val = $rawVal * $factor;
        $result = $val . $rawUnits;
        return $result;
    }

}