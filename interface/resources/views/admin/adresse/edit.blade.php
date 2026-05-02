<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('adresses.update', $compte_id) }}">
                @csrf
                @method('PUT')
                <div>
                    <input type="hidden" name="adresse_id" value="{{ $adresse['id'] }}">
                    <label for="numero" class="font-semibold">Numéro civique :</label><br>
                    <input type="number" id="numero" name="numero" value="{{ $adresse['numero'] }}"
                           class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <br><br>
                    <label for="rue" class="font-semibold">Rue :</label><br>
                    <input type="text" id="rue" name="rue" value="{{ $adresse['rue'] }}"
                           class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <br><br>
                    <label for="ville" class="font-semibold">Ville :</label><br>
                    <input type="text" id="ville" name="ville" value="{{ $adresse['ville'] }}"
                           class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <br><br>
                    <label for="code_postal" class="font-semibold">Code postal :</label><br>
                    <input type="text" id="code_postal" name="code_postal" value="{{ $adresse['code_postal'] }}"
                           class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                    <br><br>
                    <label for="province" class="font-semibold">
                        Province
                    </label>
                    <select
                        id="province"
                        name="province"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                        <option value="">Choisissez une province</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province['name'] }}"
                                {{ $adresse['province'] == $province['name'] ? 'selected' : '' }}>
                                {{ $province['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <br><br>
                    <label for="type" class="font-semibold">
                        Type d'adresse
                    </label>
                    <select
                        id="type"
                        name="type"
                        required
                        class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition">
                        <option value="">Choisissez un type</option>
                        @foreach($types as $type)
                            <option value="{{ $type['name'] }}"
                                {{ $adresse['type'] == $type['name'] ? 'selected' : '' }}>
                                {{ $type['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <br>
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-200 text-teal-700 rounded">
                    Modifier la visite
                </button>
            </form>
        </div>
    </div>
    <form method="GET" action="{{ route('adresses.index', $compte_id) }}">
        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
            Retour au tableau de bord
        </button>
    </form>
</x-app-layout>
