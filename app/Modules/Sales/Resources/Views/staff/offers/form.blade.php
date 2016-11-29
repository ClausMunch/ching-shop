<form method="post"
      id="offer-form" data-toggle="validator" role="form"
      action="{{ $location->persistActionFor($offer) }}">
    {{ csrf_field() }}
    {{ method_field($location->persistMethodFor($offer)) }}
    <input type="hidden" name="id" value="{{$offer->id}}">

    <div class="row">
        <div class="col-md-5">
            <div class="form-group {{$reply->putHasError('price')}}">
                <label for="price">
                    Price units
                    <small>(£0.01, e.g. 200 = £2)</small>
                </label>
                <input type="number"
                       class="form-control"
                       id="price"
                       name="price"
                       min="0"
                       step="1"
                       size="5"
                       value="{{$reply->oldInputOr('price', $offer->price->amount() ?: '')}}">
                @foreach($reply->errorsFor('price') as $error)
                    <label class="help-block" for="price">
                        {{$error}}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="col-md-2"><p class="text-center"><br>OR</p></div>
        <div class="col-md-5">
            <div class="form-group {{$reply->putHasError('percentage')}}">
                <label for="percentage">
                    Percentage
                </label>
                <div class="input-group">
                    <input type="number"
                           class="form-control"
                           id="percentage"
                           name="percentage"
                           min="1"
                           max="99"
                           step="1"
                           size="2"
                           value="{{$reply->oldInputOr('percentage', (string) $offer->percentage ?: '')}}">
                    @foreach($reply->errorsFor('percentage') as $error)
                        <label class="help-block" for="percentage">
                            {{$error}}
                        </label>
                    @endforeach
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{$reply->putHasError('quantity')}}">
                <label for="quantity">
                    Minimum purchase quantity
                </label>
                <input type="number"
                       class="form-control"
                       id="quantity"
                       name="quantity"
                       min="0"
                       max="999"
                       step="1"
                       value="{{$reply->oldInputOr('quantity', $offer->quantity ?? 1)}}">
                @foreach($reply->errorsFor('quantity') as $error)
                    <label class="help-block" for="quantity">
                        {{$error}}
                    </label>
                @endforeach
                @foreach($reply->errorsFor('quantity') as $error)
                    <label class="help-block" for="quantity">
                        {{$error}}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{$reply->putHasError('effect')}}">
                <label for="effect">
                    Effect
                    <small>(how does this change the price?)</small>
                </label>
                <select class="form-control"
                        id="effect"
                        name="effect">
                    @foreach(\ChingShop\Modules\Sales\Domain\Offer\Offer::EFFECTS as $effect)
                        <option value="{{$effect}}"
                                @if ($offer->effect === $effect)
                                selected
                                @endif>
                            {{ucfirst($effect)}}
                        </option>
                    @endforeach
                </select>
                @foreach($reply->errorsFor('effect') as $error)
                    <label class="help-block" for="effect">
                        {{$error}}
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-group-sm {{$reply->putHasError('name')}}">
                <label for="name">
                    Colour
                </label>
                <input type="color"
                       class="form-control pastel"
                       id="colour"
                       name="colour"
                       required
                       value="{{$reply->oldInputOr('colour', $offer->colour)}}">
                @foreach($reply->errorsFor('colour') as $error)
                    <label class="help-block" for="colour">
                        {{$error}}
                    </label>
                @endforeach
            </div>
            <div>
                <span class="colour-suggestions"></span>
                <button type="button"
                        class="btn btn-sm btn-link suggest-colours">
                    <span class="glyphicon glyphicon-refresh"></span>
                    <span class="sr-only">More</span>
                </button>
            </div>
        </div>
        <div class="col-md-6 text-muted">
            <div class="form-group form-group-sm {{$reply->putHasError('name')}}">
                <label for="name">
                    Pre-set name
                    <small>(leave blank for automatic name &mdash;
                        recommended)
                    </small>
                </label>
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       minlength="1"
                       maxlength="255"
                       value="{{$reply->oldInputOr('name', $offer->preSetName())}}">
                @foreach($reply->errorsFor('name') as $error)
                    <label class="help-block" for="name">
                        {{$error}}
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <hr>

    <button type="submit" class="btn btn-success btn-lg">Save</button>
</form>

@push('scripts')
<script async defer src="{{elixir('js/colours.js')}}"
        type="application/javascript"></script>
@endpush
