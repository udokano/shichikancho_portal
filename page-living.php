<?php
/**
 * Template Name: 町に住む
 * 町に住むページ
 */

// postmetaからACFリピーターを直読みするヘルパー
function living_get_repeater( int $pid, string $key ): array {
	$count = (int) get_post_meta( $pid, $key, true );
	if ( $count <= 0 ) return [];
	$all  = get_post_meta( $pid );
	$rows = [];
	for ( $i = 0; $i < $count; $i++ ) {
		$prefix = $key . '_' . $i . '_';
		$row    = [];
		foreach ( $all as $mk => $mv ) {
			if ( str_starts_with( $mk, $prefix ) && ! str_starts_with( $mk, '_' ) ) {
				$row[ substr( $mk, strlen( $prefix ) ) ] = $mv[0];
			}
		}
		$rows[] = $row;
	}
	return $rows;
}

$pid = get_queried_object_id();

// ─── 固定タブ定義（ACFキー / プレフィックス / 表示名 / アイコン / リード）
$living_tabs = [
	[
		'key'        => 'living_family',
		'pre'        => 'lf',
		'label'      => '子育て世代',
		'icon'       => 'icon-school',
		'panel_id'   => 'living-panel-family',
		'lead_title' => '子どもたちが、町ぐるみで育つ。',
		'lead_text'  => '七間町は「みんなで子育て」が当たり前の町。保育園から中学校まで徒歩圏内、公園や児童施設も充実。商店街のおじちゃんおばちゃんが子どもの名前を覚えてくれる、そんな温かさがここにはあります。',
	],
	[
		'key'        => 'living_business',
		'pre'        => 'lb',
		'label'      => 'ビジネスパーソン',
		'icon'       => 'icon-briefcase',
		'panel_id'   => 'living-panel-business',
		'lead_title' => '静岡の真ん中で、自分らしく働く。',
		'lead_text'  => 'リモートワーク、フリーランス、スモールビジネス。働き方が多様化する今、七間町は「暮らしと仕事が一体になる」理想の拠点です。',
	],
	[
		'key'        => 'living_senior',
		'pre'        => 'ls',
		'label'      => 'シニア世代',
		'icon'       => 'icon-heart',
		'panel_id'   => 'living-panel-senior',
		'lead_title' => '歳を重ねるほど、豊かになる町。',
		'lead_text'  => '商店街の賑わいが毎日の活力に。医療・福祉・交通の利便性が高く、老後も安心して暮らせる環境が整っています。',
	],
	[
		'key'        => 'living_access',
		'pre'        => 'lac',
		'label'      => 'アクセス・利便性',
		'icon'       => 'icon-train',
		'panel_id'   => 'living-panel-access',
		'lead_title' => '静岡駅徒歩5分、東京・名古屋も日帰り圏。',
		'lead_text'  => '新幹線・在来線・バスが集まる静岡駅から徒歩5分。都市の便利さを享受しながら、物価や住居費は東京の半分以下。',
	],
	[
		'key'        => 'living_support',
		'pre'        => 'lsu',
		'label'      => '移住支援',
		'icon'       => 'icon-house',
		'panel_id'   => 'living-panel-support',
		'lead_title' => '七間町への移住を、まるごとサポート。',
		'lead_text'  => '住まい探しから就職・起業支援まで、移住者向けの手厚いプログラムが充実。気軽に相談できる窓口も設けています。',
	],
];

