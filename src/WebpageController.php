<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Controllers\Controller;
use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Models\ContentMeta;
use Baytek\Laravel\Content\Models\ContentRelation;
use Baytek\Laravel\Content\Types\Webpage\Webpage;
use Baytek\Laravel\Settings\SettingsProvider;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

use Cache;
use View;

class WebpageController extends ContentController
{
    /**
     * The model the Content Controller super class will use to access the resource
     *
     * @var Baytek\Laravel\Content\Types\Webpage\Webpage
     */
    protected $model = Webpage::class;

    /**
     * List of views this content type uses
     * @var [type]
     */
    protected $views = [
        'index' => 'index',
        'create' => 'create',
        'edit' => 'edit',
        'show' => 'show',
    ];


    /**
     * [__construct description]
     * @param \Baytek\Laravel\Settings\SettingsProvider $config Create the config instance
     */
    public function __construct(\Baytek\Laravel\Settings\SettingsProvider $config)
    {
        parent::__construct();
    }

    /**
     * Show the index of all content with content type 'webpage'
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $webpages = Webpage::all();
        $relations = Cache::get('content.cache.relations')->where('relation_type_id', 4);

        $this->viewData['index'] = [
            'webpages' => Content::hierarchy($webpages),
        ];

        return parent::contentIndex();
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['create'] = [
            'parents' => Webpage::all(),
        ];

        return parent::contentCreate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->redirects = false;

        $request->merge(['key' => str_slug($request->title)]);

        $webpage = parent::contentStore($request);
        $webpage->saveRelation('parent-id', $request->parent_id);
        $webpage->saveMetadata('external_url', $request->external_url);
        $webpage->cacheUrl();

        $webpage->onBit(Webpage::APPROVED)->update();

        return redirect(route($this->names['singular'].'.show', $webpage));
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $webpage = $this->bound($id);
        $parent = $webpage->getRelationship('parent-id');

        $this->viewData['edit'] = [
            'parents' => Webpage::all(),
            'parent_id' => ($parent) ? $parent->id : null,
        ];

        return parent::contentEdit($id);
    }

    /**
     * Show the webpage
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return parent::contentShow($id);
    }

    /**
     * Show the webpage
     *
     * @return \Illuminate\Http\Response
     */
    public function render($id = null)
    {
        if(is_null($id)) {
            abort(404);
        }

        $this->viewData['show'] = [
            'layout' => 'marketing'
        ];

        return parent::contentShow($id);
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->redirects = false;

        // $request->merge(['key' => str_slug($request->title)]);

        $webpage = parent::contentUpdate($request, $id);
        $webpage->saveMetadata('external_url', $request->external_url);
        $webpage->removeRelationByType('parent-id');
        $webpage->saveRelation('parent-id', $request->parent_id);

        $webpage->cacheUrl();

        return redirect(route($this->names['singular'].'.show', $webpage));
    }

    /**
     * Recursively Destroy using status bits
     */
    public function destroy($id)
    {
        $webpage = $this->bound($id);

        $this->getChildrenAndDelete($webpage);
    }

    public function getChildrenAndDelete($item) {

        $children = Content::childrenOf($item->id)->get();

        if ($children->isNotEmpty()) {
            foreach ($children as $item) {
                $this->getChildrenAndDelete($item);
            }
        }

        $item->offBit(Content::APPROVED)->onBit(Content::DELETED)->update();
    }

}
