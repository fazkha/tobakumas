<div class="relative" style="width: 500px;">
    <div id="qr-reader"></div>
    <div id="qr-reader-results" style="text-align: center; color: #ffffff; background-color: #00aa00;">...</div>
    <div class="absolute top-2 left-2">
        <x-secondary-button id="close-scanner">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12 5.293 6.707a1 1 0 0 1 0-1.414z"
                    fill="currentColor" />
            </svg>
        </x-secondary-button>
    </div>
</div>

{{-- <div id="qr-reader"></div>
<button id="startButton">Start Scan</button>
<button id="stopButton" style="display:none;">Stop Scan</button> --}}

@push('scripts')
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    {{-- <script src="https://unpkg.com/html5-qrcode"></script> --}}
    <script type="text/javascript">
        var resultContainer = document.getElementById('qr-reader-results');
        // var resultContainer = document.getElementById('{{ $element['el'] }}');
        var lastResult, countResults = 0;

        // resultContainer.value = '3';

        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;
                // Handle on success condition with the decoded message.
                // console.log(`Scan result ${decodedText}`, decodedResult);

                resultContainer.innerText = decodedText;

                // resultContainer.value = decodedText;
                // resultContainer.focus();

                // html5QrcodeScanner.clear();
            } else {
                resultContainer.innerText = '...';
                countResults = 0;
                lastResult = 0;
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 10,
                qrbox: 250
            }
        );

        html5QrcodeScanner.render(onScanSuccess);
    </script>
    {{-- <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            let lastResult;
            let html5QrCode = new Html5Qrcode("qr-reader");

            function onScanSuccess(decodedText, decodedResult) {
                if (decodedText !== lastResult) {
                    lastResult = decodedText;
                    console.log(`Scan result ${decodedText}`, decodedResult);
                    // window.open('https://optwo.id/?scan=' + decodedText, '_blank');
                }
            }

            function startScan() {
                Html5Qrcode.getCameras().then(cameras => {
                    if (cameras && cameras.length) {
                        // Assumendo che l'ultima fotocamera nell'elenco sia quella posteriore
                        let cameraId = cameras[cameras.length - 1].id;
                        html5QrCode.start(
                                cameraId, {
                                    fps: 10,
                                    qrbox: {
                                        width: 300,
                                        height: 300
                                    }
                                },
                                onScanSuccess,
                                errorMessage => {
                                    console.log(`QR Code no longer in front of camera.`);
                                })
                            .catch(err => {
                                console.log(`Unable to start scanning, error: ${err}`);
                            });
                    }
                }).catch(err => {
                    console.error(err);
                });
            }

            function stopScan() {
                html5QrCode.stop().then(ignore => {
                    console.log("Scanning stopped.");
                }).catch(err => {
                    console.error("Unable to stop scanning.", err);
                });
            }

            document.getElementById('startButton').addEventListener('click', function() {
                startScan();
                this.style.display = 'none';
                document.getElementById('stopButton').style.display = 'block';
            });

            document.getElementById('stopButton').addEventListener('click', function() {
                stopScan();
                this.style.display = 'none';
                document.getElementById('startButton').style.display = 'block';
            });
        });
    </script> --}}
@endpush
