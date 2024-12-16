<x-admin-layout>
    <div class="container-fluid px-md-5 px-xs-2 py-md-3">
        <div class="text-white mb-3">
         
               <h4>Manage Activity Logs</h4>
             
                
            </div>
     
        <section class="tab-content mt-4"> 
            <div class="row"> 
                <div class="col-12 col-md-6">
                    <button id="toggle_filter" class="btn btn-dark mb-3">
                        <i class="fas fa-filter"></i> Toggle Filter
                    </button>
                </div>
                <div class="col-12 col-md-6 export-buttons text-end">
                    <button class="btn btn-success" id="export_pdf">Export PDF</button>
    
                    <button class="btn btn-info" id="export_excel">Export Excel</button> 
                </div>
            </div>

        
            <div class="d mb-4 filter-section" style="display: none;">
              
                <div class="card-body px-4">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="action_filter" class="text-white">Action:</label>
                            <select id="action_filter" class="form-select">
                                <option value="">All</option>
                                <option value="update">Update</option>
                                <option value="delete">Delete</option>
                                <option value="view">View</option>
                                <option value="login">Login</option>
                                <option value="logout">Logout</option>
                                <option value="create">Create</option>
                                <option value="request">Request</option>
                                <option value="rate">Rate</option>
                                <option value="verify">Verify</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="type_filter"  class="text-white">Type:</label>
                            <select id="type_filter" class="form-select">
                                <option value="">All</option>
                                <option value="build">Build</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from"  class="text-white">From Date:</label>
                            <input type="date" id="date_from" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to"  class="text-white">To Date:</label>
                            <input type="date" id="date_to" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button id="filter_button" class="btn btn-primary float-end">
                                <i class="fas fa-filter me-1"></i> Apply Filter
                            </button>
                            <button id="reset_filter" class="btn btn-secondary float-end me-2">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="mb-4">
                <div class="card-body table-responsive">
                    <table id="activityLogsTable" class="table table-dark table-striped table-hover table-bordered table-striped text-center" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Type</th>
                                <th>Activity</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($initialLogs as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $log['activity_timestamp'] }}</td>
                                    <td>{{ $log['user'] }}</td>
                                  <td><span class="badge badge-{{  
                                        $log['action'] == 'login' ? 'success' :  
                                        ($log['action'] == 'logout' ? 'danger' :  
                                        ($log['action'] == 'delete' ? 'danger' :  
                                        ($log['action'] == 'view' ? 'primary' :  
                                        ($log['action'] == 'request' ? 'violet' :  
                                        ($log['action'] == 'create' ? 'warning' :  
                                        ($log['action'] == 'rate' ? 'warning' :  
                                        ($log['action'] == 'update' ? 'info' :  
                                        ($log['action'] == 'verify' ? 'success' : 'primary')))))))) }}">{{ $log['action'] }}</span></td>
                                    <td>{{ $log['type'] }}</td>
                                    <td>{{ $log['activity'] }}</td>
                                    <td>{{ $log['activity_details'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Add DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Add DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Pass Laravel route to JavaScript
        const activityLogsDataUrl = "{{ route('admin.activity-logs.data') }}";
    </script>
    <script src="{{ asset('js/admin/manage-activitylogs.js') }}"></script>
 
 </x-admin-layout>
