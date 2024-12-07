<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="container p-4">
        <div class="row">
            <!-- Left Column: Profile Forms -->
            <div class="col-md-8 col-xs-12 ">

                    <!-- Update Profile Information -->

                        <div class="row mb-4">
                            @include('profile.partials.update-profile-information-form')
                        </div>

              <div class="row mb-4">
                            @include('profile.partials.update-password-form')
                        </div>

                    <!-- Delete User -->
                    <div class="row mb-4">
                 @include('profile.partials.delete-user-form')

                </div>



            </div>

            <!-- Right Column: Activity Logs -->
            <div class="col-md-4 col-xs-12 ">

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        {{ __('Activity Logs') }}
                    </h3>
                    @include('profile.partials.activitylog')

            </div>
        </div>
    </div>
</x-app-layout>
