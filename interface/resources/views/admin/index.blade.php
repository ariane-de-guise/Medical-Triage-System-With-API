<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-teal-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Administration générale</h3>
                <form method="GET" action="{{ route('personnels.index') }}">
                    <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                        Personnels
                    </button>
                </form>
                <form method="GET" action="{{ route('triages.index') }}">
                    <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                        Files d'attente
                    </button>
                </form>
                <form method="GET" action="{{ route('comptes.index') }}">
                    <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                        Comptes
                    </button>
                </form>
                <a href="{{ route('dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-teal-500 text-white rounded">Retour
                    au tableau de bord</a>
            </div>
        </div>
    </div>
</x-app-layout>
