<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Role Constants
    |--------------------------------------------------------------------------
    | Konstanta untuk role user agar mudah digunakan di seluruh aplikasi.
    | Contoh penggunaan: User::ROLE_ADMIN
    */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_LIBRARIAN = 'librarian';
    public const ROLE_TEACHER = 'teacher';
    public const ROLE_STUDENT = 'student';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Ambil inisial dari nama user.
     * Contoh: "John Doe" -> "JD"
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Relasi ke profil member (untuk student/teacher).
     */
    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    /**
     * Relasi ke log login user.
     */
    public function loginLogs(): HasMany
    {
        return $this->hasMany(LoginLog::class);
    }

    /**
     * Cek apakah user adalah Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Cek apakah user adalah Pustakawan.
     */
    public function isLibrarian(): bool
    {
        return $this->role === self::ROLE_LIBRARIAN;
    }

    /**
     * Cek apakah user adalah Guru.
     */
    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    /**
     * Cek apakah user adalah Siswa.
     */
    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Dapatkan route dashboard berdasarkan role user.
     */
    public function dashboardRoute(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'admin.dashboard',
            self::ROLE_LIBRARIAN => 'librarian.dashboard',
            self::ROLE_TEACHER => 'teacher.dashboard',
            self::ROLE_STUDENT => 'student.dashboard',
            default => 'dashboard',
        };
    }
}

