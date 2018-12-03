<?php

/**
 * SVGParser
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


/**
 * This class parses teh ODF draw elements to convert them into SVG
 *
 * @package    Docxpresso
 * @subpackage ODF2HTML5
 */

class SVGParser
{
    /**
     * XPath instance of content.xml
     * 
     * @var DOMXPath
     * @access private
     */
    private $_xpath;
    /**
     * XPath instance of styles.xml
     * 
     * @var DOMXPath
     * @access private
     */
    private $_stylesXpath;
    /**
     * SVG base node
     * 
     * @var DOMNode
     * @access private
     */
    private $_node;
    /**
     * document styles
     * 
     * @var array
     * @access private
     */
    private $_style;
    
    /**
     * Construct
     *
     * @param CreateDocument $doc
     * @access public
     */
    public function __construct($doc)
    {          
        //initialize variables
        $this->_dom = $doc->getDOM();
        $this->_xpath = new \DOMXPath($this->_dom['content.xml']);
        $this->_stylesXpath = new \DOMXPath($this->_dom['styles.xml']);
        $this->_drawNodeList = array('dr3d:scene' => true,
                                     'draw:a' => true,
                                     'draw:caption' => true,
                                     'draw:circle' => true,
                                     'draw:connector' => true,
                                     'draw:control' => true,
                                     'draw:custom-shape' => true,
                                     'draw:ellipse' => true, 
                                     'draw:frame' => true,
                                     'draw:g' => true,
                                     'draw:glue-point' => true,
                                     'draw:line' => true,
                                     'draw:measure' => true,
                                     'draw:page-thumbnail' => true,
                                     'draw:path' => true,
                                     'draw:polygon' => true,
                                     'draw:polyline' => true,
                                     'draw:rect' => true,
                                     'draw:regular-polygon' => true,
                                     'office:event-listeners' => true,
                                     'svg:desc' => true, 
                                     'svg:title' => true,
                                     );
        
    }
    
    /**
     * Renders SVG nodes
     *
     * @param DOMNode $svgNode
     * @param DOMNode $odfNode
     * @return DOMNode
     * @access public
     */
    public function render($svgNode, $odfNode, $style) 
    {
        $this->_style = $style;
        //determine node type
        $type = $odfNode->nodeName;
        switch ($type) {
            case 'draw:g':
                $node = $this->_g($svgNode, $odfNode);
                break;
            case 'draw:custom-shape':
                $node = $this->_customShape($svgNode, $odfNode);
                break;
            case 'draw:connector':
            case 'draw:line':
                $node = $this->_line($svgNode, $odfNode);
                break;
            default:
                $node = $this->_g($svgNode, $odfNode);
                break;
        }

        return $node;
    }
    
    /**
     * Computes the required svg height or width
     *
     * @param string $dimension it can take the values 'x' or 'y'
     * @param DOMNode $node
     * @param string $offset
     * @return string
     * @access private
     */
    private function _calculateDimension($dimension, $node, $offset = '0pt') 
    {
        //the sources of height information are given by width and height
        //attributes of the (child) nodes, their x and y (positioning) attr.,
        //the transform (translate) property and eventually the associated style
        //properties (we will ignore the last ones by the time being because it
        //do not seem to be used by Open and Libre Office
        if ($dimension == 'x') {
            $dim = 'svg:width';
            $pos = 'svg:x';
            $p1 = 'svg:x1';
            $p2 = 'svg:x2';
        } else if ($dimension == 'y') {
            $dim = 'svg:height';
            $pos = 'svg:y';
            $p1 = 'svg:y1';
            $p2 = 'svg:y2';
        } 
        $dimArray = array();
        $childs = $node->childNodes;
        foreach ($childs as $child) {    
            if ($child->nodeType == 1) {
                $type = $child->getAttribute('draw:type');
                $name = $child->nodeName;
                //TODO: check for other possible child types
                if ($child->nodeType == 1 && 
                    ($type == 'line' || $name == 'draw:line')
                    ) {
                    $l = $child->getAttribute($pos);
                    $d_1 = $child->getAttribute($p1);
                    $d_2 = $child->getAttribute($p2);
                    $d1 = \floatval(ODF2HTML5::convertUnits('pt', $d_1));
                    $d2 = \floatval(ODF2HTML5::convertUnits('pt', $d_2));
                    $size = abs($d2 - $d1);
                    $size .= 'pt';
                    $t = '12pt';
                    if ($dimension == 'x') {
                            $sum = array($size, $t, $offset);
                    } else if ($dimension == 'y') {
                            $sum = array($size, $l, $t, $offset);
                    }
                    $dimArray[] = self::sum($sum, 'pt');
                } else if ($child->nodeType == 1){
                    $d = $child->getAttribute($dim);
                    $l = $child->getAttribute($pos);
                    /*$trans = $this->_translate($dimension,
                    $child->getAttribute('draw:transform'));
                    $t = $trans[$dimension];*/
                    $t = 0;
                    if (empty($d) && empty($l)){
                        $d = $this->_calculateDimension($dimension, $child);
                    } else if (empty($d)){
                        $d = $this->_calculateDimension($dimension, $child, $l);
                    }
                    if ($dimension == 'x') {
                        $sum = array($d, $t, $offset);
                    } else if ($dimension == 'y') {
                        $sum = array($d, $l, $t, $offset);
                    }
                    $dimArray[] = self::sum($sum, 'pt');
                }
            }
        }
        //get the biggest number in dimArray
        if (\count($dimArray) > 0) {
            return \max($dimArray) . 'pt';
        } else {
            return '0pt';
        }
    }
    
