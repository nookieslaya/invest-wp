<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$investmentFloorsMap = new FieldsBuilder('investment-floors-map', [
    'label' => 'Investment Floors Map',
]);

$investmentFloorsMap
    ->addText('section_title', [
        'label' => 'Section title',
    ])
        ->setWidth(30)
    ->addImage('base_image', [
        'label' => 'Base image (building)',
        'return_format' => 'array',
        'preview_size' => 'large',
        'required' => 1,
    ])
        ->setWidth(40)
    ->addLink('investment_link', [
        'label' => 'Investment link',
        'return_format' => 'array',
        'instructions' => 'Link to the investment page where floorplan is shown.',
    ])
        ->setWidth(40)
    ->addNumber('building_index', [
        'label' => 'Building index',
        'instructions' => '0 = first building from investment floors.',
        'default_value' => 0,
        'min' => 0,
        'step' => 1,
    ])
        ->setWidth(20)
    ->addFile('floors_svg_file', [
        'label' => 'Floors SVG file',
        'return_format' => 'array',
        'mime_types' => 'svg',
        'instructions' => 'SVG must include elements with data-floor-index (0-based) or data-floor-target (building-X-floor-Y).',
    ])
        ->setWidth(40)
    ->addTextarea('floors_svg', [
        'label' => 'Floors SVG markup',
        'rows' => 8,
        'instructions' => 'Paste SVG markup with data-floor-index (0-based) or data-floor-target (building-X-floor-Y).',
    ])
        ->setWidth(60);

return $investmentFloorsMap;
