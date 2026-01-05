<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$investmentGallery = new FieldsBuilder('investment-gallery', [
    'label' => 'Investment Gallery',
]);

$investmentGallery
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
        'rows' => 3,
    ])
        ->setWidth(30)
    ->addGroup('featured', [
        'label' => 'Left image',
    ])
        ->addImage('image', [
            'label' => 'Image',
            'return_format' => 'array',
            'preview_size' => 'large',
            'required' => 1,
        ])
        ->addText('heading', [
            'label' => 'Heading',
        ])
        ->addTextarea('description', [
            'label' => 'Description',
            'rows' => 2,
        ])
        ->addColorPicker('accent_color', [
            'label' => 'Accent color',
            'default_value' => '#d6dbe2',
        ])
    ->endGroup()
    ->addRepeater('items', [
        'label' => 'Right images',
        'min' => 2,
        'max' => 2,
        'layout' => 'block',
        'button_label' => 'Add image',
    ])
        ->addImage('image', [
            'label' => 'Image',
            'return_format' => 'array',
            'preview_size' => 'large',
            'required' => 1,
        ])
        ->addText('heading', [
            'label' => 'Heading',
        ])
        ->addTextarea('description', [
            'label' => 'Description',
            'rows' => 2,
        ])
        ->addColorPicker('accent_color', [
            'label' => 'Accent color',
            'default_value' => '#cfd6d1',
        ])
    ->endRepeater();

return $investmentGallery;
