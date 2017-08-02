<?php
namespace Baytek\Laravel\Content\Types\Webpage\Seeders;

use Baytek\Laravel\Content\Seeder;

class WebpageSeeder extends Seeder
{
    private $data = [
        [
            'key' => 'webpage',
            'title' => 'Webpage',
            'content' => \Baytek\Laravel\Content\Types\Webpage\Webpage::class,
            'relations' => [
                ['parent-id', 'content-type']
            ]
        ],
        [
            'key' => 'homepage',
            'title' => 'Homepage',
            'content' => 'This is the basic and required homepage, every site must have an index.',
            'relations' => [
                ['content-type', 'webpage'],
                ['parent-id', 'webpage'],
            ]
        ],
        [
            'key' => 'webpage-menu',
            'title' => 'Webpage Navigation Menu',
            'content' => '',
            'relations' => [
                ['content-type', 'menu'],
                ['parent-id', 'admin-menu'],
            ]
        ],
        [
            'key' => 'webpage-index',
            'title' => 'Webpages',
            'content' => 'webpage.index',
            'meta' => [
                'type' => 'route',
                'class' => 'item',
                'append' => '</span>',
                'prepend' => '<i class="globe left icon"></i><span class="collapseable-text">',
            ],
            'relations' => [
                ['content-type', 'menu-item'],
                ['parent-id', 'webpage-menu'],
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedStructure($this->data);
    }
}
