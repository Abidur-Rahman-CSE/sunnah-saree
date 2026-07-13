@if ($testimonial->image_url)
    <div class="aspect-[4/3] overflow-hidden bg-[#fff6e8]">
        <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->customer_name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
    </div>
@endif
<div class="p-4">
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="font-serif text-lg font-bold text-[#2f241f]">{{ $testimonial->customer_name }}</p>
            <p class="text-xs font-bold uppercase tracking-wide text-[#c9a24a]">Verified feedback</p>
        </div>
        @if ($testimonial->facebook_post_url)
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-[#fff6e8] text-[#8a155b]">
                <x-storefront.icon name="facebook" class="h-4 w-4" />
            </span>
        @endif
    </div>
    <p class="mt-4 line-clamp-4 text-sm leading-6 text-[#6f5a50]">“{{ $testimonial->message }}”</p>
</div>
