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
        $this->generateContactPages();
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
    		if (!rand(0,5)) {
                $webpage->title = 'External Link to Google';
                $webpage->save();
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

    protected function generateContactPages()
    {
        $content_type = content('content-type/webpage', false);
        
        //Generate the member webpages
        $contact = new Webpage(
            [
                'title' => 'Contact',
                'key' => 'contact',
                'content' => '',
                'status' => Webpage::APPROVED,
                'language' => \App::getLocale(),
            ]
        );
        $contact->save();
        $contact->saveRelation('content-type', $content_type);
        $contact->saveRelation('parent-id', $content_type);
        $contact->saveMetadata('author_id', 1);
        $contact->saveMetadata('path', $this->buildPathFromParents($contact));

        $general = new Webpage(
            [
                'title' => 'General',
                'key' => 'general',
                'content' => '<h2>Address</h2><p>250 City Centre, Suite 801<br/>Ottawa, Ontario</br>K1R 6K7</p>',
                'status' => Webpage::APPROVED,
                'language' => \App::getLocale(),
            ]
        );
        $general->save();
        $general->saveRelation('content-type', $content_type);
        $general->saveRelation('parent-id', $contact->id);
        $general->saveMetadata('author_id', 1);
        $general->saveMetadata('path', $this->buildPathFromParents($general));

        $feedback = new Webpage(
            [
                'title' => 'Website Feedback',
                'key' => 'website-feedback',
                'content' => '<p>Use the form below to provide feedback to our website support team.',
                'status' => Webpage::APPROVED,
                'language' => \App::getLocale(),
            ]
        );
        $feedback->save();
        $feedback->saveRelation('content-type', $content_type);
        $feedback->saveRelation('parent-id', $contact->id);
        $feedback->saveMetadata('author_id', 1);
        $feedback->saveMetadata('path', $this->buildPathFromParents($feedback));
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
