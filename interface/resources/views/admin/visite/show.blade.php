<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-teal-100 p-6 rounded shadow">
                <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-semibold text-lg text-gray-800">
                            Visite du {{ $visite['date_heure'] }}
                        </h4>
                        <div class="gap-2 flex">
                            <span class="text-sm px-3 py-1 rounded-full bg-teal-100 text-teal-700">
                                Priorité {{ $visite['diagnostique']['priorite'] }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <p><span class="font-semibold">Docteur assigné :</span><br>
                                {{ $visite['docteur']['prenom'] }} {{ $visite['docteur']['nom'] }}
                            </p>
                            <p class="mt-2">
                                <span class="font-semibold">A attendu {{ (int) (\Carbon\Carbon::parse($visite['diagnostique']['date_heure'])->diffInHours($visite['date_heure'], true)) }} heure(s)</span>
                            </p>
                        </div>
                        <div>
                            <p><span class="font-semibold">Commentaires de l'infirmier :</span><br>
                                {{ $visite['diagnostique']['commentaire'] }}
                            </p>
                            <p><span class="font-semibold">Commentaires du docteur :</span><br>
                                {{ $visite['diagnostique']['description'] }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('visites.destroy', [$compte_id, $visite['id']]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                                Supprimer la visite
                            </button>
                        </form>
                        <form method="GET" action="{{ route('visites.edit', [$compte_id, $visite['id']]) }}">
                            <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                                Modifier les détails de la visite
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('visites.index', $compte_id) }}">
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                    Retour au tableau de bord
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
<?php
