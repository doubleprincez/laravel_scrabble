@extends('layout')
@section('title','Inscrivez-vous!')
@section('content')
    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-md-7">
                <img src="http://localhost/laravel_scrabble/public/img/logo.jpg" class="logo"
                     style="width:100%;height:550px">
            </div>
            <div class="col-md-5">
                <form method="POST" action="{{ route('game.start') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <h3>Set Avatar to proceed</h3>
                    </div>
                    <div class="form-group">
                        <div class="col-6 offset-3 imgUp">
                            <div class="imagePreview" style="width:100%; align-content: center"></div>
                            <label for="photo" class="btn btn-danger"
                                   style="padding-left:5px;width: 100%;bottom:0;height: 30px; ">
                                Choisir avatar<input type="file" id="photo" name="photo" class="uploadFile img"
                                                     value="Upload Photo" style="display: none;">
                            </label>
                            @error('photo')
                            <div class="alert alert-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nick" class="col-3 col-form-label text-md-right">{{ __('UserName') }}</label>

                        <div class="col-6">
                            <input id="nom" type="text" class="form-control @error('nick') is-invalid @enderror"
                                   name="nick" value="{{ isset($nick)?$nick:old('nick') }}" required autocomplete="nick"
                                   autofocus>

                            @error('nick')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-6 offset-3">
                            <button type="submit" class="btn btn-danger">
                                {{ __('Jouer') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--<div class="container inscription">--}}
    {{--    <!-- name and signup  -->--}}
    {{--    <div class="row justify-content-center ">--}}
    {{--        <div class="col-md-8">--}}
    {{--            <div class="card-body">--}}
    {{--                <form method="POST" action="{{ url('/') }}" enctype="multipart/form-data">--}}
    {{--                       @csrf        --}}
    <!-- Upload photo  -->
    {{--                        <div class="photo">--}}
    {{--                            <br>--}}
    {{--                            <div class="container">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-sm-2 imgUp">--}}
    {{--                                        <div class="imagePreview"></div>--}}
    {{--                                        <label class="btn btn-danger" style="padding-left:5px;width: 300px;height: 30px; ">--}}
    {{--                                            Choisir avatar<input type="file" name="photo" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">--}}
    {{--                                        </label>--}}
    {{--                                    </div><!-- col-2 -->--}}

    {{--                                </div><!-- row -->--}}
    {{--                            </div><!-- container -->--}}


    {{--                        </div><br>--}}
    {{--                        <div class="form-group row">--}}
    {{--                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nom') }}</label>--}}

    {{--                            <div class="col-md-6">--}}
    {{--                                <input id="nom" type="text" class="form-control @error('name') is-invalid @enderror" name="nom" value="{{ old('name') }}" required autocomplete="name" autofocus>--}}

    {{--                                @error('name')--}}
    {{--                                <span class="invalid-feedback" role="alert">--}}
    {{--                                    <strong>{{ $message }}</strong>--}}
    {{--                                </span>--}}
    {{--                                @enderror--}}
    {{--                            </div>--}}
    {{--                        </div>--}}

    {{--                </div>--}}

    {{--    <div class="form-group row mb-0">--}}
    {{--        <div class="col-md-6 offset-md-4">--}}
    {{--            <button type="submit" class="btn btn-danger">--}}
    {{--                {{ __('Jouer') }}--}}
    {{--            </button>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    {{--            </form>--}}

    {{--        </div>--}}
    {{--    </div>--}}

    {{--</div>--}}


@endsection