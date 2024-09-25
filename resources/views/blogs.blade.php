<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Blogs
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
            @endif
            @if (session('danger'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('danger') }}</span>
                </div>
            </div>
            @endif
            <br>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900">
                    <x-primary-button class="ms-3 bg-gray-100" onclick="window.location='blogs/create'">Create Blog</x-primary-button>
                    <!-- {{ __("You're logged in!") }} -->
                    <table class="w-full" border="1">
                        <thead class="w-100">
                            <tr>
                                <th>Tilte</th>
                                <th>Content</th>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Tags</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($blogs as $blog)
                            <tr>
                                <td>{{ $blog->title }}</td>
                                <td>{{ $blog->content }}</td>
                                <td><img width="140" src="{{Storage::url('images/'.$blog->image)}}"> </td>
                                <td>{{$blog?->category?->name}}</td>
                                <td>
                                    @foreach($blog->tags as $tag)
                                    #{{$tag?->name}},
                                    @endforeach
                                </td>
                                <td>
                                    <x-primary-button class="ms-3 bg-gray-100" onclick="window.location='blogs/{{$blog->id}}'">View</x-primary-button>
                                </td>

                                <td>
                                    <x-primary-button class="ms-3 bg-gray-100" onclick="window.location='blogs/{{$blog->id}}/edit'">Edit</x-primary-button>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('blogs.destroy', $blog->id) }}" class="p-6">
                                        @csrf
                                        @method('delete')
                                        <x-danger-button>Delete</x-danger-button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <p>No Blogs</p>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>