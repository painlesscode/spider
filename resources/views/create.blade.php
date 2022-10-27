<x-dynamic-component :component="$layout">
    <div class="w-full bg-white border-b flex justify-between items-center">
        <div class="p-4 text-xl">{{ 'Create '.$name }}</div>
        @if(Route::has($routeName.'.index'))
            <a href="{{ route($routeName.'.index') }}"
               class="py-1 px-3 text-sm bg-gray-700 text-white rounded mr-4">{{ __(Str::plural($name)) }}</a>
        @endif
    </div>
    <div class="w-full p-4">
        <form action="{{ route($routeName.'.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="w-full flex flex-wrap justify-center">
                @foreach($fields as $field)
                    <x-spider::labeled-input :type="$field->type" class="p-1 w-full lg:w-1/2 xl:w-1/3" :name="$field->column" :label="$field->name" :required="$field->isRequired('create')"/>
                @endforeach
            </div>
            <div class="w-full p-4 flex justify-center">
                <button class="py-2 px-4 rounded bg-gray-700 text-sm text-white">{{ __('Submit') }}</button>
            </div>
        </form>
    </div>
</x-dynamic-component>
