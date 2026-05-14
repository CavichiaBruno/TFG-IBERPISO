<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

try {
    echo "Total Properties: " . \App\Models\Property::count() . "\n";
    echo "Active Properties: " . \App\Models\Property::active()->count() . "\n";
    echo "Unread Inquiries: " . \App\Models\Inquiry::unread()->count() . "\n";
    echo "Total Users: " . \App\Models\User::where('activo', \DB::raw('true'))->count() . "\n";
    $recentProperties = \App\Models\Property::latest()->take(5)->get();
    foreach ($recentProperties as $p) {
        echo "Property {$p->id} cover: " . $p->cover_url . "\n";
    }
    echo "SUCCESS\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
