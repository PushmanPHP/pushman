<?php

namespace Pushman\Http\Requests;

class PushRequest extends Request
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
            'private' => 'required|size:60',
            'channel' => 'string|min:3',
            'event'   => 'required|string|min:3',
            'payload' => 'string',
        ];
    }
}
