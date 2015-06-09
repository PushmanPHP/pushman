<?php

namespace Pushman\Http\Requests;

class EditBanRequest extends Request
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
            'id'       => 'required|exists:bans',
            'ip'       => 'required',
            'duration' => 'required',
            'active'   => 'required|in:yes,no',
        ];
    }
}
