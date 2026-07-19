<?php
/** ACF 設定・オプションページ（ギャラリーアイコン / 今月のベスト） */

// acf-json/ への保存パス
add_filter( 'acf/settings/save_json', function() {
	return get_template_directory() . '/acf-json';
} );

// acf-json/ からの読み込みパス
add_filter( 'acf/settings/load_json', function( $paths ) {
	$paths[] = get_template_directory() . '/acf-json';
	return $paths;
} );

// ═══════════════════════════════════════════════════════
// ギャラリーアイコン設定
// ═══════════════════════════════════════════════════════
/**
 * ギャラリーアイコン設定 — タームごと（term_id）にアイコン名を保存
 *
 * 仕様:
 * - 単一の管理ページ（Settings API ではなく独自表示）でターム一覧 + アイコン選択
 * - 投稿 > 町のギャラリー > アイコン設定 メニューから開く
 * - 値は term_meta `_sc_term_icon` に保存
 * - フロントは sc_get_term_icon( $term_id ) で参照
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Heroicons Solid 由来の SVGスプライトアイコン候補（chip / season ラベル装飾用）
function sc_gallery_icon_choices(): array {
	return [
		'icon-sparkles'      => '✨ キラキラ・桜・花',
		'icon-park'          => '🌳 木・自然',
		'icon-mountain'      => '🗻 山・富士山',
		'icon-building'      => '🏛 ビル・歴史的建造物',
		'icon-building-library' => '🏛 図書館・寺社',
		'icon-house'         => '🏠 家・街並み',
		'icon-house-modern'  => '🏘 モダンな家',
		'icon-store'         => '🏪 お店',
		'icon-cafe'          => '☕ カフェ',
		'icon-cake'          => '🍰 ケーキ・グルメ',
		'icon-film'          => '🎞 フィルム・レトロ',
		'icon-camera'        => '📷 カメラ',
		'icon-fire'          => '🔥 炎',
		'icon-heart-solid'   => '❤ ハート',
		'icon-star'          => '⭐ 星',
		'icon-map-pin'       => '📍 マップピン',
		'icon-clock'         => '⏰ 時計',
		'icon-tag'           => '🏷 タグ',
		'icon-bolt-solid'    => '⚡ 稲妻',
		'icon-shield'        => '🛡 盾',
		'icon-train'         => '🚆 電車',
		'icon-culture'       => '🎭 文化',
	];
}

// 設定対象タクソノミー
function sc_gallery_icon_taxes(): array {
	return [
		'gallery_category' => 'カテゴリーアイコン',
		'photo_season'     => 'シーズンアイコン',
	];
}

// ─── ギャラリーCPTの管理メニューにサブページを追加 ───────────
add_action( 'admin_menu', function () {
	$cpt = defined( 'CPT_GALLERY' ) ? CPT_GALLERY : 'gallery_photo';
	if ( ! get_post_type_object( $cpt ) ) return;

	add_submenu_page(
		'edit.php?post_type=' . $cpt,
		'アイコン設定',
		'アイコン設定',
		'edit_posts',
		'gallery-icons',
		'sc_render_gallery_icons_page'
	);
} );

// ─── 保存処理 ──────────────────────────────────────────
add_action( 'admin_init', function () {
	if ( empty( $_POST['sc_gallery_icons_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['sc_gallery_icons_nonce'], 'sc_gallery_icons_save' ) ) return;
	if ( ! current_user_can( 'edit_posts' ) ) return;

	$valid_icons = array_keys( sc_gallery_icon_choices() );
	$input       = $_POST['sc_term_icon'] ?? [];
	if ( ! is_array( $input ) ) return;

	foreach ( $input as $term_id => $icon ) {
		$term_id = (int) $term_id;
		if ( ! $term_id ) continue;
		// 絵文字保存のため sanitize_text_field では落ちる場合がある。wp_strip_all_tags + 検証で許可リスト
		$icon = wp_strip_all_tags( (string) wp_unslash( $icon ) );
		if ( $icon && in_array( $icon, $valid_icons, true ) ) {
			update_term_meta( $term_id, '_sc_term_icon', $icon );
		} else {
			delete_term_meta( $term_id, '_sc_term_icon' );
		}
	}

	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-success is-dismissible"><p>保存しました。</p></div>';
	} );
} );

// ─── 設定ページUI ──────────────────────────────────────
function sc_render_gallery_icons_page(): void {
	if ( ! current_user_can( 'edit_posts' ) ) return;
	$choices = sc_gallery_icon_choices();
	?>
	<div class="wrap">
		<h1>アイコン設定</h1>
		<form method="post">
			<?php wp_nonce_field( 'sc_gallery_icons_save', 'sc_gallery_icons_nonce' ); ?>
			<?php foreach ( sc_gallery_icon_taxes() as $tax => $label ) :
				if ( ! taxonomy_exists( $tax ) ) continue;
				$terms = get_terms( [ 'taxonomy' => $tax, 'hide_empty' => false ] );
				if ( is_wp_error( $terms ) || ! $terms ) continue;
			?>
			<h2><?php echo esc_html( $label ); ?></h2>
			<table class="widefat striped">
				<thead><tr><th style="width:40%">ターム</th><th>アイコン</th></tr></thead>
				<tbody>
					<?php foreach ( $terms as $t ) :
						$current = get_term_meta( $t->term_id, '_sc_term_icon', true );
					?>
					<tr>
						<td><strong><?php echo esc_html( $t->name ); ?></strong> <code><?php echo esc_html( $t->slug ); ?></code></td>
						<td>
							<select name="sc_term_icon[<?php echo (int) $t->term_id; ?>]">
								<option value="">— 未設定 —</option>
								<?php foreach ( $choices as $val => $lbl ) : ?>
								<option value="<?php echo esc_attr( $val ); ?>"<?php selected( $current, $val ); ?>><?php echo esc_html( $lbl ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endforeach; ?>
			<p><?php submit_button( '保存' ); ?></p>
		</form>
	</div>
	<?php
}

// ─── ヘルパー: term_id からアイコン名を引く ──────────────
if ( ! function_exists( 'sc_get_term_icon' ) ) :
	function sc_get_term_icon( int $term_id, string $tax = '' ): string {
		if ( ! $term_id ) return '';
		$icon = get_term_meta( $term_id, '_sc_term_icon', true );
		return is_string( $icon ) ? $icon : '';
	}
endif;

// ═══════════════════════════════════════════════════════
// 今月のベスト設定
// ═══════════════════════════════════════════════════════
/**
 * 今月のベスト設定 — ギャラリーCPTから3件選択（option に保存、ACF非依存）
 */

