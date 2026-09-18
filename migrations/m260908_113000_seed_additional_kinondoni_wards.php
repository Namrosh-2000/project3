<?php

use yii\db\Migration;

/**
 * Seeds additional Kinondoni wards (Sinza, Kawe, Tegeta, Mbezi Beach, Kunduchi)
 * and adds missing query indexes for seeker_id and reporter_id.
 */
class m260908_113000_seed_additional_kinondoni_wards extends Migration
{
    public function safeUp()
    {
        $now = time();

        $additionalWards = [
            'Sinza',
            'Kawe',
            'Tegeta',
            'Mbezi Beach',
            'Kunduchi',
        ];

        $rows = [];
        foreach ($additionalWards as $ward) {
            $exists = (new \yii\db\Query())
                ->from('{{%location}}')
                ->where(['municipality' => 'Kinondoni', 'ward' => $ward])
                ->exists();

            if (!$exists) {
                $rows[] = ['Dar es Salaam', 'Kinondoni', $ward, null, null, null, $now, $now];
            }
        }

        if (!empty($rows)) {
            $this->batchInsert(
                '{{%location}}',
                ['region', 'municipality', 'ward', 'street', 'latitude', 'longitude', 'created_at', 'updated_at'],
                $rows
            );
        }

        // Add indexes for performance if they don't exist
        $this->createIndex('idx-inquiry-seeker_id', '{{%inquiry}}', 'seeker_id');
        $this->createIndex('idx-report-reporter_id', '{{%report}}', 'reporter_id');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-inquiry-seeker_id', '{{%inquiry}}');
        $this->dropIndex('idx-report-reporter_id', '{{%report}}');

        $this->delete('{{%location}}', [
            'municipality' => 'Kinondoni',
            'ward' => ['Sinza', 'Kawe', 'Tegeta', 'Mbezi Beach', 'Kunduchi']
        ]);
    }
}
