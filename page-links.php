<?php
/** 関連リンクページ */
get_header();

// カテゴリー別リンク定義
$link_groups = [
	[
		'label' => '行政',
		'links' => [
			[ 'name' => '静岡市公式サイト',  'url' => 'https://www.city.shizuoka.lg.jp/' ],
			[ 'name' => '静岡県公式サイト',  'url' => 'https://www.pref.shizuoka.jp/' ],
			[ 'name' => '静岡市観光協会',    'url' => 'https://www.shizuoka-kankou.jp/' ],
		],
	],
	[
		'label' => '交通',
		'links' => [
			[ 'name' => 'JR東海',              'url' => 'https://jr-central.co.jp/' ],
			[ 'name' => '静岡鉄道',            'url' => 'https://www.shizutetsu.co.jp/' ],
			[ 'name' => 'しずてつジャストライン', 'url' => 'https://justline.co.jp/' ],
		],
	],
	[
		'label' => '観光',
		'links' => [
			[ 'name' => 'するが企画観光局', 'url' => 'https://surugawan.net/' ],
			[ 'name' => '静岡市美術館',    'url' => 'https://www.shizubi.jp/' ],
			[ 'name' => '駿府城公園',      'url' => 'https://sumpu-castlepark.com/' ],
		],
	],
	[
		'label' => '商店街',
		'links' => [
			[ 'name' => '呉服町商店街',       'url' => 'https://gofukucho.com/' ],
			[ 'name' => '人宿町商店街',       'url' => 'https://hitoyado.com/' ],
			[ 'name' => '静岡市商店街連合会', 'url' => 'https://shizuoka-shotengai.jp/' ],
		],
	],
];
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-links">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '関連リンク',
		'sub'   => '七間町に関連する便利なリンク集です。',
	] );
	?>

	<div class="p-links__body">
		<div class="p-links__inner">

			<?php foreach ( $link_groups as $group ) : ?>
			<section class="p-links__group">
				<h2 class="p-links__group-title"><?php echo esc_html( $group['label'] ); ?></h2>
				<ul class="p-links__grid" role="list">
					<?php foreach ( $group['links'] as $link ) : ?>
					<li>
						<a class="p-links__card" href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer">
							<svg class="p-links__card-icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
							<span class="p-links__card-name"><?php echo esc_html( $link['name'] ); ?></span>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</section>
			<!-- /.p-links__group -->
			<?php endforeach; ?>

		</div>
		<!-- /.p-links__inner -->
	</div>
	<!-- /.p-links__body -->

</article>
<!-- /.p-links -->

<?php get_footer(); ?>
