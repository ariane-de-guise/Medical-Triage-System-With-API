<x-app-layout>
    <div class="max-w-md mx-auto">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center">
            S'inscrire pour accéder à votre espace personnel
        </h2>

        <!-- Card -->
        <div class="bg-white p-6 rounded-2xl shadow-md">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm text-gray-600 mb-1">
                        Courriel
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                        placeholder="you@example.com"
                    />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm text-gray-600 mb-1">
                        Mot de passe
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                        placeholder="••••••••"
                    />
                </div>

                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm text-gray-600 mb-1">
                        Nom
                    </label>
                    <input
                        id="nom"
                        name="nom"
                        type="text"
                        value="{{ old('nom') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                        placeholder="Doe"
                    />
                </div>

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm text-gray-600 mb-1">
                        Prénom
                    </label>
                    <input
                        id="prenom"
                        name="prenom"
                        type="text"
                        value="{{ old('prenom') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                        placeholder="John"
                    />
                </div>

                <!-- Date de naissance -->
                <div>
                    <label for="date_naissance" class="block text-sm text-gray-600 mb-1">
                        Date de naissance
                    </label>
                    <input
                        id="date_naissance"
                        name="date_naissance"
                        type="date"
                        value="{{ old('date_naissance') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                    />
                </div>

                <!-- Numéro civique -->
                <div>
                    <label for="numero" class="block text-sm text-gray-600 mb-1">
                        Numéro civique
                    </label>
                    <input
                        id="numero"
                        name="numero"
                        type="number"
                        value="{{ old('numero') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                    />
                </div>

                <!-- Rue -->
                <div>
                    <label for="rue" class="block text-sm text-gray-600 mb-1">
                        Rue
                    </label>
                    <input
                        id="rue"
                        name="rue"
                        type="text"
                        value="{{ old('rue') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                    />
                </div>

                <!-- Ville -->
                <div>
                    <label for="ville" class="block text-sm text-gray-600 mb-1">
                        Ville
                    </label>
                    <input
                        id="ville"
                        name="ville"
                        type="text"
                        value="{{ old('ville') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                    />
                </div>

                <!-- Province -->
                <div>
                    <label for="province" class="block text-sm text-gray-600 mb-1">
                        Province
                    </label>
                    <select
                        id="province"
                        name="province"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                    >
                        <option value="">Choisissez une province</option>
                        @foreach($provinces as $code)
                            <option value="{{ $code }}"
                                {{ old('province') == $code ? 'selected' : '' }}>
                                {{ $code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Code postal -->
                <div>
                    <label for="code_postal" class="block text-sm text-gray-600 mb-1">
                        Code postal
                    </label>
                    <input
                        id="code_postal"
                        name="code_postal"
                        type="text"
                        value="{{ old('code_postal') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                    />
                </div>

                <!-- Type de l'adresse -->
                <div>
                    <label for="type" class="block text-sm text-gray-600 mb-1">
                        Type de votre adresse
                    </label>
                    <select
                        id="type"
                        name="type"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                    >
                        <option value="">Choisissez un type</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}"
                                {{ old('type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-sm text-gray-600 mb-1">
                        Téléphone
                    </label>
                    <input
                        id="telephone"
                        name="telephone"
                        type="number"
                        value="{{ old('telephone') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition"
                    />
                </div>

                <!-- Button -->
                <div>
                    <button
                        type="submit"
                        class="w-full bg-purple-500 text-white py-2 rounded-xl hover:bg-purple-600 transition shadow-sm font-medium"
                    >
                        S'incrire
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
