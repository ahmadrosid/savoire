@props(['id', 'name', 'value' => ''])

<div class="border rounded-md overflow-hidden"
    x-data="{
        content: @entangle($attributes->wire('model')),
    }"
    x-on:trix-initialize="$refs.text.editor.loadHTML(content)"
    x-on:trix-change="content = $event.target.value"
    wire:ignore
    >
    <input
        type="hidden"
        name="{{ $name }}"
        id="{{ $id }}_input"
        value="{{ $value }}"
        {{ $attributes }}
    />

    <trix-toolbar
        class="[&_.trix-button]:bg-white [&_.trix-button.trix-active]:bg-neutral-300"
        id="{{ $id }}_toolbar"
    ></trix-toolbar>

    <trix-editor {{ $attributes->whereDoesntStartWith('wire')->merge(array_filter([
        'id' => $id,
        'x-ref' => 'text',
        'toolbar' => $id . '_toolbar',
        'class' => "trix-content focus:ring-none bg-neutral-50 -mt-2 pt-5 prose-sm max-w-none",
        'input' => "{$id}_input",
        'data-controller' => 'rich-text rich-text-uploader rich-text-mentions',
        'data-rich-text-uploader-accept-files-value' => 'false',
        'data-action' => 'tribute-replaced->rich-text-mentions#addMention trix-attachment-add->rich-text-uploader#upload keydown->rich-text#submitByKeyboard',
    ])) }}></trix-editor>
</div>
