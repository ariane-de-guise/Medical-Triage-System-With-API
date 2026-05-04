<x-app-layout>
    <div class="max-w-md mx-auto">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center">
            Re-bienvenue !
        </h2>
        <div class="bg-white p-6 rounded-2xl shadow-md">
            @if($errors->has('api'))
                <div class="text-red-500">
                    {{ $errors->first('api') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm text-gray-600 mb-1">
                        Courriel
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition"
                        placeholder="you@example.com"
                    />
                </div>
                <div>
                    <label for="password" class="block text-sm text-gray-600 mb-1">
                        Mot de passe
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent transition"
                        placeholder="••••••••"
                    />
                </div>
                <div>
                    <button
                        type="submit"
                        class="w-full bg-teal-500 text-white py-2 rounded-xl hover:bg-teal-600 transition shadow-sm font-medium"
                    >
                        Se connecter
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
