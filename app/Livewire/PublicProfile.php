<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Image;
use Livewire\Component;

class PublicProfile extends Component
{
    public User $user;

    public function mount($username)
    {
        $this->user = User::where('username', $username)
            ->where('is_public', true)
            ->firstOrFail();
    }

    public function render()
    {
        $images = Image::with('prompt:id,prompt_text')
            ->where('user_id', $this->user->id)
            ->where('is_public', true)
            ->latest()
            ->paginate(24);

        return view('livewire.public-profile', [
            'user' => $this->user,
            'images' => $images,
        ]);
    }
}
