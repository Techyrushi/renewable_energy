<?php
require_once __DIR__ . '/includes/cms.php';

$sr_slug = strtolower((string)($_GET['post'] ?? ''));
if (!preg_match('/^[a-z0-9-]+$/', $sr_slug)) {
	$sr_slug = '';
}

$sr_post = null;
$sr_db = sr_cms_db_try();
if ($sr_db instanceof mysqli && $sr_slug !== '') {
	$stmt = $sr_db->prepare('SELECT slug, title, category, date_label, read_time, cover_image, content FROM cms_blog_posts WHERE slug=? AND published=1 LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('s', $sr_slug);
		$stmt->execute();
		$stmt->bind_result($rslug, $rtitle, $rcat, $rdate, $rread, $rimg, $rcontent);
		if ($stmt->fetch()) {
			$sr_post = [
				'slug' => (string)$rslug,
				'category' => (string)$rcat,
				'date' => (string)$rdate,
				'read_time' => (string)$rread,
				'image' => (string)$rimg,
				'title' => (string)$rtitle,
				'html' => (string)$rcontent,
			];
		}
		$stmt->close();
	}
}

if ($sr_post === null) {
	$sr_blog_posts = [
	[
		'slug' => 'rooftop-solar-nashik-savings',
		'category' => 'Solar Basics',
		'date' => 'Apr 2026',
		'read_time' => '8 min read',
		'image' => 'images/blog/blog-01.jpg',
		'title' => 'How Much Can You Really Save with Rooftop Solar in Nashik?',
		'html' => '<p>Rooftop solar savings depend on your electricity tariff, system size, roof orientation, and the portion of consumption you can offset during the day. In Nashik, a well-designed rooftop system can reduce a large share of monthly electricity bills and offer a predictable payback period.</p>
<h2>What Affects Savings the Most?</h2>
<ul><li><strong>Monthly units consumed:</strong> Higher usage typically improves payback because you offset more high-tariff units.</li><li><strong>System sizing:</strong> Right-sizing avoids over-export and aligns generation with consumption.</li><li><strong>Net metering:</strong> Export credits can improve ROI when configured correctly.</li><li><strong>Subsidy:</strong> PM Surya Ghar subsidy reduces upfront cost and accelerates payback.</li></ul>
<h2>Typical Outcome (Simple Model)</h2>
<p>A properly engineered system usually recovers a significant portion of your daytime consumption. We recommend a site survey to validate shading, structure, and optimal layout.</p>
<h2>Quick Checklist Before You Decide</h2>
<ul><li>Verify roof shade-free hours and structure strength</li><li>Confirm sanctioned load and net meter feasibility</li><li>Choose Tier-1 panels and quality inverters</li><li>Ask for performance estimate and BOM clarity</li></ul>',
	],
	[
		'slug' => 'pm-surya-ghar-yojana-2024-guide',
		'category' => 'Government Schemes',
		'date' => 'Apr 2026',
		'read_time' => '10 min read',
		'image' => 'images/blog/blog-02.jpg',
		'title' => 'PM Surya Ghar Yojana 2024: Who Qualifies and How to Apply',
		'html' => '<p>The PM Surya Ghar scheme supports residential rooftop solar with a structured subsidy process. The exact eligibility and subsidy workflow can vary based on DISCOM requirements and documentation.</p>
<h2>Eligibility Basics</h2>
<ul><li>Residential consumer with eligible electricity connection</li><li>Valid documents for identity and address verification</li><li>Roof suitability confirmed via site survey</li></ul>
<h2>Application Flow (High Level)</h2>
<ol><li>Submit application and basic details</li><li>Site survey and technical feasibility</li><li>Installation and inspection</li><li>Net meter process and commissioning</li><li>Subsidy claim submission</li></ol>
<h2>Common Mistakes to Avoid</h2>
<ul><li>Incorrect system sizing for your monthly units</li><li>Missing documents delaying net meter approval</li><li>Choosing non-certified components that create warranty issues</li></ul>',
	],
	[
		'slug' => 'open-access-solar-guide-maharashtra',
		'category' => 'Case Studies',
		'date' => 'Apr 2026',
		'read_time' => '12 min read',
		'image' => 'images/blog/blog-03.jpg',
		'title' => 'Open Access Solar for Industries: A Complete Guide for Maharashtra',
		'html' => '<p>Open Access solar can help larger consumers procure power at competitive rates by purchasing directly from a generator. The model involves approvals, wheeling, banking rules, and forecasting considerations.</p>
<h2>When Open Access Makes Sense</h2>
<ul><li>High and consistent electricity demand</li><li>Ability to sign long-term agreements</li><li>Strong savings potential compared to grid tariff</li></ul>
<h2>Key Elements</h2>
<ul><li><strong>Regulatory approvals:</strong> Processes differ across DISCOMs and demand levels.</li><li><strong>Energy accounting:</strong> Billing settlement includes multiple components.</li><li><strong>Commercial structure:</strong> Contract, tariff, and risk allocation matter.</li></ul>
<h2>Recommended Next Step</h2>
<p>Start with a feasibility assessment based on your sanctioned load, consumption pattern, and location to evaluate savings and implementation timelines.</p>',
	],
	[
		'slug' => 'mistakes-choosing-solar-epc',
		'category' => 'FAQs',
		'date' => 'Apr 2026',
		'read_time' => '8 min read',
		'image' => 'images/blog/blog-04.jpg',
		'title' => '5 Mistakes to Avoid When Choosing a Solar EPC Company',
		'html' => '<p>Choosing the right EPC partner is as important as choosing the right components. A good EPC ensures correct design, safe installation, and long-term performance.</p>
<h2>Top 5 Mistakes</h2>
<ol><li><strong>Comparing only on price:</strong> Lower initial cost can hide inferior components or design shortcuts.</li><li><strong>Ignoring structure and wind load:</strong> Poor engineering can lead to failures and leaks.</li><li><strong>Unclear BOM and warranties:</strong> Always confirm brands, models, and warranty terms.</li><li><strong>No performance estimate:</strong> Ask for generation assumptions and shading analysis.</li><li><strong>Weak O&amp;M plan:</strong> Maintenance and monitoring are essential for consistent ROI.</li></ol>
<h2>What to Ask Before Signing</h2>
<ul><li>Design layout and yield estimate</li><li>Tier-1 component proof and certifications</li><li>Net metering support and timeline</li><li>O&amp;M scope, SLAs, and response times</li></ul>',
	],
	[
		'slug' => 'net-metering-maharashtra-explained',
		'category' => 'Solar Basics',
		'date' => 'Apr 2026',
		'read_time' => '7 min read',
		'image' => 'images/blog/blog-05.jpg',
		'title' => 'Net Metering in Maharashtra: How to Earn from Your Solar System',
		'html' => '<p>Net metering allows you to export excess solar energy to the grid and receive credit based on energy accounting rules. The goal is to maximize self-consumption and use export credits effectively.</p>
<h2>How It Works</h2>
<ul><li>Solar powers your loads first</li><li>Excess units export to the grid</li><li>Net meter records import/export units</li><li>Billing settlement applies credits</li></ul>
<h2>Getting It Right</h2>
<ul><li>Right-size the system to your consumption</li><li>Ensure the application and paperwork are complete</li><li>Use quality protection devices and proper earthing</li></ul>',
	],
	[
		'slug' => 'solar-ppa-explained-business',
		'category' => 'Industry News',
		'date' => 'Apr 2026',
		'read_time' => '9 min read',
		'image' => 'images/blog/blog-06.jpg',
		'title' => 'What is a Solar PPA and Is It Right for Your Business?',
		'html' => '<p>A Power Purchase Agreement (PPA) is a model where you consume solar energy and pay per unit, often with little or no upfront investment. The PPA provider owns and maintains the plant.</p>
<h2>PPA vs CAPEX (Simple View)</h2>
<ul><li><strong>PPA:</strong> OPEX model, predictable tariff, provider handles O&amp;M</li><li><strong>CAPEX:</strong> You invest upfront and own the asset, higher long-term savings potential</li></ul>
<h2>When a PPA is a Good Fit</h2>
<ul><li>You prefer low upfront investment</li><li>You want predictable per-unit pricing</li><li>You want maintenance handled by the provider</li></ul>
<h2>Key Contract Points</h2>
<ul><li>Tariff and escalation terms</li><li>Performance guarantees</li><li>Exit clauses and asset ownership terms</li></ul>',
	],
];

	foreach ($sr_blog_posts as $p) {
		if ($p['slug'] === $sr_slug) {
			$sr_post = $p;
			break;
		}
	}
}

