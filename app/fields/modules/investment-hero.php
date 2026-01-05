<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$investmentHero = new FieldsBuilder('investment-hero', [
    'label' => 'Investment Hero',
]);

$investmentHero
    ->addImage('logo', [
        'label' => 'Logo',
        'return_format' => 'array',
        'preview_size' => 'medium',
    ])
        ->setWidth(25)
    ->addText('stage', [
        'label' => 'Stage',
    ])
        ->setWidth(25)
    ->addTextarea('heading_left', [
        'label' => 'Heading left',
        'rows' => 2,
        'instructions' => 'Use <br> for line breaks.',
    ])
        ->setWidth(25)
    ->addTextarea('heading_right', [
        'label' => 'Heading right',
        'rows' => 2,
        'instructions' => 'Use <br> for line breaks.',
    ])
        ->setWidth(25)
    ->addImage('background_desktop', [
        'label' => 'Background desktop',
        'return_format' => 'array',
        'preview_size' => 'large',
        'required' => 1,
    ])
        ->setWidth(50)
    ->addImage('background_mobile', [
        'label' => 'Background mobile',
        'return_format' => 'array',
        'preview_size' => 'large',
    ])
        ->setWidth(50)
    ->addText('caption_location', [
        'label' => 'Caption location',
    ])
        ->setWidth(50)
    ->addText('caption_name', [
        'label' => 'Caption name',
    ])
        ->setWidth(50);

return $investmentHero;
