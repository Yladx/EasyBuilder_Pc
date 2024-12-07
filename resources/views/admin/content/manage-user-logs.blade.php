
<x-admin-layout>

    <div class="container-fluid px-md-5 px-xs-2 py-md-3">
        <div class="text text-white">Manage User w/ Log</div>


<div class="row">
    <div class="col-md-8 col-xs-12">
        <div class="row ">
            <div class="col-12">
                <div class="container-fluid">
                    @include('admin.content.partials.users-list')
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xs-12">
        @include('admin.content.partials.recentactivity')
    </div>


</div>
</div>
</x-admin-layout>
