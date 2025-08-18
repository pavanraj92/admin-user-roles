<?php

namespace admin\user_roles\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRoleUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
              'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('user_roles', 'name')->whereNull('deleted_at') ->ignore($this->route('user_role')->id),
            ],
            'status' => 'required|in:0,1',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