// 各タブのACFデータを取得
foreach ( $living_tabs as $idx => &$tab ) {
	$tab['items'] = living_get_repeater( $pid, $tab['key'] );

	// 周辺施設グループ（{pre}_facility_groups リピーター）
	$tab['facility_groups'] = [];
	$pre    = $tab['pre']; // lf / lb / ls / lac / lsu
	$gbase  = "{$pre}_facility_groups";
	$gcount = (int) get_post_meta( $pid, $gbase, true );
	for ( $g = 0; $g < $gcount; $g++ ) {
		$gprefix = "{$gbase}_{$g}_";
		$gtitle  = (string) get_post_meta( $pid, $gprefix . 'group_title', true );
		$icount  = (int) get_post_meta( $pid, $gprefix . 'items', true );
		$items   = [];
		for ( $i = 0; $i < $icount; $i++ ) {
			$ik = "{$gprefix}items_{$i}_";
			$items[] = [
				'name'    => (string) get_post_meta( $pid, $ik . 'name',    true ),
				'address' => (string) get_post_meta( $pid, $ik . 'address', true ),
				'note'    => (string) get_post_meta( $pid, $ik . 'note',    true ),
			];
		}
		if ( $gtitle && $items ) {
			$tab['facility_groups'][] = [ 'title' => $gtitle, 'items' => $items ];
		}
	}
}
unset( $tab );

// 「住んでいる人の声」3名（全タブ共通で PC3カード / SPスライダー表示）
$tab_voices = [
	'living_family'   => [ 'quote' => '東京から移住して3年。子どもが「おはよう」って商店街の人に声をかけるのを見ると、ここに来てよかったって心から思います。', 'author' => '田中 美咲さん（32歳・2児の母）' ],
	'living_business' => [ 'quote' => 'フリーランスのエンジニアです。東京と同じ仕事をしながら、生活の質は格段に上がりました。朝は駿府城公園をランニングして、昼はコワーキングで集中。夜は商店街の行きつけの居酒屋で仲間と一杯。この暮らしが手に入るなんて。', 'author' => '佐藤 健太さん（28歳・フリーランスエンジニア）' ],
	'living_senior'   => [ 'quote' => '定年後、東京から引っ越してきました。毎朝の散歩で商店街の人と挨拶を交わし、午後は囲碁クラブへ。夕方は行きつけの居酒屋で一杯。こんなに穏やかで充実した日々が待っているとは思いませんでした。', 'author' => '山田 義雄さん（68歳・元会社員）' ],
];

