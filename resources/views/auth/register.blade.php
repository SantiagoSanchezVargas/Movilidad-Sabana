<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" class="text-slate-300" />
            <x-text-input id="name" class="block mt-1 w-full bg-[#0f172a] border-slate-600 text-white focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="apellido" :value="__('Apellido')" class="text-slate-300" />
            <x-text-input id="apellido" class="block mt-1 w-full bg-[#0f172a] border-slate-600 text-white focus:border-indigo-500 focus:ring-indigo-500" type="text" name="apellido" :value="old('apellido')" required autocomplete="apellido" />
            <x-input-error :messages="$errors->get('apellido')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />
            <x-text-input id="email" class="block mt-1 w-full bg-[#0f172a] border-slate-600 text-white focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" class="text-slate-300" />
            <x-text-input id="password" class="block mt-1 w-full bg-[#0f172a] border-slate-600 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirma tu contraseña')" class="text-slate-300" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-[#0f172a] border-slate-600 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Tipo de Usuario -->
        <div class="mt-4">
           <x-input-label for="role_id" :value="__('Tipo de Usuario')" class="text-slate-300" />

            <select
                id="role_id"
                name="role_id"
                required
                class="block mt-1 w-full bg-[#0f172a] border-slate-600 text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="">Selecciona un rol</option>

                @foreach($roles as $role)
                    <option value="{{ $role->id }}">
                        {{ $role->nombre }}
                    </option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-slate-400 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('¿Ya tienes una cuenta?') }}
            </a>

            <x-primary-button class="ms-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 shadow-lg transition-all">
                {{ __('REGISTRARSE') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>