<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user as author (or create one if none exists)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@nimr.or.tz',
                'password' => bcrypt('password'),
                'centre' => 'headquarters',
                'station' => 'administration'
            ]);
        }

        $newsData = [
            [
                'title' => 'NIMR Mwanza Centre Launches New Malaria Research Initiative',
                'content' => "The National Institute for Medical Research (NIMR) Mwanza Centre has officially launched a groundbreaking malaria research initiative aimed at developing innovative prevention and treatment strategies for the Lake Zone region.\n\nThis comprehensive program will focus on understanding malaria transmission patterns around Lake Victoria and developing community-based interventions that are culturally appropriate and sustainable.\n\nDr. Sarah Mwalimu, the project lead, emphasized the importance of community involvement in this research. \"We believe that lasting solutions to malaria prevention must come from within the communities themselves,\" she stated during the launch ceremony.\n\nThe initiative has received funding from international partners and is expected to run for the next five years, with preliminary results anticipated within the first two years.\n\nKey research areas include:\n- Vector control strategies specific to lakeside communities\n- Behavioral change communication programs\n- Development of rapid diagnostic tools\n- Community health worker training programs\n\nThis project represents a significant step forward in NIMR's commitment to addressing Tanzania's most pressing health challenges through evidence-based research.",
                'location' => 'mwanza',
                'priority' => 'high',
                'status' => 'published',
                'is_featured' => true,
                'allow_comments' => true,
                'published_at' => Carbon::now()->subDays(2),
                'views_count' => 156,
                'likes_count' => 23
            ],
            [
                'title' => 'Headquarters Announces New Collaboration with International Research Partners',
                'content' => "NIMR Headquarters is pleased to announce a new strategic collaboration with leading international research institutions to advance medical research capabilities across Tanzania.\n\nThe partnership includes institutions from the United Kingdom, United States, and Sweden, focusing on capacity building, technology transfer, and joint research initiatives.\n\nDr. John Mwakigonja, Director General of NIMR, highlighted the significance of this collaboration: \"This partnership will significantly enhance our research infrastructure and provide our scientists with access to cutting-edge technologies and methodologies.\"\n\nKey components of the collaboration include:\n- Exchange programs for researchers and students\n- Joint grant applications for large-scale research projects\n- Technology transfer initiatives\n- Capacity building workshops and training programs\n- Shared access to research databases and publications\n\nThe collaboration is expected to strengthen NIMR's position as a leading research institution in Africa and contribute to solving health challenges not only in Tanzania but across the continent.\n\nImplementation will begin in the next quarter, with the first exchange program scheduled for early next year.",
                'location' => 'headquarters',
                'priority' => 'high',
                'status' => 'published',
                'is_featured' => true,
                'allow_comments' => true,
                'published_at' => Carbon::now()->subDays(5),
                'views_count' => 234,
                'likes_count' => 45
            ],
            [
                'title' => 'Tanga Centre Successfully Completes COVID-19 Variant Study',
                'content' => "The NIMR Tanga Centre has successfully completed a comprehensive study on COVID-19 variants circulating in the coastal region, providing crucial data for national health policy decisions.\n\nThe study, conducted over 18 months, analyzed samples from across the Tanga region and identified several variants of concern, while also tracking their transmission patterns and impact on local communities.\n\nDr. Amina Hassan, the study coordinator, reported that the findings will inform national vaccination strategies and public health interventions. \"Our data shows clear patterns in variant circulation that will help health authorities make informed decisions about prevention measures.\"\n\nKey findings include:\n- Identification of three major variants in the region\n- Seasonal variation patterns in variant prevalence\n- Community transmission dynamics\n- Effectiveness of existing prevention measures\n\nThe study involved collaboration with local hospitals, health centers, and community health workers, demonstrating NIMR's commitment to community-engaged research.\n\nResults have been submitted to international journals and will be shared with health authorities at both regional and national levels to inform policy decisions.",
                'location' => 'tanga',
                'priority' => 'normal',
                'status' => 'published',
                'is_featured' => false,
                'allow_comments' => true,
                'published_at' => Carbon::now()->subDays(7),
                'views_count' => 89,
                'likes_count' => 12
            ],
            [
                'title' => 'Mbeya Station Receives Equipment Upgrade for Enhanced Research Capacity',
                'content' => "NIMR Mbeya Station has received a significant equipment upgrade that will enhance its research capacity and enable more sophisticated analyses for ongoing health studies in the Southern Highlands.\n\nThe new equipment includes advanced laboratory instruments, computing facilities, and field research tools that will support various research programs including nutrition studies, infectious disease research, and environmental health investigations.\n\nStation Manager Dr. Peter Msigwa expressed gratitude for the upgrade: \"This equipment will allow us to conduct more comprehensive studies and provide faster, more accurate results for our research programs.\"\n\nThe upgrade includes:\n- High-precision laboratory analyzers\n- Advanced microscopy equipment\n- Data management systems\n- Field research vehicles and equipment\n- Renewable energy systems for reliable power supply\n\nThis investment reflects NIMR's commitment to ensuring all research stations have the necessary tools to conduct world-class research and contribute meaningfully to improving health outcomes in their regions.\n\nThe station is now better positioned to support regional health initiatives and collaborate with local health facilities.",
                'location' => 'mbeya',
                'priority' => 'normal',
                'status' => 'published',
                'is_featured' => false,
                'allow_comments' => true,
                'published_at' => Carbon::now()->subDays(10),
                'views_count' => 67,
                'likes_count' => 8
            ],
            [
                'title' => 'Tabora Centre Hosts Regional Workshop on Neglected Tropical Diseases',
                'content' => "NIMR Tabora Centre successfully hosted a three-day regional workshop on neglected tropical diseases (NTDs), bringing together researchers, health officials, and community representatives from across the central region.\n\nThe workshop focused on collaborative approaches to NTD research and control, with particular emphasis on diseases prevalent in the central regions of Tanzania including schistosomiasis, lymphatic filariasis, and soil-transmitted helminths.\n\nParticipants included representatives from regional medical offices, district health departments, academic institutions, and community-based organizations.\n\nDr. Grace Mboya, workshop coordinator, noted the importance of such gatherings: \"Bringing together diverse stakeholders allows us to develop comprehensive strategies that address NTDs from multiple angles - research, treatment, prevention, and community engagement.\"\n\nWorkshop outcomes included:\n- Development of a regional NTD research agenda\n- Establishment of collaborative networks\n- Planning of joint surveillance activities\n- Community engagement strategies\n- Resource sharing agreements\n\nThe workshop concluded with commitments for follow-up meetings and the establishment of a regional NTD working group to coordinate ongoing efforts.\n\nNIMR Tabora will serve as the secretariat for this new working group, facilitating communication and collaboration among member institutions.",
                'location' => 'tabora',
                'priority' => 'normal',
                'status' => 'published',
                'is_featured' => false,
                'allow_comments' => true,
                'published_at' => Carbon::now()->subDays(14),
                'views_count' => 45,
                'likes_count' => 6
            ],
            [
                'title' => 'Amani Research Centre Makes Breakthrough in Herbal Medicine Studies',
                'content' => "The Amani Research Centre has achieved a significant breakthrough in traditional herbal medicine research, identifying several plant compounds with potential therapeutic applications for common health conditions.\n\nThe research, conducted in collaboration with traditional healers from the East Usambara Mountains, has led to the isolation and characterization of bioactive compounds from indigenous plants.\n\nDr. Mohamed Kikwete, lead researcher, emphasized the importance of this work: \"Our findings bridge traditional knowledge with modern scientific methods, potentially leading to new treatments while preserving cultural heritage.\"\n\nThe study involved:\n- Ethnobotanical surveys with traditional healers\n- Laboratory analysis of plant extracts\n- Bioactivity testing against various pathogens\n- Documentation of traditional preparation methods\n- Community consultation and consent processes\n\nPreliminary results show promising antimicrobial and anti-inflammatory activities in several plant species, warranting further investigation for potential drug development.\n\nThe centre is now seeking partnerships with pharmaceutical companies for advanced development while ensuring that local communities benefit from any commercialization of their traditional knowledge.\n\nThis research demonstrates NIMR's commitment to integrating traditional knowledge with modern science to develop locally relevant health solutions.",
                'location' => 'amani',
                'priority' => 'normal',
                'status' => 'published',
                'is_featured' => true,
                'allow_comments' => true,
                'published_at' => Carbon::now()->subDays(3),
                'views_count' => 112,
                'likes_count' => 18
            ],
            [
                'title' => 'NIMR Celebrates World Health Day with Community Outreach Programs',
                'content' => "NIMR centres across Tanzania marked World Health Day with comprehensive community outreach programs, emphasizing the theme \"Building a Fairer, Healthier World for Everyone.\"\n\nActivities took place simultaneously at all NIMR locations, including headquarters and research centres in Mwanza, Mbeya, Tanga, Tabora, Dodoma, Amani, and Mpwapwa.\n\nThe day-long programs included:\n- Free health screenings and consultations\n- Health education sessions on preventable diseases\n- Distribution of educational materials\n- Community dialogue sessions on local health priorities\n- Recognition of community health champions\n\nDr. Elizabeth Mwambazi, Head of Community Engagement, highlighted the significance of the day: \"World Health Day provides an opportunity for us to directly engage with the communities we serve and demonstrate our commitment to improving health outcomes for all Tanzanians.\"\n\nAcross all locations, NIMR teams reached over 5,000 community members, providing direct health services and education. The programs also served as platforms for discussing ongoing research activities and how they benefit local communities.\n\nCommunity feedback was overwhelmingly positive, with many participants expressing appreciation for NIMR's continued commitment to community health and engagement.\n\nPlans are already underway for expanded community outreach programs throughout the year, building on the success of World Health Day activities.",
                'location' => 'headquarters',
                'priority' => 'normal',
                'status' => 'published',
                'is_featured' => false,
                'allow_comments' => true,
                'published_at' => Carbon::now()->subDays(18),
                'views_count' => 198,
                'likes_count' => 34
            ],
            [
                'title' => 'New Research Findings on Maternal Health Published in International Journal',
                'content' => "NIMR researchers have published groundbreaking findings on maternal health interventions in a prestigious international journal, contributing valuable evidence to global maternal health discourse.\n\nThe multi-centre study, conducted across several NIMR locations, examined the effectiveness of community-based maternal health interventions in reducing maternal mortality and improving birth outcomes.\n\nLead author Dr. Fatuma Mselle noted the significance of the publication: \"This research provides strong evidence for the effectiveness of community-based approaches to maternal health, which could inform policy decisions both nationally and internationally.\"\n\nKey findings include:\n- 40% reduction in maternal complications through community interventions\n- Improved access to skilled birth attendance\n- Increased knowledge of danger signs during pregnancy\n- Enhanced family planning uptake\n- Strengthened community health systems\n\nThe study involved over 2,000 pregnant women across rural and urban settings, demonstrating the scalability and adaptability of the interventions.\n\nResults have already been shared with the Ministry of Health and are being incorporated into national maternal health guidelines.\n\nThis publication adds to NIMR's growing portfolio of internationally recognized research and demonstrates the institute's contribution to global health knowledge.\n\nThe research team is now planning follow-up studies to explore long-term sustainability and cost-effectiveness of the interventions.",
                'location' => 'headquarters',
                'priority' => 'high',
                'status' => 'published',
                'is_featured' => false,
                'allow_comments' => true,
                'published_at' => Carbon::now()->subDays(12),
                'views_count' => 87,
                'likes_count' => 15
            ]
        ];

        foreach ($newsData as $data) {
            $data['author_id'] = $user->id;
            $data['location_type'] = 'centre';
            News::create($data);
        }

        // Update views and likes counts
        $this->command->info('News seeder completed successfully. Created ' . count($newsData) . ' news articles.');
    }
}
