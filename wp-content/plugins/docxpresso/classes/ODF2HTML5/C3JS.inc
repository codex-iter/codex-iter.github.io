<?php

/**
 * C3 translator
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




class C3JS
{
    /**
     * Chart axis format
     * 
     * @var array
     * @access private
     */
    private $_axis;
    /**
     * Chart categories
     * 
     * @var array
     * @access private
     */
    private $_categories;
    /**
     * Chart DOMDocument
     * 
     * @var DOMDocument
     * @access private
     */
    private $_chart;
    /**
     * Chart data
     * 
     * @var array
     * @access private
     */
    private $_data;
    /**
     * Chart global type info
     * 
     * @var array
     * @access private
     */
    private $_globalType;
    /**
     * Chart div id
     * 
     * @var string
     * @access private
     */
    private $_id;
    /**
     * Chart legend format
     * 
     * @var array
     * @access private
     */
    private $_legend;
    /**
     * Chart series format
     * 
     * @var array
     * @access private
     */
    private $_series;
    /**
     * Chart title data
     * 
     * @var array
     * @access private
     */
    private $_title;
    
    /**
     * Construct
     *
     * @param array $options
     * @access public
     */
    public function __construct($options)
    {          
        $this->_id = $options['name'];
    }
    
    /**
     * Sets the chart axis format
     *
     * @param array $axis
     * @return void
     * @access public
     */
    public function setAxis($axis) 
    {
        $this->_axis = $axis;  
    }
      
    /**
     * Sets the chart global type
     *
     * @param array $type
     * @return void
     * @access public
     */
    public function setGlobalType($type) 
    {
        $this->_globalType = $type;
    }
    
    /**
     * Sets the chart legend format
     *
     * @param array $legend
     * @return void
     * @access public
     */
    public function setLegend($legend) 
    {
        $this->_legend = $legend;
    }
    
    /**
     * sets the chart raw data
     *
     * @param array $data
     * @return void
     * @access public
     */
    public function setData($data) 
    {
        $this->_data = $data;
    }
    
    /**
     * Sets the chart series format
     *
     * @param array $series
     * @return void
     * @access public
     */
    public function setSeries($series) 
    {
        $this->_series = $series;      
    }
    
    /**
     * Sets the chart title data
     *
     * @array $title
     * @return void
     * @access public
     */
    public function setTitle($title) 
    {
        $this->_title = $title;      
    }
    
    /**
     * Renders the chart data in c3.js compatible format
     *
     * @param DOMDocument $chart
     * @return string
     * @access public
     */
    public function renderScript($chart) 
    {
        
        $this->_chart = $chart;
        $unid = 'chart_' . \uniqid();
        $script = '<script>';
        $script .= 'var ' . $unid . ' = c3.generate({';
        $script .= 'bindto: \'#' . $this->_id .  '\',';
        switch ($this->_globalType['type']) {
            case 'chart:circle':
                $code = $this->_pieChart('pie');
                $script .= $code;
                break;
            case 'chart:ring':
                $code = $this->_pieChart('donut');
                $script .= $code;
                break;
            case 'chart:bar':
                $code = $this->_genericChart('bar');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            case 'chart:area':
                $code = $this->_genericChart('area');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            case 'chart:line':
                $code = $this->_genericChart('line');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            case 'chart:radar':
                //there is currently no specific support for radar charts
                $code = $this->_genericChart('line');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            case 'chart:filled-radar':
                //there is currently no specific support for filled radar charts
                $code = $this->_genericChart('area');
                $script .= $code;
                $axis = $this->_chartAxis();
                $script .= $axis;
                break;
            default:
                $code = $this->_genericChart();
                $script .= $code;
                break;            
        }
        if (!empty($this->_legend['pos'])) {
            $script .= 'legend: {position: \'' . $this->_legend['pos'] . '\'},';
        } else {
            $script .= 'legend: {hide: true},';
        }
        $script .= 'color: {pattern: [' . $this->_getColorPattern() . ']},';
        $script .= '});';
        $script .= '</script>';
        return $script;
    }
    /**
     * Generates the generic chart JSON code
     *
     * @param string $subtype
     * @return string
     * @access private
     */
    private function _genericChart($subtype = 'bar') 
    {
        //we now use the new ODS parser although we keep the the ODT ODS
        //dichotomy for possible future use
        if(Docxpresso\CreateDocument::$docType == 'spreadsheet'){
            $data = $this->_genericChartODS($subtype);
        } else {
            $data = $this->_genericChartODS($subtype);
        }
        return $data;
    }
    /**
     * Generates the generic chart JSON code for ODT files
     *
     * @param string $subtype
     * @return string
     * @access private
     */
    private function _genericChartODT($subtype = 'bar') 
    {      
        $data = 'data: {rows: [';
        //check the categories
        foreach ($this->_axis as $axis){
           if (isset($axis['x']['range'])) {
               $catRange = $axis['x']['range'];
               $catLetterArray = ChartParser::extractColumnRow($catRange);
               if (count($catLetterArray) > 1) {
                $catLetter = $catLetterArray[0];
               }
           } 
        }
        //the real data    
        foreach ($this->_series as $key => $series) {
            $labels = array();
            $categories = array();
            $groups = array();
            foreach ($series as $num => $value){
                $label= $series[$num]['label'];
                $letterArray = ChartParser::extractColumnRow($label);
                $serLetter = $letterArray[0];
                $labels[] = $serLetter;
            }
            if (isset($this->_globalType['percentage'])
            && $this->_globalType['percentage']) {
                //we need to manipulate the data because c3.js does not
                //support natively percentage (stacked) 
                $length = \count($this->_data[$key]);
                for ($j = 2; $j <= $length; $j++) {
                    $subdata = $this->_data[$key][$j];
                    \array_shift($subdata);
                    $subtotal = \array_sum($subdata);
                    if ($subtotal != 0) {
                        foreach ($subdata as $ord => $num) {
                            $base = $subdata[$ord]/$subtotal;
                            $percent = \round(10000 * $base)/100;
                            $this->_data[$key][$j][$ord] = $percent;
                        }    
                    }
                }
            }
                
            foreach ($this->_data[$key] as $row => $cells){
                $data .= '[';
                if ($row > 1 && isset($catLetter) && isset($cells[$catLetter])) {
                    $categories[] =  $cells[$catLetter];
                }
                foreach($labels as $letter){
                    if (\is_numeric($cells[$letter])) {
                        $data .= $cells[$letter] . ',';
                        if ($cells[$letter] < 0) {
                            $this->_globalType['negativeValues'] = true;
                        }
                    } else {
                        $groups[] = '\'' . $cells[$letter] . '\'';
                        $data .= '\'' . $cells[$letter] . '\',';
                    }
                }
                $data .= '],';
            }                
        }    
        $data .= '], ';
        $data .= ' type : \'' . $subtype . '\',';
        $data .= 'order: null,';
        if ((isset($this->_globalType['stacked']) 
            && $this->_globalType['stacked'] === true)
            || (isset($this->_globalType['percentage']) 
            && $this->_globalType['percentage'] === true)) {
            $data .= ' groups: [[';
            $data .= \implode(',', $groups);
            $data .= ']],';
        }
        $data.= '},';
        if (isset($this->_title) && !empty($this->_title['text'])) {
            $data.= 'title: {text: "' . $this->_title['text'] . '"},';
        }
        $this->_categories = $categories;
        return $data;
    }
    
    /**
     * Generates the generic chart JSON code for spreadsheets
     *
     * @param string $subtype
     * @return string
     * @access private
     */
    private function _genericChartODS($subtype = 'bar') 
    {      
        //check the categories
        //generate categories
        $categories = array();
        foreach ($this->_axis as $axis){
            if (isset($axis['x']['range'])) {
                $range = $this->_range($axis['x']['range']);
                //build the categories array
                foreach ($range as $name => $table){
                    foreach ($table as $num => $letter) {
                        foreach($letter as $key => $value){
                            $categories[] = $this->_data[$name][$num][$key];
                        }
                    }
                }
            } 
        }
        //check for subtypes
        //the real data 
        $groups = array();
        $dataholder = array();
        $classArray = array();
        foreach ($this->_series as $key => $series) {
            $classes = array();
            foreach ($series as $num => $value){
                $serRange = $this->_range($series[$num]['range']);
                $prelabel= $series[$num]['label'];
                if (!empty($prelabel)) {
                    $temp = ChartParser::extractColumnRow($prelabel);
                    foreach($serRange as $key => $value){
                        if (count($temp) > 1) {
                            $serRange[$key][$temp[1]][$temp[0]] = true;
                        }
                        //class
                        if (isset($series[$num]['class'])) {
                            $preclass = explode(':', $series[$num]['class']);
                            $class = $preclass[1];
                            if ($class != $subtype && $class == 'line') {
                                $classes[$temp[0]] = 'line';
                            }
                        }
                        uksort($serRange[$key], array($this, '_sortLetters'));
                        $groups[] = $serRange[$key];
                        $singleSeries = false;
                    }
                } else {
                    foreach($serRange as $key => $value){
                        uksort($serRange[$key], array($this, '_sortLetters'));
                        $groups[] = $serRange[$key];
                    }
                    $singleSeries = true;
                }
            }
            if (isset($this->_globalType['percentage'])
            && $this->_globalType['percentage']) {
                //we need to manipulate the data because c3.js does not
                //support natively percentage (stacked) 
                $length = \count($this->_data[$key]);
                for ($j = 2; $j <= $length; $j++) {
                    $subdata = $this->_data[$key][$j];
                    \array_shift($subdata);
                    $subtotal = \array_sum($subdata);
                    if ($subtotal != 0) {
                        foreach ($subdata as $ord => $num) {
                            $base = $subdata[$ord]/$subtotal;
                            $percent = \round(10000 * $base)/100;
                            $this->_data[$key][$j][$ord] = $percent;
                        }    
                    }
                }
            }
            $name = current(array_keys($this->_data));
            foreach ($groups as $group){
                $temp = array();
                foreach ($group as $num => $letters){  
                    uksort($letters, array($this, '_sortLetters'));
                    foreach ($letters as $key => $val){
                        //we need to check that the value exist because we may 
                        //choose an empty cell
                        if (isset($this->_data[$name][$num][$key]) &&
                             \is_numeric($this->_data[$name][$num][$key])) {
                            $dp = (float) $this->_data[$name][$num][$key];
                            $temp[] = $dp;
                            if ($dp < 0) {
                                $this->_globalType['negativeValues'] = true;
                            }
                        } else if (isset($this->_data[$name][$num][$key])) {
                            $temp[] = $this->_data[$name][$num][$key];
                            if (isset($classes[$key])){
                                $classArray[$this->_data[$name][$num][$key]] 
                                    = 'line';
                            }
                        }
                    }
                    
                }
                $dataholder[] = $temp;
            }
            //now we have to transpose dataholder
            array_unshift($dataholder, null);
            $dataholder = call_user_func_array('array_map', $dataholder);
        }
        $data = 'data: {rows: ';
        if ($singleSeries){
            array_unshift($dataholder, ' ');
            $base = array();
            foreach ($dataholder as $value) {
               $base[] = array($value);
            }
            $data .= json_encode($base);
            
        } else if (!is_array($dataholder[0])) {
            $base = array();
            foreach ($dataholder as $value) {
               $base[] = array($value);
            }
            $data .= json_encode($base);
        } else {
            $data .= json_encode($dataholder);
        }
        $data .= ', ';
        $data .= ' type : \'' . $subtype . '\',';
        if (count($classArray)) {
            $data .= 'types: {';
            foreach($classArray as $key => $val){
                $data .= '\'' . $key . '\': ' . '\'' . $val . '\',';
            }
            $data .= '},';
        }
        $data .= 'order: null,';
        if ((isset($this->_globalType['stacked']) 
            && $this->_globalType['stacked'] === true)
            || (isset($this->_globalType['percentage']) 
            && $this->_globalType['percentage'] === true)) {
            $data .= ' groups: [[';
            if (is_array($dataholder[0])){
                foreach($dataholder[0] as $value){
                    $data .= '\'' . $value . '\',';
                }
            } else {
                $data .= '\'' . $dataholder[0] . '\',';
            }
            $data = substr($data, 0, -1);
            $data .= ']],';
        }
        $data.= '},';
        if (isset($this->_title) && !empty($this->_title['text'])) {
            $data.= 'title: {text: "' . $this->_title['text'] . '"},';
        }
        if (count($categories) == 0){
            //there were no categories so we have to number them as Excel does
            $numdata = count($dataholder);
            for ($s = 1; $s < $numdata; $s++){
                $categories[] = $s;
            }
        }
        $this->_categories = $categories;

        return $data;
    }
    
    /**
     * Generates the color pattern for a bar chart
     *
     * @return string
     * @access private
     */
    private function _genericColorPattern() 
    {
        $pattern = array();
        $colors = array();
        $data = \array_keys($this->_data);
        $tableName = $data[0];
        $xpath = new \DOMXPath($this->_chart);
        if (!isset($this->_series[$tableName])) {
            $realName = \array_keys($this->_series);
            $tableName = $realName[0];
        }
        if (isset($this->_series[$tableName])) {
            foreach($this->_series[$tableName] as $ser){
                $style = $ser['style'];
                $query = '//style:style[@style:name="' . $style . '"]';
                $query .= '/style:graphic-properties';
                $nodes = $xpath->query($query);
                if ($nodes->length > 0) {
                    $pointColor = $nodes->item(0)
                                        ->getAttribute('draw:fill-color');
                    $fill = $nodes->item(0)->getAttribute('draw:fill-color');
                    if ($fill == 'none') {
                        $colors[] = '#ffffff';
                    } else if (!empty($pointColor)) {
                        $colors[] = $pointColor;
                    } else {
                        $colors[] = '';   
                    }
                } else {
                    $colors[] = '';
                }
            }
        }
        $numColors = \count($colors);
        $numDefault = \count(ChartParser::$defaultColorPattern);
        $counter = max(array($numColors, $numDefault));
        for ($j = 0; $j < $counter; $j++) {
            if (!empty($colors[$j])){
                $pattern[$j] = '\'' . $colors[$j] . '\'';
            } else if (!empty(ChartParser::$defaultColorPattern)) {
                $pattern[$j] = '\'' . ChartParser::$defaultColorPattern[$j];
                $pattern[$j] .= '\'';
            } else {
                $pattern[$j] = '\'#ffffff\'';
            }
        }
        $colorPattern = \implode(',', $pattern);
        
        return $colorPattern;        
    }
    
    /**
     * Selectes the data point to render
     *
     * @param $input
     * @return array
     * @access private
     */
    private function _range($input) 
    {
        $range = array();
        $nameArray = explode('.', $input);
        $temp = ChartParser::extractColumnRow($input);
        if (count($temp) == 4) {
            $name = $nameArray[0];
            $range[$name] = array();
            $first_number = $temp[1];
            $last_number = $temp[3];
            $first_letter = $temp[0];
            $first_letter_ord = self::letter2number($first_letter);
            $last_letter = $temp[2];
            $last_letter_ord = self::letter2number($last_letter);
            for ($j = $first_number; $j <= $last_number; $j++){
                $range[$name][$j] = array();
                for ($k = $first_letter_ord; $k <= $last_letter_ord; $k++) {
                    $letter = self::number2letter($k);
                    $range[$name][$j][$letter] = true;
                }
            }
        } else if (count($temp) == 2){
            $name = $nameArray[0];
            $range[$name] = array();
            $first_number = $temp[1];
            $first_letter = $temp[0];
            $range[$name][$first_number] = array();
            $range[$name][$first_number][$first_letter] = true;
        }
        return $range;
    }
    
    /**
     * Generates the axis JSON code
     *
     * @return string
     * @access private
     */
    private function _chartAxis() 
    {
        $axis = 'axis: {';
        if (isset($this->_globalType['rotated'])
            && $this->_globalType['rotated'] === true) {
            $axis .= 'rotated: true,';
        } 
        //x-axis
        $axis .= 'x: {';
        if (isset($this->_categories)) {
            $axis .= 'type: \'category\',';
            $axis .= 'categories:[';
            foreach($this->_categories as $cat){
                $axis .= '\'' . str_replace("'", "\'",$cat) . '\',';
            }
            $axis .= '],';
            foreach ($this->_axis as $props) {
                if (isset($props['x']['props']['interval'])
                    && $props['x']['props']['interval'] != 1){
                    $culled = self::culling($this->_categories,
                                          $props['x']['props']['interval']);
                    $axis .= 'tick:{values:[';
                    foreach($culled as $cullcat){
                        $axis .= '\'' . str_replace("'", "\'",$cullcat) . '\',';
                    }
                    $axis .= ']},';
                }
            }
        }
        $axis .= '},';//close x-axis
        //y-axis
        $axis .= 'y: {';
        if (isset($this->_globalType['percentage'])
            && $this->_globalType['percentage']) {
            $axis .= 'max: 95,'; 
            $axis .= 'min: 9,'; 
            $axis .= 'tick: {format: function (d) { return d + "%"; }}';
        } else {
            //check if a maximum or minimun has been set for the chart
            foreach ($this->_axis as $props) {
                if (isset($props['y']['props']['max'])){
                    $axis .= 'max: ' . $props['y']['props']['max'] . ', ';
                } 
                if (isset($props['y']['props']['min'])){
                    $axis .= 'min: ' . $props['y']['props']['min'] . ', ';
                    $axis .= 'padding:{bottom: 0}';
                }
            }
        }
        $axis .= '},';//close y-axis
        $axis .= '},';//close axis
        $axis .= 'grid: {';
        foreach ($this->_axis as $grid) {
            if(isset($grid['x']['grid']['class'])
               && ($grid['x']['grid']['class'] == 'major'
                   || $grid['x']['grid']['class'] == 'minor')){
                $axis .= 'x: {show: true,},';
            }
            if(isset($grid['y']['grid']['class'])
               && ($grid['y']['grid']['class'] == 'major'
                   || $grid['y']['grid']['class'] == 'minor')){
                $axis .= 'y: {show: true,';
                if (isset($this->_globalType['negativeValues']) 
                    && $this->_globalType['negativeValues'] === true) {
                     $axis .= 'lines: [{value:0}]';
                 }
                $axis .= '},';
            }
        }
        $axis .= '},';//close grid
        return $axis;
    }
    
    /**
     * Generates the color pattern for a generic type chart
     *
     * @return string
     * @access private
     */
    private function _getColorPattern() 
    {
        switch ($this->_globalType['type']) {
            case 'chart:circle':
                $pattern = $this->_pieColorPattern();
                break;
            default:
                $pattern = $this->_genericColorPattern();
                break;            
        }
        return $pattern;
    }
    
    /**
     * Generates the specific pie chart JSON code
     *
     * @param string $subtype
     * @return string
     * @access private
     */
    private function _pieChart($subtype = 'pie') 
    {
        $data = 'data: {columns: [';
        //check the categories
        foreach ($this->_axis as $axis){
            if (isset($axis['x']['range'])) {
                $catRange = $this->_range($axis['x']['range']);
                //build the labels array
                $labels = array();
                foreach ($catRange as $name => $table){
                    foreach ($table as $num => $letter) {
                        foreach($letter as $key => $value){
                            $labels[] = $this->_data[$name][$num][$key];
                        }
                    }
                }
            } 
        }
        //the real data    
        foreach ($this->_series as $key => $series) {
            //for pie chart we should only take into account the first series
            foreach ($series as $num => $value){
                if ($num == 0) {
                    $serRange = $this->_range($series[0]['range']);
                    $dataValues = array();
                    foreach ($serRange as $name => $table){
                        foreach ($table as $num => $letter) {
                            foreach($letter as $key => $value){
                                $dataValues[] = $this->_data[$name][$num][$key];
                            }
                        }
                    }
                }
            }
        }   
        $l = count($labels);
        for ($n = 0; $n < $l; $n++){
            $data .= '[';
            $data .= '"' . $labels[$n] . '",';
            $data .= $dataValues[$n] . ',';
            $data .= '],';
        }
        $data .= '], ';
        $data .= ' type : \'' . $subtype . '\',';
        $data .= 'order: null,},';
        if (isset($this->_title) && !empty($this->_title['text'])) {
            $data.= 'title: {text: "' . $this->_title['text'] . '"},';
        }
        return $data;
    }
    
    /**
     * Generates the color pattern for a pie chart
     *
     * @return string
     * @access private
     */
    private function _pieColorPattern() 
    {
        $pattern = array();
        $colors = array();
        $data = \array_keys($this->_data);
        $tableName = $data[0];
        $dataPoints = $this->_series[$tableName][0]['data-points'];
        $xpath = new \DOMXPath($this->_chart);
        foreach ($dataPoints as $data) {
            $query = '//style:style[@style:name="' . $data . '"]';
            $query .= '/style:graphic-properties';
            $nodes = $xpath->query($query);
            if ($nodes->length > 0) {
                $pointColor = $nodes->item(0)->getAttribute('draw:fill-color');
                $fill = $nodes->item(0)->getAttribute('draw:fill-color');
                if ($fill == 'none') {
                    $colors[] = '#ffffff';
                } else if (!empty($pointColor)) {
                    $colors[] = $pointColor;
                } else {
                    $colors[] = '';   
                }
            } else {
                $colors[] = '';
            }
        }
        $numColors = \count($colors);
        $numDefault = \count(ChartParser::$defaultColorPattern);
        $counter = max(array($numColors, $numDefault));
        for ($j = 0; $j < $counter; $j++) {
            if (!empty($colors[$j])){
                $pattern[$j] = '\'' . $colors[$j] . '\'';
            } else if (!empty(ChartParser::$defaultColorPattern)) {
                $pattern[$j] = '\'' . ChartParser::$defaultColorPattern[$j];
                $pattern[$j] .= '\'';
            } else {
                $pattern[$j] = '\'#ffffff\'';
            }
        }
        $colorPattern = \implode(',', $pattern);
        
        return $colorPattern;        
    }
    
    /**
     * rorder letters according to: A-Z AA-AZ BA-BZ ...)
     *
     * @param string $a
     * @param string $b
     * @return	integer
     * @access	private
     */

    private function _sortLetters($a, $b)
    {
      $anum = self::letter2number($a);
      $bnum = self::letter2number($b);
      if ($bnum < $anum) {
          return 1;
      } else {
          return -1;
      }
    }
    
    /**
     * returns a culled array
     *
     * @param array $data
     * @param integer $factor
     * @return	array
     * @access	public
     * @static
     */

    public static function culling($data, $factor)
    {
        $new = array();
        $length = count($data);
        for ($j = 0; $j < $length; $j++) {
            if($j%$factor == 0){
                $new[] = $data[$j];
            }
        }
        return $new;
    }
    
    /**
     * returns the letter corresponding to a number: A-Z AA-AZ BA-BZ ...)
     *
     * @param int $j
     * @return	string
     * @access	public
     * @static
     */

    public static function number2letter($j)
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
     * returns the number corresponding to letters: A-Z AA-AZ BA-BZ ...)
     *
     * @param string $str
     * @return	integer
     * @access	public
     * @static
     */

    public static function letter2number($str)
    {
        $number = 0;
        $upstr = \strtoupper($str);
        $chars = \str_split($upstr);
        $count = 0;
        foreach ($chars as $char) {
            $number += (ord($char) - 64) + $count * 25;
            $count++;
        }
        return $number;
    }

}