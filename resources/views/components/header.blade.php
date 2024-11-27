<div class="d-flex align-items-center mt-3 ms-5">
    @if (isset($backButton))
        <div class="backButton bg-primary rounded me-4 p-2" onclick="history.back()" style="cursor: pointer">
            <img src="{{ asset('assets/svg/header-back.svg') }}" alt="">
        </div>
    @endif

    <h2>{{ $title }}</h2>
</div>
