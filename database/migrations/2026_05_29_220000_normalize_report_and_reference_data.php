<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_report_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_report_id')->constrained('test_reports')->onDelete('cascade');
            $table->foreignId('lab_test_id')->nullable()->constrained('lab_tests')->nullOnDelete();
            $table->string('category')->default('General');
            $table->string('subcategory')->nullable();
            $table->string('name');
            $table->string('observed_value');
            $table->string('unit')->nullable();
            $table->text('normal_value')->nullable();
            $table->text('biological_reference')->nullable();
            $table->string('flag', 10)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['test_report_id', 'sort_order']);
            $table->index(['name', 'observed_value']);
            $table->index('flag');
        });

        Schema::create('test_report_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_report_id')->nullable()->constrained('test_reports')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->longText('old_data')->nullable();
            $table->longText('new_data')->nullable();
            $table->timestamps();

            $table->index(['test_report_id', 'action']);
        });

        $this->syncLabTestReferenceColumnsToParameters();
        $this->prepareTestParameters();
        $this->prepareReferenceIntervals();
        $this->migrateReportJsonItems();
        $this->removeDuplicateLabTestColumns();

        Schema::table('test_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('test_reports', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('test_reports', function (Blueprint $table) {
            if (Schema::hasColumn('test_reports', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        $this->restoreLabTestColumns();

        Schema::dropIfExists('test_report_audits');
        Schema::dropIfExists('test_report_items');

        Schema::table('test_parameters', function (Blueprint $table) {
            if (Schema::hasColumn('test_parameters', 'critical_low')) {
                $table->dropColumn(['critical_low', 'critical_high']);
            }
            $table->dropForeign(['lab_test_id']);
            $table->dropUnique('test_parameters_lab_test_id_unique');
            $table->foreign('lab_test_id')->references('id')->on('lab_tests')->onDelete('cascade');
        });

        Schema::table('reference_intervals', function (Blueprint $table) {
            if (Schema::hasColumn('reference_intervals', 'lab_test_id')) {
                $table->dropForeign(['lab_test_id']);
            }
            $columns = ['lab_test_id', 'gender', 'age_min', 'age_max', 'reference_text', 'min_value', 'max_value'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('reference_intervals', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function syncLabTestReferenceColumnsToParameters(): void
    {
        if (!Schema::hasTable('test_parameters') || !Schema::hasColumn('lab_tests', 'unit')) {
            return;
        }

        DB::table('lab_tests')
            ->select([
                'id',
                'unit',
                'male_reference',
                'female_reference',
                'male_min',
                'male_max',
                'female_min',
                'female_max',
                'is_immunoassay',
            ])
            ->orderBy('id')
            ->chunkById(100, function ($tests) {
                foreach ($tests as $test) {
                    DB::table('test_parameters')->updateOrInsert(
                        ['lab_test_id' => $test->id],
                        [
                            'unit' => $test->unit,
                            'male_reference' => $test->male_reference,
                            'female_reference' => $test->female_reference,
                            'male_min' => $test->male_min,
                            'male_max' => $test->male_max,
                            'female_min' => $test->female_min,
                            'female_max' => $test->female_max,
                            'is_immunoassay' => $test->is_immunoassay ?? false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });
    }

    private function prepareTestParameters(): void
    {
        if (!Schema::hasTable('test_parameters')) {
            return;
        }

        $duplicates = DB::table('test_parameters')
            ->select('lab_test_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('lab_test_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('test_parameters')
                ->where('lab_test_id', $duplicate->lab_test_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('test_parameters', function (Blueprint $table) {
            if (!Schema::hasColumn('test_parameters', 'critical_low')) {
                $table->decimal('critical_low', 10, 2)->nullable()->after('female_max');
            }
            if (!Schema::hasColumn('test_parameters', 'critical_high')) {
                $table->decimal('critical_high', 10, 2)->nullable()->after('critical_low');
            }
            $table->unique('lab_test_id');
        });
    }

    private function prepareReferenceIntervals(): void
    {
        Schema::table('reference_intervals', function (Blueprint $table) {
            if (!Schema::hasColumn('reference_intervals', 'lab_test_id')) {
                $table->foreignId('lab_test_id')->nullable()->after('id')->constrained('lab_tests')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('reference_intervals', 'gender')) {
                $table->string('gender', 20)->nullable()->after('lab_test_id');
            }
            if (!Schema::hasColumn('reference_intervals', 'age_min')) {
                $table->unsignedSmallInteger('age_min')->default(0)->after('gender');
            }
            if (!Schema::hasColumn('reference_intervals', 'age_max')) {
                $table->unsignedSmallInteger('age_max')->nullable()->after('age_min');
            }
            if (!Schema::hasColumn('reference_intervals', 'reference_text')) {
                $table->text('reference_text')->nullable()->after('age_max');
            }
            if (!Schema::hasColumn('reference_intervals', 'min_value')) {
                $table->decimal('min_value', 10, 2)->nullable()->after('reference_text');
            }
            if (!Schema::hasColumn('reference_intervals', 'max_value')) {
                $table->decimal('max_value', 10, 2)->nullable()->after('min_value');
            }
        });

        $parameters = DB::table('test_parameters')->get();
        foreach ($parameters as $parameter) {
            $this->seedDefaultInterval($parameter->lab_test_id, 'Male', $parameter->male_reference ?? null, $parameter->male_min ?? null, $parameter->male_max ?? null);
            $this->seedDefaultInterval($parameter->lab_test_id, 'Female', $parameter->female_reference ?? null, $parameter->female_min ?? null, $parameter->female_max ?? null);
        }
    }

    private function seedDefaultInterval(int $labTestId, string $gender, ?string $reference, mixed $min, mixed $max): void
    {
        if ($reference === null && $min === null && $max === null) {
            return;
        }

        DB::table('reference_intervals')->updateOrInsert(
            [
                'lab_test_id' => $labTestId,
                'gender' => $gender,
                'age_min' => 0,
                'age_max' => 200,
            ],
            [
                'reference_text' => $reference,
                'min_value' => $min,
                'max_value' => $max,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function migrateReportJsonItems(): void
    {
        if (!Schema::hasColumn('test_reports', 'results')) {
            return;
        }

        $labTests = DB::table('lab_tests')->pluck('id', 'name');

        DB::table('test_reports')
            ->whereNotNull('results')
            ->orderBy('id')
            ->chunkById(100, function ($reports) use ($labTests) {
                foreach ($reports as $report) {
                    $items = json_decode($report->results, true);
                    if (!is_array($items)) {
                        continue;
                    }

                    foreach ($items as $index => $item) {
                        if (!is_array($item) || empty($item['name'])) {
                            continue;
                        }

                        DB::table('test_report_items')->insert([
                            'test_report_id' => $report->id,
                            'lab_test_id' => $labTests[$item['name']] ?? null,
                            'category' => $item['category'] ?? 'General',
                            'subcategory' => $item['subcategory'] ?? null,
                            'name' => $item['name'],
                            'observed_value' => (string) ($item['observed_value'] ?? ''),
                            'unit' => $item['unit'] ?? null,
                            'normal_value' => $item['normal_value'] ?? null,
                            'biological_reference' => $item['biological_reference'] ?? null,
                            'flag' => $item['flag'] ?? null,
                            'sort_order' => $index,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            });

        Schema::table('test_reports', function (Blueprint $table) {
            $table->dropColumn('results');
        });
    }

    private function removeDuplicateLabTestColumns(): void
    {
        $columns = ['unit', 'male_reference', 'female_reference', 'male_min', 'male_max', 'female_min', 'female_max', 'is_immunoassay'];
        $existing = array_values(array_filter($columns, fn ($column) => Schema::hasColumn('lab_tests', $column)));

        if ($existing === []) {
            return;
        }

        Schema::table('lab_tests', function (Blueprint $table) use ($existing) {
            $table->dropColumn($existing);
        });
    }

    private function restoreLabTestColumns(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            if (!Schema::hasColumn('lab_tests', 'unit')) {
                $table->string('unit')->nullable();
            }
            if (!Schema::hasColumn('lab_tests', 'male_reference')) {
                $table->text('male_reference')->nullable();
            }
            if (!Schema::hasColumn('lab_tests', 'female_reference')) {
                $table->text('female_reference')->nullable();
            }
            if (!Schema::hasColumn('lab_tests', 'male_min')) {
                $table->decimal('male_min', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('lab_tests', 'male_max')) {
                $table->decimal('male_max', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('lab_tests', 'female_min')) {
                $table->decimal('female_min', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('lab_tests', 'female_max')) {
                $table->decimal('female_max', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('lab_tests', 'is_immunoassay')) {
                $table->boolean('is_immunoassay')->default(false);
            }
        });
    }
};
