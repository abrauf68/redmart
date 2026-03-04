@extends('layouts.master')

@section('title', __('Recharge Requests'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Recharge Requests') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Recharge Requests List Table -->
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
                            @canany(['delete recharge', 'update recharge', 'view recharge'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recharges as $index => $recharge)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($recharge->user)
                                        <div class="d-flex align-items-center">

                                            <!-- User Image -->
                                            <div class="avatar avatar-sm me-3">
                                                @if ($recharge->user->image)
                                                    <img src="{{ asset($recharge->user->image) }}" alt="Avatar"
                                                        class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($recharge->user->name, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- User Info -->
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">
                                                    {{ $recharge->user->name }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ $recharge->user->email }}
                                                </small>
                                            </div>

                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ \App\Helpers\Helper::formatCurrency($recharge->amount) }}</td>
                                <td>
                                    @if ($recharge->status == 'pending')
                                        <span class="badge me-4 bg-label-warning">Pending</span>
                                    @elseif($recharge->status == 'completed')
                                        <span class="badge me-4 bg-label-success">Completed</span>
                                    @elseif($recharge->status == 'failed')
                                        <span class="badge me-4 bg-label-danger">Failed</span>
                                    @elseif($recharge->status == 'cancelled')
                                        <span class="badge me-4 bg-label-danger">Cancelled</span>
                                    @else
                                        <span class="badge me-4 bg-label-primary">Unknown</span>
                                    @endif
                                </td>
                                <td>{{ $recharge->created_at->format('d M Y') }}</td>
                                @canany(['delete recharge', 'update recharge', 'view recharge'])
                                    <td class="d-flex">
                                        @canany(['delete recharge'])
                                            <form action="{{ route('dashboard.recharges.destroy', $recharge->id) }}"
                                                method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Recharge') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update recharge'])
                                            @if($recharge->status == 'pending')
                                                <span class="text-nowrap">
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1 edit-recharge-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editRechargeModal"
                                                        data-id="{{ $recharge->id }}" data-status="{{ $recharge->status }}"
                                                        title="{{ __('Edit Recharge') }}">
                                                        <i class="ti ti-edit ti-md"></i>
                                                    </a>
                                                </span>
                                            @endif
                                        @endcan
                                        @canany(['view recharge'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.recharges.show', $recharge->id) }}"
                                                    class="btn btn-icon btn-text-warning waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Recharge Details') }}">
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

    <!-- Edit Recharge Modal -->
    <div class="modal fade" id="editRechargeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Edit Recharge Status</h4>
                    </div>
                    <form id="editRechargeForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="recharge_id" id="recharge_id">

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Recharge Status') }}</label>
                            <select name="status" id="status" class="form-select select2" required>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="completed">{{ __('Completed') }}</option>
                                <option value="failed">{{ __('Failed') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" cols="20" rows="5" placeholder="{{ __('Enter admin note') }}" >{{ old('description') }}</textarea>
                            @error('description')
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
    <!--/ Edit Recharge Modal -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.edit-recharge-btn').on('click', function() {
                let rechargeId = $(this).data('id');
                let status = $(this).data('status');

                // Set hidden input
                $('#recharge_id').val(rechargeId);

                // Set dropdown selected value
                $('#status').val(status);

                // Build form action URL using route name
                let actionUrl = "{{ route('dashboard.recharges.update', ':id') }}";
                actionUrl = actionUrl.replace(':id', rechargeId);

                $('#editRechargeForm').attr('action', actionUrl);
            });
        });
    </script>
@endsection
