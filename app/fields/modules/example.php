<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$example = new FieldsBuilder('example', [
    'label' => 'Example Module',
]);

$example
    ->addText('example_heading', [
        'label' => 'Heading',
        'instructions' => 'Short title for the module.',
    ])
    ->addTextarea('example_text', [
        'label' => 'Text',
        'instructions' => 'Main description text.',
        'rows' => 4,
    ])
    ->addLink('example_link', [
        'label' => 'Link',
        'instructions' => 'Optional button link.',
        'return_format' => 'array',
    ]);

return $example;