    /**
     * Renders the draw:custom-shape node with all the required attributes
     *
     * @param DOMNode $baseNode
     * @param DOMNode $odfNode
     * @return DOMNode
     * @access private
     */
    private function _customShape($baseNode, $odfNode) 
    {
        $height = $odfNode->getAttribute('svg:height');
        $width = $odfNode->getAttribute('svg:width');
        $x = $odfNode->getAttribute('svg:x');
        $y = $odfNode->getAttribute('svg:y');
        $transform = $odfNode->getAttribute('draw:transform');
        $graphicStyle = $odfNode->getAttribute('draw:style-name');
        $anchor = $odfNode->getAttribute('text:anchor-type');
        //the text style is taken into account when parsing the text:p node
        //$textStyle = $odfNode->getAttribute('draw:text-style-name');
        if ($anchor == 'paragraph'){
            $style = 'position: relative;';
        } else {
            $style = 'position: absolute;';
        }
        $style .= ' float: none !important;';
        //let us try to extract the stroke width from the grapich style
        //in order to find the value that we have to add to 0 width/height
        //svg nodes
        $factor = 1;
        if (isset($this->_style['div.' . $graphicStyle])) {
            $stroke = ODF2HTML5::extractSingleProperty(
                                    'stroke-width', 
                                    $this->_style['div.' . $graphicStyle]
                                    );
            $strokeWidth = ODF2HTML5::convertUnits('px', $stroke);
            $factor = \ceil($strokeWidth);
        }
        if (!empty($height)) {
            $height = $this->_repairDimension($height, $factor);
            $style .= 'height: ' . $height . ';';
        }
        if (!empty($width)) {
            $width = $this->_repairDimension($width, $factor);
            $style .= 'width: ' . $width . ';';
        }
        if (!empty($x)) {
            $style .= 'margin-left: ' . $x . ';';
        }
        if (!empty($y)) {
            $style .= 'margin-top: ' . $y . ';';
        }
        if (!empty($transform)) {
            $transform = $this->_parseTransform($transform);
            $style .= 'transform: ' . $transform . ';';
            $style .= 'transform-origin: 0% 0%;';
        }
        $baseNode->setAttribute('style', $style);
        if (!empty($graphicStyle)) {
            $baseNode->setAttribute('class', $graphicStyle);
        }
        $geometries = $odfNode->getElementsByTagName('enhanced-geometry');
        if ($geometries->length > 0) {
            $geo = $geometries->item(0);
            $viewBox = $geo->getAttribute('svg:viewBox');
            $svg = $baseNode->ownerDocument->createElement('svg');
            $svgStyle = 'position: absolute; top: 0; left: 0;';
            $svgStyle .= 'padding: 0 !important; border: none !important';
            $svg->setAttribute('style', $svgStyle);
            $svg->setAttribute('preserveAspectRatio', 'none');
            $svg->setAttribute('fill-rule', 'evenodd');
            $svg->setAttribute('class', $graphicStyle);

            if (!empty($height)) {
                $svg->setAttribute('height', $height);
            }
            if (!empty($width)) {
                $svg->setAttribute('width', $width);
            }
            if(!empty($viewBox)){
                $viewBox = $this->_widenViewBox($viewBox, $factor);
                $scaleX = 1;
                if (!empty($viewBox) && !empty($width)) {
                    $scaleX = $this->_scale($viewBox, $width, 'x');
                }
                $scaleY = 1;
                if (!empty($viewBox) && !empty($height)) {
                    $scaleY = $this->_scale($viewBox, $height, 'y');
                }
                $viewBox = $this->_repairViewBox($viewBox, $scaleX, $scaleY);
                $svg->setAttribute('viewBox', $viewBox);
            }
            
            $path = $geo->getAttribute('draw:enhanced-path');
            if (!empty($path)) {
                $g = $baseNode->ownerDocument->createElement('g');
                //we also need to pass the dimensions in 1/100th of a mm units
                $xmm = ODF2HTML5::convertUnits('mm', $x) * 100;
                $ymm = ODF2HTML5::convertUnits('mm', $y) * 100;
                $dimensions = array($x, $y, $xmm, $ymm);
                $this->_parseEnhancedPaths($g, 
                                           $path, 
                                           $geo, 
                                           $graphicStyle,
                                           $dimensions,
                                           $scaleX,
                                           $scaleY);
                $svg->appendChild($g);
            }
        }
        $baseNode->appendChild($svg);
        return $baseNode;
    }
    
