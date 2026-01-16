<?php

namespace App\Livewire\Usuarios;

use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class Create extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $selectedRoles = [];

    public $roles;

    public function mount()
    {
        $this->roles = Role::orderBy('nombre_rol')->get();
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'selectedRoles' => ['required', 'array', 'min:1'],
            'selectedRoles.*' => ['exists:roles,id'],
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->roles()->sync($this->selectedRoles);

        return redirect()->route('usuarios.index')->with('status', 'Usuario creado correctamente.');
    }

    public function render()
    {
        return view('livewire.usuarios.create')->layout('layouts.app-legacy');
    }
}
