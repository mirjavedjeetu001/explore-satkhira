@extends('admin.layouts.app')

@section('title', 'ঈদ গ্রিটিং কার্ড ম্যানেজমেন্ট')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">ঈদ গ্রিটিং কার্ড ম্যানেজমেন্ট</h1>
            <p class="text-muted mb-0">সকল ঈদ কার্ড দেখুন ও ম্যানেজ করুন</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger" onclick="confirmClearAll()">
                <i class="fas fa-trash-alt me-1"></i> সব মুছুন
            </button>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#settingsModal">
                <i class="fas fa-cog me-1"></i> সেটিংস
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ $stats['unique_users'] }}</h3>
                            <small>মোট ব্যবহারকারী</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-id-card fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ $stats['total_cards'] }}</h3>
                            <small>মোট কার্ড</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ $stats['today_cards'] }}</h3>
                            <small>আজকের কার্ড</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm {{ $settings->is_enabled ? 'bg-success' : 'bg-secondary' }} text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-power-off fa-2x opacity-75"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ $settings->is_enabled ? 'চালু' : 'বন্ধ' }}</h5>
                                <small>ফিচার স্ট্যাটাস</small>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggleStatus" 
                                   {{ $settings->is_enabled ? 'checked' : '' }} 
                                   onchange="toggleFeatureStatus()"
                                   style="width: 3rem; height: 1.5rem; cursor: pointer;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="ফোন নম্বর বা নাম দিয়ে খুঁজুন...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="templateFilter">
                        <option value="">সব টেমপ্লেট</option>
                        <option value="green">গ্রীন টেমপ্লেট</option>
                        <option value="blue">ব্লু টেমপ্লেট</option>
                        <option value="golden">গোল্ডেন টেমপ্লেট</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <span class="text-muted">
                        মোট <strong id="displayCount">{{ $users->count() }}</strong> জন ব্যবহারকারী
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Users List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="usersTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 ps-4">#</th>
                            <th class="border-0">ফোন নম্বর</th>
                            <th class="border-0">মোট কার্ড</th>
                            <th class="border-0">প্রথম কার্ড</th>
                            <th class="border-0">শেষ কার্ড</th>
                            <th class="border-0 text-end pe-4">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr class="user-row" data-phone="{{ $user->phone }}">
                            <td class="ps-4">{{ $index + 1 }}</td>
                            <td>
                                <span class="fw-bold">{{ $user->phone }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $user->card_count }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($user->first_card)->format('d M Y, h:i A') }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($user->last_card)->format('d M Y, h:i A') }}</small>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="toggleCards('{{ $user->phone }}', this)">
                                    <i class="fas fa-eye me-1"></i> বিস্তারিত
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteUser('{{ $user->phone }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="cards-row d-none" id="cards-{{ preg_replace('/[^0-9]/', '', $user->phone) }}">
                            <td colspan="6" class="bg-light p-0">
                                <div class="cards-container p-3">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="ms-2">লোড হচ্ছে...</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block opacity-50"></i>
                                    কোনো কার্ড পাওয়া যায়নি
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ঈদ কার্ড সেটিংস</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="settingsForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">শিরোনাম</label>
                        <input type="text" class="form-control" name="title" value="{{ $settings->title ?? 'ঈদ গ্রিটিং কার্ড মেকার' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">বিবরণ</label>
                        <textarea class="form-control" name="description" rows="3">{{ $settings->description ?? '' }}</textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_enabled" id="settingsActive" {{ $settings->is_enabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="settingsActive">ফিচার চালু রাখুন</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">সেভ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle feature status
function toggleFeatureStatus() {
    const isActive = document.getElementById('toggleStatus').checked;
    
    fetch('{{ route("admin.eid-card.toggle-status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ is_enabled: isActive })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Toggle cards details
function toggleCards(phone, btn) {
    const sanitizedPhone = phone.replace(/[^0-9]/g, '');
    const cardsRow = document.getElementById('cards-' + sanitizedPhone);
    const container = cardsRow.querySelector('.cards-container');
    
    if (cardsRow.classList.contains('d-none')) {
        // Show and load
        cardsRow.classList.remove('d-none');
        btn.innerHTML = '<i class="fas fa-eye-slash me-1"></i> বন্ধ করুন';
        
        // Load cards
        fetch('{{ url("admin/eid-card/cards") }}/' + encodeURIComponent(phone))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderCards(container, data.cards);
                }
            });
    } else {
        // Hide
        cardsRow.classList.add('d-none');
        btn.innerHTML = '<i class="fas fa-eye me-1"></i> বিস্তারিত';
    }
}

// Render cards
function renderCards(container, cards) {
    if (cards.length === 0) {
        container.innerHTML = '<div class="text-center py-3 text-muted">কোনো কার্ড নেই</div>';
        return;
    }
    
    let html = '<div class="row g-3">';
    cards.forEach(card => {
        const templateColors = {
            'green': { bg: '#0d5c3a', text: 'গ্রীন' },
            'blue': { bg: '#1a237e', text: 'ব্লু' },
            'golden': { bg: '#8B6914', text: 'গোল্ডেন' }
        };
        const template = templateColors[card.template] || templateColors.green;
        
        html += `
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: ${template.bg}">
                        <span><i class="fas fa-id-card me-1"></i> ${template.text} টেমপ্লেট</span>
                        <button class="btn btn-sm btn-light" onclick="confirmDeleteCard(${card.id})">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        ${card.photo ? `<div class="text-center mb-3"><img src="/storage/${card.photo}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" alt="Photo"></div>` : ''}
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" width="100">নাম:</td>
                                <td class="fw-bold">${card.name}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">পদবী:</td>
                                <td>${card.designation || '-'}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">বার্তা:</td>
                                <td><small>${card.custom_message || '-'}</small></td>
                            </tr>
                            <tr>
                                <td class="text-muted">তারিখ:</td>
                                <td><small>${new Date(card.created_at).toLocaleString('bn-BD')}</small></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

// Delete single card
function confirmDeleteCard(cardId) {
    if (confirm('এই কার্ডটি মুছে ফেলতে চান?')) {
        fetch('{{ url("admin/eid-card") }}/' + cardId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Delete all cards of a user
function confirmDeleteUser(phone) {
    if (confirm('এই ব্যবহারকারীর সকল কার্ড মুছে ফেলতে চান?')) {
        fetch('{{ url("admin/eid-card/user") }}/' + encodeURIComponent(phone), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Clear all cards
function confirmClearAll() {
    if (confirm('সকল ঈদ কার্ড মুছে ফেলতে চান? এই অ্যাকশন আনডু করা যাবে না!')) {
        fetch('{{ route("admin.eid-card.clear-all") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

// Settings form
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        title: formData.get('title'),
        description: formData.get('description'),
        is_enabled: formData.get('is_enabled') ? true : false
    };
    
    fetch('{{ route("admin.eid-card.update-settings") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const phone = row.dataset.phone.toLowerCase();
        if (phone.includes(search)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
            // Also hide cards row
            const sanitizedPhone = row.dataset.phone.replace(/[^0-9]/g, '');
            const cardsRow = document.getElementById('cards-' + sanitizedPhone);
            if (cardsRow) cardsRow.classList.add('d-none');
        }
    });
    
    document.getElementById('displayCount').textContent = visibleCount;
});
</script>
@endpush
@endsection
