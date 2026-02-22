<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UpazilaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ListingImageController;
use App\Http\Controllers\MpController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\FaviconController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UpazilaController as AdminUpazilaController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\MpProfileController;
use App\Http\Controllers\Admin\MpQuestionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\ListingImageController as AdminListingImageController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Sitemap Routes (for SEO)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-listings.xml', [SitemapController::class, 'listings'])->name('sitemap.listings');
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-upazilas.xml', [SitemapController::class, 'upazilas'])->name('sitemap.upazilas');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');

// Dynamic Favicon
Route::get('/favicon.svg', [FaviconController::class, 'svg'])->name('favicon.svg');
Route::get('/favicon.ico', [FaviconController::class, 'png'])->name('favicon.ico');

// Language Switcher
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Upazilas
Route::get('/upazilas', [UpazilaController::class, 'index'])->name('upazilas.index');
Route::get('/upazila/{upazila}', [UpazilaController::class, 'show'])->name('upazilas.show');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Listings
Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('/listing/{listing}', [ListingController::class, 'show'])->name('listings.show');
Route::post('/listing/{listing}/comment', [ListingController::class, 'storeComment'])->name('listings.comment');

// MP Section
Route::get('/mp', [MpController::class, 'index'])->name('mp.index');
Route::post('/mp/ask', [MpController::class, 'askQuestion'])->name('mp.ask');

// News
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

// Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'storeContact'])->name('contact.store');
Route::get('/page/{page}', [PageController::class, 'show'])->name('pages.show');

// User Dashboard (Authenticated Users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/listings', [UserDashboardController::class, 'listings'])->name('dashboard.listings');
    Route::get('/dashboard/listings/create', [UserDashboardController::class, 'createListing'])->name('dashboard.listings.create');
    Route::post('/dashboard/listings', [UserDashboardController::class, 'storeListing'])->name('dashboard.listings.store');
    Route::get('/dashboard/listings/{listing}/edit', [UserDashboardController::class, 'editListing'])->name('dashboard.listings.edit');
    Route::put('/dashboard/listings/{listing}', [UserDashboardController::class, 'updateListing'])->name('dashboard.listings.update');
    Route::delete('/dashboard/listings/{listing}', [UserDashboardController::class, 'destroyListing'])->name('dashboard.listings.destroy');
    Route::post('/dashboard/listings/{listing}/resubmit', [UserDashboardController::class, 'resubmitListing'])->name('dashboard.listings.resubmit');
    
    // Duplicate Check API
    Route::post('/dashboard/listings/check-duplicate', [UserDashboardController::class, 'checkDuplicate'])->name('dashboard.listings.check-duplicate');
    
    // Listing Images (Offers, Promotions, Banners)
    Route::get('/dashboard/listings/{listing}/images', [ListingImageController::class, 'index'])->name('dashboard.listings.images');
    Route::get('/dashboard/listings/{listing}/images/create', [ListingImageController::class, 'create'])->name('dashboard.listings.images.create');
    Route::post('/dashboard/listings/{listing}/images', [ListingImageController::class, 'store'])->name('dashboard.listings.images.store');
    Route::get('/dashboard/listings/{listing}/images/{image}/edit', [ListingImageController::class, 'edit'])->name('dashboard.listings.images.edit');
    Route::put('/dashboard/listings/{listing}/images/{image}', [ListingImageController::class, 'update'])->name('dashboard.listings.images.update');
    Route::delete('/dashboard/listings/{listing}/images/{image}', [ListingImageController::class, 'destroy'])->name('dashboard.listings.images.destroy');
    
    Route::get('/dashboard/my-questions', [UserDashboardController::class, 'questions'])->name('dashboard.my-questions');
    Route::get('/dashboard/profile', [UserDashboardController::class, 'profile'])->name('dashboard.profile');
    Route::put('/dashboard/profile', [UserDashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::put('/dashboard/password', [UserDashboardController::class, 'changePassword'])->name('dashboard.password');
    
    // Access Request
    Route::get('/dashboard/request-access', [UserDashboardController::class, 'requestAccess'])->name('dashboard.request-access');
    Route::post('/dashboard/request-access', [UserDashboardController::class, 'storeAccessRequest'])->name('dashboard.request-access.store');
    
    // MP question posting
    Route::post('/mp/question', [MpController::class, 'askQuestion'])->name('mp.question');
});

// Listings public routes
Route::post('/listing/{listing}/comment', [ListingController::class, 'storeComment'])->name('listings.comment')->middleware('auth');

