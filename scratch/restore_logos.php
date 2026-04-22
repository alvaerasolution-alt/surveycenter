<?php
use App\Models\PartnerLogo;
use Illuminate\Support\Facades\Storage;

$files = Storage::disk('public')->files('partner_logos');
$count = 0;
foreach ($files as $index => $file) {
    if (PartnerLogo::where('logo_path', $file)->exists()) continue;
    PartnerLogo::create([
        'name' => 'Partner ' . ($index + 1),
        'logo_path' => $file
    ]);
    $count++;
}
echo "Restored $count partner logos.\n";
