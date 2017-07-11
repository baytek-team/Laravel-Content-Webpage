<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Events\ContentEvent;
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
use Validator;

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
        $search  = (!is_null($request->search))? "%{$request->search}%" : '';

        $query = Content::childrenOfType(content('content-type/webpage')->id, 'webpage')
            ->withStatus('r', Webpage::APPROVED);
        if ($search) {
            $query = $query->where('r.title', 'like', [$search])
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
    public function create()
    {
        $webpages = Webpage::withStatus('contents', Webpage::APPROVED)->get();
        $this->viewData['create'] = [
            'parents' => Content::hierarchy($webpages, false),
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
        $webpage->cacheUrl();

        $webpage->onBit(Webpage::APPROVED)->update();

        event(new ContentEvent($webpage));

        return redirect(route($this->names['singular'].'.index'));
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

        $webpages = Webpage::withStatus('contents', Webpage::APPROVED)->get();
        $this->viewData['edit'] = [
            'parents' => Content::hierarchy($webpages, false),
            'parent_id' => ($parent) ? $parent->id : null,
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
        $search  = (!is_null($request->search))? "%{$request->search}%" : '';

        $query = Content::childrenOfType($id, 'webpage')
            ->withStatus('r', Webpage::APPROVED);
        if ($search) {
            $query = $query->where('r.title', 'like', [$search])
                ->orWhere('r.content', 'like', [$search]);
        }
        $webpages = $query->paginate($per_page);

        $webpage = $this->bound($id);
        $parent = content($webpage->getRelationship('parent-id'));

        $this->viewData['index'] = [
            'webpages' => $webpages,
            'parent' => $parent,
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
        $webpage->saveMetadata('external_url', $request->external_url);
        $webpage->removeRelationByType('parent-id');
        $webpage->saveRelation('parent-id', ($request->parent_id) ?: (new Content)->getContentByKey('webpage')->id);

        $webpage->cacheUrl();
        event(new ContentEvent($webpage));

        return redirect(route($this->names['singular'].'.index'));
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

        return redirect(route($this->names['singular'].'.index'));
    }

    public function getChildrenAndDelete($item) {

        $children = Content::childrenOf($item->id)->get();

        if ($children->isNotEmpty()) {
            foreach ($children as $child) {
                $this->getChildrenAndDelete($child);
            }
        }

        $item->offBit(Content::APPROVED)->onBit(Content::DELETED)->update();
    }

}
