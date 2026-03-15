@extends('admin::components.layouts.master')

@section('title', 'Editar Strong: ' . $strong->number)

@section('content')
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Editar Strong {{ $strong->number }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Ajuste explicação e contexto. Campos originais (lemma, number) preservados; edite livremente o equivalente em português (lemma_br) e a descrição.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.bible.strong.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                    <x-icon name="arrow-left" style="duotone" class="w-4 h-4 mr-2" />
                    Listar Strong
                </a>
                <a href="{{ route('admin.bible.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">
                    Bíblia Digital
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300 dark:border-green-800 flex items-center" role="alert">
                <x-icon name="circle-check" class="w-5 h-5 mr-3 flex-shrink-0" />
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900/20 dark:text-red-300 dark:border-red-800" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <x-icon name="book" style="duotone" class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" />
                    Concordância Strong (estilo NEPE)
                </h2>
            </div>

            <form action="{{ route('admin.bible.strong.update', $strong) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium text-gray-700 dark:text-gray-300">ID:</span> {{ $strong->id }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Número (somente leitura):</span>
                        <span class="font-mono font-semibold text-gray-900 dark:text-white">{{ $strong->number }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="lemma" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Lemma (original)</label>
                        <input type="text" id="lemma" name="lemma" value="{{ old('lemma', $strong->lemma) }}" dir="rtl"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white font-serif">
                        @error('lemma')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="lemma_br" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Lemma (BR) – equivalente em português</label>
                        <textarea id="lemma_br" name="lemma_br" rows="2" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none">{{ old('lemma_br', $strong->lemma_br) }}</textarea>
                        @error('lemma_br')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="transliteration" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Transliteração (xlit)</label>
                        <input type="text" id="transliteration" name="transliteration" value="{{ old('transliteration', $strong->transliteration) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('transliteration')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="pronunciation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pronúncia</label>
                        <input type="text" id="pronunciation" name="pronunciation" value="{{ old('pronunciation', $strong->pronunciation) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('pronunciation')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="part_of_speech" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Classe gramatical (part_of_speech)</label>
                        <input type="text" id="part_of_speech" name="part_of_speech" value="{{ old('part_of_speech', $strong->part_of_speech) }}" placeholder="Ex: Verbo, Substantivo, Advérbio"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('part_of_speech')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="language" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Idioma</label>
                        <select id="language" name="language" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="H" {{ old('language', $strong->language) === 'H' ? 'selected' : '' }}>Hebraico (H)</option>
                            <option value="G" {{ old('language', $strong->language) === 'G' ? 'selected' : '' }}>Grego (G)</option>
                        </select>
                        @error('language')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Descrição / definição</label>
                    <textarea id="description" name="description" rows="6" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-y">{{ old('description', $strong->description) }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="twot_ref" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">TWOT (referência)</label>
                        <input type="text" id="twot_ref" name="twot_ref" value="{{ old('twot_ref', $strong->twot_ref) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('twot_ref')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="ditat_ref" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">DITAT (referência)</label>
                        <input type="text" id="ditat_ref" name="ditat_ref" value="{{ old('ditat_ref', $strong->ditat_ref) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('ditat_ref')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Gematria (opcional)</h3>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div>
                            <label for="gematria_hechrachi" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Hechrachi</label>
                            <input type="number" id="gematria_hechrachi" name="gematria_hechrachi" value="{{ old('gematria_hechrachi', $strong->gematria_hechrachi) }}" min="0" step="1"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="gematria_gadol" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Gadol</label>
                            <input type="number" id="gematria_gadol" name="gematria_gadol" value="{{ old('gematria_gadol', $strong->gematria_gadol) }}" min="0" step="1"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="gematria_siduri" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Siduri</label>
                            <input type="number" id="gematria_siduri" name="gematria_siduri" value="{{ old('gematria_siduri', $strong->gematria_siduri) }}" min="0" step="1"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="gematria_katan" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Katan</label>
                            <input type="number" id="gematria_katan" name="gematria_katan" value="{{ old('gematria_katan', $strong->gematria_katan) }}" min="0" step="1"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="gematria_perati" class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Perati</label>
                            <input type="number" id="gematria_perati" name="gematria_perati" value="{{ old('gematria_perati', $strong->gematria_perati) }}" min="0" step="1"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="lexicon_metadata_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Léxico (metadados)</label>
                    <select id="lexicon_metadata_id" name="lexicon_metadata_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">— Nenhum —</option>
                        @foreach ($lexicons as $lex)
                            <option value="{{ $lex->id }}" {{ old('lexicon_metadata_id', $strong->lexicon_metadata_id) == $lex->id ? 'selected' : '' }}>{{ $lex->slug }} – {{ $lex->title }}</option>
                        @endforeach
                    </select>
                    @error('lexicon_metadata_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="text-sm text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-200 dark:border-gray-700">
                    Criado em {{ $strong->created_at?->format('d/m/Y H:i') }} · Atualizado em {{ $strong->updated_at?->format('d/m/Y H:i') }}
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('admin.bible.strong.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
