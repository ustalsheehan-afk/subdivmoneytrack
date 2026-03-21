<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\Resident;

$resident = Resident::whereNotNull('photo')->first();

if (!$resident) {
    echo "No resident with photo found.\n";
    exit;
}

echo "Resident ID: " . $resident->id . "\n";
echo "DB Photo Path: " . $resident->photo . "\n";

$exists = Storage::disk('public')->exists($resident->photo);
echo "Storage::disk('public')->exists(): " . ($exists ? 'TRUE' : 'FALSE') . "\n";

$path = Storage::disk('public')->path($resident->photo);
echo "Full Path: " . $path . "\n";
echo "File exists at path? " . (file_exists($path) ? 'YES' : 'NO') . "\n";

$url = Storage::disk('public')->url($resident->photo);
echo "URL: " . $url . "\n";
