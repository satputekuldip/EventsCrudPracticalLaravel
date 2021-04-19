<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('images', 'Images:') !!}

@if(isset($event->images))
    <div class="row">
        @foreach ($event->images as $image)
            <div id="image-box-{{ $image->id }}" class="col-sm-3 m-4">
                <img width="200" src="{{ url('/') . $image->path }}" alt="Image">

            </div>
        @endforeach
    </div>
@endif
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $event->name }}</p>
</div>

<!-- Starts At Field -->
<div class="col-sm-12">
    {!! Form::label('starts_at', 'Starts At:') !!}
    <p>{{ $event->starts_at }}</p>
</div>

<!-- Ends At Field -->
<div class="col-sm-12">
    {!! Form::label('ends_at', 'Ends At:') !!}
    <p>{{ $event->ends_at }}</p>
</div>

<!-- Type Field -->
<div class="col-sm-12">
    {!! Form::label('type', 'Type:') !!}
    <p>{{ $event->type }}</p>
</div>

<!-- Banner Field -->
<div class="col-sm-12">
    {!! Form::label('banner', 'Banner:') !!}
    @if(isset($event->banner))
        <div  id="banner-box" class="form-group col-sm-3 m-4">
            <img width="200" src="{{ url('/') . $event->banner }}" alt="Banner">

        </div>
    @endif
</div>

<!-- Venue Field -->
<div class="col-sm-12">
    {!! Form::label('venue', 'Venue:') !!}
    <p>{{ $event->venue }}</p>
</div>

<!-- Category Id Field -->
<div class="col-sm-12">
    {!! Form::label('category_id', 'Category Id:') !!}
    <p>{{ $event->category_id }}</p>
</div>

<!-- Price Field -->
<div class="col-sm-12">
    {!! Form::label('price', 'Price:') !!}
    <p>{{ $event->price }}</p>
</div>

