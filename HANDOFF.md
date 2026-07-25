# sichikenchou テーマ — Session Handoff

直近の Claude セッションで触った内容のサマリー。次のセッションはここを最初に読む。

---

## 1. 直近のセッションでやったこと

### ★ 2026-07-21 セッション（お問い合わせ改修・inc統合・エリアターム連動・スポット投入・マップSVG化）

**コミット済み**: `3bb472c`(お問い合わせ改修/CF7/ファビコン) → `b9a95a2`(エリア名5構成) → `f29a7fa`(エリアターム連動) → `824876d`(店舗カード下辺) → `da1cd41`(inc統合) → `3faed97`(エリアガイドをPNG完全再現クリッカブルSVGマップ化)。前セッションの未コミット分（archive-*/area/breadcrumps 等）も 3bb472c に巻き込み済。

#### Q) お問い合わせページをブロックエディタ化（★コード）
- `page-contact.php` → **`page-contact-form-base.php`** にリネーム（`Template Name: お問い合わせフォームベース`）。ファイル名を page-contact.php にするとスラッグ contact が自動適用され `_wp_page_template`=default になり ACF の page_template 判定が外れるため機能名に
- `SC_TPL_CONTACT_FORM` 定数（constants.php）。テンプレ割当ページだけブロックエディタ有効化（`sc_block_editor_templates()` / editor-classic-pages.php → 統合後は `inc/admin.php`）。判定は `get_page_template_slug()`
- ヒーロー=ACF（`acf-import/page-hero.json`, `group_page_hero`, location=page_template）。本文=ブロック。連絡先=**同期パターン「連絡先」(wp_block ID 1266)**、テーマ側の種は block-patterns.php の `sichikenchou/contact-info`。フォーム=CF7ブロック
- **ハマり**: 同期パターンは DB のみ・git外。本番は別途移行要。`remove_post_type_support('page','editor')` は REST保存(`/wp-json/wp/v2/pages/{id}`)時に外れると保存不可→URIパースで除外必須
- SCSS `_contact.scss` は2カラム→シングル→**PC3カラム中央揃え**（`display:contents` で inner-container 透過が肝、theme.json 無し）

#### R) CF7 エラー処理（★コード）
- `main.js`: `wpcf7invalid` で最初の `.wpcf7-not-valid` へ固定ヘッダーオフセットしてスクロール＋focus
- `inc/cf7-japanese.php`（統合後 `inc/blocks.php`）: `wpcf7_messages` で日本語デフォルト。既存フォーム5/1221 の `_messages` も DB 更新
- enqueue.php: CF7 の CSS/JS 判定を `is_page('contact')`→`has_block('contact-form-7/...')||has_shortcode` に。photo-contest は例外で明示 true（テンプレ do_shortcode のため）

#### S) ファビコン（★コード・DB）
- ロゴ(logo.png)の富士山マークを crop `357x204+346+0` → 正方形512化 → `assets/images/favicon/`（ico/16/32/180/192/512）
- **管理画面のサイトアイコン方式**（`site_icon` option、uploads にコピー・サブサイズ生成）。テーマ固定出力(inc/favicon.php)は作ったが削除。差替は 外観→カスタマイズ→サイト基本情報

#### T) inc/ を機能ドメインに統合 25→13（★コード）
- helpers←helpers+pickup-helper+area / register←cpt-register+menu-locations / acf←acf-settings+acf-options-gallery-icons/best / admin←admin-menu-order+disable-comments/default-post+user-profile+editor-classic-pages / blocks←blocks-register+block-patterns+cf7-japanese / endpoints←event-views+ajax-gallery / seo←seo+seo-llmo
- 単独維持: constants/enqueue/breadcrumbs/schema/likes/walker-nav。functions.php は読込順コメント付き
- **手法**: `<?php` 除去して連結（declare/閉じタグ無しを確認済）。関数名重複ゼロ・ランタイム smoke test 済。バックアップ `/tmp/incmerge/`
- **likes はデッドコード**（テンプレ出力ゼロ、REST/JS/SCSS残骸のみ）。削除は保留中

