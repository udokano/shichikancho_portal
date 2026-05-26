<?php
/**
 * LLMO（LLM最適化）・AIクローラー対応
 * - llms.txt エンドポイント
 * - robots.txt AIクローラールール
 * - AI 向け meta タグ
 */

// ─── llms.txt エンドポイント ─────────────────────────────

add_action( 'init', 'sc_llms_txt_rewrite' );

function sc_llms_txt_rewrite(): void {
	// nginx がトレイリングスラッシュを付与するケースに対応
	add_rewrite_rule( '^llms\.txt/?$',      'index.php?sc_llms_txt=1',      'top' );
	add_rewrite_rule( '^llms-full\.txt/?$', 'index.php?sc_llms_full_txt=1', 'top' );
}

add_filter( 'query_vars', function( array $vars ): array {
	$vars[] = 'sc_llms_txt';
	$vars[] = 'sc_llms_full_txt';
	return $vars;
} );

add_action( 'template_redirect', 'sc_llms_txt_output' );

function sc_llms_txt_output(): void {
	if ( get_query_var( 'sc_llms_txt' ) ) {
		header( 'Content-Type: text/plain; charset=UTF-8' );
		header( 'Cache-Control: public, max-age=86400' );
		echo sc_get_llms_txt_content();
		exit;
	}

	if ( get_query_var( 'sc_llms_full_txt' ) ) {
		header( 'Content-Type: text/plain; charset=UTF-8' );
		// full版はサイズが大きいためキャッシュ時間を短めに
		header( 'Cache-Control: public, max-age=21600' );
		echo sc_get_llms_full_txt_content();
		exit;
	}
}

/**
 * AI 向けメタ要約を取得
 * 優先順: post_excerpt → post_content（先頭30語）
 */
function sc_ai_summary( int $post_id, int $words = 30 ): string {
	$post = get_post( $post_id );
	if ( ! $post ) return '';

	if ( $post->post_excerpt ) return trim( $post->post_excerpt );

	return wp_trim_words( wp_strip_all_tags( $post->post_content ), $words, '…' );
}

/**
 * サイト全体の AI 向け要約
 * 優先順: option sc_ai_summary → bloginfo description
 */
function sc_site_ai_summary(): string {
	$opt = (string) get_option( 'sc_ai_summary', '' );
	if ( $opt !== '' ) return $opt;
	return (string) get_bloginfo( 'description' );
}

