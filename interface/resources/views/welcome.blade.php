<x-app-layout>
    <main class="w-full lg:max-w-4xl max-w-[335px] flex flex-col  gap-12 ">
        <div class="w-full lg:max-w-4xl max-w-[335px]">
            <div class="text-3xl lg:text-5xl font-bold mb-8 py-6 text-center">
                <h1>
                    Bienvenue sur le Centre de triage des hopitaux du Québec !
                </h1>
            </div>

            <div class="flex justify-center">
                <img
                    src="{{ asset('logo/CTHQ.png') }}"
                    alt="CTHQ"
                    class="w-1/2 justify-content-center rounded-lg mb-6"
                />
            </div>
            <p class="text-center text-gray-600 dark:text-gray-400 mb-6">
                Centre de triage des hopitaux du Québec, une meilleure gestion de la priorité des patients !
            </p>
        </div>

        <div class="flex flex-col gap-8  w-full ">
            <h1 class="text-3xl font-semibold">Liste de ressources</h1>
            @foreach($ressources as $categorie)
                <div class="flex flex-col gap-1.5 ">
                    <h2 class="text-2xl mb-2">{{$categorie["nom"]}}</h2>
                    @foreach($categorie["items"] as $ressource)
                        <div class="flex flex-col gap-1.5 border dark:border-gray-500 rounded-lg p-4">
                            <h3 class="text-xl">{{$ressource["nom"]}}</h3>
                            @if($ressource["site"] == "Variable")
                                <span><span class="font-semibold">Site Web : </span>Variable</span>
                            @else
                                <a href={{$ressource["site"]}}><span class="font-semibold">Site Web : </span><span class="underline">{{$ressource["site"]}}</span></a>
                            @endif
                            <p><span class="font-semibold">Numéro de téléphone : </span>{{$ressource["tel"]}}</p>
                            <p><span class="font-semibold">Description : </span>{{$ressource["desc"]}}</p>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </main>
</x-app-layout>
