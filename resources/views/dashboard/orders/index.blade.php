@extends('layouts.master')

@section('title', __('Orders'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Orders') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Orders List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Order By') }}</th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Commission') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created at') }}</th>
                            @canany(['delete order', 'update order', 'view order'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($order->user)
                                        <div class="d-flex align-items-center">

                                            <!-- User Image -->
                                            <div class="avatar avatar-sm me-3">
                                                @if ($order->user->image)
                                                    <img src="{{ asset($order->user->image) }}" alt="Avatar"
                                                        class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- User Info -->
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">
                                                    {{ $order->user->name }}
                                                </span>
                                                <small class="text-muted">
                                                    {{ $order->user->email }}
                                                </small>
                                            </div>

                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $order->product->name }}</td>
                                <td>{{ \App\Helpers\Helper::formatCurrency($order->total) }}</td>
                                <td>{{ \App\Helpers\Helper::formatCurrency($order->commission) }}</td>
                                <td>
                                    @if ($order->status == 'pending')
                                        <span class="badge me-4 bg-label-warning">Pending</span>
                                    @elseif($order->status == 'completed')
                                        <span class="badge me-4 bg-label-success">Completed</span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge me-4 bg-label-danger">Cancelled</span>
                                    @else
                                        <span class="badge me-4 bg-label-primary">Unknown</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                @canany(['delete order', 'update order', 'view order'])
                                    <td class="d-flex">
                                        @canany(['delete order'])
                                            <form action="{{ route('dashboard.orders.destroy', $order->id) }}"
                                                method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Order') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update order'])
                                            @if($order->status == 'pending')
                                                <span class="text-nowrap">
                                                    <a href="javascript:void(0)"
                                                        class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1 edit-order-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editOrderModal"
                                                        data-id="{{ $order->id }}" data-status="{{ $order->status }}"
                                                        title="{{ __('Edit Order') }}">
                                                        <i class="ti ti-edit ti-md"></i>
                                                    </a>
                                                </span>
                                            @endif
                                        @endcan
                                        @canany(['view order'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.orders.show', $order->id) }}"
                                                    class="btn btn-icon btn-text-warning waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Order Details') }}">
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

    <!-- Edit Order Modal -->
    <div class="modal fade" id="editOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Edit Order Status</h4>
                    </div>
                    <form id="editOrderForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_id" id="order_id">

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('Order Status') }}</label>
                            <select name="status" id="status" class="form-select select2" required>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="completed">{{ __('Completed') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
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
    <!--/ Edit Order Modal -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.edit-order-btn').on('click', function() {
                let orderId = $(this).data('id');
                let status = $(this).data('status');

                // Set hidden input
                $('#order_id').val(orderId);

                // Set dropdown selected value
                $('#status').val(status);

                // Build form action URL using route name
                let actionUrl = "{{ route('dashboard.orders.update', ':id') }}";
                actionUrl = actionUrl.replace(':id', orderId);

                $('#editOrderForm').attr('action', actionUrl);
            });
        });
    </script>
@endsection