    /**
     * Renders the draw:g node with all the required attributes
     *
     * @param DOMNode $baseNode
     * @param DOMNode $odfNode
     * @return DOMNode
     * @access private
     */
    private function _g($baseNode, $odfNode) 
    {
        //SVG standard requires always height and width attributes for
        //proper rendering of the <svg> node. Unfortuanetly Open and Libre 
        //Office compute them on real time the required size so we are also 
        //obliged to do so.
        $height = 0;
        $width = 0;
        //First check if the height and width attribute are explicitely defined
        $height = $odfNode->getAttribute('svg:height');
        $width = $odfNode->getAttribute('svg:width');
        if (empty($height)) {
            $height = $this->_calculateDimension('y', $odfNode);
        }
        if (empty($width)) {
            $width = $this->_calculateDimension('x', $odfNode);
        }
        $halign = $odfNode->getAttribute('draw:textarea-horizontal-align');
        $style = '';
        //$style = ' float: none !important; ';
        $style .= 'left: 0;';
        $style .= 'height: ' . $height . ';';
        //check if there is an text: anchor-type element
        $anchor = $odfNode->getAttribute('text:anchor-type');
        if (empty($anchor)) {
            $style .= 'position: absolute;';
            $style .= 'width: ' . $width . ';';
            //$style .= 'left: 0.588cm;';
        } else {
            $style .= 'width: ' . $width . ';';
        }
        $x = $odfNode->getAttribute('svg:x');
        if (!empty($x)) {
            $style .= 'left: ' . $x . ';';
        }
        $y = $odfNode->getAttribute('svg:y');
        if (!empty($y)) {
            $style .= 'margin-top: ' . $y . ';';
        }
        //TODO: remove border
        //$style .= 'border: 1px solid red;';
        $baseNode->setAttribute('style', $style);
        $class = $odfNode->getAttribute('draw:style-name');
        $baseNode->setAttribute('class', $class);
        return $baseNode;
    }
    
