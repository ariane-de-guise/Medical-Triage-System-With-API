<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('personnels.store') }}">
                @csrf
                <div>
                    <label for="compte_id" class="block text-sm text-gray-600 mb-1">
                        Employé
                    </label>
                    <select
                        id="compte_id"
                        name="compte_id"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                        <option value="">Choisissez un employé</option>
                        @foreach($personnels as $personnel)
                            <option value="{{ $personnel['id'] }}"
                                {{ old('personnel') == $personnel['id'] ? 'selected' : '' }}>
                                {{ $personnel['prenom'] }} {{ $personnel['nom'] }}
                            </option>
                        @endforeach
                    </select>
                    <label for="role" class="block text-sm text-gray-600 mb-1 mt-4">
                        Rôle
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                        <option value="">Choisissez un rôle</option>
                        <option value="medecin" {{ old('role') == 'medecin' ? 'selected' : '' }}>Médecin</option>
                        <option value="infirmier" {{ old('role') == 'infirmier' ? 'selected' : '' }}>Infirmier</option>
                    </select>
                </div>
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                    Connecter l'employé
                </button>
            </form>
        </div>
    </div>
    <form method="GET" action="{{ route('personnels.index') }}">
        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
            Retour au tableau de bord
        </button>
    </form>
</x-app-layout>
