<x-dynamic-component :component="$layout">
    <div class="w-full bg-white border-b flex justify-between items-center">
        <div class="p-4 text-xl">{{ $name.' List' }}</div>
        @if(Route::has($routeName.'.index'))
            <a href="{{ route($routeName.'.index') }}"
               class="py-1 px-3 text-sm bg-gray-700 text-white rounded mr-4">{{ __($name.' List') }}</a>
        @endif
    </div>
    <div class="w-full p-4">
        <div class="w-full flex flex-wrap justify-center">
            @foreach($fields as $field)
                @if($field instanceof \Painlesscode\Spider\Fields\Widgets\ShowAbleWidget || $field instanceof \Painlesscode\Spider\Fields\Widgets\ReadAbleWidget)
                    <x-dynamic-component :component="$field->getComponentForShow()" :field="$field" :model="$model"/>
                @else
                    <div class="w-full flex border border-slate-400">
                        <div class="p-2 w-1/3 bg-slate-300">{{ $field->name }}</div>
                        <div
                            class="p-2 w-2/3">{!! $field->showValueResolver ? ($field->showValueResolver)($model) : $model->{$field->column} !!}</div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-dynamic-component>
