<?php include 'includes/header.php'; ?>
<?php
$sr_blog_posts_fallback = [
	[
		'slug' => 'rooftop-solar-nashik-savings',
		'category' => 'Solar Basics',
		'date' => 'Apr 2026',
		'read_time' => '8 min read',
		'image' => 'images/blog/blog-01.jpg',
		'title' => 'How Much Can You Really Save with Rooftop Solar in Nashik?',
		'excerpt' => 'A practical breakdown of system sizing, monthly bills, subsidy impact, and typical payback for Nashik homes.',
	],
	[
		'slug' => 'pm-surya-ghar-yojana-2024-guide',
		'category' => 'Government Schemes',
		'date' => 'Apr 2026',
		'read_time' => '10 min read',
		'image' => 'images/blog/blog-02.jpg',
		'title' => 'PM Surya Ghar Yojana 2024: Who Qualifies and How to Apply',
		'excerpt' => 'Eligibility, documents, subsidy flow, and a step-by-step checklist to apply without confusion.',
	],
	[
		'slug' => 'open-access-solar-guide-maharashtra',
		'category' => 'Case Studies',
		'date' => 'Apr 2026',
		'read_time' => '12 min read',
		'image' => 'images/blog/blog-03.jpg',
		'title' => 'Open Access Solar for Industries: A Complete Guide for Maharashtra',
		'excerpt' => 'Learn when Open Access makes sense, how billing works, key approvals, and common project risks.',
	],
	[
		'slug' => 'mistakes-choosing-solar-epc',
		'category' => 'FAQs',
		'date' => 'Apr 2026',
		'read_time' => '8 min read',
		'image' => 'images/blog/blog-04.jpg',
		'title' => '5 Mistakes to Avoid When Choosing a Solar EPC Company',
		'excerpt' => 'From Tier-1 components to warranty clarity—use this checklist to select the right EPC partner.',
	],
	[
		'slug' => 'net-metering-maharashtra-explained',
		'category' => 'Solar Basics',
		'date' => 'Apr 2026',
		'read_time' => '7 min read',
		'image' => 'images/blog/blog-05.jpg',
		'title' => 'Net Metering in Maharashtra: How to Earn from Your Solar System',
		'excerpt' => 'Understand approvals, net meter installation, export credits, and how billing is calculated.',
	],
	[
		'slug' => 'solar-ppa-explained-business',
		'category' => 'Industry News',
		'date' => 'Apr 2026',
		'read_time' => '9 min read',
		'image' => 'images/blog/blog-06.jpg',
		'title' => 'What is a Solar PPA and Is It Right for Your Business?',
		'excerpt' => 'A clear explanation of PPA models, pricing, contract terms, and when it outperforms CAPEX.',
	],
];

$sr_blog_page = sr_cms_page_get('blog');
$sr_blog_hero_title = $sr_blog_page ? (string)$sr_blog_page['hero_title'] : 'Solar Knowledge Hub';
$sr_blog_hero_subtitle = $sr_blog_page ? (string)$sr_blog_page['hero_subtitle'] : 'Stay informed with the latest news, guides, and insights from India&#8217;s solar industry.';
$sr_banner_image = $sr_blog_page && trim((string)($sr_blog_page['banner_image'] ?? '')) !== '' ? (string)$sr_blog_page['banner_image'] : '';
$sr_page_override = $sr_blog_page && trim((string)($sr_blog_page['content'] ?? '')) !== '' ? (string)$sr_blog_page['content'] : '';

$sr_blog_categories_title = sr_cms_setting_get('blog_categories_title', 'Browse Categories');
$sr_blog_cat1_icon = sr_cms_setting_get('blog_cat1_icon', 'pbmit-base-icon-lightening');
$sr_blog_cat1_title = sr_cms_setting_get('blog_cat1_title', 'Solar Basics');
$sr_blog_cat1_desc = sr_cms_setting_get('blog_cat1_desc', 'How solar works, types of systems, net metering explained');
$sr_blog_cat2_icon = sr_cms_setting_get('blog_cat2_icon', 'pbmit-base-icon-document');
$sr_blog_cat2_title = sr_cms_setting_get('blog_cat2_title', 'Government Schemes');
$sr_blog_cat2_desc = sr_cms_setting_get('blog_cat2_desc', 'PM Surya Ghar Yojana, subsidies, accelerated depreciation');
$sr_blog_cat3_icon = sr_cms_setting_get('blog_cat3_icon', 'pbmit-base-icon-news');
$sr_blog_cat3_title = sr_cms_setting_get('blog_cat3_title', 'Industry News');
$sr_blog_cat3_desc = sr_cms_setting_get('blog_cat3_desc', 'Renewable energy policy updates, Maharashtra solar news');
$sr_blog_cat4_icon = sr_cms_setting_get('blog_cat4_icon', 'pbmit-base-icon-check-mark');
$sr_blog_cat4_title = sr_cms_setting_get('blog_cat4_title', 'Case Studies');
$sr_blog_cat4_desc = sr_cms_setting_get('blog_cat4_desc', 'Detailed project stories with energy savings data');
$sr_blog_cat5_icon = sr_cms_setting_get('blog_cat5_icon', 'pbmit-base-icon-chat-3');
$sr_blog_cat5_title = sr_cms_setting_get('blog_cat5_title', 'FAQs');
$sr_blog_cat5_desc = sr_cms_setting_get('blog_cat5_desc', 'Answers to common questions from residential and commercial buyers');
$sr_blog_latest_title = sr_cms_setting_get('blog_latest_title', 'Latest Articles');
$sr_blog_latest_desc = sr_cms_setting_get('blog_latest_desc', 'Practical guides, policy updates, and solar insights to help you make confident decisions.');
$sr_blog_faq_title = sr_cms_setting_get('blog_faq_title', 'Frequently Asked Questions');
$sr_blog_faq_cta_label = sr_cms_setting_get('blog_faq_cta_label', 'Request Free Consultation');
$sr_blog_faq_cta_url = sr_cms_setting_get('blog_faq_cta_url', 'contact');

