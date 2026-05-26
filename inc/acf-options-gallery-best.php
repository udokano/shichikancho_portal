<?php
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
