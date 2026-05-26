# 七間町（しちけんちょう）公式サイト ── WordPress再構築 完全指示プロンプト

---

## はじめに：このドキュメントの使い方

このドキュメントは、Manus AIの新しいタスクにそのまま貼り付けて使用する「完全な指示書」である。七間町商店街の公式Webサイトを、WordPress（PHP/HTML/CSS/JavaScript + ACF無料版）でゼロからオリジナルテーマとして構築するための、要件定義・基本設計・詳細設計・実装ルール・SEO/LLMO対策・レスポンシブUX設計のすべてを網羅している。

**別添ドキュメント「サイトマップ・サイト構成書」と必ずセットで使用すること。** サイト構成書には全ページのACFフィールド設計、カスタム投稿タイプ設計、テーマファイル構成が記載されている。

---

## A. プロジェクト概要

### A.1 プロジェクト名

七間町（しちけんちょう）商店街 公式Webサイト WordPress版

### A.2 サイトコンセプト

**「和紙の記憶」** ── 観光ルートではなく、町の時間・記憶・人・風景をたどる回遊体験を提供する地域ポータルサイト。静岡市葵区に位置する歴史ある商店街エリア「七間町」の魅力を、観光客・住民・事業者の三者に向けて発信する。

### A.3 技術スタック（厳守事項）

| 項目 | 指定 |
|------|------|
| CMS | WordPress（最新安定版） |
| テーマ | 完全オリジナルテーマ（`shichikancho-theme`） |
| 言語 | PHP / HTML5 / CSS3 / Vanilla JavaScript |
| カスタムフィールド | ACF（Advanced Custom Fields）**有料版（PRO）** |
| ページビルダー | **使用禁止**（Elementor, WPBakery等は一切使わない） |
| CSSフレームワーク | 使用しない（**SCSS + FLOCSS設計** + CSS変数で構築） |
| JSフレームワーク | 使用しない（Vanilla JSのみ。jQuery最小限） |
| フォント | Google Fonts CDN（**Inter** + Noto Sans JP） |

### A.4 ターゲットユーザー

| ユーザー層 | ニーズ |
|-----------|-------|
| 観光客 | 七間町の見どころ、お店、イベント情報を知りたい |
| 住民 | くらしガイド、防災情報、地域のお知らせを確認したい |
| 事業者 | 求人掲載、空き物件情報、スポンサー募集に参加したい |

---

## B. 要件定義

### B.1 機能要件

1. **全27ページ**の固定ページ・アーカイブページ・個別ページを構築する（サイト構成書参照）
2. **11種類のカスタム投稿タイプ**を登録し、管理画面から追加・編集・非公開切り替えができること
3. **9種類のカスタムタクソノミー**でコンテンツを分類できること
4. **ACFフィールド**を使用し、各投稿タイプ・固定ページに必要なカスタムフィールドを設定すること
5. **お問い合わせフォーム**（Contact Form 7）を設置し、送信完了時にSlack通知を飛ばせる構成にすること
6. **タブ切り替えUI**を複数ページ（町で学ぶ、町で働く、くらしガイド、いのちを守る）で使用すること
7. **カテゴリーフィルター**をギャラリーページ、お店一覧、スポット一覧で使用すること
8. **FAQ（よくある質問）**をお店個別ページ、イベント個別ページ、学ぶ施設ページに設置し、FAQPage構造化データを出力すること
9. **パンくずリスト**を全ページに設置し、BreadcrumbList構造化データを出力すること
10. **Google Maps埋め込み**をお店個別ページ、イベント個別ページ、アクセスページに設置すること
11. **静岡県形SVGマップ**をトップページに設置し、七間町の位置をマーカーで示すこと
12. **レスポンシブデザイン**で全ページがモバイル・タブレット・デスクトップに最適化されていること
13. **ハンバーガーメニュー**をモバイル表示時に実装すること（フルスクリーンオーバーレイ式）
14. **写真募集セクション**をギャラリーページに設置すること（投稿機能は不要、案内のみ）
15. **SNSシェアボタン**をコラム個別ページ、イベント個別ページに設置すること

### B.2 非機能要件

| 項目 | 要件 |
|------|------|
| 表示速度 | Lighthouse Performance 90点以上 |
| アクセシビリティ | Lighthouse Accessibility 90点以上 |
| SEO | Lighthouse SEO 95点以上 |
| ブラウザ対応 | Chrome, Safari, Firefox, Edge（最新2バージョン） |
| モバイル対応 | iOS Safari, Android Chrome（最新2バージョン） |
| セキュリティ | HTTPS必須、XSS/CSRF対策、入力値サニタイズ |
| バックアップ | UpdraftPlus による自動バックアップ |
| 画像最適化 | WebP変換、遅延読み込み（loading="lazy"） |

---

## C. 基本設計

### C.1 デザインコンセプト：「静岡の記憶」

七間町が位置する静岡の自然・文化・歴史を色彩で表現する。茶畑の深い緑、駿河湾の青、富士山の雪白、みかんのオレンジ、和紙の生成りを基調とし、温かみのある「人間らしい」デザインを目指す。

### C.2 カラーパレット（静岡カラー）

#### メインカラー

| 色名 | HEX | 用途 | 由来 |
|------|-----|------|------|
| 茶畑の深緑 | `#2D5A27` | ヘッダー、ナビゲーション、見出し | 静岡茶の茶畑 |
| 萌黄色 | `#7BA23F` | アクセントボタン、ホバー状態 | 新茶の若葉 |
| 駿河湾の青 | `#1B6B93` | リンク、アクティブ要素、タブ | 駿河湾・太平洋 |
| 富士山の雪白 | `#F5F0EB` | メイン背景、余白 | 富士山の雪 |
| みかんのオレンジ | `#E8841A` | CTAボタン、緊急情報、強調 | 静岡みかん |

#### サブカラー

| 色名 | HEX | 用途 | 由来 |
|------|-----|------|------|
| 和紙の生成り | `#FAF7F2` | ページ背景、カード背景 | 駿河和紙 |
| 墨色 | `#2C2C2C` | 本文テキスト | 書道の墨 |
| 桜色 | `#F2D5CE` | アクセント背景、バッジ | 静岡の桜 |
| 土色 | `#8B6914` | 見出しアクセント、ボーダー | 登り窯の土 |
| 淡い青緑 | `#E8F4F0` | セクション背景（交互） | 安倍川の清流 |

#### CSS変数定義

