<h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
    {{ __('Profile Information') }}
</h2>

<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
    {{ __("Update your account's profile information and email address.") }}
</p>

<form method="post" action="{{ route('profile.update') }}" class="mt-3">
    @csrf
    @method('patch')

    <!-- Name -->
    <div class="form-group position-relative mb-4">
        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
               class="form-control animated-input dark:bg-gray-700 dark:text-gray-200 @error('name') is-invalid @enderror">
        <label for="name" class="floating-label text-gray-700 dark:text-gray-300">
            {{ __('Username') }}
        </label>
        @error('name')
        <div class="text-danger mt-2">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="row">
    <!-- First Name -->
    <div class="form-group position-relative mb-4 col-6">
        <input type="text" name="fname" id="fname" value="{{ old('fname', $user->fname) }}" required
               class="form-control animated-input dark:bg-gray-700 dark:text-gray-200 @error('fname') is-invalid @enderror">
        <label for="fname" class="floating-label text-gray-700 dark:text-gray-300">
            {{ __('First Name') }}
        </label>
        @error('fname')
        <div class="text-danger mt-2">
            {{ $message }}
        </div>
        @enderror
    </div>

    <!-- Last Name -->
    <div class="form-group position-relative mb-4 col-6">
        <input type="text" name="lname" id="lname" value="{{ old('lname', $user->lname) }}" required
               class="form-control animated-input dark:bg-gray-700 dark:text-gray-200 @error('lname') is-invalid @enderror">
        <label for="lname" class="floating-label text-gray-700 dark:text-gray-300">
            {{ __('Last Name') }}
        </label>
        @error('lname')
        <div class="text-danger mt-2">
            {{ $message }}
        </div>
        @enderror
    </div>
    </div>
    <!-- Email -->
    <div class="form-group position-relative mb-4">
        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
               class="form-control animated-input dark:bg-gray-700 dark:text-gray-200 @error('email') is-invalid @enderror">
        <label for="email" class="floating-label text-gray-700 dark:text-gray-300">
            {{ __('Email') }}
        </label>
        @error('email')
        <div class="text-danger mt-2">
            {{ $message }}
        </div>
        @enderror
    </div>

  <!-- Verification Status -->


    @if (session('status') === 'profile-updated')
    <div class="mt-3 alert alert-success" role="alert" >
        {{ __('Saved.') }}
    </div>

@endif

    <!-- Submit -->
    <div class="text-end  ">
    <button type="submit" class="easypc-btn" style="width: 100%; max-width: 150px;">
            {{ __('Save') }}
        </button>
    </div>

</form>

@if (!auth()->user()->hasVerifiedEmail())
<div class="mt-4 alert alert-warning" role="alert">
    {{ __('Your email is not verified.') }}
    <form method="post" action="{{ route('verification.send') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
            {{ __('Click here to resend the verification email.') }}
        </button>
    </form>
</div>
@endif
