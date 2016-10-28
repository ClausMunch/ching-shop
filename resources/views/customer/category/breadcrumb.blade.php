<ol class="breadcrumbs">
    <li class="breadcrumbs-item"><a href="/">Home</a></li>
    @if ($leaf)
        @foreach ($leaf->getAncestorsAndSelf() as $category)
            <li class="breadcrumbs-item">
                <a href="{{$category->url()}}">
                    {{$category->name}}
                </a>
            </li>
        @endforeach
    @endif
</ol>
