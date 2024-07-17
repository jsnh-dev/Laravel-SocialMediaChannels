@if(isset($elements) && $elements)
    <div class="d-flex flex-column">
        @foreach($elements as $element)
            <div class="d-flex border-bottom">
                <a href="{{ $element->url }}" class="flex-1 text-hover text-decoration-none p-3" target="_blank">
                    <h5 class="d-flex align-items-center my-2">
                        <i class="fa-brands {{ $element->icon }} w-3-rem"></i>
                        <span class="text-dark">{{ $element->text }}</span>
                    </h5>
                </a>
            </div>
        @endforeach
        <div class="d-flex">
            <div class="flex-1 p-3 mw-100">
                <h5 class="d-flex align-items-center my-2">
                    <span id="shareLink" class="text-dark font-size-08rem border border-secondary p-2 text-nowrap overflow-hidden text-overflow-ellipsis flex-1 me-4">
                        {{ $url }}
                    </span>
                    <div class="position-relative">
                        <button id="copyShareLink"
                                type="button"
                                class="btn p-0 text-hover w-3-rem text-end border-0 pe-2 me-1"
                                data-clipboard-target="#shareLink">
                            <i class="fa fa-copy text-dark"></i>
                        </button>
                        <span class="feedback success border border-secondary d-none">{{ 'Copied' }}</span>
                        <span class="feedback error border border-secondary d-none">{{ 'Copy failed' }}</span>
                    </div>
                </h5>
            </div>
        </div>
    </div>
@endif