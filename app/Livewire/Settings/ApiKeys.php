<?php

namespace App\Livewire\Settings;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class ApiKeys extends Component
{
    public $provider = '';
    public $label = '';
    public $key = '';
    public $confirm_delete_id = null;

    public $providers = ['openai', 'replicate', 'stability', '9router'];

    public function add()
    {
        $this->validate([
            'provider' => 'required|in:openai,replicate,stability,9router',
            'label' => 'required|max:100',
            'key' => 'required|string',
        ]);

        ApiKey::create([
            'user_id' => auth()->id(),
            'provider' => $this->provider,
            'label' => $this->label,
            'key_encrypted' => Crypt::encryptString($this->key),
        ]);

        $this->reset(['provider', 'label', 'key']);
        $this->dispatch('key-added');
    }

    public function confirmDelete($id)
    {
        $this->confirm_delete_id = $id;
    }

    public function cancelDelete()
    {
        $this->confirm_delete_id = null;
    }

    public function delete($id)
    {
        ApiKey::where('user_id', auth()->id())->where('id', $id)->delete();
        $this->confirm_delete_id = null;
    }

    public function render()
    {
        $keys = ApiKey::where('user_id', auth()->id())->get();
        return view('livewire.settings-api-keys', compact('keys'));
    }
}
