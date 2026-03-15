@extends('frontend.layouts.app')

@section('title', $settings->title ?? 'ঈদ সালামি ক্যালকুলেটর')
@section('meta_description', 'আপনার ঈদের সালামি হিসাব রাখুন সহজেই। কে কত টাকা সালামি দিয়েছে তার হিসাব রাখুন এই ক্যালকুলেটর দিয়ে।')

@section('content')
<div class="salami-calculator-page">
    <!-- Hero Section -->
    <div class="salami-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="eid-icon mb-3">
                        <span class="crescent">🌙</span>
                        <span class="star">⭐</span>
                    </div>
                    <h1 class="display-4 fw-bold text-white mb-3">
                        {{ $settings->title ?? 'ঈদ সালামি ক্যালকুলেটর' }}
                    </h1>
                    <p class="lead text-white-50">
                        {{ $settings->description ?? 'আপনার ঈদের সালামি হিসাব রাখুন সহজেই!' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="hero-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- User Info Card (Show if no session) -->
                <div id="userInfoCard" class="salami-card mb-4 {{ $hasSession ? 'd-none' : '' }}">
                    <div class="card-header-custom">
                        <i class="fas fa-user-circle"></i>
                        <span>আপনার তথ্য দিন</span>
                    </div>
                    <div class="card-body-custom">
                        <div class="text-center mb-4">
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                একবার মোবাইল নম্বর দিলে পরবর্তীতে আর দিতে হবে না। আপনার ডাটা সেভ থাকবে!
                            </p>
                        </div>
                        <form id="userInfoForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" name="user_name" id="userName" 
                                           placeholder="যেমন: রহিম উদ্দিন" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control form-control-lg" name="phone" id="userPhone" 
                                           placeholder="যেমন: 01712345678" required pattern="[0-9]{11}">
                                    <small class="text-muted">১১ ডিজিটের মোবাইল নম্বর দিন</small>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button type="submit" class="btn btn-eid btn-lg px-5">
                                    <i class="fas fa-rocket me-2"></i>শুরু করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Calculator Section (Show if session exists) -->
                <div id="calculatorSection" class="{{ !$hasSession ? 'd-none' : '' }}">
                    
                    <!-- Total Summary Card -->
                    <div class="total-card mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="user-greeting">
                                    <span class="greeting-wave">👋</span>
                                    <div>
                                        <small class="text-muted">স্বাগতম</small>
                                        <h4 class="mb-0 user-name-display">{{ $savedName ?? 'অতিথি' }}</h4>
                                        <small class="text-muted phone-display">{{ $savedPhone }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <div class="total-amount-box">
                                    <small class="text-muted d-block">মোট সালামি</small>
                                    <h2 class="total-amount mb-0">৳<span id="totalAmount">{{ number_format($total, 0) }}</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Add Entry Form -->
                        <div class="col-lg-5">
                            <div class="salami-card sticky-lg-top" style="top: 100px;">
                                <div class="card-header-custom bg-success">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>নতুন সালামি যোগ করুন</span>
                                </div>
                                <div class="card-body-custom">
                                    <form id="addEntryForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">কার কাছ থেকে পেয়েছেন? <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="giver_name" id="giverName" 
                                                   placeholder="যেমন: মামা, ফুফু, দাদা" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">সম্পর্ক (ঐচ্ছিক)</label>
                                            <select class="form-select" name="giver_relation" id="giverRelation">
                                                <option value="">নির্বাচন করুন</option>
                                                <option value="বাবা">বাবা</option>
                                                <option value="মা">মা</option>
                                                <option value="দাদা">দাদা</option>
                                                <option value="দাদি">দাদি</option>
                                                <option value="নানা">নানা</option>
                                                <option value="নানি">নানি</option>
                                                <option value="চাচা">চাচা</option>
                                                <option value="চাচি">চাচি</option>
                                                <option value="মামা">মামা</option>
                                                <option value="মামি">মামি</option>
                                                <option value="ফুফু">ফুফু</option>
                                                <option value="ফুফা">ফুফা</option>
                                                <option value="খালা">খালা</option>
                                                <option value="খালু">খালু</option>
                                                <option value="ভাই">ভাই</option>
                                                <option value="বোন">বোন</option>
                                                <option value="বন্ধু">বন্ধু</option>
                                                <option value="প্রতিবেশী">প্রতিবেশী</option>
                                                <option value="অন্যান্য">অন্যান্য</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">টাকার পরিমাণ <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-success text-white">৳</span>
                                                <input type="number" class="form-control" name="amount" id="amount" 
                                                       placeholder="500" min="1" required>
                                            </div>
                                            <!-- Quick Amount Buttons -->
                                            <div class="quick-amounts mt-2">
                                                <button type="button" class="quick-amt" data-amount="100">৳১০০</button>
                                                <button type="button" class="quick-amt" data-amount="200">৳২০০</button>
                                                <button type="button" class="quick-amt" data-amount="500">৳৫০০</button>
                                                <button type="button" class="quick-amt" data-amount="1000">৳১০০০</button>
                                                <button type="button" class="quick-amt" data-amount="2000">৳২০০০</button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">নোট (ঐচ্ছিক)</label>
                                            <input type="text" class="form-control" name="note" id="note" 
                                                   placeholder="যেমন: নগদ / বিকাশ">
                                        </div>
                                        <button type="submit" class="btn btn-success btn-lg w-100" id="addEntryBtn">
                                            <i class="fas fa-plus me-2"></i>যোগ করুন
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Entries List -->
                        <div class="col-lg-7">
                            <div class="salami-card">
                                <div class="card-header-custom d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-list"></i>
                                        <span>সালামির তালিকা</span>
                                        <span class="badge bg-white text-success ms-2" id="entriesCount">{{ $entries->count() }}</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-warning" id="downloadCardBtn" title="কার্ড ডাউনলোড করুন" {{ $entries->count() == 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-download me-1"></i> ডাউনলোড
                                        </button>
                                        <button class="btn btn-sm btn-outline-light" id="logoutBtn" title="অন্য অ্যাকাউন্টে যান">
                                            <i class="fas fa-sign-out-alt me-1"></i> লগআউট
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body-custom p-0">
                                    <div id="entriesList">
                                        @if($entries->count() > 0)
                                            @foreach($entries as $entry)
                                                <div class="entry-item" data-id="{{ $entry->id }}">
                                                    <div class="entry-info">
                                                        <div class="entry-avatar">
                                                            {{ mb_substr($entry->giver_name, 0, 1) }}
                                                        </div>
                                                        <div class="entry-details">
                                                            <h6 class="mb-0">{{ $entry->giver_name }}</h6>
                                                            <small class="text-muted">
                                                                {{ $entry->giver_relation ?? 'অন্যান্য' }}
                                                                @if($entry->note) • {{ $entry->note }} @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="entry-amount">
                                                        <span class="amount">৳{{ number_format($entry->amount, 0) }}</span>
                                                        <button class="btn btn-sm btn-link text-danger delete-entry" data-id="{{ $entry->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="empty-state" id="emptyState">
                                                <div class="empty-icon">💰</div>
                                                <h5>এখনও কোন সালামি যোগ করেননি</h5>
                                                <p class="text-muted">বাম দিকের ফর্ম থেকে সালামি যোগ করুন</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics Card -->
                            <div class="stats-card mt-4" id="statsCard" style="{{ $entries->count() > 0 ? '' : 'display:none;' }}">
                                <div class="row text-center g-3">
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-icon">👥</div>
                                            <div class="stat-value" id="totalGivers">{{ $entries->count() }}</div>
                                            <div class="stat-label">জন</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-icon">💵</div>
                                            <div class="stat-value" id="avgAmount">{{ $entries->count() > 0 ? number_format($total / $entries->count(), 0) : 0 }}</div>
                                            <div class="stat-label">গড় টাকা</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-item">
                                            <div class="stat-icon">🏆</div>
                                            <div class="stat-value" id="maxAmount">{{ $entries->count() > 0 ? number_format($entries->max('amount'), 0) : 0 }}</div>
                                            <div class="stat-label">সর্বোচ্চ</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Downloadable Card -->
<div id="downloadableCard" style="position: absolute; left: -9999px; top: 0;">
    <div class="salami-download-card" style="width: 600px; font-family: 'Hind Siliguri', Arial, sans-serif;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1a5f2a 0%, #28a745 100%); padding: 30px; text-align: center; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 10px; right: 20px; font-size: 60px; opacity: 0.2;">🌙</div>
            <div style="font-size: 40px; margin-bottom: 10px;">🌙 ⭐</div>
            <h2 style="color: white; margin: 0; font-size: 28px; font-weight: bold;">ঈদ সালামি কার্ড</h2>
            <p style="color: rgba(255,255,255,0.8); margin: 10px 0 0; font-size: 16px;">{{ now()->format('d M, Y') }}</p>
        </div>
        
        <!-- User Info -->
        <div style="background: #f8f9fa; padding: 20px 30px; text-align: center; border-bottom: 2px dashed #ddd;">
            <h3 style="margin: 0; color: #333; font-size: 24px;" id="cardUserName">{{ $savedName ?? 'অতিথি' }}</h3>
            <p style="margin: 5px 0 0; color: #666; font-size: 14px;" id="cardUserPhone">{{ $savedPhone }}</p>
        </div>
        
        <!-- Total -->
        <div style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); padding: 25px; text-align: center;">
            <p style="margin: 0; color: rgba(0,0,0,0.6); font-size: 14px;">মোট সালামি</p>
            <h1 style="margin: 5px 0 0; color: #333; font-size: 48px; font-weight: bold;" id="cardTotalAmount">৳{{ number_format($total, 0) }}</h1>
        </div>
        
        <!-- Entries List -->
        <div style="padding: 20px 30px; background: white;" id="cardEntriesList">
            <h4 style="margin: 0 0 15px; color: #333; font-size: 16px; border-bottom: 2px solid #28a745; padding-bottom: 10px;">📋 সালামির তালিকা</h4>
            @foreach($entries as $entry)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #eee;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #28a745, #1a5f2a); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px;">{{ mb_substr($entry->giver_name, 0, 1) }}</div>
                    <div>
                        <div style="font-weight: 600; color: #333; font-size: 15px;">{{ $entry->giver_name }}</div>
                        <div style="color: #888; font-size: 12px;">{{ $entry->giver_relation ?? 'অন্যান্য' }}</div>
                    </div>
                </div>
                <div style="font-weight: bold; color: #28a745; font-size: 18px;">৳{{ number_format($entry->amount, 0) }}</div>
            </div>
            @endforeach
        </div>
        
        <!-- Stats -->
        <div style="display: flex; background: #f8f9fa; text-align: center;">
            <div style="flex: 1; padding: 20px; border-right: 1px solid #ddd;">
                <div style="font-size: 24px;">👥</div>
                <div style="font-size: 22px; font-weight: bold; color: #28a745;" id="cardGiversCount">{{ $entries->count() }}</div>
                <div style="font-size: 12px; color: #666;">জন থেকে</div>
            </div>
            <div style="flex: 1; padding: 20px; border-right: 1px solid #ddd;">
                <div style="font-size: 24px;">💵</div>
                <div style="font-size: 22px; font-weight: bold; color: #28a745;" id="cardAvgAmount">৳{{ $entries->count() > 0 ? number_format($total / $entries->count(), 0) : 0 }}</div>
                <div style="font-size: 12px; color: #666;">গড়</div>
            </div>
            <div style="flex: 1; padding: 20px;">
                <div style="font-size: 24px;">🏆</div>
                <div style="font-size: 22px; font-weight: bold; color: #28a745;" id="cardMaxAmount">৳{{ $entries->count() > 0 ? number_format($entries->max('amount'), 0) : 0 }}</div>
                <div style="font-size: 12px; color: #666;">সর্বোচ্চ</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="background: linear-gradient(135deg, #1a5f2a 0%, #28a745 100%); padding: 15px; text-align: center;">
            <p style="margin: 0; color: white; font-size: 14px;">🌐 exploresatkhira.com/salami</p>
            <p style="margin: 5px 0 0; color: rgba(255,255,255,0.7); font-size: 11px;">ঈদ মোবারক! 🌙</p>
        </div>
    </div>
</div>

<style>
/* Salami Calculator Styles */
.salami-calculator-page {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.salami-hero {
    background: linear-gradient(135deg, #1a5f2a 0%, #28a745 50%, #1a5f2a 100%);
    padding: 80px 0 120px;
    position: relative;
    overflow: hidden;
}

.eid-icon {
    font-size: 3rem;
    animation: float 3s ease-in-out infinite;
}

.eid-icon .crescent {
    display: inline-block;
    animation: pulse 2s ease-in-out infinite;
}

.eid-icon .star {
    display: inline-block;
    margin-left: 10px;
    animation: twinkle 1.5s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

@keyframes twinkle {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.8); }
}

.hero-shapes .shape {
    position: absolute;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.shape-1 {
    width: 100px;
    height: 100px;
    top: 20%;
    left: 10%;
    animation: float 4s ease-in-out infinite;
}

.shape-2 {
    width: 150px;
    height: 150px;
    bottom: 10%;
    right: 15%;
    animation: float 5s ease-in-out infinite 1s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    top: 40%;
    right: 25%;
    animation: float 3.5s ease-in-out infinite 0.5s;
}

/* Cards */
.salami-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.salami-card:hover {
    transform: translateY(-5px);
}

.card-header-custom {
    background: linear-gradient(135deg, #28a745 0%, #1a5f2a 100%);
    color: white;
    padding: 20px 25px;
    font-size: 1.1rem;
    font-weight: 600;
}

.card-header-custom i {
    margin-right: 10px;
}

.card-body-custom {
    padding: 25px;
}

/* Total Card */
.total-card {
    background: white;
    border-radius: 20px;
    padding: 25px 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.user-greeting {
    display: flex;
    align-items: center;
    gap: 15px;
}

.greeting-wave {
    font-size: 2.5rem;
    animation: wave 2s ease-in-out infinite;
}

@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(20deg); }
    75% { transform: rotate(-20deg); }
}

.total-amount-box {
    background: linear-gradient(135deg, #28a745 0%, #1a5f2a 100%);
    color: white;
    padding: 15px 30px;
    border-radius: 15px;
    display: inline-block;
}

.total-amount {
    font-size: 2.5rem;
    font-weight: 700;
}

/* Entry Items */
.entry-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 25px;
    border-bottom: 1px solid #eee;
    transition: background 0.2s;
}

.entry-item:hover {
    background: #f8f9fa;
}

.entry-item:last-child {
    border-bottom: none;
}

.entry-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.entry-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #1a5f2a 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
}

.entry-amount {
    display: flex;
    align-items: center;
    gap: 10px;
}

.entry-amount .amount {
    font-size: 1.25rem;
    font-weight: 600;
    color: #28a745;
}

/* Quick Amounts */
.quick-amounts {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.quick-amt {
    background: #e9ecef;
    border: none;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
}

.quick-amt:hover {
    background: #28a745;
    color: white;
}

/* Eid Button */
.btn-eid {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: #333;
    border: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-eid:hover {
    background: linear-gradient(135deg, #ff9800 0%, #ff5722 100%);
    color: white;
    transform: scale(1.05);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
}

/* Stats Card */
.stats-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.stat-item {
    padding: 15px;
}

.stat-icon {
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #28a745;
}

.stat-label {
    font-size: 0.85rem;
    color: #666;
}

/* Form Controls */
.form-control, .form-select {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
}

.form-control:focus, .form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
}

/* Animations */
.entry-item {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .salami-hero {
        padding: 60px 0 80px;
    }
    
    .total-amount {
        font-size: 2rem;
    }
    
    .total-card {
        text-align: center;
    }
    
    .total-amount-box {
        margin-top: 15px;
    }
}

/* Loading State */
.btn.loading {
    position: relative;
    color: transparent !important;
}

.btn.loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin: -10px 0 0 -10px;
    border: 3px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.phone-display {
    font-size: 0.85rem;
    opacity: 0.7;
}
</style>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userInfoForm = document.getElementById('userInfoForm');
    const addEntryForm = document.getElementById('addEntryForm');
    const userInfoCard = document.getElementById('userInfoCard');
    const calculatorSection = document.getElementById('calculatorSection');
    const entriesList = document.getElementById('entriesList');
    const totalAmountEl = document.getElementById('totalAmount');
    const entriesCountEl = document.getElementById('entriesCount');
    const statsCard = document.getElementById('statsCard');
    const emptyState = document.getElementById('emptyState');

    // Quick amount buttons
    document.querySelectorAll('.quick-amt').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('amount').value = this.dataset.amount;
        });
    });

    // Start Session / Login Form
    if (userInfoForm) {
        userInfoForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.classList.add('loading');
            
            try {
                const response = await fetch('{{ route("salami.start-session") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_name: document.getElementById('userName').value,
                        phone: document.getElementById('userPhone').value
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Reload page to show data with cookies
                    if (data.has_previous_data) {
                        showToast('আপনার আগের ডাটা লোড হয়েছে!', 'success');
                    } else {
                        showToast('সেশন শুরু হয়েছে!', 'success');
                    }
                    setTimeout(() => location.reload(), 500);
                }
            } catch (error) {
                showToast('কিছু সমস্যা হয়েছে!', 'error');
            }
            
            btn.classList.remove('loading');
        });
    }

    // Add Entry Form
    if (addEntryForm) {
        addEntryForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('addEntryBtn');
            btn.classList.add('loading');
            
            try {
                const response = await fetch('{{ route("salami.add-entry") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        giver_name: document.getElementById('giverName').value,
                        giver_relation: document.getElementById('giverRelation').value,
                        amount: document.getElementById('amount').value,
                        note: document.getElementById('note').value
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Add new entry to list
                    const entry = data.entry;
                    const firstChar = entry.giver_name.charAt(0);
                    const entryHtml = `
                        <div class="entry-item" data-id="${entry.id}">
                            <div class="entry-info">
                                <div class="entry-avatar">${firstChar}</div>
                                <div class="entry-details">
                                    <h6 class="mb-0">${entry.giver_name}</h6>
                                    <small class="text-muted">
                                        ${entry.giver_relation || 'অন্যান্য'}
                                        ${entry.note ? ' • ' + entry.note : ''}
                                    </small>
                                </div>
                            </div>
                            <div class="entry-amount">
                                <span class="amount">৳${Number(entry.amount).toLocaleString('en-IN')}</span>
                                <button class="btn btn-sm btn-link text-danger delete-entry" data-id="${entry.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Remove empty state if exists
                    const emptyStateEl = document.getElementById('emptyState');
                    if (emptyStateEl) {
                        emptyStateEl.remove();
                    }
                    
                    entriesList.insertAdjacentHTML('afterbegin', entryHtml);
                    totalAmountEl.textContent = Number(data.total).toLocaleString('en-IN');
                    entriesCountEl.textContent = data.entries_count;
                    
                    // Update stats
                    updateStats();
                    statsCard.style.display = 'block';
                    
                    // Reset form
                    document.getElementById('giverName').value = '';
                    document.getElementById('giverRelation').value = '';
                    document.getElementById('amount').value = '';
                    document.getElementById('note').value = '';
                    document.getElementById('giverName').focus();
                    
                    showToast('সালামি যোগ হয়েছে!', 'success');
                }
            } catch (error) {
                showToast('কিছু সমস্যা হয়েছে!', 'error');
            }
            
            btn.classList.remove('loading');
        });
    }

    // Delete Entry
    document.addEventListener('click', async function(e) {
        if (e.target.closest('.delete-entry')) {
            const btn = e.target.closest('.delete-entry');
            const id = btn.dataset.id;
            
            if (!confirm('আপনি কি নিশ্চিত?')) return;
            
            try {
                const response = await fetch(`/salami/entry/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    btn.closest('.entry-item').remove();
                    totalAmountEl.textContent = Number(data.total).toLocaleString('en-IN');
                    
                    const remaining = document.querySelectorAll('.entry-item').length;
                    entriesCountEl.textContent = remaining;
                    
                    if (remaining === 0) {
                        entriesList.innerHTML = `
                            <div class="empty-state" id="emptyState">
                                <div class="empty-icon">💰</div>
                                <h5>এখনও কোন সালামি যোগ করেননি</h5>
                                <p class="text-muted">বাম দিকের ফর্ম থেকে সালামি যোগ করুন</p>
                            </div>
                        `;
                        statsCard.style.display = 'none';
                    } else {
                        updateStats();
                    }
                    
                    showToast('মুছে ফেলা হয়েছে!', 'success');
                }
            } catch (error) {
                showToast('কিছু সমস্যা হয়েছে!', 'error');
            }
        }
    });

    // Logout Button
    document.getElementById('logoutBtn')?.addEventListener('click', async function() {
        if (!confirm('আপনি কি লগআউট করতে চান? আপনার ডাটা সংরক্ষিত থাকবে, পরে আবার মোবাইল নম্বর দিয়ে দেখতে পারবেন।')) return;
        
        try {
            const response = await fetch('{{ route("salami.reset") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('লগআউট হয়েছে!', 'success');
                setTimeout(() => location.reload(), 500);
            }
        } catch (error) {
            showToast('কিছু সমস্যা হয়েছে!', 'error');
        }
    });

    // Update Stats
    function updateStats() {
        const entries = document.querySelectorAll('.entry-item');
        const amounts = Array.from(entries).map(e => {
            const amountText = e.querySelector('.amount').textContent;
            return parseInt(amountText.replace(/[৳,]/g, ''));
        });
        
        if (amounts.length > 0) {
            document.getElementById('totalGivers').textContent = amounts.length;
            document.getElementById('avgAmount').textContent = Math.round(amounts.reduce((a, b) => a + b, 0) / amounts.length).toLocaleString('en-IN');
            document.getElementById('maxAmount').textContent = Math.max(...amounts).toLocaleString('en-IN');
        }
    }

    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        toast.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: ${type === 'success' ? '#28a745' : '#dc3545'};
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            animation: slideUp 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideDown 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Download Card as PNG
    document.getElementById('downloadCardBtn')?.addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> জেনারেট হচ্ছে...';
        
        try {
            // Update card content dynamically
            const downloadCard = document.getElementById('downloadableCard');
            downloadCard.style.left = '0';
            downloadCard.style.position = 'fixed';
            downloadCard.style.zIndex = '-1';
            
            // Update total in card
            document.getElementById('cardTotalAmount').textContent = '৳' + totalAmountEl.textContent;
            document.getElementById('cardGiversCount').textContent = entriesCountEl.textContent;
            document.getElementById('cardAvgAmount').textContent = '৳' + document.getElementById('avgAmount').textContent;
            document.getElementById('cardMaxAmount').textContent = '৳' + document.getElementById('maxAmount').textContent;
            
            // Update entries in card
            const entries = document.querySelectorAll('#entriesList .entry-item');
            let entriesHtml = '<h4 style="margin: 0 0 15px; color: #333; font-size: 16px; border-bottom: 2px solid #28a745; padding-bottom: 10px;">📋 সালামির তালিকা</h4>';
            
            entries.forEach(entry => {
                const name = entry.querySelector('.entry-details h6').textContent;
                const relation = entry.querySelector('.entry-details small').textContent.split('•')[0].trim();
                const amount = entry.querySelector('.amount').textContent;
                const firstChar = entry.querySelector('.entry-avatar').textContent;
                
                entriesHtml += `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #eee;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #28a745, #1a5f2a); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px;">${firstChar}</div>
                            <div>
                                <div style="font-weight: 600; color: #333; font-size: 15px;">${name}</div>
                                <div style="color: #888; font-size: 12px;">${relation}</div>
                            </div>
                        </div>
                        <div style="font-weight: bold; color: #28a745; font-size: 18px;">${amount}</div>
                    </div>
                `;
            });
            
            document.getElementById('cardEntriesList').innerHTML = entriesHtml;
            
            // Wait for styles to apply
            await new Promise(resolve => setTimeout(resolve, 100));
            
            // Generate canvas
            const cardElement = downloadCard.querySelector('.salami-download-card');
            const canvas = await html2canvas(cardElement, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff',
                logging: false
            });
            
            // Download
            const link = document.createElement('a');
            const userName = document.querySelector('.user-name-display')?.textContent || 'salami';
            link.download = `salami-card-${userName.replace(/\s+/g, '-')}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            
            // Hide card again
            downloadCard.style.left = '-9999px';
            downloadCard.style.position = 'absolute';
            
            showToast('কার্ড ডাউনলোড হয়েছে!', 'success');
        } catch (error) {
            console.error('Download error:', error);
            showToast('ডাউনলোডে সমস্যা হয়েছে!', 'error');
        }
        
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-download me-1"></i> ডাউনলোড';
    });

    // Add animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideUp {
            from { transform: translateY(100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes slideDown {
            from { transform: translateY(0); opacity: 1; }
            to { transform: translateY(100px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
