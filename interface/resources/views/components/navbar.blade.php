<nav class="bg-white/80 backdrop-blur-md shadow-sm px-6 py-4 flex justify-between items-center">

    <!-- Logo -->
    <div class="text-2xl font-bold text-purple-600">
        <a href="{{ route('dashboard') }}">
            🏥 Système de triage médical <!-- À chnager plus tard -->
        </a>
    </div>

    <!-- Right -->
    <div class="flex items-center space-x-4">

        @if(!session('user'))

            <a href="{{ route('login') }}"
               class="text-gray-600 hover:text-purple-500 transition">
                Connexion
            </a>

            <a href="{{ route('register') }}"
               class="bg-purple-500 text-white px-4 py-2 rounded-2xl hover:bg-purple-600 transition shadow-md">
                S'enregistrer
            </a>

        @else
            <a href="{{ route('profile') }}"
               class="bg-gray-100 px-4 py-2 rounded-2xl hover:bg-gray-200 transition">
                Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-400 hover:text-red-600 transition">
                    Déconnexion
                </button>
            </form>

        @endif

    </div>
</nav>
