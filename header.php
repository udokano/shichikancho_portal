<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>.goog-te-banner-frame{display:none!important}body{top:0!important}</style>
<?php
// 全ページ共通 JSON-LD（AIOSEO 有効時は WebSite / Organization / BreadcrumbList を AIOSEO に任せて重複回避）
$sc_aioseo = defined( 'AIOSEO_VERSION' ) || defined( 'AIOSEO_FILE' );
if ( ! $sc_aioseo ) {
	schema_website();
	schema_organization();
	schema_breadcrumb();
}
wp_head();
?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'template-parts/components/svg-sprite' ); ?>

<a class="u-skip-link" href="#main-content">本文へスキップ</a>

<header class="l-header" role="banner">
	<div class="l-header__top">
		<a class="l-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="SHICHIKANCHO トップページへ">
			<img class="l-header__logo-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo/logo.png' ); ?>" alt="SHICHIKANCHO" width="200" height="35" loading="eager">
		</a>

		<!-- メインナビ＋コントロール（右まとめ） -->
		<div class="l-header__right">
			<nav class="l-header__nav-primary" aria-label="メインナビゲーション">
				<ul class="l-header__nav-primary-list" role="list">
					<li><a class="l-header__nav-primary-link" href="<?php echo esc_url( home_url( '/tourism/' ) ); ?>"<?php echo is_page( 'tourism' ) ? ' aria-current="page"' : ''; ?>>観光情報</a></li>
					<li><a class="l-header__nav-primary-link" href="<?php echo esc_url( home_url( '/shops/' ) ); ?>"<?php echo is_post_type_archive( CPT_SHOP ) || is_singular( CPT_SHOP ) ? ' aria-current="page"' : ''; ?>>商店街のお店</a></li>
					<li><a class="l-header__nav-primary-link" href="<?php echo esc_url( home_url( '/events/' ) ); ?>"<?php echo is_post_type_archive( CPT_EVENT ) || is_singular( CPT_EVENT ) ? ' aria-current="page"' : ''; ?>>イベント</a></li>
					<li><a class="l-header__nav-primary-link" href="<?php echo esc_url( home_url( '/column/' ) ); ?>"<?php echo is_post_type_archive( CPT_COLUMN ) || is_singular( CPT_COLUMN ) ? ' aria-current="page"' : ''; ?>>七ぶらコラム</a></li>
					<li><a class="l-header__nav-primary-link" href="<?php echo esc_url( home_url( '/access/' ) ); ?>"<?php echo is_page( 'access' ) ? ' aria-current="page"' : ''; ?>>アクセス</a></li>
				</ul>
			</nav>

			<div class="l-header__controls">
				<?php
				// googtrans クッキーで翻訳状態を判定
				$is_en    = isset( $_COOKIE['googtrans'] ) && strpos( $_COOKIE['googtrans'], '/ja/en' ) !== false;
				$lang_label = $is_en ? 'English' : 'Japanese';
				?>
				<div class="l-header__lang-wrap js-lang-wrap">
					<button class="l-header__lang js-lang-toggle" type="button"
						aria-expanded="false" aria-haspopup="listbox"
						aria-label="言語切替">
						<?php echo esc_html( $lang_label ); ?> <span class="l-header__lang-arrow" aria-hidden="true">&#8964;</span>
					</button>
					<ul class="l-header__lang-dropdown" role="listbox" aria-label="言語を選択">
						<li><button class="l-header__lang-option js-translate-option <?php echo ! $is_en ? 'is-selected' : ''; ?>"
							type="button" role="option" aria-selected="<?php echo ! $is_en ? 'true' : 'false'; ?>"
							data-lang="ja">日本語</button></li>
						<li><button class="l-header__lang-option js-translate-option <?php echo $is_en ? 'is-selected' : ''; ?>"
							type="button" role="option" aria-selected="<?php echo $is_en ? 'true' : 'false'; ?>"
							data-lang="en">English</button></li>
					</ul>
				</div>
				<!-- /.l-header__lang-wrap -->
				<button class="l-header__hamburger js-hamburger" type="button" aria-expanded="false" aria-controls="drawer" aria-label="メニューを開く">
					<span class="l-header__bar"></span>
					<span class="l-header__bar"></span>
					<span class="l-header__bar"></span>
				</button>
			</div>
		</div>
		<!-- /.l-header__right -->
	</div>
	<!-- /.l-header__top -->

	<!-- サブナビ（PC）：町カテゴリー 9項目 アイコン付き -->
	<div class="l-header__sub">
		<nav aria-label="カテゴリーナビゲーション">
			<ul class="l-header__nav-sub" role="list">
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'about' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-house"></use></svg>
						<span>町の紹介</span>
					</a>
				</li>
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'cinema' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-film"></use></svg>
						<span>映画の町</span>
					</a>
				</li>
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'walk' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/walk/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-map-pin"></use></svg>
						<span>町をめぐる</span>
					</a>
				</li>
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'living' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/living/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-person"></use></svg>
						<span>町に住む</span>
					</a>
				</li>
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'learn' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/learn/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-hat"></use></svg>
						<span>町でまなぶ</span>
					</a>
				</li>
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'work' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/work/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-briefcase"></use></svg>
						<span>町で働く</span>
					</a>
				</li>
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'business' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/business/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-store"></use></svg>
						<span>町で商い</span>
					</a>
				</li>
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'gallery' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-camera"></use></svg>
						<span>町のギャラリー</span>
					</a>
				</li>
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'guide' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/guide/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-book"></use></svg>
						<span>くらしガイド</span>
					</a>
				</li>
				<li class="l-header__nav-sub-item">
					<a class="l-header__nav-sub-link<?php echo is_page( 'safety' ) ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/safety/' ) ); ?>">
						<svg class="l-header__nav-sub-icon" aria-hidden="true" focusable="false"><use href="#icon-shield"></use></svg>
						<span>いのちを守る</span>
					</a>
				</li>
			</ul>
		</nav>
	</div>
	<!-- /.l-header__sub -->
