@extends('layout.master')

@section('page.title', 'Checkout')

@section('content')
    <div class="w-full mb-5 text-center">
        <h2 class="font-semibold tracking-widest">Checkout</h2>

        <p class="font-thin">Complete your order</p>
    </div>

    <form method="post" action="{{ route('checkout.order') }}">
        <div class="flex">
            <div class="w-1/2">
                <div class="bg-white rounded shadow-md p-6 text-center mb-5 mr-2">
                    <h4 class="text-primary">Delivery Address</h4>

                    @if (session('address'))
                        <div class="w-3/4 text-center bg-gray-200 p-4 rounded mx-auto">
                            <div>{{ session('address.address_details.company_name') }}</div>
                            <div>{{ session('address.address_details.address_2') }}</div>
                            <div>{{ session('address.address_details.address_3') }}</div>
                            <div>{{ session('address.address_details.address_4') }}</div>
                            <div>{{ session('address.address_details.address_5') }}</div>
                            <div>{{ session('address.address_details.postcode') }}</div>
                        </div>
                    @elseif ($default_address)
                        <div class="w-3/4 text-center bg-gray-200 p-4 rounded mx-auto">
                            <div>{{ $default_address->company_name }}</div>
                            <div>{{ $default_address->address_line_2 }}</div>
                            <div>{{ $default_address->address_line_3 }}</div>
                            <div>{{ $default_address->address_line_4 }}</div>
                            <div>{{ $default_address->address_line_5 }}</div>
                            <div>{{ $default_address->post_code }}</div>
                        </div>
                    @else
                        <span
                            class="text-red-400">No default delivery address set, click below to add one.</span>
                    @endif

                    <div class="text-center mt-2 mb-2">
                        <a href="{{ route('account.addresses', ['checkout' => true]) }}">
                            <button type="button" class="button button-primary">
                                Change Delivery Address
                            </button>
                        </a>
                    </div>

                    <h4 class="text-primary mt-5">Invoice Address</h4>

                    <div class="w-3/4 text-center bg-gray-200 p-4 rounded mx-auto">
                        <div>{{ auth()->user()->customer->invoice_name }}</div>
                        <div>{{ auth()->user()->customer->invoice_address_line_1 }}</div>
                        <div>{{ auth()->user()->customer->invoice_address_line_2 }}</div>
                        <div>{{ auth()->user()->customer->invoice_address_line_3 }}</div>
                        <div>{{ auth()->user()->customer->invoice_address_line_4 }}</div>
                        <div>{{ auth()->user()->customer->invoice_address_line_5 }}</div>
                    </div>
                </div>
            </div>
            <div class="w-1/2">
                <div class="bg-white rounded shadow-md p-6 mb-5 ml-2">
                    @include('layout.alerts')

                    <h4 class="text-primary">Order Details</h4>

                    <div class="flex items-center mb-3">
                        <label for="reference" class="w-1/2">Order Reference</label>
                        <input id="reference" name="reference" autocomplete="off"
                               value="{{ old('reference') }}">
                    </div>

                    <div class="flex items-center mb-3">
                        <label for="notes" class="w-1/2">Order Notes</label>
                        <input id="notes" name="notes" autocomplete="off"
                               value="{{ old('notes') }}">
                    </div>

                    <delivery-method :delivery_methods="{{ json_encode($delivery_methods, true) }}"
                                     old_delivery_method="{{ old('shipping') ?? 'HHH' }}"
                                     goods_total="{{ $basket['summary']['goods_total'] }}"
                                     small_order="{{ json_encode($basket['summary']['small_order_rules'], true) }}">
                    </delivery-method>

                    <h4 class="text-primary mt-3">Contact Details</h4>

                    <div class="flex items-center mb-3">
                        <label for="name" class="w-1/2">Name</label>
                        <input id="name"
                               name="name"
                               value="{{ old('name') ?: auth()->user()->name }}"
                               autocomplete="off">
                    </div>

                    <div class="flex items-center mb-3">
                        <label for="telephone" class="w-1/2">Telephone</label>
                        <input id="telephone"
                               name="telephone"
                               value="{{ old('telephone') ?: auth()->user()->telephone }}"
                               autocomplete="off">
                    </div>

                    <div class="flex items-center mb-3">
                        <label for="mobile" class="w-1/2">Mobile</label>
                        <input id="mobile"
                               name="mobile"
                               value="{{ old('mobile') ?: auth()->user()->mobile }}"
                               autocomplete="off">
                    </div>

                    <div class="flex mb-3">
                        <label class="checkbox flex items-center">
                            <input type="checkbox" name="terms" class="form-checkbox"
                                   {{ old('terms') ? 'checked' : '' }} autocomplete="off" >
                            <span class="ml-2">I have read and agree to the <a href="{{ route('support.terms') }}"
                                                                               class="underline" target="_blank">terms & conditions</a>
                            </span>
                        </label>
                    </div>

                    @if($checkout_notice)
                        <div role="alert" class="alert alert-info">
                            <div class="alert-body text-sm leading-none items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="alert-icon">
                                    <path d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20z" class="primary"></path>
                                    <path
                                        d="M11 12a1 1 0 0 1 0-2h2a1 1 0 0 1 .96 1.27L12.33 17H13a1 1 0 0 1 0 2h-2a1 1 0 0 1-.96-1.27L11.67 12H11zm2-4a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"
                                        class="secondary"></path>
                                </svg>
                                <div>
                                    <p class="alert-text">{{ $checkout_notice }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mb-3">
            <h4 class="text-primary">Order Summary</h4>

            <basket-summary :summary="{{ json_encode($basket['summary'], true) }}"></basket-summary>

            @if($basket['summary']['small_order_rules']['threshold'] > 0)
                <div class="mt-3 text-xs">
                    * orders below {{ currency($basket['summary']['small_order_rules']['threshold'], 0) }} attract
                    a {{ currency($basket['summary']['small_order_rules']['charge'], 0) }}

                    @if($basket['summary']['small_order_rules']['exclude_collection'] && $basket['summary']['small_order_rules']['exclude_charged_delivery'])
                        small order charge, unless you are collecting your order or paying a delivery charge.
                    @elseif ($basket['summary']['small_order_rules']['exclude_collection'])
                        small order charge, unless you are collecting your order.
                    @elseif ($basket['summary']['small_order_rules']['exclude_charged_delivery'])
                        small order charge, unless you are paying a delivery charge.
                    @else
                        small order charge.
                    @endif
                </div>
            @endif
        </div>

        <div class="flex justify-between">
            <div>
                <a href="{{ route('products') }}">
                    <button class="button button-secondary">Continue Shopping</button>
                </a>
                <a href="{{ route('basket') }}">
                    <button class="button button-secondary">Return To basket</button>
                </a>
            </div>

            <checkout></checkout>
        </div>
    </form>
@endsection
