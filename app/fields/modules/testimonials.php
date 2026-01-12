<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$testimonials = new FieldsBuilder('testimonials', [
    'label' => 'Testimonials',
]);

$testimonials
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
    ->addRepeater('items', [
        'label' => 'Testimonials',
        'layout' => 'block',
        'min' => 1,
        'button_label' => 'Add testimonial',
    ])
        ->addTextarea('quote', [
            'label' => 'Quote',
            'rows' => 4,
        ])
        ->addText('name', [
            'label' => 'Name',
        ])
        ->addText('role', [
            'label' => 'Role / Company',
        ])
        ->addImage('image', [
            'label' => 'Photo',
            'return_format' => 'array',
            'preview_size' => 'thumbnail',
        ])
    ->endRepeater();

return $testimonials;
