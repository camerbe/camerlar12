<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Str;

new class extends Component
{
    public ?string $openSection = null;
    public string $search = '';
    public ?string $searchDomain = null; // ex: 'moncamer.com' pour restreindre la recherche au site

    /**
     * #[Computed] permet d'utiliser $this->menu (sans parenthèses) dans la vue,
     * avec mise en cache automatique pour la durée de la requête.
     * C'est ça qui corrige l'erreur "Undefined variable $menu".
     */
    #[Computed]
    public function menu(): array
    {
        return [
            'accueil' => [
                'label' => 'Accueil',
                'href' => '/',
                'simple' => true,
            ],
            'actualite' => [
                'label' => 'Actualité',
                'links' => ['Diaspora', 'Économie', 'Politique', 'Réligion', 'Société', 'Sport'],
                'menulink' => [
                    'Diaspora' => '/camerounais-du-monde/diaspora',
                    'Économie' => '/actualites/economie',
                    'Politique' => '/actualites/politique',
                    'Réligion' => '/actualites/religion',
                    'Société' => '/actualites/societe',
                    'Sport' => '/actualites/sport',
                ],
            ],
            'culture' => [
                'label' => 'Culture',
                'links' => ['Art', 'Cinéma', 'Livre', 'Musique'],
                'menulink' => [
                    'Art' => '/culture/art',
                    'Cinéma' => '/culture/cinema',
                    'Livre' => '/culture/livres',
                    'Musique' => '/culture/musique',
                ],
            ],
            'divertissement' => [
                'label' => 'Divertissement',
                'links' => ['Insolite', 'Le saviez-vous', 'People', 'Sans tabou'],
                'menulink' => [
                    'Insolite' => '/actualites/insolite',
                    'Le saviez-vous' => '/fait-curieux/le-saviez-vous',
                    'People' => '/actualites/people',
                    'Sans tabou' => '/libre-parole/sans-tabou',
                ],
            ],
            'international' => [
                'label' => 'International',
                'links' => ['FrançaisCamer', 'Françafrique', 'Géopolitique', 'Panafricanisme'],
                'menulink' => [
                    'FrançaisCamer' => '/frananglais/francaiscamer',
                    'Françafrique' => '/liens-postcoloniaux/francafrique',
                    'Géopolitique' => '/monde-pouvoir/geopolitique',
                    'Panafricanisme' => '/actualites/panafricanisme',
                ],
            ],
            'Libre Voix' => [
                'label' => 'Libre Voix',
                'links' => ['Débat', 'Droit', 'Point de vue'],
                'menulink' => [
                    'Débat' => '/tribune/le-debat',
                    'Droit' => '/droit/point-du-droit',
                    'Point de vue' => '/analyse/point-de-vue',
                ],
            ],
            'sante' => [
                'label' => 'Santé',
                'links' => ['Allo Docteur', 'Santé'],
                'menulink' => [
                    'Allo Docteur' => '/le-coin-sante/allo-docteur',
                    'Santé' => '/actualites/sante',
                ],
            ],
            'videos' => [
                'label' => 'Vidéos',
                'links' => ['Camer', 'Sopie Prod'],
                'menulink' => [
                    'Camer' => '/video/camer',
                    'Sopie Prod' => '/video/sopie',
                ],
            ],
        ];
    }

    /**
     * Retourne l'URL réelle définie dans menulink, ou un slug de secours
     * si aucun lien explicite n'a été renseigné pour cette entrée.
     */
    public function linkFor(string $sectionKey, string $label): string
    {
        $section = $this->menu()[$sectionKey] ?? null;

        return $section['menulink'][$label]
            ?? '/rubrique/' . $this->slug($sectionKey) . '/' . $this->slug($label);
    }

    public function slug(string $label): string
    {
        return Str::slug($label);
    }

    public function toggle(string $section): void
    {
        $this->openSection = $this->openSection === $section ? null : $section;
    }

    public function close(): void
    {
        $this->openSection = null;
    }

    /**
     * Construit l'URL Google Search et redirige l'utilisateur.
     * Restreint la recherche au domaine si $searchDomain est défini (site:domaine).
     */
    public function runSearch()
    {
        $query = trim($this->search);

        if ($query === '') {
            return;
        }

        $q = $this->searchDomain
            ? "site:{$this->searchDomain} {$query}"
            : $query;

        $url = 'https://www.google.com/search?q=' . urlencode($q);

        $this->search = '';
        $this->close();

        return $this->redirect($url, navigate: false);
    }
}; ?>

<div
    x-data
    @keydown.escape.window="$wire.close()"
    @click.outside="$wire.close()"
    class="relative"
>
    <flux:navbar class="border-b border-zinc-200 dark:border-zinc-700">

        @foreach ($this->menu as $key => $section)
            @if (!empty($section['simple']))
                <flux:navbar.item href="{{ $section['href'] }}">
                    {{ $section['label'] }}
                </flux:navbar.item>
            @else
                <flux:navbar.item
                    wire:click="toggle('{{ $key }}')"
                    :icon:trailing="$openSection === $key ? 'chevron-up' : 'chevron-down'"
                >
                    {{ $section['label'] }}
                </flux:navbar.item>
            @endif
        @endforeach

        <flux:spacer />

        {{-- Recherche Google --}}
        <form wire:submit="runSearch" class="flex items-center gap-2">
            <flux:input
                wire:model="search"
                icon="magnifying-glass"
                placeholder="Rechercher sur Google..."
                size="sm"
                class="w-56"
                clearable
            />
            <flux:button type="submit" variant="primary" size="sm" icon="magnifying-glass">
                <span class="hidden sm:inline">Rechercher</span>
            </flux:button>
        </form>
    </flux:navbar>

    {{-- Panneau du mega menu --}}
    @foreach ($this->menu as $key => $section)
        @if (empty($section['simple']))
            <div
                x-show="$wire.openSection === '{{ $key }}'"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="absolute left-0 right-0 top-full z-50 mt-1 rounded-xl border border-zinc-200 bg-white p-6 shadow-xl dark:border-zinc-700 dark:bg-zinc-900"
                style="display: none;"
            >
                <div class="mx-auto grid max-w-5xl gap-6" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
                    @foreach ($section['links'] as $link)
                        <flux:navlist.item
                            href="{{ $this->linkFor($key, $link) }}"
                            wire:click="close"
                            class="rounded-lg px-2 py-1.5 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                        >
                            {{ $link }}
                        </flux:navlist.item>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</div>
