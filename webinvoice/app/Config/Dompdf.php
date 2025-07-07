<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Dompdf extends BaseConfig
{
    /**
     * File options
     */
    public $filename = '';

    /**
     * Paper options
     */
    public $paper = 'A4';
    public $orientation = 'portrait';

    /**
     * Font options
     */
    public $defaultFont = 'sans-serif';
    
    /**
     * Options
     */
    public $options = [
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'isPhpEnabled' => true,
        'isJavascriptEnabled' => false,
        'dpi' => 96,
        'defaultMediaType' => 'screen',
        'isFontSubsettingEnabled' => true,
        'debugKeepTemp' => false,
        'debugCss' => false,
        'debugLayout' => false,
        'debugLayoutLines' => false,
        'debugLayoutBlocks' => false,
        'debugLayoutInline' => false,
        'debugLayoutPaddingBox' => false,
    ];
} 