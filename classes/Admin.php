<?php

declare(strict_types=1);

namespace App;

class Admin extends User
{
    public function login(string $email, string $password): array|false
    {
        $admin = $this->db->fetchOne('SELECT * FROM admins WHERE email = ? LIMIT 1', [strtolower($email)]);
        if (!$admin) {
            return false;
        }

        $stored = $admin['password'] ?? '';
        $verified = password_verify($password, $stored);

        if (!$verified && hash_equals((string) $stored, $password)) {
            $newHash = password_hash($password, PASSWORD_BCRYPT);
            $this->db->execute('UPDATE admins SET password = ? WHERE email = ?', [$newHash, $admin['email']]);
            $verified = true;
        }

        if (!$verified) {
            return false;
        }

        $session = new SessionManager();
        $session->start();
        $session->regenerate();
        $session->set('admin_name', $admin['name']);
        $session->set('admin_email', $admin['email']);
        $session->set('role', 'admin');
        $session->set('last_activity', time());

        return $admin;
    }

    public function getAllMembers(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        return $this->db->fetchAll(
            'SELECT m.member_id, m.user_id, m.name, u.email, u.phone, COUNT(ms.mbs_id) AS enrolled_courses
             FROM members m
             LEFT JOIN users u ON u.user_id = m.user_id
             LEFT JOIN memberships ms ON ms.member_id = m.member_id
             GROUP BY m.member_id, m.user_id, m.name, u.email, u.phone
             ORDER BY m.name ASC
             LIMIT ? OFFSET ?',
            [$perPage, $offset]
        );
    }

    public function getMemberById(string $id): ?array
    {
        return $this->db->fetchOne('SELECT * FROM members WHERE member_id = ?', [(string) $id]);
    }

    public function deleteMember(string $id): bool
    {
        return $this->db->execute('DELETE FROM members WHERE member_id = ?', [(string) $id]);
    }

    public function getDashboardStats(): array
    {
        $totalMembers = $this->db->fetchOne('SELECT COUNT(*) AS total FROM members') ?? ['total' => 0];
        $activeMembers = $this->db->fetchOne('SELECT COUNT(DISTINCT user_id) AS active FROM memberships WHERE end_date >= NOW()') ?? ['active' => 0];
        $revenue = $this->db->fetchOne('SELECT COALESCE(SUM(amount), 0) AS revenue FROM payments WHERE status = ? AND DATE_FORMAT(payment_date, "%Y-%m") = DATE_FORMAT(NOW(), "%Y-%m")', ['success']) ?? ['revenue' => 0];

        return [
            'total_members' => (int) ($totalMembers['total'] ?? 0),
            'active_members' => (int) ($activeMembers['active'] ?? 0),
            'revenue_this_month' => (float) ($revenue['revenue'] ?? 0),
        ];
    }

    public function viewPayments(string $memberId): array
    {
        return $this->db->fetchAll('SELECT * FROM payments WHERE user_id = ? ORDER BY payment_date DESC', [(string) $memberId]);
    }
}
