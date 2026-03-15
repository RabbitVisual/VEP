@props([
    'name' => 'full_content',
    'value' => '',
    'placeholder' => 'Digite o conteúdo do sermão... Use @ para inserir referências bíblicas.',
])

<div class="rich-editor-wrapper">
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        data-mention-editor="true"
        placeholder="{{ $placeholder }}"
        rows="16"
        class="mt-1 block w-full rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm focus:border-amber-500 focus:ring-amber-500 dark:bg-gray-700 dark:text-white sm:text-sm font-sans"
        {{ $attributes->except('class') }}
    >{{ $value }}</textarea>
</div>
