<?php

use yii\db\Migration;

/**
 * Seeds initial Phase 4 Local Data (Sources & Market Indicators)
 * for key Kinondoni wards (Sinza, Mwenge, Kawe, Mikocheni, Kijitonyama).
 */
class m260919_131000_seed_kinondoni_local_data extends Migration
{
    public function safeUp()
    {
        $now = time();

        // 1. Data Sources
        $this->batchInsert('{{%local_data_source}}', ['name', 'type', 'description', 'is_demo', 'collected_at', 'created_at', 'updated_at'], [
            [
                'Uchunguzi wa Mtaani — MachoMtaa Field Survey 2026',
                'manual_observation',
                'Data zilizokusanywa kwa kutembelea mitaa na kuhesabu biashara hai katika kata za Kinondoni.',
                1,
                '2026-08-15',
                $now,
                $now,
            ],
            [
                'Makadirio ya Idadi ya Watu & Kaya — NBS Tanzania',
                'population_estimate',
                'Makadirio ya idadi ya watu, kaya na mzunguko wa kiuchumi kwa kata za Dar es Salaam.',
                1,
                '2026-01-10',
                $now,
                $now,
            ],
            [
                'Tathmini ya Miundombinu & Biashara — Kinondoni 2026',
                'economic_indicator',
                'Tathmini ya barabara, vituo vya usafiri, maegesho na upatikanaji wa huduma muhimu.',
                1,
                '2026-06-20',
                $now,
                $now,
            ],
            [
                'Uchunguzi wa Ushindani wa Biashara — Kinondoni Commercial Hubs',
                'competition_survey',
                'Tathmini ya mapengo ya fursa na msongamano wa maduka, saluni, na migahawa.',
                1,
                '2026-07-05',
                $now,
                $now,
            ],
        ]);

        // Fetch source IDs
        $sourceSurvey = (new \yii\db\Query())->from('{{%local_data_source}}')->where(['name' => 'Uchunguzi wa Mtaani — MachoMtaa Field Survey 2026'])->select('id')->scalar();
        $sourcePop = (new \yii\db\Query())->from('{{%local_data_source}}')->where(['name' => 'Makadirio ya Idadi ya Watu & Kaya — NBS Tanzania'])->select('id')->scalar();
        $sourceInfra = (new \yii\db\Query())->from('{{%local_data_source}}')->where(['name' => 'Tathmini ya Miundombinu & Biashara — Kinondoni 2026'])->select('id')->scalar();
        $sourceComp = (new \yii\db\Query())->from('{{%local_data_source}}')->where(['name' => 'Uchunguzi wa Ushindani wa Biashara — Kinondoni Commercial Hubs'])->select('id')->scalar();

        // Fetch ward location IDs
        $wards = ['Sinza', 'Mwenge', 'Kawe', 'Mikocheni', 'Kijitonyama'];
        $locMap = [];
        foreach ($wards as $w) {
            $id = (new \yii\db\Query())->from('{{%location}}')->where(['ward' => $w, 'municipality' => 'Kinondoni'])->select('id')->scalar();
            if ($id) {
                $locMap[$w] = (int)$id;
            }
        }

        $indicators = [];

        // Sinza
        if (isset($locMap['Sinza'])) {
            $indicators[] = [$locMap['Sinza'], null, $sourceSurvey, 'density', null, 'Juu Sana', 'high', 'Kanda yenye msongamano mkubwa wa biashara za huduma, saluni, migahawa na maduka.', 1, $now, $now];
            $indicators[] = [$locMap['Sinza'], null, $sourceSurvey, 'demand', null, 'Juu', 'high', 'Wateja wengi wa nyakati za jioni na wikendi, mzunguko wa fedha upo juu.', 1, $now, $now];
            $indicators[] = [$locMap['Sinza'], null, $sourceComp, 'competition', null, 'Mkali', 'medium', 'Ushindani mkubwa wa biashara za vinywaji na saluni kando ya barabara kuu.', 1, $now, $now];
            $indicators[] = [$locMap['Sinza'], null, $sourceInfra, 'market_strength', null, 'Imara', 'high', 'Upatikanaji mzuri wa miundombinu, usafiri, na umeme wa uhakika.', 1, $now, $now];
        }

        // Mwenge
        if (isset($locMap['Mwenge'])) {
            $indicators[] = [$locMap['Mwenge'], null, $sourceSurvey, 'density', null, 'Juu', 'high', 'Kituo kikuu cha usafiri na biashara za rejareja, nguo na vifaa vya elektroniki.', 1, $now, $now];
            $indicators[] = [$locMap['Mwenge'], null, $sourceSurvey, 'demand', null, 'Juu Sana', 'high', 'Watu wengi wanaopita kuelekea vituo vya daladala na mwendokasi.', 1, $now, $now];
            $indicators[] = [$locMap['Mwenge'], null, $sourceComp, 'gaps', null, 'Fursa Zipo', 'medium', 'Mahitaji makubwa ya huduma za haraka za chakula na vifaa vya ofisi.', 1, $now, $now];
            $indicators[] = [$locMap['Mwenge'], null, $sourceInfra, 'market_strength', null, 'Nzuri', 'medium', 'Upatikanaji wa masoko na vituo vya usafiri kwa saa 18 kwa siku.', 1, $now, $now];
        }

        // Kawe
        if (isset($locMap['Kawe'])) {
            $indicators[] = [$locMap['Kawe'], null, $sourceSurvey, 'density', null, 'Wastani', 'medium', 'Eneo linalokua kwa kasi kuelekea makazi ya kisasa na vituo vya huduma.', 1, $now, $now];
            $indicators[] = [$locMap['Kawe'], null, $sourcePop, 'market_strength', null, 'Inakua', 'medium', 'Uwezo wa manunuzi wa kaya za wastani na juu unaongezeka kila mwaka.', 1, $now, $now];
            $indicators[] = [$locMap['Kawe'], null, $sourceComp, 'gaps', null, 'Nafasi Zipo', 'medium', 'Maduka ya dawa, minimarkets na huduma za watoto bado ni chache.', 1, $now, $now];
        }

        // Mikocheni
        if (isset($locMap['Mikocheni'])) {
            $indicators[] = [$locMap['Mikocheni'], null, $sourceSurvey, 'density', null, 'Wastani', 'high', 'Mchanganyiko wa ofisi za kampuni, hospitali, na makazi ya hadhi ya juu.', 1, $now, $now];
            $indicators[] = [$locMap['Mikocheni'], null, $sourcePop, 'market_strength', null, 'Juu', 'high', 'Uwezo mkubwa wa kifedha wa wateja, unaofaa kwa bidhaa na huduma zenye ubora wa juu.', 1, $now, $now];
            $indicators[] = [$locMap['Mikocheni'], null, $sourceInfra, 'risk', null, 'Chini', 'high', 'Usalama mzuri na miundombinu imara ya barabara na maegesho ya magari.', 1, $now, $now];
        }

        // Kijitonyama
        if (isset($locMap['Kijitonyama'])) {
            $indicators[] = [$locMap['Kijitonyama'], null, $sourceSurvey, 'density', null, 'Wastani kuelekea Juu', 'high', 'Karibu na vituo vya teknolojia (Sayansi), huduma za mawasiliano na ofisi za wataalamu.', 1, $now, $now];
            $indicators[] = [$locMap['Kijitonyama'], null, $sourceSurvey, 'demand', null, 'Wastani', 'medium', 'Mahitaji thabiti ya vyakula vya mchana, vifaa vya kompyuta na huduma za kitaalamu.', 1, $now, $now];
        }

        if (!empty($indicators)) {
            $this->batchInsert(
                '{{%market_indicator}}',
                ['location_id', 'category_id', 'data_source_id', 'indicator_type', 'value_numeric', 'value_label', 'confidence_level', 'notes', 'is_demo', 'created_at', 'updated_at'],
                $indicators
            );
        }
    }

    public function safeDown()
    {
        $this->delete('{{%market_indicator}}');
        $this->delete('{{%local_data_source}}');
    }
}

