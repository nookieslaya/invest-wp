<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$investment = new FieldsBuilder('investment_details', ['title' => 'Inwestycja']);

$investment->setLocation('post_type', '==', 'investment');

$investment
    ->addText('investment_subtitle', [
        'label' => 'Podtytul',
    ])
    ->addTextarea('investment_intro', [
        'label' => 'Opis',
        'rows' => 3,
    ])
    ->addRepeater('buildings', [
        'label' => 'Budynki',
        'layout' => 'block',
        'button_label' => 'Dodaj budynek',
    ])
        ->addText('building_name', [
            'label' => 'Nazwa budynku',
        ])
        ->addRepeater('floors', [
            'label' => 'Pietra',
            'layout' => 'block',
            'button_label' => 'Dodaj pietro',
        ])
            ->addText('floor_label', [
                'label' => 'Nazwa pietra',
            ])
            ->addSelect('floor_view', [
                'label' => 'Widok pietra',
                'choices' => [
                    'plan' => 'Rzut (SVG)',
                    'table' => 'Tabela mieszkan',
                ],
                'default_value' => 'plan',
            ])
            ->addImage('floor_image', [
                'label' => 'Rzut pietra (obraz)',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ])
            ->addFile('floor_svg_file', [
                'label' => 'Plik SVG (mapa mieszkan)',
                'return_format' => 'array',
                'mime_types' => 'svg',
            ])
            ->addTextarea('floor_svg', [
                'label' => 'SVG mapy mieszkan',
                'rows' => 8,
                'instructions' => 'Wklej SVG z obszarami oznaczonymi data-apartment-id. Pole opcjonalne, mozesz tez wybrac plik SVG powyzej.',
            ])
            ->addRepeater('apartments', [
                'label' => 'Mieszkania',
                'layout' => 'block',
                'button_label' => 'Dodaj mieszkanie',
            ])
                ->addTab('Podstawowe', [
                    'placement' => 'left',
                ])
                ->addText('apartment_label', [
                    'label' => 'Nazwa mieszkania',
                ])
                ->addText('apartment_area', [
                    'label' => 'Metraz',
                ])
                ->addText('apartment_rooms', [
                    'label' => 'Pokoje',
                ])
                ->addSelect('apartment_status', [
                    'label' => 'Status',
                    'choices' => [
                        'available' => 'Wolne',
                        'reserved' => 'Rezerwacja',
                        'sold' => 'Sprzedane',
                    ],
                ])
                ->addText('apartment_svg_id', [
                    'label' => 'ID z SVG',
                    'instructions' => 'Musi pasowac do data-apartment-id w SVG.',
                ])
                ->addTab('Ceny i promocja', [
                    'placement' => 'left',
                ])
                ->addText('apartment_price', [
                    'label' => 'Cena',
                ])
                ->addNumber('apartment_price_current', [
                    'label' => 'Cena aktualna (brutto)',
                    'instructions' => 'Wpisz tylko liczbe, np. 450000.',
                    'prepend' => 'PLN',
                ])
                ->addTrueFalse('apartment_is_promo', [
                    'label' => 'Promocja',
                    'ui' => 1,
                ])
                ->addText('apartment_promo_label', [
                    'label' => 'Opis promocji',
                    'instructions' => 'Np. -50000 zl',
                ])
                ->addNumber('apartment_price_lowest_30', [
                    'label' => 'Najnisza cena z 30 dni',
                    'instructions' => 'Wpisz tylko liczbe, np. 470000.',
                    'prepend' => 'PLN',
                ])
                ->addRepeater('apartment_price_history', [
                    'label' => 'Historia cen',
                    'layout' => 'table',
                    'button_label' => 'Dodaj wpis',
                ])
                    ->addDatePicker('history_date', [
                        'label' => 'Data',
                        'display_format' => 'd.m.Y',
                        'return_format' => 'Y-m-d',
                    ])
                    ->addNumber('history_price', [
                        'label' => 'Cena',
                        'prepend' => 'PLN',
                    ])
                ->endRepeater()
                ->addTab('Koszty dodatkowe', [
                    'placement' => 'left',
                ])
                ->addRepeater('apartment_extra_costs', [
                    'label' => 'Koszty dodatkowe',
                    'layout' => 'table',
                    'button_label' => 'Dodaj koszt',
                ])
                    ->addText('extra_label', [
                        'label' => 'Nazwa',
                    ])
                    ->addNumber('extra_price', [
                        'label' => 'Cena',
                        'prepend' => 'PLN',
                    ])
                ->endRepeater()
                ->addTab('Mieszkanie', [
                    'placement' => 'left',
                ])
                ->addImage('apartment_floorplan', [
                    'label' => 'Rzut mieszkania',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                ])
                ->addRepeater('apartment_room_list', [
                    'label' => 'Pomieszczenia',
                    'layout' => 'table',
                    'button_label' => 'Dodaj pomieszczenie',
                ])
                    ->addText('room_name', [
                        'label' => 'Pomieszczenie',
                    ])
                    ->addNumber('room_area', [
                        'label' => 'Metraz',
                        'append' => 'm2',
                    ])
                ->endRepeater()
                ->addFile('apartment_card_file', [
                    'label' => 'Karta lokalu (PDF)',
                    'return_format' => 'array',
                    'mime_types' => 'pdf',
                ])
            ->endRepeater()
        ->endRepeater()
    ->endRepeater();

return $investment;