// ─── タブ固有の追加コンテンツ（Manus準拠ハードコード）
$tab_extras = [
	'living_business' => [
		'workspaces' => [
			[ 'type' => 'コワーキングスペース', 'name' => 'コワーキング七間町', 'price' => '月額15,000円〜 / ドロップイン1,500円/日', 'hours' => '7:00〜22:00', 'tags' => [ 'Wi-Fi', '会議室', 'ロッカー', 'フリードリンク', 'プリンター' ] ],
			[ 'type' => 'シェアオフィス',     'name' => 'シェアオフィス駿府', 'price' => '月額30,000円〜（個室）',                 'hours' => '24時間',    'tags' => [ '専用デスク', '法人登記可', '郵便受取', '24時間利用', 'ラウンジ' ] ],
			[ 'type' => 'ワークカフェ',       'name' => 'カフェ＆ワーク常磐', 'price' => 'ドリンク代のみ（500円〜）',             'hours' => '8:00〜20:00', 'tags' => [ 'Wi-Fi', '電源席', '静かな環境', '軽食あり', 'テラス席' ] ],
			[ 'type' => 'ライブラリースペース', 'name' => 'Library Lounge 葵',  'price' => '月額8,000円 / ドロップイン800円/日',     'hours' => '9:00〜21:00', 'tags' => [ 'Wi-Fi', '電源', '書籍読み放題', '静粛エリア' ] ],
		],
		'cost_table' => [
			'headers' => [ '項目', '東京', '七間町' ],
			'rows'    => [
				[ '家賃（1LDK）',           '12〜15万円',   '5〜7万円' ],
				[ '家賃（2LDK）',           '18〜25万円',   '7〜10万円' ],
				[ 'ランチ',                 '1,000〜1,500円', '600〜900円' ],
				[ '保育料（月額）',         '5〜8万円',     '2〜4万円' ],
				[ 'コワーキング（月額）',   '3〜5万円',     '1.5〜3万円' ],
				[ '駐車場（月極）',         '3〜5万円',     '0.8〜1.5万円' ],
			],
		],
		'events' => [
			[ 'frequency' => '毎月第2水曜日', 'name' => '七間町ビジネス交流会', 'description' => 'フリーランス・起業家・副業ワーカーが集まるカジュアルな交流会' ],
			[ 'frequency' => '年4回',        'name' => '静岡スタートアップピッチ', 'description' => '地元企業・投資家へのプレゼンイベント' ],
			[ 'frequency' => '毎月第4金曜日', 'name' => 'Tech Meetup Shizuoka', 'description' => 'エンジニア・デザイナーのLT会＆懇親会' ],
			[ 'frequency' => '毎月第3火曜日', 'name' => '女性起業家ランチ会', 'description' => '女性経営者・フリーランスの情報交換' ],
		],
	],
	'living_access' => [
		'stats' => [
			[ 'label' => '静岡駅',         'value' => '徒歩5分',   'icon' => 'icon-train' ],
			[ 'label' => '駿府城公園',     'value' => '徒歩10分',  'icon' => 'icon-park' ],
			[ 'label' => '商店街',         'value' => '徒歩0分',   'icon' => 'icon-map-pin' ],
			[ 'label' => '治安',           'value' => '良好',      'icon' => 'icon-shield' ],
			[ 'label' => '病院',           'value' => '徒歩圏内',  'icon' => 'icon-medical' ],
			[ 'label' => '学校',           'value' => '徒歩圏内',  'icon' => 'icon-school' ],
			[ 'label' => 'カフェ',         'value' => '10店舗+',   'icon' => 'icon-cafe' ],
			[ 'label' => '住民満足度',     'value' => '92%',       'icon' => 'icon-heart' ],
		],
		'trains' => [
			[ 'label' => '東京',   'value' => '約60分',  'note' => 'ひかり' ],
			[ 'label' => '名古屋', 'value' => '約60分',  'note' => 'ひかり' ],
			[ 'label' => '大阪',   'value' => '約120分', 'note' => 'ひかり' ],
		],
		'facilities' => [
			[ 'label' => 'スーパー（複数）', 'desc' => '日常の買い物に困りません',     'note' => '徒歩3〜5分' ],
			[ 'label' => 'コンビニ',         'desc' => '24時間営業',                   'note' => '徒歩1分' ],
			[ 'label' => '銀行・ATM',        'desc' => '主要銀行が揃っています',       'note' => '徒歩3分' ],
			[ 'label' => '郵便局',           'desc' => '静岡郵便局',                   'note' => '徒歩5分' ],
			[ 'label' => '図書館',           'desc' => '静岡市立中央図書館',           'note' => '徒歩10分' ],
			[ 'label' => '市役所',           'desc' => '葵区役所',                     'note' => '徒歩8分' ],
		],
	],
	'living_support' => [
		'cta' => [
			'title'      => '七間町で、あなたらしい暮らしを始めませんか？',
			'text'       => 'まずは気軽にご相談ください。住まい探しから生活設計まで、ワンストップでサポートします。',
			'btn_primary'   => [ 'label' => '移住相談をする', 'url' => home_url( '/contact/' ) ],
			'btn_secondary' => [ 'label' => '電話で相談する', 'url' => 'tel:054-000-0000' ],
			'notes' => [ '相談無料', 'しつこい営業なし', '資料請求だけでもOK' ],
		],
	],
];
foreach ( $living_tabs as &$tab ) {
	$tab['extras'] = $tab_extras[ $tab['key'] ] ?? [];
}
unset( $tab );

