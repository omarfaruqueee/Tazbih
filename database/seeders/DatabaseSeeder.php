<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ZikirItem;
use App\Models\Hadith;
use App\Models\QuranVerse;
use App\Models\IslamicEvent;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Preset Zikir Items
        $zikirs = [
            [
                'name_bn' => 'সুবহানাল্লাহ',
                'name_en' => 'Subhanallah',
                'arabic_text' => 'سُبْحَانَ ٱللَّهِ',
                'default_target' => 33,
                'icon' => 'sparkles',
                'is_custom' => false
            ],
            [
                'name_bn' => 'আলহামদুলিল্লাহ',
                'name_en' => 'Alhamdulillah',
                'arabic_text' => 'ٱلْحَمْدُ لِلَّهِ',
                'default_target' => 33,
                'icon' => 'heart',
                'is_custom' => false
            ],
            [
                'name_bn' => 'আল্লাহু আকবার',
                'name_en' => 'Allahu Akbar',
                'arabic_text' => 'ٱللَّهُ أَكْبَرُ',
                'default_target' => 34,
                'icon' => 'sun',
                'is_custom' => false
            ],
            [
                'name_bn' => 'লা ইলাহা ইল্লাল্লাহ',
                'name_en' => 'La Ilaha Illallah',
                'arabic_text' => 'لَا إِلَٰهَ إِلَّا ٱللَّهُ',
                'default_target' => 100,
                'icon' => 'shield-check',
                'is_custom' => false
            ],
            [
                'name_bn' => 'আস্তাগফিরুল্লাহ',
                'name_en' => 'Astaghfirullah',
                'arabic_text' => 'أَسْتَغْفِرُ ٱللَّهَ',
                'default_target' => 100,
                'icon' => 'arrow-path',
                'is_custom' => false
            ],
            [
                'name_bn' => 'দরুদ শরীফ',
                'name_en' => 'Durood Shareef',
                'arabic_text' => 'اللَّهُمَّ صَلِّ عَلَى مُحَمَّدٍ',
                'default_target' => 100,
                'icon' => 'academic-cap',
                'is_custom' => false
            ]
        ];

        foreach ($zikirs as $zikir) {
            ZikirItem::create($zikir);
        }

        // 2. Seed Hadiths
        $hadiths = [
            [
                'text_ar' => 'إِنَّمَا الأَعْمَالُ بِالنِّيَّاتِ',
                'text_bn' => 'নিশ্চয়ই প্রতিটি কাজ নিয়তের উপর নির্ভরশীল।',
                'text_en' => 'Indeed, actions are judged by intentions.',
                'reference' => 'Sahih al-Bukhari 1'
            ],
            [
                'text_ar' => 'الدِّينُ النَّصِيحَةُ',
                'text_bn' => 'দ্বীন হচ্ছে অপরের কল্যাণ কামনা করা।',
                'text_en' => 'Religion is sincerity and well-wishing.',
                'reference' => 'Sahih Muslim 55'
            ],
            [
                'text_ar' => 'الطُّهُورُ شَطْرُ الإِيمَانِ',
                'text_bn' => 'পবিত্রতা ঈমানের অর্ধেক।',
                'text_en' => 'Purity is half of faith.',
                'reference' => 'Sahih Muslim 223'
            ],
            [
                'text_ar' => 'الْكَلِمَةُ الطَّيِّبَةُ صَدَقَةٌ',
                'text_bn' => 'উত্তম কথা একটি ভালো আমল বা সদকা।',
                'text_en' => 'A good word is charity.',
                'reference' => 'Sahih al-Bukhari 2989'
            ],
            [
                'text_ar' => 'خَيْرُكُمْ مَنْ تَعَلَّمَ الْقُرْآنَ وَعَلَّمَهُ',
                'text_bn' => 'তোমাদের মধ্যে সর্বোত্তম সে ব্যক্তি, যে নিজে কুরআন শেখে এবং অপরকে শেখায়।',
                'text_en' => 'The best among you is the one who learns the Quran and teaches it.',
                'reference' => 'Sahih al-Bukhari 5027'
            ]
        ];

        foreach ($hadiths as $hadith) {
            Hadith::create($hadith);
        }

        // 3. Seed Quran Verses
        $verses = [
            [
                'text_ar' => 'فَاذْكُرُونِي أَذْكُرْكُمْ وَاشْكُرُوا لِي وَلَا تَكْفُرُونِ',
                'text_bn' => 'অতএব তোমরা আমাকে স্মরণ করো, আমিও তোমাদের স্মরণ করব। আর আমার কৃতজ্ঞতা প্রকাশ করো, অকৃতজ্ঞ হয়ো না।',
                'text_en' => 'So remember Me; I will remember you. And be grateful to Me and do not deny Me.',
                'surah_name' => 'Al-Baqarah',
                'verse_no' => 152
            ],
            [
                'text_ar' => 'إِنَّ مَعَ الْعُسْرِ يُسْرًا',
                'text_bn' => 'নিশ্চয়ই কষ্টের সাথেই স্বস্তি রয়েছে।',
                'text_en' => 'Indeed, with hardship [will be] ease.',
                'surah_name' => 'Ash-Sharh',
                'verse_no' => 6
            ],
            [
                'text_ar' => 'وَقَالَ رَبُّكُمُ ادْعُونِي أَسْتَجِبْ لَكُمْ',
                'text_bn' => 'এবং তোমাদের পালনকর্তা বলেন, তোমরা আমাকে ডাকো, আমি তোমাদের ডাকে সাড়া দেব।',
                'text_en' => 'And your Lord says, "Call upon Me; I will respond to you."',
                'surah_name' => 'Ghafir',
                'verse_no' => 60
            ],
            [
                'text_ar' => 'وَمَن يَتَوَكَّلْ عَلَى اللَّهِ فَهُوَ حَسْبُهُ',
                'text_bn' => 'যে ব্যক্তি আল্লাহর উপর ভরসা করে, তার জন্য তিনিই যথেষ্ট।',
                'text_en' => 'And whoever relies upon Allah - then He is sufficient for him.',
                'surah_name' => 'At-Talaq',
                'verse_no' => 3
            ],
            [
                'text_ar' => 'إِنَّ اللَّهَ مَعَ الصَّابِرِينَ',
                'text_bn' => 'নিশ্চয়ই আল্লাহ ধৈর্যশীলদের সাথে আছেন।',
                'text_en' => 'Indeed, Allah is with the patient.',
                'surah_name' => 'Al-Baqarah',
                'verse_no' => 153
            ]
        ];

        foreach ($verses as $verse) {
            QuranVerse::create($verse);
        }

        // 4. Seed Islamic Events
        $events = [
            [
                'name_bn' => '১ম রমজান (রোজা শুরু)',
                'name_en' => '1st Ramadan (Fasting Begins)',
                'hijri_date' => '09-01'
            ],
            [
                'name_bn' => 'শবে কদর',
                'name_en' => 'Laylat al-Qadr',
                'hijri_date' => '09-27'
            ],
            [
                'name_bn' => 'ঈদুল ফিতর',
                'name_en' => 'Eid-ul-Fitr',
                'hijri_date' => '10-01'
            ],
            [
                'name_bn' => 'আরাফাহ দিবস',
                'name_en' => 'Day of Arafah',
                'hijri_date' => '12-09'
            ],
            [
                'name_bn' => 'ঈদুল আজহা',
                'name_en' => 'Eid-ul-Adha',
                'hijri_date' => '12-10'
            ],
            [
                'name_bn' => 'আশুরা',
                'name_en' => 'Ashura (10th Muharram)',
                'hijri_date' => '01-10'
            ],
            [
                'name_bn' => 'শবে বরাত',
                'name_en' => 'Laylat al-Baraat',
                'hijri_date' => '08-15'
            ],
            [
                'name_bn' => 'ঈদে মিলাদুন্নবী',
                'name_en' => 'Mawlid al-Nabi',
                'hijri_date' => '03-12'
            ]
        ];

        foreach ($events as $event) {
            IslamicEvent::create($event);
        }
    }
}
