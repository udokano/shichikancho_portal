# Sichikenchou Theme — Local Rules

Claude がこのテーマを編集するときに **必ず守るルール**。

---

## コメント規約

### 原則: **コメントはすべて日本語で書く**
- 目的・背景・なぜこの実装なのかを日本語で書く
- 英語の用語（WordPress, ACF, SEO, og:title など）はそのまま混ぜて OK
- 関数 / 変数 / クラス名自体は英語のまま（WP コーディング規約に従うため）

### 例外: 残してよい英語コメント
1. **閉じタグコメント** — 構造マーカーは英語のクラス名のまま
   ```html
   <!-- /.l-header__inner -->
   <!-- /.p-shop-hero -->
   ```
2. **サードパーティ / ライセンスヘッダー**
3. **minified ファイル**（`assets/css/`, `assets/vendor/`）

### 「AIが書いた感」を出さないこと

- ❌ `// 店舗情報を取得します。` → ✅ `// 店舗情報を取得`
- ❌ `// ヒーローセクション — 全幅で表示` → ✅ `// ヒーロー / 全幅`
- **当たり前のことを書かない**。コードを見ればわかることは書かない。**「なぜ」だけ**書く
- **です・ます調は使わない**。体言止め / 常体で短く
- **絵文字・装飾記号を大量に入れない**

---

## HTML / マークアップ規約

- `<img>` に無駄な class を付けない。必要なスタイルは親要素から
- **裸のタグセレクタでスタイリングしない**。`li` `p` `a` `svg` `dt` `dd` `strong` `th` `td` 等をSCSSでネストした要素セレクタで直接スタイリングするのは原則禁止。HTML側に要素クラス（`&__item` `&__icon` `&__text` 等）を付与し、クラスセレクタでスタイリングする
  - 例外1: `<img>`（クラスを付けず親要素で制御）
  - 例外2: ブロックエディタ出力（`.wp-block-*` 配下、HTML構造を制御できない）
  - 例外3: `&::before` `&::after` 等の擬似要素、`:hover` 等の状態擬似クラス
- `<div>` の閉じタグには必ず `<!-- /.class-name -->` コメントを付ける
- W3C バリデータでエラー 0 件を目指す（`<p>` 内に `<div>` を入れない等）
- PC/SP で画像を切り替えるときは `<picture>` を使う（base = PC 画像、breakpoint = `768px`）
- 装飾目的の `<img>` には `alt=""` と `aria-hidden="true"` を両方付ける

---

## SCSS / FLOCSS 規約

- クラスのプレフィックス: `l-`（Layout）/ `c-`（Component）/ `p-`（Project/Pages）/ `u-`（Utility）/ `is-`（State）/ `js-`（JS hook）
- `!important` は `u-` クラスのみ許可
- 新規ページスタイルは `assets/scss/pages/_xxx.scss` に追加し、`main.scss` で `@use` する
- CSS変数（カスタムプロパティ）はハードコードせず `_variables.scss` の値を使う
- **単位は `rem()` 関数で統一**。`_functions.scss` の `rem()` を使い、px直書き禁止（border / line-height 等の例外除く）
- **`vw()` 関数あり**（`_functions.scss`）。デザイン基準幅1440pxで `px → vw` 変換。`html { font-size: clamp(14px, vw(16), 16px); }` を `_base.scss` に記述済み
- **PC-first**。ベースがPC、SP上書きは `@media (max-width: #{$bp-sp})` のみ。`$bp-sp: 768px` は `_variables.scss` で管理

---

## PHP / テンプレート規約

