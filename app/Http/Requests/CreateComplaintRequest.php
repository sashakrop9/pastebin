<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplaintRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Проверка авторизации выполняется в контроллере или маршруте
    }

    public function rules()
    {
        return [
            'paste_id' => 'required|exists:pastes,id',
            'reason' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'paste_id.required' => 'Пожалуйста, выберите пасту, на которую жалуетесь.',
            'paste_id.exists' => 'Выбранная паста не существует.',
            'reason.required' => 'Укажите причину жалобы.',
            'reason.max' => 'Причина жалобы не должна превышать 255 символов.',
        ];
    }
}
