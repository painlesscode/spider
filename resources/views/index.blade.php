<x-dynamic-component :component="$layout">
    <div class="w-full bg-white border-b flex justify-between items-center">
        <div class="p-4 text-xl">{{ $name }}</div>
        @if(Route::has($routeName.'.create'))
            <a href="{{ route($routeName.'.create') }}"
               class="py-1 px-3 text-sm bg-gray-700 text-white rounded mr-4">{{ __('Create '.$name) }}</a>
        @endif
    </div>
    <div class="w-full p-4 bg-slate-100">
        <div class="w-full flex flex-col">
            <div class="w-full flex justify-between">

            </div>
            <div class="w-full rounded-t bg-gray-50 h-12 flex justify-between items-center p-2" x-data>
                <form action="">
                    <select class="border rounded p-1" name="per_page"
                            x-on:change="$event.target.parentElement.submit()">
                        <option value="10" @if(request('per_page') == 10) selected @endif>{{ __('10') }}</option>
                        <option value="20" @if(request('per_page') == 20) selected @endif>{{ __('20') }}</option>
                        <option value="30" @if(request('per_page') == 30) selected @endif>{{ __('30') }}</option>
                        <option value="50" @if(request('per_page') == 50) selected @endif>{{ __('50') }}</option>
                        <option value="100" @if(request('per_page') == 100) selected @endif>{{ __('100') }}</option>
                    </select>
                </form>
            </div>
            <table class="table w-full">
                <thead>
                <tr class="bg-gray-100 uppercase border-y text-gray-600 text-sm">
                    @foreach($fields as $field)
                        <th class="p-2 text-left">{{ $field->name }}</th>
                    @endforeach
                    <th class="p-2"></th>
                </tr>
                </thead>
                <tbody>
                @php
                    $hasShowRoute = Route::has($routeName.'.show');
                    $hasEditRoute = Route::has($routeName.'.edit');
                    $hasDestroyRoute = Route::has($routeName.'.destroy');
                @endphp
                @foreach($items as $item)
                    <tr class="border-y bg-white">
                        @foreach($fields as $field)
                            <td class="p-2">{{ $item->{$field->column} }}</td>
                        @endforeach
                        <td class="p-2">
                            <div class="flex justify-end text-gray-500">
                                @if($hasShowRoute)
                                    <a href="{{ route($routeName.'.show', $item->getKey()) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             class="cursor-pointer w-4 h-4 ml-2 hover:text-gray-700">
                                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/>
                                            <path fill-rule="evenodd"
                                                  d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($hasEditRoute)
                                    <a href="{{ route($routeName.'.edit', $item->getKey()) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             class="cursor-pointer w-4 h-4 ml-2 hover:text-gray-700">
                                            <path
                                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                                            <path
                                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($hasDestroyRoute)
                                    <form action="{{ route($routeName.'.destroy', $item->getKey()) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <svg onclick="confirm('Are you sure about your action?') && this.parentElement.submit()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             class="cursor-pointer w-4 h-4 ml-2 hover:text-gray-700">
                                            <path fill-rule="evenodd"
                                                  d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="w-full">
                {{ $items->appends(request()->only(['page','per_page']))->links('spider::pagination') }}
            </div>
        </div>
    </div>
</x-dynamic-component>
