@extends('image-uploader::layout')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('image-uploader.store') }}" enctype="multipart/form-data" name="demoForm" id="demoForm" style="margin-top: 10px;">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="form-group">
            <input type="file" name="file" class="btn btn-default" required="true" autocomplete="off" accept="image/*" />
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
@endsection
