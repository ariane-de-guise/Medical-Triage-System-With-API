<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('triages.update') }}">
                @csrf
                @method('PUT')
                <div>
                    <input type="hidden" name="triage_id" value="{{ $triage['id'] }}">
                    <label for="compte_id" class="block text-sm text-gray-600 mb-1">
                        Employé
                    </label>
                    <input type="text" id="compte_id" name="compte_id"
                           value="{{ $triage['compte']['prenom'] }} {{ $triage['compte']['nom'] }}" disabled
                           class="w-full px-4 py-2 border rounded-xl bg-gray-100 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
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
                            <option
                                value="{{ $file }}" {{ old('file', $triage['file_attente']) == $file ? 'selected' : '' }}>
                                {{ ucfirst($file) }}
                            </option>
                        @endforeach
                    </select>
                    <label for="priorite" class="block text-sm text-gray-600 mb-1">
                        Priorité
                    </label>
                    <input type="number" id="priorite" name="priorite" value="{{ $triage['priority'] }}"
                           class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                </div>
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                    Modifier la file
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