```css
:root {
  /* メインカラー */
  --color-primary: #2D5A27;       /* 茶畑の深緑 */
  --color-primary-light: #7BA23F; /* 萌黄色 */
  --color-secondary: #1B6B93;     /* 駿河湾の青 */
  --color-background: #F5F0EB;    /* 富士山の雪白 */
  --color-accent: #E8841A;        /* みかんのオレンジ */

  /* サブカラー */
  --color-bg-alt: #FAF7F2;        /* 和紙の生成り */
  --color-text: #2C2C2C;          /* 墨色 */
  --color-text-light: #666666;    /* 薄墨 */
  --color-sakura: #F2D5CE;        /* 桜色 */
  --color-earth: #8B6914;         /* 土色 */
  --color-section-alt: #E8F4F0;   /* 淡い青緑 */

  /* ボーダー・シャドウ */
  --color-border: #E0D8CF;
  --color-border-light: #F0EBE5;
  --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
  --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
  --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);

  /* タイポグラフィ */
  --font-sans: 'Inter', 'Noto Sans JP', sans-serif;
  --font-serif: 'Noto Sans JP', serif;
  --font-size-xs: 0.75rem;
  --font-size-sm: 0.875rem;
  --font-size-base: 1rem;
  --font-size-lg: 1.125rem;
  --font-size-xl: 1.25rem;
  --font-size-2xl: 1.5rem;
  --font-size-3xl: 1.875rem;
  --font-size-4xl: 2.25rem;

  /* スペーシング */
  --spacing-xs: 0.25rem;
  --spacing-sm: 0.5rem;
  --spacing-md: 1rem;
  --spacing-lg: 1.5rem;
  --spacing-xl: 2rem;
  --spacing-2xl: 3rem;
  --spacing-3xl: 4rem;

  /* ボーダーラジウス */
  --radius-sm: 4px;
  --radius-md: 8px;
  --radius-lg: 12px;
  --radius-xl: 16px;
  --radius-full: 9999px;

  /* ブレイクポイント */
  --bp-sm: 640px;
  --bp-md: 768px;
  --bp-lg: 1024px;
  --bp-xl: 1280px;

  /* コンテナ */
  --container-max: 1200px;
  --container-padding: 1rem;
}

@media (min-width: 768px) {
  :root {
    --container-padding: 1.5rem;
  }
}

@media (min-width: 1024px) {
  :root {
    --container-padding: 2rem;
  }
}
```

### C.3 タイポグラフィ

| 要素 | フォント | サイズ（モバイル） | サイズ（デスクトップ） | ウェイト |
|------|---------|-----------------|---------------------|---------|
| h1 | Inter / Noto Sans JP | 1.75rem | 2.25rem | 700 |
| h2 | Inter / Noto Sans JP | 1.5rem | 1.875rem | 700 |
| h3 | Inter / Noto Sans JP | 1.25rem | 1.5rem | 600 |
| h4 | Inter / Noto Sans JP | 1.125rem | 1.25rem | 600 |
| 本文 | Inter / Noto Sans JP | 0.9375rem | 1rem | 400 |
| 小文字 | Inter / Noto Sans JP | 0.8125rem | 0.875rem | 400 |
| キャプション | Inter / Noto Sans JP | 0.75rem | 0.8125rem | 400 |

- **Inter**：英数字・記号（可変フォント対応）
- **Noto Sans JP**：日本語テキスト

Google Fonts CDN読み込み（`<head>`内）:
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
```

### C.4 レイアウトシステム

| 要素 | 仕様 |
|------|------|
| コンテナ最大幅 | 1200px |
| グリッドシステム | CSS Grid + Flexbox（CSSフレームワーク不使用） |
| カラム数 | モバイル1列 → タブレット2列 → デスクトップ3〜4列 |
| カード間隔 | モバイル16px → デスクトップ24px |
| セクション間隔 | モバイル48px → デスクトップ80px |

---

## D. 詳細設計

### D.1 レスポンシブデザイン設計（UX最適化）

#### ブレイクポイント

| 名称 | 幅 | 対象デバイス |
|------|-----|------------|
| sm | 640px | スマートフォン横向き |
| md | 768px | タブレット縦向き |
| lg | 1024px | タブレット横向き・小型ノートPC |
| xl | 1280px | デスクトップ |

#### モバイルファースト設計原則

1. **CSSはモバイルファーストで記述する。** ベースCSSはモバイル用、`min-width`メディアクエリで段階的に拡張する。
2. **タッチターゲットは最低44px x 44pxを確保する。** ボタン、リンク、タブのタップ領域を十分に確保する。
3. **タブ・フィルターは横スクロール対応にする。** `overflow-x: auto`と`scrollbar-hide`を使用し、モバイルで横スクロールできるようにする。
4. **グリッドはモバイルで1列、タブレットで2列、デスクトップで3〜4列に切り替える。**
5. **テキストサイズは`clamp()`関数で流体タイポグラフィを実装する。** 例: `font-size: clamp(1.5rem, 4vw, 2.25rem);`
6. **画像は`max-width: 100%`と`height: auto`を基本とし、`aspect-ratio`で比率を固定する。**
7. **stickyヘッダーの高さを考慮し、`scroll-margin-top`をアンカーリンク先に設定する。**
8. **ハンバーガーメニューはフルスクリーンオーバーレイ式とし、`body`のスクロールをロックする。**

#### scrollbar-hide ユーティリティ

```css
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
```

#### レスポンシブ画像

```html
<picture>
  <source srcset="image-400.webp 400w, image-800.webp 800w, image-1200.webp 1200w"
          sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
          type="image/webp">
  <img src="image-800.jpg" alt="説明文" loading="lazy" decoding="async"
       width="800" height="600">
</picture>
```

### D.2 ハンバーガーメニュー設計

```
┌─────────────────────────┐
│ [×] 閉じる              │
├─────────────────────────┤
│                         │
│  町の紹介               │
│  映画の町               │
│  町をめぐる             │
│  町に住む               │
│  町で学ぶ               │
│  町で働く               │
│  町のギャラリー         │
│  くらしガイド           │
│  いのちを守る           │
│                         │
│ ─────────────────────── │
│                         │
│  [🏠] [📷] [📰] [📞]  │
│  ホーム ギャラリー       │
│  コラム お問い合わせ     │
│                         │
│ ─────────────────────── │
│  🇯🇵 日本語 | EN        │
│                         │
└─────────────────────────┘
```

**実装要件:**
- `position: fixed; inset: 0; z-index: 9999;`
- 背景: `rgba(0,0,0,0.95)` または `var(--color-primary)` の半透明
- 開閉アニメーション: `transform: translateX(100%)` → `translateX(0)` + `transition: 0.3s ease`
- メニュー開時に `body` に `overflow: hidden` を付与してスクロールロック
- メニュー内リンクをクリックしたら自動で閉じる
- ESCキーでも閉じる

### D.3 タブ切り替えUI設計

```html
<div class="tab-navigation scrollbar-hide" role="tablist">
  <button class="tab-btn active" role="tab" aria-selected="true"
          data-tab="tab1">タブ1</button>
  <button class="tab-btn" role="tab" aria-selected="false"
          data-tab="tab2">タブ2</button>
</div>
<div class="tab-panel active" role="tabpanel" id="tab1">
  <!-- コンテンツ -->
</div>
<div class="tab-panel" role="tabpanel" id="tab2" hidden>
  <!-- コンテンツ -->
</div>
```

**実装要件:**
- タブバーは `overflow-x: auto` + `scrollbar-hide` でモバイル横スクロール対応
- タブボタンは `white-space: nowrap` + `min-width: fit-content`
- アクティブタブは `var(--color-primary)` の下線 + テキスト色変更
- `aria-selected`, `role="tab"`, `role="tabpanel"` でアクセシビリティ確保
- URLハッシュでタブ状態を保持（`#career`, `#space`等）

### D.4 FAQ設計

FAQは以下のページに設置する:

