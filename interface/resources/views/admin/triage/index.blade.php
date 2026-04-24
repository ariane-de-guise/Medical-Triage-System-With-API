<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center justify-between bg-white px-4 py-2">
                    <a href="{{ route('triages.create') }}"
                       class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-md whitespace-nowrap transition">
                        Ajouter un patient à la file
                    </a>
                    <form method="GET" action="{{ route('triages.index') }}">
                        <input
                            type="number"
                            name="priorite"
                            placeholder="Priorité"
                            class="w-28 px-2 py-1.5 text-sm border rounded-md focus:ring-1 focus:ring-purple-300 focus:outline-none">
                        <select
                            name="file_attente"
                            class="w-36 px-2 py-1.5 text-sm border rounded-md focus:ring-1 focus:ring-purple-300 focus:outline-none">
                            <option value="">File d'attente</option>
                            <option value="medecin">Médecin</option>
                            <option value="infirmier">Infirmier</option>
                        </select>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-md transition">
                            Filtrer
                        </button>
                    </form>
                </div>
            </div>
            <br>
            <div class="bg-blue-100 p-6 rounded shadow">
                @foreach(collect($triages) as $triage)
                    <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-semibold text-lg text-gray-800">
                                {{ $triage['compte']['prenom'] }} {{ $triage['compte']['nom'] }}
                            </h4>
                            <div class="gap-2 flex">
                                <span class="text-sm px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                                    Priorité {{ $triage['priority'] }}
                                </span>
                                <span class="text-sm px-3 py-1 rounded-full bg-blue-200 text-blue-700">
                                    {{ $triage['file_attente'] }}
                                </span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-700">
                            <p><span class="font-semibold">Personnel en charge :</span><br>
                                {{ $triage['personnel']['compte']['prenom'] }} {{ $triage['personnel']['compte']['nom'] }}
                            </p>
                            <p class="mt-2">
                                <span class="font-semibold">En attente depuis {{ (int) (now()->diffInHours($triage['created_at'], true)) }} heure(s)</span>
                            </p>
                            <form method="GET" action="{{ route('triages.show', $triage['id']) }}">
                                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-500 text-white rounded">
                                    Voir les détails
                                </button>
                            </form>
                        </div>
                    </div>
                    <br>
                @endforeach
            </div>
            <form method="GET" action="{{ route('admin') }}">
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-500 text-white rounded">
                    Retour au tableau de bord
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
