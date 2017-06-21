<?php

namespace Baytek\Laravel\Content\Types\Webpage\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Baytek\Laravel\Content\Models\Content;
use Illuminate\Contracts\Validation\Validator;

class WebpageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|unique_key:contents,parent_id',
            //'content' => 'required',
        ];
    }

    //This is supposed to live in App\Http\Request which WebpageRequest should extend
    //But we can't have a package depend on an app asset
    //Probably need to refactor a lot of things
    protected function formatErrors(Validator $validator)
    {
        return ['errors' => $validator->errors()->all()];
    }
}
