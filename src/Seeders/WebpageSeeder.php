<?php
namespace Baytek\Laravel\Content\Types\Webpage\Seeders;

use Baytek\Laravel\Content\Seeder;

class WebpageSeeder extends Seeder
{
    private $data = [
        [
            'key' => 'webpage',
            'title' => 'Webpage',
            'content' => 'Webpage Content Type',
            'relations' => [
                ['parent-id', 'content-type']
            ]
        ],
        [
            'key' => 'homepage',
            'title' => 'Homepage',
            'content' => 'This is the basic and required homepage, every site must have an index.',
            'relations' => [
                ['content-type', 'webpage']
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
