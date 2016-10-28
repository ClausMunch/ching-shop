<form method="post"
      id="category-{{$category->id}}-parent-form"
      class="form-inline"
      action="{{route('categories.put-parent', [$category->id])}}">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <label for="parent-id" class="sr-only">
        Select category
    </label>
    <div class="input-group input-group-sm">
        <select class="form-control"
                name="parent-id"
                id="{{$category->id}}-parent-id">
            <option value="-1">
                None
            </option>
            @foreach ($allCategories as $potentialParent)
                @unless($potentialParent->id === $category->id)
                    <option value="{{$potentialParent->id}}"
                            id="category-{{$category->id}}-option-{{$potentialParent->id}}"
                            @if ($category->parent && $category->parent->id === $potentialParent->id)
                            selected
                            @endif
                    >
                        {{$potentialParent->name}}
                    </option>
                @endunless
            @endforeach
        </select>
        <span class="input-group-btn">
            <button type="submit"
                    id="save-parent-button"
                    class="btn btn-success">
                <span class="glyphicon glyphicon-check"></span>
                <span class="sr-only">Set {{$category->name}}'s parent</span>
            </button>
        </span>
    </div>
</form>
