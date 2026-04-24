<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                <div class="flex items-center justify-between bg-white px-4 py-2">
                    <a href="{{ route('personnels.create') }}"
                       class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-md whitespace-nowrap transition">
                        Connecter un employé
                    </a>
                    <form method="GET" action="{{ route('personnels.index') }}">
                        <input
                            type="number"
                            name="temps_connexion"
                            placeholder="Heures"
                            class="w-28 px-2 py-1.5 text-sm border rounded-md focus:ring-1 focus:ring-purple-300 focus:outline-none">
                        <select
                            name="role"
                            class="w-36 px-2 py-1.5 text-sm border rounded-md focus:ring-1 focus:ring-purple-300 focus:outline-none">
                            <option value="">Rôle</option>
                            <option value="medecin">Médecin</option>
                            <option value="infirmier">Infirmier</option>
                        </select>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-md transition">
                            Filtrer
                        </button>
                    </form>
                </div>
            </div>
            <br>
            <div class="bg-blue-100 p-6 rounded shadow">
                @foreach(collect($personnels) as $personnel)
                    <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-semibold text-lg text-gray-800">
                                {{ $personnel['compte']['prenom'] }} {{ $personnel['compte']['nom'] }}
                            </h4>
                            <span class="text-sm px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                                {{ ucfirst($personnel['role']) }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-700">
                            <p><span class="font-semibold">Courriel :</span><br>
                                {{ $personnel['compte']['user']['email'] }}
                            </p>
                            <p class="mt-2">
                                <span class="font-semibold">Connecté depuis {{ (int) (now()->diffInHours($personnel['created_at'], true)) }} heure(s)</span>
                            </p>
                            <form method="GET" action="{{ route('personnels.show', $personnel['id']) }}">
                                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-500 text-white rounded">
                                    Voir les détails
                                </button>
                            </form>
                        </div>
                    </div>
                    <br>
                @endforeach
            </div>
            <form method="GET" action="{{ route('admin') }}">
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-500 text-white rounded">
                    Retour au tableau de bord
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