| ページ | FAQ内容 |
|--------|---------|
| お店個別ページ | 各店舗固有のFAQ（ACFリピーターで管理） |
| イベント個別ページ | 各イベント固有のFAQ（ACFリピーターで管理） |
| 学ぶ施設ページ | 各施設固有のFAQ（ACFリピーターで管理） |
| 町をめぐるページ | 散策コースに関する共通FAQ |
| くらしガイドページ | 生活に関する共通FAQ |

**FAQ表示形式:** アコーディオン（クリックで開閉）

```html
<section class="faq-section" itemscope itemtype="https://schema.org/FAQPage">
  <h2>よくある質問</h2>
  <div class="faq-item" itemscope itemprop="mainEntity"
       itemtype="https://schema.org/Question">
    <button class="faq-question" itemprop="name"
            aria-expanded="false" aria-controls="faq-1">
      質問テキスト
      <span class="faq-icon">+</span>
    </button>
    <div class="faq-answer" id="faq-1" itemprop="acceptedAnswer"
         itemscope itemtype="https://schema.org/Answer" hidden>
      <div itemprop="text">
        <p>回答テキスト</p>
      </div>
    </div>
  </div>
</section>
```

**加えて、JSON-LDでも出力する（後述のSEOセクション参照）。**

---

## E. 実装ルール（コーディング規約）

### E.1 PHPコーディングルール

1. **WordPress Coding Standards に準拠する。** インデントはタブ、関数名はスネークケース。
2. **テンプレートパーツは `get_template_part()` で呼び出す。** 直接 `include` しない。
3. **ACFフィールドの取得は `get_field()` / `the_field()` を使用する。** 値が空の場合のフォールバックを必ず記述する。
4. **出力は必ずエスケープする。** `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()` を適切に使い分ける。
5. **カスタム投稿タイプ・タクソノミーの登録は `functions.php` から `inc/custom-post-types.php` と `inc/taxonomies.php` を読み込む形で分離する。**
6. **WP_Queryの使用後は `wp_reset_postdata()` を必ず呼ぶ。**
7. **管理画面のカラム表示は Admin Columns Pro（または手動）で最適化し、運用者が楽になる構成にする。**

### E.2 HTML/CSSコーディングルール

1. **セマンティックHTMLを徹底する。** `<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>` を適切に使用する。
2. **SCSSでFLOCSSアーキテクチャを採用する。** Foundation / Layout / Object（Component / Project / Utility）の3層構成。
3. **クラス命名はFLOCSSのプレフィックス規則に従う。** `l-`（Layout）、`c-`（Component）、`p-`（Project）、`u-`（Utility）、`is-`（State）、`js-`（JS hook）。
4. **CSS変数（カスタムプロパティ）でカラー・フォント・スペーシングを管理する。** SCSSの `_variables.scss` で定義し、`:root` にも出力してJSから参照可能にする。
5. **`!important` の使用は `u-`（Utility）クラスのみ許可。** それ以外は詳細度で解決する。
6. **画像には必ず `alt` 属性を設定する。** 装飾画像は `alt=""` + `role="presentation"`。
7. **フォーム要素には `<label>` を紐付ける。** `for` 属性と `id` で関連付け。
8. **フォーカスリングを削除しない。** キーボードナビゲーションのアクセシビリティを確保する。

### E.3 JavaScriptコーディングルール

1. **Vanilla JSで記述する。** jQueryは最小限（WordPress同梱のものを使用する場合のみ）。
2. **`DOMContentLoaded` イベント内で初期化する。**
3. **イベントリスナーは `addEventListener` を使用する。** インラインイベントハンドラ（`onclick`等）は使わない。
4. **DOM操作は最小限にする。** 必要な要素はキャッシュする。
5. **`const` / `let` を使用する。** `var` は使わない。
6. **テンプレートリテラルを使用する。** 文字列結合は `+` ではなくバッククォート。

### E.4 ファイル命名規則

| 対象 | 規則 | 例 |
|------|------|-----|
| PHPファイル | ケバブケース | `page-about.php`, `single-shop.php` |
| CSSファイル | ケバブケース | `style.css`, `responsive.css` |
| JSファイル | ケバブケース | `main.js`, `navigation.js` |
| 画像ファイル | ケバブケース + 用途 | `hero-main.jpg`, `icon-phone.svg` |
| ACFフィールド | スネークケース | `shop_name`, `event_date_start` |

### E.5 Git運用ルール

1. **`.gitignore`** に `node_modules/`, `.env`, `wp-config.php`, `uploads/` を含める。
2. **コミットメッセージ** は日本語で、`[feat]`, `[fix]`, `[style]`, `[refactor]` のプレフィックスを付ける。
3. **ブランチ戦略**: `main`（本番）← `develop`（開発）← `feature/*`（機能別）

---

## F. SEO設計（Google 2026年3月コアアップデート対応）

### F.1 基本SEO対策

1. **タイトルタグ**: 各ページ固有のタイトル。形式: `{ページ名} | 七間町 ─ 静岡市葵区の商店街`
2. **メタディスクリプション**: 各ページ120〜160文字のユニークな説明文。ACFフィールドまたはYoast SEOで管理。
3. **見出し構造**: h1は1ページ1つ。h2→h3→h4の階層を厳守。見出し直後に必ず導入文を置く。
4. **パンくずリスト**: 全ページに設置。BreadcrumbList構造化データを出力。
5. **XMLサイトマップ**: Yoast SEOで自動生成。カスタム投稿タイプも含める。
6. **robots.txt**: AIクローラーへの対応を含める（後述）。
7. **canonical URL**: 全ページに設定。
8. **OGP / Twitterカード**: 全ページに設定。画像は1200x630px。
9. **内部リンク**: 関連コンテンツへの内部リンクを積極的に設置。
10. **最終更新日**: 全ページに `dateModified` を表示・構造化データに含める。

### F.2 E-E-A-T対応（2026年最新）

Google 2026年3月コアアップデートでは、E-E-A-T（Experience, Expertise, Authoritativeness, Trust）の評価がさらに厳格化された。以下の対策を実装する。

| E-E-A-T要素 | 実装方法 |
|------------|---------|
| Experience（経験） | 現場の写真、住民インタビュー、実際のイベントレポートなど一次情報を掲載 |
| Expertise（専門性） | 七間町に特化したコンテンツ。サイト全体のトピカリティを「静岡市七間町」に集中 |
| Authoritativeness（権威性） | 運営団体情報の明記、静岡市役所等の公式情報へのリンク、地域メディアからの被リンク獲得 |
| Trust（信頼性） | HTTPS、プライバシーポリシー、運営会社情報、お問い合わせ先の明記 |

### F.3 ローカルSEO / MEO対策

1. **NAP情報の一貫性**: サイト内の住所・電話番号・名称を統一する。フッターに常時表示。
2. **LocalBusiness構造化データ**: 各店舗ページにJSON-LDで出力。
3. **Googleビジネスプロフィール連携**: 各店舗ページからGBPへのリンク。
4. **地域キーワード最適化**: 「七間町」「静岡市葵区」「静岡 商店街」等のキーワードを自然に含める。
5. **地域の口コミ・レビュー**: お隣さんの話（住民インタビュー）を活用。

### F.4 構造化データ（JSON-LD）完全実装ガイド

