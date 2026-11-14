
<?php

use App\Enums\Status;
use App\Enums\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('type', [Type::LOST->value, Type::FOUND->value])->default(Type::LOST->value);

            $table->string('title', 150);
            $table->string('category', 50);
            $table->text('description');

            $table->string('color', 50)->nullable();
            $table->string('location', 150);
            $table->date('event_date');

            $table->string('image_path')->nullable();

            $table->enum('status', [Status::ACTIVE->value, Status::RESOLVED->value])->default(Status::ACTIVE->value);

            $table->boolean('is_hidden')->default(false);

            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index(['category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
