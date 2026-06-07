<?php
/**
 * Template Name: フォトコンテスト
 * 七間町フォトコンテストページ
 *
 * ※ 作品・賞品・規約・アーカイブはデザイン再現用のサンプルデータ。
 *    CPT_PHOTO_AWARD 連携・応募フォーム実機化（CF7 等）・年度アーカイブは別途実装する。
 */
get_header();

// ─── サンプルデータ（後で ACF / CPT に差し替え）────────────────
$pc_year   = '2025';
$pc_period = '2025年7月1日 〜 2026年1月31日';
$pc_period_short = '2025年07月01日 〜 2026年01月31日';

// 部門と賞品
$pc_categories = [
	[
		'key'  => 'A',
		'name' => '七間町の日常とレトロ',
		'sub'  => '風景・建築・路地・歴史的建造物',
		'prizes' => [
			[ 'rank' => '最優秀賞', 'detail' => '商品券 30,000円分' ],
			[ 'rank' => '優秀賞',   'detail' => '商品券 10,000円分（2名）' ],
			[ 'rank' => '入選',     'detail' => '駿河の工芸品（5名）' ],
		],
	],
	[
		'key'  => 'B',
		'name' => '七間町のお気に入り',
		'sub'  => 'グルメ・お土産・ひととき・人々',
		'prizes' => [
			[ 'rank' => '最優秀賞', 'detail' => '商品券 30,000円分' ],
			[ 'rank' => '優秀賞',   'detail' => '商品券 10,000円分（2名）' ],
			[ 'rank' => '入選',     'detail' => '駿河の工芸品（5名）' ],
		],
	],
];

// 最新の投稿作品（サンプル）
$pc_entries = [
	[ 'cat' => 'A', 'likes' => 42, 'title' => '朝の七間町',   'author' => '山田太郎', 'comment' => '早朝の静けさの中、打ち水をする店主の姿が印象的でした。', 'img' => 'gallery/gallery-1.jpg' ],
	[ 'cat' => 'A', 'likes' => 51, 'title' => '桜と駿府城',   'author' => '田中美咲', 'comment' => '春の駿府城公園。石垣と桜のコントラストが美しい一枚。',   'img' => 'gallery/gallery-2.jpg' ],
	[ 'cat' => 'A', 'likes' => 35, 'title' => '映画館の記憶', 'author' => '鈴木一郎', 'comment' => 'かつて映画館が立ち並んだ通り。レトロな看板が当時を伝える。', 'img' => 'gallery/gallery-3.jpg' ],
	[ 'cat' => 'B', 'likes' => 40, 'title' => '老舗の和菓子', 'author' => '小林美月', 'comment' => '三代続く老舗の練りきり。季節の素材を活かした美しい一品。',   'img' => 'gallery/gallery-4.jpg' ],
	[ 'cat' => 'B', 'likes' => 31, 'title' => '静岡おでん',   'author' => '山本達也', 'comment' => '冬の定番、黒はんぺんのおでん。湯気が立ち上る幸せの一杯。',   'img' => 'gallery/gallery-5.jpg' ],
	[ 'cat' => 'A', 'likes' => 45, 'title' => '七間町のお祭り', 'author' => '渡辺明',  'comment' => '提灯の明かりに照らされた商店街。夏祭りの活気が通りに満ちる。', 'img' => 'gallery/gallery-6.png' ],
];
$pc_cat_labels = [ 'A' => 'A部門（日常とレトロ）', 'B' => 'B部門（お気に入り）' ];

// 応募規約
$pc_guidelines = [
	[ 'title' => '応募期間',         'body' => '2025年7月1日（火）〜 2026年1月31日（土）23:59 まで。期間内に応募フォームから投稿された作品を対象とします。' ],
	[ 'title' => '応募資格',         'body' => 'どなたでもご応募いただけます（プロ・アマ問わず）。お一人様、各部門3点までご応募可能です。' ],
	[ 'title' => '画像サイズ・形式', 'body' => 'JPEG または PNG 形式、1点あたり 10MB 以内。長辺 2,000px 以上を推奨します。' ],
	[ 'title' => '著作権・肖像権について', 'body' => '応募作品の著作権は応募者に帰属します。人物が写っている場合は、必ず被写体の許可を得てからご応募ください。応募作品は七間町のプロモーション（ウェブサイト・SNS・印刷物等）に無償で使用させていただく場合があります。' ],
	[ 'title' => '結果発表',         'body' => '審査の上、2026年2月下旬に当ウェブサイトおよび七間町公式SNSにて発表します。入賞者には個別にご連絡いたします。' ],
	[ 'title' => '注意事項',         'body' => '他のコンテストへの応募作品・受賞作品、第三者の権利を侵害する作品はご応募いただけません。応募をもって本規約に同意いただいたものとみなします。' ],
];