全ページの `<head>` 内または `</body>` 直前に、ページ種別に応じた構造化データをJSON-LD形式で出力する。`inc/schema.php` に出力関数を集約し、各テンプレートから呼び出す設計とする。構造化データは「入れられるところには全て入れる」方針で、Google検索のリッチリザルト表示とAI検索での引用率を最大化する。

#### F.4.1 全ページ共通Schema（3種類）

全ページの `<head>` 内に以下の3つを必ず出力する。

| Schema.orgタイプ | 用途 | 出力関数名 |
|-----------------|------|----------|
| `WebSite` | サイト名・URL・サイト内検索ボックス | `schema_website()` |
| `Organization` | 七間町商店街振興組合の団体情報 | `schema_organization()` |
| `BreadcrumbList` | パンくずリスト（全階層） | `schema_breadcrumb()` |

```json
// WebSite + SearchAction
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "七間町 - しちけんちょう",
  "url": "https://shichikancho.com/",
  "description": "静岡市葵区・七間町商店街の公式サイト。お店、イベント、観光、暮らしの情報をお届けします。",
  "inLanguage": "ja",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://shichikancho.com/?s={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
```

```json
// Organization（商店街振興組合）
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "七間町商店街振興組合",
  "url": "https://shichikancho.com/",
  "logo": "https://shichikancho.com/assets/images/logo.png",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "七間町",
    "addressLocality": "静岡市葵区",
    "addressRegion": "静岡県",
    "postalCode": "420-0035",
    "addressCountry": "JP"
  },
  "sameAs": [
    "https://www.instagram.com/shichikancho/",
    "https://twitter.com/shichikancho",
    "https://www.facebook.com/shichikancho"
  ],
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "054-XXX-XXXX",
    "contactType": "customer service",
    "availableLanguage": "Japanese"
  }
}
```

#### F.4.2 ページ別Schema一覧（全27ページ対応）

以下の表に基づき、各ページに適切なSchemaを追加出力する。共通の3種類に加えて、ページ固有のSchemaを重ねて出力する。

| ページ | テンプレート | 追加Schema | 備考 |
|--------|------------|-----------|------|
| トップページ | `front-page.php` | `LocalBusiness`（商店街全体）, `ItemList`（カテゴリーナビ）, `Event`（直近イベント） | 商店街全体をLocalBusinessとして出力 |
| 町の紹介 | `page-about.php` | `AboutPage`, `Place`（七間町エリア） | |
| 映画の町 | `page-cinema.php` | `WebPage`, `Article`（歴史コンテンツ） | |
| 町をめぐる | `page-walk.php` | `ItemList`（コース一覧）, `TouristTrip`（各コース） | |
| 観光情報 | `page-tourism.php` | `TouristDestination`, `ItemList`（スポット一覧） | |
| 町に住む | `page-living.php` | `WebPage`, `Place`（住環境情報） | |
| 町で学ぶ | `page-learn.php` | `ItemList`（施設リスト） | |
| 町で働く | `page-work.php` | `WebPage` | 子要素のSchema参照 |
| 町のギャラリー | `page-gallery.php` | `ImageGallery`, `CollectionPage` | |
| くらしガイド | `page-guide.php` | `FAQPage`（ごみ・生活ルール）, `HowTo`（ごみの出し方） | 公共施設・医療機関は個別Schema |
| いのちを守る | `page-safety.php` | `FAQPage`（日頃の備え）, `WebPage` | 緊急連絡先・避難場所は個別Schema |
| アクセス | `page-access.php` | `Place`（七間町位置情報）, `Map` | |
| お問い合わせ | `page-contact.php` | `ContactPage` | |
| インフォメーション | `page-info.php` | `ItemList`（お知らせリスト） | |
| スポンサー募集 | `page-sponsor.php` | `WebPage`, `Offer`（スポンサープラン） | |
| お店一覧 | `archive-shop.php` | `ItemList`（お店リスト） | |
| **お店個別** | `single-shop.php` | **`LocalBusiness`（サブタイプ別）**, `ImageGallery`, `FAQPage` | **最重要** |
| イベント一覧 | `archive-event.php` | `ItemList`（イベントリスト） | |
| **イベント個別** | `single-event.php` | **`Event`**, `Place`（会場）, `Offer`（参加費）, `FAQPage` | **最重要** |
| スポット一覧 | `archive-spot.php` | `ItemList`（スポットリスト） | |
| **スポット個別** | `single-spot.php` | **`TouristAttraction`**, `Place`, `ImageGallery` | |
| コラム一覧 | `archive-column.php` | `ItemList`, `Blog` | |
| **コラム個別** | `single-column.php` | **`Article`/`BlogPosting`**, `Person`（著者） | |
| お隣さん一覧 | `archive-resident.php` | `ItemList` | |
| お隣さん個別 | `single-resident.php` | `Article`, `Person`（住民） | |
| プライバシーポリシー | `page-privacy.php` | `WebPage` | |
| 利用規約 | `page-terms.php` | `WebPage` | |
| 運営会社 | `page-company.php` | `WebPage` | |

#### F.4.3 LocalBusinessのサブタイプ使い分け（お店個別ページ）

お店個別ページ（`single-shop.php`）では、ACFの `shop_category` タクソノミーに基づいて、LocalBusinessの最も具体的なサブタイプを自動選択する。具体的なサブタイプを使うほどGoogleの理解度が上がり、リッチリザルトの表示確率が高まる。

| 店舗カテゴリー | Schema.orgタイプ | 必須追加プロパティ |
|-------------|-----------------|------------------|
| 食べる（レストラン） | `Restaurant` | `servesCuisine`, `menu`, `acceptsReservations` |
| 食べる（カフェ） | `CafeOrCoffeeShop` | `servesCuisine` |
| 食べる（パン屋） | `Bakery` | `servesCuisine` |
| 食べる（居酒屋・バー） | `BarOrPub` | `servesCuisine` |
| 食べる（その他飲食） | `FoodEstablishment` | `servesCuisine` |
| 買う（衣料品） | `ClothingStore` | |
| 買う（書店） | `BookStore` | |
| 買う（食料品） | `GroceryStore` | |
| 買う（雑貨・日用品） | `Store` | |
| 買う（その他小売） | `Store` | |
| 遊ぶ（スポーツ） | `SportsActivityLocation` | |
| 遊ぶ（娯楽） | `EntertainmentBusiness` | |
| 泊まる（ホテル） | `Hotel` | `checkinTime`, `checkoutTime`, `starRating` |
| 泊まる（ゲストハウス） | `LodgingBusiness` | `checkinTime`, `checkoutTime` |
| 学ぶ | `EducationalOrganization` | |
| 美容（美容室） | `HairSalon` | |
| 美容（ネイル） | `NailSalon` | |
| 美容（エステ） | `BeautySalon` | |
| 医療（病院） | `MedicalBusiness` | `medicalSpecialty` |
| 医療（歯科） | `Dentist` | |
| 医療（薬局） | `Pharmacy` | |
| その他 | `LocalBusiness` | |

**PHP実装パターン（`inc/schema.php`内）:**

