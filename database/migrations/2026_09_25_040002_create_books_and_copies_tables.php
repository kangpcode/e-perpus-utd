<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->string('isbn', 30)->unique()->index();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->nullOnDelete();
            $table->year('publication_year')->nullable()->index();
            $table->string('language')->default('Bahasa Indonesia');
            $table->unsignedInteger('pages')->default(0);
            $table->text('synopsis')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('cover_gradient')->nullable();
            $table->string('cover_color')->nullable();
            $table->boolean('is_physical')->default(true);
            $table->boolean('is_digital')->default(false);
            $table->string('format_type', 20)->default('physical'); // 'physical' | 'pdf' | 'epub' | 'hybrid'
            $table->string('shelf_location')->nullable();
            $table->unsignedInteger('total_stock')->default(1);
            $table->unsignedInteger('available_stock')->default(1);
            $table->unsignedInteger('borrow_count')->default(0);
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->unsignedInteger('rating_count')->default(0);
            $table->json('tags')->nullable();
            $table->timestamps();

            // Indexes for SEO & fast searching
            $table->index(['category_id', 'format_type', 'available_stock']);
        });

        Schema::create('book_author', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete();
            $table->unique(['book_id', 'author_id']);
        });

        Schema::create('book_copies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->string('copy_code', 50)->unique()->index(); // e.g. B-04-001 or barcode
            $table->string('condition', 30)->default('good'); // 'good' | 'fair' | 'damaged'
            $table->string('status', 30)->default('available'); // 'available' | 'borrowed' | 'reserved' | 'maintenance'
            $table->timestamps();
        });

        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->string('file_size', 50)->nullable();
            $table->string('format', 20)->default('pdf'); // 'pdf' | 'epub'
            $table->json('sample_content')->nullable();
            $table->boolean('drm_watermark_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
        Schema::dropIfExists('book_copies');
        Schema::dropIfExists('book_author');
        Schema::dropIfExists('books');
    }
};
