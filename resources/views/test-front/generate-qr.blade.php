<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <p><span>Page : 1</span></p>
    <p><span>Jumlah Generate: 0</span></p>

    <button type="button" onclick="generateQr()">click me</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        let page = 1;

        const generateQr = () => {
            $.ajax({
                url: '{{ route('generate-qr-asset.post') }}',
                type: 'POST',
                data: {
                    page: page,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        page++;
                        $('p span').eq(0).text('Page : ' + page);
                        $('p span').eq(1).text('Jumlah Generate: ' + (100 * (page - 1)));
                    }

                    if (!response.last_page) {
                        generateQr();
                    }
                }
            })
        }
    </script>
</body>
</html>