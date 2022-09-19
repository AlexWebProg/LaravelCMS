<?php

namespace App\Http\Requests\Admin\Subscriber;

use App\Rules\MobilePhone;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use CommonStoreUpdateData;

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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'phone' => preg_replace('/[^[:digit:]]/', '', $this->phone)
        ]);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge($this->commonRules(), [
            'email' => 'required|email:filter|unique:mysql_no_prefix.loncq_user,email,0,subscriber',
            'phone' => [
                'required',
                'numeric',
                new MobilePhone,
                'unique:mysql_no_prefix.loncq_user,phone,0,subscriber'
            ],
        ]);
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
