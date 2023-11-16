<div class="card my-1">
    <div class="card-body">

        <h2>Authentication</h2>

        <div class="row">

            <div class="col-sm-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        {{ __('Handling your account credentials') }}
                        <small class="text-muted">
                            {{ __('authentication, registration and passwords') }}
                        </small>
                    </div>
                    <ul class="list-group list-group-flush">
                        <a href="{{ route('verification.notice') }}" class="list-group-item list-group-item-action">
                            {{ __('Verify Email') }}
                        </a>
                        <a href="{{ route('password.request') }}" class="list-group-item list-group-item-action">
                            {{ __('Forgot Password') }}
                        </a>
                        <a href="{{ route('register') }}" class="list-group-item list-group-item-action">
                            {{ __('Register') }}
                        </a>
                        <a href="{{ route('login') }}" class="list-group-item list-group-item-action">
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('logout') }}" class="list-group-item list-group-item-action">
                            {{ __('Logout') }}
                        </a>
                    </ul>
                </div>
            </div>

        </div>

    </div>
</div>
