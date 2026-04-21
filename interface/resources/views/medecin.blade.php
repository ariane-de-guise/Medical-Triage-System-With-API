<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Médecin
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-green-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Espace Médecin</h3>
                @if ($loggedIn)
                    <form method="POST" action="{{ route('medecin.logout') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-green-500 text-white rounded">
                            Terminer la consultation de patients
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('medecin.login') }}">
                        @csrf
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-green-500 text-white rounded">
                            Prêt à consulter
                        </button>
                    </form>
                @endif
                @if(session('success'))
                    <div class="bg-green-200 text-green-800 p-2 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-200 text-red-800 p-2 rounded">
                        {{ session('error') }}
                    </div>
                @endif
                <hr class="my-6 border-gray-300">
                <h2 class="text-md font-medium mb-2">Patients dans la file</h2>
                @if($count === 0)
                    <p>Aucun patient dans la file</p>
                @else
                    <div class="space-y-6">
                        @for($i = 0; $i < $count; $i++)
                            <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">

                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="font-semibold text-lg text-gray-800">
                                        {{ $patients[$i]['prenom'] }} {{ $patients[$i]['nom'] }}
                                    </h4>
                                    <span class="text-sm px-3 py-1 rounded-full bg-green-100 text-green-700">
                                        Priorité {{ $patients[$i]['triage']['priority'] }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">

                                    <div>
                                        <p><span class="font-semibold">Date de début de la visite :</span><br>
                                            {{ $patients[$i]['latest_visite']['date_heure'] }}
                                        </p>
                                        @if ($i === 0)
                                            <form method="GET" action="{{ route('medecin.show') }}">
                                                @csrf
                                                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-green-100 text-green-700 rounded">
                                                    Prendre en charge
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <div>
                                        <p>
                                            <span class="font-semibold">💊🩺 Commentaires de l'infirmière :</span><br>
                                            {{ $patients[$i]['latest_visite']['diagnostique']['commentaire'] }}
                                        </p>

                                        <p class="mt-2 text-xs text-gray-500">
                                            Attends depuis {{ (int) (now()->diffInMinutes($patients[$i]['latest_visite']['date_heure'], true)) }} minutes
                                        </p>
                                    </div>

                                </div>

                            </div>
                        @endfor
                    </div>
                @endif
                <hr class="my-6 border-gray-300">
                <a href="{{ route('dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-green-500 text-white rounded">Retour au tableau de bord</a>
            </div>
        </div>
    </div>
</x-app-layout>