```php
function get_shop_schema_type( $shop_id ) {
    $categories = wp_get_post_terms( $shop_id, 'shop_category', ['fields' => 'slugs'] );
    $type_map = [
        'restaurant'  => 'Restaurant',
        'cafe'        => 'CafeOrCoffeeShop',
        'bakery'      => 'Bakery',
        'bar'         => 'BarOrPub',
        'food'        => 'FoodEstablishment',
        'clothing'    => 'ClothingStore',
        'bookstore'   => 'BookStore',
        'grocery'     => 'GroceryStore',
        'store'       => 'Store',
        'sports'      => 'SportsActivityLocation',
        'entertainment' => 'EntertainmentBusiness',
        'hotel'       => 'Hotel',
        'lodging'     => 'LodgingBusiness',
        'education'   => 'EducationalOrganization',
        'hair-salon'  => 'HairSalon',
        'nail-salon'  => 'NailSalon',
        'beauty'      => 'BeautySalon',
        'medical'     => 'MedicalBusiness',
        'dentist'     => 'Dentist',
        'pharmacy'    => 'Pharmacy',
    ];
    foreach ( $categories as $cat ) {
        if ( isset( $type_map[ $cat ] ) ) return $type_map[ $cat ];
    }
    return 'LocalBusiness';
}
```

**JSON-LD出力例（LocalBusiness完全版 — Restaurant）:**

```json
{
  "@context": "https://schema.org",
  "@type": "Restaurant",
  "name": "七間町食堂",
  "description": "地元の新鮮食材を使った定食が人気の食堂です。",
  "image": [
    "https://shichikancho.com/wp-content/uploads/shop-main.jpg",
    "https://shichikancho.com/wp-content/uploads/shop-interior.jpg"
  ],
  "url": "https://shichikancho.com/shops/shichikancho-shokudo/",
  "telephone": "054-XXX-XXXX",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "七間町12-3",
    "addressLocality": "静岡市葵区",
    "addressRegion": "静岡県",
    "postalCode": "420-0035",
    "addressCountry": "JP"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 34.9756,
    "longitude": 138.3831
  },
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
      "opens": "11:00",
      "closes": "21:00"
    },
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": "Sunday",
      "opens": "11:00",
      "closes": "15:00"
    }
  ],
  "priceRange": "¥800〜¥1,500",
  "servesCuisine": "和食",
  "acceptsReservations": true,
  "menu": "https://shichikancho.com/shops/shichikancho-shokudo/#menu",
  "hasMap": "https://www.google.com/maps?q=34.9756,138.3831",
  "sameAs": [
    "https://www.instagram.com/shichikancho_shokudo/"
  ],
  "isAccessibleForFree": false,
  "paymentAccepted": "現金, クレジットカード",
  "areaServed": {
    "@type": "City",
    "name": "静岡市"
  }
}
```

#### F.4.4 イベント個別ページのSchema（Event）

イベント個別ページ（`single-event.php`）では、ACFの `event_category` に基づいてEventのサブタイプを選択する。

| イベントカテゴリー | Schema.orgタイプ |
|-----------------|----------------|
| 祭り | `Festival` |
| マルシェ | `SaleEvent` |
| ワークショップ | `EducationEvent` |
| 映画上映 | `ScreeningEvent` |
| 音楽 | `MusicEvent` |
| 食 | `FoodEvent` |
| 展示 | `ExhibitionEvent` |
| その他 | `Event` |

```json
{
  "@context": "https://schema.org",
  "@type": "Festival",
  "name": "七間町 新春初詣ウォーク",
  "description": "七間町の神社仏閣を巡る新春恒例の初詣ウォーキングイベント。",
  "image": "https://shichikancho.com/wp-content/uploads/event-hatsumode.jpg",
  "startDate": "2026-01-03T10:00:00+09:00",
  "endDate": "2026-01-03T15:00:00+09:00",
  "eventStatus": "https://schema.org/EventScheduled",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "location": {
    "@type": "Place",
    "name": "七間町商店街",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "七間町",
      "addressLocality": "静岡市葵区",
      "addressRegion": "静岡県",
      "postalCode": "420-0035",
      "addressCountry": "JP"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 34.9756,
      "longitude": 138.3831
    }
  },
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "JPY",
    "availability": "https://schema.org/InStock",
    "validFrom": "2025-12-01"
  },
  "organizer": {
    "@type": "Organization",
    "name": "七間町商店街振興組合",
    "url": "https://shichikancho.com/"
  },
  "performer": {
    "@type": "Organization",
    "name": "七間町商店街振興組合"
  }
}
```

#### F.4.5 スポット個別ページのSchema（TouristAttraction）

```json
{
  "@context": "https://schema.org",
  "@type": "TouristAttraction",
  "name": "駿府城公園",
  "description": "徳川家康が築いた駿府城の跡地に整備された公園。桜の名所としても有名。",
  "image": "https://shichikancho.com/wp-content/uploads/spot-sunpu.jpg",
  "url": "https://shichikancho.com/spots/sunpu-castle-park/",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "駿府城公園1-1",
    "addressLocality": "静岡市葵区",
    "addressRegion": "静岡県",
    "postalCode": "420-0855",
    "addressCountry": "JP"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 34.9772,
    "longitude": 138.3836
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
    "opens": "06:00",
    "closes": "21:00"
  },
  "isAccessibleForFree": true,
  "touristType": ["ファミリー", "カップル", "一人旅"]
}
```

#### F.4.6 コラム・お隣さん個別ページのSchema（Article / BlogPosting）

```json
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "七間町の朝、カフェから始まる一日",
  "description": "早朝の静けさの中、一杯のコーヒーとともに始まる七間町の一日。",
  "image": "https://shichikancho.com/wp-content/uploads/column-morning.jpg",
  "url": "https://shichikancho.com/columns/morning-cafe/",
  "datePublished": "2026-01-15T09:00:00+09:00",
  "dateModified": "2026-01-20T14:30:00+09:00",
  "author": {
    "@type": "Person",
    "name": "七ぶら編集部",
    "image": "https://shichikancho.com/wp-content/uploads/author.jpg"
  },
  "publisher": {
    "@type": "Organization",
    "name": "七間町商店街振興組合",
    "logo": {
      "@type": "ImageObject",
      "url": "https://shichikancho.com/assets/images/logo.png"
    }
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://shichikancho.com/columns/morning-cafe/"
  },
  "wordCount": 1500,
  "inLanguage": "ja"
}
```

#### F.4.7 求人情報のSchema（JobPosting）

求人情報（`job` 投稿タイプ）は Google for Jobs に表示されるため、`JobPosting` Schemaの実装は極めて重要。

```json
{
  "@context": "https://schema.org",
  "@type": "JobPosting",
  "title": "カフェスタッフ（アルバイト）",
  "description": "七間町のカフェでのホール・キッチンスタッフを募集します。未経験歓迎。",
  "datePosted": "2026-04-01",
  "validThrough": "2026-05-31T23:59:59+09:00",
  "employmentType": "PART_TIME",
  "hiringOrganization": {
    "@type": "Organization",
    "name": "七間町カフェ",
    "sameAs": "https://shichikancho.com/shops/shichikancho-cafe/",
    "logo": "https://shichikancho.com/wp-content/uploads/cafe-logo.jpg"
  },
  "jobLocation": {
    "@type": "Place",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "七間町15-2",
      "addressLocality": "静岡市葵区",
      "addressRegion": "静岡県",
      "postalCode": "420-0035",
      "addressCountry": "JP"
    }
  },
  "baseSalary": {
    "@type": "MonetaryAmount",
    "currency": "JPY",
    "value": {
      "@type": "QuantitativeValue",
      "value": 1100,
      "unitText": "HOUR"
    }
  },
  "jobBenefits": "交通費支給、まかない付き",
  "qualifications": "18歳以上"
}
```

