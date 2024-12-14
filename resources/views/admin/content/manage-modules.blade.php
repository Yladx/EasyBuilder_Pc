<x-admin-layout>
    <div class="container-fluid px-md-5 px-xs-2 py-md-3">

        <div class="text text-white">Manage Modules</div>


<div class="card bg-secondary text-white text-center ">

    <p class="">Total Modules: {{ $statistics['totalModules'] }}</p>

</div>

<div class="container mt-5">
    <div class="row">
        @foreach($tags as $tag)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 text-center">
            <div class="folder">
                <div class="scrollable-content p-3">
                    <div class="row g-3 =">
                        @foreach($modules->where('tag', $tag) as $module)
                        <a class="module col-12 col-sm-6 col-md-4 " data-id="{{ $module->id }} "data-bs-toggle="modal" data-bs-target="#adminModal">
                            <div class="module-icon">
                                <i class="fa fa-file" style="font-size:38px;color:rgb(164, 164, 164)"></i>
                            </div>
                            <div class="module-title text-center mt-2 ">{{ $module->title }}</div>
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>
            <p class="mt-2 fw-semibold text-white" >{{ $tag }}</p>
        </div>
        @endforeach
    </div>
</div>



<!-- Custom Context Menu -->
<!-- Context Menu -->
<div id="customContextMenu" class="context-menu">
    <ul>
        <li id="viewEditOption" data-bs-toggle="modal" data-bs-target="#adminModal" >View/Edit</li>
        <li id="deleteOption">Delete</li>
    </ul>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>



<!-- Add Module Button -->
<div  class="position-fixed bottom-0 end-0 p-3">

    <button type="button" class="btn btn-dark d-flex align-items-center " data-bs-toggle="modal" data-bs-target="#adminModal"  id="addModuleButton">
        <!-- SVG Icon -->
        <box-icon name='book-add' type='solid' color='#ffffff' class="me-2"></box-icon>Add Module

    </button>
</div>

</div>
</div>

<script>
    const moduleCreateUrl = "{{ route('modules.create') }}";
    const moduleRoutes = {
        create: "{{ route('modules.create') }}",
        edit: "/admin/modules/",
        delete: "/admin/modules/destroy/"
    };
</script>

<script src="{{ asset('js/admin/manage-modules.js') }}"></script>

</x-admin-layout>
