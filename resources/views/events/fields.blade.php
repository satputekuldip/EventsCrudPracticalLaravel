<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'maxlength' => 255,'required']) !!}
</div>

<!-- Starts At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('starts_at', 'Starts At:') !!}
    {!! Form::text('starts_at', null, ['class' => 'form-control','id'=>'starts_at','required']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#starts_at').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Ends At Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ends_at', 'Ends At:') !!}
    {!! Form::text('ends_at', null, ['class' => 'form-control','id'=>'ends_at','required']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#ends_at').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::select('type', ['PAID'=>'PAID','FREE'=>'FREE'],null, ['class' => 'form-control','required']) !!}
</div>

<!-- Banner Field -->
<div class="input-group col-sm-8">
    {!! Form::label('banner', 'Banner:', ['class'=>"input-group-text"]) !!}
    {!! Form::file('banner', ['class' => 'form-control', 'required']) !!}

</div>

<!-- Venue Field -->
<div class="form-group col-sm-6">
    {!! Form::label('venue', 'Venue:') !!}
    {!! Form::text('venue', null, ['class' => 'form-control','maxlength' => 255, 'required']) !!}
</div>

<!-- Category Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('category_id', 'Category:') !!}
    {!! Form::select('category_id', $categories ,null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price:') !!}
    {!! Form::number('price', 0, ['class' => 'form-control']) !!}
</div>


<!-- Price Field -->
<div class="form-group col-sm-12">

    {!! Form::label('images', 'Images:') !!}
    {!! Form::file('images[]', ['class' => 'form-control', 'required', 'multiple', 'minlength'=>2]) !!}

    @if(isset($event->images))
        <div class="row">
            @foreach ($event->images as $image)
                <div id="image-box-{{ $image->id }}" class="col-sm-3 m-4">
                    <img width="200" src="{{ url('/') . $image->path }}" alt="Image">
                    <button class="btn btn-sm btn-danger m-1"
                            onclick="deleteImage({{ $image->id }}); return false;"><i
                            class="fa fa-trash"></i></button>

                </div>
            @endforeach
        </div>
    @endif
</div>

@push('page_css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endpush
@push('page_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        function deleteImage(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('events.deleteImage') }}',
                headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
                data: {
                    id: id
                },
                success: function (data) {
                    console.log(data)
                    $("#btn-send-request-" + id).html('Request Sent').addClass("disabled");
                    if (!data.success) {
                        toastr.error("Something Went To Wrong", "Error");
                    } else {
                        toastr.success(data.message, "Success");
                        let div = document.getElementById("image-box-" + id);
                        div.remove();
                    }
                },
                error: function (data) {
                    $(".full-display").hide();
                    // $("#btn_contact_us").button('reset');
                    if (data.responseJSON.data != null) {
                        $.each(data.responseJSON.data, function (key, value) {
                            toastr.error(value, "Error");
                        });
                    } else {
                        toastr.error(data.responseJSON.responseMessage, "Error");
                    }
                },
            });
        }
    </script>
@endpush


