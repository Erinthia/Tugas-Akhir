<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'roles_id',
        'password',
        'phone_number',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Role (Many to One)
    public function roles()
    {
        return $this->belongsTo(Role::class, 'roles_id', 'id');
    }

    // Relasi ke Permission melalui Role
    /**
     * @return Collection<int, \App\Models\Permission>
     */
    /** @method \Illuminate\Support\Collection permissions() */
    public function permissions()
    {
        $data = $this->roles()               // ambil roles milik user
            ->with('permissions')            // sertakan permissions
            ->get()
            ->pluck('permissions')           // ambil permissions-nya
            ->flatten()                      // ubah jadi satu array datar
            ->unique('id')                   // hapus duplikat berdasarkan ID
            ->values();                      // reset index agar rapi

        return $data;
    }

    public function hasPermission($id)
    {
        return $this->roles_id == 1 || $this->permissions()->pluck('id')->contains($id);
    }
}
