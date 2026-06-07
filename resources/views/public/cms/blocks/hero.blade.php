<section style="background:linear-gradient(135deg,#166534 0%,#15803d 50%,#059669 100%);padding:56px 0 40px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 16px;">
        @if($block->link_url)
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#86efac;margin-bottom:16px;">
            <a href="{{ route('home') }}" style="color:#86efac;text-decoration:none;transition:color .2s;">Início</a>
            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span style="color:#fff;">{{ $block->title }}</span>
        </div>
        @endif
        <h1 style="font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;line-height:1.1;margin-bottom:8px;">
            {!! $block->title !!}
        </h1>
        @if($block->subtitle)
        <p style="font-size:16px;color:#bbf7d0;max-width:600px;margin-bottom:20px;">
            {{ $block->subtitle }}
        </p>
        @endif
        @if($block->link_url && !$block->link_text)
        <div style="display:flex;flex-wrap:wrap;gap:10px;">
            <a href="{{ $block->link_url }}" style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);padding:6px 14px;border-radius:24px;font-size:13px;color:#fff;font-weight:500;text-decoration:none;">
                {{ $block->link_text ?? 'Saiba Mais' }}
            </a>
        </div>
        @endif
    </div>
</section>
