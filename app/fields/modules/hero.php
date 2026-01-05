<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$hero = new FieldsBuilder('hero', [
    'label' => 'Hero Slider',
]);

$hero
    ->addRepeater('slides', [
        'label' => 'Slides',
        'min' => 1,
        'layout' => 'block',
        'button_label' => 'Add slide',
    ])
        ->addImage('image', [
            'label' => 'Image',
            'return_format' => 'array',
            'preview_size' => 'small',
            'required' => 1,
        ])
            ->setWidth(30)
        ->addText('heading', [
            'label' => 'Heading',
        ])
            ->setWidth(30)
        ->addTextarea('text', [
            'label' => 'Text',
            'rows' => 3,
        ])
            ->setWidth(40)
        ->addLink('button', [
            'label' => 'Button',
            'return_format' => 'array',
        ])
    ->endRepeater();

return $hero;