    /**
     * Renders the draw:connector and draw:line nodes with all the required
     *  attributes
     *
     * @param DOMNode $baseNode
     * @param DOMNode $odfNode
     * @return DOMNode
     * @access private
     */
    private function _line($baseNode, $odfNode) 
    {
        //SVG standard requires always height and width attributes for
        //proper rendering of the <svg> node. Unfortuanetly Open and Libre 
        //Office compute them on real time the required size so we are also 
        //obliged to do so.
        $x1 = $odfNode->getAttribute('svg:x1');
        $x1 = ODF2HTML5::convertUnits('px', $x1);
        $x2 = $odfNode->getAttribute('svg:x2');
        $x2 = ODF2HTML5::convertUnits('px', $x2);
        $y1 = $odfNode->getAttribute('svg:y1');
        $y1 = ODF2HTML5::convertUnits('px', $y1);
        $y2 = $odfNode->getAttribute('svg:y2');
        $y2 = ODF2HTML5::convertUnits('px', $y2);
        
        //we set a minimum height and width of 5 pixels
         
        $raw_height = max((float) $y2 - (float) $y1, 5);
        $raw_width = max((float) $x2 - (float) $x1, 5);
        $height = $raw_height + $y1;
        $width = $raw_width + $x1;
        
        $baseNode->setAttribute('height', $height);
        $baseNode->setAttribute('width', $width);
        
        //we have to create the line node
        $line = $baseNode->ownerDocument->createElement('line');
        $line->setAttribute('x1', $x1);
        $line->setAttribute('x2', $x2);
        $line->setAttribute('y1', $y1);
        $line->setAttribute('y2', $y2);
        $style = '';
        //$style = ' float: none !important; ';
        //$style .= 'left: 0;';
        //$style .= 'height: ' . $height . ';';
        //check if there is an text: anchor-type element
        $anchor = $odfNode->getAttribute('text:anchor-type');
        if (empty($anchor)) {
            $style .= 'position: absolute;';
            $baseNodeStyle = $baseNode->getAttribute('style');
            if (!empty($baseNodeStyle)) {
                $baseNodeStyle .= ';';
            } 
            $baseNodeStyle .= 'position: absolute;';
            //we set the overflow to visible in order to show markers and also
            //allow for thick lines
            $baseNodeStyle .= 'overflow: visible;';
            $baseNode->setAttribute('style',$baseNodeStyle);
        } else {
            $baseNodeStyle = $baseNode->getAttribute('style');
            if (!empty($baseNodeStyle)) {
                $baseNodeStyle .= ';';
            }
            //we set the overflow to visible in order to show markers and also
            //allow for thick lines
            $baseNodeStyle .= 'overflow: visible;';
            $baseNode->setAttribute('style',$baseNodeStyle);
        }
        $line->setAttribute('style', $style);
        $class = $odfNode->getAttribute('draw:style-name');
        $baseNode->setAttribute('class', $class);
        $line->setAttribute('class', $class);
        $baseNode->appendChild($line);
        //search for markers
        if (!empty($class)){
            //if required create the marker
            $queryStyle = '//style:style[@style:name="' . $class . '"]';
            $queryStyle .= '/style:graphic-properties';
            $graphicProps = $this->_xpath->query($queryStyle);
            if ($graphicProps->length > 0) {
               $prop =  $graphicProps->item(0);
               $m_start = $prop->getAttribute('draw:marker-start');
               $m_end = $prop->getAttribute('draw:marker-end');
               $m_start_c = $prop->getAttribute('draw:marker-start-center');
               $m_end_c = $prop->getAttribute('draw:marker-end-center');
               if (!empty($m_start)
                    || !empty($m_end)
                    || !empty($m_start_c)
                    || !empty($m_end_c)        
                   ){
                $posArray = array('x1' => $x1,
                                  'y1' => $y1,
                                  'x2' => $x2,
                                  'y2' => $y2);
                $options = array('m_start' => $m_start,
                                 'm_end' => $m_end,
                                 'm_start_c' => $m_start_c,
                                 'm_end_c' => $m_end_c);
                $this->_createMarkers($baseNode, $posArray, $options);
               }
            }
        }
        return $baseNode;
    }
    
