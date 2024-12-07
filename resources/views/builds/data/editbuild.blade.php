<div class="build-card">

<form action="{{ route('builds.update', $buildinfo->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')


    <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
    <img src="{{ asset('storage/' . $buildinfo->image) }}"
     class="card-img"
     alt="{{ $buildinfo->build_name }}">
    </div>

    <div class="build-info">
        <!-- Build Name -->
        <div class="mb-3">
            <label for="build_name" class="form-label">Build Name</label>
            <input type="text" class="form-control" id="build_name" name="build_name" value="{{ $buildinfo->build_name }}" readonly>
        </div>

        <!-- Selected Tags -->
        <div class="mb-3">
            <input type="hidden" id="tag" name="tag" value="{{ $buildinfo->tag }}" readonly>
        </div>

        <!-- Selected Tags Display -->
        <div id="selected-tags-container" class="mb-3">
            @foreach(explode(',', $buildinfo->tag) as $tag)
                @php
                    $tagColors = [
                        'Recommended' => 'success',
                        'Editing' => 'info',
                        'Office' => 'danger',
                        'Gaming' => 'primary',
                        'School' => 'warning'
                    ];
                    $color = $tagColors[trim($tag)] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $color }} text-white me-2">{{ trim($tag) }}</span>
            @endforeach
        </div>

        <!-- Tag Selection -->
        <div class="mb-3">
            <label class="form-label">Select Tags</label>
            <div id="checkbox-container">
                @foreach (['Gaming', 'Office', 'School'] as $tagOption)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="checkbox-{{ $tagOption }}"
                            value="{{ $tagOption }}"
                            onchange="updateTags()"
                            {{ in_array($tagOption, explode(',', $buildinfo->tag)) ? 'checked' : '' }}
                            disabled>
                        <label class="form-check-label" for="checkbox-{{ $tagOption }}">{{ $tagOption }}</label>
                    </div>
                @endforeach
            </div>
        </div>

       

        <!-- Build Note -->
        <div class="mb-3">
            <label for="build_note" class="form-label">Build Note</label>
            <textarea class="form-control" id="build_note" name="build_note" rows="3" readonly>{{ $buildinfo->build_note }}</textarea>
        </div>

        <!-- Published -->
        <div class="mb-3">
            <label for="published" class="form-label">Publish</label>
            <input type="hidden" name="published" value="0"> <!-- Hidden field for unchecked cases -->
            <input type="checkbox" id="published" name="published" value="1" {{ $buildinfo->published ? 'checked' : '' }} disabled>
        </div>
    </div>

    <div class="accordion mt-4" id="buildDetailsAccordion">
        @foreach(['gpu', 'cpu', 'motherboard', 'ram', 'storage', 'powerSupply', 'pcCase'] as $component)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ ucfirst($component) }}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ ucfirst($component) }}" aria-expanded="true" aria-controls="collapse{{ ucfirst($component) }}">
                        <strong>{{ ucfirst($component) }}:</strong>
                        @if ($component === 'ram')
                            <div class="ram-list">
                                @if ($rams && $rams->isNotEmpty())
                                    @foreach ($rams as $ram)
                                        <span>{{ $ram->name }}</span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </div>
                        @else
                            {{ $buildinfo->$component->name ?? 'N/A' }}
                        @endif
                    </button>
                </h2>
                <div id="collapse{{ ucfirst($component) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ ucfirst($component) }}" data-bs-parent="#buildDetailsAccordion">
                    <div class="accordion-body">
                        @if ($component === 'ram')
                            @if ($rams && $rams->isNotEmpty())
                                <table class="table table-bordered table-striped component-table">
                                    <tbody>
                                        <!-- Table Header -->


                                        <!-- Table Body -->
                                        @foreach ($rams->first()->getAttributes() as $attr => $value)
                                            @if (!in_array($attr, ['id', 'build_id', 'image', 'created_at', 'updated_at']))
                                                <tr>
                                                    <!-- Attribute Name in First Column -->
                                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $attr)) }}</strong></td>

                                                    <!-- Values for Each RAM -->
                                                    @foreach ($rams as $ram)
                                                        <td>{{ $ram->$attr ?? 'N/A' }}</td>
                                                    @endforeach
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>

                                </table>
                            @else
                                <p>No RAM found.</p>
                            @endif
                        @else
                            @if ($buildinfo->$component)
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped component-table">
                                            <tbody>
                                                @foreach ($buildinfo->$component->getFillable() as $attr)
                                                    @if (!in_array($attr, ['name', 'id', 'image']))
                                                        <tr>
                                                            <td><strong>{{ ucfirst(str_replace('_', ' ', $attr)) }}</strong></td>
                                                            <td>{{ $buildinfo->$component->$attr ?? 'N/A' }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Right Column -->

                                </div>
                            @else
                                <p>No {{ ucfirst($component) }} found.</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Accessories Section -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAccessories">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccessories" aria-expanded="true" aria-controls="collapseAccessories">
                    <strong>Accessories:</strong> {{ $buildinfo->accessories ?? 'N/A' }}
                </button>
            </h2>
            <div id="collapseAccessories" class="accordion-collapse collapse" aria-labelledby="headingAccessories" data-bs-parent="#buildDetailsAccordion">
                <div class="accordion-body">
                    <p><strong>Accessories Details:</strong> Information about additional accessories here.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Footer -->
   

<div class="sticky-footer bg-white modal-footer">
        <button type="button" class="btn btn-secondary" id="editButton">Edit</button>
        <button type="submit" class="btn btn-success d-none" id="saveButton">Save</button>
    </div>
    </form>
    </div>
    