@extends('layouts.app')

@section('hero-title', 'Checkout')

@section('content')
<div class="untree_co-section before-footer-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <h2 class="h3 mb-3 text-black">Your Order</h2>
                <div class="p-3 p-lg-4 border bg-white">
                    <table class="table site-block-order-table mb-4">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>{{ $item['article']->title }} <strong class="mx-2">x</strong> {{ $item['quantity'] }}</td>
                                    <td>${{ number_format($item['subtotal'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-black"><strong>Order Total</strong></td>
                                <td class="text-black"><strong>${{ number_format($total, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('cart.index') }}" class="btn btn-sm">&larr; Back to Cart</a>
                </div>
            </div>

            <div class="col-md-6">
                <h2 class="h3 mb-3 text-black">Shipping Details</h2>
                <div class="p-3 p-lg-4 border bg-white">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('cart.checkout.submit') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="customer_first_name" class="text-black">First Name <span class="text-danger">*</span></label>
                                <input type="text" id="customer_first_name" name="customer_first_name" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="customer_last_name" class="text-black">Last Name <span class="text-danger">*</span></label>
                                <input type="text" id="customer_last_name" name="customer_last_name" required class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="city" class="text-black">City <span class="text-danger">*</span></label>
                                <input type="text" id="city" name="city" required class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number" class="text-black">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" id="phone_number" name="phone_number" required class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="text-black">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="address" class="text-black">Address <span class="text-danger">*</span></label>
                            <textarea id="address" name="address" required class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg py-3 w-100 mt-3">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
