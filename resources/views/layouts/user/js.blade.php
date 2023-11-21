<!-- Bootstrap -->
<script src="{{ asset('assets/vendors/general/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/user/js/lib/bootstrap.bundle.min.js') }}"></script>
<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<!-- Splide -->
<script src="{{ asset('assets/user/js/plugins/splide/splide.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            "Accept": "application/json",
        }
    });
</script>
@yield('pluggin-js')
<!-- Base Js File -->
<script src="{{ asset('assets/user/js/base.js') }}"></script>
<script src="{{ asset('custom-js/general.js') }}"></script>

<script>
    // Add to Home with 2 seconds delay.
    AddtoHome("2000", "once");

    const changeTextToast = (id, text) => {
        $('#'+id).find('.toastText').text(text);
    }

    const dialogDanger = (title, body) => {
        $('#alertDangerTitle').text(title);
        $('#alertDangerBody').text(body);
        $('#DialogIconedDanger').modal('show');
    }

    const countNotif = () => {
        $.ajax({
            url: "{{ route('user.notification.count') }}",
            data: {
                user_id: "{{ Auth::user()->id }}",
            },
            type: "GET",
            success: function (response) {
                $('#countNotif').text(response.data);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    $(document).ready(function () {
        countNotif();
    });
</script>

@yield('custom-js')