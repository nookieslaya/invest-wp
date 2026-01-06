<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$contact = new FieldsBuilder('contact', [
    'label' => 'Contact',
]);

$contact
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
        'rows' => 4,
    ])
        ->setWidth(60)
    ->addText('office_title', [
        'label' => 'Office title',
    ])
        ->setWidth(40)
    ->addTextarea('office_description', [
        'label' => 'Office description',
        'rows' => 3,
    ])
        ->setWidth(60)
    ->addText('form_title', [
        'label' => 'Form title',
    ])
        ->setWidth(40)
    ->addTextarea('form_description', [
        'label' => 'Form description',
        'rows' => 3,
    ])
        ->setWidth(60);

return $contact;
