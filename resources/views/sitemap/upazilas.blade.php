{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($upazilas as $upazila)
    <url>
        <loc>{{ url('/upazila/' . ($upazila->slug ?? $upazila->id)) }}</loc>
        <lastmod>{{ $upazila->updated_at ? $upazila->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
</urlset>
