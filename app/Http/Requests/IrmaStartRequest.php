<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\UziRelation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use RuntimeException;

class IrmaStartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'ura' => [
                'required',
                Rule::in(Arr::pluck($this->user()->uras, 'ura')),
            ],
        ];
    }

    public function getValidatedUra(): UziRelation
    {
        foreach ($this->user()->uras as $ura) {
            if ($ura->ura === $this->validated('ura')) {
                return $ura;
            }
        }

        throw new RuntimeException('Requested ura not found in users uras');
    }
}