// 過去のアーカイブ
$pc_archives = [
	[ 'year' => '2024', 'count' => 156 ],
	[ 'year' => '2023', 'count' => 128 ],
	[ 'year' => '2022', 'count' => 97 ],
];

$pc_form_anchor = '#photo-contest-entry';
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-photo-contest">

	<!-- ─── ヒーロー ── -->
	<section class="p-photo-contest__hero" aria-labelledby="pc-hero-title">
		<picture class="p-photo-contest__hero-bg" aria-hidden="true">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/top/hero-main.jpg' ); ?>" alt="" width="1440" height="800" loading="eager">
		</picture>
		<!-- /.p-photo-contest__hero-bg -->
		<div class="p-photo-contest__hero-inner">
			<p class="p-photo-contest__hero-eyebrow">PHOTO CONTEST</p>
			<h1 class="p-photo-contest__hero-title" id="pc-hero-title">七間町フォトコンテスト <?php echo esc_html( $pc_year ); ?></h1>
			<p class="p-photo-contest__hero-catch">あなたのレンズが切り取る、<br>七間町の新しい記憶。</p>
			<p class="p-photo-contest__hero-lead">日常の風景、お気に入りの店、歴史を感じる路地、人々の笑顔——<br>あなただけの七間町を、一枚の写真に。</p>
			<a class="p-photo-contest__hero-cta c-btn c-btn--primary" href="<?php echo esc_url( $pc_form_anchor ); ?>">
				<svg class="p-photo-contest__hero-cta-icon" aria-hidden="true" focusable="false"><use href="#icon-camera"></use></svg>
				コンテストに応募する
			</a>
			<p class="p-photo-contest__hero-period">募集期間：<?php echo esc_html( $pc_period ); ?></p>
		</div>
		<!-- /.p-photo-contest__hero-inner -->
	</section>
	<!-- /.p-photo-contest__hero -->

	<!-- ─── About ── -->
	<section class="p-photo-contest__about" aria-labelledby="pc-about-title">
		<div class="p-photo-contest__about-inner">
			<header class="p-photo-contest__section-head">
				<p class="p-photo-contest__section-eyebrow">About</p>
				<h2 class="p-photo-contest__section-title" id="pc-about-title">七間町フォトコンテストとは</h2>
			</header>
			<!-- /.p-photo-contest__section-head -->

			<p class="p-photo-contest__about-lead">七間町の絶景＆日常写真を大募集！</p>
			<p class="p-photo-contest__about-text">静岡の中心に位置する七間町。映画館が立ち並んだ昭和の記憶、職人の手仕事が息づく路地裏、季節ごとに表情を変える駿府城公園——この町には、まだ誰も切り取っていない風景が無数にあります。</p>
			<p class="p-photo-contest__about-text">「七間町フォトコンテスト」は、住む人も訪れる人も、この町の魅力を再発見し、共有するためのプロジェクトです。あなたの視点で見つけた“七間町らしさ”を、一枚の写真に託してください。</p>

			<div class="p-photo-contest__theme">
				<p class="p-photo-contest__theme-label">テーマ</p>
				<p class="p-photo-contest__theme-title">七間町のいいとこ、撮ってみた！</p>
				<p class="p-photo-contest__theme-text">何気ない風景も、とっておきの瞬間も。お散歩しながら、食べ歩きしながら、通勤途中に——あなたの日常の中にある七間町を撮ってください。</p>
				<p class="p-photo-contest__theme-note">※ 応募される方は、エピソード欄に「撮影場所」をご記入ください。</p>
			</div>
			<!-- /.p-photo-contest__theme -->
		</div>
		<!-- /.p-photo-contest__about-inner -->
	</section>
	<!-- /.p-photo-contest__about -->

	<!-- ─── 部門・賞品 ── -->
	<section class="p-photo-contest__category" aria-labelledby="pc-category-title">
		<div class="p-photo-contest__category-inner">
			<header class="p-photo-contest__section-head">
				<p class="p-photo-contest__section-eyebrow">Prize</p>
				<h2 class="p-photo-contest__section-title" id="pc-category-title">入賞者には七間町の逸品をプレゼント！</h2>
				<p class="p-photo-contest__section-lead">フォトコンテストに入賞された方には、七間町の商店街で使える商品券や、地元の名産品（駿河の工芸品など）を贈呈いたします。</p>
			</header>
			<!-- /.p-photo-contest__section-head -->

			<div class="p-photo-contest__category-grid">
				<?php foreach ( $pc_categories as $cat ) : ?>
				<div class="p-photo-contest__category-card p-photo-contest__category-card--<?php echo esc_attr( strtolower( $cat['key'] ) ); ?>">
					<div class="p-photo-contest__category-card-head">
						<span class="p-photo-contest__category-card-key"><?php echo esc_html( $cat['key'] ); ?>部門</span>
						<div class="p-photo-contest__category-card-titles">
							<p class="p-photo-contest__category-card-name"><?php echo esc_html( $cat['name'] ); ?></p>
							<p class="p-photo-contest__category-card-sub"><?php echo esc_html( $cat['sub'] ); ?></p>
						</div>
						<!-- /.p-photo-contest__category-card-titles -->
					</div>
					<!-- /.p-photo-contest__category-card-head -->
					<ul class="p-photo-contest__category-card-prizes">
						<?php foreach ( $cat['prizes'] as $prize ) : ?>
						<li class="p-photo-contest__prize">
							<span class="p-photo-contest__prize-rank"><?php echo esc_html( $prize['rank'] ); ?></span>
							<span class="p-photo-contest__prize-detail"><?php echo esc_html( $prize['detail'] ); ?></span>
						</li>
						<?php endforeach; ?>
					</ul>
					<!-- /.p-photo-contest__category-card-prizes -->
				</div>
				<!-- /.p-photo-contest__category-card -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-photo-contest__category-grid -->

			<div class="p-photo-contest__category-cta">
				<a class="c-btn c-btn--primary" href="<?php echo esc_url( $pc_form_anchor ); ?>">コンテストに応募する</a>
			</div>
			<!-- /.p-photo-contest__category-cta -->
		</div>
		<!-- /.p-photo-contest__category-inner -->
	</section>
	<!-- /.p-photo-contest__category -->

	<!-- ─── 最新の投稿作品 ── -->
	<section class="p-photo-contest__entries" aria-labelledby="pc-entries-title">
		<div class="p-photo-contest__entries-inner">
			<header class="p-photo-contest__section-head">
				<p class="p-photo-contest__section-eyebrow">Entry</p>
				<h2 class="p-photo-contest__section-title" id="pc-entries-title">最新の投稿作品</h2>
			</header>
			<!-- /.p-photo-contest__section-head -->

			<!-- フィルタタブ -->
			<nav class="c-tabs js-tabs" data-panels=".p-photo-contest__entries-panel" aria-label="部門で絞り込み">
				<div class="c-tabs__inner">
					<ul class="c-tabs__list" role="tablist">
						<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn is-active" role="tab" aria-selected="true" aria-controls="pc-panel-all">すべて</button></li>
						<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="pc-panel-a">A部門（日常とレトロ）</button></li>
						<li class="c-tabs__item" role="presentation"><button type="button" class="c-tabs__btn" role="tab" aria-selected="false" aria-controls="pc-panel-b">B部門（お気に入り）</button></li>
					</ul>
				</div>
				<!-- /.c-tabs__inner -->
			</nav>
			<!-- /.c-tabs -->

			<?php
			// すべて / A / B の3パネルを出力
			$pc_panels = [
				'pc-panel-all' => null,
				'pc-panel-a'   => 'A',
				'pc-panel-b'   => 'B',
			];
			$pc_first = true;
			foreach ( $pc_panels as $panel_id => $filter ) :
			?>
			<div class="p-photo-contest__entries-panel<?php echo $pc_first ? ' is-active' : ''; ?>" id="<?php echo esc_attr( $panel_id ); ?>" role="tabpanel"<?php echo $pc_first ? '' : ' hidden'; ?>>
				<ul class="p-photo-contest__entries-grid">
					<?php foreach ( $pc_entries as $entry ) :
						if ( $filter && $entry['cat'] !== $filter ) continue;
					?>
					<li class="p-photo-contest__entry">
						<div class="p-photo-contest__entry-thumb">
							<img class="u-img-cover" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $entry['img'] ); ?>" alt="" aria-hidden="true" loading="lazy" width="400" height="300">
							<span class="p-photo-contest__entry-badge p-photo-contest__entry-badge--<?php echo esc_attr( strtolower( $entry['cat'] ) ); ?>"><?php echo esc_html( $entry['cat'] ); ?>部門</span>
							<span class="p-photo-contest__entry-like">
								<svg class="p-photo-contest__entry-like-icon" aria-hidden="true" focusable="false"><use href="#icon-heart-solid"></use></svg>
								<?php echo esc_html( $entry['likes'] ); ?>
							</span>
						</div>
						<!-- /.p-photo-contest__entry-thumb -->
						<div class="p-photo-contest__entry-body">
							<h3 class="p-photo-contest__entry-title"><?php echo esc_html( $entry['title'] ); ?></h3>
							<p class="p-photo-contest__entry-author">by <?php echo esc_html( $entry['author'] ); ?></p>
							<p class="p-photo-contest__entry-comment"><?php echo esc_html( $entry['comment'] ); ?></p>
						</div>
						<!-- /.p-photo-contest__entry-body -->
					</li>
					<!-- /.p-photo-contest__entry -->
					<?php endforeach; ?>
				</ul>
				<!-- /.p-photo-contest__entries-grid -->
			</div>
			<!-- /.p-photo-contest__entries-panel -->
			<?php $pc_first = false; endforeach; ?>

			<div class="p-photo-contest__entries-foot">
				<a class="p-photo-contest__entries-more" href="<?php echo esc_url( get_post_type_archive_link( CPT_GALLERY ) ?: home_url( '/gallery/' ) ); ?>">すべての投稿作品を見る →</a>
			</div>
			<!-- /.p-photo-contest__entries-foot -->
		</div>
		<!-- /.p-photo-contest__entries-inner -->
	</section>
	<!-- /.p-photo-contest__entries -->

	<!-- ─── 応募規約 ── -->
	<section class="p-photo-contest__guidelines" aria-labelledby="pc-guidelines-title">
		<div class="p-photo-contest__guidelines-inner">
			<header class="p-photo-contest__section-head">
				<p class="p-photo-contest__section-eyebrow">Guidelines</p>
				<h2 class="p-photo-contest__section-title" id="pc-guidelines-title">応募要項・規約</h2>
			</header>
			<!-- /.p-photo-contest__section-head -->

			<div class="p-photo-contest__guidelines-list">
				<?php foreach ( $pc_guidelines as $g ) : ?>
				<details class="p-photo-contest__guideline">
					<summary class="p-photo-contest__guideline-summary">
						<?php echo esc_html( $g['title'] ); ?>
						<svg class="p-photo-contest__guideline-icon" aria-hidden="true" focusable="false"><use href="#icon-chevron-down"></use></svg>
					</summary>
					<div class="p-photo-contest__guideline-body">
						<p><?php echo esc_html( $g['body'] ); ?></p>
					</div>
					<!-- /.p-photo-contest__guideline-body -->
				</details>
				<!-- /.p-photo-contest__guideline -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-photo-contest__guidelines-list -->
		</div>
		<!-- /.p-photo-contest__guidelines-inner -->
	</section>
	<!-- /.p-photo-contest__guidelines -->

	<!-- ─── 応募フォーム ── -->
	<section class="p-photo-contest__form" id="photo-contest-entry" aria-labelledby="pc-form-title">
		<div class="p-photo-contest__form-inner">
			<header class="p-photo-contest__section-head">
				<p class="p-photo-contest__section-eyebrow">Entry Form</p>
				<h2 class="p-photo-contest__section-title" id="pc-form-title">作品を応募する</h2>
				<p class="p-photo-contest__section-lead">以下のフォームに必要事項を入力し、作品をアップロードしてください。管理者の確認後、ギャラリーに掲載されます。</p>
			</header>
			<!-- /.p-photo-contest__section-head -->

			<!-- ※ 実機化時は CF7 ショートコード等に置換。現状はデザイン用の静的フォーム -->
			<form class="p-photo-contest__form-body" action="#" method="post" enctype="multipart/form-data" onsubmit="return false;">
				<div class="p-photo-contest__form-field">
					<label class="p-photo-contest__form-label" for="pc-nickname">ニックネーム <span class="p-photo-contest__form-required">*</span></label>
					<input class="p-photo-contest__form-input" type="text" id="pc-nickname" name="nickname" required>
				</div>
				<!-- /.p-photo-contest__form-field -->

				<div class="p-photo-contest__form-field">
					<label class="p-photo-contest__form-label" for="pc-email">メールアドレス <span class="p-photo-contest__form-required">*</span></label>
					<input class="p-photo-contest__form-input" type="email" id="pc-email" name="email" required>
				</div>
				<!-- /.p-photo-contest__form-field -->

				<div class="p-photo-contest__form-field">
					<span class="p-photo-contest__form-label">応募部門 <span class="p-photo-contest__form-required">*</span></span>
					<div class="p-photo-contest__form-radios">
						<label class="p-photo-contest__form-radio">
							<input type="radio" name="category" value="A" required>
							<span class="p-photo-contest__form-radio-box">
								<span class="p-photo-contest__form-radio-key">A部門</span>
								<span class="p-photo-contest__form-radio-name">日常とレトロ</span>
							</span>
						</label>
						<label class="p-photo-contest__form-radio">
							<input type="radio" name="category" value="B">
							<span class="p-photo-contest__form-radio-box">
								<span class="p-photo-contest__form-radio-key">B部門</span>
								<span class="p-photo-contest__form-radio-name">お気に入り</span>
							</span>
						</label>
					</div>
					<!-- /.p-photo-contest__form-radios -->
				</div>
				<!-- /.p-photo-contest__form-field -->

				<div class="p-photo-contest__form-field">
					<label class="p-photo-contest__form-label" for="pc-title">作品タイトル <span class="p-photo-contest__form-required">*</span></label>
					<input class="p-photo-contest__form-input" type="text" id="pc-title" name="title" required>
				</div>
				<!-- /.p-photo-contest__form-field -->

				<div class="p-photo-contest__form-field">
					<label class="p-photo-contest__form-label" for="pc-place">撮影場所</label>
					<input class="p-photo-contest__form-input" type="text" id="pc-place" name="place">
				</div>
				<!-- /.p-photo-contest__form-field -->

				<div class="p-photo-contest__form-field">
					<label class="p-photo-contest__form-label" for="pc-comment">写真コメント・エピソード <span class="p-photo-contest__form-required">*</span></label>
					<textarea class="p-photo-contest__form-input p-photo-contest__form-textarea" id="pc-comment" name="comment" rows="4" required></textarea>
				</div>
				<!-- /.p-photo-contest__form-field -->

				<div class="p-photo-contest__form-field">
					<span class="p-photo-contest__form-label">写真ファイル <span class="p-photo-contest__form-required">*</span></span>
					<label class="p-photo-contest__form-file">
						<input type="file" accept="image/jpeg,image/png" hidden>
						<svg class="p-photo-contest__form-file-icon" aria-hidden="true" focusable="false"><use href="#icon-camera"></use></svg>
						<span class="p-photo-contest__form-file-text">クリックまたはドラッグ＆ドロップ</span>
						<span class="p-photo-contest__form-file-note">JPEG / PNG（10MB以内）</span>
					</label>
					<!-- /.p-photo-contest__form-file -->
				</div>
				<!-- /.p-photo-contest__form-field -->

				<p class="p-photo-contest__form-agreement">応募作品は七間町のプロモーション（ウェブサイト、SNS、印刷物等）に無償で使用させていただく場合があります。人物が写っている場合は、必ず被写体の許可を得てからご応募ください。</p>

				<button class="p-photo-contest__form-submit c-btn c-btn--primary" type="submit">作品を応募する</button>
			</form>
			<!-- /.p-photo-contest__form-body -->
		</div>
		<!-- /.p-photo-contest__form-inner -->
	</section>
	<!-- /.p-photo-contest__form -->

	<!-- ─── これまでの投稿作品（アーカイブ） ── -->
	<section class="p-photo-contest__archive" aria-labelledby="pc-archive-title">
		<div class="p-photo-contest__archive-inner">
			<header class="p-photo-contest__section-head">
				<p class="p-photo-contest__section-eyebrow">Archive</p>
				<h2 class="p-photo-contest__section-title" id="pc-archive-title">これまでの投稿作品</h2>
			</header>
			<!-- /.p-photo-contest__section-head -->

			<div class="p-photo-contest__archive-grid">
				<?php foreach ( $pc_archives as $a ) : ?>
				<a class="p-photo-contest__archive-card" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">
					<p class="p-photo-contest__archive-card-year">七間町フォトコン <?php echo esc_html( $a['year'] ); ?></p>
					<p class="p-photo-contest__archive-card-link">投稿作品を見る →</p>
					<p class="p-photo-contest__archive-card-count"><?php echo esc_html( $a['count'] ); ?>作品</p>
				</a>
				<!-- /.p-photo-contest__archive-card -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-photo-contest__archive-grid -->

			<div class="p-photo-contest__archive-foot">
				<a class="p-photo-contest__archive-more" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">過去のフォトコンテスト作品一覧 →</a>
			</div>
			<!-- /.p-photo-contest__archive-foot -->
		</div>
		<!-- /.p-photo-contest__archive-inner -->
	</section>
	<!-- /.p-photo-contest__archive -->

</article>
<!-- /.p-photo-contest -->

<?php get_footer(); ?>
