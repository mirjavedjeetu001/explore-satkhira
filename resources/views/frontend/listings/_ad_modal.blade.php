<!-- Ad Modal for ID: {{ $ad->id }} -->
<div class="modal fade" id="adModal{{ $ad->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-{{ $ad->type == 'offer' ? 'danger' : ($ad->type == 'promotion' ? 'warning' : 'info') }} text-{{ $ad->type == 'promotion' ? 'dark' : 'white' }}">
                <h5 class="modal-title">
                    <i class="fas fa-{{ $ad->type == 'offer' ? 'percent' : ($ad->type == 'promotion' ? 'bullhorn' : 'image') }} me-2"></i>
                    {{ $ad->title ?? \App\Models\ListingImage::getTypes()[$ad->type] ?? 'প্রচারমূলক ছবি' }}
                </h5>
                <button type="button" class="btn-close {{ $ad->type != 'promotion' ? 'btn-close-white' : '' }}" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}" class="img-fluid w-100">
            </div>
            @if($ad->description || $ad->valid_from || $ad->valid_until)
            <div class="modal-footer d-block">
                @if($ad->description)
                    <p class="text-muted mb-2">{{ $ad->description }}</p>
                @endif
                @if($ad->valid_from || $ad->valid_until)
                    <p class="small text-muted mb-0">
                        <i class="fas fa-calendar-alt me-1"></i>
                        মেয়াদকাল: 
                        {{ $ad->valid_from?->format('d M Y') ?? 'শুরু থেকে' }} 
                        - 
                        {{ $ad->valid_until?->format('d M Y') ?? 'চলমান' }}
                    </p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
