@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upload</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {!! Form::open(['route' => 'upload', 'files' => true])!!}

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Select Sector</label>

                            <div class="col-md-6">
                                
                                {!! Form::select('sector', $sectors, null, ['class' => 'form-control ']) !!}
                            </div>
                        </div>
                         <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Upload File</label>

                            <div class="col-md-6">
                               {!!  Form::file('file') !!}

                                @error('token')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('submit') }}
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
