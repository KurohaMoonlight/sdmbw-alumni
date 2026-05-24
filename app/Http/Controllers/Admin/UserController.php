<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar admin dan kepala sekolah.
     */
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'kepala_sekolah'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah pengguna.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'role'     => ['required', Rule::in(['admin', 'kepala_sekolah'])],
            'password' => 'required|string|min:6|confirmed',
        ], [
            'username.unique'    => 'Username ini sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 6 karakter.'
        ]);

        $user = User::create([
            'username'  => $request->username,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        AdminLog::log(
            Auth::id(),
            'create_user',
            'users',
            $user->id,
            "Membuat akun pengguna baru: {$user->username} (Role: {$user->role})"
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->username} berhasil dibuat.");
    }

    /**
     * Menampilkan form edit pengguna.
     */
    public function edit(User $user)
    {
        // Cegah admin mengedit role pengguna selain admin/kepala sekolah melalui URL ini
        if (!in_array($user->role, ['admin', 'kepala_sekolah'])) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna.
     */
    public function update(Request $request, User $user)
    {
        if (!in_array($user->role, ['admin', 'kepala_sekolah'])) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'     => ['required', Rule::in(['admin', 'kepala_sekolah'])],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->username = $request->username;
        
        // Mencegah user menghapus hak akses admin dari dirinya sendiri
        if ($user->id !== Auth::id() || $request->role === 'admin') {
            $user->role = $request->role;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        AdminLog::log(
            Auth::id(),
            'update_user',
            'users',
            $user->id,
            "Mengupdate akun pengguna: {$user->username} (Role: {$user->role})"
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->username} berhasil diperbarui.");
    }

    /**
     * Menghapus pengguna (Opsional).
     */
    public function destroy(User $user)
    {
        if (!in_array($user->role, ['admin', 'kepala_sekolah'])) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $username = $user->username;
        $user->delete();

        AdminLog::log(
            Auth::id(),
            'delete_user',
            'users',
            null,
            "Menghapus akun pengguna: {$username}"
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$username} berhasil dihapus.");
    }
}
