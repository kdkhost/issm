@if(isset($cmsSections) && $cmsSections->isNotEmpty())
    @foreach($cmsSections as $section)
        @php $blocks = $section->blocks; @endphp
        @if($blocks->isNotEmpty())
            @foreach($blocks as $block)
                @php $t = $block->type ?? 'text'; @endphp
                @if(view()->exists("public.cms.blocks.{$t}"))
                    @include("public.cms.blocks.{$t}", ['block' => $block, 'section' => $section, 'blocks' => $blocks])
                @endif
            @endforeach
        @endif
    @endforeach
@elseif(isset($cmsBlocks) && $cmsBlocks->isNotEmpty())
    @foreach($cmsBlocks as $block)
        @php $t = $block->type ?? 'text'; @endphp
        @if(view()->exists("public.cms.blocks.{$t}"))
            @include("public.cms.blocks.{$t}", ['block' => $block, 'section' => null, 'blocks' => $cmsBlocks])
        @endif
    @endforeach
@endif
