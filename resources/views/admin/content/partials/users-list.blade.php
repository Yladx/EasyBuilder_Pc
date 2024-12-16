<section id="usersTableSection">
    <div class="row mb-3">
        <!-- Total Users Card -->
        <div class="col-md-4 col-sm-12 ">
            <div class="custom-card bg-blue text-white">
                <div class="icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="card-content">
                    <h5>Total Users</h5>
                    <p id="totalUsers" class="fs-2">{{ $userStats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Verified Users Card -->
        <div class="col-md-4 col-sm-12">
            <div class="custom-card bg-green text-white">
                <div class="icon">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="card-content">
                    <h5>Verified Users</h5>
                    <p id="verifiedUsers" class="fs-2">{{ $userStats['verified'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Unverified Users Card -->
        <div class="col-md-4 col-sm-12">
            <div class="custom-card bg-red text-white">
                <div class="icon">
                    <i class="fa-solid fa-exclamation-circle"></i>
                </div>
                <div class="card-content">
                    <h5>Unverified Users</h5>
                    <p id="unverifiedUsers" class="fs-2">{{ $userStats['unverified'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <div class="card-header d-flex justify-content-between align-items-center mb-3 p-1">
        <h5 class="text-white mb-0">Users List</h5>
    </div>
    <section class="tab-content">
        <!-- Users List Section -->
        <div class="d-flex justify-content-end mb-3">
            <button id="export_excel" class="btn btn-info btn-sm me-2">Export Excel</button>
            <button id="export_pdf" class="btn btn-success btn-sm">Export PDF</button>
        </div>

        <!-- Detached Controls Section -->
        <div class="controls-section ">
            <!-- Top row - Length and Filter -->
            <div class="d-flex flex-column flex-md-row gap-3 mb-3">
                <div class="d-flex flex-wrap gap-3 flex-grow-1">
                    <!-- Entries length control -->
                    <div class="d-flex align-items-center">
                        <label class="text-white me-2">Show</label>
                        <select class="form-select form-select-sm" style="width: 80px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label class="text-white ms-2">entries</label>
                    </div>
                    <!-- Filter control -->
                    <div class="d-flex align-items-center">
                        <select class="form-select form-select-sm" id="verificationFilter" style="width: 150px;">
                            <option value="">All Users</option>
                            <option value="verified">Verified Users</option>
                            <option value="unverified">Unverified Users</option>
                        </select>
                    </div>
                </div>
                <!-- Search control -->
                <div class="search-wrapper ms-md-auto">
                    <input type="search" class="form-control form-control-sm w-100" 
                           placeholder="Search users..." 
                           style="min-width: 200px; max-width: 300px;">
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="maintable table-responsive"  style="max-height: 250px; overflow-y: auto;">
            <table class="table table-dark table-striped table-hover" id="userDataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                  
                        <th>Email</th>
                        <th>Email Verified At</th>
                        <th>Created At</th>
                        <th>Builds [Total / Published / Unpublished]</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @foreach ($users as $user)
                        <tr onclick="showUserInfo({{ $user->id }})" style="cursor: pointer;">
                            <td data-label="ID">{{ $user->id }}</td>
                            <td data-label="Username">{{ $user->name }}</td>
                            <td data-label="First Name">{{ $user->fname }}</td>
                            <td data-label="Last Name">{{ $user->lname }}</td>
                      
                            <td data-label="Email">{{ $user->email }}</td>
                            <td data-label="Email Verified At">{{ $user->email_verified_at ?? 'Not verified' }}</td>
                            <td data-label="Created At">{{ $user->created_at }}</td>
                            <td class="text-center" data-label="Builds">
                                <span class="badge bg-primary">{{ $user->total_builds ?? 0 }}</span> / 
                                <span class="badge bg-success">{{ $user->published_builds ?? 0 }}</span> / 
                                <span class="badge bg-danger">{{ $user->unpublished_builds ?? 0 }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Bottom Info and Pagination -->
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center  mt-3">
            <div class="text-white order-2 order-md-1 text-center text-md-start" id="table-info"></div>
            <nav aria-label="Table navigation" class="order-1 order-md-2 d-flex justify-content-center">
                <ul class="pagination pagination-sm mb-0" id="users-table-pagination"></ul>
            </nav>
        </div>
    </section>

</section>
<script src="{{ asset('js\admin\userlist.js') }}"></script>