    /**
     * Chop an enhanced path into its simple path components and inserts
     * the corresponding <path> elements into the given <g> element
     *
     * @param DOMNode $g
     * @param string $paths
     * @param DOMNode $geo
     * @param string $style
     * @param array $dimensions
     * @return DOMNode
     * @access private
     */
    private function _parseEnhancedPaths($g, 
                                         $paths, 
                                         $geo, 
                                         $style, 
                                         $dimensions,
                                         $scaleX,
                                         $scaleY) 
    {
        $pathArray = \explode('N', $paths);
        foreach ($pathArray as $path) {
            if (!empty($path)) {
                $pathNode = $g->ownerDocument->createElement('path');
                if (\strpos($path, 'S') !== false) {
                    $path = \str_replace('S', '', $path);
                    $pathNode->setAttribute('stroke', 'none');
                }
                if (\strpos($path, 'F') !== false) {
                    $path = \str_replace('F', '', $path);
                    $pathNode->setAttribute('fill', 'none');
                }
                //we need now to parse for formulas
                if (\strpos($path, '?') !== false) {
                    $path = $this->_parseFormula($path, 
                                                 $geo, 
                                                 $style, 
                                                 $dimensions);
                }
                //take care of counterclockwise elliptical paths
                if (\strpos($path, 'W') !== false) {
                    $path = \str_replace('W', 'A', $path);
                }
                //recompute dimesions to adapt them to the scaled viewBox
                $path = $this->_rescalePath($path, $scaleX, $scaleY);
                $pathNode->setAttribute('d', $path);
                $g->appendChild($pathNode);
            }
        }
        return $g;        
    }
    
    /**
     * Parses the path formula
     *
     * @param string $path
     * @param DOMNode $geo
     * @param array $dimensions
     * @return string
     * @access private
     */
    private function _parseFormula($path, $geo, $style, $dimensions) 
    {
        //we first need to extract certain data from the node
        //viewBox
        $viewBox = $geo->getAttribute('svg:viewBox');
        $posArray = \explode(' ', $viewBox);
        $left = $posArray[0];
        $top = $posArray[1];
        $right = $posArray[2];
        $bottom = $posArray[3];
        //modifiers
        $modif = $geo->getAttribute('draw:modifiers');
        if (!empty($modif)){
            $modifiers = \explode(' ', $modif);
        } else {
            $modifiers = array();
        }
        $xstretch = $geo->getAttribute('draw:path-stretchpoint-x'); 
        $ystretch = $geo->getAttribute('draw:path-stretchpoint-y');
        //we also need to parse the style
        $hasfill = 0;
        $hasstroke = 0;
        $query = '//style:style[@style:name="' . $style . '"]';
        $query .= '/style:graphic-properties';
        $nodes = $this->_xpath->query($query);
        $numNodes = $nodes->length;
        if ($numNodes > 0) {
           $stroke = $nodes->item(0)->getAttribute('draw:stroke');
           if (!empty($stroke) && $stroke != 'none') {
               $hasstroke = 1;
           }
           $fill = $nodes->item(0)->getAttribute('draw:fill');
           if (!empty($fill) && $fill != 'none') {
               $hasfill = 1;
           }
        }
        //get the formula
        $formula = array();
        $childs = $geo->childNodes;
        foreach ($childs as $child) {
            if ($child->nodeName == 'draw:equation' ) {
                $n = $child->getAttribute('draw:name');
                $f = $child->getAttribute('draw:formula');
                $formula[$n] = $f;
            }
        }
        //replacements
        $replace = array();
        $replace['pi'] = 3.1416;
        $replace['left'] = $left;
        $replace['top'] = $top;
        $replace['right'] = $right;
        $replace['bottom'] = $bottom;
        $replace['xstretch'] = $xstretch;
        $replace['ystretch'] = $ystretch;
        $replace['hasstroke'] = $hasstroke;
        $replace['hasfill'] = $hasfill;
        $replace['logwidth'] = $dimensions[2];
        $replace['logheight'] = $dimensions[3];
        $replace['width'] = $dimensions[0];
        $replace['height'] = $dimensions[1];
        //tokenize the path
        $tokens = \explode(' ', $path);
        $len = \count($tokens);
        for($j = 0; $j < $len; $j++) {
            $tokens[$j] = $this->_evalFormula($tokens[$j],
                                              $formula,
                                              $replace,
                                              $modifiers);
        }
        $newPath = \implode(' ', $tokens);
        $newPath = \str_replace('  ', ' ', $newPath);
        //$newPath = \str_replace('A', 'L', $newPath);
        //$newPath = \str_replace('W', 'L', $newPath);
        return $newPath; 
    }
    
