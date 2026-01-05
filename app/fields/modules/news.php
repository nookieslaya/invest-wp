<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$news = new FieldsBuilder('news', [
    'label' => 'Aktualnosci',
]);

$news
    ->addText('section_title', [
        'label' => 'Section title',
    ])
        ->setWidth(30)
    ->addText('heading', [
        'label' => 'Heading',
    ])
        ->setWidth(70)
    ->addTextarea('description', [
        'label' => 'Description',
        'rows' => 3,
    ]);

return $news;
