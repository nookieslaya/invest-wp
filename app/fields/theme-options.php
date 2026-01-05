<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$options = new FieldsBuilder('theme_options', ['title' => 'General Settings']);

$options->setLocation('options_page', '==', 'theme-general-settings');

$options
    ->addTab('Contact', ['placement' => 'left'])
    ->addText('phone_number', ['label' => 'Phone number'])
    ->addText('contact_form_email', ['label' => 'Contact form email'])
    ->addText('twitter', ['label' => 'Twitter'])
    ->addText('linkedin', ['label' => 'LinkedIn'])
    ->addText('instagram', ['label' => 'Instagram']);

$options
    ->addTab('Footer', ['placement' => 'left'])
    ->addImage('footer_logo', [
        'label' => 'Footer logo',
        'return_format' => 'array',
        'preview_size' => 'medium',
        'instructions' => 'Optional. If empty, site logo will be used.',
    ])
    ->addTextarea('footer_address', [
        'label' => 'Company address',
        'rows' => 4,
    ])
    ->addTextarea('footer_hours', [
        'label' => 'Working hours',
        'rows' => 4,
    ])
    ->addText('footer_menu_title', [
        'label' => 'Footer menu title',
    ])
    ->addText('footer_copyright', [
        'label' => 'Copyright text',
        'instructions' => 'Example: © 2025 Investment. All rights reserved.',
    ]);

$options
    ->addTab('Mapa inwestycji', ['placement' => 'left'])
    ->addText('google_maps_api_key', [
        'label' => 'Google Maps API key',
        'instructions' => 'Add the browser API key.',
    ])
    ->addRepeater('investments', [
        'label' => 'Investments',
        'layout' => 'block',
        'button_label' => 'Add investment',
    ])
        ->addText('name', [
            'label' => 'Name',
        ])
            ->setWidth(50)
        ->addSelect('status', [
            'label' => 'Status',
            'choices' => [
                'w_sprzedazy' => 'W sprzedazy',
                'zrealizowane' => 'Zrealizowane',
                'w_przygotowaniu' => 'W przygotowaniu',
            ],
        ])
            ->setWidth(50)
        ->addImage('image', [
            'label' => 'Image',
            'return_format' => 'array',
            'preview_size' => 'thumbnail',
        ])
            ->setWidth(30)
        ->addText('lat', [
            'label' => 'Latitude',
        ])
            ->setWidth(35)
        ->addText('lng', [
            'label' => 'Longitude',
        ])
            ->setWidth(35)
        ->addLink('link', [
            'label' => 'Link',
            'return_format' => 'array',
        ])
    ->endRepeater();

return $options;
