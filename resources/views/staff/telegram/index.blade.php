@extends('layouts.staff.dashboard')

@section('page-title')
    Telegram
@endsection

@section('content')

    <form method="post" action="{{route('telegram.store')}}">
        <div class="form-group {{$reply->putHasError('text')}}">
            {{csrf_field()}}
            <label for="text">New staff message:</label>
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       minlength="1" maxlength="256"
                       size="256"
                       name="text"
                       id="text">
                <span class="input-group-btn">
                    <button type="submit"
                            class="btn btn-info">
                        <span class="icon icon-telegram"></span>
                    </button>
                </span>
            </div>
            @foreach($reply->errorsFor('text') as $error)
                <label class="help-block" for="text">
                    {{$error}}
                </label>
            @endforeach
        </div>
    </form>

    <hr>

    <h2>Messages received</h2>

    <div id="telegram">
        <ul class="updates">
            <li class="update" v-for="update in updates">
                <small class="text-muted">
                    <strong>@{{update.message.from.username}}</strong>
                    @{{update.message.date | date}}:
                </small>
                <p>
                    @{{update.message.text}}
                </p>
            </li>
        </ul>
    </div>

@endsection

@push('scripts')
<script async defer type="application/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.5/vue.min.js">
</script>
<script async defer type="application/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.0.3/vue-resource.min.js">
</script>
<script async defer type="application/javascript"
        src="{{secure_asset(elixir('js/Telegram.js'))}}"></script>
@endpush
