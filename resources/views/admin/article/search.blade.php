<x-layouts.app-dashboard :stat="$stat">
    <flux:card class="w-full mx-auto">
        <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-5 mb-5">
            <div class="flex">
                <flux:icon name="squares-2x2" class="size-7 mr-2" />
                <flux:heading size="xl"  class="font-bold">Articles</flux:heading>

            </div>

            <a href="{{route('admin.article.create')}}" class="bg-green-700 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Nouveau</span>
            </a>


        </div>
        <flux:table bleed container:class="mt-6" >
            <flux:table.columns sticky >
                <flux:table.column>#</flux:table.column>
                <flux:table.column>Titre</flux:table.column>
                <flux:table.column>Rubrique</flux:table.column>
                <flux:table.column>Date</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @foreach($articles as $article)
                    <flux:table.row :key="$article->idarticle">
                        <flux:table.cell variant="strong">{{ $loop->index+ 1}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ Str::limit($article->titre,40)  }}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ $article->sousrubrique->sousrubrique}}</flux:table.cell>
                        <flux:table.cell variant="strong">{{ \Carbon\Carbon::parse($article->dateparution)->locale('fr')->translatedFormat('d M Y H:i')  }}</flux:table.cell>
                        <flux:table.cell variant="strong">
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="pencil-square" :href="route('admin.article.edit',$article->idarticle )">
                                    Éditer
                                </flux:button>

                                <flux:button
                                    variant="danger"
                                    size="sm"
                                    icon="trash"
                                    class="text-red-600 hover:text-red-800"
                                    wire:click="delete({{ $article->idarticle }})"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cet article ?"
                                >

                                </flux:button>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
        {{-- Pagination --}}
        <div class="mt-6">
            <flux:pagination :paginator="$articles" />
        </div>
    </flux:card>
</x-layouts.app-dashboard>
