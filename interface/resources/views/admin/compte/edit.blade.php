<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('comptes.update') }}">
                @csrf
                @method('PUT')
                <div>
                    <input type="hidden" name="compte_id" value="{{ $compte['id'] }}">
                    <label for="prenom" class="block text-sm text-gray-600 mb-1">
                        Prénom
                    </label>
                    <input type="text" id="prenom" name="prenom" value="{{ $compte['prenom'] }}" class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition">
                    <label for="nom" class="block text-sm text-gray-600 mb-1">
                        Nom
                    </label>
                    <input type="text" id="nom" name="nom" value="{{ $compte['nom'] }}" class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition">
                    <label for="date_naissance" class="block text-sm text-gray-600 mb-1">
                        Date de naissance
                    </label>
                    <input type="date" id="date_naissance" name="date_naissance" value="{{ $compte['date_naissance'] }}" class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition">
                    <label for="add_role" class="block text-sm text-gray-600 mb-1 mt-4">
                        Ajouter un rôle
                    </label>
                    <select
                        id="add_role"
                        name="add_role"
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition">
                        <option value="">Choisissez un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('add_role') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                    <label for="remove_role" class="block text-sm text-gray-600 mb-1 mt-4">
                        Retirer un rôle
                    </label>
                    <select
                        id="remove_role"
                        name="remove_role"
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition">
                        <option value="">Choisissez un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('remove_role') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-200 text-blue-700 rounded">
                    Modifier le compte
                </button>
            </form>
        </div>
    </div>
    <form method="GET" action="{{ route('comptes.index') }}">
        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-500 text-white rounded">
            Retour au tableau de bord
        </button>
    </form>
</x-app-layout>