function sc_get_llms_txt_content(): string {
	$url       = home_url( '/' );
	$site_name = get_bloginfo( 'name' );
	$tagline   = sc_site_ai_summary();

	$out  = "# {$site_name} — llms.txt\n\n";
	$out .= "> {$tagline}\n\n";

	// 引用ブロック直後の平文詳細（llms.txt仕様の「追加プロジェクト詳細」セクション）
	$out .= SC_ORG_NAME . "が運営する七間町商店街の公式Webサイト。";
	$out .= "静岡市葵区・七間町（〒" . SC_ADDRESS['postalCode'] . "）に位置し、「映画の町」として知られる歴史ある商店街エリアの情報ポータルです。";
	$out .= "観光客・住民・事業者の三者に向けて、お店・イベント・観光スポット・暮らし情報を発信しています。";
	$out .= "「和紙の記憶」をデザインコンセプトに、町の時間・記憶・人・風景をたどる回遊体験を提供します。\n\n";

	// ─── サイト詳細 ─────────────────────────────────────
	$out .= "## サイト詳細\n\n";
	$out .= "- **サイト名**: {$site_name}\n";
	$out .= '- **運営**: ' . SC_ORG_NAME . "\n";
	$out .= '- **所在地**: 〒' . SC_ADDRESS['postalCode'] . ' ' . SC_ADDRESS['addressRegion'] . SC_ADDRESS['addressLocality'] . SC_ADDRESS['streetAddress'] . "\n";
	$out .= "- **公式URL**: {$url}\n";
	$out .= "- **最寄り駅**: JR東海道新幹線・東海道本線「静岡駅」徒歩約10分\n";
	$out .= '- **座標**: ' . SC_GEO['latitude'] . ', ' . SC_GEO['longitude'] . "\n";
	$out .= "- **コンセプト**: 「和紙の記憶」── 映画の町として知られる歴史ある商店街。飲食・物販・文化が共存するエリア。\n";
	$out .= "- **キーワード**: 七間町, しちけんちょう, 静岡市葵区, 静岡商店街, 映画の町, 静岡観光, 地域ポータル\n\n";

	// ─── 固定ページ ─────────────────────────────────────
	$pages = get_pages( [
		'sort_column' => 'menu_order,post_title',
		'parent'      => 0,
		'post_status' => 'publish',
	] );
	if ( $pages ) {
		$out .= "## 主要ページ\n\n";
		foreach ( $pages as $page ) {
			$permalink = get_permalink( $page->ID );
			$title     = get_the_title( $page->ID );
			$summary   = sc_ai_summary( $page->ID, 30 );
			$line      = "- [{$title}]({$permalink})";
			if ( $summary ) {
				$line .= " — {$summary}";
			}
			$out .= $line . "\n";
		}
		$out .= "\n";
	}

	// ─── CPT 別最新コンテンツ ────────────────────────────
	$cpt_sections = [
		CPT_SHOP     => [ 'label' => 'お店',           'limit' => 10 ],
		CPT_EVENT    => [ 'label' => 'イベント',       'limit' => 10 ],
		CPT_SPOT     => [ 'label' => 'スポット',       'limit' => 10 ],
		CPT_COLUMN   => [ 'label' => 'コラム',         'limit' => 10 ],
		CPT_RESIDENT => [ 'label' => 'お隣さんの話',   'limit' => 5  ],
		CPT_LEARN    => [ 'label' => '学ぶ施設',       'limit' => 5  ],
		CPT_JOB      => [ 'label' => '求人',           'limit' => 5  ],
		CPT_PROPERTY => [ 'label' => '空き物件',       'limit' => 5  ],
		CPT_NEWS     => [ 'label' => 'インフォメーション', 'limit' => 10 ],
	];

	foreach ( $cpt_sections as $cpt => $conf ) {
		if ( ! post_type_exists( $cpt ) ) continue;

		$archive = get_post_type_archive_link( $cpt );
		$query   = new WP_Query( [
			'post_type'      => $cpt,
			'posts_per_page' => $conf['limit'],
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		] );

		if ( ! $query->have_posts() ) continue;

		$out .= "## {$conf['label']}";
		if ( $archive ) {
			$out .= " — [一覧]({$archive})";
		}
		$out .= "\n\n";

		foreach ( $query->posts as $p ) {
			$permalink = get_permalink( $p->ID );
			$title     = get_the_title( $p->ID );
			$summary   = sc_ai_summary( $p->ID, 25 );
			$line      = "- [{$title}]({$permalink})";
			if ( $summary ) {
				$line .= " — {$summary}";
			}
			$out .= $line . "\n";
		}
		$out .= "\n";

		wp_reset_postdata();
	}

	// ─── 地域情報 ───────────────────────────────────────
	$out .= "## 地域情報\n\n";
	$out .= "- **最寄り駅**: JR東海道新幹線・東海道本線「静岡駅」徒歩約10分\n";
	$out .= '- **緯度経度**: ' . SC_GEO['latitude'] . ', ' . SC_GEO['longitude'] . "\n";
	$out .= "- **特徴**: 映画の町として知られる歴史ある商店街。静岡市中心部に位置し、飲食・物販・文化施設が集積。\n";
	$out .= "- **キーワード**: 七間町, しちけんちょう, 静岡市葵区, 静岡商店街, 映画の町, 静岡観光, 地域ポータル\n\n";

	// ─── 利用条件 ───────────────────────────────────────
	$out .= "## 利用条件\n\n";
	$out .= 'このサイトのコンテンツは ' . SC_ORG_NAME . " に帰属します。\n";
	$out .= "AI学習・要約・引用の際は出典として「{$site_name}（{$url}）」を明記してください。\n\n";
	$out .= '_最終生成: ' . wp_date( 'Y-m-d H:i' ) . "_\n";

	return $out;
}

