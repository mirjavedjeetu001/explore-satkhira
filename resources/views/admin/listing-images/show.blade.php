@extends('admin.layouts.app')

@section('title', 'ছবির বিস্তারিত')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">ছবির বিস্তারিত</h3>
                    <a href="{{ route('admin.listing-images.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> ফিরে যান
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center mb-4">
                                <img src="{{ $listingImage->image_url }}" alt="{{ $listingImage->title }}" 
                                     class="img-fluid rounded" style="max-height: 400px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">শিরোনাম</th>
                                    <td>{{ $listingImage->title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>বিবরণ</th>
                                    <td>{{ $listingImage->description ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>তথ্য</th>
                                    <td>
                                        @if($listingImage->listing)
                                            <a href="{{ route('admin.listings.edit', $listingImage->listing) }}">
                                                {{ $listingImage->listing->name }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>আপলোডকারী</th>
                                    <td>{{ $listingImage->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>ধরন</th>
                                    <td>{!! $listingImage->type_badge !!}</td>
                                </tr>
                                <tr>
                                    <th>পজিশন</th>
                                    <td>{{ \App\Models\ListingImage::getPositions()[$listingImage->position] ?? $listingImage->position }}</td>
                                </tr>
                                <tr>
                                    <th>সাইজ</th>
                                    <td>{{ \App\Models\ListingImage::getDisplaySizes()[$listingImage->display_size] ?? $listingImage->display_size }}</td>
                                </tr>
                                <tr>
                                    <th>স্ট্যাটাস</th>
                                    <td>{!! $listingImage->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <th>বৈধতার তারিখ</th>
                                    <td>
                                        @if($listingImage->valid_from || $listingImage->valid_until)
                                            {{ $listingImage->valid_from ? $listingImage->valid_from->format('d/m/Y') : 'N/A' }}
                                            -
                                            {{ $listingImage->valid_until ? $listingImage->valid_until->format('d/m/Y') : 'N/A' }}
                                        @else
                                            সবসময় বৈধ
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>তৈরির তারিখ</th>
                                    <td>{{ $listingImage->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                            
                            @if($listingImage->status === 'pending')
                            <div class="mt-4">
                                <form action="{{ route('admin.listing-images.approve', $listingImage) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> অনুমোদন করুন
                                    </button>
                                </form>
                                <form action="{{ route('admin.listing-images.reject', $listingImage) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i> বাতিল করুন
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
