<?php

declare(strict_types=1);

namespace App;

class Validator
{
    public static function validateEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePhone(string $phone): bool
    {
        return (bool) preg_match('/^\d{10}$/', $phone);
    }

    public static function validatePassword(string $password): bool
    {
        return (bool) preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/', $password);
    }

    public static function validateName(string $name): bool
    {
        return (bool) preg_match('/^[a-zA-Z ]{2,50}$/', $name);
    }

    public static function validateHeight(float $height): bool
    {
        return $height >= 50 && $height <= 300;
    }

    public static function validateWeight(float $weight): bool
    {
        return $weight >= 20 && $weight <= 500;
    }

    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    public static function validateAll(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            if ($value === null || $value === '') {
                $errors[$field] = ucfirst($field) . ' is required.';
                continue;
            }

            $isValid = match ($rule) {
                'email' => self::validateEmail((string) $value),
                'phone' => self::validatePhone((string) $value),
                'password' => self::validatePassword((string) $value),
                'name' => self::validateName((string) $value),
                'height' => self::validateHeight((float) $value),
                'weight' => self::validateWeight((float) $value),
                default => true,
            };

            if (!$isValid) {
                $errors[$field] = 'Invalid ' . $field . '.';
            }
        }

        return $errors;
    }
}
