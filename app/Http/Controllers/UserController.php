<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAccess;
use App\Traits\GenerateIdTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use GenerateIdTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.access:pengguna')->only('index', 'show');
        $this->middleware('check.access:pengguna,tambah')->only('create', 'store');
        $this->middleware('check.access:pengguna,ubah')->only('edit', 'update');
        $this->middleware('check.access:pengguna,hapus')->only('destroy');
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Generate automatic ID
        $newId = $this->generateId('A01', 'a01_dm_users');

        return view('users.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik_kry' => 'required',
            'nama_kry' => 'required',
            'departemen_kry' => 'required',
            'jabatan_kry' => 'required',
            'wilker_kry' => 'required',
            'password_kry' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        // Generate ID if not present
        if (empty($request->id_kode)) {
            $id_kode = $this->generateId('A01', 'a01_dm_users');
        } else {
            $id_kode = $request->id_kode;
        }

        $user = User::create([
            'id_kode' => $id_kode,
            'nik_kry' => $request->nik_kry,
            'nama_kry' => $request->nama_kry,
            'departemen_kry' => $request->departemen_kry,
            'jabatan_kry' => $request->jabatan_kry,
            'wilker_kry' => $request->wilker_kry,
            'password_kry' => Hash::make($request->password_kry),
            'is_admin' => $request->has('is_admin') ? 1 : 0,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dibuat. Anda dapat mengatur hak akses melalui tombol kunci.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validationRules = [
            'nik_kry' => 'required',
            'nama_kry' => 'required',
            'departemen_kry' => 'required',
            'jabatan_kry' => 'required',
            'wilker_kry' => 'required',
        ];

        // Add password validation rules if password is being updated
        if ($request->filled('password_kry')) {
            $validationRules['password_kry'] = 'required|min:6|confirmed';
            $validationRules['password_confirmation'] = 'required';
        }

        $request->validate($validationRules);

        $data = [
            'nik_kry' => $request->nik_kry,
            'nama_kry' => $request->nama_kry,
            'departemen_kry' => $request->departemen_kry,
            'jabatan_kry' => $request->jabatan_kry,
            'wilker_kry' => $request->wilker_kry,
            'is_admin' => $request->has('is_admin') ? 1 : 0,
            'updated_by' => auth()->user()->id_kode ?? null,
        ];

        if ($request->filled('password_kry')) {
            $data['password_kry'] = Hash::make($request->password_kry);
        }

        $user->update($data);

        // If user was changed to admin, remove all user access records
        if ($user->is_admin) {
            UserAccess::where('id_kode_a01', $user->id_kode)->delete();
        }

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Delete user access
        UserAccess::where('id_kode_a01', $user->id_kode)->delete();

        // Delete user
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
