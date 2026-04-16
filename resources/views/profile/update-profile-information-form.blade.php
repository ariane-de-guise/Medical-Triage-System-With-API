<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <input type="file" id="photo" class="hidden"
                       wire:model.live="photo"
                       x-ref="photo"
                       x-on:change="
                           photoName = $refs.photo.files[0].name;
                           const reader = new FileReader();
                           reader.onload = (e) => { photoPreview = e.target.result; };
                           reader.readAsDataURL($refs.photo.files[0]);
                       " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'"></span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Nom -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="nom" value="{{ __('Nom') }}" />
            <x-input id="nom" type="text" class="mt-1 block w-full" wire:model="state.nom" />
            <x-input-error for="nom" class="mt-2" />
        </div>

        <!-- Prénom -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="prenom" value="{{ __('Prénom') }}" />
            <x-input id="prenom" type="text" class="mt-1 block w-full" wire:model="state.prenom" />
            <x-input-error for="prenom" class="mt-2" />
        </div>

        <!-- Date de naissance -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="date_naissance" value="{{ __('Date de naissance') }}" />
            <x-input id="date_naissance" type="date" class="mt-1 block w-full" wire:model="state.date_naissance" />
            <x-input-error for="date_naissance" class="mt-2" />
        </div>

        @php
            $adresse = $this->user->compte->adresses->first() ?? null;
            $telephone = $this->user->compte->telephones->first() ?? null;
        @endphp

            <!-- No Civique -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="numero" value="{{ __('Numéro civique') }}" />
            <x-input id="numero" type="text" class="mt-1 block w-full" wire:model="state.numero"/>
        </div>

            <!-- Rue -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="rue" value="{{ __('Rue') }}" />
            <x-input id="rue" type="text" class="mt-1 block w-full" wire:model="state.rue"/>
        </div>

        <!-- Ville -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="ville" value="{{ __('Ville') }}" />
            <x-input id="ville" type="text" class="mt-1 block w-full" wire:model="state.ville"/>
        </div>

        <!-- Province -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="province" value="{{ __('Province') }}" />
            <x-input id="province" type="text" class="mt-1 block w-full" wire:model="state.province"/>
        </div>

        <!-- Code postal -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="code_postal" value="{{ __('Code postal') }}" />
            <x-input id="code_postal" type="text" class="mt-1 block w-full" wire:model="state.code_postal"/>
        </div>

        <!-- Type -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="type" value="{{ __('Options') }}" />
            <select id="type" class="mt-1 block w-full border-gray-300 rounded-md" wire:model="state.type">
                <option value="home" {{ ($adresse->type ?? '') === 'home' ? 'selected' : '' }}>Maison</option>
                <option value="work" {{ ($adresse->type ?? '') === 'work' ? 'selected' : '' }}>Travail</option>
            </select>
        </div>

        <!-- Téléphone -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="telephone" value="{{ __('Téléphone') }}" />
            <x-input id="telephone" type="text" class="mt-1 block w-full" wire:model="state.telephone"/>
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required />
            <x-input-error for="email" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
