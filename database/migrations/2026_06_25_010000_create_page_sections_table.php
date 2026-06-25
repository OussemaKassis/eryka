<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->default('about');
            $table->string('title');
            $table->text('body');
            $table->string('image_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('page_sections')->insert([
            [
                'page_key' => 'home',
                'title' => 'Bienvenue chez Eryka',
                'body' => "Chez Eryka, chaque objet a une âme. On imagine, on dessine, on ajuste… jusqu'à ce qu'il trouve sa place dans votre intérieur.\n\nCe qu'on aime ? Les formes simples, les textures douces, et surtout : un design qui respecte la planète.",
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_key' => 'about',
                'title' => 'Bienvenue chez Eryka',
                'body' => "Chez Eryka, chaque objet a une âme. On imagine, on dessine, on ajuste… jusqu'à ce qu'il trouve sa place dans votre intérieur.\n\nCe qu'on aime ? Les formes simples, les textures douces, et surtout : un design qui respecte la planète.",
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_key' => 'about',
                'title' => 'Des matériaux responsables',
                'body' => "Tous nos objets sont fabriqués à partir d'un matériau biosourcé, écologique et biodégradable. Un choix qui reflète notre envie de créer avec respect — respect de la matière, de l'environnement, et de ceux qui nous entourent.",
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_key' => 'about',
                'title' => 'Tout commence par un trait',
                'body' => "Avant de donner vie à un objet, il y a l'idée. Des croquis à la main, des recherches, des détails qu'on affine avec patience…\n\nOn veut que chaque pièce soit unique, et qu'elle vous parle.",
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_key' => 'about',
                'title' => 'Inspirés par ce qui nous entoure',
                'body' => "Chez Eryka, on s'inspire de la nature, des formes organiques, de l'architecture minimaliste, et de l'artisanat tunisien. Ce mélange donne naissance à des objets à la fois modernes, sensibles et intemporels.",
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_key' => 'about',
                'title' => "Merci d'accueillir Eryka chez vous",
                'body' => "Nous sommes une petite marque, mais chaque pièce est conçue avec soin et passion.\n\nNotre objectif : offrir des objets durables, esthétiques et porteurs de sens.",
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
