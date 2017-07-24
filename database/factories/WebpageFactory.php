<?php

/**
 * Webpages
 */
$factory->define(Baytek\Laravel\Content\Types\Webpage\Webpage::class, function (Faker\Generator $faker) {

    $title = ucwords(implode(' ', $faker->unique()->words(rand(1,5))));

    return [
        'key' => str_slug($title),
        'title' => $title,
        'content' => implode('<br/><br/>', $faker->paragraphs(rand(0, 2))),
        'status' => App\ContentTypes\Resources\Models\File::APPROVED,
        'language' => App::getLocale(),
    ];
});
