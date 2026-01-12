<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

if (! function_exists('acf_add_local_field_group')) {
    return null;
}

if (! class_exists(FieldsBuilder::class)) {
    return null;
}

$blockFields = [
    'blocks.hero-block',
    'blocks.cta-block',
    'blocks.testimonials-block',
];

foreach ($blockFields as $blockField) {
    $fields = get_field_partial($blockField);

    if ($fields instanceof FieldsBuilder) {
        acf_add_local_field_group($fields->build());
    }
}

return null;
