@extends('layouts.mainlayout')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <!-- Profile Photo (vertical layout: image above, controls below) -->
                    <div class="mb-3">
                        <style>
                        /* Disable hover effects inside this box and make text smaller for the upload button */
                        .no-hover * { transition: none !important; }
                        .no-hover:hover { transform: none !important; box-shadow: none !important; }
                        .no-hover .btn { box-shadow: none !important; }
                        .upload-text { font-size: 0.85rem; }
                        </style>

                        <div class="d-flex flex-column align-items-center gap-2 no-hover">
                            <img src="{{ auth()->user()->photo_url }}"
                                 alt="Profile Photo"
                                 class="rounded-circle"
                                 id="profilePhotoPreview"
                                 style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #6A38C2;">

                            <div class="w-100" style="max-width:220px;">
                                <form id="uploadPhotoForm" enctype="multipart/form-data" class="mb-2 w-100 text-center">
                                    @csrf
                                    <input type="file"
                                           id="photoInput"
                                           name="photo"
                                           accept="image/*"
                                           class="d-none"
                                           onchange="previewAndUploadPhoto(this)">
                                    <button type="button"
                                            class="btn btn-sm w-100 mb-2"
                                            style="background: #6A38C2; color: white; padding: .35rem .6rem;"
                                            onclick="document.getElementById('photoInput').click()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block me-1">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                        <span class="upload-text">Upload Photo</span>
                                    </button>
                                </form>

                                @if(auth()->user()->profile_photo || auth()->user()->image_path)
                                <form action="{{ route('profile.photo.remove') }}" method="POST" class="w-100">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="font-size:0.85rem; padding: .28rem .55rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block me-1">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                        <span class="upload-text">Remove Photo</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                    <p class="text-secondary small mb-3">{{ auth()->user()->email }}</p>
                    
                    <span class="badge mb-2" style="background: #6A38C2;">
                        {{ ucfirst(auth()->user()->role ?? 'user') }}
                    </span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="list-group mt-3">
                <a href="#profile-info" class="list-group-item list-group-item-action active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block me-2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Profile Information
                </a>
                <a href="#change-password" class="list-group-item list-group-item-action">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block me-2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Change Password
                </a>
                <a href="#delete-account" class="list-group-item list-group-item-action text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="d-inline-block me-2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Delete Account
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Profile Information -->
            <div class="card shadow-sm mb-4" id="profile-info">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Profile Information</h4>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Change Password -->
            <div class="card shadow-sm mb-4" id="change-password">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Change Password</h4>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card shadow-sm mb-4" id="delete-account">
                <div class="card-body">
                    <h4 class="fw-bold mb-4 text-danger">Delete Account</h4>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewAndUploadPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('profilePhotoPreview').src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
        
        // Auto-submit the form
        const formData = new FormData(document.getElementById('uploadPhotoForm'));
        
        fetch('{{ route("profile.photo.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Photo uploaded successfully!');
            } else {
                alert('Failed to upload photo. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}
</script>
@endpush
