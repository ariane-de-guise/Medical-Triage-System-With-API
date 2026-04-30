<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Infirmier
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-teal-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Espace Infirmier</h3>
                @if ($loggedIn)
                    <form method="POST" action="{{ route('infirmier.update') }}">
                        @csrf
                        @method('PATCH')
                        <hr class="my-6 border-gray-300">
                        <h2 class="text-md font-medium mb-2">{{ $patient['prenom'] }} {{ $patient['nom'] }}</h2>
                        <br>
                        <p><span class="font-semibold">Date de début d'entrée dans la file :</span><br>
                            {{ \Carbon\Carbon::parse($patient['triage']['created_at'])->format('d/m/Y H:i') }}
                        </p>
                        <br>
                        <p><span class="font-semibold">Date de naissance :</span><br>
                            {{ $patient['date_naissance'] }}
                        </p>
                        <br>
                        @if ($priorite !== null)
                            <input type="hidden" name="symptomes" value="{{ $symptomes }}">
                            <label for="priorite" class="font-semibold">Priorité :</label><br>
                            <input type="number" name="priorite"
                                   class="text-sm px-3 py-1 rounded-full bg-teal-50 text-teal-700"
                                   value="{{ $priorite['priorite'] }}">
                            <br><br>
                            <label for="commentaire" class="font-semibold">Commentaire :</label><br>
                            <textarea id="commentaire" name="commentaire"
                                      class="mt-1 block w-full bg-teal-50 border-gray-300 rounded-md"
                                      rows="4"></textarea>
                            <br><br>
                            <span class="font-semibold">Afflictions possibles</span>
                            @foreach($priorite['afflictions'] as $affliction)
                                <span class="inline-block mt-1 px-3 py-1 rounded-full bg-teal-100 text-teal-700">
                                    {{ ucfirst($affliction['nom']) }}
                                </span>
                            @endforeach
                        @endif
                        @if ($priorite !== null)
                            <button type="submit"
                                    class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                                Terminer la consultation
                            </button>
                        @endif
                    </form>
                    @if ($priorite == null)
                        <form method="GET" action="{{ route('infirmier.show') }}">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($symptomes as $symptome)
                                    <label class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            id="{{ $symptome }}"
                                            name="symptomes[]"
                                            value="{{ $symptome }}"
                                        />
                                        <span>{{ $symptome }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit"
                                    class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                                Calculer la priorité
                            </button>
                        </form>
                    @endif
                @else
                    <h2 class="text-md font-medium mb-2">Vous n'êtes pas connecté.</h2>
                @endif
                <hr class="my-6 border-gray-300">
                <a href="{{ route('infirmier') }}" class="inline-block mt-4 px-4 py-2 bg-teal-500 text-white rounded">Retour
                    à la file d'attente</a>
            </div>
        </div>
    </div>
</x-app-layout>
