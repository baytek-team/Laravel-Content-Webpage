<?php

namespace Baytek\Laravel\Content\Types\Webpage\Controllers\Api;

use Baytek\Laravel\Content\Types\Webpage\Webpage;
use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Controllers\ApiController;
use Baytek\Laravel\Content\Models\Scopes\TranslationScope;
use Baytek\Laravel\Users\User;
use Illuminate\Http\Request;

use Auth;

class WebpageController extends ApiController
{
    public function index()
    {
    	return Webpage::all();
    }

    public function categories($category)
    {
    	// $page = (new Content)
    	// 	->getWithPath('webpage/' . $category)
    	// 	->first()
    	// 	->fresh();

        $page;
        $items = (new Content)->getWithPath('webpage/' . $category);

        if (count($items) > 1) {
            foreach( $items as $item ) {
                $item->path = $item->getMeta('path');

                if (trim($item->path, '/') == $category) {
                    $page = $item->fresh();
                    break;
                }
            }
        }
        else {
            $page = $items->first()->fresh();
        }

    	$page->children = Webpage::withoutGlobalScopes()->with([
		            'relations',
		            'relations.relation',
		            'relations.relationType',
                    'meta'
		        ])
	    	->childrenOfType($page->id, 'webpage')
            ->withStatus('r', Webpage::APPROVED)
	    	->get();

        $page->resources = Content::childrenOfType($page->id, 'file')
            ->withStatus('r', Content::APPROVED)
            ->withMeta()
            ->get();

        //Set the path for the children
        if (count($page->children)) {
            foreach ($page->children as $key => $child) {
                $page->children[$key]->path = $page->children[$key]->getMeta('path');
            }
        }

        //Set the path for the page
        if (!isset($page->path)) {
            $page->path = $page->getMeta('path');
        }

        $page->parent = content($page->parent());

    	return $page;
    }
}