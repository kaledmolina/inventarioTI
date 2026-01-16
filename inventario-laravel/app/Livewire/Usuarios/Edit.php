<?php

namespace App\Livewire\Usuarios;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class Edit extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password; // Optional for edit
    public $selectedRoles = [];

    public $roles;

    public function mount($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('id')->toArray();

        $this->roles = Role::orderBy('nombre_rol')->get();
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'password' => ['nullable', Rules\Password::defaults()],
            'selectedRoles' => ['required', 'array', 'min:1'],
            'selectedRoles.*' => ['exists:roles,id'],
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);
        $user->roles()->sync($this->selectedRoles);

        return redirect()->route('usuarios.index')->with('status', 'Usuario actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.usuarios.edit')->layout('layouts.app-legacy');
    }
}