    /**
     * Parses the path formula
     *
     * @param string $path
     * @param DOMNode $geo
     * @param array $dimensions
     * @return string
     * @access private
     */
    private function _evalFormula($token, $formula, $replace, $modifiers) 
    { 
        //var_dump($token);
        $constants = array('A' => true,
                           'B' => true,
                           'C' => true,
                           'F' => true,
                           'L' => true,
                           'M' => true,
                           'N' => true,
                           'Q' => true,
                           'S' => true,
                           'T' => true,
                           'U' => true,
                           'V' => true,
                           'W' => true,
                           'X' => true,
                           'Y' => true,
                           'Z' => true
                           );
        if (isset($constants[$token]) || \is_numeric($token)){
            return $token;
        } else if (\strpos($token, '$') !== false
                   || \strpos($token, '?f') !== false
                   || \strpos($token, 'if(') !== false) {
            //first take care of the if conditions
            \preg_match('|if\(([^,]+),([^,]+),([^,)]+)\)|',
                            $token,
                            $m);
            if (\count($m) > 0) {
                //get the if arguments
                $val = $this->_evalFormula($m[1], 
                                           $formula, 
                                           $replace, 
                                           $modifiers);
                if ($val > 0) {
                    $follow = $m[2];
                } else {
                    $follow = $m[3];
                }

                $test = $this->_evalFormula($follow, 
                                           $formula, 
                                           $replace, 
                                           $modifiers);
                return $test;
            } else {
                $token = \preg_replace_callback('|\$([0-9]+)|',
                    function($matches) use($modifiers){
                        return '(' . $modifiers[$matches[1]] . ')';
                    },
                    $token);
                //we add the parenthesis for the right grouping
                $token = \preg_replace_callback('|\?(f[0-9]+)|',
                    function($matches) use($formula){
                        return '(' . $formula[$matches[1]] . ')';
                    },
                    $token);
                foreach ($replace as $key => $value) {
                    $token = str_replace($key, $value, $token);
                }
                return $this->_evalFormula($token, 
                                           $formula, 
                                           $replace, 
                                           $modifiers);
            }   
        } else {
            $token = ODF2HTML5::sanitize4eval($token);
            return trim(@eval('return ' . $token . ';'));
        }
    }
    
    /**
     * Sets the transform parameter following CSS standards
     *
     * @param string $transform
     * @return string
     * @access private
     */
    private function _parseTransform($transform) 
    {
        $transform = \trim($transform);
        $regex = '/(\s?[^\(]*\([^\)]*\))/';
        preg_match_all($regex, $transform, $matches);
        $list = $matches[0];
        $l = \count($list);
        for ($j = 0; $j < $l; $j++) {
            $list[$j] = $this->_translate($list[$j]);
            $list[$j] = $this->_rotate($list[$j]);
        }
        //the ODF standard changes the order
        $def = \array_reverse($list);
        return \implode(' ', $def);
    }
    
    /**
     * Changes dimension 0px for 1px so thin lines are rendered in HTML
     *
     * @param string $dim
     * @param int $factor
     * @return string
     * @access private
     */
    private function _repairDimension($dim, $factor) 
    {
        $pixels = ODF2HTML5::convertUnits('px', $dim);
        if ($pixels == 0) {
            return $factor . 'px';
        } else {
            return $dim;
        }
    }
    
    /**
     * Widens the dimension of the box for pure lines that are zero in the ODF
     * SVG stantard and not in HTML
     *
     * @param string $viewBox
     * @param int $factor
     * @param float $scaleY
     * @return string
     * @access private
     */
    private function _widenViewBox($viewBox, $factor) 
    {
        $regex = '/(([0-9]+)[^\d]*)/';
        \preg_match_all($regex, $viewBox, $matches);
        $newViewBox = array();
        if(isset($matches[2])) {
            $newViewBox[0] = $matches[2][0];
            $newViewBox[1] = $matches[2][1];
            $newViewBox[2] = $matches[2][2];
            $newViewBox[3] = $matches[2][3];

            if ($newViewBox[0] == $newViewBox[2]) {
                $newViewBox[2] = $newViewBox[2] + $factor;
            }
            if ($newViewBox[1] == $newViewBox[3]) {
                $newViewBox[3] = $newViewBox[3] + $factor;
            }
        }
        if (\count($newViewBox) == 4) {
            return \implode(' ', $newViewBox);
        } else {
            return $viewBox;
        }
    }
    
