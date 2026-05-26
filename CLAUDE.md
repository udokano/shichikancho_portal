# CLAUDE.md — sichikenchou テーマ

> ## 🚨🚨🚨 絶対ルール（最優先・違反厳禁） 🚨🚨🚨
>
> ### 1. ACF を PHP でハードコードしない
> 以下の関数を **新規 PHP に書くことを完全禁止**：
> - `acf_add_options_page()` / `acf_add_options_sub_page()`
> - `acf_add_local_field_group()` / `acf_register_field_group()`
> - `acf_register_block_type()`（フィールド付きブロック登録目的）
>
> **フィールド/オプションページ追加が必要な場合**：
> 1. インポート用 JSON を `acf-json/` 外（テーマ外の親ディレクトリ等）に生成
> 2. ユーザーに「管理画面 → ACF → ツール → インポート」を依頼
> 3. インポート後 ACF が自動で `acf-json/` に同期
>
> **管理画面UIが必要な場合**：WP ネイティブ（`add_submenu_page()` + `update_option()` 等）で実装。ACF API は使わない。
>
> ### 2. `acf-json/` を直接編集しない
> ユーザーが管理画面で編集している。直接書き換えるとユーザーの変更を上書きする。
>
> ### 3. 既存違反箇所
> - `inc/acf-options-pickup.php` — レガシー、新規はこれを真似しない
>
> 詳細: `CLAUDE.local.md` / `~/.claude/projects/-Users-okanoyusuke/memory/feedback_sichikenchou_no_acf_php.md` / `project_sichikenchou_acf_sync.md`

## プロジェクト概要

静岡七間町商店街の公式サイト WordPress テーマ。
デザイン参照: https://shichikancho-yx4urq24.manus.space/

## ディレクトリ構成

```
sichikenchou/
├── assets/
│   ├── scss/        Dart Sass ソース
│   │   ├── _variables.scss
│   │   ├── _functions.scss
│   │   ├── _mixin.scss
│   │   ├── _base.scss
│   │   ├── config.scss      (_variables + _functions + _mixin を @forward)
│   │   ├── layout/          l- クラス
│   │   ├── object/
│   │   │   └── component/   c- クラス
│   │   └── pages/           p- クラス
│   ├── css/main.css         コンパイル済み（直接編集しない）
│   ├── js/main.js
│   └── images/
│       ├── logo/logo.svg / logo-white.svg
│       ├── top/             ヒーロー・ページ固有画像
│       ├── common/          共通画像（観光マップ等）
│       ├── gallery/         ギャラリー画像
│       └── sponsor/         スポンサー・メディアロゴ
├── inc/
│   ├── constants.php        CPT / タクソノミー定数
│   ├── cpt.php              カスタム投稿タイプ登録
│   ├── enqueue.php          CSS/JS 読み込み（filemtime キャッシュバスト）
│   └── helper.php           sc_field() / sc_thumbnail_url() 等
├── front-page.php           トップページテンプレート
├── header.php
├── footer.php
└── functions.php
```

## CSS コンパイル

```bash
npx sass assets/scss/main.scss assets/css/main.css --style expanded --no-source-map
```

ファイル変更後は必ず再コンパイルする。enqueue.php が `filemtime()` でバージョンを自動更新する。

## 命名規則（FLOCSS）

| プレフィックス | 用途                                          |
| -------------- | --------------------------------------------- |
| `l-`           | レイアウト（header, footer, container）       |
| `c-`           | 再利用コンポーネント（card, button, heading） |
| `p-`           | ページ固有（p-home-_, p-about-_ 等）          |
| `is-`          | 状態変化                                      |
| `js-`          | JS フック（スタイルなし）                     |

## CPT 定数（inc/constants.php）

| 定数         | スラッグ      |
| ------------ | ------------- |
| CPT_SHOP     | shop          |
| CPT_EVENT    | event         |
| CPT_COLUMN   | column        |
| CPT_NEWS     | news          |
| CPT_GALLERY  | gallery_photo |
| CPT_RESIDENT | resident      |
| TAX_SHOP_CAT | shop_category |
| TAX_AREA     | area          |

## コーディングルール

- **PC ファースト**レスポンシブ。ブレイクポイント `$bp-sp: 768px`
- サイズ単位は `rem()` 関数（`math.div` で計算）
- 画像の PC/SP 切り替えは `<picture>` 要素を使用
- 装飾画像（`alt=""`）には `aria-hidden="true"` を付ける
- `<img>` タグに class 属性は付けない（親要素でスタイル制御）
- `<div>` 閉じタグにはコメントを入れる（`<!-- /.block-name -->`）
- W3C バリデーション準拠
- **HTMLタグに `style=""` を書かない**。スタイルはすべてSCSSのクラスで管理する

## SCSS記述形式

**ネスト形式**（BEMの `__element` と `--modifier` は親ブロック内に `&__` / `&--` でネスト）

```scss
// ✅ 正しい書き方
.p-home-gallery {
  &__grid {
    display: grid;
  }
  &__item {
    aspect-ratio: 1 / 1;
    img { ... }
    &:hover { ... }
  }
  &__more {
    text-align: center;
    &-item {
      ...
    }
  }
}

// ❌ フラットな書き方は使わない
.p-home-gallery__grid { ... }
.p-home-gallery__item { ... }
```

## ロゴ

SVG ファイルを `<img>` タグで読み込む。

```php
<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo/logo.svg' ); ?>"
     alt="SHICHIKANCHO" width="200" height="35" loading="eager">
```

## デザイン参照

Manus プロトタイプ（https://shichikancho-yx4urq24.manus.space/）が常に最新デザイン。
実装差分はこのサイトと WordPress ローカル（http://sitikentxhou.local/）を比較して確認する。

## ヘルパー関数

```php
sc_field( 'field_name' )              // ACF フィールド取得（安全）
sc_thumbnail_url( $id, 'size' )       // サムネイル URL（なければプレースホルダー）
sc_date_jp( $date_str )               // 日付フォーマット
schema_local_business()               // JSON-LD 出力（フロントページ）
```