// ─── llms-full.txt：全文コンテンツ ──────────────────────

function sc_get_llms_full_txt_content(): string {
	$url       = home_url( '/' );
	$site_name = get_bloginfo( 'name' );
	$tagline   = sc_site_ai_summary();

	$out  = "# {$site_name} — llms-full.txt\n\n";
	$out .= "> {$tagline}\n\n";
	$out .= SC_ORG_NAME . "が運営する七間町商店街の公式サイト全文コンテンツ。";
	$out .= "AIエージェントが詳細な質問に回答するための完全版ドキュメントです。\n\n";
	$out .= "概要版: {$url}llms.txt\n\n";
	$out .= "---\n\n";

	// ─── 固定ページ全文 ─────────────────────────────────
	$pages = get_pages( [
		'sort_column' => 'menu_order,post_title',
		'parent'      => 0,
		'post_status' => 'publish',
	] );
	if ( $pages ) {
		foreach ( $pages as $page ) {
			$permalink = get_permalink( $page->ID );
			$title     = get_the_title( $page->ID );
			$content   = wp_strip_all_tags( apply_filters( 'the_content', $page->post_content ) );
			$content   = preg_replace( '/\n{3,}/', "\n\n", trim( $content ) );

			$out .= "## {$title}\n\n";
			$out .= "URL: {$permalink}\n\n";
			if ( $content ) {
				$out .= $content . "\n\n";
			}
			$out .= "---\n\n";
		}
	}

	// ─── CPT 全文 ────────────────────────────────────────
	$cpt_sections = [
		CPT_SHOP     => [ 'label' => 'お店',         'limit' => 100 ],
		CPT_EVENT    => [ 'label' => 'イベント',     'limit' => 50  ],
		CPT_SPOT     => [ 'label' => 'スポット',     'limit' => 50  ],
		CPT_COLUMN   => [ 'label' => 'コラム',       'limit' => 30  ],
		CPT_RESIDENT => [ 'label' => 'お隣さんの話', 'limit' => 20  ],
		CPT_LEARN    => [ 'label' => '学ぶ施設',     'limit' => 30  ],
		CPT_WALK     => [ 'label' => '散策コース',   'limit' => 20  ],
		CPT_NEWS     => [ 'label' => 'インフォメーション', 'limit' => 30  ],
		CPT_JOB      => [ 'label' => '求人',         'limit' => 20  ],
		CPT_PROPERTY => [ 'label' => '空き物件',     'limit' => 20  ],
	];

	foreach ( $cpt_sections as $cpt => $conf ) {
		if ( ! post_type_exists( $cpt ) ) continue;

		$archive = get_post_type_archive_link( $cpt );
		$query   = new WP_Query( [
			'post_type'      => $cpt,
			'posts_per_page' => $conf['limit'],
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		] );

		if ( ! $query->have_posts() ) continue;

		$out .= "# {$conf['label']}";
		if ( $archive ) {
			$out .= "（一覧: {$archive}）";
		}
		$out .= "\n\n";

		foreach ( $query->posts as $p ) {
			$permalink = get_permalink( $p->ID );
			$title     = get_the_title( $p->ID );
			$content   = wp_strip_all_tags( apply_filters( 'the_content', $p->post_content ) );
			$content   = preg_replace( '/\n{3,}/', "\n\n", trim( $content ) );
			$excerpt   = $p->post_excerpt ? trim( $p->post_excerpt ) : '';

			$out .= "## {$title}\n\n";
			$out .= "URL: {$permalink}\n\n";
			if ( $excerpt ) {
				$out .= "{$excerpt}\n\n";
			}
			if ( $content ) {
				$out .= $content . "\n\n";
			}
			$out .= "---\n\n";
		}

		wp_reset_postdata();
	}

	$out .= '_最終生成: ' . wp_date( 'Y-m-d H:i' ) . "_\n";

	return $out;
}

