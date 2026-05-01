<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center justify-between bg-white px-4 py-2">
                    <a href="{{ route('visites.create', $compte_id) }}"
                       class="bg-teal-500 hover:bg-teal-600 text-white text-sm px-4 py-2 rounded-md whitespace-nowrap transition">
                        Ajouter une visite au patient
                    </a>
                    <form method="GET" action="{{ route('visites.index', $compte_id) }}">
                        <input
                            type="date"
                            name="date_heure"
                            placeholder="Date de la visite"
                            class="w-28 px-2 py-1.5 text-sm border rounded-md focus:ring-1 focus:ring-teal-300 focus:outline-none">
                        <select
                            name="docteur_id"
                            class="w-36 px-2 py-1.5 text-sm border rounded-md focus:ring-1 focus:ring-teal-300 focus:outline-none">
                            <option value="">Docteur</option>
                            @foreach(collect($docteurs) as $docteur)
                                <option value="{{ $docteur['id'] }}">{{ $docteur['compte']['prenom'] }} {{ $docteur['compte']['nom'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit"
                                class="bg-teal-500 hover:bg-teal-600 text-white text-sm px-4 py-2 rounded-md transition">
                            Filtrer
                        </button>
                    </form>
                </div>
            </div>
            <br>
            <div class="bg-teal-100 p-6 rounded shadow">
                @foreach(collect($visites)->sortByDesc('date_heure') as $visite)
                    <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-semibold text-lg text-gray-800">
                                Visite du {{ $visite['date_heure'] }}
                            </h4>
                            <span class="text-sm px-3 py-1 rounded-full bg-teal-100 text-teal-700">
                                Priorité {{ $visite['diagnostique']['priorite'] }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <p><span class="font-semibold">👨‍⚕️ Docteur :</span><br>
                                    {{ $visite['docteur']['prenom'] }} {{ $visite['docteur']['nom'] }}
                                </p>
                            </div>
                            <div>
                                <p class="mt-2 text-xs text-gray-500">
                                    Diagnostiqué le {{ $visite['diagnostique']['date_heure'] }}
                                </p>
                                <form method="GET" action="{{ route('visites.show', ['compte_id' => $compte_id, 'id' => $visite['id']]) }}">
                                    <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                                        Voir les détails
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br>
                @endforeach
            </div>
            <form method="GET" action="{{ route('admin') }}">
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                    Retour au tableau de bord
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
