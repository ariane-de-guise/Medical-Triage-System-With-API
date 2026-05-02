<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center justify-between bg-white px-4 py-2">
                    <a href="{{ route('telephones.create', $compte_id) }}"
                       class="bg-teal-500 hover:bg-teal-600 text-white text-sm px-4 py-2 rounded-md whitespace-nowrap transition">
                        Ajouter un numéro de téléphone au compte
                    </a>
                    <form method="GET" id="filter-form" action="{{ route('telephones.index', $compte_id) }}">
                        <input
                            type="date"
                            name="created_at"
                            placeholder="Ajouté après"
                            class="w-55 px-2 py-1.5 text-sm border rounded-md focus:ring-1 focus:ring-teal-300 focus:outline-none">
                        <input
                            type="text"
                            name="contains"
                            placeholder="Présent dans le numéro"
                            class="w-38 px-2 py-1.5 text-sm border rounded-md focus:ring-1 focus:ring-teal-300 focus:outline-none">
                        <button type="submit"
                                class="bg-teal-500 hover:bg-teal-600 text-white text-sm px-4 py-2 rounded-md transition">
                            Filtrer
                        </button>
                    </form>
                </div>
            </div>
            <br>
            <div class="bg-teal-100 p-6 rounded shadow">
                @foreach(collect($telephones) as $telephone)
                    <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-semibold text-lg text-gray-800">
                                {{ $telephone['numero'] }}
                            </h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <form method="GET"
                                  action="{{ route('telephones.show', ['compte_id' => $compte_id, 'id' => $telephone['id']]) }}">
                                <button type="submit"
                                        class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                                    Voir les détails
                                </button>
                            </form>
                        </div>
                    </div>
                    <br>
                @endforeach
            </div>
            <form method="GET" action="{{ route('admin') }}">
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                    Retour au tableau de bord
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
