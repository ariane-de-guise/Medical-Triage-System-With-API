<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('visites.update', $compte_id) }}">
                @csrf
                @method('PUT')
                <div>
                    <input type="hidden" name="visite_id" value="{{ $visite['id'] }}">
                    <label for="docteur_id" class="block text-sm text-gray-600 mb-1">
                        Docteur
                    </label>
                    <select
                        id="docteur_id"
                        name="docteur_id"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                        <option value="">Choisissez un docteur</option>
                        @foreach($docteurs as $docteur)
                            <option value="{{ $docteur['id'] }}"
                                {{ $visite['docteur_id'] == $docteur['id'] ? 'selected' : '' }}>
                                {{ $docteur['compte']['prenom'] }} {{ $docteur['compte']['nom'] }}
                            </option>
                        @endforeach
                    </select>
                    <label for="priorite" class="block text-sm text-gray-600 mb-1">
                        Priorité
                    </label>
                    <select
                        id="priorite"
                        name="priorite"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                        <option value="">Choisissez une priorité</option>
                        @foreach($priorites as $priorite)
                            <option value="{{ $priorite }}"
                                {{ $visite['diagnostique']['priorite'] == $priorite ? 'selected' : '' }}>
                                {{ $priorite }}
                            </option>
                        @endforeach
                    </select>
                    <label for="visite_date_heure" class="block text-sm text-gray-600 mb-1">
                        Date du début de la visite
                    </label>
                    <input type="datetime-local" id="visite_date_heure" name="visite_date_heure"
                           value="{{ $visite['date_heure'] }}"
                           class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <label for="diagnostique_date_heure" class="block text-sm text-gray-600 mb-1">
                        Date de fin de la visite
                    </label>
                    <input type="datetime-local" id="diagnostique_date_heure" name="diagnostique_date_heure"
                           value="{{ $visite['diagnostique']['date_heure'] }}"
                           class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <label for="commentaire" class="font-semibold">Commentaires de l'infirmier :</label><br>
                    <textarea id="commentaire" name="commentaire"
                              class="mt-1 block w-full bg-teal-50 border-gray-300 rounded-md"
                              rows="4">{{ $visite['diagnostique']['commentaire'] }}</textarea>
                    <label for="description" class="font-semibold">Notes du docteur :</label><br>
                    <textarea id="description" name="description"
                              class="mt-1 block w-full bg-teal-50 border-gray-300 rounded-md"
                              rows="4">{{ $visite['diagnostique']['description'] }}</textarea>
                </div>
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                    Modifier la visite
                </button>
            </form>
        </div>
    </div>
    <form method="GET" action="{{ route('visites.index', $compte_id) }}">
        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
            Retour au tableau de bord
        </button>
    </form>
</x-app-layout>
