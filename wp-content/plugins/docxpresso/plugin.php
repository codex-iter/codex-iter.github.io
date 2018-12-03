<?php

/*
  Plugin Name: Docxpresso
  Plugin URI: http://www.docxpresso.com/plugins/wp-docxpresso
  Description: Insert content coming from an Office file (.odt or .ods) with all kinds of elements: complex formatting, charts, sortable tables, youtube videos, Google docs, etcetera.
  Version: 2.2
  Author: No-nonsense Labs
  Author URI: http://www.docxpresso.com
 */

if (is_admin()) {
    include dirname(__FILE__) . '/admin.php';
}

function docxpresso_call($attrs, $content = null) {
    $options = get_option('docxpresso', array());
    $buffer = '';
    if (isset($attrs['file'])) {
        if (isset($attrs['comments'])) {
            $comments = $attrs['comments'];
        } else {
            $comments = false;
        }
        if (isset($attrs['svg']) && $attrs['svg'] == 'false') {
            $SVG = false;
        } else {
            $SVG = true;
        }
        if (isset($attrs['remote']) && $attrs['remote'] == 'true') {
            $remote = true;
        } else if (isset($attrs['remote'])) {
            $remote = false;
	    } else {
            if (isset($options['remote']) && $options['remote']){
                $remote = true;
            } else {
                $remote = false;
            }
        }
        if (isset($attrs['sortTables'])) {
            $sortTables = $attrs['sortTables'];
        } else {
            $sortTables = true;
        }
        if (isset($attrs['sortNumberFormat'])) {
            $sortNumberFormat = $attrs['sortNumberFormat'];
        } else if (isset($options['sortNumberFormat'])){
            $sortNumberFormat = $options['sortNumberFormat'];
        } else {
            $sortNumberFormat = ',.';
        }
       $file = strip_tags($attrs['file']);
        if ($remote) {
            $unid = uniqid() . rand (999, 9999);
            $newfile = '/tmp/tmp_doc' . $unid . '.odt';
            $fullPath = dirname(__FILE__) . $newfile;
            copy($file, $fullPath);
        } else {
            $path = parse_url($file, PHP_URL_PATH);
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
            if (!file_exists($fullPath)) {
                //this is a hack for servers that do not return the correct
                //$_SERVER['DOCUMENT_ROOT'] value
                $tempArray = explode('wp-content', $path);
                $fullPath = getcwd() . '/wp-content' . $tempArray[1];
            }
        }
        
        require_once 'CreateDocument.inc';

        $doc = new Docxpresso\createDocument(array('template' => $fullPath));
        $html = $doc->ODF2HTML5('test.html', array('format' => 'single-file', 
                                                   'download' => true, 
                                                   'parseLayout' => false, 
                                                   'parseComments' => $comments,
                                                   'parseSVG' => $SVG,
                                                   'sortNumberFormat' => $sortNumberFormat,
                                                   'sortTables' => $sortTables));
        $buffer = $html;
        unset($doc);
        if ($remote) {
            unlink($fullPath);
        }
    }
    return $buffer;
}

add_shortcode('docxpresso', 'docxpresso_call');

