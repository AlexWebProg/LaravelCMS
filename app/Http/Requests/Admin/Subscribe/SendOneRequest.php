<?php

namespace App\Http\Requests\Admin\Subscribe;

use Illuminate\Foundation\Http\FormRequest;

class SendOneRequest extends FormRequest
{
    use CommonSendData;

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
        return $this->commonRules();
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
