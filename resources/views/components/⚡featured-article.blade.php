<?php

use Livewire\Component;

new class extends Component
{

    //
};
?>
@props(['article'])
<article class="relative group rounded-2xl overflow-hidden bg-gray-900 text-white shadow-xl aspect-[16/9] lg:aspect-[21/9]">
    <!-- Background Image avec gradient SEO & contraste -->
    <img
        src="{{ $article['image_url'] ?? 'https://picsum.photos/1200/600' }}"
        alt="{{ $article['titre'] }}"
        loading="eager"
        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out opacity-80"
    >
    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent"></div>

    <!-- Badge Breaking -->
    <div class="absolute top-4 left-4 sm:top-6 sm:left-6 z-10 flex space-x-2">
        <span class="bg-brand-500 text-white font-extrabold text-xs px-3 py-1 uppercase rounded-full tracking-wider animate-pulse shadow">
            À LA UNE
        </span>
        <span class="bg-accent-500 text-white font-bold text-xs px-3 py-1 uppercase rounded-full shadow">
            {{ $article['sousrubrique']['sousrubrique'] ?? 'Économie' }}
        </span>
    </div>

    <!-- Contenu Textuel -->
    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 md:p-10 z-10">
        <div class="max-w-3xl">
            <div class="flex items-center space-x-3 text-xs text-gray-300 mb-2 font-medium">
                <span>Par {{ $article['auteur'] ?? 'Paul Biya' }}</span>
                <span>•</span>
                <span>{{ $article['dateparution'] ?? 'Il y a 30 min' }}</span>
            </div>

            <h1 class="text-2xl sm:text-4xl md:text-5xl font-extrabold font-heading text-white group-hover:text-highlight-500 transition leading-tight">
                <a href="{{ $article['url'] ?? '#' }}" class="focus:outline-none">
                    {{ $article['titre']}}
                </a>
            </h1>

            <p class="hidden sm:block text-sm md:text-base text-gray-200 mt-3 line-clamp-2">
                {{ $article['chapeau'] ?? 'Aperçu détaillé du contenu de l\'article à la une pour capter immédiatement l\'attention des lecteurs.' }}
            </p>
        </div>
    </div>
</article>
