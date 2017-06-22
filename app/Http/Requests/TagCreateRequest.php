<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

/**
 * This is a request class which contains validation logic
 */

class TagCreateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//true means user must be authorized
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tag' => 'required|unique:tags,tag',//tag must be uniqe, it will check tags table's tag column in database
            'title' => 'required',
            'subtitle' => 'required',
            'layout' => 'required',
        ];
    }
}