// ─── robots.txt AIクローラールール追加 ──────────────────

// AIOSEO の Sitemap 行（sitemap.rss を指している）を sitemap.xml に置換
// + AI クローラー Allow 追記
add_filter( 'robots_txt', 'sc_robots_txt_ai', 10001, 2 );

function sc_robots_txt_ai( string $output, bool $public ): string {
	if ( ! $public ) return $output;

	// AIOSEO 等が出した Sitemap 行（rss / 旧形式含む）を全て除去
	$output = preg_replace( '/^\s*Sitemap:.*$/mi', '', $output );

	// llms.txt への誘導と AI クローラー Allow（追加対応：ChatGPT-User / CCBot / Amazonbot）
	$output .= "\n# LLM / AI Crawlers\n";
	foreach ( [ 'GPTBot', 'ChatGPT-User', 'Claude-Web', 'anthropic-ai', 'PerplexityBot', 'Googlebot-Extended', 'CCBot', 'Amazonbot' ] as $ua ) {
		$output .= "User-agent: {$ua}\nAllow: /\n\n";
	}
	$output .= "# LLMs.txt\n";
	$output .= 'LLMs: ' . home_url( '/llms.txt' ) . "\n";
	$output .= 'LLMs-Full: ' . home_url( '/llms-full.txt' ) . "\n";
	$output .= 'Sitemap: ' . home_url( '/sitemap.xml' ) . "\n";

	return $output;
}

// ─── AI向け meta タグ（wp_head）───────────────────────

add_action( 'wp_head', 'sc_llmo_meta_head', 2 );

function sc_llmo_meta_head(): void {
	$url = home_url( '/' );

	// AIOSEO 有効時は description / og: 系を出さない（重複防止）
	$aioseo_active = defined( 'AIOSEO_VERSION' ) || defined( 'AIOSEO_FILE' );

	// AI クローラー向けメタ（Perplexity / ChatGPT / Gemini 対応）
	echo '<link rel="llms-txt" href="' . esc_url( $url . 'llms.txt' ) . '">' . "\n";
	echo '<link rel="llms-full-txt" href="' . esc_url( $url . 'llms-full.txt' ) . '">' . "\n";

	if ( ! $aioseo_active ) {
		// 文脈に応じた AI 要約: 個別投稿 → ai_summary、それ以外 → サイト全体
		$desc = is_singular() ? sc_ai_summary( get_the_ID(), 40 ) : sc_site_ai_summary();
		if ( $desc ) {
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
			echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
		}
	}

	// 運営者情報（E-E-A-T）— AIOSEO 非対応の補完なので常時出力
	echo '<meta name="author" content="' . esc_attr( SC_ORG_NAME ) . '">' . "\n";
	echo '<meta name="publisher" content="' . esc_attr( SC_ORG_NAME ) . '">' . "\n";
	echo '<meta name="copyright" content="' . esc_attr( SC_ORG_NAME ) . '">' . "\n";
	// geo タグ（MEO 補完）— AIOSEO に該当機能なし
	echo '<meta name="geo.region" content="JP-22">' . "\n";
	echo '<meta name="geo.placename" content="静岡市葵区七間町">' . "\n";
	echo '<meta name="geo.position" content="' . esc_attr( SC_GEO['latitude'] . ';' . SC_GEO['longitude'] ) . '">' . "\n";
	echo '<meta name="ICBM" content="' . esc_attr( SC_GEO['latitude'] . ', ' . SC_GEO['longitude'] ) . '">' . "\n";
}

// ─── フラッシュリライトルール（テーマ切替時のみ）──────────

add_action( 'after_switch_theme', 'flush_rewrite_rules' );
