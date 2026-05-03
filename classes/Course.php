<?php

declare(strict_types=1);

namespace App;

class Course
{
    private Database $db;
    private ?string $id = null;
    private ?string $title = null;
    private ?string $description = null;
    private ?float $price = null;
    private ?string $duration = null;
    private ?string $image = null;

    public function __construct(?Database $db = null)
    {
        $this->db = $db ?? Database::getInstance();
    }

    public function create(array $data): bool|string
    {
        $title = trim((string) ($data['title'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        $price = (float) ($data['pricing'] ?? $data['price'] ?? 0);
        $duration = (string) ($data['duration'] ?? '');
        $creator = (string) ($data['creator'] ?? 'admin');

        if ($title === '') {
            return 'Title is required.';
        }

        if ($price <= 0) {
            return 'Price must be greater than zero.';
        }

        if (!in_array($duration, ['3_mt', '6_mt', '12_mt'], true)) {
            return 'Invalid course duration.';
        }

        $uploadResult = $this->handleImageUpload($_FILES['spanImg'] ?? $_FILES['image'] ?? null);
        if (is_string($uploadResult) && str_starts_with($uploadResult, 'ERROR:')) {
            return substr($uploadResult, 6);
        }

        $courseId = uniqid('crs_');
        $sql = 'INSERT INTO courses (course_id, title, image, description, price, duration, creator) VALUES (?, ?, ?, ?, ?, ?, ?)';

        return $this->db->execute($sql, [
            $courseId,
            Validator::sanitizeInput($title),
            $uploadResult,
            Validator::sanitizeInput($description),
            $price,
            $duration,
            Validator::sanitizeInput($creator),
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $existing = $this->getCourseById($id);
        if (!$existing) {
            return false;
        }

        $title = $data['title'] ?? $existing['title'];
        $description = $data['description'] ?? $existing['description'];
        $price = isset($data['price']) ? (float) $data['price'] : (float) $existing['price'];
        $duration = $data['duration'] ?? $existing['duration'];

        $imageName = $existing['image'];
        if (!empty($_FILES['spanImg']['name']) || !empty($_FILES['image']['name'])) {
            $uploadResult = $this->handleImageUpload($_FILES['spanImg'] ?? $_FILES['image']);
            if (is_string($uploadResult) && str_starts_with($uploadResult, 'ERROR:')) {
                return false;
            }
            $imageName = $uploadResult;
        }

        return $this->db->execute(
            'UPDATE courses SET title = ?, image = ?, description = ?, price = ?, duration = ? WHERE course_id = ?',
            [Validator::sanitizeInput((string) $title), $imageName, Validator::sanitizeInput((string) $description), $price, $duration, (string) $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->db->execute('DELETE FROM courses WHERE course_id = ?', [(string) $id]);
    }

    public function getAllCourses(): array
    {
        return $this->db->fetchAll('SELECT * FROM courses ORDER BY title ASC');
    }

    public function getCourseById(int $id): ?array
    {
        return $this->db->fetchOne('SELECT * FROM courses WHERE course_id = ?', [(string) $id]);
    }

    public function searchCourses(string $keyword): array
    {
        return $this->db->fetchAll('SELECT * FROM courses WHERE title LIKE ? OR description LIKE ?', ["%{$keyword}%", "%{$keyword}%"]);
    }

    public function getCoursesByPriceRange(float $min, float $max): array
    {
        return $this->db->fetchAll('SELECT * FROM courses WHERE price BETWEEN ? AND ? ORDER BY price ASC', [$min, $max]);
    }

    private function handleImageUpload(?array $file): string
    {
        if ($file === null || !isset($file['error'])) {
            return '';
        }

        if ((int) $file['error'] !== UPLOAD_ERR_OK) {
            return 'ERROR:Image upload failed.';
        }

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed, true)) {
            return 'ERROR:Invalid file type.';
        }

        if ((int) $file['size'] > 2 * 1024 * 1024) {
            return 'ERROR:File too large.';
        }

        $newName = bin2hex(random_bytes(16)) . '.' . $ext;
        $uploadDir = dirname(__DIR__) . '/admin/img/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $targetPath = $uploadDir . $newName;
        if (!move_uploaded_file((string) $file['tmp_name'], $targetPath)) {
            return 'ERROR:Unable to store uploaded file.';
        }

        return $newName;
    }
}