#### U) エリア5構成 + タームフィールド連動（★コード・ACF・DB）
- `sc_get_areas()`（helpers.php）の name/card_title/tags/area_terms を5エリア新町名に。ユーザーが area ターム個別追加済（七間町8/駒形通り110/人宿町9/駿河町111/常磐町112/両替町113/昭和町114/呉服町10/紺屋町115/御幸町116/駿府城公園117/駿府町118/鷹匠11/伝馬町119/馬場町120/宮ヶ崎町121/大手町122/車町123/中町124）。旧`青葉通り`(12)は未割当で浮き
- **ACF「エリア連動ターム」**（`acf-import/area-linked-terms.json`, `group_area_linked_terms`, `area_linked_terms` taxonomy型）。別グループで group_area_detail を壊さず。5ページ(1223-1227)に term投入
- `page-area.php`: ターム解決を ACF優先+直書きフォールバック。**グルメを手入力リピーター→shop投稿クエリ（area × shop_category「食べる」term2）に**。カードを `<a>` 化（SCSS hover追加）。旧 area_gourmet フィールドは未使用化

#### V) スポット3件を Places API で投入（★DB・公開済）
- Google Places API(新)が `SC_GOOGLE_MAPS_KEY` で稼働（Geocoding は無効）。**住所/電話/座標/URL の事実のみ**、本文はオリジナル1文、写真/説明の転載なし
- 公開: #1273 静岡東宝会館 / #1274 七間町名店街 / #1275 人宿町やどりぎ座（全て area=七間町or人宿町）。東宝会館の「24時間営業」は Google 誤りのため hours 空
- **URLスラッグが日本語(%エンコード)**。英字化は未対応（要判断）

#### W) 店舗アーカイブ カード下辺揃え（★コード）
- `_archive-shop.scss` `.p-shop-card__more` に `margin-top:auto`（card/body は既に flex縦・flex:1）

#### X) エリアマップ SVG ベクター化（★素材）
- `~/Desktop/shizuoka-area-map.png`(正/424×482) を **vtracer** でベクター化 → `~/Desktop/shizuoka-area-map.svg`（純パス・約96%一致）。埋め込み版は拒否され純ベクターで再作成
- クリッカブルSVGマップへの差し替えは **下記 Y) で実装完了**（`3faed97`）

