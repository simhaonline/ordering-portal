@extends('layout.master')

@section('page.title', 'Basket')

@section('content')
    <div class="row">
        <div class="col">
            <h1 class="page-title">{{ __('Basket') }}</h1>
        </div>
        <div class="col text-right">
            Tracking Stuffs
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7"></div>
        <div class="col-lg-5 justify-content-end">
            <div class="card card-body quick-buy-basket">
                <div class="form-group">
                    <label><strong>{{ __('Quick Buy') }}</strong></label>
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Enter Product Code">
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control text-center" value="1">
                        </div>
                        <div class="col-4">
                            <button class="btn btn-block btn-primary">Add To Basket</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-basket">
        <thead>
        <tr>
            <th>Product</th>
            <th>Code</th>
            <th>Unit</th>
            <th class="text-right">Stock (†)</th>
            <th class="text-right">Net Price</th>
            <th class="text-right">Quantity</th>
            <th class="text-right">Total Price</th>
        </tr>
        </thead>
        @if (count($basket['lines']) > 0)
            <tbody>
            @foreach($basket['lines'] as $line)
                <tr>
                    <td>
                        <img class="basket-image" src="https://scolmoreonline.com/product_images/DPBN024BK.png">
                        <h2 class="section-title d-inline-block">{{ $line->productDetails->name }}</h2>
                    </td>
                    <td>{{ $line->product }}</td>
                    <td>{{ $line->productDetails->uom }}</td>
                    <td class="text-right">{{ $line->productDetails->stock->quantity }}</td>
                    <td class="text-right">£{{ number_format($line->productDetails->prices->price, 4) }}</td>
                    <td class="text-right">{{ $line->quantity }}</td>
                    <td class="text-right">£{{ number_format(number_format($line->productDetails->prices->price, 4) * $line->quantity, 2, '.', ',') }}</td>
                </tr>
            @endforeach
            </tbody>
        @else
            <div class="text-center mb-5"><h2>No items are in your basket</h2></div>
        @endif
    </table>

    <div class="row">
        <div class="col-lg-7"></div>
        <div class="col-lg-5 justify-content-end">
            <div class="card card-body basket-summary">
                <div class="row">
                    <div class="col">Goods Total</div>
                    <div class="col text-right">£{{ $basket['summary']['goods_total'] }}</div>
                </div>
                <div class="row">
                    <div class="col">Shipping</div>
                    <div class="col text-right">£0.00</div>
                </div>
                <div class="row">
                    <div class="col">Sub Total</div>
                    <div class="col text-right">£0.00</div>
                </div>
                <div class="row">
                    <div class="col">Small Order Charge*</div>
                    <div class="col text-right">£0.00</div>
                </div>
                <div class="row">
                    <div class="col">VAT</div>
                    <div class="col text-right">£0.00</div>
                </div>
                <hr>
                <div class="row basket-total">
                    <div class="col">Order Total</div>
                    <div class="col text-right">£0.00</div>
                </div>
                <hr>
                <div class="small-print">
                    <div class="row">
                        <div class="col">*orders below £200 attract a £10 small order charge, unless you are collecting
                            your order.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">† Stock levels are only accurate at the time the product is first added to the
                            basket.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <button class="btn btn-blue">{{ __('Continue Shopping') }}</button>
            <button class="btn btn-blue">{{ __('Empty basket') }}</button>
        </div>
        <div class="col text-right">
            <button class="btn btn-primary">{{ __('Save Basket') }}</button>
            <button class="btn btn-primary">{{ __('Checkout') }}</button>
        </div>
    </div>
@endsection