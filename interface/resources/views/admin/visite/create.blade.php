<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('triages.store') }}">
                @csrf
                <div>
                    <label for="compte_id" class="block text-sm text-gray-600 mb-1">
                        Patient
                    </label>
                    <select
                        id="compte_id"
                        name="compte_id"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                        <option value="">Choisissez un patient</option>
                        @foreach($comptes as $compte)
                            <option value="{{ $compte['id'] }}"
                                {{ old('compte') == $compte['id'] ? 'selected' : '' }}>
                                {{ $compte['prenom'] }} {{ $compte['nom'] }}
                            </option>
                        @endforeach
                    </select>
                    <label for="file" class="block text-sm text-gray-600 mb-1 mt-4">
                        File d'attente
                    </label>
                    <select
                        id="file"
                        name="file"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                        <option value="">Choisissez une file</option>
                        @foreach($files as $file)
                            <option value="{{ $file }}" {{ old('file' == $file ? 'selected' : '' )}}>
                                {{ ucfirst($file) }}
                            </option>
                        @endforeach
                    </select>
                    <label for="priorite" class="block text-sm text-gray-600 mb-1">
                        Priorité
                    </label>
                    <input type="number" id="priorite" name="priorite" class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                </div>
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                    Ajouter le patient à la file
                </button>
            </form>
        </div>
    </div>
    <form method="GET" action="{{ route('triages.index') }}">
        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
            Retour au tableau de bord
        </button>
    </form>
</x-app-layout>
