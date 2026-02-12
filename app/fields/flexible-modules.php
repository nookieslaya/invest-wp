<?php

namespace App;

use StoutLogic\AcfBuilder\FieldsBuilder;

$modules = new FieldsBuilder('flexible_modules', [
    'title' => 'Flexible Modules',
    'hide_on_screen' => ['the_content'],
]);

$modules->setLocation('post_type', '==', 'page');

$flexibleContent = $modules->addFlexibleContent('flexible_modules', [
    'label' => 'Flexible Modules',
    'button_label' => 'Add Module',
]);

// Add module layouts here as you create them.
if ($hero = get_field_partial('modules.hero')) {
    $flexibleContent->addLayout($hero);
}
if ($shopHero = get_field_partial('modules.shop-hero')) {
    $flexibleContent->addLayout($shopHero);
}
if ($investmentHero = get_field_partial('modules.investment-hero')) {
    $flexibleContent->addLayout($investmentHero);
}
if ($investmentGallery = get_field_partial('modules.investment-gallery')) {
    $flexibleContent->addLayout($investmentGallery);
}
if ($stackedGallery = get_field_partial('modules.stacked-gallery')) {
    $flexibleContent->addLayout($stackedGallery);
}
if ($investmentFloorsMap = get_field_partial('modules.investment-floors-map')) {
    $flexibleContent->addLayout($investmentFloorsMap);
}
if ($aboutUs = get_field_partial('modules.about-us')) {
    $flexibleContent->addLayout($aboutUs);
}
if ($investmentsMap = get_field_partial('modules.investments-map')) {
    $flexibleContent->addLayout($investmentsMap);
}
if ($news = get_field_partial('modules.news')) {
    $flexibleContent->addLayout($news);
}
if ($testimonials = get_field_partial('modules.testimonials')) {
    $flexibleContent->addLayout($testimonials);
}
if ($contact = get_field_partial('modules.contact')) {
    $flexibleContent->addLayout($contact);
}
if ($example = get_field_partial('modules.example')) {
    $flexibleContent->addLayout($example);
}

return $modules;
