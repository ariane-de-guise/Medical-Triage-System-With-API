<x-app-layout>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Espace Admin
        </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-blue-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Administration générale</h3>
                <form method="GET" action="{{ route('personnels.index') }}">
                    <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-200 text-blue-700 rounded">
                        Personnels
                    </button>
                </form>
                <form method="GET" action="{{ route('triages.index') }}">
                    <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-200 text-blue-700 rounded">
                        Files de triage
                    </button>
                </form>
                <a href="{{ route('dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-blue-500 text-white rounded">Retour au Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
