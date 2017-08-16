<?php

namespace Baytek\Laravel\Content\Types\Webpage\Controllers;

use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Events\ContentEvent;
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
use Validator;

class WebpageController extends ContentController
{
    /**
     * The model the Content Controller super class will use to access the resource
     *
     * @var Baytek\Laravel\Content\Types\Webpage\Webpage
     */
    protected $model = Webpage::class;

    protected $viewPrefix = 'admin';

    /**
     * List of views this content type uses
     * @var [type]
     */
    protected $views = [
        'index' => 'webpage.index',
        'create' => 'webpage.create',
        'edit' => 'webpage.edit',
        'show' => 'webpage.show',
    ];

    /**
     * [__construct description]
     * @param \Baytek\Laravel\Settings\SettingsProvider $config Create the config instance
     */
    public function __construct(SettingsProvider $config)
    {
        parent::__construct();
    }

    /**
     * Get a validator for an incoming webpage creation request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255|unique_key:contents,parent_id',
        ]);
    }

    /**
     * Show the index of all content with content type 'webpage'
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = config('cms.content.webpage.per_page') ?: 20;

        // Get the search criteria
        $search  = (!is_null($request->search)) ? "%{$request->search}%" : '';

        $query = content('content-type/webpage')->children()->withRelationships();

        if ($search) {
            $query
                ->where('r.title', 'like', [$search])
                ->orWhere('r.content', 'like', [$search]);
        }

        $webpages = $query->paginate($per_page);

        $this->viewData['index'] = [
            'webpages' => $webpages,
            'parent' => false,
        ];

        return parent::contentIndex();
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        // $webpages = Webpage::withStatus('contents', Webpage::APPROVED)->get();
        $this->viewData['create'] = [
            // 'parents' => Content::hierarchy($webpages, false),
            'parents' => [],
            'parent' => is_null($id) ? '' : Webpage::find($id),
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
        //Validate the request
        $validator = $this->validator($request->all());

        if($validator->fails())
        {
            return back()
               ->withErrors($validator)
               ->withInput();
        }

        $this->redirects = false;

        $request->merge(['key' => str_slug($request->title)]);

        $webpage = parent::contentStore($request);
        $webpage->saveRelation('parent-id', ($request->parent_id) ?: (new Content)->getContentByKey('webpage')->id);
        $webpage->saveMetadata('external_url', $request->external_url);
        $webpage->saveMetadata('path', $this->buildPathFromParents($webpage));

        $webpage->cacheUrl();

        $webpage->onBit(Webpage::APPROVED)->update();

        //Excluded status if there is an external url meta
        if ($request->external_url) {
            $webpage->onBit(Webpage::EXCLUDED)->update();
        }

        event(new ContentEvent($webpage));

        return redirect(route($this->names['singular'].'.edit', $webpage));
        //return redirect(route($this->names['singular'].'.show', $webpage));
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
            'parents' => [],
            'parent' => $parent,
        ];

        return parent::contentEdit($id);
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function editParent($id)
    {
        $webpage = $this->bound($id);
        $parent = $webpage->getRelationship('parent-id');

        $webpages = Webpage::withStatus('contents', Webpage::APPROVED)->get();
        $this->viewData['edit'] = [
            // 'parents' => Content::hierarchy($webpages, false),
            'parents' => [],
            'parent' => $parent,
            'disabledFlag' => false,
            'disabledDepth' => 0,
        ];

        return parent::contentEdit($id);
    }

    /**
     * Show the webpage
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $per_page = config('cms.content.webpage.per_page') ?: 20;

        // Get the search criteria
        $search = (!is_null($request->search))? "%{$request->search}%" : '';

        $builder = content($id)
            ->children('webpage')
            ->withRelationships()
            ->withStatus('r', Webpage::APPROVED);

        if ($search) {
            $builder
                ->where('title', 'like', [$search])
                ->orWhere('content', 'like', [$search]);
        }

        $webpages = $builder->paginate($per_page);

        $webpage = $this->bound($id);
        $parent = content($webpage->getRelationship('parent-id'));

        $this->viewData['index'] = [
            'webpages' => $webpages,
            'parent' => $parent,
            'webpage' => $webpage,
        ];

        return parent::contentIndex();
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

        //See whether the parent needs to change and if so, update this path and descendents' path
        $parent = $webpage->getRelationship('parent-id');
        if ( ($parent && $parent->id != $request->parent_id) || ($request->parent_id && !$parent) ) {
            $webpage->removeRelationByType('parent-id');
            $webpage->saveRelation('parent-id', ($request->parent_id) ?: (new Content)->getContentByKey('webpage')->id);

            //Update path
            $webpage->saveMetadata('path', $this->buildPathFromParents($webpage));

            //ten minutes
            ini_set('max_execution_time', 600);

            //Update descendents' path
            Content::descendentsOfType($webpage->id, 'webpage')
                ->each(function(&$self) {

                    $parents = $self->getParents();
                    $path = '';

                    for ($i = count($parents) - 1; $i >= 0; $i--) {
                        if ($parents[$i]->key != 'webpage') {
                            $path = '/'.$parents[$i]->key.$path;
                        }
                        else {
                            break;
                        }
                    }

                    $self->saveMetadata('path', $path);
                });
        }

        //Update metadata
        $webpage->saveMetadata('external_url', $request->external_url);

        //Remove the status bit if there is no external url, or add it if there is
        if ($webpage->hasStatus(Webpage::EXCLUDED) && !$request->external_url) {
            $webpage->offBit(Webpage::EXCLUDED)->update();
        }
        else if (!$webpage->hasStatus(Webpage::EXCLUDED) && $request->external_url) {
            $webpage->onBit(Webpage::EXCLUDED)->update();
        }

        $webpage->cacheUrl();
        event(new ContentEvent($webpage));

        return redirect(route($this->names['singular'].'.edit', $webpage));
        //return redirect(route($this->names['singular'].'.show', $webpage));
    }

    /**
     * Recursively Destroy using status bits
     */
    public function destroy($id)
    {
        $webpage = $this->bound($id);

        $this->getChildrenAndDelete($webpage);
        event(new ContentEvent($webpage));

        return back();

        // return redirect(route($this->names['singular'].'.index'));
    }

    public function getChildrenAndDelete($item)
    {

        $children = Content::childrenOf($item->id)->get();

        if ($children->isNotEmpty()) {
            foreach ($children as $child) {
                $this->getChildrenAndDelete($child);
            }
        }

        $item->offBit(Content::APPROVED)->onBit(Content::DELETED)->update();
    }

    public function buildPathFromParents($page)
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
