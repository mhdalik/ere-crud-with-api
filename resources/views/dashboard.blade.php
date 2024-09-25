<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Blogsss
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900">
                    <!-- {{ __("You're logged in!") }} -->
                    <table class="w-full">
                        <thead class="w-100">
                            <tr>
                                <th>Tilte</th>
                                <th>Content</th>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Tags</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Eclipse: the Best of Solana, Ethereum, and Celestia</td>
                                <td>One way to think of the design choices within the layers is the lower down the stack we go, the more we need small machines to be able to verify the truth. By ‘small machines’ we mean a set of requirements that are widely achievable, allowing nearly anyone to verify the truth if they so desire, keeping collusion at bay. So long as these smaller machines are publishing and v</td>
                                <td><img width="240" src="https://plus.unsplash.com/premium_photo-1661338804072-aff787617c3f?q=80&w=2116&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"> </td>
                                <td>Tech</td>
                                <td>Coding, Blog</td>
                                <td>
                                    <x-primary-button class="ms-3 bg-gray-100">Edit</x-primary-button>
                                    <x-secondary-button class="ms-3 bg-gray-100">Delete</x-secondary-button>
                                </td>
                            </tr>
                        </tbody>

                    </table>

                    @forelse ($blogs as $blog)
                    <p>This is blog {{ $blog }}</p>
                    @empty
                    <p>No Blogs</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-app-layout>