- **SVG はサイト全体で必ず SVG スプライト経由**。`template-parts/components/svg-sprite.php` に `<symbol id="icon-xxx">` を登録し、`<svg><use href="#icon-xxx"></use></svg>` で参照する。インライン SVG（path 直書き）禁止
- アイコンは原則 [Heroicons Solid](https://heroicons.com/solid) から選ぶ。`viewBox="0 0 24 24"` `fill="currentColor"` で登録し、サイズは利用側 SCSS で指定
- エスケープは `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()` を必ず通す
- カスタム投稿タイプ / タクソノミーのスラッグは `inc/constants.php` の定数を使う（ハードコード禁止）
- ACFフィールドが空の場合のフォールバックを必ず記述する
- `WP_Query` 使用後は `wp_reset_postdata()` を必ず呼ぶ

## 記事コンテンツ入稿規約

- **記事本文はブロックエディタ（Gutenberg）で入稿する**。見出しは Heading ブロック、画像は Image ブロック、引用は Quote ブロック、リストは List ブロックなど、それぞれ適切なネイティブブロックを使う
- **クラシックブロック（Classic block / `<!-- wp:freeform -->`）は禁止**。投稿/コラム/イベント等すべての CPT で classic block を使わない
- DB 直接挿入で記事を作る場合も `<!-- wp:paragraph -->` `<!-- wp:heading -->` などの **ブロックコメント形式** で `post_content` を構築する

## ACF / CPT 管理規約

- **ACF フィールドを PHP でハードコードしない**。すべてのフィールドグループは ACF PRO 管理画面 GUI で定義し、`acf-json/` Local JSON 自動同期で管理する
- `register_field_group()` / `acf_add_local_field_group()` の直書きは禁止
- **CPT・タクソノミーも ACF PRO 管理画面UIで定義する**。「カスタムフィールド > 投稿タイプ/タクソノミー」から作成。`register_post_type()` / `register_taxonomy()` のPHP直書きは禁止
- CPT スラッグの参照は `inc/constants.php` の定数を使う（文字列ハードコード禁止）
- `acf-json/` は git に含める
- **ACF オプションページ・フィールドグループも PHP ハードコード禁止**。`acf_add_options_page()` / `acf_add_options_sub_page()` / `acf_add_local_field_group()` を新規に PHP に書かない。管理者向けの設定UIが必要な場合は WordPress ネイティブ（`add_submenu_page()` + `update_option()` / `term_meta`）で実装する
  - 既存の `inc/acf-options-pickup.php` は移行前のレガシー。新規追加では真似しない

---

## グローバルナビゲーション規約

- **Gナビ（グローバルナビ）は `wp_nav_menu()` を使わず直書き**。`header.php` に HTML で固定記述する
- ナビ項目の追加・変更は `header.php` を直接編集する
- `wp_nav_menu()` は使用禁止（ヘッダーに限り）。フッターも同様に直書き済み
- `SC_Sub_Nav_Walker` は `walker-nav.php` に定義済みだが現在未使用（将来用途のため残存）

---

## PHP コーディング規約

- **投稿ページ（ブログ投稿・CPT 個別・アーカイブ）のみエディタ（WP ブロックエディタ）でコンテンツを作成する**。固定ページはテンプレートで直接実装。
- PHP ファイルには各セクション・関数・処理ブロックに日本語コメントを入れる
- インクルード先のファイルにも役割コメントを冒頭に書く
- コメントスタイル：
  - ファイル冒頭：`/** 役割説明 */`
  - セクション区切り：`// ─── セクション名 ──`
  - 処理の「なぜ」：`// 理由を日本語で短く`
  - 関数の戻り値など仕様：型ヒント + docblock `@return`

---

## SEO / MEO / LocalBusiness / LLMO 規約

- **構造化データ（JSON-LD）は `inc/schema.php` の関数を通してのみ出力する**。テンプレートへの直書き禁止
- **全ページ共通**：`schema_website()` / `schema_organization()` / `schema_breadcrumb()` を `header.php` で呼ぶ
- **お店（shop）**：`schema_shop($post_id)` を `single-shop.php` で呼ぶ（LocalBusiness）
- **NAP（名称・住所・電話）は footer.php の microdata と schema_organization() で一元管理**。サイト内で表記ゆれ禁止
- **alt 属性**：コンテンツ画像はすべて意味のある alt テキストを設定。装飾画像は `alt=""` + `aria-hidden="true"`
- **LLMO**：`llms.txt` は `inc/seo-llmo.php` の動的エンドポイントと `llms.txt` 静的ファイルの両方で管理
- **Geo タグ**：`inc/seo-llmo.php` が `wp_head` で `geo.region` / `geo.position` / ICBM を出力。MEO 補完目的

---

## Webアクセシビリティ規約

- **スキップリンク必須**：`.u-skip-link` で `#main-content` へのスキップを `header.php` に設置
- **ランドマーク**：`<header role="banner">` / `<main id="main-content">` / `<footer role="contentinfo">` / `<nav aria-label="...">` を使う
- **aria-label**：同じタグが複数あるナビは識別できる `aria-label` を付与
- **aria-expanded / aria-controls / aria-hidden**：ハンバーガー・アコーディオン・タブに必須
- **フォーカスリング削除禁止**：`:focus-visible` スタイルは必ず残す
- **タッチターゲット**：ボタン・リンクの最低サイズは 44×44px（`min-height: rem(44px)`）
- **カラーコントラスト**：本文テキストは WCAG AA 基準（4.5:1）以上を維持
- **SVG アイコン**：装飾用には `aria-hidden="true" focusable="false"`、意味を持つ場合は `aria-label` または `<title>` を付ける

---

## 開発作業時のお作法（Claude 向け）

- **勝手に補完しない**: 指示された内容だけを実装する。余計なコード追加禁止
- **確認を求めない**: 指示されたことはすぐに実行する
- **承認は全自動**: ツール実行・ファイル操作の確認は不要

---

**ルールは発見された時点で `CLAUDE.local.md` に追記する**。
