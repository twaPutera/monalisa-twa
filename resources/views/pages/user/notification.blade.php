@extends('layouts.user.master')
@section('page-title', 'Notifikasi')
@section('custom-js')
    <script>
        $(document).ready(function() {
            getDataNotification();
        });

        const getDataNotification = () => {
            $.ajax({
                url: "{{ route('user.notification.get-data') }}",
                data: {
                    user_id: "{{ Auth::user()->id }}"
                },
                type: "GET",
                dataType: "JSON",
                success: function (response) {
                    if (response.success) {
                        console.log(response.data);
                        $('.notificationContainer').empty();
                        if (response.data.length > 0) {
                            $(response.data).each(function (index, value) {
                                $('.notificationContainer').append(generateTemplateNotification(value.data, value.id));
                            })
                        }
                    }
                }
            });
        }

        const generateTemplateNotification = (data, id) => {
            return `
                <li>
                    <a href="${data.url}" class="item" onclick="onNotificationClick('${id}')">
                        <div class="icon-box bg-warning">
                            <ion-icon name="chatbubble-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong>${data.title}</strong></div>
                                <div class="text-small mb-05">${data.message}</div>
                                <div class="text-xsmall">${data.date}</div>
                            </div>
                        </div>
                    </a>
                </li>
            `;
        }

        const onNotificationClick = (id) => {
            $.ajax({
                url: "{{ route('user.notification.read') }}",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                type: "POST",
                dataType: "JSON",
                success: function (response) {
                    if (response.success) {
                        getDataNotification();
                    }
                }
            });
        }
    </script>
@endsection
@section('content')
<div class="section full">

    <ul class="listview image-listview flush notificationContainer">
        <li>
            <a href="app-notification-detail.html" class="item">
                <div class="icon-box bg-warning">
                    <ion-icon name="chatbubble-outline"></ion-icon>
                </div>
                <div class="in">
                    <div>
                        <div class="mb-05"><strong>New Messages</strong></div>
                        <div class="text-small mb-05">You have new messages from John</div>
                        <div class="text-xsmall">5/3/2020 10:30 AM</div>
                    </div>
                </div>
            </a>
        </li>
    </ul>

</div>
@endsection
