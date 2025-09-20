<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidNISN implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Allow empty values if not required
        }

        // Check if NISN is exactly 10 digits
        if (!preg_match('/^\d{10}$/', $value)) {
            $fail('NISN harus berupa 10 digit angka.');
            return;
        }

        // Check NISN checksum (basic validation)
        if (!$this->isValidNISNChecksum($value)) {
            $fail('NISN tidak valid.');
        }
    }

    /**
     * Validate NISN checksum
     */
    private function isValidNISNChecksum(string $nisn): bool
    {
        // Basic NISN validation
        // NISN format: 8 digits + 2 check digits
        $digits = str_split($nisn);
        
        // Simple checksum validation
        $sum = 0;
        for ($i = 0; $i < 8; $i++) {
            $sum += intval($digits[$i]) * (9 - $i);
        }
        
        $checkDigit = $sum % 10;
        $expectedCheckDigit = intval($digits[8]) * 10 + intval($digits[9]);
        
        return $checkDigit === ($expectedCheckDigit % 10);
    }
}