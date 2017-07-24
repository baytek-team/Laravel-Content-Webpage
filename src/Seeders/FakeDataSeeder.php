<?php
namespace Baytek\Laravel\Content\Types\Webpage\Seeders;

use Baytek\Laravel\Content\Seeder;
use Baytek\Laravel\Content\Types\Webpage\Webpage;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generateAboutPages();
        $this->generateMemberPages();
    }

    protected function generateAboutPages($total = 30)
    {
    	//Generate webpages
    	$content_type = content('content-type/webpage', false);
    	$webpage_ids = collect([]);

 		//Generate the top level webpage
 		$about = new Webpage(
 			[
 				'title' => 'About',
 				'key' => 'about',
 				'content' => '',
 				'status' => Webpage::APPROVED,
 				'language' => \App::getLocale(),
 			]
 		);
 		$about->save();
 		$about->saveRelation('content-type', $content_type);
 		$about->saveRelation('parent-id', $content_type);
		$about->saveMetadata('author_id', 1);

		$webpage_ids->push($about->id);

    	foreach(range(1,$total) as $index) {
    		//Choose a parent at random
			$parent_id = $webpage_ids->random();

    		$webpage = (factory(Webpage::class)->make());
    		$webpage->save();

    		//Add relationships
    		$webpage->saveRelation('content-type', $content_type);
    		$webpage->saveRelation('parent-id', $parent_id);

    		//Add metadata
    		$webpage->saveMetadata('author_id', 1);
    		$webpage->saveMetadata('path', $this->buildPathFromParents($webpage));

    		//Add some external links
    		if (!rand(0,4)) {
    			$webpage->saveMetadata('external_url', 'http://www.google.ca');
    		}
    		else {
    			//Add to collection, only if it doesn't have an external link
    			$webpage_ids->push($webpage->id);
    		}
    	}
    }

    protected function generateMemberPages()
    {
    	$content_type = content('content-type/webpage', false);
    	
    	//Generate the member webpages
    	$members = new Webpage(
 			[
 				'title' => 'Members',
 				'key' => 'members',
 				'content' => '',
 				'status' => Webpage::APPROVED,
 				'language' => \App::getLocale(),
 			]
 		);
 		$members->save();
 		$members->saveRelation('content-type', $content_type);
 		$members->saveRelation('parent-id', $content_type);
		$members->saveMetadata('author_id', 1);
		$members->saveMetadata('path', $this->buildPathFromParents($members));

		$directory = new Webpage(
 			[
 				'title' => 'Directory',
 				'key' => 'directory',
 				'content' => '',
 				'status' => Webpage::APPROVED,
 				'language' => \App::getLocale(),
 			]
 		);
		$directory->save();
 		$directory->saveRelation('content-type', $content_type);
 		$directory->saveRelation('parent-id', $members->id);
		$directory->saveMetadata('author_id', 1);
		$directory->saveMetadata('path', $this->buildPathFromParents($directory));

		$rules = new Webpage(
 			[
 				'title' => 'Member Rules',
 				'key' => 'member-rules',
 				'content' => '<p>Here are the member rules:</p><ul><li>Rule 1</li><li>Rule 2</li></ul>',
 				'status' => Webpage::APPROVED,
 				'language' => \App::getLocale(),
 			]
 		);
 		$rules->save();
 		$rules->saveRelation('content-type', $content_type);
 		$rules->saveRelation('parent-id', $members->id);
		$rules->saveMetadata('author_id', 1);
		$rules->saveMetadata('path', $this->buildPathFromParents($rules));
    }

    protected function buildPathFromParents($page)
    {
        $parents = $page->getParents();
        $path = '';

        for ($i = count($parents) - 1; $i >= 0; $i--) {
            if ($parents[$i]->key != 'webpage') {
                $path = '/'.$parents[$i]->key.$path;
            }
            else {
                break;
            }
        }

        return $path;
    }
}
