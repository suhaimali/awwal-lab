<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\FlagTemplate;
use App\Models\ReferenceTemplate;
use App\Models\ResultTemplate;
use App\Models\LabTest;
use App\Models\TestParameter;
use App\Models\ReferenceInterval;
use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Secure Admin User
        User::updateOrCreate(
            ['email' => 'lab@gmail.com'],
            [
                'name' => 'Safwan',
                'password' => '12345678', // Auto-hashed via User model cast
            ]
        );

        // 2. Seed Flag Templates
        $flags = ['L', 'H', 'N', 'C', 'Neg', 'Pos', 'B', 'Reactive', 'Non-Reactive'];
        foreach ($flags as $f) {
            FlagTemplate::updateOrCreate(['name' => $f]);
        }

        // 3. Seed Units
        $units = ['g/dL', 'million/cumm', '%', 'fl', 'pg', 'mg/dL', 'U/L', 'mIU/L', '/cumm'];
        foreach ($units as $u) {
            Unit::updateOrCreate(['name' => $u]);
        }

        // 4. Seed Reference Templates
        $refs = [
            '13.5 - 17.5 g/dL',
            '12.0 - 15.5 g/dL',
            '4.5 - 5.9 million/cumm',
            '3.8 - 5.2 million/cumm',
            '40 - 50 %',
            '35 - 45 %',
            '80 - 100 fl',
            '27 - 32 pg',
            '32 - 36 g/dL',
            '70 - 100 mg/dL',
            '0.6 - 1.2 mg/dL',
            '15 - 45 mg/dL',
            'Negative',
            'Non-Reactive',
            'Normal'
        ];
        foreach ($refs as $r) {
            ReferenceTemplate::updateOrCreate(['name' => $r]);
        }

        // 5. Seed Result Templates
        $results = [
            'Normal',
            'Negative',
            'Positive',
            'Borderline',
            'Reactive',
            'Non-Reactive',
            '10',
            '12',
            '14',
            '15',
            '13.5',
            '4.5',
            '5.0',
            '40',
            '45',
            '85',
            '29',
            '34',
            '90',
            '0.8',
            '1.0',
            '20',
            '30'
        ];
        foreach ($results as $res) {
            ResultTemplate::updateOrCreate(['name' => $res]);
        }

        // 6. Seed Categories
        $catHaem = Category::updateOrCreate(['name' => 'HAEMATOLOGY']);
        $catBiochem = Category::updateOrCreate(['name' => 'BIOCHEMISTRY']);
        $catPathology = Category::updateOrCreate(['name' => 'CLINICAL PATHOLOGY']);
        $catSerology = Category::updateOrCreate(['name' => 'SEROLOGY & IMMUNOLOGY']);

        // 7. Seed Sub-Categories
        $subCbc = SubCategory::updateOrCreate(['name' => 'COMPLETE BLOOD COUNT (CBC)', 'category_id' => $catHaem->id]);
        $subRft = SubCategory::updateOrCreate(['name' => 'RENAL FUNCTION TEST (RFT)', 'category_id' => $catBiochem->id]);
        $subLft = SubCategory::updateOrCreate(['name' => 'LIVER FUNCTION TEST (LFT)', 'category_id' => $catBiochem->id]);
        $subDiabetic = SubCategory::updateOrCreate(['name' => 'DIABETIC PROFILE', 'category_id' => $catBiochem->id]);

        // 8. Seed Doctors
        Doctor::updateOrCreate(
            ['name' => 'Dr. Suhaim Soft'],
            [
                'qualification' => 'MBBS, MD',
                'phone' => '+91 8891479505',
                'email' => 'suhaim@suhaimsoft.com'
            ]
        );
        Doctor::updateOrCreate(
            ['name' => 'Dr. Safwan'],
            [
                'qualification' => 'MBBS, MD (Pathology)',
                'phone' => '+91 7034250209',
                'email' => 'safwan@suhaimsoft.com'
            ]
        );
        Doctor::updateOrCreate(
            ['name' => 'Self'],
            [
                'qualification' => '',
                'phone' => '',
                'email' => null
            ]
        );

        // 9. Seed Lab Tests & Parameters
        // Hemoglobin (Hb)
        $testHb = LabTest::updateOrCreate(
            ['name' => 'Hemoglobin (Hb)'],
            [
                'price' => 120,
                'description' => '13.5 - 17.5 g/dL'
            ]
        );
        TestParameter::updateOrCreate(
            ['lab_test_id' => $testHb->id],
            [
                'unit' => 'g/dL',
                'male_reference' => '13.5 - 17.5 g/dL',
                'female_reference' => '12.0 - 15.5 g/dL',
                'male_min' => 13.5,
                'male_max' => 17.5,
                'female_min' => 12.0,
                'female_max' => 15.5,
                'critical_low' => 7.0,
                'critical_high' => 20.0,
                'is_immunoassay' => 0
            ]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testHb->id, 'gender' => 'Male', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '13.5 - 17.5 g/dL', 'min_value' => 13.5, 'max_value' => 17.5]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testHb->id, 'gender' => 'Female', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '12.0 - 15.5 g/dL', 'min_value' => 12.0, 'max_value' => 15.5]
        );

        // RBC Count
        $testRbc = LabTest::updateOrCreate(
            ['name' => 'RBC Count'],
            [
                'price' => 100,
                'description' => '4.5 - 5.9 million/cumm'
            ]
        );
        TestParameter::updateOrCreate(
            ['lab_test_id' => $testRbc->id],
            [
                'unit' => 'million/cumm',
                'male_reference' => '4.5 - 5.9 million/cumm',
                'female_reference' => '3.8 - 5.2 million/cumm',
                'male_min' => 4.5,
                'male_max' => 5.9,
                'female_min' => 3.8,
                'female_max' => 5.2,
                'is_immunoassay' => 0
            ]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testRbc->id, 'gender' => 'Male', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '4.5 - 5.9 million/cumm', 'min_value' => 4.5, 'max_value' => 5.9]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testRbc->id, 'gender' => 'Female', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '3.8 - 5.2 million/cumm', 'min_value' => 3.8, 'max_value' => 5.2]
        );

        // HCT
        $testHct = LabTest::updateOrCreate(
            ['name' => 'HCT'],
            [
                'price' => 100,
                'description' => '40 - 50 %'
            ]
        );
        TestParameter::updateOrCreate(
            ['lab_test_id' => $testHct->id],
            [
                'unit' => '%',
                'male_reference' => '40 - 50 %',
                'female_reference' => '35 - 45 %',
                'male_min' => 40.0,
                'male_max' => 50.0,
                'female_min' => 35.0,
                'female_max' => 45.0,
                'is_immunoassay' => 0
            ]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testHct->id, 'gender' => 'Male', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '40 - 50 %', 'min_value' => 40.0, 'max_value' => 50.0]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testHct->id, 'gender' => 'Female', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '35 - 45 %', 'min_value' => 35.0, 'max_value' => 45.0]
        );

        // MCV
        $testMcv = LabTest::updateOrCreate(
            ['name' => 'MCV'],
            [
                'price' => 100,
                'description' => '80 - 100 fl'
            ]
        );
        TestParameter::updateOrCreate(
            ['lab_test_id' => $testMcv->id],
            [
                'unit' => 'fl',
                'male_reference' => '80 - 100 fl',
                'female_reference' => '80 - 100 fl',
                'male_min' => 80.0,
                'male_max' => 100.0,
                'female_min' => 80.0,
                'female_max' => 100.0,
                'is_immunoassay' => 0
            ]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testMcv->id, 'gender' => 'Male', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '80 - 100 fl', 'min_value' => 80.0, 'max_value' => 100.0]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testMcv->id, 'gender' => 'Female', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '80 - 100 fl', 'min_value' => 80.0, 'max_value' => 100.0]
        );

        // MCH
        $testMch = LabTest::updateOrCreate(
            ['name' => 'MCH'],
            [
                'price' => 100,
                'description' => '27 - 32 pg'
            ]
        );
        TestParameter::updateOrCreate(
            ['lab_test_id' => $testMch->id],
            [
                'unit' => 'pg',
                'male_reference' => '27 - 32 pg',
                'female_reference' => '27 - 32 pg',
                'male_min' => 27.0,
                'male_max' => 32.0,
                'female_min' => 27.0,
                'female_max' => 32.0,
                'is_immunoassay' => 0
            ]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testMch->id, 'gender' => 'Male', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '27 - 32 pg', 'min_value' => 27.0, 'max_value' => 32.0]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testMch->id, 'gender' => 'Female', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '27 - 32 pg', 'min_value' => 27.0, 'max_value' => 32.0]
        );

        // MCHC
        $testMchc = LabTest::updateOrCreate(
            ['name' => 'MCHC'],
            [
                'price' => 100,
                'description' => '32 - 36 g/dL'
            ]
        );
        TestParameter::updateOrCreate(
            ['lab_test_id' => $testMchc->id],
            [
                'unit' => 'g/dL',
                'male_reference' => '32 - 36 g/dL',
                'female_reference' => '32 - 36 g/dL',
                'male_min' => 32.0,
                'male_max' => 36.0,
                'female_min' => 32.0,
                'female_max' => 36.0,
                'is_immunoassay' => 0
            ]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testMchc->id, 'gender' => 'Male', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '32 - 36 g/dL', 'min_value' => 32.0, 'max_value' => 36.0]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testMchc->id, 'gender' => 'Female', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '32 - 36 g/dL', 'min_value' => 32.0, 'max_value' => 36.0]
        );

        // Fasting Blood Sugar
        $testFbs = LabTest::updateOrCreate(
            ['name' => 'Blood Glucose (Fasting)'],
            [
                'price' => 150,
                'description' => '70 - 100 mg/dL'
            ]
        );
        TestParameter::updateOrCreate(
            ['lab_test_id' => $testFbs->id],
            [
                'unit' => 'mg/dL',
                'male_reference' => '70 - 100 mg/dL',
                'female_reference' => '70 - 100 mg/dL',
                'male_min' => 70.0,
                'male_max' => 100.0,
                'female_min' => 70.0,
                'female_max' => 100.0,
                'critical_low' => 50.0,
                'critical_high' => 250.0,
                'is_immunoassay' => 0
            ]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testFbs->id, 'gender' => 'Male', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '70 - 100 mg/dL', 'min_value' => 70.0, 'max_value' => 100.0]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testFbs->id, 'gender' => 'Female', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '70 - 100 mg/dL', 'min_value' => 70.0, 'max_value' => 100.0]
        );

        // Serum Creatinine
        $testCreat = LabTest::updateOrCreate(
            ['name' => 'Serum Creatinine'],
            [
                'price' => 150,
                'description' => '0.6 - 1.2 mg/dL'
            ]
        );
        TestParameter::updateOrCreate(
            ['lab_test_id' => $testCreat->id],
            [
                'unit' => 'mg/dL',
                'male_reference' => '0.6 - 1.2 mg/dL',
                'female_reference' => '0.5 - 1.1 mg/dL',
                'male_min' => 0.6,
                'male_max' => 1.2,
                'female_min' => 0.5,
                'female_max' => 1.1,
                'critical_high' => 3.0,
                'is_immunoassay' => 0
            ]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testCreat->id, 'gender' => 'Male', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '0.6 - 1.2 mg/dL', 'min_value' => 0.6, 'max_value' => 1.2]
        );
        ReferenceInterval::updateOrCreate(
            ['lab_test_id' => $testCreat->id, 'gender' => 'Female', 'age_min' => 0, 'age_max' => 200],
            ['reference_text' => '0.5 - 1.1 mg/dL', 'min_value' => 0.5, 'max_value' => 1.1]
        );
    }
}
