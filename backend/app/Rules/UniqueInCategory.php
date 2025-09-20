<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueInCategory implements ValidationRule
{
    protected $categoryId;
    protected $ignoreId;
    protected $table;

    /**
     * Create a new rule instance.
     */
    public function __construct($categoryId, $ignoreId = null, $table = 'reference_data')
    {
        $this->categoryId = $categoryId;
        $this->ignoreId = $ignoreId;
        $this->table = $table;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Allow empty values
        }

        $query = DB::table($this->table)
            ->where('category_id', $this->categoryId)
            ->where($attribute, $value);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail("The {$attribute} has already been taken in this category.");
        }
    }
}