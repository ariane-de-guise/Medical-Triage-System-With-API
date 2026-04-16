<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Patient') }}
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-purple-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Espace Patient</h3>
                @if (\App\Algorithms\TriageQueue::enqueued(auth()->user()))
                    <p>Vous êtes à la position {{ \App\Algorithms\TriageQueue::queuePosition(auth()->user()) }} dans la file.</p>
                    <form method="POST" action="{{ route('patient.dequeue') }}">
                        @csrf
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-purple-500 text-white rounded">
                            Sortir de la file d'attente
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('patient.enqueue') }}">
                        @csrf
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-purple-500 text-white rounded">
                            Je suis à l'hôpital
                        </button>
                    </form>
                @endif
                <hr class="my-6 border-gray-300">
                <h2 class="text-md font-medium mb-2">Visites précédentes</h2>
                @if(auth()->user()->compte->visites()->count() === 0)
                    <p>Aucune visite enregistrée.</p>
                @else
                    <div class="space-y-6">
                        @foreach(auth()->user()->compte->visites()->orderByDesc('date_heure')->get() as $visite)
                            <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">

                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="font-semibold text-lg text-gray-800">
                                        Visite du {{ \Carbon\Carbon::parse($visite->date_heure)->format('d/m/Y H:i') }}
                                    </h4>
                                    <span class="text-sm px-3 py-1 rounded-full bg-purple-100 text-purple-700">
                                        Priorité {{ $visite->diagnostique->priorite }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">

                                    <div>
                                        <p><span class="font-semibold">👨‍⚕️ Docteur :</span><br>
                                            {{ $visite->docteur->prenom }} {{ $visite->docteur->nom }}
                                        </p>

                                        <p class="mt-2">
                                            <span class="font-semibold">🩺 Diagnostic :</span><br>
                                            {{ $visite->diagnostique->commentaire }}
                                        </p>
                                    </div>

                                    <div>
                                        <p>
                                            <span class="font-semibold">💊 Traitement :</span><br>
                                            {{ $visite->diagnostique->description }}
                                        </p>

                                        <p class="mt-2 text-xs text-gray-500">
                                            Diagnostiqué le {{ \Carbon\Carbon::parse($visite->diagnostique->date_heure)->format('d/m/Y H:i') }}
                                        </p>
                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
                <hr class="my-6 border-gray-300">
                <a href="{{ url('/dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-purple-500 text-white rounded">Retour au Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
