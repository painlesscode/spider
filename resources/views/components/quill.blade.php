@props(['field', 'model' => null])
<div class="w-full py-2 font-semibold text-gray-700">{{ $field->lebel ?? $field->name }}</div>
<div class="w-full pb-12">
    <div class="w-full bg-white">
        <div id="{{ $field->name }}-editor" class="bg-white">{!! optional($model)->{$field->column} !!}</div>
    </div>
    <input type="hidden" name="{{ $field->column }}" id="{{ $field->name }}"/>
</div>
<script>
    (function (){
        const int = setInterval(() => {
            if (window.hasOwnProperty('Quill')) {
                clearInterval(int);
                const boundInput = document.getElementById('{{ $field->name }}');
                const quill = new window.Quill('#{{ $field->name }}-editor', @js($field->getOption()));
                quill.on('text-change', function (){
                    boundInput.value = quill.container.querySelector('.ql-editor').innerHTML
                });
                boundInput.closest('form').addEventListener('submit', function (){
                    quill.setContents(quill.getContents())
                    boundInput.value = quill.container.querySelector('.ql-editor').innerHTML
                })
            }
        }, 1)
    })()
</script>
