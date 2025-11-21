<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h1 class="mb-2 text-4xl font-bold text-gray-800 dark:text-gray-200">
                👥 Participantes da Mega-Sena
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Veja quem já fez suas escolhas!
            </p>
        </div>

        <!-- Componente de Lista de Participantes -->
        <livewire:participants-list />
    </div>
</x-app-layout>
