@extends('layouts.user.master-detail')
@section('page-title', 'Detail Asset')
@section('custom-js')
    <script>
        $('body').on('_EventAjaxSuccess', function(event, formElement, data) {
            if (data.success) {
                //
            }
        });
        $('body').on('_EventAjaxErrors', function(event, formElement, errors) {
            //
        });
    </script>
    {{-- <script src="{{ asset('custom-js/html5-qrcode.min.js') }}"></script> --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script type="text/javascript">
        function onScanSuccess(qrCodeMessage) {
            $.ajax({
                url: "{{ route('user.scan-qr.find') }}",
                method: 'POST',
                dataType: 'json',
                async: false,
                cache: false,
                data: {
                    kode_asset: qrCodeMessage,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    var success = $('#resultSuccess');
                    var error = $('#resultError');
                    if (result.success) {
                        success.empty();
                        error.addClass('d-none');

                        success.append('<span class="result">' + result.message + '</span>');
                        success.removeClass('d-none');

                        setTimeout(function() {
                            var redirect = "{{ route('user.asset-data.detail', '') }}" + "/" + result
                                .data.id;
                            location.assign(redirect);
                        }, 2000);
                    } else {
                        success.addClass('d-none');
                        error.removeClass('d-none');
                        document.getElementById('resultError').innerHTML = '<span class="result">' +
                            result.message + '</span>';

                    }
                },
                error: function(result) {
                    alert(result.responseText);
                }
            });
        }

        function onScanError(errorMessage) {
            document.getElementById('resultError').innerHTML = '<span class="result">' + errorMessage + '</span>';
        }
        // var html5QrcodeScanner = new Html5QrcodeScanner(
        //     "reader", {
        //         fps: 30,
        //         qrbox: 250,
        //     });
        // html5QrcodeScanner.render(onScanSuccess, onScanError);

        const html5QrCode = new Html5Qrcode("reader");
        const config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        };
        // If you want to prefer back camera
        html5QrCode.start({
            facingMode: "environment"
        }, config, onScanSuccess, onScanError);

        // // Membuat opsi kamera dalam elemen select dengan id 'camera-select'
        // function createCameraOptions(devices) {
        //     var cameraSelect = document.getElementById('camera-select');
        //     cameraSelect.innerHTML = ''; // Menghapus opsi yang ada sebelumnya

        //     var videoInputs = devices.filter(function(device) {
        //         return device.kind === 'videoinput';
        //     });

        //     videoInputs.forEach(function(device) {
        //         var option = document.createElement('option');
        //         option.value = device.deviceId;
        //         option.text = device.label || 'Kamera ' + (videoInputs.indexOf(device) + 1);
        //         cameraSelect.appendChild(option);
        //     });
        // }
        // var html5QrCode;

        // // Periksa apakah perangkat mendukung getUserMedia
        // if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        //     // Konfigurasi konstrain video
        //     var constraints = {
        //         video: true
        //     };

        //     // Meminta izin pengguna untuk menggunakan kamera
        //     navigator.mediaDevices.getUserMedia(constraints)
        //         .then(function(stream) {
        //             startCameraScaning();
        //         })
        //         .catch(function(error) {
        //             console.error('Gagal meminta izin kamera:', error);
        //         });
        // } else {
        //     console.error('getUserMedia tidak didukung di peramban ini.');
        // }

        // function startCameraScaning() {
        //     navigator.mediaDevices.enumerateDevices()
        //         .then(function(devices) {
        //             var cameraSelect = document.getElementById('camera-select');

        //             // Function to create camera options
        //             function createCameraOptions(devices) {
        //                 cameraSelect.innerHTML = ''; // Clear previous options

        //                 var videoInputs = devices.filter(function(device) {
        //                     return device.kind === 'videoinput';
        //                 });

        //                 videoInputs.forEach(function(device) {
        //                     var option = document.createElement('option');
        //                     option.value = device.deviceId;
        //                     option.text = device.label || 'Camera ' + (videoInputs.indexOf(device) + 1);
        //                     cameraSelect.appendChild(option);
        //                 });
        //             }

        //             // Update camera options initially
        //             createCameraOptions(devices);

        //             html5QrCode = new Html5Qrcode("reader");

        //             // Function to start QR code scanning
        //             function startQrCodeScanning(cameraId) {
        //                 html5QrCode.start(
        //                     cameraId, {
        //                         fps: 30,
        //                         qrbox: {
        //                             width: 250,
        //                         }
        //                     },
        //                     function(decodedText, decodedResult) {
        //                         onScanSuccess(decodedText);
        //                     },
        //                     function(errorMessage) {
        //                         onScanError(errorMessage);
        //                     }
        //                 ).catch(function(err) {
        //                     console.error('Failed to start QR code scanning:', err);
        //                 });
        //             }

        //             // Event listener for camera selection change
        //             cameraSelect.addEventListener('change', function(event) {
        //                 var selectedCameraId = event.target.value;
        //                 html5QrCode.stop().then(function() {
        //                     startQrCodeScanning(selectedCameraId);
        //                 });
        //             });

        //             // Find the back camera ID
        //             var backCameraId = null;
        //             var videoInputs = devices.filter(function(device) {
        //                 return device.kind === 'videoinput';
        //             });

        //             for (var i = 0; i < videoInputs.length; i++) {
        //                 var device = videoInputs[i];
        //                 if (!device.label.toLowerCase().includes('front')) {
        //                     backCameraId = device.deviceId;
        //                     break;
        //                 }
        //             }

        //             // Set the back camera as the default selection
        //             if (backCameraId) {
        //                 cameraSelect.value = backCameraId;
        //             }

        //             // Start QR code scanning with the default camera
        //             var defaultCameraId = cameraSelect.value;
        //             startQrCodeScanning(defaultCameraId);
        //         })
        //         .catch(function(error) {
        //             console.error('Failed to get media device list:', error);
        //         });
        // }
        // // Fungsi untuk memulai pemindaian dengan file
        // function startFileScanning(file) {
        //     html5QrCode.stop().then(function() {
        //         html5QrCode.scanFile(file, true)
        //             .then(function(decodedText, decodedResult) {
        //                 onScanSuccess(decodedText);
        //             })
        //             .catch(function(error) {
        //                 console.error('Failed to scan file:', error);
        //                 onScanError('Failed to scan file: ' + error.message);
        //             });
        //     });
        // }
        // // Event listener untuk memilih file
        // var fileInput = document.getElementById('file-input');
        // fileInput.addEventListener('change', function(event) {
        //     var file = event.target.files[0];
        //     startFileScanning(file);
        // });
    </script>
@endsection
@section('back-button')
    <a href="{{ route('user.dashboard.index') }}" class="headerButton">
        <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
    </a>
@endsection
@section('content')
    <div class="section mt-2">
        <h2 style="color: #6F6F6F;"></h2>
        <div class="mt-2">
            <div class="row justify-content-between">
                <div class="col-md-5 col-12">
                    {{-- Scan Div --}}
                    <div id="reader"></div>
                    {{-- <div class="row gutters">
                        <div class="col-6">
                            <label for="file-input">Ganti Opsi Kamera</label>
                            <select id="camera-select" class="form-control"></select>

                        </div>
                        <div class="col-6">
                            <label for="file-input">Unggah QR Asset</label>
                            <input type="file" id="file-input" class="form-control" accept=".png,.jpg,.jpeg">

                        </div>
                    </div> --}}
                </div>
                <div class="col-md-7 col-12">
                    {{-- Info Div --}}
                    <div class="row gutters">
                        <div class="col-12">
                            <div class="form-section-header light-bg">Hasil Scan
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-danger" id="resultError">Data Tidak Ditemukan, Arahkan Kamera
                                Pada Kode
                                QR Yang Dimiliki Asset</div>

                            <div class="alert alert-success d-none" id="resultSuccess"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('button-menu')
    @include('layouts.user.bottom-menu')
@endsection
