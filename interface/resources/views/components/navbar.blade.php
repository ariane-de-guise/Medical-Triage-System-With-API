<nav class="bg-white/80 backdrop-blur-md shadow-sm px-6 py-4 flex justify-between items-center">

    <!-- Logo -->
    <div class="text-2xl font-bold text-black">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('logo/CTHQ.png') }}" alt="CTHQ" class="w-1/20 justify-content-center rounded-lg"/> Système de triage médical
        </a>
    </div>

    <!-- Right -->
    <div class="flex items-center space-x-4">

        @if(!session('user'))

            <a href="{{ route('login') }}"
               class="text-gray-600 hover:text-teal-500 transition">
                Connexion
            </a>

            <a href="{{ route('register') }}"
               class="bg-teal-500 text-white px-4 py-2 rounded-2xl hover:bg-teal-600 transition shadow-md">
                S'enregistrer
            </a>

        @else
            <a href="{{ route('profile') }}"
               class="bg-gray-100 px-4 py-2 rounded-2xl hover:bg-gray-200 transition">
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-teal-600 hover:text-teal-400 transition">
                    Déconnexion
                </button>
            </form>

        @endif

    </div>
</nav>
