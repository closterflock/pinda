<?php

namespace App\Http\Requests;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string url
 * @property string title
 * @property string description
 * @property array tags
 */
class LinkRequest extends FormRequest
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
        $urlRules = [
            'required',
            'url'
        ];
        if ($this->user() !== null) {
            $urlRules[] = Rule::unique('links')
                ->where('user_id', $this->user()->id);
        }

        return [
            'url' => $urlRules,
            'title' => 'string|nullable',
            'description' => 'string|nullable',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id'
        ];
    }
}
