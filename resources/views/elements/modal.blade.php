<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true" {{ $attribute ?? '' }}>
    <div class="modal-dialog {{ $class ?? '' }}">
        <div class="modal-content">
            <div class="modal-header {{ $headerClass ?? 'border-bottom' }}">
                @if(isset($title) && $title)
                    <h5 class="modal-title me-auto">{!! $title !!}</h5>
                @endif
                @if(isset($titleElement) && $titleElement)
                    {!! $titleElement !!}
                @endif
                @if(!isset($hideDismiss) || !$hideDismiss)
                    <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn border-0 text-dark text-hover">
                        <i class="fa fa-x"></i>
                    </button>
                @endif
            </div>
            <div class="modal-body {{ $bodyClass ?? '' }}">
                <div class="modal-body-inner {{ $bodyInnerClass ?? '' }}">
                    {!! $body ?? '' !!}
                </div>
            </div>
            @if(isset($footer) && $footer)
                <div class="modal-footer border-top {{ $footerClass ?? '' }}">
                    {!! $footer !!}
                </div>
            @endif
            @if(isset($endContent) && $endContent)
                {!! $endContent !!}
            @endif
        </div>
    </div>
</div>