if ( ! defined( 'ABSPATH' ) ) exit;

const SC_GALLERY_BEST_OPTION = 'sc_gallery_best';

// ─── サブメニュー追加 ────────────────────────────────
add_action( 'admin_menu', function () {
	$cpt = defined( 'CPT_GALLERY' ) ? CPT_GALLERY : 'gallery_photo';
	if ( ! get_post_type_object( $cpt ) ) return;

	add_submenu_page(
		'edit.php?post_type=' . $cpt,
		'今月のベスト',
		'今月のベスト',
		'edit_posts',
		'gallery-best',
		'sc_render_gallery_best_page'
	);
} );

// ─── 保存処理 ──────────────────────────────────────
add_action( 'admin_init', function () {
	if ( empty( $_POST['sc_gallery_best_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['sc_gallery_best_nonce'], 'sc_gallery_best_save' ) ) return;
	if ( ! current_user_can( 'edit_posts' ) ) return;

	$ids = [];
	for ( $i = 1; $i <= 3; $i++ ) {
		$id = isset( $_POST['sc_gallery_best'][ $i ] ) ? (int) $_POST['sc_gallery_best'][ $i ] : 0;
		$ids[ $i ] = $id;
	}
	update_option( SC_GALLERY_BEST_OPTION, $ids );

	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-success is-dismissible"><p>保存しました。</p></div>';
	} );
} );

// ─── 設定ページUI ──────────────────────────────────
function sc_render_gallery_best_page(): void {
	if ( ! current_user_can( 'edit_posts' ) ) return;
	$cpt   = defined( 'CPT_GALLERY' ) ? CPT_GALLERY : 'gallery_photo';
	$saved = get_option( SC_GALLERY_BEST_OPTION, [] );
	$posts = get_posts( [
		'post_type'      => $cpt,
		'posts_per_page' => -1,
		'orderby'        => 'date',
		'order'          => 'DESC',
	] );
	$labels = [ 1 => 'BEST', 2 => '#2', 3 => '#3' ];
	?>
	<div class="wrap">
		<h1>今月のベスト</h1>
		<form method="post">
			<?php wp_nonce_field( 'sc_gallery_best_save', 'sc_gallery_best_nonce' ); ?>
			<table class="form-table">
				<?php foreach ( $labels as $i => $lbl ) :
					$current = (int) ( $saved[ $i ] ?? 0 );
				?>
				<tr>
					<th scope="row"><label for="sc_gallery_best_<?php echo $i; ?>"><?php echo esc_html( $lbl ); ?></label></th>
					<td>
						<select name="sc_gallery_best[<?php echo $i; ?>]" id="sc_gallery_best_<?php echo $i; ?>" style="min-width:320px">
							<option value="0">— 未選択 —</option>
							<?php foreach ( $posts as $p ) : ?>
							<option value="<?php echo (int) $p->ID; ?>"<?php selected( $current, $p->ID ); ?>><?php echo esc_html( $p->post_title ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button( '保存' ); ?>
		</form>
	</div>
	<?php
}

// ─── ヘルパー ───────────────────────────────────────
if ( ! function_exists( 'sc_get_gallery_best_ids' ) ) :
	function sc_get_gallery_best_ids(): array {
		$saved = get_option( SC_GALLERY_BEST_OPTION, [] );
		$ids   = [];
		for ( $i = 1; $i <= 3; $i++ ) {
			$id = (int) ( $saved[ $i ] ?? 0 );
			if ( $id && get_post_status( $id ) === 'publish' ) $ids[] = $id;
		}
		return $ids;
	}
endif;
