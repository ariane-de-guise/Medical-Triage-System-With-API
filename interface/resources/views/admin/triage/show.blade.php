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
                            {{ $triage['compte']['prenom'] }} {{ $triage['compte']['nom'] }}
                        </h4>
                        <div class="gap-2 flex">
                            <span class="text-sm px-3 py-1 rounded-full bg-teal-100 text-teal-700">
                                Priorité {{ $triage['priority'] }}
                            </span>
                            <span class="text-sm px-3 py-1 rounded-full bg-teal-200 text-teal-700">
                                {{ $triage['file_attente'] }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <div>
                            <p><span class="font-semibold">Personnel en charge :</span><br>
                                {{ $triage['personnel']['compte']['prenom'] }} {{ $triage['personnel']['compte']['nom'] }}
                            </p>
                            <p class="mt-2">
                                <span class="font-semibold">En attente depuis {{ (int) (now()->diffInHours($triage['created_at'], true)) }} heure(s)</span>
                            </p>
                        </div>
                        <div>
                            <p><span class="font-semibold">Date de naissance :</span><br>
                                {{ $triage['compte']['date_naissance'] }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('triages.destroy', $triage['id']) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                                Retirer {{ $triage['compte']['prenom'] }} de la file
                            </button>
                        </form>
                        <form method="GET" action="{{ route('triages.edit', $triage['id']) }}">
                            <button type="submit"
                                    class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                                Changer {{ $triage['compte']['prenom'] }} de file
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('triages.index') }}">
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                    Retour au tableau de bord
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
<?php
