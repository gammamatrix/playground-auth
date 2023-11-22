@extends($package_config_auth['layout'], [
    // 'withSidebarLeft' => false,
    // 'withSidebarRight' => false,
])
@section('title', __('Verify Email'))

@section('breadcrumbs')
    <nav aria-label="breadcrumb" class="m-3">
        <ol class="breadcrumb">
            @if (Route::has('home'))
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">
                        {{ __('Home') }}
                    </a>
                </li>
            @endif
            @if (Route::has('login'))
                <li class="breadcrumb-item">
                    <a href="{{ route('login') }}">
                        {{ __('Login') }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ request()->url() }}">
                    {{ __('Verify Email') }}
                </a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify Email') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    {{ __('E-Mail Address') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Verify Email') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
