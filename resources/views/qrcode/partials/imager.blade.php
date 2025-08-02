@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<div class="visible-print text-center">
    {!! QrCode::size(100)->generate('Eko Handriyanto') !!}
</div>
