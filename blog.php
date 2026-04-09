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

$sr_blog_posts = [];
$sr_db = sr_cms_db_try();
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
	<section id="news" class="section-xl pbmit-bg-color-white sr-blog-cats" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title">Browse Categories</h2>
			</div>
			<div class="row g-4 pt-3">
				<div class="col-md-6 col-lg-4">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="pbmit-base-icon-lightening"></i></div>
						<h3 class="sr-cat-title">Solar Basics</h3>
						<p class="sr-cat-desc">How solar works, types of systems, net metering explained</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="pbmit-base-icon-document"></i></div>
						<h3 class="sr-cat-title">Government Schemes</h3>
						<p class="sr-cat-desc">PM Surya Ghar Yojana, subsidies, accelerated depreciation</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="pbmit-base-icon-news"></i></div>
						<h3 class="sr-cat-title">Industry News</h3>
						<p class="sr-cat-desc">Renewable energy policy updates, Maharashtra solar news</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-6">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="pbmit-base-icon-check-mark"></i></div>
						<h3 class="sr-cat-title">Case Studies</h3>
						<p class="sr-cat-desc">Detailed project stories with energy savings data</p>
					</div>
				</div>
				<div class="col-md-6 col-lg-6">
					<div class="sr-cat-card">
						<div class="sr-cat-icon"><i class="pbmit-base-icon-chat-3"></i></div>
						<h3 class="sr-cat-title">FAQs</h3>
						<p class="sr-cat-desc">Answers to common questions from residential and commercial buyers</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="solar-guides" class="section-xl sr-blog-posts" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="pbmit-heading-subheading text-center">
				<h2 class="pbmit-title">Latest Articles</h2>
				<p class="mb-0 sr-blog-subtext">Practical guides, policy updates, and solar insights to help you make
					confident decisions.</p>
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
				<h2 class="pbmit-title">Frequently Asked Questions</h2>
			</div>
			<div class="row justify-content-center pt-3">
				<div class="col-lg-10 col-xl-9">
					<div class="accordion" id="blogFaq">
						<div class="accordion-item active">
							<h2 class="accordion-header" id="bf1">
								<button class="accordion-button" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba1" aria-expanded="true" aria-controls="ba1">
									<span class="sr-faq-title">What is a Solar EPC company?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba1" class="accordion-collapse collapse show" aria-labelledby="bf1"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>An EPC (Engineering, Procurement &amp; Construction) company manages the complete solar
										project lifecycle from design and material procurement to installation and
										commissioning.</p>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="bf2">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba2" aria-expanded="false" aria-controls="ba2">
									<span class="sr-faq-title">How much does a residential solar system cost in Maharashtra?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba2" class="accordion-collapse collapse" aria-labelledby="bf2"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>A 5 kW system typically costs between ₹2.5–3.5 lakh before subsidy. After PM Surya Ghar
										subsidy, the net cost can drop significantly.</p>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="bf3">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba3" aria-expanded="false" aria-controls="ba3">
									<span class="sr-faq-title">What is the payback period for solar in India?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba3" class="accordion-collapse collapse" aria-labelledby="bf3"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>Most residential systems pay back in 4–6 years. Commercial and industrial systems often pay
										back in 3–5 years.</p>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="bf4">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba4" aria-expanded="false" aria-controls="ba4">
									<span class="sr-faq-title">Does Shivanjali Renewables offer maintenance after installation?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba4" class="accordion-collapse collapse" aria-labelledby="bf4"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>Yes. We offer comprehensive O&amp;M services including remote monitoring, cleaning, and on-site
										repairs.</p>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="bf5">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba5" aria-expanded="false" aria-controls="ba5">
									<span class="sr-faq-title">What is Open Access solar?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba5" class="accordion-collapse collapse" aria-labelledby="bf5"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>Open Access allows large consumers to purchase solar power directly from a generator,
										bypassing the distribution grid tariff. It is typically available for consumers above 100
										kW demand.</p>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="bf6">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba6" aria-expanded="false" aria-controls="ba6">
									<span class="sr-faq-title">Which panels and inverters do you use?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba6" class="accordion-collapse collapse" aria-labelledby="bf6"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>We use only Tier-1 certified solar panels and inverters from reputed brands that meet MNRE
										and BIS standards.</p>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="bf7">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba7" aria-expanded="false" aria-controls="ba7">
									<span class="sr-faq-title">Can solar be installed on any type of roof?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba7" class="accordion-collapse collapse" aria-labelledby="bf7"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>Yes. We install on RCC, metal sheet, and tile roofs. Our structural team assesses load-bearing
										capacity before installation.</p>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="bf8">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba8" aria-expanded="false" aria-controls="ba8">
									<span class="sr-faq-title">How long does installation take?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba8" class="accordion-collapse collapse" aria-labelledby="bf8"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>Residential systems are installed in 1–3 days. Commercial and industrial projects may take
										2–8 weeks depending on scale.</p>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="bf9">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba9" aria-expanded="false" aria-controls="ba9">
									<span class="sr-faq-title">Is there a warranty on solar systems?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba9" class="accordion-collapse collapse" aria-labelledby="bf9"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>Yes. Panels carry a 25-year performance warranty. Inverters and other equipment have
										manufacturer warranties ranging from 5–10 years.</p>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="bf10">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
									data-bs-target="#ba10" aria-expanded="false" aria-controls="ba10">
									<span class="sr-faq-title">How do I get started?</span>
									<span class="sr-faq-arrow" aria-hidden="true"><i class="pbmit-base-icon-arrow-right-2"></i></span>
								</button>
							</h2>
							<div id="ba10" class="accordion-collapse collapse" aria-labelledby="bf10"
								data-bs-parent="#blogFaq">
								<div class="accordion-body">
									<p>Simply fill out our contact form or call us. Our team will schedule a free site survey within
										48 hours.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="sr-blog-faq-cta">
						<a href="contact" class="pbmit-btn"><span class="pbmit-button-text">Request Free Consultation</span></a>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php include 'includes/footer.php'; ?>
