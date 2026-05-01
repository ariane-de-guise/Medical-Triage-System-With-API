<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-teal-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Espace Patient</h3>
                @if ($position > -1)
                    <p>Vous êtes à la position {{ $position }} dans la file.</p>
                    <form method="POST" action="{{ route('dequeue') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                            Sortir de la file d'attente
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('enqueue') }}">
                        @csrf
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                            Je suis à l'hôpital
                        </button>
                    </form>
                @endif
                <hr class="my-6 border-gray-300">
                <h2 class="text-md font-medium mb-2">Visites précédentes</h2>
                @if(empty($user['compte']['visites']))
                    <p>Aucune visite enregistrée.</p>
                @else
                    <div class="space-y-6">
                        @foreach(collect($user['compte']['visites'] ?? [])->sortByDesc('date_heure') as $visite)
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

                                        <p class="mt-2">
                                            <span class="font-semibold">🩺 Commentaires de l'infirmier :</span><br>
                                            {{ $visite['diagnostique']['commentaire'] }}
                                        </p>
                                    </div>

                                    <div>
                                        <p>
                                            <span class="font-semibold">💊 Notes du docteur :</span><br>
                                            {{ $visite['diagnostique']['description'] }}
                                        </p>

                                        <p class="mt-2 text-xs text-gray-500">
                                            Diagnostiqué le {{ $visite['diagnostique']['date_heure'] }}
                                        </p>
                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
                <hr class="my-6 border-gray-300">
                <a href="{{ route('dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-teal-500 text-white rounded">Retour au tableau de bord</a>
            </div>
        </div>
    </div>
</x-app-layout>
