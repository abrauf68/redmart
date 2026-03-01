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
                <h6 class="section-title">Bank / Crypto Details</h6>

                <form action="{{ route('frontend.bank-details.update') }}" method="POST">
                    @csrf

                    <div class="row">

                        <!-- ===== Method Selection ===== -->
                        <div class="col-12 mb-3">
                            <label>Select Method</label>
                            <select name="method" id="methodSelect" class="form-control">
                                <option value="bank"
                                    {{ optional($user->bankDetails)->method == 'bank' ? 'selected' : '' }}>Bank</option>
                                <option value="crypto"
                                    {{ optional($user->bankDetails)->method == 'crypto' ? 'selected' : '' }}>Crypto
                                </option>
                            </select>
                        </div>

                        <!-- ================= Bank Details ================= -->
                        <div id="bankFields" class="col-12"
                            style="display: {{ optional($user->bankDetails)->method == 'bank' ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Bank Name</label>
                                    <select name="bank_name" class="form-control">
                                        <option value="">-- Select Bank --</option>
                                        <option value="State Bank of India"
                                            {{ optional($user->bankDetails)->bank_name == 'State Bank of India' ? 'selected' : '' }}>
                                            State Bank of India</option>
                                        <option value="HDFC Bank"
                                            {{ optional($user->bankDetails)->bank_name == 'HDFC Bank' ? 'selected' : '' }}>
                                            HDFC
                                            Bank</option>
                                        <option value="ICICI Bank"
                                            {{ optional($user->bankDetails)->bank_name == 'ICICI Bank' ? 'selected' : '' }}>
                                            ICICI Bank</option>
                                        <option value="Axis Bank"
                                            {{ optional($user->bankDetails)->bank_name == 'Axis Bank' ? 'selected' : '' }}>
                                            Axis
                                            Bank</option>
                                        <option value="Punjab National Bank"
                                            {{ optional($user->bankDetails)->bank_name == 'Punjab National Bank' ? 'selected' : '' }}>
                                            Punjab National Bank</option>
                                        <option value="Bank of Baroda"
                                            {{ optional($user->bankDetails)->bank_name == 'Bank of Baroda' ? 'selected' : '' }}>
                                            Bank of Baroda</option>
                                        <option value="Kotak Mahindra Bank"
                                            {{ optional($user->bankDetails)->bank_name == 'Kotak Mahindra Bank' ? 'selected' : '' }}>
                                            Kotak Mahindra Bank</option>
                                        <option value="IndusInd Bank"
                                            {{ optional($user->bankDetails)->bank_name == 'IndusInd Bank' ? 'selected' : '' }}>
                                            IndusInd Bank</option>
                                        <option value="Yes Bank"
                                            {{ optional($user->bankDetails)->bank_name == 'Yes Bank' ? 'selected' : '' }}>
                                            Yes
                                            Bank</option>
                                        <option value="Union Bank of India"
                                            {{ optional($user->bankDetails)->bank_name == 'Union Bank of India' ? 'selected' : '' }}>
                                            Union Bank of India</option>
                                        <option value="Other"
                                            {{ optional($user->bankDetails)->bank_name == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>
                                    </select>
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
                                    <label>Account Type</label>
                                    <select name="account_type" class="form-control">
                                        <option value="">-- Select Type --</option>
                                        <option value="savings"
                                            {{ optional($user->bankDetails)->account_type == 'savings' ? 'selected' : '' }}>
                                            Savings</option>
                                        <option value="current"
                                            {{ optional($user->bankDetails)->account_type == 'current' ? 'selected' : '' }}>
                                            Current</option>
                                        <option value="salary"
                                            {{ optional($user->bankDetails)->account_type == 'salary' ? 'selected' : '' }}>
                                            Salary</option>
                                        <option value="fixed_deposit"
                                            {{ optional($user->bankDetails)->account_type == 'fixed_deposit' ? 'selected' : '' }}>
                                            Fixed Deposit</option>
                                        <option value="nri"
                                            {{ optional($user->bankDetails)->account_type == 'nri' ? 'selected' : '' }}>NRI
                                        </option>
                                        <option value="recurring_deposit"
                                            {{ optional($user->bankDetails)->account_type == 'recurring_deposit' ? 'selected' : '' }}>
                                            Recurring Deposit</option>
                                        <option value="demat"
                                            {{ optional($user->bankDetails)->account_type == 'demat' ? 'selected' : '' }}>
                                            Demat
                                        </option>
                                        <option value="others"
                                            {{ optional($user->bankDetails)->account_type == 'others' ? 'selected' : '' }}>
                                            Others</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>IFSC Code</label>
                                    <input type="text" name="ifsc_code"
                                        value="{{ optional($user->bankDetails)->ifsc_code }}" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Branch</label>
                                    <input type="text" name="branch"
                                        value="{{ optional($user->bankDetails)->branch }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- ================= Crypto Details ================= -->
                        <div id="cryptoFields" class="col-12"
                            style="display: {{ optional($user->bankDetails)->method == 'crypto' ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Wallet Type</label>
                                    <select name="crypto_type" class="form-control">
                                        <option value="">-- Select Wallet Type --</option>
                                        <option value="BTC"
                                            {{ optional($user->bankDetails)->crypto_type == 'BTC' ? 'selected' : '' }}>BTC
                                        </option>
                                        <option value="ETH"
                                            {{ optional($user->bankDetails)->crypto_type == 'ETH' ? 'selected' : '' }}>ETH
                                        </option>
                                        <option value="USDT"
                                            {{ optional($user->bankDetails)->crypto_type == 'USDT' ? 'selected' : '' }}>USDT
                                        </option>
                                        <option value="BUSD"
                                            {{ optional($user->bankDetails)->crypto_type == 'BUSD' ? 'selected' : '' }}>BUSD
                                        </option>
                                        <option value="BNB"
                                            {{ optional($user->bankDetails)->crypto_type == 'BNB' ? 'selected' : '' }}>BNB
                                        </option>
                                        <option value="Other"
                                            {{ optional($user->bankDetails)->crypto_type == 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Wallet Address</label>
                                    <input type="text" name="crypto_address"
                                        value="{{ optional($user->bankDetails)->crypto_address }}" class="form-control">
                                </div>
                            </div>
                        </div>

                    </div>

                    <button class="btn btn-gold w-100 mt-2">Save Details</button>
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

    <!-- ================= JS to Toggle Fields ================= -->
    <script>
        window.addEventListener('load', function() {
            const methodSelect = document.getElementById('methodSelect');
            const bankFields = document.getElementById('bankFields');
            const cryptoFields = document.getElementById('cryptoFields');

            // Initial load: agar user ka method nahi set hai, default 'bank' rakhein
            if (!methodSelect.value) {
                methodSelect.value = 'bank';
            }

            // Show/hide fields on load
            if (methodSelect.value === 'bank') {
                bankFields.style.display = 'block'; // block rakhein
                cryptoFields.style.display = 'none';
            } else {
                bankFields.style.display = 'none';
                cryptoFields.style.display = 'block'; // block rakhein
            }

            // Toggle fields on change
            methodSelect.addEventListener('change', function() {
                if (this.value === 'bank') {
                    bankFields.style.display = 'block';
                    cryptoFields.style.display = 'none';
                } else if (this.value === 'crypto') {
                    bankFields.style.display = 'none';
                    cryptoFields.style.display = 'block';
                }
            });
        });
    </script>
@endsection
