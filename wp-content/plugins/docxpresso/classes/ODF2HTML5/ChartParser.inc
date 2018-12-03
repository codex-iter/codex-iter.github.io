<?php

/**
 * ChartParser
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

class ChartParser
{
    /**
     * ODF chart axis format
     * 
     * @var array
     * @access public
     * @static
     */
    public static $defaultColorPattern = array( '#5b9bd5',
                                                '#ed7d31',
                                                '#757575',
                                                '#ffc000',
                                                '#4472c4',
                                                '#70ad47',
                                                '#255e91',
                                                '#9e480e',
                                                '#636363',
                                                '#997300',
                                                '#264478',
                                                '#43682b',
                                                '#7cafdd',
                                                '#f1975a',
                                                '#b7b7b7',
                                                '#ffcd33',
                                                '#698ed0',
                                                '#8cc168',
                                                '#ed7d31',
                                                '#d26012',
                                            );
    /**
     * List of columns to apply specific number formatting
     * 
     * @var array
     * @access private
     */
    private $_applyFormatting;
    /**
     * ODF chart axis format
     * 
     * @var array
     * @access private
     */
    private $_axis;
    /**
     * ODF chart DOMDocument
     * 
     * @var DOMDocument
     * @access private
     */
    private $_chart;
    /**
     * ODF chart DOMXPath
     * 
     * @var DOMXPath
     * @access private
     */
    private $_chartXPath;
    /**
     * DOMDocument of content.xml needed only for spreadsheets
     * 
     * @var DOMDocument
     * @access private
     */
    private $_content;
    /**
     * ODF chart data
     * 
     * @var array
     * @access private
     */
    private $_data;
    /**
     * The name of the data table source
     * 
     * @var string
     * @access private
     */
    private $_dataSourceTable;
    /**
     * document type
     * 
     * @var string
     * @access private
     */
    private $_docType;
    /**
     * Chart global type info
     * 
     * @var array
     * @access private
     */
    private $_globalType;
    /**
     * ODF chart legend format
     * 
     * @var array
     * @access private
     */
    private $_legend;
    /**
     * Available number formats
     * 
     * @var array
     * @access private
     */
    private $_numberFormats;
    /**
     * ODF chart series format
     * 
     * @var array
     * @access private
     */
    private $_series;
    /**
     * ODF chart title data
     * 
     * @var array
     * @access private
     */
    private $_title;
    
    /**
     * Construct
     *
     * @param string $chart
     * @param DOMDocument $content
     * @access public
     */
    public function __construct($chart, $content, $docType = 'text')
    {          
        //initialize variables
        $this->_docType = $docType;
        $this->_chart = new \DOMDocument();
        $this->_chart->loadXML($chart);
        $this->_chartXPath = new \DOMXPath($this->_chart);
        $this->_content = $content;
        $this->_globalType = $this->_getGlobalType();
        $this->_numberFormats = $this->_parseNumberFormats();
        $this->_legend = $this->_getLegend();
        $this->_axis = $this->_getAxis();
        $this->_series = $this->_getSeries();
        $this->_title = $this->_getTitle(); 
        $this->_data = $this->_getRawData();
        $this->_applyFormatting = array();
    }
    
    /**
     * Renders the chart data in the selected JS compatible format
     *
     * @param array $options
     * @return mixed
     * @access public
     */
    public function render($options) 
    {
        //By the time being only the c3.js rendering is available
        if ($options['js'] == 'c3.js') {
            $engine = new C3JS($options);
        } else {
            return NULL;
        }
        $engine->setGlobalType($this->_globalType);
        $engine->setData($this->_data);
        $engine->setLegend($this->_legend);
        $engine->setAxis($this->_axis);
        $engine->setSeries($this->_series);
        $engine->setTitle($this->_title);
        $script = $engine->renderScript($this->_chart);
        return $script;
    }
    
    /**
     * Extracts the chart axis format
     *
     * @return mixed
     * @access private
     */
    private function _getAxis() 
    {
        $axisFormat = array();
        $axis = $this->_chart->getElementsByTagName('axis');
        if ($axis->length > 0) {
            foreach ($axis as $ax) {
                $dim = $ax->getAttribute('chart:dimension');
                $name = $ax->getAttribute('chart:name');
                if (empty($name)) {
                    $name = \uniqid();
                }
                $style = $ax->getAttribute('chart:style-name');
                $range= '';
                $categories = $ax->getElementsByTagname('categories');
                if ($categories->length > 0) {
                    $range = $categories->item(0)
                                    ->getAttribute('table:cell-range-address');
                }
                $axisFormat[$name][$dim] = array();
                $axisFormat[$name][$dim]['style'] = $style;
                $axisFormat[$name][$dim]['range'] = $range;
                //we need to check if tehre are specific number styles
                //attached to that axis
                $query = '//style:style[@style:name="' . $style . '"]';
                $axStyles = $this->_chartXPath->query($query);
                if ($axStyles->length > 0) {
                    $axStyle = $axStyles->item(0);
                    $dStyle = $axStyle->getAttribute('style:data-style-name');
                    if (isset($this->_numberFormats[$dStyle])) {
                        $temp = self::extractColumnRow($range);
                        if (\count($temp) > 1){
                            $this->_applyFormatting[$temp[0]] =
                                        $this->_numberFormats[$dStyle];     
                        }
                    }
                    //look for a chart minimum and maximum if any
                    $axisProps = $axStyle
                                    ->getElementsByTagName('chart-properties');
                    if ($axisProps->length > 0) {
                        $minimum = $axisProps->item(0)
                                             ->getAttribute('chart:minimum');
                        $maximum = $axisProps->item(0)
                                             ->getAttribute('chart:maximum');
                        $interv = $axisProps->item(0)
                                    ->getAttribute('chart:interval-major');
                    }
                    $axisFormat[$name][$dim]['props'] = array();
                    if ($minimum != ''){
                        $axisFormat[$name][$dim]['props']['min'] = $minimum;
                    }
                    if ($maximum != ''){
                        $axisFormat[$name][$dim]['props']['max'] = $maximum;
                    }
                    if ($interv != ''){
                        $axisFormat[$name][$dim]['props']['interval'] = $interv;
                    }
                }
                //look for grid info
                $axisFormat[$name][$dim]['grid'] = array();
                $grids = $ax->getElementsByTagName('grid');
                if ($grids->length > 0) {
                    $grid = $grids->item(0);
                    $class = $grid->getAttribute('chart:class');
                    if (empty($class)) {
                        $class = 'major';
                    }
                    $st = $grid->getAttribute('chart:style-name');
                    $axisFormat[$name][$dim]['grid']['class'] = $class;
                    $axisFormat[$name][$dim]['grid']['style'] = $st;
                }
            }
            return $axisFormat;
        } else {
            return false;
        }
        
    }
      
    /**
     * Gets the chart global type data
     *
     * @return array
     * @access private
     */
    private function _getGlobalType() 
    {
        $result = array();
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:chart:1.0';
        $class = $this->_chart->getElementsByTagNameNS($ns, 'chart')
                              ->item(0)
                              ->getAttribute('chart:class');
        $result['type'] = $class;
        if ($class != 'chart:pie') {
            //we have to distinguish betwen the horizontal an vertical bar
            //cases via the chart:vertical attribute of the chart:plot-area 
            //element
            $style = $this->_chart->getElementsByTagName('plot-area')
                              ->item(0)
                              ->getAttribute('chart:style-name');
            $xpath = new \DOMXPath($this->_chart);
            $query = 'string(//style:style[@style:name="' . $style . '"]';
            $query .= '/style:chart-properties/@chart:vertical)';
            $vertical = $xpath->evaluate($query);
            if ($vertical == 'true') {
                $result['rotated'] = true;
            }
            //we have to distinguish betwen stacked and not stacked charts
            $query = 'string(//style:style[@style:name="' . $style . '"]';
            $query .= '/style:chart-properties/@chart:stacked)';
            $stacked = $xpath->evaluate($query);
            if ($stacked == 'true') {
                $result['stacked'] = true;
            }
            //check if it is percentage stacked
            $query = 'string(//style:style[@style:name="' . $style . '"]';
            $query .= '/style:chart-properties/@chart:percentage)';
            $percentage = $xpath->evaluate($query);
            if ($percentage == 'true') {
                $result['percentage'] = true;
            }
        }
        return $result;
    }
    
    /**
     * Extracts the chart legend format
     *
     * @return mixed
     * @access private
     */
    private function _getLegend() 
    {
        $pos = array('start' => 'right', //c3JS does not support left pos.
                     'end' => 'right',
                     'bottom' => 'bottom',
                     'top' => 'top',
                     'bottom-end' => 'bottom',
                     'top-end' => 'top',
                     'bottom-start' => 'bottom',
                     'top-start' => 'top',);
        $legendFormat = array();
        $legends = $this->_chart->getElementsByTagName('legend');
        if ($legends->length > 0) {
            $legend = $legends->item(0);
            $position = $legend->getAttribute('chart:legend-position');
            if (!empty($position)) {
                $legendFormat['pos'] = $pos[$position];
            } else {
                $legendFormat['pos'] = 'bottom';
            }
            $legendFormat['align'] = $legend
                                     ->getAttribute('chart:legend-align');
            $legendFormat['x'] = $legend->getAttribute('svg:x');
            $legendFormat['y'] = $legend->getAttribute('svg:y');
            $legendFormat['style'] = $legend->getAttribute('chart:style-name');
            return $legendFormat;
        } else {
            return false;
        }
        
    }
    
    /**
     * Extracts the chart row data
     *
     * @return array
     * @access private
     */
    private function _getRawData() 
    {
        $tables = $this->_chart->getElementsByTagName('table');
                
        if ($tables->length > 0 && $this->_docType == 'text') {
            $rawData = $this->_getRawDataFromTables($tables);
        } else {
            //in this case we may have an spreadsheet with the data residing
            //in content.xml
            $xpath = new \DOMXPath($this->_content);
            $query = '//table:table[@table:name="'. $this->_dataSourceTable .'"]';
            $tables = $xpath->query($query);
            $rawData = $this->_getRawDataFromTables($tables);
        }
        return $rawData;
    }
    
    /**
     * Extracts the chart row data
     *
     * @param DOMNodeLists $tables
     * @return array
     * @access private
     */
    private function _getRawDataFromTables($tables) 
    {
        foreach ($tables as $table) {
            $name = $table->getAttribute('table:name');
            $rawData[$name] = array();
            $rowGroups = $table->childNodes;
            $rowCounter = 0;
            foreach ($rowGroups as $rowsGroup) {
                $rowName = $rowsGroup->nodeName;
                $rowspan = $rowsGroup->getAttribute('table:number-rows-repeated');
                if ($rowName == 'table:table-header-rows'
                    || $rowName == 'table:table-rows') {
                    $rows = $rowsGroup->childNodes;
                    foreach ($rows as $row){
                        $rowCounter++;
                        $rawData[$name][$rowCounter] = array();
                        $cellCounter = 0;
                        $cells = $row->childNodes;
                        foreach ($cells as $cell) {
                            $cellCounter++;
                            $letter = ODF2HTML5::rowLetter($cellCounter);
                            $value = $cell->getAttribute('office:value');
                            if (empty($value)) {
                                //we have to get the value of the first p node
                                $value = ' ';
                                $ps = $cell->getElementsByTagName('p');
                                if ($ps->length > 0) {
                                    $p = $ps->item(0);
                                    $value = $p->nodeValue;
                                }
                            }
                            if (isset($this->_applyFormatting[$letter])){
                                $value = $this->_format($value, $letter);
                            } 
                            $rawData[$name][$rowCounter][$letter] = $value;
                            //check if there is a colspan so we have to fill
                            //extra column data
                            $colspan = $cell->getAttribute('table:number-columns-repeated');
                            if ($colspan > 1 && $colspan < 20) {
                                for ($n = 1; $n < $colspan; $n++) {
                                    $cellCounter++;
                                    $letter = ODF2HTML5::rowLetter($cellCounter);
                                    $rawData[$name][$rowCounter][$letter] = $value;
                                }
                            }
                        }
                    }
                } else if ($rowName == 'table:table-row'){
                    //The charts generated by Word do not generate the
                    //wrapping table:table-rows element
                    $rowCounter++;
                    $rawData[$name][$rowCounter] = array();
                    $cellCounter = 0;
                    $cells = $rowsGroup->childNodes;
                    foreach ($cells as $cell) {
                        $cellCounter++;
                        $letter = ODF2HTML5::rowLetter($cellCounter);
                        $value = $cell->getAttribute('office:value');
                        if (empty($value)) {
                            $value = $cell->nodeValue;
                        }
                        if (isset($this->_applyFormatting[$letter])){
                            $value = $this->_format($value, $letter);
                        } 
                        $rawData[$name][$rowCounter][$letter] = $value;
                        //check if there is a colspan so we have to fill
                        //extra column data
                        $colspan = $cell->getAttribute('table:number-columns-repeated');
                        if ($colspan > 1 && $colspan < 20) {
                            for ($n = 1; $n < $colspan; $n++) {
                                $cellCounter++;
                                $letter = ODF2HTML5::rowLetter($cellCounter);
                                $rawData[$name][$rowCounter][$letter] = $value;
                            }
                        }
                    }   
                }
                if ($rowspan > 1 && $rowspan < 100) {
                    for ($n = 1; $n < $rowspan; $n++) {
                        $rawData[$name][$rowCounter + 1][$letter] = 
                                $rawData[$name][$rowCounter][$letter];
                        $rowCounter++;
                    }
                }
            }
        }
        return $rawData;
    }
    
    /**
     * Extracts the chart series format
     *
     * @return array
     * @access private
     */
    private function _getSeries() 
    {
        $seriesFormat = array();
        $series= $this->_chart->getElementsByTagName('series');
        $counter = 0;
        foreach ($series as $ser) {
            $class = $ser->getAttribute('chart:class');
            $style = $ser->getAttribute('chart:style-name');
            $label = $ser->getAttribute('chart:label-cell-address');
            $range = $ser->getAttribute('chart:values-cell-range-address');
            $temp = \explode('.', $range);
            $name = $temp[0];
            $this->_dataSourceTable = $name;
            $seriesFormat[$name][$counter] = array();
            $seriesFormat[$name][$counter]['class'] = $class;
            $seriesFormat[$name][$counter]['style'] = $style;
            if (!empty($label)) {
                $seriesFormat[$name][$counter]['label'] = $label;
            } else {
                $seriesFormat[$name][$counter]['label'] = ' ';
            }
            $seriesFormat[$name][$counter]['range'] = $range;
            $seriesFormat[$name][$counter]['data-points'] = array();
            //look for data point styles
            $points = $ser->getElementsByTagName('data-point');
            foreach ($points as $point) {
                $seriesFormat[$name][$counter]['data-points'][] = 
                        $point->getAttribute('chart:style-name');
            }
            $counter++;
        }
        return $seriesFormat;       
    }
    
    /**
     * Extracts the chart title data
     *
     * @return array
     * @access private
     */
    private function _getTitle() 
    {
        $data = array();
        $titles= $this->_chart->getElementsByTagName('title');
        if ($titles->length > 0) {
            $title = $titles->item(0);
            $data['style'] = $title->getAttribute('chart:style-name');
            $data['text'] = $title->nodeValue;
        }
        return $data;       
    }
    
    /**
     * Extracts the available number formats
     *
     * @return array
     * @access private
     */
    private function _parseNumberFormats() 
    {
        $nformats = array();
        $numbers= $this->_chart->getElementsByTagName('number-style');
        foreach ($numbers as $number) {
            $name = $number->getAttribute('style:name');
            if (!empty($name)) {
                $nformats[$name] = array();
                $nformats[$name]['type'] = 'number';
                $formats = $number->childNodes;
                foreach ($formats as $format) {
                    if ($format->nodeType == 1){
                        $nformats[$name]['decimals'] = 
                        $format->getAttribute('number:decimal-places');
                    }
                }
            }
        }
        $dates= $this->_chart->getElementsByTagName('date-style');
        foreach ($dates as $date) {
            $name = $date->getAttribute('style:name');
            if (!empty($name)) {
                $nformats[$name] = array();
                $nformats[$name]['type'] = 'date';
                $formats = $date->childNodes;
                $date_format = '';
                foreach ($formats as $format) {
                    $nodeName = $format->nodeName;
                    if ($nodeName == 'number:day'){
                        $short = true;
                        if ($format->getAttribute('number:style') == 'long'){
                            $short =false;
                        }
                        if ($short) {
                            $date_format .= '%e';
                        } else {
                            $date_format .= '%d';
                        }
                    } else if ($nodeName == 'number:month'){
                        $short = true;
                        $numeric = true;
                        if ($format->getAttribute('number:style') == 'long'){
                            $short =false;
                        }
                        if ($format->getAttribute('number:textual') == 'true'){
                            $numeric =false;
                        }
                        if ($numeric) {
                            $date_format .= '%m';
                        } else if ($short){
                            $date_format .= '%b';
                        } else {
                            $date_format .= '%B';
                        }
                    } else if ($nodeName == 'number:year'){
                        $short = true;
                        if ($format->getAttribute('number:style') == 'long'){
                            $short =false;
                        }
                        if ($short) {
                            $date_format .= '%y';
                        } else {
                            $date_format .= '%Y';
                        }
                    } else if ($nodeName == 'number:text') {
                        $date_format .= $format->nodeValue;
                    }
                    $nformats[$name]['format'] = $date_format;
                }
            }
        }
        return $nformats;       
    }
    
    /**
     * Gives specific format to a patricular chart data
     * Note: by the time being we only apply it to date formats
     *
     * @param float $value
     * @param string $letter
     * @return array
     * @access private
     */
    private function _format($value, $letter) 
    {
        if($this->_applyFormatting[$letter]['type'] == 'date'
           && is_numeric($value) && $value > 2){
            $format = $this->_applyFormatting[$letter]['format'];
            //Use real locale
            setlocale(LC_ALL, 'en');
            $days = (int) $value - 2;
            $date = new \DateTime('1900-01-01');
            $date->add(new \DateInterval('P' . $days . 'D'));
            return strftime($format, strtotime($date->format('d-M-Y')));
        } else {
            return $value;
        }
    }
    
    /**
     * Extract row and column info
     *
     * @param string $input
     * @return array
     * @access public
     * @static
     */
    public static function extractColumnRow($input) 
    {
        $data = array();
        $blocks = explode(':', $input);
        $dataBlock_1 = explode('.', $blocks[0]);
        if (isset($dataBlock_1[1])) {
            $data_1 = str_replace('$', '', $dataBlock_1[1]);
            $data[0] = preg_replace('/[0-9]+/', '', $data_1);
            $data[1] = preg_replace('/[^0-9]+/', '', $data_1);
        }
        if (isset($blocks[1])) {
            $dataBlock_2 = explode('.', $blocks[1]);
            if (isset($dataBlock_2[1])) {
                $data_2 = str_replace('$', '', $dataBlock_2[1]);
                $data[2] = preg_replace('/[0-9]+/', '', $data_2);
                $data[3] = preg_replace('/[^0-9]+/', '', $data_2);
            }
        }
        return $data;
    }
    

}