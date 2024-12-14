<x-admin-layout>

    <div class="container-fluid px-md-5 px-xs-2 py-md-3">

        <div class="text-white mb-3">
            <h4>Manage Builds</h4>
        </div>

        <!-- Main Content Row -->
        <div class="row g-3 mt-4">
            <div class="col-lg-4 col-md-12">
                <div class="container">
                    <div class="row mt-xs-4">
                        <!-- Total Builds Card -->
                        <div class="custom-card bg-yellow">
                            <div class="icon">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <div class="card-content">
                                <h5>Total Builds</h5>
                                <p  class="fs-2">{{ $statistics['totalBuilds'] }}</p>
                                <span">Total Number of Builds</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- User Builds Card -->
                        <div class="custom-card bg-blue">
                            <div class="icon">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div class="card-content">
                                <h5>User Builds</h5>
                                <p  class="fs-2">{{ $statistics['userBuildsCount'] }}</p>
                                <span">Total Number of Builds</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Recommended Builds Card -->
                        <div class="custom-card bg-green">
                            <div class="icon">
                                <i class="fa-solid fa-thumbs-up"></i>
                            </div>
                            <div class="card-content">
                                <h5>Recommended Builds</h5>
                                <p>{{ $statistics['recommendedBuildsCount'] }}</p>
                                <span">Total Number of Builds</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Builds Table Column -->
            <div class="col-lg-8 col-md-12">
                <div class="  text-white">

                    <div class="card-body p-0">
                        @include('admin.content.partials.build-table')
                    </div>
                </div>
            </div>

            <!-- Statistics Column -->

        </div>
    </div>



    <script src="{{ asset('js/admin/manage-builds.js') }}"></script>


</x-admin-layout>
