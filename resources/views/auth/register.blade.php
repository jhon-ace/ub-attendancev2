<x-guest-layout>
    <div class="text-center font-bold text-white">
        <h1>Create Account</h1>
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-white" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-white"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        @if (!App\Models\User::where('role', 'admin')->exists())
            <div class="form-group row mt-2 mb-2 w-full hidden">
                <label for="school_id" class="col-md-4 col-form-label text-md-right text-white">{{ __('School') }}</label>
                <div class="col-md-6 w-full">
                    <select id="school_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                            name="school_id">
                        <option value=""></option>
                  
                    </select>

                    @error('school_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        @else
            <div class="form-group row mt-2 mb-2 w-full">
                <label for="school_id" class="col-md-4 col-form-label text-md-right text-white">{{ __('School') }}</label>
                <div class="col-md-6 w-full">
                    <select id="school_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                            name="school_id" required>
                        @if($schools->isEmpty())
                            <option value="">No School to display</option>
                        @else
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->abbreviation }}</option>
                            @endforeach
                        @endif
                    </select>

                    @error('school_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        @endif

        <div class="form-group row">
            <label for="role" class="col-md-4 col-form-label text-md-right text-white">{{ __('Role') }}</label>
            <div class="col-md-6">
                <select id="role" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                        name="role" required>
                    
                    <option value="">Select Role</option>
                    @if (!App\Models\User::where('role', 'admin')->exists())
                        <option value="admin">Admin</option>
                    @endif
                    <option value="admin_staff">Admin Staff</option>
                    <option value="sao">SAO</option>
                    <option value="employee">Employee</option>
                    <option value="student">Student</option>
                </select>

                @error('role')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-white" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')"  class="text-white"/>

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" 
            href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>
