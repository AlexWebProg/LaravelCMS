<?php

namespace App\Http\Requests\Admin\Subscribe;

use App\Rules\DateMonthYear;
use Illuminate\Foundation\Http\FormRequest;

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
            'sum' => floatval(str_replace([' ',','], ['','.'], $this->sum))
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
            'next_send_month' => ['sometimes', 'required', new DateMonthYear],
        ]);
    }

    public function messages()
    {
        return array_merge($this->commonMessages(), [
            'next_send_month.required' => 'Это поле необходимо заполнить'
        ]);
    }
}
