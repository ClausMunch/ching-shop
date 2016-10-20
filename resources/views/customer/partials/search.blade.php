<form class="form top-center" method="get"
      action="{{route('catalogue.search')}}">
    <div class="input-group">
        <input name="query" type="search"
               class="form-control top-search"
               value="{{$query or ''}}"
               placeholder="Search for Christmas, love, flower..."
               list="suggestions">
        @if (isset($suggestionsCache))
            {!!$suggestionsCache!!}
        @elseif (isset($suggestions))
            @include('customer.partials.suggestions')
        @endif
        <span class="input-group-btn">
                        <button class="btn btn-default"
                                id="search-button"
                                type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                            <span class="sr-only">Search</span>
                        </button>
                    </span>
    </div>
</form>
