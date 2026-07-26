<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Gallery\GalleryIndex;
use App\Livewire\Gallery\GalleryShow;
use App\Livewire\Prompt\PromptCreate;
use App\Livewire\Prompt\PromptEdit;
use App\Livewire\Prompt\PromptIndex;
use App\Livewire\Collection\CollectionIndex;
use App\Livewire\Collection\CollectionShow;
use App\Livewire\Settings\ApiKeys;
use App\Livewire\Settings\Profile;
use App\Livewire\Dashboard;
use App\Livewire\PublicProfile;
use App\Livewire\SharedImage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::prefix('/gallery')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', GalleryIndex::class)->name('gallery.index');
    Route::get('/{image}', GalleryShow::class)->name('gallery.show');
});

Route::prefix('/prompts')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', PromptIndex::class)->name('prompts.index');
    Route::get('/create', PromptCreate::class)->name('prompts.create');
    Route::get('/{prompt}/edit', PromptEdit::class)->name('prompts.edit');
});

Route::prefix('/collections')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', CollectionIndex::class)->name('collections.index');
    Route::get('/{collection}', CollectionShow::class)->name('collections.show');
});

Route::prefix('/settings')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/keys', ApiKeys::class)->name('settings.keys');
    Route::get('/profile', Profile::class)->name('settings.profile');
});

// Public routes
Route::get('/@{username}', PublicProfile::class)->name('profile.public');
Route::get('/s/{shareToken}', SharedImage::class)->name('image.shared');

require __DIR__.'/auth.php';
