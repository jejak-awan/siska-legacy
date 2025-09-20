<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Allow empty values if not required
        }

        // Remove all non-digit characters
        $cleanNumber = preg_replace('/[^0-9]/', '', $value);

        // Check if it's a valid Indonesian phone number
        if (!$this->isValidIndonesianPhoneNumber($cleanNumber)) {
            $fail('Nomor HP tidak valid. Gunakan format: 08xxxxxxxxxx, +628xxxxxxxxx, atau 628xxxxxxxxx');
        }
    }

    /**
     * Validate Indonesian phone number
     */
    private function isValidIndonesianPhoneNumber(string $number): bool
    {
        // Indonesian mobile number patterns
        $patterns = [
            '/^08[1-9][0-9]{6,10}$/', // 08xxxxxxxxxx (10-13 digits total)
            '/^628[1-9][0-9]{6,10}$/', // 628xxxxxxxxx (11-14 digits total)
            '/^\+628[1-9][0-9]{6,10}$/', // +628xxxxxxxxx (12-15 digits total)
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $number)) {
                return true;
            }
        }

        return false;
    }
}