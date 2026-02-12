<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$shopHero = new FieldsBuilder('shop-hero', [
    'label' => 'Shop Hero',
]);

$shopHero
    ->addText('eyebrow', [
        'label' => 'Eyebrow',
    ])
    ->addText('title', [
        'label' => 'Title',
    ])
    ->addTextarea('text', [
        'label' => 'Text',
        'rows' => 3,
    ])
    ->addLink('button', [
        'label' => 'Button',
        'return_format' => 'array',
    ])
    ->addImage('image', [
        'label' => 'Image',
        'return_format' => 'array',
        'preview_size' => 'medium',
    ]);

return $shopHero;
