<?php
require_once __DIR__ . '/db.php';
sr_admin_require_login();

$db = sr_cms_db_required();
sr_cms_migrate($db);

$msg = isset($_GET['msg']) ? (string) $_GET['msg'] : '';
$action = isset($_GET['action']) ? (string) $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$slugParam = isset($_GET['slug']) ? sr_cms_slugify((string) $_GET['slug']) : '';
$tabParam = isset($_GET['tab']) ? (string) $_GET['tab'] : '';
$slideIdParam = isset($_GET['slide_id']) ? (int) $_GET['slide_id'] : 0;
$testIdParam = isset($_GET['test_id']) ? (int) $_GET['test_id'] : 0;
$faqIdParam = isset($_GET['faq_id']) ? (int) $_GET['faq_id'] : 0;

$homeKeys = [
	'home_stat1_title',
	'home_stat1_to',
	'home_stat1_suffix',
	'home_stat2_title',
	'home_stat2_to',
	'home_stat2_suffix',
	'home_stat3_title',
	'home_stat3_to',
	'home_stat3_suffix',
	'home_stat4_title',
	'home_stat4_to',
	'home_stat4_suffix',

	'home_services_subtitle',
	'home_services_title',
	'home_services_cta_label',
	'home_products_subtitle',
	'home_products_title',
	'home_products_cta_label',

	'home_about_subtitle',
	'home_about_title',
	'home_about_p1',
	'home_about_p2',
	'home_about_b1',
	'home_about_b2',
	'home_about_cta_label',
	'home_about_cta_url',
	'home_about_bg_image',
	'home_about_fid_to',
	'home_about_fid_suffix',
	'home_about_fid_title',
	'home_about_timeline1_title',
	'home_about_timeline1_desc',
	'home_about_timeline2_title',
	'home_about_timeline2_desc',
	'home_about_timeline3_title',
	'home_about_timeline3_desc',

	'home_why_subtitle',
	'home_why_title',
	'home_why_card1_title',
	'home_why_card1_desc',
	'home_why_card2_title',
	'home_why_card2_desc',
	'home_why_card3_title',
	'home_why_card3_desc',
	'home_why_card4_title',
	'home_why_card4_desc',
	'home_why_card5_title',
	'home_why_card5_desc',
	'home_why_card6_title',
	'home_why_card6_desc',

	'home_why_sr_title',
	'home_why_sr_1_title',
	'home_why_sr_1_desc',
	'home_why_sr_2_title',
	'home_why_sr_2_desc',
	'home_why_sr_3_title',
	'home_why_sr_3_desc',

	'home_process_subtitle',
	'home_process_title',
	'home_process_1_title',
	'home_process_1_desc',
	'home_process_1_image',
	'home_process_2_title',
	'home_process_2_desc',
	'home_process_2_image',
	'home_process_3_title',
	'home_process_3_desc',
	'home_process_3_image',
	'home_process_4_title',
	'home_process_4_desc',
	'home_process_4_image',

	'home_marquee_1',
	'home_marquee_2',
	'home_marquee_3',
	'home_marquee_4',

	'home_blog_subtitle',
	'home_blog_title',
	'home_blog_cta_label',

	'home_cta_title',
	'home_cta_desc',
	'home_cta_btn1_label',
	'home_cta_btn1_url',
	'home_cta_btn2_label',
	'home_cta_btn2_url',
];

$homeDefaults = [
	'home_stat1_title' => 'Projects<br>Completed',
	'home_stat1_to' => '500',
	'home_stat1_suffix' => '+',
	'home_stat2_title' => 'Solar Capacity<br>Installed',
	'home_stat2_to' => '20',
	'home_stat2_suffix' => ' MW+',
	'home_stat3_title' => 'System<br>Range',
	'home_stat3_to' => '3',
	'home_stat3_suffix' => ' kW – 20 MW',
	'home_stat4_title' => 'After-Sales<br>Support',
	'home_stat4_to' => '100',
	'home_stat4_suffix' => '%',

	'home_services_subtitle' => 'Our Services',
	'home_services_title' => 'Comprehensive solar solutions from concept to completion',
	'home_services_cta_label' => 'View All Services',
	'home_products_subtitle' => 'Products',
	'home_products_title' => 'Solar Solutions for Every Scale',
	'home_products_cta_label' => 'Explore All Products',

	'home_about_subtitle' => 'Who We Are',
	'home_about_title' => 'Shivanjali Renewables Pvt. Ltd.',
	'home_about_p1' => 'Shivanjali Renewables Pvt. Ltd. is a pioneering Solar EPC (Engineering, Procurement & Construction) company headquartered in Nashik, Maharashtra. With deep expertise across the entire solar value chain — from design and procurement to installation and maintenance — we deliver reliable, high-performance solar solutions for every scale.',
	'home_about_p2' => 'Whether you are a homeowner looking to cut your electricity bill, a factory owner seeking energy independence, or a developer wanting to build a solar park, we are your end-to-end partner.',
	'home_about_b1' => 'End-to-end EPC delivery across every scale',
	'home_about_b2' => 'Design, procurement, installation & maintenance',
	'home_about_cta_label' => 'Know More About Us',
	'home_about_cta_url' => 'about',
	'home_about_bg_image' => '',
	'home_about_fid_to' => '2386',
	'home_about_fid_suffix' => '+',
	'home_about_fid_title' => 'Trusted customers around the world',
	'home_about_timeline1_title' => 'Our Vision.',
	'home_about_timeline1_desc' => 'Our mission is to create meaningful connections through the power of music. By fostering creativity, passion, and innovation',
	'home_about_timeline2_title' => 'Our Mission',
	'home_about_timeline2_desc' => 'Our mission is to create meaningful connections through the power of music. By fostering creativity, passion, and innovation',
	'home_about_timeline3_title' => 'Our Achievements',
	'home_about_timeline3_desc' => 'Our mission is to create meaningful connections through the power of music. By fostering creativity, passion, and innovation',

	'home_why_subtitle' => 'Why Choose us',
	'home_why_title' => 'Your partner for sustainable <br> environmental solutions',
	'home_why_card1_title' => 'Commercial Solutions',
	'home_why_card1_desc' => 'Our Climate change mitigation focus on sustainable practices such as rainwater harvesting, wastewater recycling.',
	'home_why_card2_title' => 'Tailored Solutions',
	'home_why_card2_desc' => 'Our Climate change mitigation focus on sustainable practices such as rainwater harvesting, wastewater recycling.',
	'home_why_card3_title' => 'Expert Installation',
	'home_why_card3_desc' => 'Our Climate change mitigation focus on sustainable practices such as rainwater harvesting, wastewater recycling.',
	'home_why_card4_title' => 'Expert Installation',
	'home_why_card4_desc' => 'Our Climate change mitigation focus on sustainable practices such as rainwater harvesting, wastewater recycling.',
	'home_why_card5_title' => 'Low Cost Operation',
	'home_why_card5_desc' => 'Our Climate change mitigation focus on sustainable practices such as rainwater harvesting, wastewater recycling.',
	'home_why_card6_title' => 'Expert Solar Worker',
	'home_why_card6_desc' => 'Our Climate change mitigation focus on sustainable practices such as rainwater harvesting, wastewater recycling.',

	'home_why_sr_title' => 'Why Shivanjali Renewables?',
	'home_why_sr_1_title' => 'Experience',
	'home_why_sr_1_desc' => 'Years of proven expertise in the solar industry',
	'home_why_sr_2_title' => 'Expert Team',
	'home_why_sr_2_desc' => 'Engineers, technicians, and consultants committed to excellence',
	'home_why_sr_3_title' => 'Comprehensive Support',
	'home_why_sr_3_desc' => 'Full warranty, after-sales maintenance, and project design services',

	'home_process_subtitle' => 'Our Process',
	'home_process_title' => 'Wind Solar Energy work<br> Project Planning',
	'home_process_1_title' => 'System Design',
	'home_process_1_desc' => 'Tailoring efficient and sustainable solar energy systems to meet your specific needs.',
	'home_process_1_image' => 'images/homepage-2/ihbox/image-01.jpg',
	'home_process_2_title' => 'Panel Installation',
	'home_process_2_desc' => 'Expert installation of high-quality solar panels for maximum energy capture.',
	'home_process_2_image' => 'images/homepage-2/ihbox/image-02.jpg',
	'home_process_3_title' => 'Inverter Integration',
	'home_process_3_desc' => 'Seamlessly converting solar energy into usable electricity with advanced inverters.',
	'home_process_3_image' => 'images/homepage-2/ihbox/image-03.png',
	'home_process_4_title' => 'Battery Solutions',
	'home_process_4_desc' => 'Panel installation involves the professional installation expert panel maintenance.',
	'home_process_4_image' => 'images/homepage-2/ihbox/image-04.jpg',

	'home_marquee_1' => 'Sustainable',
	'home_marquee_2' => 'Smart solar',
	'home_marquee_3' => 'Turbine Technology',
	'home_marquee_4' => 'Electricity',

	'home_blog_subtitle' => 'Latest News',
	'home_blog_title' => 'Latest from the Blog',
	'home_blog_cta_label' => 'View All Post',

	'home_cta_title' => 'Ready to Switch to Solar?',
	'home_cta_desc' => 'Get a free, no-obligation solar assessment from our experts. We will evaluate your energy needs and design the perfect system for you.',
	'home_cta_btn1_label' => 'Get Free Quote',
	'home_cta_btn1_url' => 'contact',
	'home_cta_btn2_label' => 'Call Us Now',
	'home_cta_btn2_url' => 'tel:+918686313133',
];

$contactKeys = [
	'contact_reach_title',
	'contact_form_title',
	'contact_form_desc',
	'contact_brands_title',
	'contact_map_title',
	'contact_directions_label',
	'contact_map_embed_url',
	'contact_map_aria_label',
];

$contactDefaults = [
	'contact_reach_title' => 'Reach Us Directly',
	'contact_form_title' => 'Get a Free Solar Quote',
	'contact_form_desc' => 'Get in touch with our team for a free consultation, site survey, or project proposal.',
	'contact_brands_title' => 'Top Brands We Trust for Your Solar System Needs',
	'contact_map_title' => 'Map',
	'contact_directions_label' => 'Get Directions',
	'contact_map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3748.7218552306003!2d73.7840677759519!3d20.02018522165262!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bddeb01bf6b4547%3A0xb7f9cea04acc974e!2sABH%20Samruddhi%20Apartment!5e0!3m2!1sen!2sin!4v1774267316950!5m2!1sen!2sin',
	'contact_map_aria_label' => 'Shivanjali Renewables, Nashik',
];

$servicesKeys = [
	'services_intro_title',
	'services_intro_desc',
];

$servicesDefaults = [
	'services_intro_title' => 'End-to-End Solar Services. Zero Compromise.',
	'services_intro_desc' => 'From feasibility study and design to installation, grid connection, and lifetime maintenance — Shivanjali Renewables handles it all.',
];

$projectsKeys = [
	'projects_card1_badge',
	'projects_card1_title',
	'projects_card1_desc',
	'projects_card1_list_title',
	'projects_card1_list1',
	'projects_card1_list2',
	'projects_card1_list3',
	'projects_card2_badge',
	'projects_card2_title',
	'projects_card2_desc',
	'projects_card2_list_title',
	'projects_card2_list1',
	'projects_card2_list2',
	'projects_card2_list3',
	'projects_card3_badge',
	'projects_card3_title',
	'projects_card3_desc',
	'projects_card3_list_title',
	'projects_card3_list1',
	'projects_card3_list2',
	'projects_card3_list3',
	'projects_gallery_title',
	'projects_gallery_desc',
	'projects_filter_all',
	'projects_filter_rooftop',
	'projects_filter_openaccess',
	'projects_filter_parks',
	'projects_cta_title',
	'projects_cta_desc',
	'projects_cta_btn_label',
	'projects_cta_btn_url',
];

$projectsDefaults = [
	'projects_card1_badge' => 'ROOFTOP',
	'projects_card1_title' => 'Rooftop Solar Systems',
	'projects_card1_desc' => 'Custom-designed solar installations for commercial and industrial rooftops. We maximise every square foot of roof space to deliver the highest possible energy output, integrating seamlessly with existing electrical infrastructure.',
	'projects_card1_list_title' => 'Featured Projects (placeholders)',
	'projects_card1_list1' => 'Commercial warehouse — Nashik — 100 kW — Savings: ~₹8 lakh/year',
	'projects_card1_list2' => 'Educational Institution — Nashik — 50 kW — Savings: ~₹4 lakh/year',
	'projects_card1_list3' => 'Hotel — Maharashtra — 80 kW — Savings: ~₹6.5 lakh/year',
	'projects_card2_badge' => 'OPEN ACCESS',
	'projects_card2_title' => 'Open Access Captive Projects',
	'projects_card2_desc' => 'Large-scale solar projects ranging from 1 MW to 20 MW, developed for industrial and institutional clients seeking direct access to cost-efficient, clean energy under the Open Access regulatory framework.',
	'projects_card2_list_title' => 'Featured Projects (placeholders)',
	'projects_card2_list1' => 'Varun Agro Food Processing Pvt. Ltd. — 900 kW — Transformative results in energy savings and sustainability',
	'projects_card2_list2' => '',
	'projects_card2_list3' => '',
	'projects_card3_badge' => 'SOLAR PARKS',
	'projects_card3_title' => 'Solar Farming &amp; Parks',
	'projects_card3_desc' => 'We develop and manage utility-scale solar farms, providing developers and investors with end-to-end EPC services, land facilitation, and grid connectivity. Our solar parks offer a plug-and-play model for large-scale renewable energy generation.',
	'projects_card3_list_title' => 'What we provide in Solar Parks',
	'projects_card3_list1' => 'Land identification and acquisition support',
	'projects_card3_list2' => 'Grid connectivity and evacuation planning',
	'projects_card3_list3' => 'End-to-end EPC execution and commissioning',
	'projects_gallery_title' => 'Featured Project Gallery',
	'projects_gallery_desc' => 'Placeholders shown below — client to provide 6–8 completed projects (name, location, capacity, photo) for the final gallery.',
	'projects_filter_all' => 'All',
	'projects_filter_rooftop' => 'Rooftop',
	'projects_filter_openaccess' => 'Open Access',
	'projects_filter_parks' => 'Solar Parks',
	'projects_cta_title' => 'Have a project in mind?',
	'projects_cta_desc' => 'Tell us about your energy requirements and we will design the perfect solar solution for you. Our team will respond within 24 hours with a preliminary proposal.',
	'projects_cta_btn_label' => 'Start Your Project',
	'projects_cta_btn_url' => 'contact',
];

$blogKeys = [
	'blog_categories_title',
	'blog_cat1_icon',
	'blog_cat1_title',
	'blog_cat1_desc',
	'blog_cat2_icon',
	'blog_cat2_title',
	'blog_cat2_desc',
	'blog_cat3_icon',
	'blog_cat3_title',
	'blog_cat3_desc',
	'blog_cat4_icon',
	'blog_cat4_title',
	'blog_cat4_desc',
	'blog_cat5_icon',
	'blog_cat5_title',
	'blog_cat5_desc',
	'blog_latest_title',
	'blog_latest_desc',
	'blog_faq_title',
	'blog_faq1_q',
	'blog_faq1_a',
	'blog_faq2_q',
	'blog_faq2_a',
	'blog_faq3_q',
	'blog_faq3_a',
	'blog_faq4_q',
	'blog_faq4_a',
	'blog_faq5_q',
	'blog_faq5_a',
	'blog_faq6_q',
	'blog_faq6_a',
	'blog_faq7_q',
	'blog_faq7_a',
	'blog_faq8_q',
	'blog_faq8_a',
	'blog_faq9_q',
	'blog_faq9_a',
	'blog_faq10_q',
	'blog_faq10_a',
	'blog_faq_cta_label',
	'blog_faq_cta_url',
];

$blogDefaults = [
	'blog_categories_title' => 'Browse Categories',
	'blog_cat1_icon' => 'pbmit-base-icon-lightening',
	'blog_cat1_title' => 'Solar Basics',
	'blog_cat1_desc' => 'How solar works, types of systems, net metering explained',
	'blog_cat2_icon' => 'pbmit-base-icon-document',
	'blog_cat2_title' => 'Government Schemes',
	'blog_cat2_desc' => 'PM Surya Ghar Yojana, subsidies, accelerated depreciation',
	'blog_cat3_icon' => 'pbmit-base-icon-news',
	'blog_cat3_title' => 'Industry News',
	'blog_cat3_desc' => 'Renewable energy policy updates, Maharashtra solar news',
	'blog_cat4_icon' => 'pbmit-base-icon-check-mark',
	'blog_cat4_title' => 'Case Studies',
	'blog_cat4_desc' => 'Detailed project stories with energy savings data',
	'blog_cat5_icon' => 'pbmit-base-icon-chat-3',
	'blog_cat5_title' => 'FAQs',
	'blog_cat5_desc' => 'Answers to common questions from residential and commercial buyers',
	'blog_latest_title' => 'Latest Articles',
	'blog_latest_desc' => 'Practical guides, policy updates, and solar insights to help you make confident decisions.',
	'blog_faq_title' => 'Frequently Asked Questions',
	'blog_faq1_q' => 'What is a Solar EPC company?',
	'blog_faq1_a' => 'An EPC (Engineering, Procurement &amp; Construction) company manages the complete solar project lifecycle from design and material procurement to installation and commissioning.',
	'blog_faq2_q' => 'How much does a residential solar system cost in Maharashtra?',
	'blog_faq2_a' => 'A 5 kW system typically costs between ₹2.5–3.5 lakh before subsidy. After PM Surya Ghar subsidy, the net cost can drop significantly.',
	'blog_faq3_q' => 'What is the payback period for solar in India?',
	'blog_faq3_a' => 'Most residential systems pay back in 4–6 years. Commercial and industrial systems often pay back in 3–5 years.',
	'blog_faq4_q' => 'Does Shivanjali Renewables offer maintenance after installation?',
	'blog_faq4_a' => 'Yes. We offer comprehensive O&amp;M services including remote monitoring, cleaning, and on-site repairs.',
	'blog_faq5_q' => 'What is Open Access solar?',
	'blog_faq5_a' => 'Open Access allows large consumers to purchase solar power directly from a generator, bypassing the distribution grid tariff. It is typically available for consumers above 100 kW demand.',
	'blog_faq6_q' => 'Which panels and inverters do you use?',
	'blog_faq6_a' => 'We use only Tier-1 certified solar panels and inverters from reputed brands that meet MNRE and BIS standards.',
	'blog_faq7_q' => 'Can solar be installed on any type of roof?',
	'blog_faq7_a' => 'Yes. We install on RCC, metal sheet, and tile roofs. Our structural team assesses load-bearing capacity before installation.',
	'blog_faq8_q' => 'How long does installation take?',
	'blog_faq8_a' => 'Residential systems are installed in 1–3 days. Commercial and industrial projects may take 2–8 weeks depending on scale.',
	'blog_faq9_q' => 'Is there a warranty on solar systems?',
	'blog_faq9_a' => 'Yes. Panels carry a 25-year performance warranty. Inverters and other equipment have manufacturer warranties ranging from 5–10 years.',
	'blog_faq10_q' => 'How do I get started?',
	'blog_faq10_a' => 'Simply fill out our contact form or call us. Our team will schedule a free site survey within 48 hours.',
	'blog_faq_cta_label' => 'Request Free Consultation',
	'blog_faq_cta_url' => 'contact',
];

$aboutKeys = [
	'about_story_subtitle',
	'about_story_title',
	'about_story_p1',
	'about_story_p2',
	'about_story_p3',
	'about_story_img1',
	'about_story_img2',

	'about_vm_title',
	'about_vision_desc',
	'about_mission_desc',

	'about_values_subtitle',
	'about_values_title',
	'about_value1_title',
	'about_value1_desc',
	'about_value2_title',
	'about_value2_desc',
	'about_value3_title',
	'about_value3_desc',

	'about_leadership_subtitle',
	'about_leadership_title',
	'about_leader1_name',
	'about_leader1_role',
	'about_leader1_photo',
	'about_leader2_name',
	'about_leader2_role',
	'about_leader2_photo',
	'about_founder_subtitle',
	'about_founder_quote',

	'about_history_subtitle',
	'about_history_title',
	'about_history1_year',
	'about_history1_title',
	'about_history1_desc',
	'about_history1_image',
	'about_history2_year',
	'about_history2_title',
	'about_history2_desc',
	'about_history2_image',
	'about_history3_year',
	'about_history3_title',
	'about_history3_desc',
	'about_history3_image',
	'about_history4_year',
	'about_history4_title',
	'about_history4_desc',
	'about_history4_image',
	'about_history5_year',
	'about_history5_title',
	'about_history5_desc',
	'about_history5_image',
	'about_history6_year',
	'about_history6_title',
	'about_history6_desc',
	'about_history6_image',
];

