<?php

namespace Baytek\Laravel\Content\Types\Webpage;

use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Controllers\Controller;
use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Models\ContentMeta;
use Baytek\Laravel\Content\Models\ContentRelation;
use Baytek\Laravel\Content\Types\Webpage\Webpage;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use View;

class WebpageController extends ContentController
{
    protected $model = Webpage::class;

    protected $views = [
        'index' => 'index',
        'create' => 'create',
        'edit' => 'edit',
        'show' => 'show',
    ];

    /**
     * Show the index of all content with content type 'webpage'
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->viewData['index'] = [
            'webpages' => Webpage::childrenOf('webpage')->get(),
        ];

        return parent::index();
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['create'] = [
            'parents' => Webpage::childrenOf('webpage')->get(),
        ];

        return parent::create();
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

        $webpage = parent::store($request);

        if($request->parent_id) {
            $webpage->saveRelation('parent-id', $request->parent_id);
        }

        return redirect(route($this->names['singular'].'.show', $webpage));
    }

    /**
     * Show the form for creating a new webpage.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->viewData['edit'] = [
            'parents' => Webpage::childrenOf('webpage')->get(),
        ];

        return parent::edit($id);
    }


    // protected function bound($id)
    // {
    //     return $this->model->find($id)->firstOrFail();
    // }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     return view($this->view('create'), [
    //         'contents' => Webpage::select('id', 'status', 'revision', 'language', 'title')->get(),
    //         'content' => (new Webpage)
    //     ]);
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     // $this->content->store($request);
    //     // parent::store();

    //     return redirect(action('\Baytek\Laravel\Content\Controllers\ContentController@show', $webpage));
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     return View::make($this->view('show'), [
    //         'webpage' => $webpage->load(Content::$eager)
    //     ]);
    //     // return $webpage->load(Webpage::$eager);
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     return view($this->view('edit'), [
    //         'contents' => Webpage::select('id', 'status', 'revision', 'language', 'title')->get(),
    //         'relationTypes' => Webpage::childrenOf('relation-type')->get(),
    //         'content' => $webpage,
    //     ]);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     // $this->content->update($request, $webpage);

    //     return redirect(action('\Baytek\Laravel\Content\Controllers\ContentController@show', $webpage));
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     //
    // }

}