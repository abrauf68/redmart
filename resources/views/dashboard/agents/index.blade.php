@extends('layouts.master')

@section('title', __('Agents'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Agents') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Agents List Table -->
        <div class="card">
            <div class="card-header">
                @canany(['create agent'])
                    <a href="{{ route('dashboard.agents.create') }}" class="add-new btn btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
                            class="d-none d-sm-inline-block">{{ __('Add New Agent') }}</span>
                    </a>
                @endcan
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Agent') }}</th>
                            <th>{{ __('Referrals') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete agent', 'update agent', 'view agent'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agents as $index => $agent)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">

                                        <!-- User Image -->
                                        <div class="avatar avatar-sm me-3">
                                            @if ($agent->image)
                                                <img src="{{ asset($agent->image) }}" alt="Avatar"
                                                    class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($agent->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- User Info -->
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">
                                                {{ $agent->name }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $agent->email }}
                                            </small>
                                        </div>

                                    </div>
                                </td>
                                <td>{{ $agent->referrals_count }}</td>
                                <td>{{ $agent->created_at->format('d M Y') }}</td>
                                <td>
                                    <span
                                        class="badge me-4 bg-label-{{ $agent->is_active == 'active' ? 'success' : 'danger' }}">{{ ucfirst($agent->is_active) }}</span>
                                </td>
                                @canany(['delete agent', 'update agent', 'view agent'])
                                    <td class="d-flex">
                                        @canany(['delete agent'])
                                            <form action="{{ route('dashboard.agents.destroy', $agent->id) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <a href="#" type="submit"
                                                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete Agent') }}">
                                                    <i class="ti ti-trash ti-md"></i>
                                                </a>
                                            </form>
                                        @endcan
                                        @canany(['update agent'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.agents.edit', $agent->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit Agent') }}">
                                                    <i class="ti ti-edit ti-md"></i>
                                                </a>
                                            </span>
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.agents.status.update', $agent->id) }}"
                                                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $agent->is_active == 'active' ? __('Deactivate Agent') : __('Activate Agent') }}">
                                                    @if ($agent->is_active == 'active')
                                                        <i class="ti ti-toggle-right ti-md text-success"></i>
                                                    @else
                                                        <i class="ti ti-toggle-left ti-md text-danger"></i>
                                                    @endif
                                                </a>
                                            </span>
                                        @endcan
                                        @canany(['view agent'])
                                            <span class="text-nowrap">
                                                <a href="{{ route('dashboard.agents.show', $agent->id) }}"
                                                    class="btn btn-icon btn-text-warning waves-effect waves-light rounded-pill me-1"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Agent Details') }}">
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
@endsection

@section('script')
    {{-- <script src="{{asset('assets/js/app-user-list.js')}}"></script> --}}
    <script>
        $(document).ready(function() {
            //
        });
    </script>
@endsection