$aboutDefaults = [
	'about_story_subtitle' => 'About Us',
	'about_story_title' => 'Our Story',
	'about_story_p1' => "Shivanjali Renewables Pvt. Ltd. was founded with a singular vision: to accelerate India's transition to clean, renewable energy. Starting from Nashik — the heart of Maharashtra — we have grown into a full-service Solar EPC company trusted by homeowners, industries, and businesses across the region.",
	'about_story_p2' => 'Every project we undertake is powered by a commitment to quality, a passion for sustainability, and a drive to deliver real value to our customers. From a small rooftop installation in a residential colony to a 20 MW open-access solar park, we bring the same level of dedication and technical excellence to every assignment.',
	'about_story_p3' => 'Our name, Shivanjali, is a tribute to our roots — a blend of strength and dedication that defines our work ethic every single day.',
	'about_story_img1' => 'images/banner-slider-img/Slider01-2.jpg',
	'about_story_img2' => 'images/banner-slider-img/Slider02-3.jpg',

	'about_vm_title' => 'OUR VISION & MISSION',
	'about_vision_desc' => 'To be a global leader in the solar energy sector, pioneering innovation and fostering the widespread adoption of sustainable renewable energy solutions.',
	'about_mission_desc' => 'To deliver affordable, efficient, and high-quality solar solutions, empowering individuals and organisations to transition to renewable energy while actively contributing to environmental sustainability.',

	'about_values_subtitle' => 'Core Values',
	'about_values_title' => 'What We Stand For',
	'about_value1_title' => 'Innovation',
	'about_value1_desc' => 'We pioneer the latest advancements in solar technology to deliver unmatched performance and future-ready energy systems.',
	'about_value2_title' => 'Sustainability',
	'about_value2_desc' => 'We are committed to promoting renewable energy as the foundation of a cleaner, healthier planet for generations to come.',
	'about_value3_title' => 'Energy Efficiency',
	'about_value3_desc' => 'Every system we design maximises energy yield while minimising waste, helping clients get the most from every ray of sunshine.',

	'about_leadership_subtitle' => 'Leadership Team',
	'about_leadership_title' => 'Meet Our Leadership',
	'about_leader1_name' => 'Anjali Shivaji Chavanke',
	'about_leader1_role' => 'Managing Director (MD)',
	'about_leader1_photo' => 'images/homepage-1/team/team-img-01.jpg',
	'about_leader2_name' => 'Abhijeet Shivaji Chavanke',
	'about_leader2_role' => 'Chief Executive Officer (CEO)',
	'about_leader2_photo' => 'images/homepage-1/team/team-img-02.jpg',
	'about_founder_subtitle' => 'Founder / CEO Message',
	'about_founder_quote' => '"At Shivanjali Renewables, our goal is to lead the transition toward clean energy. We are dedicated to delivering cutting-edge solar solutions that empower communities and businesses to embrace sustainability. Together, we can build a brighter and greener future."',

	'about_history_subtitle' => 'Achievements / Milestones',
	'about_history_title' => 'Milestones That Define Us',
	'about_history1_year' => '01',
	'about_history1_title' => 'Founded',
	'about_history1_desc' => 'Shivanjali Renewables established in Nashik, Maharashtra.',
	'about_history1_image' => 'images/history/history-img-01.jpg',
	'about_history2_year' => '02',
	'about_history2_title' => 'First commercial project',
	'about_history2_desc' => '50 kW rooftop installation for an industrial client.',
	'about_history2_image' => 'images/history/history-img-02.jpg',
	'about_history3_year' => '03',
	'about_history3_title' => 'Crossed 1 MW capacity',
	'about_history3_desc' => 'Crossed 1 MW cumulative installed capacity.',
	'about_history3_image' => 'images/history/history-img-03.jpg',
	'about_history4_year' => '04',
	'about_history4_title' => 'Open Access Solar',
	'about_history4_desc' => 'Launched Open Access Solar division for large-scale industrial clients.',
	'about_history4_image' => 'images/history/history-img-04.jpg',
	'about_history5_year' => '05',
	'about_history5_title' => '900 kW project completed',
	'about_history5_desc' => 'Completed 900 kW project for Varun Agro Food Processing Pvt. Ltd.',
	'about_history5_image' => 'images/history/history-img-05.jpg',
	'about_history6_year' => '06',
	'about_history6_title' => '20 MW+ installed',
	'about_history6_desc' => 'Crossed 20 MW+ total installed solar capacity.',
	'about_history6_image' => 'images/history/history-img-06.jpg',
];

$whyKeys = [
	'why_diff_title',
	'why_diff_card1_title',
	'why_diff_card1_desc',
	'why_diff_card2_title',
	'why_diff_card2_desc',
	'why_diff_card3_title',
	'why_diff_card3_desc',
	'why_diff_card4_title',
	'why_diff_card4_desc',
	'why_diff_card5_title',
	'why_diff_card5_desc',
	'why_diff_card6_title',
	'why_diff_card6_desc',

	'why_tech_title',
	'why_tech_card1_title',
	'why_tech_card1_desc',
	'why_tech_card2_title',
	'why_tech_card2_desc',
	'why_tech_card3_title',
	'why_tech_card3_desc',

	'why_testimonials_title',

	'why_impact_title',
	'why_impact1_label',
	'why_impact1_to',
	'why_impact1_unit',
	'why_impact1_desc',
	'why_impact2_label',
	'why_impact2_to',
	'why_impact2_unit',
	'why_impact2_desc',
	'why_impact3_label',
	'why_impact3_to',
	'why_impact3_unit',
	'why_impact3_desc',
];

$whyDefaults = [
	'why_diff_title' => 'Why Clients Choose Us',
	'why_diff_card1_title' => 'Proven Experience',
	'why_diff_card1_desc' => 'Years of hands-on expertise in solar EPC across Maharashtra with a growing portfolio of successful projects.',
	'why_diff_card2_title' => 'Expert Team',
	'why_diff_card2_desc' => 'A multidisciplinary team of certified engineers, experienced technicians, and knowledgeable consultants committed to project excellence.',
	'why_diff_card3_title' => 'End-to-End Service',
	'why_diff_card3_desc' => 'We manage every step from design and procurement to installation, grid connectivity, and lifetime maintenance.',
	'why_diff_card4_title' => 'Certified Quality',
	'why_diff_card4_desc' => 'All our products meet stringent national and international quality and safety standards, with Tier-1 certified components only.',
	'why_diff_card5_title' => 'Dedicated Project Design Team',
	'why_diff_card5_desc' => 'Specialised in solar project design, planning, and architectural integration for optimal performance and aesthetics.',
	'why_diff_card6_title' => 'Comprehensive Warranty & Support',
	'why_diff_card6_desc' => 'Full after-sales support including preventive maintenance, performance monitoring, and warranty services.',

	'why_tech_title' => 'Backed by Technology',
	'why_tech_card1_title' => 'Innovative Solutions',
	'why_tech_card1_desc' => 'We leverage the latest advancements in solar technology, including bifacial panels, smart inverters, and energy management systems, to deliver unmatched performance.',
	'why_tech_card2_title' => 'R&D Focus',
	'why_tech_card2_desc' => 'Continuous investment in research and development to improve system efficiency, reliability, and integration with emerging energy storage technologies.',
	'why_tech_card3_title' => 'Certified Products',
	'why_tech_card3_desc' => 'All equipment and installations meet stringent industry certifications for quality, safety, and long-term performance.',

	'why_testimonials_title' => 'Client Success Stories',

	'why_impact_title' => 'Our Environmental Impact',
	'why_impact1_label' => 'Solar Capacity Installed',
	'why_impact1_to' => '20',
	'why_impact1_unit' => 'MW+',
	'why_impact1_desc' => 'Harnessing solar energy to reduce dependency on fossil fuels and lower the carbon footprint of our clients across Maharashtra.',
	'why_impact2_label' => 'Projects Completed',
	'why_impact2_to' => '500',
	'why_impact2_unit' => '+',
	'why_impact2_desc' => 'Our installed systems collectively save crores of rupees in electricity bills for residential, commercial, and industrial customers every year.',
	'why_impact3_label' => 'Trusted Customers',
	'why_impact3_to' => '2386',
	'why_impact3_unit' => '+',
	'why_impact3_desc' => 'By enabling widespread solar adoption, we play a vital role in India&#8217;s national clean energy transition and Net Zero goals.',
];

$privacyKeys = [
	'pp_updated_text',
	'pp_toc_title',
	'pp_cta_label',
	'pp_cta_url',
	'pp_scope_h',
	'pp_scope_html',
	'pp_info_h',
	'pp_info_html',
	'pp_use_h',
	'pp_use_html',
	'pp_cookies_h',
	'pp_cookies_html',
	'pp_sharing_h',
	'pp_sharing_html',
	'pp_retention_h',
	'pp_retention_html',
	'pp_security_h',
	'pp_security_html',
	'pp_rights_h',
	'pp_rights_html',
	'pp_links_h',
	'pp_links_html',
	'pp_changes_h',
	'pp_changes_html',
	'pp_contact_h',
	'pp_contact_html',
	'pp_highlight1_html',
	'pp_highlight2_html',
	'pp_highlight3_html',
	'pp_highlight4_html',
];

$privacyDefaults = [
	'pp_updated_text' => 'Last updated: 03 April 2026',
	'pp_toc_title' => 'On this page',
	'pp_cta_label' => 'Request a Consultation',
	'pp_cta_url' => 'contact',
	'pp_scope_h' => 'Scope',
	'pp_scope_html' => '<p class="mb-0">This policy applies to information collected through our website and related communications (including phone, email, WhatsApp, and enquiry forms). It does not cover information collected offline outside of our business interactions.</p>',
	'pp_info_h' => 'Information We Collect',
	'pp_info_html' => '<p class="mb-2">We may collect the following categories of information:</p><ul class="sr-legal-list mb-0"><li><strong>Contact details</strong> such as name, phone number, email address, and city/location.</li><li><strong>Project details</strong> such as customer type (residential/commercial/industrial), rooftop/land information, power requirements, and any notes you provide.</li><li><strong>Communication records</strong> when you contact us by phone, email, or messaging apps.</li><li><strong>Technical data</strong> such as device/browser type, IP address, and pages visited (collected via cookies or similar technologies where applicable).</li></ul>',
	'pp_use_h' => 'How We Use Information',
	'pp_use_html' => '<ul class="sr-legal-list mb-0"><li>Respond to enquiries and provide quotations, proposals, and consultation scheduling.</li><li>Perform site visit planning and feasibility evaluation where required.</li><li>Improve website performance, user experience, and service quality.</li><li>Send service-related updates (for example, follow-up calls or emails about your request).</li><li>Comply with legal obligations and prevent misuse or fraud.</li></ul>',
	'pp_cookies_h' => 'Cookies &amp; Analytics',
	'pp_cookies_html' => '<p class="mb-2">Cookies are small files stored on your device to help websites function and remember preferences.</p><ul class="sr-legal-list mb-0"><li><strong>Essential cookies</strong> may be used to enable core website functionality.</li><li><strong>Analytics</strong> may be used to understand traffic and improve performance. Analytics scripts (if enabled) are loaded based on your cookie consent preference.</li><li>You can change your preference anytime by clearing your browser storage for this site and revisiting the website.</li></ul>',
	'pp_sharing_h' => 'Sharing &amp; Disclosure',
	'pp_sharing_html' => '<p class="mb-2">We do not sell your personal information. We may share information only in the following cases:</p><ul class="sr-legal-list mb-0"><li><strong>Service providers</strong> who support website operations (for example, hosting) and are bound by confidentiality obligations.</li><li><strong>Business operations</strong> such as arranging site surveys, installations, or maintenance with our internal team and authorized partners.</li><li><strong>Legal reasons</strong> where disclosure is required by law, regulation, or to protect rights and safety.</li></ul>',
	'pp_retention_h' => 'Data Retention',
	'pp_retention_html' => '<p class="mb-0">We retain information only as long as necessary for the purposes described in this policy (for example, to respond to your enquiry, maintain business records, or comply with applicable legal requirements). Retention periods may vary depending on the nature of the interaction.</p>',
	'pp_security_h' => 'Security',
	'pp_security_html' => '<p class="mb-0">We implement reasonable administrative and technical measures to protect information. However, no method of transmission or storage is fully secure, and we cannot guarantee absolute security.</p>',
	'pp_rights_h' => 'Your Rights',
	'pp_rights_html' => '<p class="mb-2">Depending on applicable law, you may have rights to access, correct, or delete your personal information.</p><ul class="sr-legal-list mb-0"><li>To request an update or deletion, email us with sufficient details to identify your enquiry.</li><li>We may need to retain certain records for legal compliance or legitimate business purposes.</li></ul>',
	'pp_links_h' => 'Third-Party Links',
	'pp_links_html' => '<p class="mb-0">Our website may contain links to third-party websites. We are not responsible for their privacy practices. Please review the privacy policies of any third-party sites you visit.</p>',
	'pp_changes_h' => 'Changes to This Policy',
	'pp_changes_html' => '<p class="mb-0">We may update this Privacy Policy from time to time. The “Last updated” date at the top indicates when changes were last made.</p>',
	'pp_contact_h' => 'Contact',
	'pp_contact_html' => '<p class="mb-2">For questions or requests about this Privacy Policy, contact:</p><ul class="sr-legal-contact-list mb-0"><li><strong>Shivanjali Renewables</strong></li><li>Office No. 505, ABH Samruddhi, Near Dream Castle Signal, Makhamalabad Road, Nashik – 422003, Maharashtra, India</li><li><a href="mailto:info@shivanjalirenewables.com">info@shivanjalirenewables.com</a></li><li><a href="tel:+918686313133">+91 8686 313 133</a></li></ul>',
	'pp_highlight1_html' => '<strong>We collect</strong> contact details and project requirements you share with us.',
	'pp_highlight2_html' => '<strong>We use it</strong> to respond, provide proposals, and improve our services.',
	'pp_highlight3_html' => '<strong>We don’t sell</strong> your personal information to third parties.',
	'pp_highlight4_html' => '<strong>You control</strong> cookies/analytics preferences via the consent banner.',
];

$termsKeys = [
	'tou_updated_text',
	'tou_toc_title',
	'tou_cta_label',
	'tou_cta_url',
	'tou_acceptance_h',
	'tou_acceptance_html',
	'tou_eligibility_h',
	'tou_eligibility_html',
	'tou_services_h',
	'tou_services_html',
	'tou_quotes_h',
	'tou_quotes_html',
	'tou_ip_h',
	'tou_ip_html',
	'tou_acceptable_h',
	'tou_acceptable_html',
	'tou_links_h',
	'tou_links_html',
	'tou_disclaimer_h',
	'tou_disclaimer_html',
	'tou_liability_h',
	'tou_liability_html',
	'tou_indemnity_h',
	'tou_indemnity_html',
	'tou_law_h',
	'tou_law_html',
	'tou_changes_h',
	'tou_changes_html',
	'tou_contact_h',
	'tou_contact_html',
	'tou_highlight1_html',
	'tou_highlight2_html',
	'tou_highlight3_html',
	'tou_highlight4_html',
];

$termsDefaults = [
	'tou_updated_text' => 'Last updated: 03 April 2026',
	'tou_toc_title' => 'On this page',
	'tou_cta_label' => 'Read Privacy Policy',
	'tou_cta_url' => 'privacy-policy',
	'tou_acceptance_h' => 'Acceptance',
	'tou_acceptance_html' => '<p class="mb-0">By accessing or using this website, you agree to be bound by these Terms of Use and our <a href="privacy-policy">Privacy Policy</a>. If you do not agree, please do not use the website.</p>',
	'tou_eligibility_h' => 'Eligibility',
	'tou_eligibility_html' => '<p class="mb-0">You must be able to form a legally binding contract under applicable law to use this website and submit enquiries.</p>',
	'tou_services_h' => 'Services &amp; Enquiries',
	'tou_services_html' => '<ul class="sr-legal-list mb-0"><li>The website provides information about our solar and renewable energy solutions and a way to request a consultation or quote.</li><li>You agree to provide accurate and complete information when submitting forms or contacting us.</li><li>We may contact you by phone, email, or messaging apps to respond to your enquiry.</li></ul>',
	'tou_quotes_h' => 'Quotes &amp; Proposals',
	'tou_quotes_html' => '<ul class="sr-legal-list mb-0"><li>Any estimates, savings calculations, or timelines shown on the website are indicative and may change based on site conditions, scope, permits, approvals, and equipment availability.</li><li>A final quotation or proposal may require a site visit, technical survey, and confirmation of requirements.</li><li>Unless explicitly stated in writing, a website enquiry does not create a binding contract.</li></ul>',
	'tou_ip_h' => 'Intellectual Property',
	'tou_ip_html' => '<p class="mb-2">All content on this website (including text, graphics, logos, images, and layout) is owned by Shivanjali Renewables or licensed to us and is protected by applicable intellectual property laws.</p><ul class="sr-legal-list mb-0"><li>You may view and print pages for personal, non-commercial use.</li><li>You may not copy, reproduce, distribute, or create derivative works without prior written permission.</li></ul>',
	'tou_acceptable_h' => 'Acceptable Use',
	'tou_acceptable_html' => '<ul class="sr-legal-list mb-0"><li>Do not attempt to disrupt or compromise the website’s security or availability.</li><li>Do not submit false, misleading, or unlawful information through forms.</li><li>Do not use the website to transmit malware, spam, or unauthorized promotional content.</li><li>Do not scrape, harvest, or collect data from the website without permission.</li></ul>',
	'tou_links_h' => 'Third-Party Links',
	'tou_links_html' => '<p class="mb-0">The website may include links to third-party services (such as maps or social platforms). We do not control these sites and are not responsible for their content, policies, or practices.</p>',
	'tou_disclaimer_h' => 'Disclaimers',
	'tou_disclaimer_html' => '<ul class="sr-legal-list mb-0"><li>The website and its content are provided on an “as is” and “as available” basis without warranties of any kind, to the maximum extent permitted by law.</li><li>We do not warrant that the website will be uninterrupted, error-free, or free from harmful components.</li><li>Information on the website is for general guidance and does not constitute professional, legal, or financial advice.</li></ul>',
	'tou_liability_h' => 'Limitation of Liability',
	'tou_liability_html' => '<p class="mb-0">To the maximum extent permitted by law, Shivanjali Renewables shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of the website.</p>',
	'tou_indemnity_h' => 'Indemnity',
	'tou_indemnity_html' => '<p class="mb-0">You agree to indemnify and hold harmless Shivanjali Renewables from any claims, losses, liabilities, and expenses arising from your use of the website or violation of these Terms of Use.</p>',
	'tou_law_h' => 'Governing Law',
	'tou_law_html' => '<p class="mb-0">These Terms of Use are governed by the laws of India. Courts in Nashik, Maharashtra shall have jurisdiction, subject to applicable law.</p>',
	'tou_changes_h' => 'Changes to These Terms',
	'tou_changes_html' => '<p class="mb-0">We may update these Terms of Use from time to time. Continued use of the website after updates means you accept the revised terms.</p>',
	'tou_contact_h' => 'Contact',
	'tou_contact_html' => '<p class="mb-2">If you have questions about these Terms of Use, contact:</p><ul class="sr-legal-contact-list mb-0"><li><strong>Shivanjali Renewables</strong></li><li>Office No. 505, ABH Samruddhi, Near Dream Castle Signal, Makhamalabad Road, Nashik – 422003, Maharashtra, India</li><li><a href="mailto:info@shivanjalirenewables.com">info@shivanjalirenewables.com</a></li><li><a href="tel:+918686313133">+91 8686 313 133</a></li></ul>',
	'tou_highlight1_html' => '<strong>Use responsibly:</strong> don’t misuse the website, content, or forms.',
	'tou_highlight2_html' => '<strong>Quotes vary:</strong> pricing and timelines depend on site conditions and scope.',
	'tou_highlight3_html' => '<strong>Content protection:</strong> branding and materials are protected by IP laws.',
	'tou_highlight4_html' => '<strong>Need help:</strong> reach us via phone/email for clarifications.',
];

