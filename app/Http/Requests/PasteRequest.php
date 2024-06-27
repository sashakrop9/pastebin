<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasteRequest extends FormRequest
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
     * @return string[]
     */
    function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'paste_content' => 'required|string',
            'expires_at' => 'required|in:10min,1hour,3hours,1day,1week,1month,never',
            'access' => 'required|in:public,unlisted,private',
            'language' => 'nullable|string',
        ];
    }
}
