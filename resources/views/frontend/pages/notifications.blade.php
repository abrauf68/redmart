@extends('frontend.layouts.master')

@section('title', 'Notifications')

@section('css')
    <style>
        .notifications-wrapper {
            margin-top: 60px;
            margin-bottom: 40px;
            color: #fff;
        }

        .notification-card {
            background: #1F2E3A;
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .notification-card.unread {
            border: 1px solid #D8C79A;
        }

        .notification-title {
            font-size: 14px;
            font-weight: 600;
        }

        .notification-message {
            font-size: 13px;
            color: #ccc;
            margin-top: 4px;
        }

        .notification-time {
            font-size: 11px;
            color: #888;
            margin-top: 6px;
        }

        .unread-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 8px;
            height: 8px;
            background: #D8C79A;
            border-radius: 50%;
        }

        .action-btn {
            font-size: 11px;
            border: none;
            border-radius: 12px;
            padding: 4px 10px;
            margin-right: 5px;
        }

        .btn-read {
            background: #D8C79A;
            color: #17232D;
            font-weight: 600;
        }

        .btn-delete {
            background: #dc3545;
            color: #fff;
        }

        .top-actions {
            background: #101820;
            border-radius: 20px;
            padding: 12px;
            margin-bottom: 20px;
            text-align: center;
        }

        .top-actions button {
            font-size: 12px;
            border-radius: 15px;
            padding: 6px 12px;
            margin: 5px;
            border: none;
            font-weight: 600;
        }

        .btn-mark-all {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
        }

        .btn-delete-all {
            background: #dc3545;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            margin-top: 50px;
            color: #aaa;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')

    <div class="container notifications-wrapper">
        <div class="row">
            <div class="col-12 px-3">

                <!-- TOP ACTIONS -->
                @if ($notifications->count() > 0)
                    <div class="top-actions">
                        <form action="{{ route('frontend.notifications.markAllRead') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-mark-all">
                                Mark All as Read
                            </button>
                        </form>

                        <form action="{{ route('frontend.notifications.deleteAll') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete-all">
                                Delete All
                            </button>
                        </form>
                    </div>
                @endif

                <!-- NOTIFICATION LIST -->
                @forelse($notifications as $notification)
                    <div class="notification-card {{ $notification->read_at ? '' : 'unread' }}">

                        @if (!$notification->read_at)
                            <span class="unread-badge"></span>
                        @endif

                        <div class="notification-title">
                            {{ $notification->title }}
                        </div>

                        <div class="notification-message">
                            {{ $notification->message }}
                        </div>

                        <div class="notification-time">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>

                        <div class="mt-2">

                            @if (!$notification->read_at)
                                <form action="{{ route('frontend.notifications.markRead', $notification->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="action-btn btn-read">
                                        Mark Read
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('frontend.notifications.delete', $notification->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </div>

                @empty

                    <div class="empty-state">
                        No notifications yet.
                    </div>
                @endforelse

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