$pageBannerDefaults = [
	'about' => [
		'title' => 'About Us',
		'hero_title' => 'Illuminating the Path to a Sustainable Future',
		'hero_subtitle' => 'Born in Nashik. Built for India. Driven by a clean-energy mission.',
	],
	'services' => [
		'title' => 'Services',
		'hero_title' => 'Services',
		'hero_subtitle' => '',
	],
	'products' => [
		'title' => 'Products',
		'hero_title' => 'Solar Systems for Every Scale — 3 kW to 20 MW',
		'hero_subtitle' => 'Whether you are a homeowner, a factory owner, or a large-scale developer, we have the right solar solution to match your energy needs and budget.',
	],
	'projects' => [
		'title' => 'Projects',
		'hero_title' => 'Projects That Prove Our Promise',
		'hero_subtitle' => 'From rooftop systems in Nashik to megawatt-scale solar farms, every project reflects our commitment to quality, efficiency, and clean energy.',
	],
	'why-us' => [
		'title' => 'Why Us',
		'hero_title' => 'The Shivanjali Difference',
		'hero_subtitle' => 'We don&#8217;t just install solar panels. We build long-term energy partnerships that deliver measurable results.',
	],
	'blog' => [
		'title' => 'Blog',
		'hero_title' => 'Solar Knowledge Hub',
		'hero_subtitle' => 'Stay informed with the latest news, guides, and insights from India&#8217;s solar industry.',
	],
	'contact' => [
		'title' => 'Contact Us',
		'hero_title' => 'Let&#8217;s Build Your Solar Future Together',
		'hero_subtitle' => 'Get in touch with our team for a free consultation, site survey, or project proposal. We respond within 24 hours.',
	],
	'privacy-policy' => [
		'title' => 'Privacy Policy',
		'hero_title' => 'Privacy Policy',
		'hero_subtitle' => 'This Privacy Policy explains how Shivanjali Renewables (“Shivanjali Renewables”, “we”, “us”, “our”) collects, uses, shares, and protects information when you visit our website, request a quote, or contact our team.',
	],
	'terms-of-use' => [
		'title' => 'Terms of Use',
		'hero_title' => 'Terms of Use',
		'hero_subtitle' => 'These Terms of Use govern access to and use of the Shivanjali Renewables website. By using this website, you agree to these terms.',
	],
];

