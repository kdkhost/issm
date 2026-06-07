@if(isset($cmsSections) && $cmsSections->isNotEmpty())
    @foreach($cmsSections as $section)
        @if($section->blocks->isNotEmpty())
            @foreach($section->blocks as $block)
                @php $t = $block->type ?? 'text'; @endphp
                @if(view()->exists("public.cms.blocks.{$t}"))
                    @include("public.cms.blocks.{$t}", ['block' => $block, 'section' => $section])
                @endif
            @endforeach
        @endif
    @endforeach
@elseif(isset($cmsBlocks) && $cmsBlocks->isNotEmpty())
    @foreach($cmsBlocks as $block)
        @php $t = $block->type ?? 'text'; @endphp
        @if(view()->exists("public.cms.blocks.{$t}"))
            @include("public.cms.blocks.{$t}", ['block' => $block, 'section' => null])
        @endif
    @endforeach
@endif
