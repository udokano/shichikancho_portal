<?php
// テーマアセット URI（毎回の関数呼び出しを避けテンプレート全域で共用）
define( 'SC_TPL_URI', get_template_directory_uri() );

// CPT スラッグ定数
define( 'CPT_SHOP',     'shop' );
define( 'CPT_EVENT',    'event' );
define( 'CPT_SPOT',     'spot' );
define( 'CPT_COLUMN',   'column' );
define( 'CPT_RESIDENT', 'resident' );
define( 'CPT_GALLERY',  'gallery_photo' );
define( 'CPT_LEARN',    'learn_facility' );
define( 'CPT_JOB',      'job' );
define( 'CPT_COWORK',   'coworking' );
define( 'CPT_PROPERTY', 'property' );
define( 'CPT_NEWS',     'news' );
define( 'CPT_WALK',        'walk_course' );
define( 'CPT_PHOTO_AWARD', 'photo_award' );

// タクソノミースラッグ定数
define( 'TAX_SHOP_CAT',    'shop_category' );
define( 'TAX_AREA',        'area' );
define( 'TAX_EVENT_CAT',   'event_category' );
define( 'TAX_SPOT_TYPE',   'spot_type' );
define( 'TAX_COLUMN_CAT',  'column_category' );
define( 'TAX_GALLERY_CAT', 'gallery_category' );
define( 'TAX_LEARN_CAT',   'learn_category' );
define( 'TAX_JOB_IND',     'job_industry' );
define( 'TAX_WALK_SCENE',  'walk_scene' );
define( 'TAX_PHOTO_SEASON','photo_season' );
define( 'TAX_NEWS_CAT',   'news_category' );

// ページテンプレート定数
// 本文をブロックエディタで管理し、ヒーローを ACF で持つページ用
define( 'SC_TPL_CONTACT_FORM', 'page-contact-form-base.php' );
