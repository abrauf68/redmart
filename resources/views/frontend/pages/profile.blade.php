@extends('frontend.layouts.master')

@section('title', 'Profile')

@section('css')
    <style>
        .profile-wrapper {
            margin-top: 40px;
            margin-bottom: 40px;
            color: #fff;
        }

        .profile-card {
            background: #1F2E3A;
            border-radius: 25px;
            padding: 25px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #D8C79A;
            margin-bottom: 15px;
        }

        .credit-badge {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 13px;
            display: inline-block;
            margin-top: 8px;
        }

        .profile-tabs {
            background: #101820;
            border-radius: 20px;
            padding: 8px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .profile-tabs button {
            flex: 1;
            background: transparent;
            border: none;
            color: #ccc;
            padding: 10px;
            border-radius: 15px;
            font-weight: 500;
        }

        .profile-tabs button.active {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            font-weight: bold;
        }

        .form-control {
            border-radius: 12px;
            background: #101820;
            border: none;
            color: #fff;
        }

        .form-control:focus {
            background: #101820;
            color: #fff;
            border: 1px solid #D8C79A;
            box-shadow: none;
        }

        .btn-gold {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            font-weight: bold;
            border-radius: 15px;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .email-display {
            background: #101820;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            font-size: 14px;
            color: #ccc;
        }

        .email-badge {
            background: #28a745;
            color: #fff;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 12px;
            margin-left: 8px;
        }

        #alertModal .modal-content {
            background: linear-gradient(180deg, #1F2E3A, #17232D);
            color: #fff;
            padding: 15px;
        }

        #alertModal .modal-title {
            font-weight: bold;
        }

        #alertModal .btn-success {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="container profile-wrapper">

        <div class="profile-card">

            <!-- PROFILE HEADER -->
            <div class="profile-header">
                <img src="{{ $user->image ? asset($user->image) : asset('assets/img/default/user.png') }}"
                    class="profile-avatar">

                <h5 class="mb-1">{{ $user->name }}</h5>
                <small class="text-muted d-block">{{ '@' . $user->username }}</small>

                <!-- Email Display (Non Changeable) -->
                <div class="email-display mt-2">
                    <i class="fa fa-envelope me-1"></i>
                    {{ $user->email }}
                    <span class="email-badge">Verified</span>
                </div>

                <div class="credit-badge mt-3">
                    Credit Score: {{ $user->credit_score }}
                </div>
            </div>


            <!-- TAB BUTTONS -->
            <div class="profile-tabs">
                <button class="tab-btn active" data-tab="personal">Personal</button>
                <button class="tab-btn" data-tab="bank">Bank</button>
                <button class="tab-btn" data-tab="security">Security</button>
            </div>


            <!-- ================= PERSONAL INFO ================= -->
            <div class="tab-content" id="personal">
                <h6 class="section-title">Personal Information</h6>

                <form action="{{ route('frontend.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Profile Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <button class="btn btn-gold w-100 mt-2">
                        Save Changes
                    </button>
                </form>
            </div>


            <!-- ================= BANK DETAILS ================= -->
            <div class="tab-content d-none" id="bank">
                <h6 class="section-title">Bank Details</h6>

                <form action="{{ route('frontend.bank-details.update') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Bank Name</label>
                            <input type="text" name="bank_name" value="{{ optional($user->bankDetails)->bank_name }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Beneficiary Name</label>
                            <input type="text" name="beneficiary_name"
                                value="{{ optional($user->bankDetails)->beneficiary_name }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Account Number</label>
                            <input type="text" name="account_number"
                                value="{{ optional($user->bankDetails)->account_number }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>IFSC Code</label>
                            <input type="text" name="ifsc_code" value="{{ optional($user->bankDetails)->ifsc_code }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Branch</label>
                            <input type="text" name="branch" value="{{ optional($user->bankDetails)->branch }}"
                                class="form-control">
                        </div>
                    </div>

                    <button class="btn btn-gold w-100 mt-2">
                        Save Bank Details
                    </button>
                </form>
            </div>


            <!-- ================= SECURITY ================= -->
            <div class="tab-content d-none" id="security">
                <h6 class="section-title">Change Password</h6>

                <form action="{{ route('frontend.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <button class="btn btn-gold w-100 mt-2">
                        Update Password
                    </button>
                </form>
            </div>

        </div>
    </div>


    <!-- Dynamic Alert Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-20" style="background:#1F2E3A;color:#fff;">

                <div class="modal-header border-0">
                    <h5 class="modal-title" id="alertModalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center" id="alertModalBody">
                    <!-- Dynamic message -->
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success rounded-15 w-100" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        const buttons = document.querySelectorAll('.tab-btn');
        const tabs = document.querySelectorAll('.tab-content');

        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                buttons.forEach(b => b.classList.remove('active'));
                tabs.forEach(t => t.classList.add('d-none'));

                this.classList.add('active');
                document.getElementById(this.dataset.tab).classList.remove('d-none');
            });
        });
    </script>

    <script>
        window.addEventListener('load', function() {

            // Check if session has success or error
            @if (session('success') || session('error'))
                // Hide the loader immediately
                let loader = document.querySelector('.loader-wrap');
                if (loader) loader.style.display = 'none';
            @endif

            @if (session('success'))
                let successModal = new bootstrap.Modal(document.getElementById('alertModal'));
                document.getElementById('alertModalTitle').innerText = 'Success';
                document.getElementById('alertModalBody').innerText = "{{ session('success') }}";
                successModal.show();
            @endif

            @if (session('error'))
                let errorModal = new bootstrap.Modal(document.getElementById('alertModal'));
                document.getElementById('alertModalTitle').innerText = 'Error';
                document.getElementById('alertModalBody').innerText = "{{ session('error') }}";
                errorModal.show();
            @endif

        });
    </script>
@endsection