#### Y) エリアガイドを PNG完全再現クリッカブルSVGマップに差替（★コード・コミット `3faed97`）
- `page-tourism.php`（**URLは /tourism/**。旧メモの /visit/ は誤り）のエリアガイドを、旧「area-map.png + 12x12グリッドhotspot」→ **vtracer全パスのインラインSVG**に差替
- 新規 `template-parts/components/area-map.php`: vtracer 出力の全パスを保持し PNG を細部まで再現
  - 5領域 = `<a href="/area/{slug}">` でクリック可能。色/リンク/名称は `sc_get_areas()` 由来（`$sc_area_geo` に slug→d/translate）
  - 装飾4パス（境界 `#F8F8F8`×2 + 微細 `#CCA9A3`/`#B9B8CF`）= `<g class="p-visit__area-deco" aria-hidden>`。**PNG細部再現用・`pointer-events:none` でクリックは領域へ透過**。背景 `#FEFEFE` は透過のため除外
- **領域→slug は地理位置で同定しユーザー確認済**（左上/北西=baba, 右=takajo, 中央帯=gofuku, 中央下=tokiwa, 左下=shichikancho）
- `inc/helpers.php` `sc_get_areas()`: `color` を**元マスター色**に統一（baba #A8A7C4 / takajo #D3C4A7 / gofuku #A4BBAE / tokiwa #CBA7A1 / shichikancho #8BA7C5）。マップ塗り・名称リストのドット・culture-lineのドット全て一致。旧グリッド用 `col`/`row` キー削除
- `_page-tourism.scss`: `&-map-img/-map-grid/-hotspot` 撤去→ `&-svg`/`&-region`(`&-region-shape`)/`&-deco`。**元PNGに白境界なし→ base に stroke 付けない**（境界は装飾パスで表現）。hover=brightness(0.9)、focus-visible のみ stroke
- **検証**: 実Chromeでクリック遷移OK（takajo/shichikancho）。元PNG vs テンプレ出力のピクセル比較で塗り内部完全一致、差分は外周1〜2pxのアンチエイリアス縁のみ（ベクター再現の原理的限界＝ユーザー「ベクター再現でOK」承諾済）
- **ハマり**: ①実DOM 1685×840/dpr2 とスクショ幅がズレ、細い領域はクリック座標が外れやすい（塗り面上を狙う）②**ローカルにページキャッシュ**が効き、変更確認は `?nocache=1` 等でバスト必要（enqueue の filemtime とは別レイヤー）

### ★ 2026-07-12〜14 セッション（atosaki 加盟店追加 + PICK UP スライダー刷新・**全て未コミット**）

作業ツリーが全て未コミット。inc/area.php・inc/breadcrumbs.php・page-area.php・acf-import/・acf-json/group_area_detail.json は**前セッションのエリアページ移行分**（今回未着手・別件）。以下 J〜P が今回分。

#### J) WP-CLI で Local の DB に接続する方法（★最重要・環境メモに正式版）
- 従来「WP-CLI はソケットで DB 接続不可」としていたが**接続方法が確立**。以後の DB 操作はブラウザ経由でなく WP-CLI で可
- Local の MySQL ソケット: `~/Library/Application Support/Local/run/IG98zSrPa/mysql/mysqld.sock`（サイト id は `sites.json` で `sitikentxhou` を検索）
- `http-auth` プラグインが CLI をブロックするので `--skip-plugins=http-auth`
- DB_HOST を `--exec` で先に define（wp-config の define より先に評価され定数先勝ち）
- 実行形（ラッパー `scratchpad/wpx.sh` 参照）:
  `wp --skip-plugins=http-auth --exec="define('DB_HOST','localhost:<socket>');" <cmd> 2>&1 | grep -v "already defined\|headers already sent"`
- ACF リピーター等のデータ投入は `wp eval-file <seed.php>`（`update_field()` 使用＝データ投入のみ、フィールドグループ登録ではないので規約 OK）

#### K) atosaki セブン発展会 加盟店 6 店を shop に追加（DB のみ・git 管理外）
- 出典 https://atosaki7.com/ 加盟店一覧。ID 1255〜1260: 大石精肉店 / 焼肉芳龍 / 和・そばみむらや / 揚屋たけ / 酒場詠 / 静岡洋食器
- ブロックエディタ不要（shop は post_content プレーンテキスト＋ACF 構成）。カテゴリは `--by=id` で割当（**`wp post term set` は既定で ID でなく名前扱い→ゴミターム量産の罠。必ず `--by=id`**）
- アイキャッチ未設定＝No-image（`sc_thumbnail_url()` が `no-image.svg` フォールバック）
- **要確認**: 芳龍の住所を公式に合わせ 2-5-8→**2-5-17** に修正済。たけの 2-5-8 は未確認。価格は公式/食べログ由来で鮮度未検証

#### L) イベント 3 件追加（DB のみ）
- ID 1252 安倍川花火大会(7/18) / 1253 ハレバレ(3/20) / 1254 防災フェス(6/14)。`acf/event-lead`＋`acf/event-overview` ブロック＋ACF メタ。カテゴリ 14/16/18 を `--by=id`

#### M) 4 店のコンテンツ充実（大石・芳龍・みむらや・たけ / DB のみ）
- 公式サイト・食べログ・グルメ記事から実データ取材し ACF 投入（description/menu リピーター/faq/price_range/seats/tips 等）。seed は `scratchpad/seed-*.php`

#### N) PICK UP スライダー刷新（★コード変更）
- **shop 4 件以上 / event 3 件以上で PC もスライダー化**（従来は SP のみ slick）。`is-pc-slider` クラスを PHP が付与→JS/CSS フック
- `assets/js/center-slider.js`: `is-pc-slider` 時 PC 複数枚 slick（`data-pc-slides` で枚数指定・既定 3・event は 2）。矢印/ドットは `appendArrows`/`appendDots` で操作バーへ流し込み。SP は responsive で従来のセンターモード
- **新規共通コンポーネント `assets/scss/object/component/_slider-nav.scss`（`.c-slider-nav`）**: 円形矢印（SVG スプライト chevron）＋ピル型ドット。`:empty` で未初期化時は非表示。main.scss に `@use` 追加。矢印は `prevArrow`/`nextArrow` に SVG 直書きで渡す（スプライト `#icon-chevron-left/right`）
- **ハマり**: grid 内に slick を入れると `display:grid` 残存で `.slick-list` が 1 カラムに潰れる → `is-pc-slider` は `display:block`。さらに `.l-sidebar-layout` の `1fr` カラムが `min-width:auto` だと slick トラックの巨大幅にカラムが引き伸ばされ**暴走膨張**（カード幅 8000px 級）→ カラム側に `min-width:0` 必須（shop=`__main` に追加、event=`__content` は既存で有）
- 対象 SCSS: `_archive-shop.scss` / `_archive-event.scss`（is-pc-slider 時 block 化・センターモード減光を `@include sp` 限定・初期化前ガード）

#### O) PICK UP を全ページ「上限なし・ランダム順」に（★コード変更）
- `inc/pickup-helper.php` `sc_get_pickup_ids($key, $limit=0)`: `shuffle()` で順ランダム化、`$limit>0` のときだけ上限。既定 0＝上限なし
- 4 アーカイブ（shop/event/resident/column）とも `sc_get_pickup_ids('xxx')`（上限引数削除）＋ pickup クエリ `posts_per_page => -1`。フォールバック（未登録時の最新 N 件）は据え置き
- **注意**: `shuffle()` はリクエスト毎。ページキャッシュ/CDN 環境ではキャッシュ有効中は順固定

#### P) 単発 UI 修正（★コード変更）
- shop アーカイブ**フル幅バグ**: `.p-shop-archive` 直下に幅制御コンテナが無く全幅化 → `__container`（`max-width/margin-inline/padding-inline`）を追加、`archive-shop.php` でラップ
- tourism: `p-visit__area-list`（モバイル用エリアリスト）を **SP 非表示**（`@include sp{display:none}`。下の explore カードで代替）。`p-visit__explore` の**下余白 0→64**（直後 `__first` が背景色つきでカードが密着していた）
- shop 一覧カード**折り返し防止**: excerpt 30→18 語、情報 `dd`・営業時間 `__hours-text` を `nowrap`+`ellipsis` 1 行化（`__pay`/`__hours-icon` は `flex-shrink:0`、セル/hours に `min-width:0`）。営業時間テキストは `<span class="p-shop-card__hours-text">` で包む

### ★ 2026-06-17 セッション（cinema アンカー整備 + SP 調整・コミット済み `5b3f9b8`・push 済み。前セッション未コミット分 B〜F も同コミットに同梱）

#### G) アンカーリンク固定ヘッダーオフセット（難航・最終解決）
- **根本原因**：`assets/scss/pages/_single-walk-course.scss:304` の `html { scroll-behavior: smooth; }` がページ固有ファイルなのに `html{}` で全ページにグローバル漏れ。これで `window.scrollTo` が全部 smooth 化し、その smooth が Google 翻訳の `html{height:100%}` 注入と干渉して**不発**→アンカークリックで一切スクロールしない状態だった
- **解決**：`assets/js/main.js` の `scrollToHash()` で、スクロール直前に `document.documentElement.style.scrollBehavior='auto'` に一時上書きして `window.scrollTo(0, top)` を instant 実行。CSS の smooth 漏れに関係なく確実に動く
- ヘッダー高さは `header.offsetHeight` を都度実測して引く（`is-scrolled`/SP で可変）。クリックは `preventDefault`+`pushState`、別ページ `#hash` 着地・`hashchange` も同関数で補正
- `scrollIntoView` は当環境で不安定だったため不採用。`_base.scss` の `scroll-behavior: smooth` も撤去（`scroll-padding-top: rem(120)` は保険で残置）
- **残**：`_single-walk-course.scss:304` の `html{}` グローバル漏れは JS 側で無害化済みだが、本来は正しいスコープに直すのが望ましい

#### H) cinema 映画史ブロックの ID/TOC 整備
- 4つの `.p-cinema__history-block` 外側 div に ID を統一設置：`cinema-history-block-01`〜`-04`（旧 `cinema-history-title-01` は `-block-01` にリネーム、`aria-labelledby` は見出しの `cinema-history-heading-01` を参照）
- TOC（ストーリーガイド）の href を 01→block-01, 02→block-02, 03→block-03, 04→block-04, **05→`cinema-now-title`（now セクション）** に再マッピング
- **注意**：TOC 03/04/05 のラベル（昔の映画館/いまの映画館/余韻で歩く）と飛び先ブロックの内容が不一致。ラベル側は未調整（ユーザー判断待ち）
- old/now セクションは `id="cinema-old-title"`/`"cinema-now-title"` を外側 `<section>` に保持（next は `cinema-next`）。old/next は現状 TOC リンク無し（直リンク用）
- old/now 見出し（`p-cinema__old-title`/`__now-title`）に `text-align: center` 追加（下のリード文は元々中央寄せで不整合だった）

#### I) SP 調整（全体）
- **SP 全体 +15%**：`_base.scss` の `mq-down(sm)`/`mq-down(xs)` の root font-size を `vw($baseFontsize * 1.15, …)` に。`rem()` 基準が上がり文字・余白が一律拡大
- フッター左カラム（`l-footer__col--wide`）を SP のみ中央寄せ（`align-items/text-align: center`、stats 行も中央）。フッターロゴ `logo-img` を SP のみ `rem(69)`（125%）
- living「住んでいる人の声」SP センタースライダー：カード間余白（`.slick-list` 負マージン + `.slick-slide` マージン rem(7)）、SP カード padding 圧縮で縦横比改善、名前 `voices-author` を rem(14) に拡大
- `front-page.php`：商店街お店 sub テキストに `<br class="u-br-sp">`（SP のみ改行）

### A) フォトコンテストページ Manus 寄せ + 応募フォーム CF7 化（コミット済み `c5bb45c`・push 済み）

- 入賞者セクション（`p-photo-contest__category`）を背景写真カバー + フロストガラスカードに。アイブロウ「入賞者」+ trophy アイコン
- 投稿作品フィルタを下線タブ → ピル型に（`c-tabs--pill` 新設、他ページの下線タブは不変）
- 投稿作品カードの「いいね（ハート）」削除（データ・SCSS も撤去）
- 応募フォームを Contact Form 7 化（フォーム名「フォトコンテスト応募」/ **ID 1221**）。テンプレートは ID 直書きせずタイトルで動的解決（`get_posts` → `do_shortcode`）。未作成時はフォールバック文
- `page-contact.php` 冒頭の **PHP 致命パースエラー修正**（コミット `44aa648` の schema 接続時に `<?php` タグが重複混入していた）。`get_header()` 後に `schema_contact_page()` 呼び出しを正しい PHP ブロックに
- Contact 送信ボタン中央寄せ、`c-btn--white` 追加、svg-sprite に `icon-trophy` 追加

### B) フォトコンテスト 微調整 + CF7 表示の不具合潰し（**未コミット**）

- 入賞者アイブロウ色 オレンジ → **白**（`p-photo-contest__section-eyebrow--award`）
- CF7 隠しフィールド `<fieldset class="hidden-fields-container">` を `display:none`（既定の枠線・余白を消す。hidden input は送信される）
- CF7 各フィールド直下の「入力してください。」（`.wpcf7-not-valid-tip`）を非表示。不正フィールドは赤ボーダー（`.wpcf7-not-valid`）で明示
- **`.screen-reader-response` を SR 専用クリップ**（`position:absolute; 1×1px; clip`）。本来クリップされるべき SR 用エラー一覧（サマリ + 各「入力してください。」×4）が**視覚表示されていた**のが原因 → 後述「CF7 標準 CSS 未読込」参照
- CF7 送信ボタン中央寄せ。wpautop が submit を `<p>` で囲むため、`align-self` が効かず → `.wpcf7-form p:has(.wpcf7-submit) { text-align:center }` で対応
- 応募要項アコーディオン（`p-photo-contest__guideline-body`）の答えに上余白追加（上 0 → 10px / SP 8px）

### C) 暮らしページ「住んでいる人の声」3カード化 + SP センタースライダー（**未コミット**）

- `page-living.php`：各タブ内に 1 件ずつ埋め込んでいたボイスを廃止し、**タブ下に全 3 名をまとめた共通セクション `p-living__voices` を新設**（PC 3カードグリッド）
- `$tab_voices`（3名）をそのまま流用、`$tab['voice']` 紐付けループは撤去
- PC インナー幅を `rem(1000)` に絞り縦横比改善（旧 442px 横長 → 約 296px）
- SP は slick **センターモード**（中央 opacity 1 / 両隣 0.5・centerPadding 32px・ドット・スワイプ）

### D) フォトコンテスト「これまでの投稿作品（Archive）」縦横比 + SP センタースライダー（**未コミット**）

- archive カード `min-height:180` → **`aspect-ratio: 3/2`**（旧 2.44:1 横長ベタ → 1.5:1）。インナー幅 `rem(1000)`
- SP は slick センターモード（C と共通挙動）

### E) センタースライダー共通化（**未コミット**）

- `living-voices-slider.js` を削除し、汎用 **`assets/js/center-slider.js`** に統合。フック `.js-center-slider`（1ページ複数可、`.each`）。`matchMedia('(max-width:768px)')` で SP のみ slick 化、`change` で init/destroy
- `inc/enqueue.php`：`is_page('living') || is_page('photo-contest')` で slick(CDN) + `center-slider.js` を enqueue
- `page-living.php` voices grid / `page-photo-contest.php` archive grid に `js-center-slider` 付与

### F) TOP MV マップ差し替え（**未コミット**）

- `front-page.php` の地図画像を `hero-map.png` → **`hero-map-2.png`** に変更
- Desktop の `20260611235955.PNG` を `assets/images/top/hero-map-2.png` にコピー（1478×1130・透過 PNG・白線画）
- MV は動画背景 + 青オーバーレイ（`rgba(0,109,166,.55)`）なので白マップが映える。`width:100%;height:auto` で自動追従、レイアウト崩れなし
- 旧 `hero-map.png` はバックアップ残置

---

## 2. 環境メモ

- **SCSS コンパイル必須**：編集後 `npx sass assets/scss/main.scss assets/css/main.css --style expanded --no-source-map`。`assets/css/main.css` は **.gitignore 対象**（コミットしない・本番でビルド前提）
- **OPcache**：PHP 編集後、`public/flush.php`（`opcache_reset()`）を curl で叩いて削除。本番反映には別途必要
- **WP-CLI で DB 操作可（推奨）**：Local ソケット + `--skip-plugins=http-auth` + `--exec` で DB_HOST 上書き（詳細は §1-J）。ラッパー例 `scratchpad/wpx.sh`。ACF データ投入は `wp eval-file seed.php`（`update_field()`）。ブラウザ経由 seed はもう不要
  - `wp --skip-plugins=http-auth --exec="define('DB_HOST','localhost:~/Library/Application Support/Local/run/IG98zSrPa/mysql/mysqld.sock');" <cmd>`
  - **`wp post term set` は `--by=id` 必須**（既定は名前扱いでゴミターム量産）
- **Restricted Site Access / http-auth プラグイン稼働**：未ログインの curl は 401。CLI は `--skip-plugins=http-auth` で回避
- **検証は Claude in Chrome の `javascript_tool`**（認証済みタブ）。computed-style や naturalWidth で確認。chrome-devtools MCP は別ブラウザ起動で http-auth 未認証→ローカルサイト到達不可

---

## 3. 残タスク

- **DB投入分は全て git 外**（Q〜V の contact本文/ACF値/site_icon/CF7メッセージ/同期パターン1266/area_linked_terms/スポット3件）。本番は別途移行
- likes 機能はデッドコード。削除するか判断（inc/likes.php + main.js 550-585 + _gallery/_walk.scss の like）
- スポット3件のURLスラッグが日本語（%エンコード）。英字化するか要判断（公開直後の今が安全）
- 旧 area ターム `青葉通り`(12) がどのエリアにも未割当。削除 or 割当
- tokiwa 等 ②〜⑤エリアは spot/shop/event のタグ付けが薄く各セクション空。先方のタグ付け作業待ち
- **今セッション分（J〜P）は全て未コミット**。動作確認後にコミット（DB のデータ投入はコード外＝git には乗らない点に注意）
- **atosaki 加盟店の一次情報照合**：芳龍/たけの住所（2-5-17 修正済/2-5-8 未確認）、各店の価格（公式・食べログ由来で鮮度未検証）。公開前に店へ確認
- **残り 2 店の充実**：酒場詠(1259)・静岡洋食器(1260) は基本情報のみ（大石/芳龍/みむらや/たけは充実済）
- **過去日イベントの扱い**：ハレバレ(3/20)・防災フェス(6/14) は既に終了日。公開のままか下書き化か要判断
- shop 一覧カードの**高さ完全統一**は未対応（折り返しは解消済だが、価格/席/営業時間の有無で行数差＝背が変わる。揃えるなら一覧で表示項目を固定）
- **`assets/scss/pages/_single-walk-course.scss:304` の `html { scroll-behavior: smooth; }` 本修正**（ページ固有ファイルから `html{}` でグローバル漏れ。現状 main.js 側で無害化済みだが正しいスコープに移すのが本筋）
- **cinema TOC のラベルと飛び先の不一致解消**（TOC 03/04/05 ラベル＝昔の映画館/いまの映画館/余韻で歩く、飛び先＝block-03/block-04/now。ラベル側 or 飛び先の整理が必要・ユーザー判断待ち）
- **Contact ページにも `.screen-reader-response` 可視化バグが残存**（CF7 標準 CSS 未読込が根本原因。photo-contest のみ対応済み）。同じクリップ 1 ルールで対応可 → 要確認
- CF7 サマリ「入力内容に問題があります…」を残すか消すか保留中
- **本番デプロイ時**：
  - CF7 フォーム（ID 1221）は **DB 保存で git 管理外** → 本番 DB で再作成必要（未作成だとフォールバック表示）
  - CF7 メール送信元 `wordpress@sitikentxhou.local`（ローカル用）→ 本番実ドメインに変更（SPF/DMARC 対策）
- （既存・未着手）photo-contest の CPT_PHOTO_AWARD 連携・年度アーカイブの実データ化（現状サンプル）
- SCSS リファクタ残：`_commerce` / `_working` / `_single-walk-course` / `_sponsor` / `_walk`

---

## 4. 触るときの注意

- **ローカルにページキャッシュあり**。テンプレ/CSS を変えても旧HTMLが出ることがある → 確認は `?nocache=1` 等のクエリ付与かスーパーリロードでバスト（enqueue の filemtime キャッシュバストとは別レイヤー）
- **`assets/css/main.css` は gitignore 対象**（コンパイル済みは非追跡）。SCSS 変更後は `npx sass ...` で再コンパイルしてローカル反映、コミットは SCSS ソースのみ。デプロイ先ではビルドが要る
- **アンカースクロールは `scroll-behavior: smooth` / `scrollIntoView` 禁止**。Google 翻訳の `html{height:100%}` と干渉して smooth が不発になる。`main.js` の `scrollToHash()` のように scroll-behavior を一時 auto に上書きして `window.scrollTo` で instant 実行すること
- **CF7 標準 CSS が未読込**（`cf7CssLoaded: false`）。そのため CF7 が SR 用に出す `.screen-reader-response` が視覚表示される。新規 CF7 フォームを置くページでは SR 専用クリップを当てること
- **CF7 はカード/li 自体に `slick-slide`/`slick-center` を付与**（ラップ div を作らない）。dim 等は子孫セレクタでなく要素自身に当てる
- slick 生成要素・CF7 出力・wp-block などプラグイン出力は class 付与不可 → 要素セレクタ使用可（`CLAUDE.local.md` の裸タグ禁止の例外）
- **SP slick は実機スワイプ未目視**（`resize_window` が実ビューポートに効かず SP 幅に絞れない・innerWidth が縮まない）。PC 幅は確認済み。DevTools デバイスモードで最終確認推奨
- **`js-center-slider` は living / photo-contest / shop・event アーカイブ PICK UP で使用**。PC もスライダー化したい箇所は `is-pc-slider`（+ `data-pc-slides`）を付与、操作バーは空 `<div class="c-slider-nav js-center-slider-nav">` を隣接配置（§1-N）
- **grid/flex 内に slick を置くときは親カラムに `min-width:0`**（無いと slick トラック幅でカラムが暴走膨張）。slick 対象要素自体は `display:block`（grid 残存で潰れる）
- ACF / CPT / CF7 のハードコード禁止ルール（`CLAUDE.local.md`）厳守。フォーム定義は管理画面 or DB seed で
