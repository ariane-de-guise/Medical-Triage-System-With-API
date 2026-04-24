<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Bienvenue, {{ $user['compte']['prenom'] }} {{ $user['compte']['nom'] }}</h3>

                <div class="space-y-2">
                    @if ($roles && in_array('admin', $roles))
                        <a href="{{ route('admin') }}" class="block px-4 py-2 bg-blue-500 dark:text-black rounded">Espace Admin</a>
                    @endif

                    @if ($roles && in_array('medecin', $roles))
                        <a href="{{ route('medecin') }}" class="block px-4 py-2 bg-green-500 dark:text-black rounded">Espace Médecin</a>
                    @endif

                    @if ($roles && in_array('infirmier', $roles))
                        <a href="{{ url('/infirmier') }}" class="block px-4 py-2 bg-yellow-500 dark:text-black rounded">Espace Infirmier</a>
                    @endif

                    @if ($roles && in_array('patient', $roles))
                        <a href="{{ route('patient') }}" class="block px-4 py-2 bg-purple-500 dark:text-black rounded">Espace Patient</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
