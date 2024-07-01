<x-app-layout>
  <x-slot name="header">
    <div class="flex flex-row w-full justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manage Category') }}
      </h2>
      <a href="{{ route('admin.categories.create') }}"
        class="font-bold py-3 px-5 rounded-full text-white bg-indigo-500">Add
        Category</a>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden gap-y-5 p-10 shadow-sm sm:rounded-lg">
        @forelse ($categories as $category )
        <div class="item-card flex p-3 flex-row justify-between items-center">
          <img src="{{ Storage::url($category->icon) }}" alt="{{ $category->name }}" class="w-[50px] h-[50px]">
          <h3 class="font-bold text-xl font-bold text-indigo-950">
            {{ $category->name }}
          </h3>
          <div class="flex flex-row gap-x-3 items-center">
            <a href="#" class="font-bold py-3 px-5 rounded-full text-white bg-indigo-700">Edit</a>
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
              @csrf
              @method('DELETE')
              <button class="font-bold py-3 px-5 rounded-full text-white bg-red-700">Delete</button>
            </form>
          </div>
        </div>
        @empty

        @endforelse
      </div>
    </div>
  </div>
</x-app-layout>