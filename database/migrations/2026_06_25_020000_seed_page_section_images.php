<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    // page_key => [sort_order => [source vendor stock image, destination filename]]
    private array $map = [
        'home' => [
            1 => ['why-choose-us-img.jpg', 'home-welcome.jpg'],
        ],
        'about' => [
            1 => ['why-choose-us-img.jpg', 'welcome.jpg'],
            2 => ['post-1.jpg', 'materials.jpg'],
            3 => ['post-3.jpg', 'sketch.jpg'],
            4 => ['post-2.jpg', 'nature.jpg'],
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $directory = storage_path('app/public/page-sections');
        File::ensureDirectoryExists($directory);

        foreach ($this->map as $pageKey => $sections) {
            foreach ($sections as $sortOrder => [$source, $destination]) {
                $sourcePath = public_path('vendor/furni/images/' . $source);
                $destinationPath = $directory . '/' . $destination;

                if (File::exists($sourcePath) && ! File::exists($destinationPath)) {
                    File::copy($sourcePath, $destinationPath);
                }

                DB::table('page_sections')
                    ->where('page_key', $pageKey)
                    ->where('sort_order', $sortOrder)
                    ->update(['image_path' => 'page-sections/' . $destination]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->map as $pageKey => $sections) {
            foreach ($sections as $sortOrder => [$source, $destination]) {
                DB::table('page_sections')
                    ->where('page_key', $pageKey)
                    ->where('sort_order', $sortOrder)
                    ->where('image_path', 'page-sections/' . $destination)
                    ->update(['image_path' => null]);

                File::delete(storage_path('app/public/page-sections/' . $destination));
            }
        }
    }
};
