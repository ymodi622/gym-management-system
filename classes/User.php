<?php

declare(strict_types=1);

namespace App;

class User
{
    protected Database $db;
    protected ?string $id = null;
    protected ?string $name = null;
    protected ?string $email = null;
    protected ?string $phone = null;
    protected ?float $height = null;
    protected ?float $weight = null;
    protected string $role = 'member';

    public function __construct(?Database $db = null)
    {
        $this->db = $db ?? Database::getInstance();
    }

    public function register(array $data): bool|string
    {
        $rules = [
            'name' => 'name',
            'email' => 'email',
            'phone' => 'phone',
            'password' => 'password',
            'height' => 'height',
            'weight' => 'weight',
        ];

        $errors = Validator::validateAll($data, $rules);
        if (!empty($errors)) {
            return implode(' ', array_values($errors));
        }

        $existing = $this->getUserByEmail((string) $data['email']);
        if ($existing) {
            return 'Email already registered.';
        }

        $userId = uniqid('willuser_');
        $hash = password_hash((string) $data['password'], PASSWORD_BCRYPT);

        $sql = 'INSERT INTO users (user_id, name, gender, phone, email, password, height, weight, is_member) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)';

        return $this->db->execute($sql, [
            $userId,
            Validator::sanitizeInput((string) $data['name']),
            $data['gender'] ?? 'other',
            (string) $data['phone'],
            strtolower((string) $data['email']),
            $hash,
            (float) $data['height'],
            (float) $data['weight'],
        ]);
    }

    public function login(string $email, string $password): array|false
    {
        $user = $this->getUserByEmail($email);
        if (!$user) {
            return false;
        }

        $stored = $user['password'] ?? '';
        $verified = password_verify($password, $stored);

        if (!$verified && hash_equals((string) $stored, $password)) {
            $newHash = password_hash($password, PASSWORD_BCRYPT);
            $this->db->execute('UPDATE users SET password = ? WHERE user_id = ?', [$newHash, $user['user_id']]);
            $verified = true;
            $user['password'] = $newHash;
        }

        if (!$verified) {
            return false;
        }

        $session = new SessionManager();
        $session->start();
        $session->regenerate();
        $session->set('user_id', $user['user_id']);
        $session->set('user_name', $user['name']);
        $session->set('user_email', $user['email']);
        $session->set('phone', $user['phone'] ?? null);
        $session->set('gender', $user['gender'] ?? null);
        $session->set('is_mem', $user['is_member'] ?? 0);
        $session->set('role', $user['role'] ?? 'member');
        $session->set('last_activity', time());

        return $user;
    }

    public function updateProfile(string $userId, array $data): bool
    {
        $allowed = ['name', 'email', 'phone', 'height', 'weight', 'gender'];
        $fields = [];
        $params = [];

        foreach ($allowed as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
                continue;
            }

            $value = $data[$field];
            if ($field === 'name' && !Validator::validateName((string) $value)) {
                continue;
            }
            if ($field === 'email' && !Validator::validateEmail((string) $value)) {
                continue;
            }
            if ($field === 'phone' && !Validator::validatePhone((string) $value)) {
                continue;
            }
            if ($field === 'height' && !Validator::validateHeight((float) $value)) {
                continue;
            }
            if ($field === 'weight' && !Validator::validateWeight((float) $value)) {
                continue;
            }

            $fields[] = "$field = ?";
            $params[] = in_array($field, ['height', 'weight'], true) ? (float) $value : Validator::sanitizeInput((string) $value);
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $userId;
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE user_id = ?';
        return $this->db->execute($sql, $params);
    }

    public function changePassword(string $userId, string $current, string $new): bool
    {
        $user = $this->getUserById($userId);
        if (!$user || !password_verify($current, $user['password'] ?? '')) {
            return false;
        }

        if (!Validator::validatePassword($new)) {
            return false;
        }

        $hash = password_hash($new, PASSWORD_BCRYPT);
        return $this->db->execute('UPDATE users SET password = ? WHERE user_id = ?', [$hash, $userId]);
    }

    public function getUserById(string $id): ?array
    {
        return $this->db->fetchOne('SELECT * FROM users WHERE user_id = ?', [(string) $id]);
    }

    public function getUserByEmail(string $email): ?array
    {
        return $this->db->fetchOne('SELECT * FROM users WHERE email = ? LIMIT 1', [strtolower($email)]);
    }
}
