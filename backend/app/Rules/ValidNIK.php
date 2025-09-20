<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidNIK implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Allow empty values if not required
        }

        // Check if NIK is exactly 16 digits
        if (!preg_match('/^\d{16}$/', $value)) {
            $fail('NIK harus berupa 16 digit angka.');
            return;
        }

        // Check NIK structure and validity
        if (!$this->isValidNIKStructure($value)) {
            $fail('NIK tidak valid.');
        }
    }

    /**
     * Validate NIK structure
     */
    private function isValidNIKStructure(string $nik): bool
    {
        // NIK format: 2 digits province + 2 digits regency + 2 digits district + 6 digits birth date + 4 digits sequence
        $province = substr($nik, 0, 2);
        $regency = substr($nik, 2, 2);
        $district = substr($nik, 4, 2);
        $birthDate = substr($nik, 6, 6);
        $sequence = substr($nik, 12, 4);

        // Validate province code (01-94)
        if (intval($province) < 1 || intval($province) > 94) {
            return false;
        }

        // Validate regency code (01-99)
        if (intval($regency) < 1 || intval($regency) > 99) {
            return false;
        }

        // Validate district code (01-99)
        if (intval($district) < 1 || intval($district) > 99) {
            return false;
        }

        // Validate birth date format
        $day = intval(substr($birthDate, 0, 2));
        $month = intval(substr($birthDate, 2, 2));
        $year = intval(substr($birthDate, 4, 2));

        // Check if day is valid (01-31)
        if ($day < 1 || $day > 31) {
            return false;
        }

        // Check if month is valid (01-12)
        if ($month < 1 || $month > 12) {
            return false;
        }

        // Check if year is valid (00-99)
        if ($year < 0 || $year > 99) {
            return false;
        }

        // Validate sequence number (0001-9999)
        if (intval($sequence) < 1 || intval($sequence) > 9999) {
            return false;
        }

        return true;
    }
}