function sr_admin_upload_image_to(string $absDir, string $relDir, string $prefix, array $file, int $maxBytes): array
{
	$err = isset($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
	if ($err === UPLOAD_ERR_NO_FILE) {
		return ['ok' => false, 'path' => '', 'error' => ''];
	}
	if ($err !== UPLOAD_ERR_OK) {
		return ['ok' => false, 'path' => '', 'error' => 'Upload failed.'];
	}
	$tmp = (string) ($file['tmp_name'] ?? '');
	$size = (int) ($file['size'] ?? 0);
	if ($size <= 0 || $size > $maxBytes) {
		return ['ok' => false, 'path' => '', 'error' => 'Image must be under ' . number_format($maxBytes / 1024 / 1024, 1) . 'MB.'];
	}
	$info = @getimagesize($tmp);
	$mime = is_array($info) ? (string) ($info['mime'] ?? '') : '';
	$ext = '';
	if ($mime === 'image/jpeg') {
		$ext = 'jpg';
	} elseif ($mime === 'image/png') {
		$ext = 'png';
	} elseif ($mime === 'image/webp') {
		$ext = 'webp';
	}
	if ($ext === '') {
		return ['ok' => false, 'path' => '', 'error' => 'Only JPG, PNG, or WEBP images are allowed.'];
	}
	if (!is_dir($absDir)) {
		@mkdir($absDir, 0775, true);
	}
	$filename = $prefix . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
	$dest = rtrim($absDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
	if (!@move_uploaded_file($tmp, $dest)) {
		return ['ok' => false, 'path' => '', 'error' => 'Unable to save uploaded image.'];
	}
	$rel = rtrim($relDir, '/') . '/' . $filename;
	return ['ok' => true, 'path' => $rel, 'error' => ''];
}

if ($slugParam !== '' && $action === 'list' && $id === 0) {
	$stmt = $db->prepare('SELECT id FROM cms_pages WHERE slug=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('s', $slugParam);
		$stmt->execute();
		$stmt->bind_result($foundId);
		if ($stmt->fetch()) {
			$stmt->close();
			header('Location: pages.php?action=edit&id=' . (int) $foundId);
			exit;
		}
		$stmt->close();
	}
	$seed = isset($pageBannerDefaults[$slugParam]) && is_array($pageBannerDefaults[$slugParam]) ? $pageBannerDefaults[$slugParam] : ['title' => '', 'hero_title' => '', 'hero_subtitle' => ''];
	$seedTitle = (string) ($seed['title'] ?? '');
	$seedHero = (string) ($seed['hero_title'] ?? '');
	$seedSub = (string) ($seed['hero_subtitle'] ?? '');
	$seedBanner = '';
	$stmt = $db->prepare('INSERT INTO cms_pages (slug, title, hero_title, hero_subtitle, banner_image, content) VALUES (?, ?, ?, ?, ?, "")');
	if ($stmt) {
		$stmt->bind_param('sssss', $slugParam, $seedTitle, $seedHero, $seedSub, $seedBanner);
		$stmt->execute();
		$newId = (int) $stmt->insert_id;
		$stmt->close();
		if ($newId > 0) {
			header('Location: pages.php?action=edit&id=' . $newId);
			exit;
		}
	}
	header('Location: pages.php?msg=' . rawurlencode('Unable to open this page right now.'));
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$csrf = isset($_POST['csrf']) ? (string) $_POST['csrf'] : null;
	if (!sr_admin_verify_csrf($csrf)) {
		header('Location: pages.php?msg=' . rawurlencode('Invalid session. Please try again.'));
		exit;
	}

	$op = isset($_POST['op']) ? (string) $_POST['op'] : '';
	if ($op === 'home_slide_save' || $op === 'home_slide_delete') {
		$pageId = isset($_POST['page_id']) ? (int) $_POST['page_id'] : 0;
		$back = $pageId > 0 ? ('pages.php?action=edit&id=' . $pageId . '&tab=slider') : 'pages.php?slug=home&tab=slider';

		if ($op === 'home_slide_delete') {
			$delSlideId = isset($_POST['slide_id']) ? (int) $_POST['slide_id'] : 0;
			if ($delSlideId > 0) {
				$stmt = $db->prepare('DELETE FROM cms_banners WHERE id=?');
				if ($stmt) {
					$stmt->bind_param('i', $delSlideId);
					$stmt->execute();
					$stmt->close();
				}
			}
			header('Location: ' . $back . '&msg=' . rawurlencode('Slide deleted.'));
			exit;
		}

		$slideId = isset($_POST['slide_id']) ? (int) $_POST['slide_id'] : 0;
		$kicker = trim((string) ($_POST['slide_kicker'] ?? ''));
		$title = trim((string) ($_POST['slide_title'] ?? ''));
		$subtitle = trim((string) ($_POST['slide_subtitle'] ?? ''));
		$primaryLabel = trim((string) ($_POST['slide_primary_label'] ?? ''));
		$primaryUrl = trim((string) ($_POST['slide_primary_url'] ?? ''));
		$secondaryLabel = trim((string) ($_POST['slide_secondary_label'] ?? ''));
		$secondaryUrl = trim((string) ($_POST['slide_secondary_url'] ?? ''));
		$sortOrder = isset($_POST['slide_sort_order']) ? (int) $_POST['slide_sort_order'] : 0;
		$isActive = isset($_POST['slide_is_active']) ? 1 : 0;
		$image = trim((string) ($_POST['slide_image_existing'] ?? ''));

		if ($title === '') {
			header('Location: ' . $back . '&msg=' . rawurlencode('Slide title is required.'));
			exit;
		}

		if (isset($_FILES['slide_image']) && is_array($_FILES['slide_image'])) {
			$up = sr_admin_upload_image_to(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'banner-slider-img', 'images/banner-slider-img', 'home-slide', $_FILES['slide_image'], 5_000_000);
			if ($up['error'] !== '') {
				header('Location: ' . $back . '&msg=' . rawurlencode($up['error']));
				exit;
			}
			if ($up['ok']) {
				$image = (string) $up['path'];
			}
		}

		if ($image === '') {
			header('Location: ' . $back . '&msg=' . rawurlencode('Please upload a slide image.'));
			exit;
		}

		if ($slideId > 0) {
			$stmt = $db->prepare('UPDATE cms_banners SET image=?, kicker=?, title=?, subtitle=?, primary_label=?, primary_url=?, secondary_label=?, secondary_url=?, is_active=?, sort_order=? WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('ssssssssiii', $image, $kicker, $title, $subtitle, $primaryLabel, $primaryUrl, $secondaryLabel, $secondaryUrl, $isActive, $sortOrder, $slideId);
				$stmt->execute();
				$stmt->close();
			}
			header('Location: ' . $back . '&msg=' . rawurlencode('Slide updated.'));
			exit;
		}

		$stmt = $db->prepare('INSERT INTO cms_banners (image, kicker, title, subtitle, primary_label, primary_url, secondary_label, secondary_url, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
		if ($stmt) {
			$stmt->bind_param('ssssssssii', $image, $kicker, $title, $subtitle, $primaryLabel, $primaryUrl, $secondaryLabel, $secondaryUrl, $isActive, $sortOrder);
			$stmt->execute();
			$stmt->close();
		}
		header('Location: ' . $back . '&msg=' . rawurlencode('Slide added.'));
		exit;
	}
	if ($op === 'home_testimonial_save' || $op === 'home_testimonial_delete') {
		$pageId = isset($_POST['page_id']) ? (int) $_POST['page_id'] : 0;
		$back = $pageId > 0 ? ('pages.php?action=edit&id=' . $pageId . '&tab=testimonials') : 'pages.php?slug=home&tab=testimonials';

		if ($op === 'home_testimonial_delete') {
			$delId = isset($_POST['test_id']) ? (int) $_POST['test_id'] : 0;
			if ($delId > 0) {
				$oldImage = '';
				$stmtOld = $db->prepare('SELECT image FROM cms_testimonials WHERE id=? LIMIT 1');
				if ($stmtOld) {
					$stmtOld->bind_param('i', $delId);
					$stmtOld->execute();
					$stmtOld->bind_result($oldImage);
					$stmtOld->fetch();
					$stmtOld->close();
				}

				$stmt = $db->prepare('DELETE FROM cms_testimonials WHERE id=?');
				if ($stmt) {
					$stmt->bind_param('i', $delId);
					$stmt->execute();
					$stmt->close();
				}

				if (is_string($oldImage) && preg_match('/^images\\/testimonials\\/testimonial-[a-z0-9-]+\\.(png|jpe?g|webp)$/i', $oldImage) === 1) {
					$abs = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $oldImage);
					if (is_file($abs)) {
						@unlink($abs);
					}
				}
			}
			header('Location: ' . $back . '&msg=' . rawurlencode('Testimonial deleted.'));
			exit;
		}

		$testId = isset($_POST['test_id']) ? (int) $_POST['test_id'] : 0;
		$sectionTitle = trim((string) ($_POST['home_testimonial_title'] ?? ''));
		$name = trim((string) ($_POST['test_name'] ?? ''));
		$company = trim((string) ($_POST['test_company'] ?? ''));
		$quote = trim((string) ($_POST['test_quote'] ?? ''));
		$rating = isset($_POST['test_rating']) ? (int) $_POST['test_rating'] : 5;
		if ($rating < 1)
			$rating = 1;
		if ($rating > 5)
			$rating = 5;
		$sortOrder = isset($_POST['test_sort_order']) ? (int) $_POST['test_sort_order'] : 0;
		$isActive = isset($_POST['test_is_active']) ? 1 : 0;
		$image = trim((string) ($_POST['test_image_existing'] ?? ''));

		if ($name === '' || $quote === '') {
			header('Location: ' . $back . '&msg=' . rawurlencode('Name and quote are required.'));
			exit;
		}

		if ($sectionTitle !== '') {
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v=VALUES(v)');
			if ($up) {
				$k = 'home_testimonial_title';
				$up->bind_param('ss', $k, $sectionTitle);
				$up->execute();
				$up->close();
			}
		}

		$oldImage = $image;
		if (isset($_FILES['test_image']) && is_array($_FILES['test_image'])) {
			$upImg = sr_admin_upload_image_to(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials', 'images/testimonials', 'testimonial', $_FILES['test_image'], 3_000_000);
			if ($upImg['error'] !== '') {
				header('Location: ' . $back . '&msg=' . rawurlencode($upImg['error']));
				exit;
			}
			if ($upImg['ok']) {
				$image = (string) $upImg['path'];
			}
		}

		if ($image === '') {
			header('Location: ' . $back . '&msg=' . rawurlencode('Please upload a testimonial image.'));
			exit;
		}

		if ($testId > 0) {
			$stmt = $db->prepare('UPDATE cms_testimonials SET name=?, company=?, quote=?, image=?, rating=?, is_active=?, sort_order=? WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('ssssiiii', $name, $company, $quote, $image, $rating, $isActive, $sortOrder, $testId);
				$stmt->execute();
				$stmt->close();
			}
			if ($oldImage !== $image && preg_match('/^images\\/testimonials\\/testimonial-[a-z0-9-]+\\.(png|jpe?g|webp)$/i', $oldImage) === 1) {
				$abs = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $oldImage);
				if (is_file($abs)) {
					@unlink($abs);
				}
			}
			header('Location: ' . $back . '&msg=' . rawurlencode('Testimonial updated.'));
			exit;
		}

		$stmt = $db->prepare('INSERT INTO cms_testimonials (name, company, quote, image, rating, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)');
		if ($stmt) {
			$stmt->bind_param('ssssiii', $name, $company, $quote, $image, $rating, $isActive, $sortOrder);
			$stmt->execute();
			$stmt->close();
		}
		header('Location: ' . $back . '&msg=' . rawurlencode('Testimonial added.'));
		exit;
	}
	if ($op === 'blog_faq_save' || $op === 'blog_faq_delete') {
		$pageId = isset($_POST['page_id']) ? (int) $_POST['page_id'] : 0;
		$back = $pageId > 0 ? ('pages.php?action=edit&id=' . $pageId . '&tab=faqs') : 'pages.php?slug=blog&tab=faqs';

		if ($op === 'blog_faq_delete') {
			$delId = isset($_POST['faq_id']) ? (int) $_POST['faq_id'] : 0;
			if ($delId > 0) {
				$stmt = $db->prepare('DELETE FROM cms_blog_faqs WHERE id=?');
				if ($stmt) {
					$stmt->bind_param('i', $delId);
					$stmt->execute();
					$stmt->close();
				}
			}
			header('Location: ' . $back . '&msg=' . rawurlencode('FAQ deleted.'));
			exit;
		}

		$faqId = isset($_POST['faq_id']) ? (int) $_POST['faq_id'] : 0;
		$q = trim((string) ($_POST['faq_question'] ?? ''));
		$a = trim((string) ($_POST['faq_answer'] ?? ''));
		$sortOrder = isset($_POST['faq_sort_order']) ? (int) $_POST['faq_sort_order'] : 0;
		$isActive = isset($_POST['faq_is_active']) ? 1 : 0;

		if ($q === '' || $a === '') {
			header('Location: ' . $back . '&msg=' . rawurlencode('Question and answer are required.'));
			exit;
		}

		if ($faqId > 0) {
			$stmt = $db->prepare('UPDATE cms_blog_faqs SET question=?, answer=?, is_active=?, sort_order=? WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('ssiii', $q, $a, $isActive, $sortOrder, $faqId);
				$stmt->execute();
				$stmt->close();
			}
			header('Location: ' . $back . '&msg=' . rawurlencode('FAQ updated.'));
			exit;
		}

		$stmt = $db->prepare('INSERT INTO cms_blog_faqs (question, answer, is_active, sort_order) VALUES (?, ?, ?, ?)');
		if ($stmt) {
			$stmt->bind_param('ssii', $q, $a, $isActive, $sortOrder);
			$stmt->execute();
			$stmt->close();
		}
		header('Location: ' . $back . '&msg=' . rawurlencode('FAQ added.'));
		exit;
	}
	if ($op === 'save') {
		$editId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
		$slug = trim((string) ($_POST['slug'] ?? ''));
		$slug = $slug !== '' ? sr_cms_slugify($slug) : '';
		$title = trim((string) ($_POST['title'] ?? ''));
		$heroTitle = trim((string) ($_POST['hero_title'] ?? ''));
		$heroSubtitle = trim((string) ($_POST['hero_subtitle'] ?? ''));
		$bannerImage = trim((string) ($_POST['banner_image'] ?? ''));
		$content = (string) ($_POST['content'] ?? '');
		$sr_banner_upload_err = UPLOAD_ERR_NO_FILE;
		if (isset($_FILES['banner_image_file']) && is_array($_FILES['banner_image_file'])) {
			$sr_banner_upload_err = isset($_FILES['banner_image_file']['error']) ? (int) $_FILES['banner_image_file']['error'] : UPLOAD_ERR_NO_FILE;
		}

		if ($slug === '') {
			$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
			header('Location: ' . $target . '&msg=' . rawurlencode('Slug is required.'));
			exit;
		}

		if ($editId > 0 && $sr_banner_upload_err === UPLOAD_ERR_NO_FILE && $bannerImage === '') {
			$stmtOld = $db->prepare('SELECT banner_image FROM cms_pages WHERE id=? LIMIT 1');
			if ($stmtOld) {
				$stmtOld->bind_param('i', $editId);
				$stmtOld->execute();
				$stmtOld->bind_result($oldBanner);
				if ($stmtOld->fetch()) {
					$bannerImage = (string) $oldBanner;
				}
				$stmtOld->close();
			}
		}

		if (isset($_FILES['banner_image_file']) && is_array($_FILES['banner_image_file'])) {
			$f = $_FILES['banner_image_file'];
			$err = isset($f['error']) ? (int) $f['error'] : UPLOAD_ERR_NO_FILE;
			if ($err !== UPLOAD_ERR_NO_FILE) {
				if ($err !== UPLOAD_ERR_OK) {
					$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
					header('Location: ' . $target . '&msg=' . rawurlencode('Unable to upload banner image.'));
					exit;
				}
				$tmp = (string) ($f['tmp_name'] ?? '');
				$size = (int) ($f['size'] ?? 0);
				if ($size <= 0 || $size > 4_000_000) {
					$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
					header('Location: ' . $target . '&msg=' . rawurlencode('Banner image must be under 4MB.'));
					exit;
				}
				$info = @getimagesize($tmp);
				$mime = is_array($info) ? (string) ($info['mime'] ?? '') : '';
				$ext = '';
				if ($mime === 'image/jpeg') {
					$ext = 'jpg';
				} elseif ($mime === 'image/png') {
					$ext = 'png';
				} elseif ($mime === 'image/webp') {
					$ext = 'webp';
				}
				if ($ext === '') {
					$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
					header('Location: ' . $target . '&msg=' . rawurlencode('Only JPG, PNG, or WEBP images are allowed.'));
					exit;
				}
				$filename = 'page-banner-' . $slug . '-' . time() . '.' . $ext;
				$dest = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $filename;
				if (!@move_uploaded_file($tmp, $dest)) {
					$target = $editId > 0 ? ('pages.php?action=edit&id=' . $editId) : 'pages.php?action=new';
					header('Location: ' . $target . '&msg=' . rawurlencode('Unable to save uploaded banner image.'));
					exit;
				}
				$bannerImage = 'images/' . $filename;
			}
		}

		if ($editId > 0) {
			$stmt = $db->prepare('UPDATE cms_pages SET slug=?, title=?, hero_title=?, hero_subtitle=?, banner_image=?, content=? WHERE id=?');
			if (!$stmt) {
				header('Location: pages.php?msg=' . rawurlencode('Failed to save page.'));
				exit;
			}
			$stmt->bind_param('ssssssi', $slug, $title, $heroTitle, $heroSubtitle, $bannerImage, $content, $editId);
			$stmt->execute();
			$stmt->close();
			if ($slug === 'home') {
				$homeImageUploadMap = [
					'home_about_bg_image' => ['input' => 'home_about_bg_image_file', 'prefix' => 'home-about-bg'],
					'home_process_1_image' => ['input' => 'home_process_1_image_file', 'prefix' => 'home-process-1'],
					'home_process_2_image' => ['input' => 'home_process_2_image_file', 'prefix' => 'home-process-2'],
					'home_process_3_image' => ['input' => 'home_process_3_image_file', 'prefix' => 'home-process-3'],
					'home_process_4_image' => ['input' => 'home_process_4_image_file', 'prefix' => 'home-process-4'],
				];
				foreach ($homeImageUploadMap as $k => $cfg) {
					$input = (string) $cfg['input'];
					$prefix = (string) $cfg['prefix'];
					if (isset($_FILES[$input]) && is_array($_FILES[$input])) {
						$up = sr_admin_upload_image_to(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images', 'images', $prefix, $_FILES[$input], 5_000_000);
						if ($up['error'] !== '') {
							header('Location: pages.php?action=edit&id=' . $editId . '&tab=home&msg=' . rawurlencode($up['error']));
							exit;
						}
						if ($up['ok']) {
							$_POST[$k] = (string) $up['path'];
						}
					}
				}
				$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if ($up) {
					foreach ($homeKeys as $k) {
						$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
						$up->bind_param('ss', $k, $v);
						$up->execute();
					}
					$up->close();
				}
			}
			if ($slug === 'contact') {
				$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if ($up) {
					foreach ($contactKeys as $k) {
						$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
						$up->bind_param('ss', $k, $v);
						$up->execute();
					}
					$up->close();
				}
			}
			if ($slug === 'services') {
				$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if ($up) {
					foreach ($servicesKeys as $k) {
						$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
						$up->bind_param('ss', $k, $v);
						$up->execute();
					}
					$up->close();
				}
			}
			if ($slug === 'projects') {
				$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if ($up) {
					foreach ($projectsKeys as $k) {
						$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
						$up->bind_param('ss', $k, $v);
						$up->execute();
					}
					$up->close();
				}
			}
			if ($slug === 'blog') {
				$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if ($up) {
					foreach ($blogKeys as $k) {
						$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
						$up->bind_param('ss', $k, $v);
						$up->execute();
					}
					$up->close();
				}
			}
			if ($slug === 'about') {
				$aboutImageUploadMap = [
					'about_story_img1' => ['input' => 'about_story_img1_file', 'prefix' => 'about-story-1'],
					'about_story_img2' => ['input' => 'about_story_img2_file', 'prefix' => 'about-story-2'],
					'about_leader1_photo' => ['input' => 'about_leader1_photo_file', 'prefix' => 'about-leader-1'],
					'about_leader2_photo' => ['input' => 'about_leader2_photo_file', 'prefix' => 'about-leader-2'],
					'about_history1_image' => ['input' => 'about_history1_image_file', 'prefix' => 'about-history-1'],
					'about_history2_image' => ['input' => 'about_history2_image_file', 'prefix' => 'about-history-2'],
					'about_history3_image' => ['input' => 'about_history3_image_file', 'prefix' => 'about-history-3'],
					'about_history4_image' => ['input' => 'about_history4_image_file', 'prefix' => 'about-history-4'],
					'about_history5_image' => ['input' => 'about_history5_image_file', 'prefix' => 'about-history-5'],
					'about_history6_image' => ['input' => 'about_history6_image_file', 'prefix' => 'about-history-6'],
				];
				foreach ($aboutImageUploadMap as $k => $cfg) {
					$input = (string) $cfg['input'];
					$prefix = (string) $cfg['prefix'];
					if (isset($_FILES[$input]) && is_array($_FILES[$input])) {
						$upImg = sr_admin_upload_image_to(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'about', 'images/about', $prefix, $_FILES[$input], 5_000_000);
						if ($upImg['error'] !== '') {
							header('Location: pages.php?action=edit&id=' . $editId . '&tab=content&msg=' . rawurlencode($upImg['error']));
							exit;
						}
						if ($upImg['ok']) {
							$_POST[$k] = (string) $upImg['path'];
						}
					}
				}
				$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if ($up) {
					foreach ($aboutKeys as $k) {
						$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
						$up->bind_param('ss', $k, $v);
						$up->execute();
					}
					$up->close();
				}
			}
			if ($slug === 'why-us') {
				$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if ($up) {
					foreach ($whyKeys as $k) {
						$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
						$up->bind_param('ss', $k, $v);
						$up->execute();
					}
					$up->close();
				}
			}
			if ($slug === 'privacy-policy') {
				$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if ($up) {
					foreach ($privacyKeys as $k) {
						$v = isset($_POST[$k]) ? (string) $_POST[$k] : '';
						$up->bind_param('ss', $k, $v);
						$up->execute();
					}
					$up->close();
				}
			}
			if ($slug === 'terms-of-use') {
				$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
				if ($up) {
					foreach ($termsKeys as $k) {
						$v = isset($_POST[$k]) ? (string) $_POST[$k] : '';
						$up->bind_param('ss', $k, $v);
						$up->execute();
					}
					$up->close();
				}
			}
			header('Location: pages.php?action=edit&id=' . $editId . '&msg=' . rawurlencode('Page details saved.'));
			exit;
		}

		$stmt = $db->prepare('INSERT INTO cms_pages (slug, title, hero_title, hero_subtitle, banner_image, content) VALUES (?, ?, ?, ?, ?, ?)');
		if (!$stmt) {
			header('Location: pages.php?msg=' . rawurlencode('Failed to create page.'));
			exit;
		}
		$stmt->bind_param('ssssss', $slug, $title, $heroTitle, $heroSubtitle, $bannerImage, $content);
		$stmt->execute();
		$newId = (int) $stmt->insert_id;
		$stmt->close();
		if ($slug === 'home') {
			$homeImageUploadMap = [
				'home_about_bg_image' => ['input' => 'home_about_bg_image_file', 'prefix' => 'home-about-bg'],
				'home_process_1_image' => ['input' => 'home_process_1_image_file', 'prefix' => 'home-process-1'],
				'home_process_2_image' => ['input' => 'home_process_2_image_file', 'prefix' => 'home-process-2'],
				'home_process_3_image' => ['input' => 'home_process_3_image_file', 'prefix' => 'home-process-3'],
				'home_process_4_image' => ['input' => 'home_process_4_image_file', 'prefix' => 'home-process-4'],
			];
			foreach ($homeImageUploadMap as $k => $cfg) {
				$input = (string) $cfg['input'];
				$prefix = (string) $cfg['prefix'];
				if (isset($_FILES[$input]) && is_array($_FILES[$input])) {
					$upImg = sr_admin_upload_image_to(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images', 'images', $prefix, $_FILES[$input], 5_000_000);
					if ($upImg['error'] !== '') {
						header('Location: pages.php?action=edit&id=' . $newId . '&tab=home&msg=' . rawurlencode($upImg['error']));
						exit;
					}
					if ($upImg['ok']) {
						$_POST[$k] = (string) $upImg['path'];
					}
				}
			}
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
			if ($up) {
				foreach ($homeKeys as $k) {
					$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
					$up->bind_param('ss', $k, $v);
					$up->execute();
				}
				$up->close();
			}
		}
		if ($slug === 'contact') {
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
			if ($up) {
				foreach ($contactKeys as $k) {
					$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
					$up->bind_param('ss', $k, $v);
					$up->execute();
				}
				$up->close();
			}
		}
		if ($slug === 'services') {
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
			if ($up) {
				foreach ($servicesKeys as $k) {
					$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
					$up->bind_param('ss', $k, $v);
					$up->execute();
				}
				$up->close();
			}
		}
		if ($slug === 'projects') {
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
			if ($up) {
				foreach ($projectsKeys as $k) {
					$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
					$up->bind_param('ss', $k, $v);
					$up->execute();
				}
				$up->close();
			}
		}
		if ($slug === 'blog') {
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
			if ($up) {
				foreach ($blogKeys as $k) {
					$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
					$up->bind_param('ss', $k, $v);
					$up->execute();
				}
				$up->close();
			}
		}
		if ($slug === 'about') {
			$aboutImageUploadMap = [
				'about_story_img1' => ['input' => 'about_story_img1_file', 'prefix' => 'about-story-1'],
				'about_story_img2' => ['input' => 'about_story_img2_file', 'prefix' => 'about-story-2'],
				'about_leader1_photo' => ['input' => 'about_leader1_photo_file', 'prefix' => 'about-leader-1'],
				'about_leader2_photo' => ['input' => 'about_leader2_photo_file', 'prefix' => 'about-leader-2'],
				'about_history1_image' => ['input' => 'about_history1_image_file', 'prefix' => 'about-history-1'],
				'about_history2_image' => ['input' => 'about_history2_image_file', 'prefix' => 'about-history-2'],
				'about_history3_image' => ['input' => 'about_history3_image_file', 'prefix' => 'about-history-3'],
				'about_history4_image' => ['input' => 'about_history4_image_file', 'prefix' => 'about-history-4'],
				'about_history5_image' => ['input' => 'about_history5_image_file', 'prefix' => 'about-history-5'],
				'about_history6_image' => ['input' => 'about_history6_image_file', 'prefix' => 'about-history-6'],
			];
			foreach ($aboutImageUploadMap as $k => $cfg) {
				$input = (string) $cfg['input'];
				$prefix = (string) $cfg['prefix'];
				if (isset($_FILES[$input]) && is_array($_FILES[$input])) {
					$upImg = sr_admin_upload_image_to(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'about', 'images/about', $prefix, $_FILES[$input], 5_000_000);
					if ($upImg['error'] !== '') {
						header('Location: pages.php?action=edit&id=' . $newId . '&tab=content&msg=' . rawurlencode($upImg['error']));
						exit;
					}
					if ($upImg['ok']) {
						$_POST[$k] = (string) $upImg['path'];
					}
				}
			}
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
			if ($up) {
				foreach ($aboutKeys as $k) {
					$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
					$up->bind_param('ss', $k, $v);
					$up->execute();
				}
				$up->close();
			}
		}
		if ($slug === 'why-us') {
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
			if ($up) {
				foreach ($whyKeys as $k) {
					$v = isset($_POST[$k]) ? trim((string) $_POST[$k]) : '';
					$up->bind_param('ss', $k, $v);
					$up->execute();
				}
				$up->close();
			}
		}
		if ($slug === 'privacy-policy') {
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
			if ($up) {
				foreach ($privacyKeys as $k) {
					$v = isset($_POST[$k]) ? (string) $_POST[$k] : '';
					$up->bind_param('ss', $k, $v);
					$up->execute();
				}
				$up->close();
			}
		}
		if ($slug === 'terms-of-use') {
			$up = $db->prepare('INSERT INTO cms_settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
			if ($up) {
				foreach ($termsKeys as $k) {
					$v = isset($_POST[$k]) ? (string) $_POST[$k] : '';
					$up->bind_param('ss', $k, $v);
					$up->execute();
				}
				$up->close();
			}
		}
		header('Location: pages.php?action=edit&id=' . $newId . '&msg=' . rawurlencode('Created.'));
		exit;
	}

	if ($op === 'delete') {
		$delId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
		if ($delId > 0) {
			$stmt = $db->prepare('DELETE FROM cms_pages WHERE id=?');
			if ($stmt) {
				$stmt->bind_param('i', $delId);
				$stmt->execute();
				$stmt->close();
			}
		}
		header('Location: pages.php?msg=' . rawurlencode('Deleted.'));
		exit;
	}
}

$editing = null;
if ($action === 'edit' && $id > 0) {
	$stmt = $db->prepare('SELECT id, slug, title, hero_title, hero_subtitle, banner_image, content FROM cms_pages WHERE id=? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($rid, $rslug, $rtitle, $rhero, $rsub, $rbanner, $rcontent);
		if ($stmt->fetch()) {
			$editing = [
				'id' => (int) $rid,
				'slug' => (string) $rslug,
				'title' => (string) $rtitle,
				'hero_title' => (string) $rhero,
				'hero_subtitle' => (string) $rsub,
				'banner_image' => (string) $rbanner,
				'content' => (string) $rcontent,
			];
		}
		$stmt->close();
	}
	if ($editing) {
		$slug = (string) ($editing['slug'] ?? '');
		$hasBannerData = trim((string) ($editing['hero_title'] ?? '')) !== '' || trim((string) ($editing['hero_subtitle'] ?? '')) !== '' || trim((string) ($editing['title'] ?? '')) !== '';
		if (!$hasBannerData && isset($pageBannerDefaults[$slug]) && is_array($pageBannerDefaults[$slug])) {
			$seed = $pageBannerDefaults[$slug];
			$seedTitle = (string) ($seed['title'] ?? '');
			$seedHero = (string) ($seed['hero_title'] ?? '');
			$seedSub = (string) ($seed['hero_subtitle'] ?? '');
			$stmtSeed = $db->prepare('UPDATE cms_pages SET title=?, hero_title=?, hero_subtitle=? WHERE id=?');
			if ($stmtSeed) {
				$stmtSeed->bind_param('sssi', $seedTitle, $seedHero, $seedSub, $editing['id']);
				$stmtSeed->execute();
				$stmtSeed->close();
				$editing['title'] = $seedTitle;
				$editing['hero_title'] = $seedHero;
				$editing['hero_subtitle'] = $seedSub;
			}
		}
	}
	$action = $editing ? 'edit' : 'list';
}

if ($action === 'new') {
	$editing = [
		'id' => 0,
		'slug' => $slugParam !== '' ? $slugParam : '',
		'title' => '',
		'hero_title' => '',
		'hero_subtitle' => '',
		'banner_image' => '',
		'content' => '',
	];
}

$home = [];
if ($editing && $editing['slug'] === 'home') {
	foreach ($homeKeys as $k) {
		$home[$k] = sr_cms_setting_get($k, (string) ($homeDefaults[$k] ?? ''));
	}
}

$contact = [];
if ($editing && $editing['slug'] === 'contact') {
	foreach ($contactKeys as $k) {
		$contact[$k] = sr_cms_setting_get($k, (string) ($contactDefaults[$k] ?? ''));
	}
}

$services = [];
if ($editing && $editing['slug'] === 'services') {
	$ins = $db->prepare('INSERT IGNORE INTO cms_settings (k, v) VALUES (?, ?)');
	if ($ins) {
		foreach ($servicesDefaults as $k => $v) {
			$kk = (string) $k;
			$vv = (string) $v;
			$ins->bind_param('ss', $kk, $vv);
			$ins->execute();
		}
		$ins->close();
	}
	foreach ($servicesKeys as $k) {
		$services[$k] = sr_cms_setting_get($k, (string) ($servicesDefaults[$k] ?? ''));
	}
}

$projects = [];
if ($editing && $editing['slug'] === 'projects') {
	$ins = $db->prepare('INSERT IGNORE INTO cms_settings (k, v) VALUES (?, ?)');
	if ($ins) {
		foreach ($projectsDefaults as $k => $v) {
			$kk = (string) $k;
			$vv = (string) $v;
			$ins->bind_param('ss', $kk, $vv);
			$ins->execute();
		}
		$ins->close();
	}
	foreach ($projectsKeys as $k) {
		$projects[$k] = sr_cms_setting_get($k, (string) ($projectsDefaults[$k] ?? ''));
	}
}

$blogCms = [];
if ($editing && $editing['slug'] === 'blog') {
	$ins = $db->prepare('INSERT IGNORE INTO cms_settings (k, v) VALUES (?, ?)');
	if ($ins) {
		foreach ($blogDefaults as $k => $v) {
			$kk = (string) $k;
			$vv = (string) $v;
			$ins->bind_param('ss', $kk, $vv);
			$ins->execute();
		}
		$ins->close();
	}
	foreach ($blogKeys as $k) {
		$blogCms[$k] = sr_cms_setting_get($k, (string) ($blogDefaults[$k] ?? ''));
	}
}

$blogFaqs = [];
$editingFaq = null;
if ($editing && $editing['slug'] === 'blog') {
	$existingCount = 0;
	$resCnt = $db->query("SELECT COUNT(*) AS cnt FROM cms_blog_faqs");
	if ($resCnt) {
		$row = $resCnt->fetch_assoc();
		$existingCount = (int) ($row['cnt'] ?? 0);
		$resCnt->free();
	}
	if ($existingCount === 0) {
		$seedFaqs = [];
		for ($i = 1; $i <= 10; $i++) {
			$q = trim((string) ($blogDefaults['blog_faq' . $i . '_q'] ?? ''));
			$a = trim((string) ($blogDefaults['blog_faq' . $i . '_a'] ?? ''));
			if ($q === '' || $a === '') {
				continue;
			}
			$seedFaqs[] = ['q' => $q, 'a' => $a, 'sort' => $i];
		}
		if ($seedFaqs) {
			$ins = $db->prepare('INSERT INTO cms_blog_faqs (question, answer, is_active, sort_order) VALUES (?, ?, 1, ?)');
			if ($ins) {
				foreach ($seedFaqs as $f) {
					$q = (string) $f['q'];
					$a = (string) $f['a'];
					$s = (int) $f['sort'];
					$ins->bind_param('ssi', $q, $a, $s);
					$ins->execute();
				}
				$ins->close();
			}
		}
	}

	$res = $db->query("SELECT id, question, answer, is_active, sort_order, updated_at
		FROM cms_blog_faqs
		ORDER BY sort_order ASC, updated_at DESC
		LIMIT 200");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$blogFaqs[] = $row;
		}
		$res->free();
	}

	if ($faqIdParam > 0) {
		foreach ($blogFaqs as $f) {
			if ((int) ($f['id'] ?? 0) === $faqIdParam) {
				$editingFaq = $f;
				break;
			}
		}
	}
}

$about = [];
if ($editing && $editing['slug'] === 'about') {
	foreach ($aboutKeys as $k) {
		$about[$k] = sr_cms_setting_get($k, (string) ($aboutDefaults[$k] ?? ''));
	}
}

$why = [];
if ($editing && $editing['slug'] === 'why-us') {
	foreach ($whyKeys as $k) {
		$why[$k] = sr_cms_setting_get($k, (string) ($whyDefaults[$k] ?? ''));
	}
}

$privacy = [];
if ($editing && $editing['slug'] === 'privacy-policy') {
	$ins = $db->prepare('INSERT IGNORE INTO cms_settings (k, v) VALUES (?, ?)');
	if ($ins) {
		foreach ($privacyDefaults as $k => $v) {
			$kk = (string) $k;
			$vv = (string) $v;
			$ins->bind_param('ss', $kk, $vv);
			$ins->execute();
		}
		$ins->close();
	}
	foreach ($privacyKeys as $k) {
		$privacy[$k] = sr_cms_setting_get($k, (string) ($privacyDefaults[$k] ?? ''));
	}
}

$terms = [];
if ($editing && $editing['slug'] === 'terms-of-use') {
	$ins = $db->prepare('INSERT IGNORE INTO cms_settings (k, v) VALUES (?, ?)');
	if ($ins) {
		foreach ($termsDefaults as $k => $v) {
			$kk = (string) $k;
			$vv = (string) $v;
			$ins->bind_param('ss', $kk, $vv);
			$ins->execute();
		}
		$ins->close();
	}
	foreach ($termsKeys as $k) {
		$terms[$k] = sr_cms_setting_get($k, (string) ($termsDefaults[$k] ?? ''));
	}
}

$pages = [];
$res = $db->query('SELECT id, slug, title, hero_title, banner_image, updated_at FROM cms_pages ORDER BY updated_at DESC LIMIT 200');
if ($res) {
	while ($row = $res->fetch_assoc()) {
		$pages[] = $row;
	}
	$res->free();
}

$known = [
	['slug' => 'home', 'label' => 'Home', 'url' => '../'],
	['slug' => 'about', 'label' => 'About Us', 'url' => '../about'],
	['slug' => 'services', 'label' => 'Services', 'url' => '../services'],
	['slug' => 'products', 'label' => 'Products', 'url' => '../products'],
	['slug' => 'projects', 'label' => 'Projects', 'url' => '../projects'],
	['slug' => 'why-us', 'label' => 'Why Us', 'url' => '../why-us'],
	['slug' => 'blog', 'label' => 'Blog', 'url' => '../blog'],
	['slug' => 'contact', 'label' => 'Contact', 'url' => '../contact'],
	['slug' => 'privacy-policy', 'label' => 'Privacy Policy', 'url' => '../privacy-policy'],
	['slug' => 'terms-of-use', 'label' => 'Terms of Use', 'url' => '../terms-of-use'],
];

$sr_is_home = $editing && $editing['slug'] === 'home';
$sr_allowed_tabs = $sr_is_home ? ['slider', 'home', 'testimonials'] : ['content', 'banner'];
if ($editing && $editing['slug'] === 'blog') {
	$sr_allowed_tabs[] = 'faqs';
}
$sr_tab = in_array($tabParam, $sr_allowed_tabs, true) ? $tabParam : ($sr_is_home ? 'slider' : 'content');

$homeSlides = [];
$editingSlide = null;
if ($editing && $editing['slug'] === 'home') {
	$res = $db->query("SELECT id, image, kicker, title, subtitle, primary_label, primary_url, secondary_label, secondary_url, is_active, sort_order, updated_at
		FROM cms_banners
		ORDER BY sort_order ASC, updated_at DESC
		LIMIT 50");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$homeSlides[] = $row;
		}
		$res->free();
	}
	if (!$homeSlides) {
		$dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'banner-slider-img';
		$files = is_dir($dir) ? glob($dir . DIRECTORY_SEPARATOR . '*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) : [];
		if (is_array($files) && $files) {
			sort($files);
			$kicker = sr_cms_setting_get('home_kicker', 'Maharashtra’s Trusted Solar EPC Partner');
			$heroTitle = trim((string) ($editing['hero_title'] ?? ''));
			$heroSubtitle = trim((string) ($editing['hero_subtitle'] ?? ''));
			$heroTitle = $heroTitle !== '' ? $heroTitle : 'Powering a Greener Tomorrow — One Solar Panel at a Time';
			$heroSubtitle = $heroSubtitle !== '' ? $heroSubtitle : 'Shivanjali Renewables is Maharashtra\'s trusted Solar EPC partner for homes, businesses, industries, and large-scale solar parks. Clean energy. Real savings. Lasting impact.';
			$primaryLabel = 'Get a Free Solar Quote';
			$primaryUrl = 'contact';
			$secondaryLabel = 'Explore Our Services';
			$secondaryUrl = 'services';

			$ins = $db->prepare('INSERT INTO cms_banners (image, kicker, title, subtitle, primary_label, primary_url, secondary_label, secondary_url, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)');
			if ($ins) {
				$sort = 0;
				foreach ($files as $fp) {
					$rel = 'images/banner-slider-img/' . basename((string) $fp);
					$sort++;
					$ins->bind_param('ssssssssi', $rel, $kicker, $heroTitle, $heroSubtitle, $primaryLabel, $primaryUrl, $secondaryLabel, $secondaryUrl, $sort);
					$ins->execute();
					if ($sort >= 5) {
						break;
					}
				}
				$ins->close();
			}

			$res2 = $db->query("SELECT id, image, kicker, title, subtitle, primary_label, primary_url, secondary_label, secondary_url, is_active, sort_order, updated_at
				FROM cms_banners
				ORDER BY sort_order ASC, updated_at DESC
				LIMIT 50");
			if ($res2) {
				while ($row = $res2->fetch_assoc()) {
					$homeSlides[] = $row;
				}
				$res2->free();
			}
		}
	}
	if ($slideIdParam > 0) {
		foreach ($homeSlides as $s) {
			if ((int) ($s['id'] ?? 0) === $slideIdParam) {
				$editingSlide = $s;
				break;
			}
		}
	}
}

$homeTestimonials = [];
$editingTestimonial = null;
$homeTestimonialTitle = sr_cms_setting_get('home_testimonial_title', 'What Our Clients Say');
if ($editing && $editing['slug'] === 'home') {
	$res = $db->query("SELECT id, name, company, quote, image, rating, is_active, sort_order, updated_at
		FROM cms_testimonials
		ORDER BY sort_order ASC, updated_at DESC
		LIMIT 100");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$homeTestimonials[] = $row;
		}
		$res->free();
	}
	if ($testIdParam > 0) {
		foreach ($homeTestimonials as $t) {
			if ((int) ($t['id'] ?? 0) === $testIdParam) {
				$editingTestimonial = $t;
				break;
			}
		}
	}
}
?>
<?php include 'header.php'; ?>
<div class="page-body-wrapper">
	<?php include 'sidebar.php'; ?>
	<div class="page-body">
		<div class="container-fluid">
			<div class="page-title">
				<div class="row">
					<div class="col-sm-6 col-12">
						<h2>Pages</h2>
						<p class="mb-0 text-title-gray">Edit hero titles/subtitles and page-level content blocks.</p>
					</div>
					<div class="col-sm-6 col-12">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.php"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item active">Pages</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<?php if ($msg !== '') { ?>
				<div class="alert alert-info"><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>

			<div class="row g-4">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
								<h4 class="mb-0"><?php echo $editing ? 'Edit Page' : 'Pages'; ?></h4>
								<div class="d-flex flex-wrap gap-2">
									<a class="btn btn-outline-primary" href="../" target="_blank" rel="noopener">Open
										Website</a>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php if ($editing) { ?>
								<form method="post"
									action="pages.php<?php echo $editing['id'] ? ('?action=edit&id=' . (int) $editing['id']) : '?action=new'; ?>"
									enctype="multipart/form-data">
									<input type="hidden" name="csrf"
										value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="save">
									<input type="hidden" name="id" value="<?php echo (int) $editing['id']; ?>">
									<?php if ($sr_is_home) { ?>
										<input type="hidden" name="slug" value="home">
										<input type="hidden" name="title"
											value="<?php echo htmlspecialchars((string) $editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
										<input type="hidden" name="hero_title"
											value="<?php echo htmlspecialchars((string) $editing['hero_title'], ENT_QUOTES, 'UTF-8'); ?>">
										<input type="hidden" name="hero_subtitle"
											value="<?php echo htmlspecialchars((string) $editing['hero_subtitle'], ENT_QUOTES, 'UTF-8'); ?>">
										<input type="hidden" name="content"
											value="<?php echo htmlspecialchars((string) $editing['content'], ENT_QUOTES, 'UTF-8'); ?>">
										<input type="hidden" name="banner_image"
											value="<?php echo htmlspecialchars((string) $editing['banner_image'], ENT_QUOTES, 'UTF-8'); ?>">
									<?php } ?>

									<ul class="nav nav-tabs border-bottom mb-3" role="tablist">
										<?php if ($sr_is_home) { ?>
											<li class="nav-item" role="presentation">
												<button class="nav-link <?php echo $sr_tab === 'slider' ? 'active' : ''; ?>"
													data-bs-toggle="tab" data-bs-target="#srPageTabSlider" type="button"
													role="tab">Add Slider</button>
											</li>
											<li class="nav-item" role="presentation">
												<button class="nav-link <?php echo $sr_tab === 'home' ? 'active' : ''; ?>"
													data-bs-toggle="tab" data-bs-target="#srPageTabHome" type="button"
													role="tab">Home CMS</button>
											</li>
											<li class="nav-item" role="presentation">
												<button
													class="nav-link <?php echo $sr_tab === 'testimonials' ? 'active' : ''; ?>"
													data-bs-toggle="tab" data-bs-target="#srPageTabTestimonials" type="button"
													role="tab">Testimonials</button>
											</li>
										<?php } else { ?>
											<li class="nav-item" role="presentation">
												<button class="nav-link <?php echo $sr_tab === 'content' ? 'active' : ''; ?>"
													data-bs-toggle="tab" data-bs-target="#srPageTabContent" type="button"
													role="tab">Content</button>
											</li>
											<?php if ($editing['slug'] === 'blog') { ?>
												<li class="nav-item" role="presentation">
													<button class="nav-link <?php echo $sr_tab === 'faqs' ? 'active' : ''; ?>"
														data-bs-toggle="tab" data-bs-target="#srPageTabFaqs" type="button"
														role="tab">FAQs</button>
												</li>
											<?php } ?>
											<li class="nav-item" role="presentation">
												<button class="nav-link <?php echo $sr_tab === 'banner' ? 'active' : ''; ?>"
													data-bs-toggle="tab" data-bs-target="#srPageTabBanner" type="button"
													role="tab">Banner</button>
											</li>
										<?php } ?>
										<li class="nav-item ms-auto" role="presentation">
											<a class="nav-link btn btn-secondary text-white"
												href="seo.php?route=<?php echo rawurlencode($sr_is_home ? '/' : ('/' . $editing['slug'])); ?>">SEO</a>
										</li>
									</ul>

									<div class="tab-content">
										<?php if (!$sr_is_home) { ?>
											<div class="tab-pane fade <?php echo $sr_tab === 'content' ? 'show active' : ''; ?>"
												id="srPageTabContent" role="tabpanel">
												<div class="row g-3">
													<input type="hidden" class="form-control" name="slug" required
														value="<?php echo htmlspecialchars($editing['slug'], ENT_QUOTES, 'UTF-8'); ?>">
													<!-- <div class="col-lg-8">
														<label class="form-label">Page title (optional)</label>
														<input class="form-control" name="title"
															value="<?php echo htmlspecialchars($editing['title'], ENT_QUOTES, 'UTF-8'); ?>">
													</div>
													<div class="col-12">
														<label class="form-label">Page content (optional, HTML allowed)</label>
														<textarea class="form-control" name="content"
															rows="10"><?php echo htmlspecialchars($editing['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
													</div> -->
													<?php if ($editing['slug'] === 'services') { ?>
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-1 text-dark">Services Page CMS</div>
																<div class="text-title-gray">Update the Services page intro section without changing the design.</div>
															</div>
														</div>
														<div class="col-12">
															<label class="form-label">Intro section title</label>
															<input class="form-control" name="services_intro_title" value="<?php echo htmlspecialchars((string) ($services['services_intro_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-12">
															<label class="form-label">Intro section description</label>
															<textarea class="form-control" name="services_intro_desc" rows="2"><?php echo htmlspecialchars((string) ($services['services_intro_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
													<?php } ?>
													<?php if ($editing['slug'] === 'projects') { ?>
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-1 text-dark">Projects Page CMS</div>
																<div class="text-title-gray">Update Projects sections without changing the design.</div>
															</div>
														</div>
														<div class="col-12">
															<div class="fw-bold text-dark">Category cards</div>
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 1 badge</label>
															<input class="form-control" name="projects_card1_badge" value="<?php echo htmlspecialchars((string) ($projects['projects_card1_badge'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">Card 1 title</label>
															<input class="form-control" name="projects_card1_title" value="<?php echo htmlspecialchars((string) ($projects['projects_card1_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-12">
															<label class="form-label">Card 1 description</label>
															<textarea class="form-control" name="projects_card1_desc" rows="3"><?php echo htmlspecialchars((string) ($projects['projects_card1_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-12">
															<label class="form-label">Card 1 list title</label>
															<input class="form-control" name="projects_card1_list_title" value="<?php echo htmlspecialchars((string) ($projects['projects_card1_list_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 1 list item 1</label>
															<input class="form-control" name="projects_card1_list1" value="<?php echo htmlspecialchars((string) ($projects['projects_card1_list1'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 1 list item 2</label>
															<input class="form-control" name="projects_card1_list2" value="<?php echo htmlspecialchars((string) ($projects['projects_card1_list2'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 1 list item 3</label>
															<input class="form-control" name="projects_card1_list3" value="<?php echo htmlspecialchars((string) ($projects['projects_card1_list3'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 2 badge</label>
															<input class="form-control" name="projects_card2_badge" value="<?php echo htmlspecialchars((string) ($projects['projects_card2_badge'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">Card 2 title</label>
															<input class="form-control" name="projects_card2_title" value="<?php echo htmlspecialchars((string) ($projects['projects_card2_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-12">
															<label class="form-label">Card 2 description</label>
															<textarea class="form-control" name="projects_card2_desc" rows="3"><?php echo htmlspecialchars((string) ($projects['projects_card2_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-12">
															<label class="form-label">Card 2 list title</label>
															<input class="form-control" name="projects_card2_list_title" value="<?php echo htmlspecialchars((string) ($projects['projects_card2_list_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 2 list item 1</label>
															<input class="form-control" name="projects_card2_list1" value="<?php echo htmlspecialchars((string) ($projects['projects_card2_list1'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 2 list item 2</label>
															<input class="form-control" name="projects_card2_list2" value="<?php echo htmlspecialchars((string) ($projects['projects_card2_list2'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 2 list item 3</label>
															<input class="form-control" name="projects_card2_list3" value="<?php echo htmlspecialchars((string) ($projects['projects_card2_list3'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 3 badge</label>
															<input class="form-control" name="projects_card3_badge" value="<?php echo htmlspecialchars((string) ($projects['projects_card3_badge'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">Card 3 title</label>
															<input class="form-control" name="projects_card3_title" value="<?php echo htmlspecialchars((string) ($projects['projects_card3_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-12">
															<label class="form-label">Card 3 description</label>
															<textarea class="form-control" name="projects_card3_desc" rows="3"><?php echo htmlspecialchars((string) ($projects['projects_card3_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-12">
															<label class="form-label">Card 3 list title</label>
															<input class="form-control" name="projects_card3_list_title" value="<?php echo htmlspecialchars((string) ($projects['projects_card3_list_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 3 list item 1</label>
															<input class="form-control" name="projects_card3_list1" value="<?php echo htmlspecialchars((string) ($projects['projects_card3_list1'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 3 list item 2</label>
															<input class="form-control" name="projects_card3_list2" value="<?php echo htmlspecialchars((string) ($projects['projects_card3_list2'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Card 3 list item 3</label>
															<input class="form-control" name="projects_card3_list3" value="<?php echo htmlspecialchars((string) ($projects['projects_card3_list3'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Gallery title</label>
															<input class="form-control" name="projects_gallery_title" value="<?php echo htmlspecialchars((string) ($projects['projects_gallery_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Gallery description</label>
															<input class="form-control" name="projects_gallery_desc" value="<?php echo htmlspecialchars((string) ($projects['projects_gallery_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-3">
															<label class="form-label">Filter label: All</label>
															<input class="form-control" name="projects_filter_all" value="<?php echo htmlspecialchars((string) ($projects['projects_filter_all'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-3">
															<label class="form-label">Filter label: Rooftop</label>
															<input class="form-control" name="projects_filter_rooftop" value="<?php echo htmlspecialchars((string) ($projects['projects_filter_rooftop'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-3">
															<label class="form-label">Filter label: Open Access</label>
															<input class="form-control" name="projects_filter_openaccess" value="<?php echo htmlspecialchars((string) ($projects['projects_filter_openaccess'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-3">
															<label class="form-label">Filter label: Solar Parks</label>
															<input class="form-control" name="projects_filter_parks" value="<?php echo htmlspecialchars((string) ($projects['projects_filter_parks'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-lg-6">
															<label class="form-label">CTA title</label>
															<input class="form-control" name="projects_cta_title" value="<?php echo htmlspecialchars((string) ($projects['projects_cta_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">CTA button label</label>
															<input class="form-control" name="projects_cta_btn_label" value="<?php echo htmlspecialchars((string) ($projects['projects_cta_btn_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">CTA description</label>
															<input class="form-control" name="projects_cta_desc" value="<?php echo htmlspecialchars((string) ($projects['projects_cta_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">CTA button URL</label>
															<input class="form-control" name="projects_cta_btn_url" value="<?php echo htmlspecialchars((string) ($projects['projects_cta_btn_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
													<?php } ?>
													<?php if ($editing['slug'] === 'blog') { ?>
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-1 text-dark">Blog Page CMS</div>
																<div class="text-title-gray">Update Blog sections without changing the design.</div>
															</div>
														</div>
														<div class="col-12">
															<label class="form-label">Categories section title</label>
															<input class="form-control" name="blog_categories_title" value="<?php echo htmlspecialchars((string) ($blogCms['blog_categories_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<?php for ($i = 1; $i <= 5; $i++) { ?>
															<div class="col-12">
																<div class="fw-bold text-dark">Category card <?php echo (int)$i; ?></div>
															</div>
															<div class="col-lg-4">
																<label class="form-label">Icon class</label>
																<input class="form-control" name="blog_cat<?php echo (int)$i; ?>_icon" value="<?php echo htmlspecialchars((string) ($blogCms['blog_cat' . $i . '_icon'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Title</label>
																<input class="form-control" name="blog_cat<?php echo (int)$i; ?>_title" value="<?php echo htmlspecialchars((string) ($blogCms['blog_cat' . $i . '_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-12">
																<label class="form-label">Description</label>
																<input class="form-control" name="blog_cat<?php echo (int)$i; ?>_desc" value="<?php echo htmlspecialchars((string) ($blogCms['blog_cat' . $i . '_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<?php if ($i !== 5) { ?><div class="col-12"><hr class="my-2"></div><?php } ?>
														<?php } ?>
														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Latest articles title</label>
															<input class="form-control" name="blog_latest_title" value="<?php echo htmlspecialchars((string) ($blogCms['blog_latest_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Latest articles description</label>
															<input class="form-control" name="blog_latest_desc" value="<?php echo htmlspecialchars((string) ($blogCms['blog_latest_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<!-- <div class="col-12">
															<hr class="my-2">
														</div> -->
														<!-- <div class="col-12">
															<label class="form-label">FAQ section title</label>
															<input class="form-control" name="blog_faq_title" value="<?php echo htmlspecialchars((string) ($blogCms['blog_faq_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div> -->
														<!-- <?php for ($i = 1; $i <= 10; $i++) { ?>
															<div class="col-lg-5">
																<label class="form-label">FAQ <?php echo (int)$i; ?> question</label>
																<input class="form-control" name="blog_faq<?php echo (int)$i; ?>_q" value="<?php echo htmlspecialchars((string) ($blogCms['blog_faq' . $i . '_q'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-7">
																<label class="form-label">FAQ <?php echo (int)$i; ?> answer</label>
																<textarea class="form-control" name="blog_faq<?php echo (int)$i; ?>_a" rows="2"><?php echo htmlspecialchars((string) ($blogCms['blog_faq' . $i . '_a'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
															</div>
														<?php } ?> -->
														<div class="col-lg-6">
															<label class="form-label">FAQ button label</label>
															<input class="form-control" name="blog_faq_cta_label" value="<?php echo htmlspecialchars((string) ($blogCms['blog_faq_cta_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">FAQ button URL</label>
															<input class="form-control" name="blog_faq_cta_url" value="<?php echo htmlspecialchars((string) ($blogCms['blog_faq_cta_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
													<?php } ?>
													<?php if ($editing['slug'] === 'contact') { ?>
														<!-- <div class="col-12">
															<hr class="my-2">
														</div> -->
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-1 text-dark">Contact Page CMS</div>
																<div class="text-title-gray">Update contact page section headings
																	and map embed without changing the design.</div>
															</div>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Reach section title</label>
															<input class="form-control" name="contact_reach_title"
																value="<?php echo htmlspecialchars((string) ($contact['contact_reach_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Form section title</label>
															<input class="form-control" name="contact_form_title"
																value="<?php echo htmlspecialchars((string) ($contact['contact_form_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-12">
															<label class="form-label">Form section description</label>
															<textarea class="form-control" name="contact_form_desc"
																rows="2"><?php echo htmlspecialchars((string) ($contact['contact_form_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-12">
															<label class="form-label">Brands section title</label>
															<input class="form-control" name="contact_brands_title"
																value="<?php echo htmlspecialchars((string) ($contact['contact_brands_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Map section title</label>
															<input class="form-control" name="contact_map_title"
																value="<?php echo htmlspecialchars((string) ($contact['contact_map_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Directions button label</label>
															<input class="form-control" name="contact_directions_label"
																value="<?php echo htmlspecialchars((string) ($contact['contact_directions_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Map aria-label</label>
															<input class="form-control" name="contact_map_aria_label"
																value="<?php echo htmlspecialchars((string) ($contact['contact_map_aria_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-12">
															<label class="form-label">Google Maps embed URL (iframe src)</label>
															<textarea class="form-control" name="contact_map_embed_url"
																rows="2"><?php echo htmlspecialchars((string) ($contact['contact_map_embed_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
															<div class="form-text">Paste the full iframe src URL from Google Maps
																embed.</div>
														</div>
													<?php } ?>
													<?php if ($editing['slug'] === 'privacy-policy') { ?>
														<!-- <div class="col-12">
															<hr class="my-2">
														</div> -->
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-1 text-dark">Privacy Policy CMS</div>
																<div class="text-title-gray">Update sections without changing the design. HTML is allowed inside section content.</div>
															</div>
														</div>

														<div class="col-lg-6">
															<label class="form-label">Updated text</label>
															<input class="form-control" name="pp_updated_text" value="<?php echo htmlspecialchars((string) ($privacy['pp_updated_text'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">TOC title</label>
															<input class="form-control" name="pp_toc_title" value="<?php echo htmlspecialchars((string) ($privacy['pp_toc_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">TOC CTA label</label>
															<input class="form-control" name="pp_cta_label" value="<?php echo htmlspecialchars((string) ($privacy['pp_cta_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">TOC CTA URL</label>
															<input class="form-control" name="pp_cta_url" value="<?php echo htmlspecialchars((string) ($privacy['pp_cta_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>

														<div class="col-12">
															<h4 class="mb-2">Highlights (HTML)</h4>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Highlight 1</label>
															<textarea class="form-control" name="pp_highlight1_html" rows="2"><?php echo htmlspecialchars((string) ($privacy['pp_highlight1_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Highlight 2</label>
															<textarea class="form-control" name="pp_highlight2_html" rows="2"><?php echo htmlspecialchars((string) ($privacy['pp_highlight2_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Highlight 3</label>
															<textarea class="form-control" name="pp_highlight3_html" rows="2"><?php echo htmlspecialchars((string) ($privacy['pp_highlight3_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Highlight 4</label>
															<textarea class="form-control" name="pp_highlight4_html" rows="2"><?php echo htmlspecialchars((string) ($privacy['pp_highlight4_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-12">
															<h4 class="mb-2">Sections</h4>
														</div>

														<div class="col-lg-4">
															<label class="form-label">1) Heading</label>
															<input class="form-control" name="pp_scope_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_scope_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">1) Content (HTML)</label>
															<textarea class="form-control" name="pp_scope_html" rows="4"><?php echo htmlspecialchars((string) ($privacy['pp_scope_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">2) Heading</label>
															<input class="form-control" name="pp_info_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_info_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">2) Content (HTML)</label>
															<textarea class="form-control" name="pp_info_html" rows="5"><?php echo htmlspecialchars((string) ($privacy['pp_info_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">3) Heading</label>
															<input class="form-control" name="pp_use_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_use_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">3) Content (HTML)</label>
															<textarea class="form-control" name="pp_use_html" rows="5"><?php echo htmlspecialchars((string) ($privacy['pp_use_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">4) Heading</label>
															<input class="form-control" name="pp_cookies_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_cookies_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">4) Content (HTML)</label>
															<textarea class="form-control" name="pp_cookies_html" rows="5"><?php echo htmlspecialchars((string) ($privacy['pp_cookies_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">5) Heading</label>
															<input class="form-control" name="pp_sharing_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_sharing_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">5) Content (HTML)</label>
															<textarea class="form-control" name="pp_sharing_html" rows="5"><?php echo htmlspecialchars((string) ($privacy['pp_sharing_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">6) Heading</label>
															<input class="form-control" name="pp_retention_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_retention_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">6) Content (HTML)</label>
															<textarea class="form-control" name="pp_retention_html" rows="4"><?php echo htmlspecialchars((string) ($privacy['pp_retention_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">7) Heading</label>
															<input class="form-control" name="pp_security_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_security_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">7) Content (HTML)</label>
															<textarea class="form-control" name="pp_security_html" rows="4"><?php echo htmlspecialchars((string) ($privacy['pp_security_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">8) Heading</label>
															<input class="form-control" name="pp_rights_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_rights_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">8) Content (HTML)</label>
															<textarea class="form-control" name="pp_rights_html" rows="5"><?php echo htmlspecialchars((string) ($privacy['pp_rights_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">9) Heading</label>
															<input class="form-control" name="pp_links_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_links_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">9) Content (HTML)</label>
															<textarea class="form-control" name="pp_links_html" rows="4"><?php echo htmlspecialchars((string) ($privacy['pp_links_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">10) Heading</label>
															<input class="form-control" name="pp_changes_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_changes_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">10) Content (HTML)</label>
															<textarea class="form-control" name="pp_changes_html" rows="4"><?php echo htmlspecialchars((string) ($privacy['pp_changes_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">11) Heading</label>
															<input class="form-control" name="pp_contact_h" value="<?php echo htmlspecialchars((string) ($privacy['pp_contact_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">11) Content (HTML)</label>
															<textarea class="form-control" name="pp_contact_html" rows="5"><?php echo htmlspecialchars((string) ($privacy['pp_contact_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
													<?php } ?>
													<?php if ($editing['slug'] === 'terms-of-use') { ?>
														<!-- <div class="col-12">
															<hr class="my-2">
														</div> -->
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-1 text-dark">Terms of Use CMS</div>
																<div class="text-title-gray">Update sections without changing the design. HTML is allowed inside section content.</div>
															</div>
														</div>

														<div class="col-lg-6">
															<label class="form-label">Updated text</label>
															<input class="form-control" name="tou_updated_text" value="<?php echo htmlspecialchars((string) ($terms['tou_updated_text'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">TOC title</label>
															<input class="form-control" name="tou_toc_title" value="<?php echo htmlspecialchars((string) ($terms['tou_toc_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">TOC CTA label</label>
															<input class="form-control" name="tou_cta_label" value="<?php echo htmlspecialchars((string) ($terms['tou_cta_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">TOC CTA URL</label>
															<input class="form-control" name="tou_cta_url" value="<?php echo htmlspecialchars((string) ($terms['tou_cta_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>

														<div class="col-12">
															<h4 class="mb-2">Highlights (HTML)</h4>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Highlight 1</label>
															<textarea class="form-control" name="tou_highlight1_html" rows="2"><?php echo htmlspecialchars((string) ($terms['tou_highlight1_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Highlight 2</label>
															<textarea class="form-control" name="tou_highlight2_html" rows="2"><?php echo htmlspecialchars((string) ($terms['tou_highlight2_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Highlight 3</label>
															<textarea class="form-control" name="tou_highlight3_html" rows="2"><?php echo htmlspecialchars((string) ($terms['tou_highlight3_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Highlight 4</label>
															<textarea class="form-control" name="tou_highlight4_html" rows="2"><?php echo htmlspecialchars((string) ($terms['tou_highlight4_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-12">
															<h4 class="mb-2">Sections</h4>
														</div>

														<div class="col-lg-4">
															<label class="form-label">1) Heading</label>
															<input class="form-control" name="tou_acceptance_h" value="<?php echo htmlspecialchars((string) ($terms['tou_acceptance_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">1) Content (HTML)</label>
															<textarea class="form-control" name="tou_acceptance_html" rows="4"><?php echo htmlspecialchars((string) ($terms['tou_acceptance_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">2) Heading</label>
															<input class="form-control" name="tou_eligibility_h" value="<?php echo htmlspecialchars((string) ($terms['tou_eligibility_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">2) Content (HTML)</label>
															<textarea class="form-control" name="tou_eligibility_html" rows="4"><?php echo htmlspecialchars((string) ($terms['tou_eligibility_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">3) Heading</label>
															<input class="form-control" name="tou_services_h" value="<?php echo htmlspecialchars((string) ($terms['tou_services_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">3) Content (HTML)</label>
															<textarea class="form-control" name="tou_services_html" rows="5"><?php echo htmlspecialchars((string) ($terms['tou_services_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">4) Heading</label>
															<input class="form-control" name="tou_quotes_h" value="<?php echo htmlspecialchars((string) ($terms['tou_quotes_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">4) Content (HTML)</label>
															<textarea class="form-control" name="tou_quotes_html" rows="5"><?php echo htmlspecialchars((string) ($terms['tou_quotes_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">5) Heading</label>
															<input class="form-control" name="tou_ip_h" value="<?php echo htmlspecialchars((string) ($terms['tou_ip_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">5) Content (HTML)</label>
															<textarea class="form-control" name="tou_ip_html" rows="5"><?php echo htmlspecialchars((string) ($terms['tou_ip_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">6) Heading</label>
															<input class="form-control" name="tou_acceptable_h" value="<?php echo htmlspecialchars((string) ($terms['tou_acceptable_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">6) Content (HTML)</label>
															<textarea class="form-control" name="tou_acceptable_html" rows="5"><?php echo htmlspecialchars((string) ($terms['tou_acceptable_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">7) Heading</label>
															<input class="form-control" name="tou_links_h" value="<?php echo htmlspecialchars((string) ($terms['tou_links_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">7) Content (HTML)</label>
															<textarea class="form-control" name="tou_links_html" rows="4"><?php echo htmlspecialchars((string) ($terms['tou_links_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">8) Heading</label>
															<input class="form-control" name="tou_disclaimer_h" value="<?php echo htmlspecialchars((string) ($terms['tou_disclaimer_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">8) Content (HTML)</label>
															<textarea class="form-control" name="tou_disclaimer_html" rows="5"><?php echo htmlspecialchars((string) ($terms['tou_disclaimer_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">9) Heading</label>
															<input class="form-control" name="tou_liability_h" value="<?php echo htmlspecialchars((string) ($terms['tou_liability_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">9) Content (HTML)</label>
															<textarea class="form-control" name="tou_liability_html" rows="4"><?php echo htmlspecialchars((string) ($terms['tou_liability_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">10) Heading</label>
															<input class="form-control" name="tou_indemnity_h" value="<?php echo htmlspecialchars((string) ($terms['tou_indemnity_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">10) Content (HTML)</label>
															<textarea class="form-control" name="tou_indemnity_html" rows="4"><?php echo htmlspecialchars((string) ($terms['tou_indemnity_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">11) Heading</label>
															<input class="form-control" name="tou_law_h" value="<?php echo htmlspecialchars((string) ($terms['tou_law_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">11) Content (HTML)</label>
															<textarea class="form-control" name="tou_law_html" rows="4"><?php echo htmlspecialchars((string) ($terms['tou_law_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">12) Heading</label>
															<input class="form-control" name="tou_changes_h" value="<?php echo htmlspecialchars((string) ($terms['tou_changes_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">12) Content (HTML)</label>
															<textarea class="form-control" name="tou_changes_html" rows="4"><?php echo htmlspecialchars((string) ($terms['tou_changes_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-4">
															<label class="form-label">13) Heading</label>
															<input class="form-control" name="tou_contact_h" value="<?php echo htmlspecialchars((string) ($terms['tou_contact_h'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">13) Content (HTML)</label>
															<textarea class="form-control" name="tou_contact_html" rows="5"><?php echo htmlspecialchars((string) ($terms['tou_contact_html'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
													<?php } ?>
													<?php if ($editing['slug'] === 'why-us') { ?>
														<!-- <div class="col-12">
															<hr class="my-2">
														</div> -->
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-1 text-dark">Why Us Page CMS</div>
																<div class="text-title-gray">Update Why Us sections without changing
																	the design. Testimonials are fetched from the Home Testimonials
																	tab.</div>
															</div>
														</div>

														<div class="col-12">
															<h4 class="mb-2">Why Clients Choose Us</h4>
														</div>
														<div class="col-12">
															<label class="form-label">Section title</label>
															<input class="form-control" name="why_diff_title"
																value="<?php echo htmlspecialchars((string) ($why['why_diff_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<?php for ($i = 1; $i <= 6; $i++) { ?>
															<div class="col-12">
																<div class="p-3 rounded-3 border bg-light">
																	<div class="fw-bold mb-1 text-dark">Card <?php echo (int) $i; ?>
																	</div>
																</div>
															</div>
															<div class="col-lg-4">
																<label class="form-label">Title</label>
																<input class="form-control" name="why_diff_card<?php echo (int) $i; ?>_title"
																	value="<?php echo htmlspecialchars((string) ($why['why_diff_card' . $i . '_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Description</label>
																<input class="form-control" name="why_diff_card<?php echo (int) $i; ?>_desc"
																	value="<?php echo htmlspecialchars((string) ($why['why_diff_card' . $i . '_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
														<?php } ?>

														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-12">
															<h4 class="mb-2">Backed by Technology</h4>
														</div>
														<div class="col-12">
															<label class="form-label">Section title</label>
															<input class="form-control" name="why_tech_title"
																value="<?php echo htmlspecialchars((string) ($why['why_tech_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<?php for ($i = 1; $i <= 3; $i++) { ?>
															<div class="col-12">
																<div class="p-3 rounded-3 border bg-light">
																	<div class="fw-bold mb-1 text-dark">Tech card <?php echo (int) $i; ?>
																	</div>
																</div>
															</div>
															<div class="col-lg-4">
																<label class="form-label">Title</label>
																<input class="form-control" name="why_tech_card<?php echo (int) $i; ?>_title"
																	value="<?php echo htmlspecialchars((string) ($why['why_tech_card' . $i . '_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Description</label>
																<textarea class="form-control" name="why_tech_card<?php echo (int) $i; ?>_desc"
																	rows="2"><?php echo htmlspecialchars((string) ($why['why_tech_card' . $i . '_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
															</div>
														<?php } ?>

														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-12">
															<h4 class="mb-2">Testimonials</h4>
														</div>
														<div class="col-12">
															<label class="form-label">Section title</label>
															<input class="form-control" name="why_testimonials_title"
																value="<?php echo htmlspecialchars((string) ($why['why_testimonials_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>

														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-12">
															<h4 class="mb-2">Environmental Impact</h4>
														</div>
														<div class="col-12">
															<label class="form-label">Section title</label>
															<input class="form-control" name="why_impact_title"
																value="<?php echo htmlspecialchars((string) ($why['why_impact_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<?php for ($i = 1; $i <= 3; $i++) { ?>
															<div class="col-12">
																<div class="p-3 rounded-3 border bg-light">
																	<div class="fw-bold mb-1 text-dark">Impact card <?php echo (int) $i; ?>
																	</div>
																</div>
															</div>
															<div class="col-lg-4">
																<label class="form-label">Label</label>
																<input class="form-control" name="why_impact<?php echo (int) $i; ?>_label"
																	value="<?php echo htmlspecialchars((string) ($why['why_impact' . $i . '_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-4">
																<label class="form-label">Number</label>
																<input class="form-control" type="number" name="why_impact<?php echo (int) $i; ?>_to"
																	value="<?php echo htmlspecialchars((string) ($why['why_impact' . $i . '_to'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-4">
																<label class="form-label">Unit</label>
																<input class="form-control" name="why_impact<?php echo (int) $i; ?>_unit"
																	value="<?php echo htmlspecialchars((string) ($why['why_impact' . $i . '_unit'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-12">
																<label class="form-label">Description</label>
																<textarea class="form-control" name="why_impact<?php echo (int) $i; ?>_desc"
																	rows="2"><?php echo htmlspecialchars((string) ($why['why_impact' . $i . '_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
															</div>
														<?php } ?>
													<?php } ?>
													<?php if ($editing['slug'] === 'about') { ?>
														<!-- <div class="col-12">
															<hr class="my-2">
														</div> -->
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-1 text-dark">About Page CMS</div>
																<div class="text-title-gray">Update About page sections without
																	changing the design.</div>
															</div>
														</div>

														<div class="col-12">
															<h4 class="mb-2">Our Story</h4>
														</div>
														<div class="col-lg-4">
															<label class="form-label">Subtitle</label>
															<input class="form-control" name="about_story_subtitle"
																value="<?php echo htmlspecialchars((string) ($about['about_story_subtitle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">Title</label>
															<input class="form-control" name="about_story_title"
																value="<?php echo htmlspecialchars((string) ($about['about_story_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-12">
															<label class="form-label">Paragraph 1</label>
															<textarea class="form-control" name="about_story_p1"
																rows="3"><?php echo htmlspecialchars((string) ($about['about_story_p1'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-12">
															<label class="form-label">Paragraph 2</label>
															<textarea class="form-control" name="about_story_p2"
																rows="3"><?php echo htmlspecialchars((string) ($about['about_story_p2'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-12">
															<label class="form-label">Paragraph 3</label>
															<textarea class="form-control" name="about_story_p3"
																rows="2"><?php echo htmlspecialchars((string) ($about['about_story_p3'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-lg-6">
															<label class="form-label">Story image 1 (upload)</label>
															<input type="hidden" name="about_story_img1"
																value="<?php echo htmlspecialchars((string) ($about['about_story_img1'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															<input class="form-control" type="file" name="about_story_img1_file"
																accept="image/jpeg,image/png,image/webp">
															<?php if (trim((string) ($about['about_story_img1'] ?? '')) !== '') { ?>
																<?php $p = (string) ($about['about_story_img1'] ?? '');
																$p = preg_match('#^https?://#i', $p) ? $p : ('../' . ltrim($p, '/')); ?>
																<div class="mt-2">
																	<img src="<?php echo htmlspecialchars($p, ENT_QUOTES, 'UTF-8'); ?>"
																		alt="Preview"
																		style="width:100%;max-width:520px;height:160px;object-fit:cover;border-radius:14px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																</div>
															<?php } ?>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Story image 2 (upload)</label>
															<input type="hidden" name="about_story_img2"
																value="<?php echo htmlspecialchars((string) ($about['about_story_img2'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															<input class="form-control" type="file" name="about_story_img2_file"
																accept="image/jpeg,image/png,image/webp">
															<?php if (trim((string) ($about['about_story_img2'] ?? '')) !== '') { ?>
																<?php $p = (string) ($about['about_story_img2'] ?? '');
																$p = preg_match('#^https?://#i', $p) ? $p : ('../' . ltrim($p, '/')); ?>
																<div class="mt-2">
																	<img src="<?php echo htmlspecialchars($p, ENT_QUOTES, 'UTF-8'); ?>"
																		alt="Preview"
																		style="width:100%;max-width:520px;height:160px;object-fit:cover;border-radius:14px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																</div>
															<?php } ?>
														</div>

														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-12">
															<h4 class="mb-2">Vision &amp; Mission</h4>
														</div>
														<div class="col-12">
															<label class="form-label">Section title</label>
															<input class="form-control" name="about_vm_title"
																value="<?php echo htmlspecialchars((string) ($about['about_vm_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Vision text</label>
															<textarea class="form-control" name="about_vision_desc"
																rows="2"><?php echo htmlspecialchars((string) ($about['about_vision_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Mission text</label>
															<textarea class="form-control" name="about_mission_desc"
																rows="2"><?php echo htmlspecialchars((string) ($about['about_mission_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-12">
															<h4 class="mb-2">Core Values</h4>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Subtitle</label>
															<input class="form-control" name="about_values_subtitle"
																value="<?php echo htmlspecialchars((string) ($about['about_values_subtitle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Title</label>
															<input class="form-control" name="about_values_title"
																value="<?php echo htmlspecialchars((string) ($about['about_values_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Value 1 title</label>
															<input class="form-control" name="about_value1_title"
																value="<?php echo htmlspecialchars((string) ($about['about_value1_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">Value 1 description</label>
															<input class="form-control" name="about_value1_desc"
																value="<?php echo htmlspecialchars((string) ($about['about_value1_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Value 2 title</label>
															<input class="form-control" name="about_value2_title"
																value="<?php echo htmlspecialchars((string) ($about['about_value2_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">Value 2 description</label>
															<input class="form-control" name="about_value2_desc"
																value="<?php echo htmlspecialchars((string) ($about['about_value2_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Value 3 title</label>
															<input class="form-control" name="about_value3_title"
																value="<?php echo htmlspecialchars((string) ($about['about_value3_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-8">
															<label class="form-label">Value 3 description</label>
															<input class="form-control" name="about_value3_desc"
																value="<?php echo htmlspecialchars((string) ($about['about_value3_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>

														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-12">
															<h4 class="mb-2">Leadership</h4>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Subtitle</label>
															<input class="form-control" name="about_leadership_subtitle"
																value="<?php echo htmlspecialchars((string) ($about['about_leadership_subtitle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Title</label>
															<input class="form-control" name="about_leadership_title"
																value="<?php echo htmlspecialchars((string) ($about['about_leadership_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Leader 1 name</label>
															<input class="form-control" name="about_leader1_name"
																value="<?php echo htmlspecialchars((string) ($about['about_leader1_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Leader 1 role</label>
															<input class="form-control" name="about_leader1_role"
																value="<?php echo htmlspecialchars((string) ($about['about_leader1_role'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Leader 1 photo (upload)</label>
															<input type="hidden" name="about_leader1_photo"
																value="<?php echo htmlspecialchars((string) ($about['about_leader1_photo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															<input class="form-control" type="file" name="about_leader1_photo_file"
																accept="image/jpeg,image/png,image/webp">
															<?php if (trim((string) ($about['about_leader1_photo'] ?? '')) !== '') { ?>
																<?php $p = (string) ($about['about_leader1_photo'] ?? '');
																$p = preg_match('#^https?://#i', $p) ? $p : ('../' . ltrim($p, '/')); ?>
																<div class="mt-2">
																	<img src="<?php echo htmlspecialchars($p, ENT_QUOTES, 'UTF-8'); ?>"
																		alt="Preview"
																		style="width:140px;height:140px;object-fit:cover;border-radius:16px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																</div>
															<?php } ?>
														</div>
														<div class="col-lg-4">
															<label class="form-label">Leader 2 name</label>
															<input class="form-control" name="about_leader2_name"
																value="<?php echo htmlspecialchars((string) ($about['about_leader2_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Leader 2 role</label>
															<input class="form-control" name="about_leader2_role"
																value="<?php echo htmlspecialchars((string) ($about['about_leader2_role'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-4">
															<label class="form-label">Leader 2 photo (upload)</label>
															<input type="hidden" name="about_leader2_photo"
																value="<?php echo htmlspecialchars((string) ($about['about_leader2_photo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															<input class="form-control" type="file" name="about_leader2_photo_file"
																accept="image/jpeg,image/png,image/webp">
															<?php if (trim((string) ($about['about_leader2_photo'] ?? '')) !== '') { ?>
																<?php $p = (string) ($about['about_leader2_photo'] ?? '');
																$p = preg_match('#^https?://#i', $p) ? $p : ('../' . ltrim($p, '/')); ?>
																<div class="mt-2">
																	<img src="<?php echo htmlspecialchars($p, ENT_QUOTES, 'UTF-8'); ?>"
																		alt="Preview"
																		style="width:140px;height:140px;object-fit:cover;border-radius:16px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																</div>
															<?php } ?>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Founder message subtitle</label>
															<input class="form-control" name="about_founder_subtitle"
																value="<?php echo htmlspecialchars((string) ($about['about_founder_subtitle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Founder message quote</label>
															<textarea class="form-control" name="about_founder_quote"
																rows="2"><?php echo htmlspecialchars((string) ($about['about_founder_quote'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>

														<div class="col-12">
															<hr class="my-2">
														</div>
														<div class="col-12">
															<h4 class="mb-2">Milestones</h4>
														</div>
														<div class="col-lg-6">
															<label class="form-label">Subtitle</label>
															<input class="form-control" name="about_history_subtitle"
																value="<?php echo htmlspecialchars((string) ($about['about_history_subtitle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="col-lg-6">
															<label class="form-label">Title</label>
															<input class="form-control" name="about_history_title"
																value="<?php echo htmlspecialchars((string) ($about['about_history_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
														</div>

														<?php for ($i = 1; $i <= 6; $i++) { ?>
															<div class="col-12">
																<div class="p-3 rounded-3 border bg-light">
																	<div class="fw-bold mb-1 text-dark">Milestone
																		<?php echo (int) $i; ?></div>
																</div>
															</div>
															<div class="col-lg-2">
																<label class="form-label">Year/Step</label>
																<input class="form-control"
																	name="about_history<?php echo (int) $i; ?>_year"
																	value="<?php echo htmlspecialchars((string) ($about['about_history' . $i . '_year'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-5">
																<label class="form-label">Title</label>
																<input class="form-control"
																	name="about_history<?php echo (int) $i; ?>_title"
																	value="<?php echo htmlspecialchars((string) ($about['about_history' . $i . '_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-5">
																<label class="form-label">Description</label>
																<input class="form-control"
																	name="about_history<?php echo (int) $i; ?>_desc"
																	value="<?php echo htmlspecialchars((string) ($about['about_history' . $i . '_desc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">Image (upload)</label>
																<?php $key = 'about_history' . $i . '_image'; ?>
																<input type="hidden" name="about_history<?php echo (int) $i; ?>_image"
																	value="<?php echo htmlspecialchars((string) ($about[$key] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
																<input class="form-control" type="file"
																	name="about_history<?php echo (int) $i; ?>_image_file"
																	accept="image/jpeg,image/png,image/webp">
																<?php
																if (trim((string) ($about[$key] ?? '')) !== '') {
																	$p = (string) ($about[$key] ?? '');
																	$p = preg_match('#^https?://#i', $p) ? $p : ('../' . ltrim($p, '/'));
																	?>
																	<div class="mt-2">
																		<img src="<?php echo htmlspecialchars($p, ENT_QUOTES, 'UTF-8'); ?>"
																			alt="Preview"
																			style="width:100%;max-width:520px;height:160px;object-fit:cover;border-radius:14px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																	</div>
																<?php } ?>
															</div>
														<?php } ?>
													<?php } ?>
												</div>
											</div>

											<div class="tab-pane fade <?php echo $sr_tab === 'banner' ? 'show active' : ''; ?>"
												id="srPageTabBanner" role="tabpanel">
												<div class="row g-3">
													<div class="col-12">
														<label class="form-label">Banner title</label>
														<input class="form-control" name="hero_title"
															value="<?php echo htmlspecialchars($editing['hero_title'], ENT_QUOTES, 'UTF-8'); ?>">
													</div>
													<div class="col-12">
														<label class="form-label">Banner description</label>
														<textarea class="form-control" name="hero_subtitle"
															rows="3"><?php echo htmlspecialchars($editing['hero_subtitle'], ENT_QUOTES, 'UTF-8'); ?></textarea>
													</div>
													<div class="col-12">
														<input type="hidden" name="banner_image"
															value="<?php echo htmlspecialchars($editing['banner_image'], ENT_QUOTES, 'UTF-8'); ?>">
														<label class="form-label">Upload banner image</label>
														<input class="form-control" type="file" name="banner_image_file"
															accept="image/jpeg,image/png,image/webp">
														<div class="form-text">This image is used in the page title bar
															background.</div>
													</div>
													<?php if (trim((string) $editing['banner_image']) !== '') { ?>
														<?php $sr_banner_preview = (string) $editing['banner_image'];
														$sr_banner_preview = preg_match('#^https?://#i', $sr_banner_preview) ? $sr_banner_preview : ('../' . ltrim($sr_banner_preview, '/')); ?>
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-2">Current banner preview</div>
																<img src="<?php echo htmlspecialchars($sr_banner_preview, ENT_QUOTES, 'UTF-8'); ?>"
																	alt="Banner preview"
																	style="width:100%;max-width:680px;height:220px;object-fit:cover;border-radius:16px;border:1px solid rgba(10,25,38,.12);background:#fff;">
															</div>
														</div>
													<?php } ?>
													<div class="col-12">
														<div class="p-3 rounded-3 border bg-light">
															<div class="fw-bold mb-1 text-dark">Tip</div>
															<div class="text-title-gray">Best size: 1920×500 (or wider). Keep
																text off the edges.</div>
														</div>
													</div>
												</div>
											</div>

											<?php if ($editing['slug'] === 'blog') { ?>
												<div class="tab-pane fade <?php echo $sr_tab === 'faqs' ? 'show active' : ''; ?>"
													id="srPageTabFaqs" role="tabpanel">
													<div class="row g-4">
														<div class="col-12">
															<div class="p-3 rounded-3 border bg-light">
																<div class="fw-bold mb-1 text-dark">Blog FAQs</div>
																<div class="text-title-gray">Create and manage Blog FAQs. These are shown on the Blog page FAQ section.</div>
															</div>
														</div>

														<div class="col-12">
															<div class="p-3 rounded-3 border">
																<form method="post" action="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=faqs">
																	<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																	<input type="hidden" name="op" value="blog_faq_save">
																	<input type="hidden" name="page_id" value="<?php echo (int) $editing['id']; ?>">
																	<input type="hidden" name="faq_id" value="<?php echo (int) ($editingFaq['id'] ?? 0); ?>">

																	<div class="row g-3 align-items-end">
																		<div class="col-lg-8">
																			<label class="form-label">Question</label>
																			<input class="form-control" name="faq_question" value="<?php echo htmlspecialchars((string) ($editingFaq['question'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" required>
																		</div>
																		<div class="col-lg-2">
																			<label class="form-label">Sort order</label>
																			<input class="form-control" type="number" name="faq_sort_order" value="<?php echo (int) ($editingFaq['sort_order'] ?? 0); ?>">
																		</div>
																		<div class="col-lg-2">
																			<div class="form-check mt-4">
																				<input class="form-check-input" type="checkbox" id="srFaqActive" name="faq_is_active" <?php echo ((int) ($editingFaq['is_active'] ?? 1) === 1) ? 'checked' : ''; ?>>
																				<label class="form-check-label" for="srFaqActive">Active</label>
																			</div>
																		</div>
																		<div class="col-12">
																			<label class="form-label">Answer</label>
																			<textarea class="form-control" name="faq_answer" rows="4" required><?php echo htmlspecialchars((string) ($editingFaq['answer'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
																		</div>
																		<div class="col-12 d-flex flex-wrap gap-2 justify-content-end">
																			<?php if (!empty($editingFaq)) { ?>
																				<a class="btn btn-outline-primary" href="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=faqs">Clear</a>
																			<?php } ?>
																			<button type="submit" class="btn btn-primary"><?php echo !empty($editingFaq) ? 'Update FAQ' : 'Add FAQ'; ?></button>
																		</div>
																	</div>
																</form>
															</div>
														</div>

														<div class="col-12">
															<div class="table-responsive">
																<table class="table table-striped mb-0">
																	<thead>
																		<tr>
																			<th style="width: 70px;">#</th>
																			<th>Question</th>
																			<th style="width: 110px;">Active</th>
																			<th style="width: 120px;">Sort</th>
																			<th style="width: 220px;">Actions</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php if ($blogFaqs) { ?>
																			<?php foreach ($blogFaqs as $idx => $f) { ?>
																				<tr>
																					<td><?php echo (int) ($idx + 1); ?></td>
																					<td><?php echo htmlspecialchars((string) ($f['question'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
																					<td><?php echo ((int) ($f['is_active'] ?? 0) === 1) ? 'Yes' : 'No'; ?></td>
																					<td><?php echo (int) ($f['sort_order'] ?? 0); ?></td>
																					<td>
																						<div class="d-inline-flex gap-2">
																							<a class="btn btn-sm btn-outline-primary" href="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=faqs&faq_id=<?php echo (int) ($f['id'] ?? 0); ?>">Edit</a>
																							<form method="post" action="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=faqs" onsubmit="return confirm('Delete this FAQ?');">
																								<input type="hidden" name="csrf" value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
																								<input type="hidden" name="op" value="blog_faq_delete">
																								<input type="hidden" name="page_id" value="<?php echo (int) $editing['id']; ?>">
																								<input type="hidden" name="faq_id" value="<?php echo (int) ($f['id'] ?? 0); ?>">
																								<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
																							</form>
																						</div>
																					</td>
																				</tr>
																			<?php } ?>
																		<?php } else { ?>
																			<tr>
																				<td colspan="5" class="text-center text-muted">No FAQs yet.</td>
																			</tr>
																		<?php } ?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											<?php } ?>
										<?php } ?>

										<?php if ($editing['slug'] === 'home') { ?>
											<div class="tab-pane fade <?php echo $sr_tab === 'slider' ? 'show active' : ''; ?>"
												id="srPageTabSlider" role="tabpanel">
												<div class="row g-4">
													<div class="col-12">
														<div class="p-3 rounded-3 border bg-light">
															<div class="fw-bold mb-1 text-dark">Home Slider</div>
															<div class="text-title-gray">Add multiple slides (image + title +
																description + CTA buttons).</div>
														</div>
													</div>

													<div class="col-12">
														<div class="p-3 rounded-3 border">
															<input type="hidden" form="srHomeSlideSaveForm" name="csrf"
																value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
															<input type="hidden" form="srHomeSlideSaveForm" name="op"
																value="home_slide_save">
															<input type="hidden" form="srHomeSlideSaveForm" name="page_id"
																value="<?php echo (int) $editing['id']; ?>">
															<input type="hidden" form="srHomeSlideSaveForm" name="slide_id"
																value="<?php echo (int) ($editingSlide['id'] ?? 0); ?>">
															<input type="hidden" form="srHomeSlideSaveForm"
																name="slide_image_existing"
																value="<?php echo htmlspecialchars((string) ($editingSlide['image'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

															<div class="row g-3">
																<div class="col-lg-6">
																	<label class="form-label">Slide title</label>
																	<input class="form-control" form="srHomeSlideSaveForm"
																		name="slide_title"
																		value="<?php echo htmlspecialchars((string) ($editingSlide['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
																		required>
																</div>
																<div class="col-lg-6">
																	<label class="form-label">Small label (optional)</label>
																	<input class="form-control" form="srHomeSlideSaveForm"
																		name="slide_kicker"
																		value="<?php echo htmlspecialchars((string) ($editingSlide['kicker'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-12">
																	<label class="form-label">Slide description</label>
																	<textarea class="form-control" form="srHomeSlideSaveForm"
																		name="slide_subtitle"
																		rows="3"><?php echo htmlspecialchars((string) ($editingSlide['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
																</div>
																<div class="col-lg-3">
																	<label class="form-label">Primary button label</label>
																	<input class="form-control" form="srHomeSlideSaveForm"
																		name="slide_primary_label"
																		value="<?php echo htmlspecialchars((string) ($editingSlide['primary_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-3">
																	<label class="form-label">Primary button URL</label>
																	<input class="form-control" form="srHomeSlideSaveForm"
																		name="slide_primary_url"
																		value="<?php echo htmlspecialchars((string) ($editingSlide['primary_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-3">
																	<label class="form-label">Secondary button label</label>
																	<input class="form-control" form="srHomeSlideSaveForm"
																		name="slide_secondary_label"
																		value="<?php echo htmlspecialchars((string) ($editingSlide['secondary_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-3">
																	<label class="form-label">Secondary button URL</label>
																	<input class="form-control" form="srHomeSlideSaveForm"
																		name="slide_secondary_url"
																		value="<?php echo htmlspecialchars((string) ($editingSlide['secondary_url'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-6">
																	<label class="form-label">Slide image (upload)</label>
																	<input class="form-control" form="srHomeSlideSaveForm"
																		type="file" name="slide_image"
																		accept="image/jpeg,image/png,image/webp" <?php echo $editingSlide ? '' : 'required'; ?>>
																</div>
																<div class="col-lg-3">
																	<label class="form-label">Sort order</label>
																	<input class="form-control" form="srHomeSlideSaveForm"
																		type="number" name="slide_sort_order"
																		value="<?php echo htmlspecialchars((string) ($editingSlide['sort_order'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-3 d-flex align-items-end">
																	<div class="form-check">
																		<input class="form-check-input"
																			form="srHomeSlideSaveForm" type="checkbox"
																			id="srSlideActive" name="slide_is_active" <?php echo ((int) ($editingSlide['is_active'] ?? 1) === 1) ? 'checked' : ''; ?>>
																		<label class="form-check-label"
																			for="srSlideActive">Active</label>
																	</div>
																</div>
																<?php if ($editingSlide && trim((string) ($editingSlide['image'] ?? '')) !== '') { ?>
																	<?php $sr_slide_preview = '../' . ltrim((string) $editingSlide['image'], '/'); ?>
																	<div class="col-12">
																		<div class="p-3 rounded-3 border bg-light">
																			<div class="fw-bold mb-2">Slide preview</div>
																			<img src="<?php echo htmlspecialchars($sr_slide_preview, ENT_QUOTES, 'UTF-8'); ?>"
																				alt="Slide preview"
																				style="width:100%;max-width:780px;height:240px;object-fit:cover;border-radius:18px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																		</div>
																	</div>
																<?php } ?>
																<div class="col-12 d-flex gap-2 flex-wrap">
																	<button type="submit" class="btn btn-primary"
																		form="srHomeSlideSaveForm"><?php echo $editingSlide ? 'Update Slide' : 'Add Slide'; ?></button>
																	<?php if ($editingSlide) { ?>
																		<a class="btn btn-outline-primary"
																			href="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=slider">Cancel</a>
																	<?php } ?>
																</div>
															</div>
														</div>
													</div>

													<div class="col-12">
														<div class="table-responsive">
															<table class="table table-striped mb-0">
																<thead>
																	<tr>
																		<th style="width:90px;">Image</th>
																		<th>Title</th>
																		<th>Status</th>
																		<th>Updated</th>
																		<th style="width:110px;">Sort</th>
																		<th class="text-end">Actions</th>
																	</tr>
																</thead>
																<tbody>
																	<?php if (!$homeSlides) { ?>
																		<tr>
																			<td colspan="6"
																				class="text-center text-title-gray py-4">No slides
																				yet. Add your first slide above.</td>
																		</tr>
																	<?php } ?>
																	<?php foreach ($homeSlides as $s) { ?>
																		<?php
																		$sid = (int) ($s['id'] ?? 0);
																		$img = trim((string) ($s['image'] ?? ''));
																		$img = $img !== '' ? ('../' . ltrim($img, '/')) : '';
																		$st = (int) ($s['is_active'] ?? 0) === 1 ? 'Active' : 'Hidden';
																		$badge = (int) ($s['is_active'] ?? 0) === 1 ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary';
																		?>
																		<tr>
																			<td>
																				<?php if ($img !== '') { ?>
																					<img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>"
																						alt=""
																						style="width:76px;height:54px;object-fit:cover;border-radius:10px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																				<?php } ?>
																			</td>
																			<td class="fw-bold">
																				<?php echo htmlspecialchars((string) ($s['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
																			</td>
																			<td><span
																					class="badge rounded-pill <?php echo $badge; ?>"><?php echo htmlspecialchars($st, ENT_QUOTES, 'UTF-8'); ?></span>
																			</td>
																			<td class="text-title-gray">
																				<?php echo htmlspecialchars((string) ($s['updated_at'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
																			</td>
																			<td><?php echo htmlspecialchars((string) ($s['sort_order'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?>
																			</td>
																			<td class="text-end">
																				<a class="btn btn-sm btn-outline-primary"
																					href="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=slider&slide_id=<?php echo $sid; ?>">Edit</a>
																				<button type="submit"
																					class="btn btn-sm btn-outline-danger"
																					form="srHomeSlideDeleteForm" name="slide_id"
																					value="<?php echo $sid; ?>"
																					onclick="return confirm('Delete this slide?');">Delete</button>
																			</td>
																		</tr>
																	<?php } ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>

											<div class="tab-pane fade <?php echo $sr_tab === 'testimonials' ? 'show active' : ''; ?>"
												id="srPageTabTestimonials" role="tabpanel">
												<div class="row g-4">
													<div class="col-12">
														<div class="p-3 rounded-3 border bg-light">
															<div class="fw-bold mb-1 text-dark">Testimonials</div>
															<div class="text-title-gray">Add, edit, or remove client
																testimonials shown on the homepage.</div>
														</div>
													</div>

													<div class="col-12">
														<div class="p-3 rounded-3 border">
															<input type="hidden" form="srHomeTestimonialSaveForm" name="csrf"
																value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
															<input type="hidden" form="srHomeTestimonialSaveForm" name="op"
																value="home_testimonial_save">
															<input type="hidden" form="srHomeTestimonialSaveForm" name="page_id"
																value="<?php echo (int) $editing['id']; ?>">
															<input type="hidden" form="srHomeTestimonialSaveForm" name="test_id"
																value="<?php echo (int) ($editingTestimonial['id'] ?? 0); ?>">
															<input type="hidden" form="srHomeTestimonialSaveForm"
																name="test_image_existing"
																value="<?php echo htmlspecialchars((string) ($editingTestimonial['image'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">

															<div class="row g-3">
																<div class="col-12">
																	<label class="form-label">Section title</label>
																	<input class="form-control" form="srHomeTestimonialSaveForm"
																		name="home_testimonial_title"
																		value="<?php echo htmlspecialchars((string) $homeTestimonialTitle, ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-4">
																	<label class="form-label">Client name</label>
																	<input class="form-control" form="srHomeTestimonialSaveForm"
																		name="test_name"
																		value="<?php echo htmlspecialchars((string) ($editingTestimonial['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
																		required>
																</div>
																<div class="col-lg-5">
																	<label class="form-label">Company</label>
																	<input class="form-control" form="srHomeTestimonialSaveForm"
																		name="test_company"
																		value="<?php echo htmlspecialchars((string) ($editingTestimonial['company'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-3">
																	<label class="form-label">Rating</label>
																	<select class="form-select" form="srHomeTestimonialSaveForm"
																		name="test_rating">
																		<?php $r = (int) ($editingTestimonial['rating'] ?? 5);
																		if ($r < 1)
																			$r = 1;
																		if ($r > 5)
																			$r = 5; ?>
																		<?php for ($i = 5; $i >= 1; $i--) { ?>
																			<option value="<?php echo $i; ?>" <?php echo $r === $i ? 'selected' : ''; ?>><?php echo $i; ?> stars</option>
																		<?php } ?>
																	</select>
																</div>
																<div class="col-12">
																	<label class="form-label">Quote</label>
																	<textarea class="form-control"
																		form="srHomeTestimonialSaveForm" name="test_quote"
																		rows="3"
																		required><?php echo htmlspecialchars((string) ($editingTestimonial['quote'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
																</div>
																<div class="col-lg-6">
																	<label class="form-label">Client image (upload)</label>
																	<input class="form-control" form="srHomeTestimonialSaveForm"
																		type="file" name="test_image"
																		accept="image/jpeg,image/png,image/webp" <?php echo $editingTestimonial ? '' : 'required'; ?>>
																</div>
																<div class="col-lg-3">
																	<label class="form-label">Sort order</label>
																	<input class="form-control" form="srHomeTestimonialSaveForm"
																		type="number" name="test_sort_order"
																		value="<?php echo htmlspecialchars((string) ($editingTestimonial['sort_order'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-3 d-flex align-items-end">
																	<div class="form-check">
																		<input class="form-check-input"
																			form="srHomeTestimonialSaveForm" type="checkbox"
																			id="srTestActive" name="test_is_active" <?php echo ((int) ($editingTestimonial['is_active'] ?? 1) === 1) ? 'checked' : ''; ?>>
																		<label class="form-check-label"
																			for="srTestActive">Active</label>
																	</div>
																</div>
																<?php if ($editingTestimonial && trim((string) ($editingTestimonial['image'] ?? '')) !== '') { ?>
																	<?php $sr_test_prev = '../' . ltrim((string) $editingTestimonial['image'], '/'); ?>
																	<div class="col-12">
																		<div class="p-3 rounded-3 border bg-light">
																			<div class="fw-bold mb-2 text-dark">Image preview</div>
																			<img src="<?php echo htmlspecialchars($sr_test_prev, ENT_QUOTES, 'UTF-8'); ?>"
																				alt="Preview"
																				style="width:140px;height:140px;object-fit:cover;border-radius:18px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																		</div>
																	</div>
																<?php } ?>
																<div class="col-12 d-flex gap-2 flex-wrap">
																	<button type="submit" class="btn btn-primary"
																		form="srHomeTestimonialSaveForm"><?php echo $editingTestimonial ? 'Update Testimonial' : 'Add Testimonial'; ?></button>
																	<?php if ($editingTestimonial) { ?>
																		<a class="btn btn-outline-primary"
																			href="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=testimonials">Cancel</a>
																	<?php } ?>
																</div>
															</div>
														</div>
													</div>

													<div class="col-12">
														<div class="table-responsive">
															<table class="table table-striped mb-0">
																<thead>
																	<tr>
																		<th style="width:90px;">Image</th>
																		<th>Name</th>
																		<th>Company</th>
																		<th>Rating</th>
																		<th>Status</th>
																		<th style="width:110px;">Sort</th>
																		<th class="text-end">Actions</th>
																	</tr>
																</thead>
																<tbody>
																	<?php if (!$homeTestimonials) { ?>
																		<tr>
																			<td colspan="7"
																				class="text-center text-title-gray py-4">No
																				testimonials yet. Add your first testimonial above.
																			</td>
																		</tr>
																	<?php } ?>
																	<?php foreach ($homeTestimonials as $t) { ?>
																		<?php
																		$tid = (int) ($t['id'] ?? 0);
																		$img = trim((string) ($t['image'] ?? ''));
																		$img = $img !== '' ? ('../' . ltrim($img, '/')) : '';
																		$st = (int) ($t['is_active'] ?? 0) === 1 ? 'Active' : 'Hidden';
																		$badge = (int) ($t['is_active'] ?? 0) === 1 ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary';
																		$rating = (int) ($t['rating'] ?? 5);
																		if ($rating < 1)
																			$rating = 1;
																		if ($rating > 5)
																			$rating = 5;
																		?>
																		<tr>
																			<td><?php if ($img !== '') { ?><img
																						src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>"
																						alt=""
																						style="width:64px;height:64px;object-fit:cover;border-radius:14px;border:1px solid rgba(10,25,38,.12);background:#fff;"><?php } ?>
																			</td>
																			<td class="fw-bold">
																				<?php echo htmlspecialchars((string) ($t['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
																			</td>
																			<td><?php echo htmlspecialchars((string) ($t['company'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
																			</td>
																			<td><?php echo str_repeat('★', $rating); ?></td>
																			<td><span
																					class="badge rounded-pill <?php echo $badge; ?>"><?php echo htmlspecialchars($st, ENT_QUOTES, 'UTF-8'); ?></span>
																			</td>
																			<td><?php echo htmlspecialchars((string) ($t['sort_order'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?>
																			</td>
																			<td class="text-end">
																				<a class="btn btn-sm btn-outline-primary"
																					href="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=testimonials&test_id=<?php echo $tid; ?>">Edit</a>
																				<button type="submit"
																					class="btn btn-sm btn-outline-danger"
																					form="srHomeTestimonialDeleteForm"
																					name="test_id" value="<?php echo $tid; ?>"
																					onclick="return confirm('Delete this testimonial?');">Delete</button>
																			</td>
																		</tr>
																	<?php } ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>

											<div class="tab-pane fade <?php echo $sr_tab === 'home' ? 'show active' : ''; ?>"
												id="srPageTabHome" role="tabpanel">
												<div class="row g-4">
													<div class="col-12">
														<div class="p-3 rounded-3 border bg-light">
															<div class="fw-bold mb-1 text-dark">Home CMS</div>
															<div class="text-title-gray">Edit homepage section titles,
																descriptions, counters, and media without changing the design.
															</div>
														</div>
													</div>

													<div class="col-12">
														<h4 class="mb-3">Trust Bar (4 stats)</h4>
														<div class="row g-3">
															<div class="col-lg-6">
																<label class="form-label">Stat 1 title (HTML allowed:
																	&lt;br&gt;)</label>
																<input class="form-control" name="home_stat1_title"
																	value="<?php echo htmlspecialchars($home['home_stat1_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Stat 1 value</label>
																<input class="form-control" name="home_stat1_to"
																	value="<?php echo htmlspecialchars($home['home_stat1_to'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Stat 1 suffix</label>
																<input class="form-control" name="home_stat1_suffix"
																	value="<?php echo htmlspecialchars($home['home_stat1_suffix'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>

															<div class="col-lg-6">
																<label class="form-label">Stat 2 title (HTML allowed:
																	&lt;br&gt;)</label>
																<input class="form-control" name="home_stat2_title"
																	value="<?php echo htmlspecialchars($home['home_stat2_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Stat 2 value</label>
																<input class="form-control" name="home_stat2_to"
																	value="<?php echo htmlspecialchars($home['home_stat2_to'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Stat 2 suffix</label>
																<input class="form-control" name="home_stat2_suffix"
																	value="<?php echo htmlspecialchars($home['home_stat2_suffix'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>

															<div class="col-lg-6">
																<label class="form-label">Stat 3 title (HTML allowed:
																	&lt;br&gt;)</label>
																<input class="form-control" name="home_stat3_title"
																	value="<?php echo htmlspecialchars($home['home_stat3_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Stat 3 value</label>
																<input class="form-control" name="home_stat3_to"
																	value="<?php echo htmlspecialchars($home['home_stat3_to'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Stat 3 suffix</label>
																<input class="form-control" name="home_stat3_suffix"
																	value="<?php echo htmlspecialchars($home['home_stat3_suffix'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>

															<div class="col-lg-6">
																<label class="form-label">Stat 4 title (HTML allowed:
																	&lt;br&gt;)</label>
																<input class="form-control" name="home_stat4_title"
																	value="<?php echo htmlspecialchars($home['home_stat4_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Stat 4 value</label>
																<input class="form-control" name="home_stat4_to"
																	value="<?php echo htmlspecialchars($home['home_stat4_to'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Stat 4 suffix</label>
																<input class="form-control" name="home_stat4_suffix"
																	value="<?php echo htmlspecialchars($home['home_stat4_suffix'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
														</div>
													</div>
													<hr>
													<div class="col-12">
														<h4 class="mb-3">Section Headings</h4>
														<div class="row g-3">
															<div class="col-lg-6">
																<label class="form-label">Services subtitle</label>
																<input class="form-control" name="home_services_subtitle"
																	value="<?php echo htmlspecialchars($home['home_services_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">Services title</label>
																<input class="form-control" name="home_services_title"
																	value="<?php echo htmlspecialchars($home['home_services_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">Services CTA label</label>
																<input class="form-control" name="home_services_cta_label"
																	value="<?php echo htmlspecialchars($home['home_services_cta_label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>

															<div class="col-lg-6">
																<label class="form-label">Products subtitle</label>
																<input class="form-control" name="home_products_subtitle"
																	value="<?php echo htmlspecialchars($home['home_products_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">Products title</label>
																<input class="form-control" name="home_products_title"
																	value="<?php echo htmlspecialchars($home['home_products_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">Products CTA label</label>
																<input class="form-control" name="home_products_cta_label"
																	value="<?php echo htmlspecialchars($home['home_products_cta_label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>

															<div class="col-lg-6">
																<label class="form-label">Blog subtitle</label>
																<input class="form-control" name="home_blog_subtitle"
																	value="<?php echo htmlspecialchars($home['home_blog_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">Blog title</label>
																<input class="form-control" name="home_blog_title"
																	value="<?php echo htmlspecialchars($home['home_blog_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">Blog CTA label</label>
																<input class="form-control" name="home_blog_cta_label"
																	value="<?php echo htmlspecialchars($home['home_blog_cta_label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
														</div>
													</div>
													<hr>
													<div class="col-12">
														<h4 class="mb-3">About Section</h4>
														<div class="row g-3">
															<div class="col-lg-6">
																<label class="form-label">About subtitle</label>
																<input class="form-control" name="home_about_subtitle"
																	value="<?php echo htmlspecialchars($home['home_about_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">About title</label>
																<input class="form-control" name="home_about_title"
																	value="<?php echo htmlspecialchars($home['home_about_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-12">
																<label class="form-label">Paragraph 1</label>
																<textarea class="form-control" rows="3"
																	name="home_about_p1"><?php echo htmlspecialchars($home['home_about_p1'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
															</div>
															<div class="col-12">
																<label class="form-label">Paragraph 2</label>
																<textarea class="form-control" rows="3"
																	name="home_about_p2"><?php echo htmlspecialchars($home['home_about_p2'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
															</div>
															<div class="col-lg-6">
																<label class="form-label">Bullet 1</label>
																<input class="form-control" name="home_about_b1"
																	value="<?php echo htmlspecialchars($home['home_about_b1'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">Bullet 2</label>
																<input class="form-control" name="home_about_b2"
																	value="<?php echo htmlspecialchars($home['home_about_b2'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">CTA label</label>
																<input class="form-control" name="home_about_cta_label"
																	value="<?php echo htmlspecialchars($home['home_about_cta_label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">CTA URL</label>
																<input class="form-control" name="home_about_cta_url"
																	value="<?php echo htmlspecialchars($home['home_about_cta_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-12">
																<input type="hidden" name="home_about_bg_image"
																	value="<?php echo htmlspecialchars($home['home_about_bg_image'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
																<label class="form-label">Left background image (upload)</label>
																<input class="form-control" type="file"
																	name="home_about_bg_image_file"
																	accept="image/jpeg,image/png,image/webp">
																<?php if (trim((string) ($home['home_about_bg_image'] ?? '')) !== '') { ?>
																	<?php $sr_prev = '../' . ltrim((string) $home['home_about_bg_image'], '/'); ?>
																	<div class="mt-2">
																		<img src="<?php echo htmlspecialchars($sr_prev, ENT_QUOTES, 'UTF-8'); ?>"
																			alt="Preview"
																			style="width:100%;max-width:520px;height:160px;object-fit:cover;border-radius:16px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																	</div>
																<?php } ?>
															</div>

															<div class="col-lg-4">
																<label class="form-label">Right counter value</label>
																<input class="form-control" name="home_about_fid_to"
																	value="<?php echo htmlspecialchars($home['home_about_fid_to'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-4">
																<label class="form-label">Right counter suffix</label>
																<input class="form-control" name="home_about_fid_suffix"
																	value="<?php echo htmlspecialchars($home['home_about_fid_suffix'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-4">
																<label class="form-label">Right counter title</label>
																<input class="form-control" name="home_about_fid_title"
																	value="<?php echo htmlspecialchars($home['home_about_fid_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>

															<div class="col-lg-4">
																<label class="form-label">Timeline 1 title</label>
																<input class="form-control" name="home_about_timeline1_title"
																	value="<?php echo htmlspecialchars($home['home_about_timeline1_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Timeline 1 desc</label>
																<input class="form-control" name="home_about_timeline1_desc"
																	value="<?php echo htmlspecialchars($home['home_about_timeline1_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-4">
																<label class="form-label">Timeline 2 title</label>
																<input class="form-control" name="home_about_timeline2_title"
																	value="<?php echo htmlspecialchars($home['home_about_timeline2_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Timeline 2 desc</label>
																<input class="form-control" name="home_about_timeline2_desc"
																	value="<?php echo htmlspecialchars($home['home_about_timeline2_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-4">
																<label class="form-label">Timeline 3 title</label>
																<input class="form-control" name="home_about_timeline3_title"
																	value="<?php echo htmlspecialchars($home['home_about_timeline3_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Timeline 3 desc</label>
																<input class="form-control" name="home_about_timeline3_desc"
																	value="<?php echo htmlspecialchars($home['home_about_timeline3_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
														</div>
													</div>
													<hr>
													<div class="col-12">
														<h4 class="mb-3">Why Choose Us</h4>
														<div class="row g-3">
															<div class="col-lg-4">
																<label class="form-label">Subtitle</label>
																<input class="form-control" name="home_why_subtitle"
																	value="<?php echo htmlspecialchars($home['home_why_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Title (HTML allowed:
																	&lt;br&gt;)</label>
																<input class="form-control" name="home_why_title"
																	value="<?php echo htmlspecialchars($home['home_why_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<?php for ($c = 1; $c <= 6; $c++) { ?>
																<div class="col-lg-4">
																	<label class="form-label">Card <?php echo $c; ?> title</label>
																	<input class="form-control"
																		name="home_why_card<?php echo $c; ?>_title"
																		value="<?php echo htmlspecialchars($home['home_why_card' . $c . '_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-8">
																	<label class="form-label">Card <?php echo $c; ?> desc</label>
																	<input class="form-control"
																		name="home_why_card<?php echo $c; ?>_desc"
																		value="<?php echo htmlspecialchars($home['home_why_card' . $c . '_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
																</div>
															<?php } ?>
														</div>
													</div>
													<hr>
													<div class="col-12">
														<h4 class="mb-3">Why Shivanjali (3 highlights)</h4>
														<div class="row g-3">
															<div class="col-12">
																<label class="form-label">Section title</label>
																<input class="form-control" name="home_why_sr_title"
																	value="<?php echo htmlspecialchars($home['home_why_sr_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-4">
																<label class="form-label">Highlight 1 title</label>
																<input class="form-control" name="home_why_sr_1_title"
																	value="<?php echo htmlspecialchars($home['home_why_sr_1_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Highlight 1 desc</label>
																<input class="form-control" name="home_why_sr_1_desc"
																	value="<?php echo htmlspecialchars($home['home_why_sr_1_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-4">
																<label class="form-label">Highlight 2 title</label>
																<input class="form-control" name="home_why_sr_2_title"
																	value="<?php echo htmlspecialchars($home['home_why_sr_2_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Highlight 2 desc</label>
																<input class="form-control" name="home_why_sr_2_desc"
																	value="<?php echo htmlspecialchars($home['home_why_sr_2_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-4">
																<label class="form-label">Highlight 3 title</label>
																<input class="form-control" name="home_why_sr_3_title"
																	value="<?php echo htmlspecialchars($home['home_why_sr_3_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-8">
																<label class="form-label">Highlight 3 desc</label>
																<input class="form-control" name="home_why_sr_3_desc"
																	value="<?php echo htmlspecialchars($home['home_why_sr_3_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
														</div>
													</div>
													<hr>
													<div class="col-12">
														<h4 class="mb-3">Process (4 steps)</h4>
														<div class="row g-3">
															<div class="col-lg-6">
																<label class="form-label">Process subtitle</label>
																<input class="form-control" name="home_process_subtitle"
																	value="<?php echo htmlspecialchars($home['home_process_subtitle'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-6">
																<label class="form-label">Process title (HTML allowed:
																	&lt;br&gt;)</label>
																<input class="form-control" name="home_process_title"
																	value="<?php echo htmlspecialchars($home['home_process_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>

															<?php for ($i = 1; $i <= 4; $i++) { ?>
																<div class="col-lg-3">
																	<label class="form-label">Step <?php echo $i; ?> title</label>
																	<input class="form-control"
																		name="home_process_<?php echo $i; ?>_title"
																		value="<?php echo htmlspecialchars($home['home_process_' . $i . '_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-6">
																	<label class="form-label">Step <?php echo $i; ?> desc</label>
																	<input class="form-control"
																		name="home_process_<?php echo $i; ?>_desc"
																		value="<?php echo htmlspecialchars($home['home_process_' . $i . '_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
																</div>
																<div class="col-lg-3">
																	<?php $sr_img_key = 'home_process_' . $i . '_image';
																	$sr_img_val = (string) ($home[$sr_img_key] ?? ''); ?>
																	<input type="hidden" name="home_process_<?php echo $i; ?>_image"
																		value="<?php echo htmlspecialchars($sr_img_val, ENT_QUOTES, 'UTF-8'); ?>">
																	<label class="form-label">Step <?php echo $i; ?> image
																		(upload)</label>
																	<input class="form-control" type="file"
																		name="home_process_<?php echo $i; ?>_image_file"
																		accept="image/jpeg,image/png,image/webp">
																	<?php if (trim($sr_img_val) !== '') { ?>
																		<?php $sr_prev = '../' . ltrim($sr_img_val, '/'); ?>
																		<div class="mt-2">
																			<img src="<?php echo htmlspecialchars($sr_prev, ENT_QUOTES, 'UTF-8'); ?>"
																				alt="Preview"
																				style="width:100%;height:120px;object-fit:cover;border-radius:14px;border:1px solid rgba(10,25,38,.12);background:#fff;">
																		</div>
																	<?php } ?>
																</div>
															<?php } ?>
														</div>
													</div>
													<hr>
													<div class="col-12">
														<h4 class="mb-3">Marquee (4 words)</h4>
														<div class="row g-3">
															<div class="col-lg-3"><input class="form-control"
																	name="home_marquee_1" placeholder="Word 1"
																	value="<?php echo htmlspecialchars($home['home_marquee_1'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3"><input class="form-control"
																	name="home_marquee_2" placeholder="Word 2"
																	value="<?php echo htmlspecialchars($home['home_marquee_2'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3"><input class="form-control"
																	name="home_marquee_3" placeholder="Word 3"
																	value="<?php echo htmlspecialchars($home['home_marquee_3'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3"><input class="form-control"
																	name="home_marquee_4" placeholder="Word 4"
																	value="<?php echo htmlspecialchars($home['home_marquee_4'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
														</div>
													</div>
													<hr>
													<div class="col-12">
														<h4 class="mb-3">CTA Bar (bottom)</h4>
														<div class="row g-3">
															<div class="col-12">
																<label class="form-label">CTA title</label>
																<input class="form-control" name="home_cta_title"
																	value="<?php echo htmlspecialchars($home['home_cta_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-12">
																<label class="form-label">CTA description</label>
																<textarea class="form-control" rows="2"
																	name="home_cta_desc"><?php echo htmlspecialchars($home['home_cta_desc'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
															</div>
															<div class="col-lg-3">
																<label class="form-label">Button 1 label</label>
																<input class="form-control" name="home_cta_btn1_label"
																	value="<?php echo htmlspecialchars($home['home_cta_btn1_label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Button 1 URL</label>
																<input class="form-control" name="home_cta_btn1_url"
																	value="<?php echo htmlspecialchars($home['home_cta_btn1_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Button 2 label</label>
																<input class="form-control" name="home_cta_btn2_label"
																	value="<?php echo htmlspecialchars($home['home_cta_btn2_label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
															<div class="col-lg-3">
																<label class="form-label">Button 2 URL</label>
																<input class="form-control" name="home_cta_btn2_url"
																	value="<?php echo htmlspecialchars($home['home_cta_btn2_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>

									<div class="mt-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
										<a class="btn btn-outline-primary" href="pages.php">Back to list</a>
										<div class="d-flex flex-wrap gap-2">
											<button type="submit" class="btn btn-primary">Save</button>
											<button type="submit" class="btn btn-outline-danger" form="srDeletePageForm"
												onclick="return confirm('Delete this page entry?');">Delete</button>
										</div>
									</div>
								</form>
								<form id="srDeletePageForm" method="post" action="pages.php" style="display:none">
									<input type="hidden" name="csrf"
										value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
									<input type="hidden" name="op" value="delete">
									<input type="hidden" name="id" value="<?php echo (int) $editing['id']; ?>">
								</form>
								<?php if ($editing['slug'] === 'home') { ?>
									<form id="srHomeSlideSaveForm" method="post"
										action="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=slider"
										enctype="multipart/form-data" style="display:none"></form>
									<form id="srHomeSlideDeleteForm" method="post"
										action="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=slider"
										style="display:none">
										<input type="hidden" name="csrf"
											value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
										<input type="hidden" name="op" value="home_slide_delete">
										<input type="hidden" name="page_id" value="<?php echo (int) $editing['id']; ?>">
									</form>
									<form id="srHomeTestimonialSaveForm" method="post"
										action="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=testimonials"
										enctype="multipart/form-data" style="display:none"></form>
									<form id="srHomeTestimonialDeleteForm" method="post"
										action="pages.php?action=edit&id=<?php echo (int) $editing['id']; ?>&tab=testimonials"
										style="display:none">
										<input type="hidden" name="csrf"
											value="<?php echo htmlspecialchars(sr_admin_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
										<input type="hidden" name="op" value="home_testimonial_delete">
										<input type="hidden" name="page_id" value="<?php echo (int) $editing['id']; ?>">
									</form>
								<?php } ?>
							<?php } else { ?>
								<div class="row g-3 mb-4">
									<?php foreach ($known as $k) { ?>
										<div class="col-md-6 col-lg-4">
											<div class="p-3 rounded-3 border bg-light h-100">
												<div class="fw-bold mb-1 text-dark">
													<?php echo htmlspecialchars($k['label'], ENT_QUOTES, 'UTF-8'); ?>
												</div>
												<div class="text-title-gray mb-2">Slug: <span
														class="fw-bold"><?php echo htmlspecialchars($k['slug'], ENT_QUOTES, 'UTF-8'); ?></span>
												</div>
												<div class="d-flex gap-2 flex-wrap">
													<a class="btn btn-sm btn-outline-primary"
														href="<?php echo htmlspecialchars($k['url'], ENT_QUOTES, 'UTF-8'); ?>"
														target="_blank" rel="noopener">Open</a>
													<a class="btn btn-sm btn-primary"
														href="pages.php?slug=<?php echo rawurlencode($k['slug']); ?>">Edit</a>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>

								<div class="table-responsive">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th>Slug</th>
												<th>Title</th>
												<th>Hero Title</th>
												<th>Status</th>
												<th>Updated</th>
												<th class="text-end">Actions</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$pages) { ?>
												<tr>
													<td colspan="6" class="text-center text-title-gray py-4">No pages configured
														yet.</td>
												</tr>
											<?php } ?>
											<?php foreach ($pages as $p) { ?>
												<?php
												$bannerSet = trim((string) ($p['banner_image'] ?? '')) !== '';
												$titleSet = trim((string) ($p['title'] ?? '')) !== '';
												$heroSet = trim((string) ($p['hero_title'] ?? '')) !== '';
												$isConfigured = $titleSet || $heroSet || $bannerSet;
												$statusText = $isConfigured ? 'Configured' : 'Empty';
												$statusBadge = $isConfigured ? 'bg-light-success text-success' : 'bg-light-secondary text-secondary';
												?>
												<tr>
													<td class="fw-bold">
														<?php echo htmlspecialchars((string) $p['slug'], ENT_QUOTES, 'UTF-8'); ?>
													</td>
													<td><?php echo htmlspecialchars((string) ($p['title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
													</td>
													<td><?php echo htmlspecialchars((string) ($p['hero_title'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
													</td>
													<td>
														<span class="badge rounded-pill <?php echo $statusBadge; ?>">
															<?php echo htmlspecialchars($statusText, ENT_QUOTES, 'UTF-8'); ?>
														</span>
													</td>
													<td class="text-title-gray">
														<?php echo htmlspecialchars((string) $p['updated_at'], ENT_QUOTES, 'UTF-8'); ?>
													</td>
													<td class="text-end">
														<a class="btn btn-sm btn-primary"
															href="pages.php?action=edit&id=<?php echo (int) $p['id']; ?>">Edit</a>
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include 'footer.php'; ?>
