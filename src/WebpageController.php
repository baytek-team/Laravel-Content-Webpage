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

class WebpageController extends Controller
{
    protected $type = 'Webpage';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('Webpage::index', [
            'webpages' => Webpage::childrenOf('webpage')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Pretzel::content.create', [
            'contents' => Webpage::select('id', 'status', 'revision', 'language', 'title')->get(),
            'content' => (new Webpage)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->content->store($request);
        // parent::store();

        return redirect(action('\Baytek\Laravel\Content\Controllers\ContentController@show', $webpage));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Webpage $webpage)
    {
        return View::make('Webpage::show', [
            'webpage' => $webpage->load(Content::$eager)
        ]);
        // return $webpage->load(Webpage::$eager);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Webpage $webpage)
    {
        return view('Pretzel::content.edit', [
            'contents' => Webpage::select('id', 'status', 'revision', 'language', 'title')->get(),
            'relationTypes' => Webpage::childrenOf('relation-type')->get(),
            'content' => $webpage,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Webpage $webpage)
    {
        $this->content->update($request, $webpage);

        return redirect(action('\Baytek\Laravel\Content\Controllers\ContentController@show', $webpage));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Webpage $webpage)
    {
        //
    }

}