#### F.4.8 空き物件のSchema（RealEstateListing）

```json
{
  "@context": "https://schema.org",
  "@type": "RealEstateListing",
  "name": "七間町通り 1F店舗",
  "description": "七間町通りに面した好立地の1階店舗物件。飲食店・小売店に最適。",
  "url": "https://shichikancho.com/properties/shichikancho-1f-store/",
  "image": "https://shichikancho.com/wp-content/uploads/property-1f.jpg",
  "datePosted": "2026-03-15",
  "offers": {
    "@type": "Offer",
    "price": 150000,
    "priceCurrency": "JPY",
    "priceSpecification": {
      "@type": "UnitPriceSpecification",
      "price": 150000,
      "priceCurrency": "JPY",
      "unitText": "月額"
    }
  },
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "七間町10-5",
    "addressLocality": "静岡市葵区",
    "addressRegion": "静岡県",
    "postalCode": "420-0035",
    "addressCountry": "JP"
  },
  "floorSize": {
    "@type": "QuantitativeValue",
    "value": 45,
    "unitCode": "MTK"
  }
}
```

#### F.4.9 学ぶ施設のSchema（EducationalOrganization）

学ぶ施設（`learn_facility` 投稿タイプ）では、`learn_category` に基づいてサブタイプを選択する。

| 学ぶカテゴリー | Schema.orgタイプ |
|-------------|----------------|
| 文化・歴史体験 | `TouristAttraction` + `EducationalOrganization` |
| 塾・学習塾 | `EducationalOrganization` |
| 習い事・教室（スポーツ系） | `SportsActivityLocation` |
| 習い事・教室（文化系） | `EducationalOrganization` |
| 資格・スキルアップ | `EducationalOrganization` |

```json
{
  "@context": "https://schema.org",
  "@type": "EducationalOrganization",
  "name": "七間町学習塾",
  "description": "小学生から高校生まで対応する個別指導塾。",
  "url": "https://shichikancho.com/learn/shichikancho-juku/",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "七間町8-1",
    "addressLocality": "静岡市葵区",
    "addressRegion": "静岡県",
    "postalCode": "420-0035",
    "addressCountry": "JP"
  },
  "telephone": "054-XXX-XXXX",
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
      "opens": "15:00",
      "closes": "21:00"
    }
  ]
}
```

#### F.4.10 くらしガイドのSchema（医療機関・公共施設）

くらしガイドページ（`page-guide.php`）では、各セクションに応じた複数のSchemaを出力する。

| セクション | Schema.orgタイプ | 備考 |
|-----------|-----------------|------|
| 公共施設 | `CivicStructure` / `Library` / `Park` / `GovernmentBuilding` | 施設種別で使い分け |
| 医療機関 | `MedicalOrganization` のサブタイプ | 下表参照 |
| ごみの出し方 | `HowTo` + `FAQPage` | 手順ガイド形式 |
| 生活ルール | `FAQPage` | Q&A形式 |
| 外部リンク | `WebPage` | |

**医療機関のサブタイプ:**

| 医療機関種別 | Schema.orgタイプ |
|------------|----------------|
| 総合病院 | `Hospital` |
| 内科 | `Physician` |
| 歯科 | `Dentist` |
| 眼科 | `Physician`（`medicalSpecialty: Ophthalmology`） |
| 小児科 | `Physician`（`medicalSpecialty: Pediatrics`） |
| 薬局 | `Pharmacy` |
| 接骨院 | `MedicalBusiness` |

```json
// 医療機関の例
{
  "@context": "https://schema.org",
  "@type": "Dentist",
  "name": "七間町歯科クリニック",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "七間町5-3",
    "addressLocality": "静岡市葵区",
    "addressRegion": "静岡県",
    "postalCode": "420-0035",
    "addressCountry": "JP"
  },
  "telephone": "054-XXX-XXXX",
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday","Tuesday","Wednesday","Friday"],
      "opens": "09:00",
      "closes": "18:00"
    }
  ],
  "medicalSpecialty": "Dentistry"
}
```

#### F.4.11 いのちを守るページのSchema（EmergencyService・CivicStructure）

```json
// 緊急連絡先
{
  "@context": "https://schema.org",
  "@type": "EmergencyService",
  "name": "静岡市消防局",
  "telephone": "119",
  "areaServed": {
    "@type": "City",
    "name": "静岡市"
  }
}

// 避難場所
{
  "@context": "https://schema.org",
  "@type": "CivicStructure",
  "name": "駿府城公園（広域避難場所）",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "駿府城公園1-1",
    "addressLocality": "静岡市葵区",
    "addressRegion": "静岡県",
    "addressCountry": "JP"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 34.9772,
    "longitude": 138.3836
  },
  "maximumAttendeeCapacity": 50000
}
```

#### F.4.12 FAQPage構造化データ（全対象ページ共通）

FAQセクションを持つ全ページに `FAQPage` Schemaを出力する。ACFリピーターフィールドから自動生成する。

**対象ページ:** お店個別、イベント個別、学ぶ施設、くらしガイド（ごみ・生活ルール）、いのちを守る（日頃の備え）、散策コース

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "駐車場はありますか？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "専用駐車場はございませんが、近隣にコインパーキングが複数あります。七間町パーキング（徒歩1分）が便利です。"
      }
    },
    {
      "@type": "Question",
      "name": "予約は必要ですか？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "ランチタイムは予約なしでもご利用いただけますが、ディナータイムは予約をおすすめします。お電話またはInstagramのDMで承ります。"
      }
    }
  ]
}
```

**PHP実装パターン（`inc/schema.php`内）:**

```php
function schema_faq( $faq_field_name = 'shop_faq' ) {
    $faqs = get_field( $faq_field_name );
    if ( empty( $faqs ) ) return;

    $questions = [];
    foreach ( $faqs as $faq ) {
        $questions[] = [
            '@type'          => 'Question',
            'name'           => esc_html( $faq['question'] ),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => esc_html( $faq['answer'] ),
            ],
        ];
    }

    $schema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $questions,
    ];

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
}
```

#### F.4.13 ItemList構造化データ（一覧ページ共通）

全アーカイブページ・一覧ページに `ItemList` Schemaを出力し、Googleのカルーセル表示を狙う。

```json
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "七間町のお店一覧",
  "numberOfItems": 24,
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "七間町食堂",
      "url": "https://shichikancho.com/shops/shichikancho-shokudo/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "七間町カフェ",
      "url": "https://shichikancho.com/shops/shichikancho-cafe/"
    }
  ]
}
```

#### F.4.14 構造化データの実装ルール

以下のルールを厳守すること。

1. **出力形式は JSON-LD のみ**を使用する。Microdata や RDFa は使わない。
2. **`inc/schema.php` に全出力関数を集約**し、各テンプレートから `schema_shop()`, `schema_event()` 等を呼び出す。
3. **ACFフィールドの値を動的に取得**して出力する。ハードコードしない。
4. **空のフィールドは出力しない**（`null` や空文字のプロパティは除外する）。
5. **Google Rich Results Test**（https://search.google.com/test/rich-results）で全ページの構造化データを検証する。
6. **Schema Markup Validator**（https://validator.schema.org/）でも補完検証する。
7. **1ページに複数のSchemaを出力する場合**は、それぞれ独立した `<script type="application/ld+json">` タグで出力する（`@graph` 配列でまとめてもよい）。
8. **日時はISO 8601形式**（`2026-04-29T10:00:00+09:00`）で出力する。
9. **画像URLは絶対パス**で出力する。
10. **`dateModified`** を全Article/BlogPostingに含め、コンテンツの鮮度をGoogleに伝える。

### F.5 Core Web Vitals最適化

| 指標 | 目標値 | 対策 |
|------|-------|------|
| LCP（Largest Contentful Paint） | 2.5秒以下 | ヒーロー画像のプリロード、WebP変換、CDN使用 |
| INP（Interaction to Next Paint） | 200ms以下 | JS実行の最適化、非同期処理 |
| CLS（Cumulative Layout Shift） | 0.1以下 | 画像に`width`/`height`属性、フォント`display:swap` |

**具体的な実装:**

```html
<!-- ヒーロー画像のプリロード -->
<link rel="preload" as="image" href="hero-main.webp" type="image/webp">

