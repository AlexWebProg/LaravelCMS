<?php
namespace App\Http\Requests\Admin\Subscribe;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'subscribe_type_id' => 'required|integer|exists:mysql_no_prefix.loncq_subscribe_settings,id',
            'sum_calc_type' => 'required',
            'sum' => 'required_if:sum_calc_type,1|numeric',
            'exclude' => 'nullable|string',
            'comment_manager' => 'nullable|string',
            'comment_subscriber' => 'nullable|string',
            'subscribe_date' => 'nullable|date',
            'delivery_type_id' => 'required|integer|exists:mysql_no_prefix.loncq_subscribe_settings,id',
            'delivery_addr' => 'required|string',
            'periodicity_id' => 'required|integer|exists:mysql_no_prefix.loncq_subscribe_settings,id',
            'size_bottom_id' => 'required|integer|exists:mysql_no_prefix.loncq_subscribe_settings,id',
            'size_foot_id' => 'required|integer|exists:mysql_no_prefix.loncq_subscribe_settings,id',
            'size_height_id' => 'required|integer|exists:mysql_no_prefix.loncq_subscribe_settings,id',
            'size_top_id' => 'required|integer|exists:mysql_no_prefix.loncq_subscribe_settings,id',
            'send_dates_id' => 'required|integer|exists:mysql_no_prefix.loncq_subscribe_settings,id',
            'pref_acc_id' => 'nullable|integer|exists:mysql_no_prefix.loncq_subscribe_settings,id',
        ];
    }

    protected function commonMessages()
    {
        return [
            'subscribe_type_id.required' => 'Это поле необходимо заполнить',
            'subscribe_type_id.integer' => 'Это поле заполнено неверно',
            'subscribe_type_id.exists' => 'Такой тип подписки не найден',

            'sum_calc_type.required' => 'Это поле необходимо заполнить',

            'sum.required_if' => 'Это поле необходимо заполнить',
            'sum.numeric' => 'Сумма должна быть указана числом в формате 0.00',

            'exclude.string' => 'Это поле должно быть текстом',

            'comment_manager.string' => 'Это поле должно быть текстом',

            'comment_subscriber.string' => 'Это поле должно быть текстом',

            'subscribe_date.date' => 'Это поле должно быть датой в формате дд.мм.гггг чч:мм',

            'delivery_type_id.required' => 'Это поле необходимо заполнить',
            'delivery_type_id.integer' => 'Это поле заполнено неверно',
            'delivery_type_id.exists' => 'Такой тип доставки не найден',

            'delivery_addr.required' => 'Это поле необходимо заполнить',
            'delivery_addr.string' => 'Это поле должно быть текстом',

            'periodicity_id.required' => 'Это поле необходимо заполнить',
            'periodicity_id.integer' => 'Это поле заполнено неверно',
            'periodicity_id.exists' => 'Такой тип периодичности отправки не найден',

            'size_bottom_id.required' => 'Это поле необходимо заполнить',
            'size_bottom_id.integer' => 'Это поле заполнено неверно',
            'size_bottom_id.exists' => 'Такой размер не найден',

            'size_foot_id.required' => 'Это поле необходимо заполнить',
            'size_foot_id.integer' => 'Это поле заполнено неверно',
            'size_foot_id.exists' => 'Такой размер не найден',

            'size_height_id.required' => 'Это поле необходимо заполнить',
            'size_height_id.integer' => 'Это поле заполнено неверно',
            'size_height_id.exists' => 'Такой размер не найден',

            'size_top_id.required' => 'Это поле необходимо заполнить',
            'size_top_id.integer' => 'Это поле заполнено неверно',
            'size_top_id.exists' => 'Такой размер не найден',

            'send_dates_id.required' => 'Это поле необходимо заполнить',
            'send_dates_id.integer' => 'Это поле заполнено неверно',
            'send_dates_id.exists' => 'Такие даты отправки не найдены',

            'pref_acc_id.integer' => 'Это поле заполнено неверно',
            'pref_acc_id.exists' => 'Такой тип учёта предпочтений не найден',
        ];
    }
}