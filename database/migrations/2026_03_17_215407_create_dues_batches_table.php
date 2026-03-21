<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dues_batches', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // monthly_hoa, special_assessment, regular_fees
            $table->date('billing_period_start');
            $table->date('due_date');
            $table->string('frequency'); // one_time, monthly, quarterly
            $table->decimal('total_expected', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
        });

        // Add batch_id to dues table if it doesn't exist, otherwise ensure it's linked
        Schema::table('dues', function (Blueprint $table) {
            if (!Schema::hasColumn('dues', 'batch_id')) {
                $table->unsignedBigInteger('batch_id')->nullable()->after('id');
            }
            // We'll add the foreign key in a separate step or here if we're sure
            // $table->foreign('batch_id')->references('id')->on('dues_batches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dues_batches');
    }
};
