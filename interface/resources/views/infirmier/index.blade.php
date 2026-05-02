<x-app-layout>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Espace Infirmier
    </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-teal-100 p-6 rounded shadow">
                <h3 class="text-lg font-medium mb-2">Espace Infirmier</h3>
                @if ($loggedIn)
                    <form method="POST" action="{{ route('infirmier.logout') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                            Terminer la consultation de patients
                        </button>
                    </form>
                    <hr class="my-6 border-gray-300">
                    <h2 class="text-md font-medium mb-2">Patients dans la file</h2>
                    @if($count === 0)
                        <p>Aucun patient dans la file</p>
                    @else
                        <div class="space-y-6">
                            @for($i = 0; $i < $count; $i++)
                                <div class="bg-white shadow-md rounded-xl p-5 border border-gray-200">

                                    <div class="flex justify-between items-center mb-3">
                                        <h4 class="font-semibold text-lg text-gray-800">
                                            {{ $patients[$i]['prenom'] }} {{ $patients[$i]['nom'] }}
                                        </h4>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                        <div>
                                            <p><span class="font-semibold">Date de l'arrivée en file :</span><br>
                                                {{ \Carbon\Carbon::parse($patients[$i]['triage']['created_at'])->format('d/m/Y H:i') }}
                                            </p>
                                            @if ($i === 0)
                                                <form method="GET" action="{{ route('infirmier.show') }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-100 text-teal-700 rounded">
                                                        Prendre en charge
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="mt-2 text-xs text-gray-500">
                                                Attends
                                                depuis {{ (int) (now()->diffInMinutes($patients[$i]['triage']['created_at'], true)) }}
                                                minutes
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    @endif
                @else
                    <form method="POST" action="{{ route('infirmier.login') }}">
                        @csrf
                        <button type="submit" class="inline-block mt-4 mb-2 px-4 py-2 bg-teal-500 text-white rounded">
                            Prêt à consulter
                        </button>
                    </form>
                @endif
                <hr class="my-6 border-gray-300">
                <a href="{{ route('dashboard') }}" class="inline-block mt-4 px-4 py-2 bg-teal-500 text-white rounded">Retour
                    au tableau de bord</a>
            </div>
        </div>
    </div>
</x-app-layout>
