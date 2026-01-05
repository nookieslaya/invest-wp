<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$stackedGallery = new FieldsBuilder('stacked-gallery', [
    'label' => 'Stacked Gallery',
]);

$stackedGallery
    ->addText('section_title', [
        'label' => 'Section title',
    ])
        ->setWidth(30)
    ->addText('heading', [
        'label' => 'Heading',
    ])
        ->setWidth(40)
    ->addTextarea('description', [
        'label' => 'Description',
        'rows' => 2,
    ])
        ->setWidth(30)
    ->addRepeater('items', [
        'label' => 'Images',
        'layout' => 'block',
        'min' => 6,
        'max' => 6,
        'button_label' => 'Add image',
    ])
        ->addImage('image', [
            'label' => 'Image',
            'return_format' => 'array',
            'preview_size' => 'large',
            'required' => 1,
        ])
        ->addText('label', [
            'label' => 'Label (optional)',
        ])
    ->endRepeater();

return $stackedGallery;
