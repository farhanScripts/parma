<x-app-layout>
  <x-slot name="header">
    <div class="flex flex-row w-full justify-between items-center">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{__('Details')}}
      </h2>

    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden gap-y-5 p-10 shadow-sm sm:rounded-lg">

        <div class="item-card flex flex-col gap-y-3 md:flex-row justify-between md:items-center">
          <div class="flex flex-row gap-x-3 items-center">
            <div>
              <p class="text-base font-bold">Total Transaksi</p>
              <h3 class="font-bold text-xl text-blue-500">
                Rp {{ $product_transaction->total_amount }}
              </h3>
            </div>
          </div>

          <div>
            <p class="text-base font-bold">Date</p>
            <h3 class="font-bold text-xl text-blue-500">
              {{ $product_transaction->created_at ? $product_transaction->created_at : date('Y-m-d')}}
            </h3>
          </div>

          @if ($product_transaction->is_paid)
          <span class="font-bold py-1 px-3 w-fit rounded-full bg-green-500">
            <p class="text-white font-bold text-sm">
              SUCCESS
            </p>
          </span>
          @else
          <span class="font-bold py-1 px-3  w-fit rounded-full bg-orange-500">
            <p class="text-white font-bold text-sm">
              PENDING
            </p>
          </span>
          @endif


        </div>
        <hr class="my-3">

        <h3 class="font-bold text-xl">
          List of Items
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-10">
          <div class="flex flex-col gap-y-5 col-span-2">
            @forelse ($product_transaction->transactionDetails as $details)
            <div class="item-card flex p-3 flex-row justify-between items-center">
              <div class="flex flex-row gap-x-3">
                <img src="{{ Storage::url($details->product->photo) }}" alt="{{ $details->product->name }}"
                  class="w-[50px] h-[50px]">
                <div>
                  <h3 class="font-bold text-xl text-indigo-950">
                    {{ $details->product->name }}
                  </h3>
                  <p class="text-base text-blue-500 font-bold">Rp {{ $details->product->price }}</p>
                </div>
              </div>
              <p class="text-base text-slate-500">{{ $details->product->category->name }}</p>
            </div>
            @empty
            @endforelse
            <h3 class="font-bold text-xl">
              Details of Delivery
            </h3>
            <div class="item-card flex p-3 flex-row justify-between items-center">
              <h3 class="font-bold text-xl text-indigo-950">
                Adress
              </h3>
              <p class="text-base text-indigo-950">{{ $product_transaction->address }}</p>
            </div>
            <div class="item-card flex p-3 flex-row justify-between items-center">
              <h3 class="font-bold text-xl text-indigo-950">
                City
              </h3>
              <p class="text-base text-indigo-950">{{ $product_transaction->city }}</p>
            </div>
            <div class="item-card flex p-3 flex-row justify-between items-center">
              <h3 class="font-bold text-xl text-indigo-950">
                Post Code
              </h3>
              <p class="text-base text-indigo-950">{{ $product_transaction->post_code }}</p>
            </div>
            <div class="item-card flex p-3 flex-row justify-between items-center">
              <h3 class="font-bold text-xl text-indigo-950">
                Phone Number
              </h3>
              <p class="text-base text-indigo-950">{{ $product_transaction->phone_number }}</p>
            </div>
            <div class="item-card flex p-3 flex-col items-start">
              <h3 class="font-bold text-xl text-indigo-950">
                Notes
              </h3>
              <p class="text-base text-indigo-950">{{ $product_transaction->notes }}</p>
            </div>
          </div>

          <div class="flex flex-col gap-y-5 col-span-2 items-end">
            <h3 class="font-bold text-xl">
              Details of Payment :
            </h3>
            <img src="{{ Storage::url($product_transaction->proof) }}" alt="Bukti Transfer"
              class="h-[400px] w-[300px] ">
          </div>
        </div>
        <hr class="my-3">
        @role('owner')
        @if ($product_transaction->is_paid)
        <a href="#" class="w-fit font-bold py-3 px-5 rounded-full text-white bg-indigo-700">Whatsapp Customer</a>
        @else
        <form method="POST" action="{{ route('product_transactions.update', $product_transaction) }}">
          @csrf
          @method('PUT')
          <button class="font-bold py-3 px-5 rounded-full text-white bg-indigo-700">Approve Order</button>
        </form>
        @endif

        @endrole

        @role('buyer')
        <a href="#" class="w-fit font-bold py-3 px-5 rounded-full text-white bg-indigo-700">Contact Admin</a>
        @endrole
      </div>
    </div>
  </div>
</x-app-layout>