<?php

namespace App\Http\Requests\Admin\Subscriber;

use App\Rules\MobilePhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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

    public function rules()
    {
        return array_merge($this->commonRules(), [
            'email' => 'required|string|email:filter|unique:mysql_no_prefix.loncq_user,email,' .$this->user_id.',user_id',
            'phone' => [
                'required',
                'numeric',
                new MobilePhone,
                'unique:mysql_no_prefix.loncq_user,phone,' .$this->user_id.',user_id',
            ],
            'user_id' => 'required|integer|exists:mysql_no_prefix.loncq_user,user_id',
        ]);
    }

    public function messages()
    {
        return $this->commonMessages();
    }
}
