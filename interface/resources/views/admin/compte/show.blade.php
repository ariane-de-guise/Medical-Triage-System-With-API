<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Admin
    </h2>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-blue-100 p-6 rounded shadow">
                <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-semibold text-lg text-gray-800">
                            {{ $compte['prenom'] }} {{ $compte['nom'] }}
                        </h4>
                        <span class="text-sm px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                            {{ $compte['user']['email'] }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <p><span class="font-semibold">Date de naissance :</span><br>
                            {{ $compte['date_naissance'] }}
                        </p>
                        <p><span class="font-semibold">Rôles :</span><br>
                        @foreach($roles as $role)
                                {{ $role }}
                        @endforeach
                        </p>
                        <form method="POST" action="{{ route('comptes.destroy', $compte['id']) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-500 text-white rounded">
                                Supprimer le compte de {{ $compte['prenom'] }}
                            </button>
                        </form>
                        <form method="GET" action="{{ route('comptes.edit', $compte['id']) }}">
                            <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-500 text-white rounded">
                                Modifier le compte de {{ $compte['prenom'] }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('comptes.index') }}">
                <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-blue-500 text-white rounded">
                    Retour au tableau de bord
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
<?php
