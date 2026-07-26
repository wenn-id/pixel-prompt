<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Profile Settings</h1>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 space-y-4">
            <h3 class="text-sm font-medium text-slate-300">Edit Profile</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Name</label>
                    <input wire:model="name" type="text" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Username</label>
                    <div class="flex items-center gap-1 text-slate-500 text-sm">
                        <span>@</span>
                        <input wire:model="username" type="text" placeholder="username"
                               class="flex-1 bg-slate-800 border-slate-700 text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500">
                    </div>
                    @error('username') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Bio</label>
                    <textarea wire:model="bio" rows="3" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-slate-500 resize-y"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Avatar</label>
                    <input wire:model="avatar" type="file" accept="image/*"
                           class="w-full text-sm text-slate-300 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700">
                    @error('avatar') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-300 cursor-pointer">
                    <input wire:model="is_public" type="checkbox" class="rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                    Enable public profile
                </label>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition">
                    Save Profile
                </button>
            </form>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 space-y-4">
            <h3 class="text-sm font-medium text-slate-300">Change Password</h3>
            <form wire:submit="updatePassword" class="space-y-4">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Current Password</label>
                    <input wire:model="current_password" type="password" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                    @error('current_password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">New Password</label>
                    <input wire:model="new_password" type="password" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                    @error('new_password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Confirm New Password</label>
                    <input wire:model="new_password_confirmation" type="password" class="w-full bg-slate-800 border-slate-700 text-sm text-white rounded-lg px-3 py-2">
                </div>
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>