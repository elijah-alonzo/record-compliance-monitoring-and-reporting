<?php

declare(strict_types=1);

namespace App\Features\Reports\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared base: the report forms send "all" for an unset filter, which is treated as no filter.
 */
abstract class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validated ids arrive as strings, the report actions expect integers.
     *
     * @param  array-key|null  $key
     * @return ($key is null ? array<string, mixed> : mixed)
     */
    public function validated($key = null, $default = null): mixed
    {
        $data = parent::validated();

        foreach (['department', 'faculty'] as $id) {
            if (isset($data[$id])) {
                $data[$id] = (int) $data[$id];
            }
        }

        return data_get($data, $key, $default);
    }

    protected function prepareForValidation(): void
    {
        $cleared = [];

        foreach (['year', 'semester', 'status', 'timing'] as $key) {
            if ($this->input($key) === 'all' || $this->input($key) === '') {
                $cleared[$key] = null;
            }
        }

        $this->merge($cleared);
    }
}
