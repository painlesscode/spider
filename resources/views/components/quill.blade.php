@props(['field', 'model' => null])
<div class="w-full py-2 font-semibold text-gray-700">{{ $field->lebel ?? $field->name }}</div>
<div class="w-full pb-12">
    <div class="w-full bg-white" id="{{ $field->column }}-wrapper">
        <div id="{{ $field->column }}-editor" class="bg-white">{!! optional($model)->{$field->column} !!}</div>
    </div>
    <input type="hidden" name="{{ $field->column }}" id="{{ $field->column }}"/>
</div>
<script>
    (function (){
        const int = setInterval(() => {
            if (window.hasOwnProperty('Quill')) {
                clearInterval(int);
                const boundInput = document.getElementById('{{ $field->column }}');
                const quill = new window.Quill('#{{ $field->column }}-editor', @js($field->getOption()));
                quill.on('text-change', function (){
                    boundInput.value = quill.container.querySelector('.ql-editor').innerHTML
                });
                boundInput.closest('form').addEventListener('submit', function (){
                    quill.setContents(quill.getContents())
                    boundInput.value = quill.container.querySelector('.ql-editor').innerHTML
                })
                const adjustBottomPadding = function (){
                    document.getElementById('{{ $field->column }}-wrapper').parentElement.style.paddingBottom = quill.container.previousElementSibling.offsetHeight+'px'
                }
                adjustBottomPadding()
                window.addEventListener('resize', adjustBottomPadding)
            }
        }, 1)
    })()
</script>