if ($sr_post === null) {
	http_response_code(404);
}
?>
<?php include 'includes/header.php'; ?>
</header>

<style>
	.sr-article-content img {
		max-width: 100%;
		height: auto;
		display: block;
		margin: 18px 0;
		border-radius: 18px;
		box-shadow: 0 18px 40px rgba(0, 0, 0, .10);
		border: 1px solid rgba(10, 25, 38, .10);
		background: #fff;
	}
	.sr-article-content figure {
		margin: 18px 0;
	}
	.sr-article-content figcaption {
		margin-top: 10px;
		font-weight: 600;
		color: rgba(10, 25, 38, .70);
	}
</style>

<div class="pbmit-title-bar-wrapper sr-why-hero sr-blog-detail-hero">
	<div class="container">
		<div class="pbmit-title-bar-content">
			<div class="pbmit-title-bar-content-inner">
				<div class="pbmit-tbar">
					<div class="pbmit-tbar-inner container">
						<h1 class="pbmit-tbar-title"><?php echo htmlspecialchars($sr_post ? $sr_post['title'] : 'Blog', ENT_QUOTES, 'UTF-8'); ?></h1>
						<?php if ($sr_post) { ?>
							<p class="pbmit-tbar-subtitle mb-0"><?php echo htmlspecialchars($sr_post['category'] . ' • ' . $sr_post['date'] . ' • ' . $sr_post['read_time'], ENT_QUOTES, 'UTF-8'); ?></p>
						<?php } ?>
					</div>
				</div>
				<div class="pbmit-breadcrumb">
					<div class="pbmit-breadcrumb-inner">
						<span>
							<a title="" href="./" class="home"><span>Home</span></a>
						</span>
						<i class="pbmit-base-icon-arrow-right-2"></i>
						<span>
							<a href="blog"><span>Blog</span></a>
						</span>
						<?php if ($sr_post) { ?>
							<i class="pbmit-base-icon-arrow-right-2"></i>
							<span><span class="post-root post post-post current-item"><?php echo htmlspecialchars($sr_post['title'], ENT_QUOTES, 'UTF-8'); ?></span></span>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-content blog-detail">
	<section class="section-xl sr-article-section" data-aos="fade-up" data-aos-duration="800">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-10 col-xl-8">
					<?php if ($sr_post === null) { ?>
						<div class="sr-article-card">
							<h2 class="sr-article-title">Article not found</h2>
							<p class="sr-article-lead">The article you&#8217;re looking for doesn&#8217;t exist or the link is incorrect.</p>
							<a href="blog" class="pbmit-btn outline sr-article-back"><span class="pbmit-button-text">Back to Blog</span></a>
						</div>
					<?php } else { ?>
						<article class="sr-article-card">
							<div class="sr-article-cover">
								<img src="<?php echo htmlspecialchars($sr_post['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($sr_post['title'], ENT_QUOTES, 'UTF-8'); ?>">
							</div>
							<div class="sr-article-meta">
								<span class="sr-article-chip"><?php echo htmlspecialchars($sr_post['category'], ENT_QUOTES, 'UTF-8'); ?></span>
								<span class="sr-article-dot">•</span>
								<span class="sr-article-muted"><?php echo htmlspecialchars($sr_post['date'], ENT_QUOTES, 'UTF-8'); ?></span>
								<span class="sr-article-dot">•</span>
								<span class="sr-article-muted"><?php echo htmlspecialchars($sr_post['read_time'], ENT_QUOTES, 'UTF-8'); ?></span>
							</div>
							<div class="sr-article-content">
								<?php echo $sr_post['html']; ?>
							</div>
							<div class="sr-article-cta">
								<div class="sr-article-cta-text">
									<h3 class="sr-article-cta-title">Need a site survey or proposal?</h3>
									<p class="sr-article-cta-desc mb-0">Share your location and requirement. We&#8217;ll respond within 24 hours.</p>
								</div>
								<a href="contact" class="pbmit-btn sr-article-cta-btn"><span class="pbmit-button-text">Request Free Consultation</span></a>
							</div>
						</article>
					<?php } ?>
				</div>
			</div>
		</div>
	</section>
</div>

<?php include 'includes/footer.php'; ?>