<!-- フォントのプリコネクト -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- 画像の遅延読み込み -->
<img src="photo.webp" alt="説明" loading="lazy" decoding="async"
     width="800" height="600">

<!-- CSSの非同期読み込み（非クリティカル） -->
<link rel="preload" href="non-critical.css" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
```

---

## G. LLMO対策（大規模言語モデル最適化）

### G.1 LLMO対策の概要

LLMO（Large Language Model Optimization）とは、ChatGPT、Gemini、Perplexity等のAI検索・AI回答システムに自サイトの情報が引用・参照されるよう最適化する施策である。従来のSEOに加え、AIが「読み取りやすい」構造でコンテンツを提供することが重要になる。

### G.2 llms.txt ファイルの設置

サイトルートに `llms.txt` を設置し、AIクローラーにサイトの概要を伝える。

```
# 七間町（しちけんちょう）公式サイト

> 静岡市葵区七間町の商店街ポータルサイト。観光情報、店舗案内、イベント、くらしガイド、防災情報を提供。

## サイト概要
- 運営: 七間町商店街振興組合
- 所在地: 静岡県静岡市葵区七間町
- 目的: 七間町の魅力発信、住民の生活支援、観光客の誘致

## 主要コンテンツ
- /about/ : 七間町の歴史と紹介
- /shops/ : 商店街の店舗一覧（飲食、小売、サービス）
- /events/ : イベント・催事情報
- /walk/ : 散策コース・観光スポット
- /guide/ : くらしガイド（公共施設、医療、ごみの出し方）
- /safety/ : 防災情報（避難場所、緊急連絡先、地震・津波・富士山噴火対策）
- /learn/ : 学習施設・塾・習い事
- /work/ : 求人・コワーキング・空き物件
- /gallery/ : 町の写真ギャラリー
- /column/ : 七ぶらコラム（暮らし・食・文化の記事）

## 連絡先
- メール: info@shichikancho.jp
- 電話: 054-XXX-XXXX
```

### G.3 robots.txt のAIクローラー対応

```
User-agent: *
Allow: /

# AI検索クローラーを許可
User-agent: GPTBot
Allow: /

User-agent: Google-Extended
Allow: /

User-agent: ChatGPT-User
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: ClaudeBot
Allow: /

Sitemap: https://shichikancho.jp/sitemap_index.xml
```

### G.4 AIが読み取りやすいコンテンツ構造

1. **「定義→背景→詳細→注意点→まとめ」の構造化された記述を徹底する。** 見出し直後に必ず導入文を置き、見出しだけが続く構成は避ける。
2. **FAQ形式のコンテンツを積極的に設置する。** 質問→回答の明確な構造がAIに引用されやすい。
3. **一次情報・独自データを掲載する。** AIが生成できない現場の写真、住民の声、独自の統計データ。
4. **比較コンテンツを充実させる。** 「七間町 vs 他の商店街」のような比較情報。
5. **著者情報・専門家情報を明記する。** 記事の著者名、プロフィール、専門分野を表示。
6. **最終更新日を全ページに表示する。** AIは最新情報を優先的に引用する。

### G.5 構造化データによるAI検索対応

前述のJSON-LD構造化データに加え、以下のメタ情報をHTMLに含める:

```html
<!-- AI検索向けメタ情報 -->
<meta name="description" content="ページの説明文（120〜160文字）">
<meta name="author" content="七間町商店街振興組合">
<meta name="geo.region" content="JP-22">
<meta name="geo.placename" content="静岡市葵区七間町">
<meta name="geo.position" content="34.9756;138.3828">
<meta name="ICBM" content="34.9756, 138.3828">

