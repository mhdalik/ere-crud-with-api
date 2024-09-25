<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            View Blog
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{$blog->title}}
                </h1>
                <h3 class="font-semibold text-gray-800 leading-tight">
                    Category: {{$blog?->category?->name}}
                </h3>
                <h3 class="font-semibold  text-gray-800 leading-tight">
                    Tags:
                    @foreach($blog->tags as $tag)
                    #{{$tag?->name}},
                    @endforeach
                </h3>
                <img style="width: 100%;" src="{{Storage::url('images/'.$blog->image)}}">
                <p>
                    {{$blog->content}}
                </p>
            </div>
        </div>
    </div>

</x-app-layout>