<x-dynamic-component :component="$layout">
    <div class="w-full bg-white border-b flex justify-between items-center">
        <div class="p-4 text-xl">{{ 'Edit '.$name }}</div>
        @if(Route::has($routeName.'.index'))
            <a href="{{ route($routeName.'.index') }}"
               class="py-1 px-3 text-sm bg-gray-700 text-white rounded mr-4">{{ __(Str::plural($name)) }}</a>
        @endif
    </div>
    <div class="w-full p-4">
        <form action="{{ route($routeName.'.update', $model->getKey()) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="w-full flex flex-wrap justify-center">
                @foreach($fields as $field)
                    @if($field instanceof \Painlesscode\Spider\Fields\Select)
                        <x-spider::labeled-select class="p-1 w-full lg:w-1/2 xl:w-1/3 flex-grow" :name="$field->column" :label="$field->name" :required="$field->isRequired('edit')" :extra-attributes="$field->getAttributes('edit')">
                            @foreach($field->getOptions() as $key => $option)
                                @if($option instanceof \Painlesscode\Spider\Fields\Utils\Option)
                                    <option @if(($field->value ?? $model->{$field->column}) == $option->value) selected @endif @if($option->parent) data-parent="{{ $option->parent }}" @endif value="{{ $option->value }}">{{ $option->label }}</option>
                                @else
                                    <option @if(($field->value ?? $model->{$field->column}) == (string) $key) selected @endif value="{{ $key }}">{{ $option }}</option>
                                @endif
                            @endforeach
                        </x-spider::labeled-select>
                    @else
                    <x-spider::labeled-input :type="$field->type" class="p-1 w-full lg:w-1/2 xl:w-1/3 flex-grow" :name="$field->column" :value="$field->value ?? $model->{$field->column}" :label="$field->name" :required="$field->isRequired('edit')" :extra-attributes="$field->getAttributes('edit')"/>
                    @endif
                @endforeach
            </div>
            <div class="w-full p-4 flex justify-center">
                <button class="py-2 px-4 rounded bg-gray-700 text-sm text-white">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        document.querySelectorAll("[depend-on]").forEach(e=>{let t=document.getElementById(e.getAttribute("depend-on"));t.addEventListener("change",t=>{Array.from(e.children).forEach(e=>{e.dataset.parent!=t.target.value?(e.setAttribute("disabled",!0),e.style.display="none"):(e.removeAttribute("disabled"),e.style.display=null)});let l=Array.from(e.children).filter(e=>!e.hasAttribute("disabled")),a=l.filter(e=>e.hasAttribute("selected"));a.length?e.value=a[0].getAttribute("value"):l.length?e.value=l[0].getAttribute("value"):e.value=null,e.dispatchEvent(new CustomEvent("change",{bubbles:!0,composed:!0,cancelable:!0}))}),t.getAttribute("depend-on")||setTimeout(()=>{t.dispatchEvent(new CustomEvent("change",{bubbles:!0,composed:!0,cancelable:!0}))},10)});
    </script>
</x-dynamic-component>
