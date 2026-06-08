@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    Parent Import
@stop
@endsection
@section('page-header')
@section('PageTitle')
    Parent Import
@stop
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('import_failures'))
                        <div class="alert alert-warning">
                            <ul class="mb-0">
                                @foreach(session('import_failures') as $failure)
                                    <li>{{ $failure }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <a href="{{ route('parents.import.template') }}" class="btn btn-primary">Download Parent Import Template</a>
                    </div>

                    <form action="{{ route('parents.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Excel File</label>
                            <input type="file" name="file" id="file" class="form-control-file" required>
                        </div>
                        <button type="submit" class="btn btn-success">Import Parents</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @toastr_js
    @toastr_render
@endsection