// Admin Panel Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    
    // Users Management
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
    Route::post('users/{user}/approve-category/{category}', [AdminUserController::class, 'approveCategory'])->name('users.approve-category');
    Route::post('users/{user}/reject-category/{category}', [AdminUserController::class, 'rejectCategory'])->name('users.reject-category');
    Route::post('users/{user}/make-moderator', [AdminUserController::class, 'makeModerator'])->name('users.make-moderator');
    Route::post('users/{user}/make-upazila-moderator', [AdminUserController::class, 'makeModerator'])->name('users.make-upazila-moderator');
    Route::post('users/{user}/remove-moderator', [AdminUserController::class, 'removeModerator'])->name('users.remove-moderator');
    Route::post('users/{user}/make-own-business-moderator', [AdminUserController::class, 'makeOwnBusinessModerator'])->name('users.make-own-business-moderator');
    Route::post('users/{user}/remove-own-business-moderator', [AdminUserController::class, 'removeOwnBusinessModerator'])->name('users.remove-own-business-moderator');
    Route::post('users/{user}/toggle-ad-permission', [AdminUserController::class, 'toggleAdPermission'])->name('users.toggle-ad-permission');
    
    // Upazilas Management
    Route::resource('upazilas', AdminUpazilaController::class);
    
    // Categories Management
    Route::resource('categories', AdminCategoryController::class);
    
    // Listings Management
    Route::resource('listings', AdminListingController::class);
    Route::post('listings/{listing}/approve', [AdminListingController::class, 'approve'])->name('listings.approve');
    Route::post('listings/{listing}/reject', [AdminListingController::class, 'reject'])->name('listings.reject');
    Route::post('listings/{listing}/toggle-featured', [AdminListingController::class, 'toggleFeatured'])->name('listings.toggle-featured');
    
    // Listing Images (Offers, Promotions, Banners) Approval
    Route::get('listing-images', [AdminListingImageController::class, 'index'])->name('listing-images.index');
    Route::get('listing-images/{listingImage}', [AdminListingImageController::class, 'show'])->name('listing-images.show');
    Route::post('listing-images/{listingImage}/approve', [AdminListingImageController::class, 'approve'])->name('listing-images.approve');
    Route::post('listing-images/{listingImage}/reject', [AdminListingImageController::class, 'reject'])->name('listing-images.reject');
    Route::post('listing-images/{listingImage}/toggle-homepage', [AdminListingImageController::class, 'toggleHomepage'])->name('listing-images.toggle-homepage');
    Route::post('listing-images/{listingImage}/update-priority', [AdminListingImageController::class, 'updatePriority'])->name('listing-images.update-priority');
    Route::post('listing-images/bulk-approve', [AdminListingImageController::class, 'bulkApprove'])->name('listing-images.bulk-approve');
    Route::post('listing-images/bulk-reject', [AdminListingImageController::class, 'bulkReject'])->name('listing-images.bulk-reject');
    Route::delete('listing-images/{listingImage}', [AdminListingImageController::class, 'destroy'])->name('listing-images.destroy');
    
    // Comments Management
    Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::get('comments/{comment}', [AdminCommentController::class, 'show'])->name('comments.show');
    Route::post('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
    Route::post('comments/{comment}/reject', [AdminCommentController::class, 'reject'])->name('comments.reject');
    Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('comments/bulk-action', [AdminCommentController::class, 'bulkAction'])->name('comments.bulk-action');
    
    // MP Profiles Management
    Route::resource('mp-profiles', MpProfileController::class);
    
    // MP Questions Management
    Route::get('mp-questions', [MpQuestionController::class, 'index'])->name('mp-questions.index');
    Route::get('mp-questions/{mpQuestion}', [MpQuestionController::class, 'show'])->name('mp-questions.show');
    Route::get('mp-questions/{mpQuestion}/edit', [MpQuestionController::class, 'edit'])->name('mp-questions.edit');
    Route::put('mp-questions/{mpQuestion}', [MpQuestionController::class, 'update'])->name('mp-questions.update');
    Route::post('mp-questions/{mpQuestion}/approve', [MpQuestionController::class, 'approve'])->name('mp-questions.approve');
    Route::post('mp-questions/{mpQuestion}/reject', [MpQuestionController::class, 'reject'])->name('mp-questions.reject');
    Route::post('mp-questions/{mpQuestion}/answer', [MpQuestionController::class, 'answer'])->name('mp-questions.answer');
    Route::delete('mp-questions/{mpQuestion}', [MpQuestionController::class, 'destroy'])->name('mp-questions.destroy');
    
    // Sliders Management
    Route::resource('sliders', SliderController::class);
    Route::post('sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');
    
    // News Management
    Route::resource('news', AdminNewsController::class);
    
    // Contacts Management
    Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::post('contacts/{contact}/mark-read', [AdminContactController::class, 'markRead'])->name('contacts.markRead');
    Route::post('contacts/{contact}/reply', [AdminContactController::class, 'reply'])->name('contacts.reply');
    Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
    
    // Settings
    Route::get('settings', [SettingController::class, 'general'])->name('settings.index');
    Route::get('settings/general', [SettingController::class, 'general'])->name('settings.general');
    Route::get('settings/contact', [SettingController::class, 'contact'])->name('settings.contact');
    Route::get('settings/social', [SettingController::class, 'social'])->name('settings.social');
    Route::get('settings/about', [SettingController::class, 'about'])->name('settings.about');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Team Members Management
    Route::get('team', [\App\Http\Controllers\Admin\TeamMemberController::class, 'index'])->name('team.index');
    Route::post('team', [\App\Http\Controllers\Admin\TeamMemberController::class, 'store'])->name('team.store');
    Route::put('team/{teamMember}', [\App\Http\Controllers\Admin\TeamMemberController::class, 'update'])->name('team.update');
    Route::delete('team/{teamMember}', [\App\Http\Controllers\Admin\TeamMemberController::class, 'destroy'])->name('team.destroy');
    Route::post('team/{teamMember}/toggle-active', [\App\Http\Controllers\Admin\TeamMemberController::class, 'toggleActive'])->name('team.toggle-active');
});

require __DIR__.'/auth.php';
