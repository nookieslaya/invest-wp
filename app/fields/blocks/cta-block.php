<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$ctaBlock = new FieldsBuilder('cta_block', [
    'title' => 'CTA Block',
]);

$ctaBlock->setLocation('block', '==', 'acf/cta-strip');

$ctaBlock
    ->addText('section_title', [
        'label' => 'Section title',
    ])
        ->setWidth(100)
    ->addText('heading', [
        'label' => 'Heading',
    ])
        ->setWidth(100)
    ->addTextarea('description', [
        'label' => 'Description',
        'rows' => 3,
    ])
        ->setWidth(100)
    ->addLink('primary_link', [
        'label' => 'Primary button',
        'return_format' => 'array',
    ])
        ->setWidth(50)
    ->addLink('secondary_link', [
        'label' => 'Secondary link',
        'return_format' => 'array',
    ])
        ->setWidth(50)
    ->addImage('image', [
        'label' => 'Image',
        'return_format' => 'array',
        'preview_size' => 'large',
    ])
        ->setWidth(100);

return $ctaBlock;
