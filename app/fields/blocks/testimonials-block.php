<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$testimonialsBlock = new FieldsBuilder('testimonials_block', [
    'title' => 'Testimonials Block',
]);

$testimonialsBlock->setLocation('block', '==', 'acf/testimonials');

$testimonialsBlock
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

return $testimonialsBlock;