// 機能タイトル→アイコンマップ（Manus準拠の差し替え）
$feature_icon_map = [
	// 子育て世代
	'教育施設'   => 'icon-school',
	'公園'       => 'icon-park',
	'子育て支援' => 'icon-heart',
	'地域コミュニティ' => 'icon-users-solid',
	'習い事'     => 'icon-star',
	'小児科'     => 'icon-medical',
	// ビジネスパーソン
	'静岡駅'     => 'icon-train',
	'コワーキング' => 'icon-wifi',
	'生活コスト' => 'icon-yen',
	'起業家'     => 'icon-people',
	'ライフスタイル' => 'icon-sparkles',
	'副業'       => 'icon-briefcase',
	// シニア世代
	'医療機関'   => 'icon-medical',
	'買い物'     => 'icon-shopping-bag',
	'コミュニティ活動' => 'icon-users-solid',
	'介護'       => 'icon-heart',
	'バリアフリー' => 'icon-check',
	'見守り'     => 'icon-shield',
	// アクセス・利便性
	'アクセス'   => 'icon-train',
	'駿府城'     => 'icon-park',
	'商店街'     => 'icon-store',
	'治安'       => 'icon-shield',
	'病院'       => 'icon-medical',
	'学校'       => 'icon-school',
	'カフェ'     => 'icon-cafe',
	'公共施設'   => 'icon-building',
	'銀行'       => 'icon-yen',
	// 移住支援
	'移住支援金' => 'icon-yen',
	'住まい'     => 'icon-house',
	'お試し'     => 'icon-bed',
	'就職'       => 'icon-briefcase',
];
function living_pick_icon( string $title, string $fallback, array $map ): string {
	foreach ( $map as $key => $icon ) {
		if ( str_contains( $title, $key ) ) return $icon;
	}
	return $fallback;
}

get_header();
?>

<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

