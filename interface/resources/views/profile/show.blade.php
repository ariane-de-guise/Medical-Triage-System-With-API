<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-6">

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Profil
            </h2>
            <p class="text-gray-500">
                {{ $user['compte']['prenom'] }} {{ $user['compte']['nom'] }}
            </p>
        </div>

        <!-- Card -->
        <div class="bg-white shadow-xl rounded-2xl p-8 space-y-8">

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p class="text-sm text-gray-500">Courriel</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $user['email'] }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Date de naissance</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $user['compte']['date_naissance'] }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Nom</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $user['compte']['nom'] }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Prénom</p>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $user['compte']['prenom'] }}
                    </p>
                </div>

            </div>

            <!-- Divider -->
            <div class="border-t pt-6"></div>

            <!-- Addresses -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Adresses</h3>

                <div class="space-y-3">
                    @forelse($user['compte']['adresses'] ?? [] as $adresse)
                        <div class="bg-gray-50 rounded-xl p-4 text-gray-700">
                            {{ $adresse['numero'] ?? '' }} {{ $adresse['rue'] }},
                            {{ $adresse['ville'] }},
                            {{ $adresse['province'] }},
                            {{ $adresse['code_postal'] }}
                            <span class="text-sm text-gray-500">
                                ({{ $adresse['type'] ?? '' }})
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-400">Aucune adresse</p>
                    @endforelse
                </div>
            </div>

            <!-- Phones -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Téléphones</h3>

                <div class="space-y-2">
                    @forelse($user['compte']['telephones'] ?? [] as $telephone)
                        <div class="bg-gray-50 rounded-xl p-4 text-gray-700">
                            {{ $telephone['numero'] }}
                        </div>
                    @empty
                        <p class="text-gray-400">Aucun téléphone</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
