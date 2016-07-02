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
          action="{{ route('staff.products.images.transfer-local') }}">
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
                                     alt="{{ $image->altText() }}" />
                            </td>
                            <td>
                                {{ $image->altText() }}
                            </td>
                            <td>
                                <small>
                                    <span class="glyphicon
                                        glyphicon-{{ $image->locationGlyph() }}"
                                          aria-hidden="true"></span>
                                    <a href="{{ $image->url() }}">
                                        {{ $image->url() }}
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
    @endif

@endsection