$sr_db = sr_cms_db_try();
$sr_blog_faqs = [];
if ($sr_db instanceof mysqli) {
	$resFaq = $sr_db->query("SELECT question, answer
		FROM cms_blog_faqs
		WHERE is_active = 1
		ORDER BY sort_order ASC, updated_at DESC
		LIMIT 50");
	if ($resFaq) {
		while ($row = $resFaq->fetch_assoc()) {
			$q = (string) ($row['question'] ?? '');
			$a = (string) ($row['answer'] ?? '');
			if (trim($q) === '' || trim($a) === '') {
				continue;
			}
			$sr_blog_faqs[] = ['q' => $q, 'a' => $a];
		}
		$resFaq->free();
	}
}
if (!$sr_blog_faqs) {
	for ($i = 1; $i <= 10; $i++) {
		$q = sr_cms_setting_get('blog_faq' . $i . '_q', '');
		$a = sr_cms_setting_get('blog_faq' . $i . '_a', '');
		if (trim($q) === '' || trim($a) === '') {
			continue;
		}
		$sr_blog_faqs[] = ['q' => $q, 'a' => $a];
	}
}

$sr_blog_posts = [];
if ($sr_db instanceof mysqli) {
	$res = $sr_db->query("SELECT slug, title, category, date_label, read_time, cover_image, excerpt
		FROM cms_blog_posts
		WHERE published = 1
		ORDER BY COALESCE(published_at, updated_at) DESC
		LIMIT 60");
	if ($res) {
		while ($row = $res->fetch_assoc()) {
			$sr_blog_posts[] = [
				'slug' => (string)($row['slug'] ?? ''),
				'category' => (string)($row['category'] ?? ''),
				'date' => (string)($row['date_label'] ?? ''),
				'read_time' => (string)($row['read_time'] ?? ''),
				'image' => (string)($row['cover_image'] ?? ''),
				'title' => (string)($row['title'] ?? ''),
				'excerpt' => (string)($row['excerpt'] ?? ''),
			];
		}
		$res->free();
	}
}
if (!$sr_blog_posts) {
	$sr_blog_posts = $sr_blog_posts_fallback;
}
?>

<!-- Title Bar -->
<div class="pbmit-title-bar-wrapper sr-why-hero"<?php echo $sr_banner_image !== '' ? (' style="background-image:url(' . htmlspecialchars($sr_banner_image, ENT_QUOTES, 'UTF-8') . ');"') : ''; ?>>
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_blog_hero_title, ENT_QUOTES, 'UTF-8'); ?></h1>
						<?php if (trim($sr_blog_hero_subtitle) !== '') { ?>
							<p class="pbmit-tbar-subtitle mb-0"><?php echo $sr_blog_hero_subtitle; ?></p>
						<?php } ?>
					</div>
				</div>
				<div class="pbmit-breadcrumb">
					<div class="pbmit-breadcrumb-inner">
						<span>
							<a title="" href="./" class="home"><span>Home</span></a>
						</span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span><span class="post-root post post-post current-item"> Blog</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Title Bar End-->
</header>

<div class="page-content blog-hub">
	<?php if ($sr_page_override !== '') { ?>
		<?php echo $sr_page_override; ?>
	<?php } else { ?>
	<section id="news" class="section-xl pbmit-bg-color-white sr-blog-cats" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_blog_categories_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<div class="row g-4 pt-3">
				<div class="col-md-6 col-lg-4">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="<?php echo htmlspecialchars($sr_blog_cat1_icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
						<h3 class="sr-cat-title"><?php echo htmlspecialchars($sr_blog_cat1_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="sr-cat-desc"><?php echo htmlspecialchars($sr_blog_cat1_desc, ENT_QUOTES, 'UTF-8'); ?></p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="<?php echo htmlspecialchars($sr_blog_cat2_icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
						<h3 class="sr-cat-title"><?php echo htmlspecialchars($sr_blog_cat2_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="sr-cat-desc"><?php echo htmlspecialchars($sr_blog_cat2_desc, ENT_QUOTES, 'UTF-8'); ?></p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="<?php echo htmlspecialchars($sr_blog_cat3_icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
						<h3 class="sr-cat-title"><?php echo htmlspecialchars($sr_blog_cat3_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="sr-cat-desc"><?php echo htmlspecialchars($sr_blog_cat3_desc, ENT_QUOTES, 'UTF-8'); ?></p>
					</div>
				</div>
				<div class="col-md-6 col-lg-6">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="<?php echo htmlspecialchars($sr_blog_cat4_icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
						<h3 class="sr-cat-title"><?php echo htmlspecialchars($sr_blog_cat4_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="sr-cat-desc"><?php echo htmlspecialchars($sr_blog_cat4_desc, ENT_QUOTES, 'UTF-8'); ?></p>
					</div>
				</div>
				<div class="col-md-6 col-lg-6">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="<?php echo htmlspecialchars($sr_blog_cat5_icon, ENT_QUOTES, 'UTF-8'); ?>"></i></div>
						<h3 class="sr-cat-title"><?php echo htmlspecialchars($sr_blog_cat5_title, ENT_QUOTES, 'UTF-8'); ?></h3>
						<p class="sr-cat-desc"><?php echo htmlspecialchars($sr_blog_cat5_desc, ENT_QUOTES, 'UTF-8'); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="solar-guides" class="section-xl sr-blog-posts" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_blog_latest_title, ENT_QUOTES, 'UTF-8'); ?></h2>
				<p class="mb-0 sr-blog-subtext"><?php echo htmlspecialchars($sr_blog_latest_desc, ENT_QUOTES, 'UTF-8'); ?></p>
			</div>
			<div class="row g-4 pt-4">
				<?php foreach ($sr_blog_posts as $post) { ?>
					<div class="col-md-6 col-lg-4">
						<a class="sr-post-card" href="blog/<?php echo htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8'); ?>">
							<div class="sr-post-media">
								<img src="<?php echo htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8'); ?>"
									alt="<?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>">
							</div>
							<div class="sr-post-body">
								<div class="sr-post-meta">
									<span class="sr-post-chip"><?php echo htmlspecialchars($post['category'], ENT_QUOTES, 'UTF-8'); ?></span>
									<span class="sr-post-date"><?php echo htmlspecialchars($post['date'], ENT_QUOTES, 'UTF-8'); ?></span>
								</div>
								<h3 class="sr-post-title"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
								<p class="sr-post-excerpt"><?php echo htmlspecialchars($post['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
								<div class="sr-post-footer">
									<span class="sr-post-read"><?php echo htmlspecialchars($post['read_time'], ENT_QUOTES, 'UTF-8'); ?></span>
									<span class="sr-post-cta">Read Article <i class="pbmit-base-icon-arrow-right-2" aria-hidden="true"></i></span>
								</div>
							</div>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<section id="faqs" class="section-xl sr-blog-faq" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title"><?php echo htmlspecialchars($sr_blog_faq_title, ENT_QUOTES, 'UTF-8'); ?></h2>
			</div>
			<div class="row justify-content-center pt-3">
				<div class="col-lg-10 col-xl-9">
					<div class="accordion" id="blogFaq">
						<?php if ($sr_blog_faqs) { ?>
							<?php foreach ($sr_blog_faqs as $i => $faq) { ?>
								<?php
								$iNum = (int) ($i + 1);
								$open = $iNum === 1;
								?>
								<div class="accordion-item <?php echo $open ? 'active' : ''; ?>">
									<h2 class="accordion-header" id="bf<?php echo $iNum; ?>">
										<button class="accordion-button <?php echo $open ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse"
											data-bs-target="#ba<?php echo $iNum; ?>" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>" aria-controls="ba<?php echo $iNum; ?>">
											<span class="sr-faq-title"><?php echo (string) $faq['q']; ?></span>
											<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
										</button>
									</h2>
									<div id="ba<?php echo $iNum; ?>" class="accordion-collapse collapse <?php echo $open ? 'show' : ''; ?>" aria-labelledby="bf<?php echo $iNum; ?>"
										data-bs-parent="#blogFaq">
										<div class="accordion-body">
											<p><?php echo (string) $faq['a']; ?></p>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="sr-blog-faq-cta">
						<a href="<?php echo htmlspecialchars($sr_blog_faq_cta_url, ENT_QUOTES, 'UTF-8'); ?>" class="pbmit-btn"><span class="pbmit-button-text"><?php echo htmlspecialchars($sr_blog_faq_cta_label, ENT_QUOTES, 'UTF-8'); ?></span></a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
