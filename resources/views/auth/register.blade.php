<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" name="password" required />
            </div>

            <!-- Password Confirmation -->
            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" type="password" name="password_confirmation" required />
            </div>

            <!-- Nom -->
            <div class="mt-4">
                <x-label for="nom" value="{{ __('Nom') }}" />
                <x-input id="nom" type="text" name="nom" :value="old('nom')" required />
            </div>

            <!-- Prénom -->
            <div class="mt-4">
                <x-label for="prenom" value="{{ __('Prénom') }}" />
                <x-input id="prenom" type="text" name="prenom" :value="old('prenom')" required />
            </div>

            <!-- Date de naissance -->
            <div class="mt-4">
                <x-label for="date_naissance" value="{{ __('Date de naissance') }}" />
                <x-input id="date_naissance" type="date" name="date_naissance" :value="old('date_naissance')" required />
            </div>

            <!-- Adresse complète -->
            <div class="mt-4">
                <x-label for="rue" value="{{ __('Rue') }}" />
                <x-input id="rue" type="text" name="rue" :value="old('rue')" />
            </div>

            <div class="mt-4">
                <x-label for="numero" value="{{ __('Numéro') }}" />
                <x-input id="numero" type="text" name="numero" :value="old('numero')" />
            </div>

            <div class="mt-4">
                <x-label for="ville" value="{{ __('Ville') }}" />
                <x-input id="ville" type="text" name="ville" :value="old('ville')" />
            </div>

            <div class="mt-4">
                <x-label for="province" value="{{ __('Province') }}" />
                <x-input id="province" type="text" name="province" :value="old('province')" />
            </div>

            <div class="mt-4">
                <x-label for="code_postal" value="{{ __('Code postal') }}" />
                <x-input id="code_postal" type="text" name="code_postal" :value="old('code_postal')" />
            </div>

            <div class="mt-4">
                <x-label for="type" value="{{ __('Type de votre adresse') }}" />
                <select id="type" name="type" class="block mt-1 w-full border-gray-300 rounded-md">
                    <option value="maison" selected>Maison</option>
                    <option value="travail">Travail</option>
                </select>
            </div>

            <!-- Téléphone -->
            <div class="mt-4">
                <x-label for="telephone" value="{{ __('Téléphone') }}" />
                <x-input id="telephone" type="text" name="telephone" :value="old('telephone')" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>{{ __('Register') }}</x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
