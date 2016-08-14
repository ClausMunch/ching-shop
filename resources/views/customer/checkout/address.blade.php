@extends('customer.checkout.section')

@section('page-title')
    Checkout: Address
@endsection

@section('checkout-section-content')
    <h2 class="checkout-section-title">Your address</h2>

    <form method="post"
          action="{{ route('sales.customer.checkout.save-address') }}"
          class="form-horizontal">
        {{ csrf_field() }}
        <div class="form-group {{ $reply->putHasError('name') }}">
            <label class="col-sm-3 control-label" for="name">
                Name
            </label>
            <div class="col-sm-9">
                <input type="text"
                       autocomplete="name"
                       class="form-control checkout"
                       id="name"
                       name="name"
                       value="{{ $reply->oldInputOr(
                         'name',
                         $basket->address->name ?? ''
                       ) }}"
                       required>
                @foreach($reply->errorsFor('name') as $error)
                    <label class="help-block" for="name">
                        {{ $error }}
                    </label>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="form-group {{ $reply->putHasError('line_one') }}">
            <label class="col-sm-3 control-label" for="line_one">
                Address line one
            </label>
            <div class="col-sm-9">
                <input type="text"
                       class="form-control checkout"
                       autocomplete="address-line1"
                       id="line_one"
                       name="line_one"
                       value="{{ $reply->oldInputOr(
                         'line_one',
                         $basket->address->line_one ?? ''
                       ) }}"
                       required>
                @foreach($reply->errorsFor('line_one') as $error)
                    <label class="help-block" for="line_one">
                        {{ $error }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="form-group {{ $reply->putHasError('line_two') }}">
            <label class="col-sm-3 control-label" for="line_two">
                Address line two
            </label>
            <div class="col-sm-9">
                <input type="text"
                       class="form-control checkout"
                       autocomplete="address-line2"
                       id="line_two"
                       name="line_two"
                value="{{ $reply->oldInputOr(
                     'nline_two',
                     $basket->address->line_two ?? ''
                   ) }}">
                @foreach($reply->errorsFor('line_two') as $error)
                    <label class="help-block" for="line_two">
                        {{ $error }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="form-group {{ $reply->putHasError('post_code') }}">
            <label class="col-sm-3 control-label" for="post_code">
                Post code
            </label>
            <div class="col-sm-9">
                <input type="text"
                       class="form-control checkout"
                       autocomplete="postal-code"
                       id="post_code"
                       name="post_code"
                       value="{{ $reply->oldInputOr(
                         'post_code',
                         $basket->address->post_code ?? ''
                       ) }}"
                       required>
                @foreach($reply->errorsFor('post_code') as $error)
                    <label class="help-block" for="post_code">
                        {{ $error }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="form-group {{ $reply->putHasError('country') }}">
            <label class="col-sm-3 control-label" for="country">
                Country
            </label>
            <div class="col-sm-9">
                <input type="text"
                       class="form-control checkout"
                       autocomplete="country"
                       id="country"
                       name="country"
                       value="{{ $reply->oldInputOr(
                         'country',
                         $basket->address->country ?? ''
                       ) }}"
                       required>
                @foreach($reply->errorsFor('country') as $error)
                    <label class="help-block" for="country">
                        {{ $error }}
                    </label>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="continue">
            <button class="btn btn-lg btn-success continue-button"
                    type="submit">
                Continue &rarr;
            </button>
        </div>
    </form>
    <hr>
@endsection
