<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array'; // bisa juga 'object' kalau kamu mau
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'username',
        'fullname',
        'email',
        'password',
        'role',
        'birth_date',
        'gender',
        'address',
        'city',
        'phone',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Optional: Validasi internal model
    protected $validationRules    = [
        'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Username harus diisi.',
            'is_unique' => 'Username sudah digunakan.'
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique' => 'Email sudah digunakan.'
        ],
        'password' => [
            'required' => 'Password wajib diisi.',
            'min_length' => 'Password minimal 6 karakter.'
        ]
    ];

    protected $skipValidation = false;

    // ✅ Fungsi bantu untuk login
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getUserById($id)
    {
        return $this->find($id);
    }
}
