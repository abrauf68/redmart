@extends('layouts.master')

@section('title', __('Withdraw Requests'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Withdraw Requests') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Withdraw Requests List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Request By') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created at') }}</th>
                            @canany(['delete withdraw', 'update withdraw', 'view withdraw'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($withdraws as $index => $withdraw)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($withdraw->user)
                                        <div class="d-flex align-items-center">

                                            <!-- User Image -->
                                            <div class="avatar avatar-sm me-3">
                                                @if ($withdraw->user->image)
                                                    <img src="{{ asset($withdraw->user->image) }}" alt="Avatar"
                                                        class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($withdraw->user->name, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- User Info -->
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">
                                                    {{ $withdraw->user->name }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ $withdraw->user->email }}
                                                </small>
                                            </div>

                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ \App\Helpers\Helper::formatCurrency($withdraw->amount) }}</td>
                                <td>
                                    @if ($withdraw->status == 'pending')
                                        <span class="badge me-4 bg-label-warning">Pending</span>
                                    @elseif($withdraw->status == 'approved')
                                        <span class="badge me-4 bg-label-success">Approved</span>
                                    @elseif($withdraw->status == 'rejected')
                                        <span class="badge me-4 bg-label-danger">Rejected</span>
                                    @else
                                        <span class="badge me-4 bg-label-primary">Unknown</span>
                                    @endif
                                </td>
                                <td>{{ $withdraw->created_at->format('d M Y') }}</td>
                                @canany(['delete withdraw', 'update withdraw', 'view withdraw'])
                                    <td class="d-flex">
                                        @canany(['delete withdraw'])
                                            <form action="{{ route('dashboard.withdraws.destroy', $withdraw->id) }}"
                                                method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Withdraw') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update withdraw'])
                                            @if($withdraw->status == 'pending')
                                                <span class="text-nowrap">
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1 edit-withdraw-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editWithdrawModal"
                                                        data-id="{{ $withdraw->id }}" data-status="{{ $withdraw->status }}"
                                                        title="{{ __('Edit Withdraw') }}">
                                                        <i class="ti ti-edit ti-md"></i>
                                                    </a>
                                                </span>
                                            @endif
                                        @endcan
                                        @canany(['view withdraw'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.withdraws.show', $withdraw->id) }}"
                                                    class="btn btn-icon btn-text-warning waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Withdraw Details') }}">
                                                    <i class="ti ti-eye ti-md"></i>
                                                </a>
                                            </span>
                                        @endcan
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Withdraw Modal -->
    <div class="modal fade" id="editWithdrawModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Edit Withdraw Status</h4>
                    </div>
                    <form id="editWithdrawForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="withdraw_id" id="withdraw_id">

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Withdraw Status') }}</label>
                            <select name="status" id="status" class="form-select select2" required>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="approved">{{ __('Approved') }}</option>
                                <option value="rejected">{{ __('Rejected') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="admin_note" class="form-label">{{ __('Admin Note') }}</label>
                            <textarea class="form-control @error('admin_note') is-invalid @enderror" name="admin_note" id="admin_note" cols="20" rows="5" placeholder="{{ __('Enter admin note') }}" >{{ old('admin_note') }}</textarea>
                            @error('admin_note')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-3">Submit</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Edit Withdraw Modal -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.edit-withdraw-btn').on('click', function() {
                let withdrawId = $(this).data('id');
                let status = $(this).data('status');

                // Set hidden input
                $('#withdraw_id').val(withdrawId);

                // Set dropdown selected value
                $('#status').val(status);

                // Build form action URL using route name
                let actionUrl = "{{ route('dashboard.withdraws.update', ':id') }}";
                actionUrl = actionUrl.replace(':id', withdrawId);

                $('#editWithdrawForm').attr('action', actionUrl);
            });
        });
    </script>
@endsection
