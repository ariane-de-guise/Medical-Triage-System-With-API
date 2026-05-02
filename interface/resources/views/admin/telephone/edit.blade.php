<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('telephones.update', $compte_id) }}">
                @csrf
                @method('PUT')
                <div>
                    <input type="hidden" name="telephone_id" value="{{ $telephone['id'] }}">
                    <label for="telephone" class="font-semibold">Numéro de téléphone :</label><br>
                    <input type="number" id="telephone" name="telephone" value="{{ $telephone['numero'] }}"
                           class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                </div>
                <br>
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                    Modifier le numéro de téléphone
                </button>
            </form>
        </div>
    </div>
    <form method="GET" action="{{ route('telephones.index', $compte_id) }}">
        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
            Retour au tableau de bord
        </button>
    </form>
</x-app-layout>
