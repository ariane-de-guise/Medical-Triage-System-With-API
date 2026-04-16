<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Bienvenue, {{ auth()->user()->compte->nom }}, {{auth()->user()->compte->prenom}}</h3>

                <div class="space-y-2">
                    @role('admin')
                    <a href="{{ url('/admin') }}" class="block px-4 py-2 bg-blue-500 dark:text-black rounded">Espace Admin</a>
                    @endrole

                    @role('medecin')
                    <a href="{{ url('/medecin') }}" class="block px-4 py-2 bg-green-500 dark:text-black rounded">Espace Médecin</a>
                    @endrole

                    @role('infirmier')
                    <a href="{{ url('/infirmier') }}" class="block px-4 py-2 bg-yellow-500 dark:text-black rounded">Espace Infirmier</a>
                    @endrole

                    @role('patient')
                    <a href="{{ url('/patient') }}" class="block px-4 py-2 bg-purple-500 dark:text-black rounded">Espace Patient</a>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