    /**
     * Computes the new viewBox dimensions so they match the viewPort dimensions
     *
     * @param string $viewBox
     * @param float $scaleX
     * @param float $scaleY
     * @return string
     * @access private
     */
    private function _repairViewBox($viewBox, $scaleX, $scaleY) 
    {
        
        $regex = '/(([0-9]+)[^\d]*)/';
        \preg_match_all($regex, $viewBox, $matches);
        $newViewBox = array();
        if(isset($matches[2]) && !empty($scaleX)) {
            $newViewBox[0] = \round($matches[2][0]/$scaleX);
            $newViewBox[1] = \round($matches[2][1]/$scaleY);
        } 
        if(isset($matches[2]) && !empty($scaleY)) {
            $newViewBox[2] = \round($matches[2][2]/$scaleX);
            $newViewBox[3] = \round($matches[2][3]/$scaleY);
        }

        if (\count($newViewBox) == 4) {
            return \implode(' ', $newViewBox);
        } else {
            return $viewBox;
        }
    }
    
    /**
     * rescales the path to fit the redefined viewBox
     *
     * @param string $path
     * @param float $scaleX
     * @param float $scaleY
     * @return string
     * @access private
     */
    private function _rescalePath($path, $scaleX, $scaleY) 
    {
        //remove additional white space
        $path = \trim($path);
        $path = preg_replace('/\s+/', ' ', $path);
        $data = \explode(' ', $path);
        $length = \count($data);
        $counter = 1;
        for ($j = 0; $j < $length; $j++) {
            //we do not take into account angles in T & U commands because
            //the SVG standard is diferente so it will not render them
            //correctly anyhow
            if (\is_numeric($data[$j])) {
                $num = (int) $data[$j];
                if ($counter&1 && !empty($scaleX)){
                    $data[$j] = \round($num/$scaleX);
                } else if (!empty($scaleY)) {
                    $data[$j] = \round($num/$scaleY);
                }
                $counter++;
            }
            
        }
        return \implode(' ', $data);
    }
    
    /**
     * Computes the  scale factor depending on the viewBox and
     * viewPort dimensions
     *
     * @param string $viewBox
     * @param string $dim
     * @param string $axis
     * @return int
     * @access private
     */
    private function _scale($viewBox, $dim, $axis) 
    {
        $pixels = ODF2HTML5::convertUnits('px', $dim);
        $regex = '/(([0-9]+)[^\d]*)/';
        \preg_match_all($regex, $viewBox, $matches);
        if(isset($matches[2]) && $axis == 'x' && !empty($pixels)) {
            $xbox = max(1, $matches[2][2] - $matches[2][0]);
            return $xbox/$pixels;
        } else if(isset($matches[2]) && $axis == 'y' && !empty($pixels)) {
            $ybox = max(1, $matches[2][3] - $matches[2][1]);
            return $ybox/$pixels;
        } else {
            return 1;
        }
    }
    
