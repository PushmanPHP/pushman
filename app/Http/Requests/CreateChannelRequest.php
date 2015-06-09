<?php namespace Pushman\Http\Requests;

class CreateChannelRequest extends Request
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
            'name'            => 'required|min:3',
            'refreshes'       => 'required|in:yes,no',
            'max_connections' => 'required|integer'
        ];
    }
}
