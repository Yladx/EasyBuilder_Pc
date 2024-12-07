<link rel="stylesheet" href="{{ asset('css/build/accordion.css') }}">

<div class="modal fade" id="buildDetailsModal" tabindex="-1" aria-labelledby="buildDetailsModalLabel" aria-hidden="true" data-route="{{ route('builds.info', ':id') }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buildDetailsModalLabel">Build Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>

@include('components.publish-modal')

@include('components.contact-modal')

