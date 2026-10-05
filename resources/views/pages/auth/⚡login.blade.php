<?php

use Livewire\Component;

new class extends Component
{

    public $email = "";
    public $password = "";
    public $nama_lengkap = "";

    public function save()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        session()->flash('message', 'Login berhasil!');
    }

    public function render()
    {
        return view('pages.auth.⚡login')
        ->title('Silahkan Login');
    }
};
?>

<div>

    <livewire:pages::component.navbar/>

    <form wire:submit='save'>
        <input type="email" wire:model='email' placeholder="Email">
        @error('email') <span class="error">{{ $message }}</span> @enderror

        <input type="password" wire:model='password' placeholder="Password">
        @error('password') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Login</button>
    </form>
</div>