<article class="p-living">

	<?php
	get_template_part( 'template-parts/components/page-hero', null, [
		'title' => '町に住む',
		'sub'   => '七間町で、あなたらしい暮らしを見つける。',
	] );
	?>

	<!-- ─── イントロ ── -->
	<section class="p-living__intro" aria-labelledby="living-intro-title">
		<div class="p-living__intro-inner">
			<h2 class="p-living__intro-title" id="living-intro-title">世代を超えて、選ばれる町</h2>
			<p class="p-living__intro-text">都市の利便性と歴史ある町並みの調和。静岡駅徒歩5分、商店街が生活圏。子育て世代からシニアまで、それぞれの暮らし方をご紹介します。</p>
		</div>
		<!-- /.p-living__intro-inner -->
	</section>
	<!-- /.p-living__intro -->

	<!-- ─── カテゴリータブ ── -->
	<nav class="c-tabs js-tabs" data-panels=".p-living__panel" aria-label="暮らし方カテゴリー">
		<div class="c-tabs__inner">
			<ul class="c-tabs__list" role="tablist">
				<?php foreach ( $living_tabs as $i => $tab ) : ?>
				<li class="c-tabs__item" role="presentation">
					<button
						type="button"
						class="c-tabs__btn<?php echo $i === 0 ? ' is-active' : ''; ?>"
						role="tab"
						aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
						aria-controls="<?php echo esc_attr( $tab['panel_id'] ); ?>">
						<svg class="c-tabs__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><use href="#<?php echo esc_attr( $tab['icon'] ); ?>"></use></svg>
						<?php echo esc_html( $tab['label'] ); ?>
					</button>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<!-- /.c-tabs__inner -->
	</nav>
	<!-- /.c-tabs -->

	<!-- ─── タブパネル ── -->
	<?php foreach ( $living_tabs as $i => $tab ) :
		$items = $tab['items'];
		$pre   = $tab['pre'];
	?>
	<section
		class="p-living__panel<?php echo $i === 0 ? ' is-active' : ''; ?>"
		id="<?php echo esc_attr( $tab['panel_id'] ); ?>"
		role="tabpanel"
		<?php echo $i !== 0 ? 'hidden' : ''; ?>>
		<div class="p-living__panel-inner">

			<h2 class="p-living__panel-title"><?php echo esc_html( $tab['lead_title'] ); ?></h2>
			<p class="p-living__panel-lead"><?php echo esc_html( $tab['lead_text'] ); ?></p>

			<?php if ( $items ) : ?>
			<div class="p-living__features">
				<?php
				foreach ( $items as $item ) :
					$title = $item[ $pre . '_title' ] ?? '';
					$text  = $item[ $pre . '_text' ]  ?? '';
					$icon  = living_pick_icon( $title, $tab['icon'], $feature_icon_map );
				?>
				<div class="p-living__feature">
					<span class="p-living__feature-icon" aria-hidden="true">
						<svg class="p-living__feature-icon-svg" focusable="false"><use href="#<?php echo esc_attr( $icon ); ?>"></use></svg>
					</span>
					<h3 class="p-living__feature-title"><?php echo esc_html( $title ); ?></h3>
					<?php if ( $text ) : ?>
					<p class="p-living__feature-text"><?php echo wp_kses( $text, [ 'br' => [] ] ); ?></p>
					<?php endif; ?>
				</div>
				<!-- /.p-living__feature -->
				<?php endforeach; ?>
			</div>
			<!-- /.p-living__features -->
			<?php else : ?>
			<p class="p-living__panel-empty">このカテゴリーには現在登録がありません。</p>
			<?php endif; ?>

			<?php if ( ! empty( $tab['facility_groups'] ) ) :
				// タブ毎の見出し
				$facility_titles = [
					'living_family'   => '周辺の教育・子育て施設',
					'living_business' => '仕事ができる場所',
					'living_senior'   => '周辺の医療・福祉施設',
					'living_access'   => '周辺の主な施設',
					'living_support'  => '相談・申込み窓口',
				];
				$facility_title = $facility_titles[ $tab['key'] ] ?? '周辺の施設一覧';
			?>
			<section class="p-living__listing" aria-label="<?php echo esc_attr( $facility_title ); ?>">
				<h3 class="p-living__listing-title"><?php echo esc_html( $facility_title ); ?></h3>
				<?php foreach ( $tab['facility_groups'] as $group ) : ?>
				<div class="p-living__listing-group">
					<h4 class="p-living__listing-group-title"><?php echo esc_html( $group['title'] ); ?></h4>
					<ul class="p-living__listing-rows">
						<?php foreach ( $group['items'] as $item ) : ?>
						<li class="p-living__listing-row">
							<div class="p-living__listing-row-main">
								<p class="p-living__listing-row-name"><?php echo esc_html( $item['name'] ); ?></p>
								<?php if ( $item['address'] ) : ?>
								<p class="p-living__listing-row-sub"><?php echo esc_html( $item['address'] ); ?></p>
								<?php endif; ?>
							</div>
							<?php if ( $item['note'] ) : ?>
							<p class="p-living__listing-row-note"><?php echo esc_html( $item['note'] ); ?></p>
							<?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<!-- /.p-living__listing-group -->
				<?php endforeach; ?>
			</section>
			<!-- /.p-living__listing -->
			<?php endif; ?>

			<?php // ─── タブ固有: アクセス・利便性 統計カード ─── ?>
			<?php if ( ! empty( $tab['extras']['stats'] ) ) : ?>
			<section class="p-living__stats" aria-label="利便性ハイライト">
				<ul class="p-living__stats-grid">
					<?php foreach ( $tab['extras']['stats'] as $s ) :
						$s_icon = $s['icon'] ?? $tab['icon'];
					?>
					<li class="p-living__stat">
						<span class="p-living__stat-icon" aria-hidden="true">
							<svg class="p-living__stat-icon-svg" focusable="false"><use href="#<?php echo esc_attr( $s_icon ); ?>"></use></svg>
						</span>
						<p class="p-living__stat-label"><?php echo esc_html( $s['label'] ); ?></p>
						<p class="p-living__stat-value"><?php echo esc_html( $s['value'] ); ?></p>
					</li>
					<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>

			<?php // ─── タブ固有: 新幹線アクセス ─── ?>
			<?php if ( ! empty( $tab['extras']['trains'] ) ) : ?>
			<section class="p-living__train-access" aria-label="新幹線アクセス">
				<h3 class="p-living__train-access-title">新幹線アクセス</h3>
				<ul class="p-living__train-access-grid">
					<?php foreach ( $tab['extras']['trains'] as $t ) : ?>
					<li class="p-living__train">
						<p class="p-living__train-label"><?php echo esc_html( $t['label'] ); ?></p>
						<p class="p-living__train-value"><?php echo esc_html( $t['value'] ); ?></p>
						<?php if ( ! empty( $t['note'] ) ) : ?>
						<p class="p-living__train-note"><?php echo esc_html( $t['note'] ); ?></p>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>

			<?php // ─── タブ固有: 生活利便施設 ─── ?>
			<?php if ( ! empty( $tab['extras']['facilities'] ) ) : ?>
			<section class="p-living__stats" aria-label="生活利便施設">
				<h3 class="p-living__train-access-title">生活利便施設</h3>
				<ul class="p-living__stats-grid">
					<?php foreach ( $tab['extras']['facilities'] as $f ) : ?>
					<li class="p-living__stat">
						<p class="p-living__stat-label"><?php echo esc_html( $f['label'] ); ?></p>
						<p class="p-living__stat-value"><?php echo esc_html( $f['desc'] ); ?></p>
						<?php if ( ! empty( $f['note'] ) ) : ?>
						<p class="p-living__train-note p-living__train-note--spaced"><?php echo esc_html( $f['note'] ); ?></p>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
				</ul>
			</section>
			<?php endif; ?>

			<?php // ─── タブ固有: 仕事ができる場所（共通 listing スタイル）─ ?>
			<?php if ( ! empty( $tab['extras']['workspaces'] ) ) : ?>
			<section class="p-living__listing" aria-label="仕事ができる場所">
				<h3 class="p-living__listing-title">仕事ができる場所</h3>
				<div class="p-living__listing-grid">
					<?php foreach ( $tab['extras']['workspaces'] as $w ) : ?>
					<article class="p-living__card p-living__card--has-badge">
						<span class="p-living__card-badge"><?php echo esc_html( $w['type'] ); ?></span>
						<h4 class="p-living__card-name p-living__card-name--lg"><?php echo esc_html( $w['name'] ); ?></h4>
						<p class="p-living__card-price"><?php echo esc_html( $w['price'] ); ?></p>
						<p class="p-living__card-meta">
							<svg class="p-living__card-meta-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-clock"></use></svg><?php echo esc_html( $w['hours'] ); ?>
						</p>
						<?php if ( ! empty( $w['tags'] ) ) : ?>
						<ul class="p-living__card-tags">
							<?php foreach ( $w['tags'] as $tg ) : ?>
							<li class="p-living__card-tag"><?php echo esc_html( $tg ); ?></li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>
					</article>
					<?php endforeach; ?>
				</div>
			</section>
			<?php endif; ?>

			<?php // ─── タブ固有: 東京 vs 七間町 生活コスト比較 ─── ?>
			<?php if ( ! empty( $tab['extras']['cost_table'] ) ) :
				$ct = $tab['extras']['cost_table'];
			?>
			<section class="p-living__cost-compare" aria-label="生活コスト比較">
				<h3 class="p-living__cost-compare-title">東京 vs 七間町 生活コスト比較</h3>
				<div class="p-living__cost-compare-table-wrap">
					<table class="p-living__cost-compare-table">
						<thead>
							<tr>
								<?php foreach ( $ct['headers'] as $h ) : ?>
								<th><?php echo esc_html( $h ); ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $ct['rows'] as $row ) : ?>
							<tr>
								<th><?php echo esc_html( $row[0] ); ?></th>
								<?php for ( $i = 1; $i < count( $row ); $i++ ) : ?>
								<td><?php echo esc_html( $row[ $i ] ); ?></td>
								<?php endfor; ?>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</section>
			<?php endif; ?>

			<?php // ─── タブ固有: ネットワーキング・交流イベント ─── ?>
			<?php if ( ! empty( $tab['extras']['events'] ) ) : ?>
			<section class="p-living__events" aria-label="ネットワーキング・交流イベント">
				<h3 class="p-living__events-title">ネットワーキング・交流イベント</h3>
				<div class="p-living__events-grid">
					<?php foreach ( $tab['extras']['events'] as $e ) : ?>
					<article class="p-living__event">
						<span class="p-living__event-frequency"><?php echo esc_html( $e['frequency'] ); ?></span>
						<h4 class="p-living__event-name"><?php echo esc_html( $e['name'] ); ?></h4>
						<p class="p-living__event-description"><?php echo esc_html( $e['description'] ); ?></p>
					</article>
					<?php endforeach; ?>
				</div>
			</section>
			<?php endif; ?>

			<?php // ─── タブ固有: 移住支援 CTA ─── ?>
			<?php if ( ! empty( $tab['extras']['cta'] ) ) :
				$cta = $tab['extras']['cta'];
			?>
			<section class="p-living__final-cta" aria-label="移住相談">
				<h3 class="p-living__final-cta-title"><?php echo esc_html( $cta['title'] ); ?></h3>
				<p class="p-living__final-cta-text"><?php echo esc_html( $cta['text'] ); ?></p>
				<div class="p-living__final-cta-buttons">
					<a class="p-living__final-cta-btn p-living__final-cta-btn--primary" href="<?php echo esc_url( $cta['btn_primary']['url'] ); ?>">
						<svg class="p-living__final-cta-btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-mail"></use></svg>
						<?php echo esc_html( $cta['btn_primary']['label'] ); ?>
					</a>
					<a class="p-living__final-cta-btn p-living__final-cta-btn--secondary" href="<?php echo esc_url( $cta['btn_secondary']['url'] ); ?>">
						<svg class="p-living__final-cta-btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#icon-phone"></use></svg>
						<?php echo esc_html( $cta['btn_secondary']['label'] ); ?>
					</a>
				</div>
				<?php if ( ! empty( $cta['notes'] ) ) : ?>
				<ul class="p-living__final-cta-notes">
					<?php foreach ( $cta['notes'] as $n ) : ?>
					<li class="p-living__final-cta-note"><?php echo esc_html( $n ); ?></li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</section>
			<?php endif; ?>

		</div>
		<!-- /.p-living__panel-inner -->
	</section>
	<!-- /.p-living__panel -->
	<?php endforeach; ?>

	<!-- ─── 住んでいる人の声（全タブ共通・PC3カード / SPスライダー）── -->
	<section class="p-living__voices" aria-label="住んでいる人の声">
		<div class="p-living__voices-inner">
			<header class="p-living__voices-head">
				<p class="p-living__voices-eyebrow">VOICE</p>
				<h2 class="p-living__voices-title">住んでいる人の声</h2>
			</header>
			<!-- /.p-living__voices-head -->
			<ul class="p-living__voices-grid js-center-slider">
				<?php foreach ( $tab_voices as $voice ) : ?>
				<li class="p-living__voices-card">
					<figure class="p-living__voices-figure">
						<div class="p-living__voices-avatar" aria-hidden="true">
							<svg class="p-living__voices-avatar-icon" focusable="false" aria-hidden="true"><use href="#icon-users-solid"></use></svg>
						</div>
						<!-- /.p-living__voices-avatar -->
						<blockquote class="p-living__voices-quote">「<?php echo esc_html( $voice['quote'] ); ?>」</blockquote>
						<figcaption class="p-living__voices-author"><?php echo esc_html( $voice['author'] ); ?></figcaption>
					</figure>
				</li>
				<?php endforeach; ?>
			</ul>
			<!-- /.p-living__voices-grid -->
		</div>
		<!-- /.p-living__voices-inner -->
	</section>
	<!-- /.p-living__voices -->

</article>
<!-- /.p-living -->

<?php get_footer(); ?>