    /**
     * Extracts the rotation parameters of a SVG transform
     *
     * @param string $transform
     * @return string
     * @access private
     */
    private function _rotate($transform) 
    {
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
     * Inserts a marker or markers in a line
     *
     * @param DOMNode $node
     * @param array $position
     * @param array $options
     * @return void
     * @access private
     */
    private function _createMarkers($node, $position, $options) 
    {

        if (!empty($options['m_start'])){
            $this->_marker($node, $position, $options['m_start'], true, false);
        } else if (!empty($options['m_start_c'])){
            $this->_marker($node, $position, $options['m_start_c'], true, true);
        }
        
        if (!empty($options['m_end'])){
            $this->_marker($node, $position, $options['m_end'], false, false);
        } else if (!empty($options['m_end_c'])){
            $this->_marker($node, $position, $options['m_end_c'], false, true);
        }
        
    }
    
    /**
     * Inserts a marker or markers in a line
     *
     * @param DOMNode $node
     * @param array $position
     * @param string $style
	 * @param boolean $s
	 * @param boolean $c
     * @return void
     * @access private
     */
    private function _marker($node, $position, $style, $s, $c) 
    {
        //let us first get the marker data from styles.xml
        $queryStyle = '//draw:marker[@draw:name="' . $style . '"]';
        $marker = $this->_stylesXpath->query($queryStyle);
        if ($marker->length > 0) {
            $mark = $marker->item(0);
            $viewBox = $mark->getAttribute('svg:viewBox');
            $d = $mark->getAttribute('svg:d');
            $svg = $node->ownerDocument->createElement('svg');
            $svgStyle = 'position: absolute; top: 0; left: 0;overflow: visible;';
            $svgStyle .= 'padding: 0 !important; border: none !important;';
            $svg->setAttribute('style', $svgStyle);
            if (!empty($viewBox)) {
				$svg->setAttribute('viewBox', $viewBox);
				$regex = '/(([0-9]+)[^\d]*)/';
				\preg_match_all($regex, $viewBox, $matches);
				$w = (int) $matches[0][2] - (int) $matches[0][0];
				$h = (int) $matches[0][3] - (int) $matches[0][1];
			} else {
				$w = 20;
				$h = 30;
			}
			$sf = 0.8;
			$scaleFactor = 0.5 * $sf;
            $w = $w * $scaleFactor;
            $h = $h * $scaleFactor;
            $svg->setAttribute('width', $w);
            $svg->setAttribute('height', $h);
            $c1 = $position['x2'] - $position['x1'];
            $c2 = $position['y2'] - $position['y1'];
            if ($c1 == 0){
                $angle = 0;
            } else if ($c2 == 0) {
                $angle = 90;
            } else {
                $angle = 180 - atan2($c1, $c2) * 180/M_PI;
            }
                if ($s) {
                    $angle += 180;
                }
                if ($s) {
                    $sign = -1;
                } else {
                    $sign = 1;
                }
                if ($s) {
                    $x = $position['x1']  - $w/2;
                } else {
                    $x = $position['x2']  - $w/2;
                }
                if ($c) {
                    if ($c1 != 0){
                        $x = $x + $sign * $h * cos(atan2($c2, $c1))/2;
                    }
                }
                if ($s) {
                    $y = $position['y1'];
                } else {
                    $y = $position['y2'];
                }

                if ($c) {
                    $y = $y + $sign * $h * sin(atan2($c2, $c1))/2;
                }
            $svg->setAttribute('x', $x);
            $svg->setAttribute('y', $y);
            $class = $node->getAttribute('class');
            $svg->setAttribute('class', $class);
            $path = $node->ownerDocument->createElement('path');
            $transform = '';
            $transform .= 'rotate(' . $angle . ' , ' . $w/$sf . ', 0)';
            
            $path->setAttribute('transform', $transform);
            $path->setAttribute('class', $class);
            $path->setAttribute('d', $d);
                if (!empty($class)) {
                    $svgStyles = $this->_extractSVGStyles($class, $scaleFactor);
                    if (!empty($svgStyles['stroke-color'])) {
                        $path->setAttribute('fill', $svgStyles['stroke-color']);
                        $path->setAttribute('stroke', 
                                            $svgStyles['stroke-color']);
                    }
                    if (!empty($svgStyles['stroke-width'])) {
                        $path->setAttribute('stroke-width', 
                                            $svgStyles['stroke-width']);
                    }
                }
            $svg->appendChild($path);
            $node->appendChild($svg);
        }
	}
		
    /**
     * Extracts SVG properties from a style node
     *
     * @param string $class
     * @param float $scaleFactor
     * @return array
     * @access private
     */
    private function _extractSVGStyles($class, $scaleFactor) 
    {
        $query = '//style:style[@style:name="' . $class . '"]';
        $query .= '/style:graphic-properties';
        $props = $this->_xpath->query($query);
        $svg_prop = array();
        if ($props->length > 0) {
            $node = $props->item(0);
            $svg_prop['stroke-color'] = $node->getAttribute('svg:stroke-color');
            $strokeWidth = $node->getAttribute('svg:stroke-width');
            $regex = '/([0-9\-]+\.?[0-9]*)\s*(px|em|rem|ex|%|in|cm|mm|pt|pc)?/i';
            \preg_match($regex, $strokeWidth, $matches);
            if (isset($matches[1]) && isset($matches[1])) {
                $width = $matches[1]/$scaleFactor . $matches[2];
                $svg_prop['stroke-width'] = $width;
            }
        }
        return $svg_prop;
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
            $raw[] = \floatval(ODF2HTML5::convertUnits($unit, $element));
        }
        if ($number) {
            return array_sum($raw);
        } else {
            return array_sum($raw) . $unit;
        }
    }
}