</header>
<!-- /.l-header -->

<!-- SP ドロワー -->
<div id="drawer" class="l-drawer" role="dialog" aria-modal="true" aria-label="ナビゲーションメニュー" hidden>
	<div class="l-drawer__inner">

		<div class="l-drawer__header">
			<button class="l-drawer__close js-drawer-close" type="button" aria-label="メニューを閉じる">
				<svg width="20" height="20" aria-hidden="true" focusable="false"><use href="#icon-x-mark"></use></svg>
			</button>
		</div>
		<!-- /.l-drawer__header -->

		<!-- サブナビ（カテゴリー） -->
		<div class="l-drawer__section">
			<p class="l-drawer__label">カテゴリー</p>
			<nav aria-label="カテゴリーナビゲーション（ドロワー）">
				<ul class="l-drawer__nav-list" role="list">
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-house"></use></svg>町の紹介
					</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-film"></use></svg>映画の町
					</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/walk/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-map-pin"></use></svg>町をめぐる
					</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/living/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-person"></use></svg>町に住む
					</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/learn/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-hat"></use></svg>町でまなぶ
					</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/work/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-briefcase"></use></svg>町で働く
					</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/business/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-store"></use></svg>町で商い
					</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-camera"></use></svg>町のギャラリー
					</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/guide/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-book"></use></svg>くらしガイド
					</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/safety/' ) ); ?>">
						<svg class="l-drawer__nav-icon" aria-hidden="true" focusable="false"><use href="#icon-shield"></use></svg>いのちを守る
					</a></li>
				</ul>
			</nav>
		</div>
		<!-- /.l-drawer__section -->

		<!-- メインナビ -->
		<div class="l-drawer__section">
			<p class="l-drawer__label">メニュー</p>
			<nav aria-label="メインナビゲーション（ドロワー）">
				<ul class="l-drawer__nav-list" role="list">
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/tourism/' ) ); ?>">観光情報</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/shops/' ) ); ?>">商店街のお店</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/events/' ) ); ?>">イベント</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/column/' ) ); ?>">七ぶらコラム</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/access/' ) ); ?>">アクセス</a></li>
					<li><a class="l-drawer__nav-link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a></li>
				</ul>
			</nav>
		</div>
		<!-- /.l-drawer__section -->

	</div>
	<!-- /.l-drawer__inner -->
</div>
<!-- /.l-drawer -->

<div class="l-overlay js-overlay" aria-hidden="true"></div>

<main id="main-content" class="l-main">
