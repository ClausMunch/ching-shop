@extends('layouts.staff.dashboard')

@section('page-title')
    Images
@endsection

@section('header')
    Images
@endsection

@section('content')

    <form method="post"
          class="form-inline"
          id="transfer-images-form"
          action="{{ route('catalogue.staff.products.images.transfer-local') }}">
        {{ csrf_field() }}
        <button type="submit"
                class="btn btn-success"
                title="Transfer local images to cloud storage.">
            <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true">
            </span>
            Transfer local images
        </button>
    </form>

    <hr>

    <h3>Image list</h3>

    @if (count($images))
        {{$images->links()}}
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>
                        Image
                    </th>
                    <th>
                        Alt text
                    </th>
                    <th>
                        Location
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($images as $image)
                    <tr>
                        <td>
                            <img class="img-responsive"
                                 width="128"
                                 src="{{ $image->sizeUrl('small') }}"
                                 srcset="{{ $image->srcSet() }}"
                                 alt="{{ $image->altText() }}"/>
                        </td>
                        <td>
                            <form class="form" method="post"
                                  action="{{route(
                                    'catalogue.staff.products.images.put-alt',
                                    [$image->id]
                                  )}}">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="input-group input-group-sm">
                                    <label for="alt-text"
                                           class="sr-only">Quantity</label>
                                    <input name="alt-text" type="text"
                                           id="image-{{$image->id}}-alt"
                                           class="form-control"
                                           value="{{$image->altText()}}"
                                           required>
                                    <span class="input-group-btn">
                        <button class="btn btn-success"
                                id="search-button"
                                type="submit">
                            <span class="glyphicon glyphicon-ok"></span>
                            <span class="sr-only">Save alt text {{$image->id}}</span>
                        </button>
                    </span>
                                </div>
                            </form>
                        </td>
                        <td>
                            <small>
                                    <span class="glyphicon
                                        glyphicon-{{ $image->locationGlyph() }}"
                                          aria-hidden="true"></span>
                                <a href="{{ $image->sizeUrl('small') }}">
                                    {{ $image->sizeUrl('small') }}
                                </a>
                            </small>
                            @if ($image->isInternal())
                                <hr>
                                <small>
                                    Local file: {{ $image->filename() }}
                                </small>
                            @endif
                        </td>
                        <td>
                            <form method="post"
                                  id="delete-image-form"
                                  action="{{ $location->deleteActionFor($image) }}">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{$images->links()}}
    @endif

@endsection
