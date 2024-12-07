@extends('layouts.noheader')

@section('content')
<style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
       
        body {
 margin: 0;
 padding: 0;
 background-color: grey;
     }

        /* Create a pseudo-element for the background */
        body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('{{ asset("css/bg3.jfif") }}') no-repeat center center fixed;
        background-size: cover;
        filter: opacity(0.58) ; /* Apply filters here */
        z-index: -1; /* Ensure the background is behind all content */
        }
</style>


<div class="d-flex justify-content-center align-items-center vh-100 ">

        <!-- Login Card -->
        <div class="card p-4 border border-dark" style="width: 400px; border-radius: 15px; box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2); background-color: #323131 ">
            <!-- Card Header -->
            <div class="card-headerbg-none text-center text-light border-0 m-5">
                <h3 class="mb-1"><strong>WELCOME BACK!</strong></h3>
                <p class="text-light mb-0">ADMIN</p>
            </div>

            <!-- Card Body -->

            <!-- Card Body -->
            <div class="card-body mt-4" >

                    <form method="POST"  id="adminLoginForm" action="{{ route('admin.login.submit') }}">
                        @csrf

                        <!-- Username Field -->
                        <div class="form-group position-relative mb-5">
                            <input type="text" class="form-control animated-input" name="adminUsername" id="adminUsername" required>
                            <label for="adminUsername" class="floating-label">Username</label>
                        </div>

                        <!-- Password Field -->
                        <div class="form-group position-relative mb-4">
                            <input type="password" class="form-control animated-input" name="adminPassword" id="adminPassword" required>
                            <label for="adminPassword" class="floating-label">Password</label>
                        </div>

                        {{-- Display error messages --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <!-- Submit Button -->
                    <div class="text-center mb-5">
                        <button type="submit" class="btn bg-black w-100 p-2 mt-3 text-light fw-bold" style="font-size: 1rem;">Sign In</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