<!-- 記事の場合 -->
<meta property="article:published_time" content="2026-01-15T09:00:00+09:00">
<meta property="article:modified_time" content="2026-04-29T12:00:00+09:00">
<meta property="article:author" content="著者名">
<meta property="article:section" content="カテゴリー名">
```

---

## H. パフォーマンス最適化

### H.1 画像最適化

1. **WebP形式を優先使用する。** EWWW Image Optimizerで自動変換。
2. **`<picture>` 要素でレスポンシブ画像を提供する。** モバイル用・デスクトップ用のサイズ分け。
3. **`loading="lazy"` を全画像に設定する。** ファーストビューの画像は除外。
4. **`width` / `height` 属性を必ず設定する。** CLS防止。
5. **アイコンはSVGを使用する。** Font Awesomeは使わない。

### H.2 CSS/JS最適化

1. **SCSSのコンパイルはVS Code拡張「Live Sass Compiler」を使用する。** `npm run sass:watch` は使わない。`.vscode/settings.json` でコンパイル先を `assets/css/` に設定する。
2. **クリティカルCSSをインライン化する。** ファーストビューに必要なCSSを `<style>` タグで直接記述。
3. **非クリティカルCSSは非同期読み込みする。**
4. **JSは `defer` 属性で非同期読み込みする。** `<script src="main.js" defer></script>`
5. **不要なjQueryプラグインは使わない。**

**`.vscode/settings.json` のLive Sass Compiler設定例:**
```json
{
  "liveSassCompile.settings.formats": [
    {
      "format": "expanded",
      "extensionName": ".css",
      "savePath": "/assets/css"
    },
    {
      "format": "compressed",
      "extensionName": ".min.css",
      "savePath": "/assets/css"
    }
  ],
  "liveSassCompile.settings.excludeList": [
    "**/node_modules/**",
    ".vscode/**"
  ],
  "liveSassCompile.settings.generateMap": false
}
```

### H.3 キャッシュ設定

WP Fastest CacheまたはW3 Total Cacheで以下を設定:
- ページキャッシュ: 有効
- ブラウザキャッシュ: 有効（静的ファイルに1年のキャッシュヘッダー）
- GZIP圧縮: 有効
- HTML/CSS/JS圧縮: 有効

---

## I. セキュリティ対策

1. **HTTPS必須。** SSL証明書を設定し、HTTPからのリダイレクトを設定。
2. **WordPress本体・プラグイン・テーマを常に最新に保つ。**
3. **Wordfence Securityでファイアウォール・マルウェアスキャンを有効化。**
4. **管理画面のログインURLを変更する。**（WPS Hide Login等）
5. **XML-RPCを無効化する。**
6. **ファイル編集を無効化する。** `define('DISALLOW_FILE_EDIT', true);` を `wp-config.php` に追加。
7. **入力値は必ずサニタイズ・バリデーションする。** `sanitize_text_field()`, `wp_kses_post()` 等。
8. **出力は必ずエスケープする。** `esc_html()`, `esc_attr()`, `esc_url()` 等。

---

## J. 運用設計

### J.1 管理画面の最適化

1. **Admin Columns Pro（または手動カスタマイズ）** で投稿一覧のカラムを最適化する。お店一覧にはカテゴリー、住所、電話番号を表示。
2. **不要なメニュー項目を非表示にする。** 運用者が迷わないシンプルな管理画面。
3. **ACFフィールドのラベルと説明文を日本語で丁寧に記述する。** 運用者が直感的に操作できること。
4. **投稿ステータスの活用**: 下書き→レビュー待ち→公開のワークフロー。

### J.2 コンテンツ更新フロー

| コンテンツ | 更新頻度 | 担当 |
|-----------|---------|------|
| お知らせ | 随時 | 運営事務局 |
| イベント | 月1〜2回 | 運営事務局 |
| お店情報 | 随時（新規出店・閉店時） | 運営事務局 |
| コラム | 月1〜2回 | ライター |
| ギャラリー写真 | 月1回 | 運営事務局 |
| くらしガイド | 年1〜2回 | 運営事務局 |
| 防災情報 | 年1回 + 緊急時 | 運営事務局 |

### J.3 Slack通知設計

Contact Form 7のお問い合わせフォーム送信時に、Slack Webhook経由で通知を飛ばす。CF7のアクションフックまたはプラグイン（CF7 to Slack等）で実装。

---

## K. 実装の優先順位

### Phase 1: 基盤構築（1〜2週間）

1. WordPressインストール・初期設定
2. オリジナルテーマの骨格作成（`functions.php`, `header.php`, `footer.php`, `style.css`）
3. CSS変数・カラーパレット・タイポグラフィの定義
4. カスタム投稿タイプ・タクソノミーの登録
5. ACFフィールドグループの設定
6. レスポンシブグリッドシステムの構築
7. ハンバーガーメニューの実装

### Phase 2: 主要ページ構築（2〜3週間）

1. トップページ（静岡県形SVGマップ含む）
2. 町の紹介ページ
3. 商店街のお店（一覧・個別）
4. イベント（一覧・個別）
5. 町をめぐるページ
6. 七ぶらコラム

### Phase 3: サブページ構築（2〜3週間）

1. 映画の町
2. 町に住む・お隣さんの話
3. 町で学ぶ
4. 町で働く
5. 町のギャラリー
6. くらしガイド
7. いのちを守る

### Phase 4: フッターページ・仕上げ（1〜2週間）

1. フッターページ群（お問い合わせ、プライバシーポリシー等）
2. SEO最適化（構造化データ、メタタグ、サイトマップ）
3. LLMO対策（llms.txt、robots.txt、AI向けメタ情報）
4. パフォーマンス最適化（画像、キャッシュ、Core Web Vitals）
5. セキュリティ設定
6. テスト・検証・修正

---

## L. 品質チェックリスト

### L.1 各ページ共通チェック

- [ ] タイトルタグがユニークで適切か
- [ ] メタディスクリプションが120〜160文字で設定されているか
- [ ] h1が1つだけ存在し、見出し階層が正しいか
- [ ] パンくずリストが表示されているか
- [ ] BreadcrumbList構造化データが出力されているか
- [ ] OGP / Twitterカードが設定されているか
- [ ] canonical URLが設定されているか
- [ ] 最終更新日が表示されているか
- [ ] モバイル表示が崩れていないか
- [ ] タッチターゲットが44px以上あるか
- [ ] 画像に適切なalt属性があるか
- [ ] loading="lazy"が設定されているか（ファーストビュー除く）
- [ ] フォーカスリングが表示されるか
- [ ] Lighthouse Performance 90点以上か
- [ ] Lighthouse Accessibility 90点以上か
- [ ] Lighthouse SEO 95点以上か

### L.2 構造化データチェック

**全ページ共通（3種類）:**
- [ ] WebSite + SearchAction（全ページ）
- [ ] Organization（全ページ）
- [ ] BreadcrumbList（全ページ）

**ページ固有Schema:**
- [ ] LocalBusiness + サブタイプ自動切替（お店個別: Restaurant / CafeOrCoffeeShop / Bakery / Store 等）
- [ ] Event + サブタイプ自動切替（イベント個別: Festival / SaleEvent / ScreeningEvent 等）
- [ ] TouristAttraction（スポット個別）
- [ ] Article / BlogPosting + Person（コラム個別・お隣さん個別）
- [ ] JobPosting（求人情報 → Google for Jobs対応）
- [ ] RealEstateListing（空き物件情報）
- [ ] EducationalOrganization（学ぶ施設）
- [ ] FAQPage（お店個別・イベント個別・学ぶ施設・くらしガイド・いのちを守る・散策コース）
- [ ] HowTo（くらしガイド: ごみの出し方）
- [ ] Hospital / Dentist / Physician / Pharmacy（くらしガイド: 医療機関）
- [ ] EmergencyService（いのちを守る: 緊急連絡先）
- [ ] CivicStructure（いのちを守る: 避難場所 / くらしガイド: 公共施設）
- [ ] TouristDestination（観光情報）
- [ ] TouristTrip（町をめぐる: 各コース）
- [ ] ImageGallery（町のギャラリー・お店個別・スポット個別）
- [ ] ItemList（全アーカイブページ・一覧ページ）
- [ ] Place + GeoCoordinates（アクセス・各店舗・各スポット・避難場所）
- [ ] ContactPage（お問い合わせ）
- [ ] Offer（スポンサー募集・イベント参加費）

**検証ツール:**
- [ ] Google Rich Results Test（https://search.google.com/test/rich-results）で全ページ検証済み
- [ ] Schema Markup Validator（https://validator.schema.org/）で補完検証済み
- [ ] 空のフィールドが出力されていないことを確認
- [ ] 日時がISO 8601形式で出力されていることを確認
- [ ] 画像URLが絶対パスで出力されていることを確認

---

## M. 参考情報

### M.1 現行サイトのデザイン引き継ぎ

現行サイト（React版）のデザインと内容・情報はすべて引き継ぐ。ただし、カラーパレットは「静岡カラー」に変更する。レイアウト構成、セクション構成、コンテンツ構造は現行サイトを踏襲しつつ、WordPress/ACFの運用に最適化する。

### M.2 別添ドキュメント

- **サイトマップ・サイト構成書**: 全ページのACFフィールド設計、カスタム投稿タイプ設計、テーマファイル構成の詳細

---

**以上が、七間町公式サイトをWordPressで再構築するための完全な指示書である。このドキュメントとサイト構成書を組み合わせることで、デザイン・機能・SEO・LLMO対策・レスポンシブUXのすべてを網羅したWordPressサイトを構築できる。**
