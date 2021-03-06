@extends('layout.master')

@section('page.title', 'Saved Baskets')

@section('content')
    <div class="w-full mb-5 text-center">
        <h2 class="font-semibold tracking-widest">Saved Basket Details</h2>
        <p class="font-thin">
            A list of all products in your saved basket.
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="md:flex">
            <div class="md:w-1/2">
                <h4 class="font-semibold">Template Reference <span
                        class="badge badge-info">{{ $saved_basket->first()->reference }}</span></h4>

                <h4 class="font-semibold">Date Saved <span
                        class="font-normal">{{ \Carbon\Carbon::parse($saved_basket->first()->created_at)->format('d/m/Y') }}</span>
                </h4>
            </div>

            <div class="md:w-1/2 text-right">
                <button class="button button-inverse" onclick="window.history.back();">
                    Back
                </button>

                <a href="{{ route('saved-baskets.destroy', ['reference' => $saved_basket->first()->reference]) }}">
                    <button class="button button-danger">Delete Template</button>
                </a>
            </div>
        </div>
    </div>

    <h2 class="font-semibold tracking-widest mt-3 mb-3">Products</h2>

    <div class="table-container">
        <table class="table">
            <thead>
            <tr>
                <th>Product Code</th>
                <th class="hidden md:block">Name</th>
                <th class="text-right">Quantity</th>
            </tr>
            </thead>
            <tbody>
            @foreach($saved_basket as $item)
                <tr class="{{ !$item->price ? 'bg-red-200' : '' }}">
                    <td>{{ $item->product }}</td>
                    <td class="hidden md:block">{{ $item->name ?: 'Not Available' }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="sm:text-left md:text-right">
        <div class="text-red-600 my-3 text-xs leading-none">
            * Items marked in red are no longer available for purchase and will not be added.
        </div>

        <a href="{{ route('saved-baskets.copy', ['reference' => $saved_basket->first()->reference]) }}" class="text-right">
            <submit-button before-text="Add to basket" after-text="Adding to basket"></submit-button>
        </a>
    </div>
@endsection
