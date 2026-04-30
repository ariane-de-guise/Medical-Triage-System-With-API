<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('comptes.store') }}">
                @csrf
                <div>
                    <label for="prenom" class="block text-sm text-gray-600 mb-1">
                        Prénom
                    </label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <label for="nom" class="block text-sm text-gray-600 mb-1">
                        Nom
                    </label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <label for="date_naissance" class="block text-sm text-gray-600 mb-1">
                        Date de naissance
                    </label>
                    <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <label for="email" class="block text-sm text-gray-600 mb-1">
                        Courriel
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <label for="password" class="block text-sm text-gray-600 mb-1">
                        Mot de passe
                    </label>
                    <input type="password" id="password" name="password" value="{{ old('password') }}" class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <label for="role" class="block text-sm text-gray-600 mb-1 mt-4">
                        Rôle
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                        <option value="">Choisissez un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                    Créer le compte
                </button>
            </form>
        </div>
    </div>
    <form method="GET" action="{{ route('comptes.index') }}">
        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
            Retour au tableau de bord
        </button>
    </form>
</x-app-layout>
