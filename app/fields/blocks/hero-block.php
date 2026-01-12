<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$heroBlock = new FieldsBuilder('hero_strip_block', [
    'title' => 'Hero Strip Block',
]);

$heroBlock->setLocation('block', '==', 'acf/hero-strip');

$heroBlock
    ->addText('title', [
        'label' => 'Title',
    ])
        ->setWidth(100)
    ->addImage('image', [
        'label' => 'Image',
        'return_format' => 'array',
        'preview_size' => 'large',
    ])
        ->setWidth(100);

return $heroBlock;
