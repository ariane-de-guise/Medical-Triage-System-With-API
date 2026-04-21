<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Médecin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-green-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Espace Médecin</h3>
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
                <form method="POST" action="{{ route('medecin.update') }}">
                    @csrf
                    @method('PATCH')
                    <hr class="my-6 border-gray-300">
                    <h2 class="text-md font-medium mb-2">{{ $patient['prenom'] }} {{ $patient['nom'] }}</h2>
                    <br>
                    <p><span class="font-semibold">Date de début de la visite :</span><br>
                        {{ $patient['latest_visite']['date_heure'] }}
                    </p>
                    <br>
                    <p>
                        <span class="font-semibold">Commentaires de l'infirmière :</span><br>
                        {{ $patient['latest_visite']['diagnostique']['commentaire'] }}
                    </p>
                    <br>
                    <label for="diagnostique" class="mt-4 font-semibold" >Diagnostic</label>
                    <input id="diagnostique" type="text" class="mt-1 block w-full border-gray-300 bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="diagnostique" required />
                    <br>
                    <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-green-500 text-white rounded">
                        Terminer la consultation de ce patient
                    </button>
                </form>
                <hr class="my-6 border-gray-300">
                <a href="{{ route('medecin') }}" class="inline-block mt-4 px-4 py-2 bg-green-500 text-white rounded">Retour à la file d'attente</a>
            </div>
        </div>
    </div>
</x-app-layout>
