<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $username;
    public $bio;
    public $is_public;
    public $avatar;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->bio = $user->bio;
        $this->is_public = $user->is_public;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|max:255',
            'username' => ['nullable', 'max:50', Rule::unique('users')->ignore(auth()->id())],
            'bio' => 'nullable|max:500',
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
            'is_public' => $this->is_public,
        ]);

        if ($this->avatar) {
            $this->validate(['avatar' => 'image|max:2048']);
            $path = $this->avatar->store('avatars', 'public');
            auth()->user()->update(['avatar' => $path]);
        }

        $this->dispatch('profile-saved');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
        ]);

        auth()->user()->update(['password' => Hash::make($this->new_password)]);
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->dispatch('password-updated');
    }

    public function render()
    {
        return view('livewire.settings-profile');
    }
}
