<x-app-layout>
  <x-slot name="header">
    <div class="flex flex-row w-full justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{Auth::user()->hasRole('owner') ? __('Apotek Orders') : __('My Transaction')}}
      </h2>
      <a href="{{ route('admin.products.create') }}"
        class="font-bold py-3 px-5 rounded-full text-white bg-indigo-500">Add
        Products</a>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden gap-y-5 p-10 shadow-sm sm:rounded-lg">

        <div class="item-card flex p-3 flex-row justify-between items-center">
          <div class="flex flex-row gap-x-3 items-center">
            <div>
              <p class="text-base font-bold">Total Transaksi</p>
              <h3 class="font-bold text-xl text-blue-500">
                Rp 18.000.000
              </h3>
            </div>
          </div>

          <div>
            <p class="text-base font-bold">Date</p>
            <h3 class="font-bold text-xl text-blue-500">
              25 January 2024
            </h3>
          </div>

          <span class="font-bold py-1 px-3 rounded-full bg-orange-500">
            <p class="text-white font-bold text-sm">
              PENDING
            </p>
          </span>

          <div class="flex flex-row gap-x-3 items-center">
            <a href="{{ route('product_transactions.show') }}"
              class="font-bold py-3 px-5 rounded-full text-white bg-indigo-700">View Details</a>
          </div>
        </div>
        <hr class="my-3">
      </div>
    </div>
  </div>
</x-app-layout>