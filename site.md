# 七間町（しちけんちょう）公式サイト ── WordPress再構築用 サイトマップ・サイト構成書

**作成日:** 2026年4月29日
**対象プロジェクト:** 七間町商店街 公式Webサイト
**技術スタック:** WordPress / PHP / HTML / SCSS（FLOCSS） / JavaScript / ACF PRO / Contact Form 7
**テーマ:** オリジナルテーマ（Elementor不使用）

---

## 1. サイト概要

七間町（しちけんちょう）は静岡市葵区に位置する歴史ある商店街エリアである。本サイトは「町をめぐる」をコンセプトに、観光客・住民・事業者の三者に向けた地域ポータルサイトとして機能する。単なる観光案内ではなく、町の時間・記憶・人・風景をたどる回遊体験を提供し、「和紙の記憶」というデザインコンセプトのもと、静岡の自然と文化を色彩で表現する。

---

## 2. サイトマップ（全ページ一覧）

### 2.1 メインナビゲーション

| No. | ページ名 | URL | WPテンプレート | 投稿タイプ | メモ |
|-----|---------|-----|--------------|-----------|------|
| 1 | ホーム | `/` | `front-page.php` | 固定ページ | FAQ構造化データ・カウントダウン |
| 2 | 観光情報 | `/visit/` | `page-visit.php` | 固定ページ | 観光マップ |
| 3 | 商店街のお店（一覧） | `/shops/` | `archive-shop.php` | カスタム投稿 | 販売・飲食・サービス・医療 |
| 4 | 商店街のお店（個別） | `/shops/{slug}/` | `single-shop.php` | カスタム投稿 | |
| 5 | アクセス | `/access/` | `page-access.php` | 固定ページ | アクセス動画・Googleマップ |
| 6 | 町の紹介（七間町について） | `/about/` | `page-about.php` | 固定ページ | 地域の歴史・名前の由来・地図 |
| 7 | 町の紹介（下層ページ） | `/about/{slug}/` | `page.php` | 固定ページ | |
| 8 | 映画の町 | `/cinema-town/` | `page-cinema-town.php` | 固定ページ | |
| 9 | 映画の町（下層ページ） | `/cinema-town/{slug}/` | `page.php` | 固定ページ | |
| 10 | 町をめぐる | `/explore/` | `page-explore.php` | 固定ページ | まち歩き・回遊 |
| 11 | 町に住む | `/living/` | `page-living.php` | 固定ページ | ゴミ/リサイクル・共通マナー |
| 12 | 町で学ぶ | `/learning/` | `page-learning.php` | 固定ページ | 子育て・学校・塾・習い事 |
| 13 | 町で学ぶ（下層ページ） | `/learning/{slug}/` | `page.php` | 固定ページ | |
| 14 | 町で働く | `/working/` | `page-working.php` | 固定ページ | 求人情報・シェアオフィス |
| 15 | 町で働く（求人個別） | `/working/{slug}/` | `single-job.php` | カスタム投稿 | 求人情報用 |
| 16 | イベント（一覧） | `/events/` | `archive-event.php` | カスタム投稿 | |
| 17 | イベント（個別） | `/events/{slug}/` | `single-event.php` | カスタム投稿 | |
| 18 | 町のギャラリー | `/gallery/` | `page-gallery.php` | 固定ページ | 写真カスタムフィールド |
| 19 | 町のギャラリー（投稿個別・仮） | `/gallery/{slug}/` | `single-gallery.php` | カスタム投稿（仮） | |
| 20 | くらしガイド | `/life-guide/` | `page-life-guide.php` | 固定ページ | 町内お知らせ・回覧板・PDF |
| 21 | いのちを守る | `/safety/` | `page-safety.php` | 固定ページ | 病院・防災・ハザードマップ・防犯 |
| 22 | お隣さんの話（一覧） | `/stories/` | `archive-stories.php` | カスタム投稿 | コラムと一元管理 |
| 23 | お隣さんの話（個別） | `/stories/{slug}/` | `single-stories.php` | カスタム投稿 | |

### 2.2 インフォメーション・コラム

| No. | ページ名 | URL | WPテンプレート | メモ |
|-----|---------|-----|--------------|------|
| 24 | インフォメーション（一覧） | `/news/` | `archive-news.php` | ニュース的役割・アイキャッチ無し |
| 25 | インフォメーション（個別） | `/news/{slug}/` | `single-news.php` | |
| 26 | 七ぶらコラム（一覧） | `/column/` | `archive-column.php` | 記事方式・アイキャッチあり・学生執筆 |
| 27 | 七ぶらコラム（個別） | `/column/{slug}/` | `single-column.php` | |

### 2.3 フッターページ

| No. | ページ名 | URL | WPテンプレート |
|-----|---------|-----|--------------|
| 28 | スポンサー募集 | `/sponsorship/` | `page-sponsorship.php` |
| 29 | 関連リンク | `/links/` | `page-links.php` |
| 30 | お問い合わせ | `/contact/` | `page-contact.php` |
| 31 | プライバシーポリシー | `/privacy/` | `page-privacy.php` |
| 32 | ご利用案内 | `/guide/` | `page-guide.php` |
| 33 | 運営会社 | `/organization/` | `page-organization.php` |

### 2.4 関連エリア（観光マップ用）

駿府城 / 富士山の見える場所 / お茶マップ / 人宿町 / 浅間通り / 鷹匠 / 呉服町 / 青葉通り

---

## 3. カスタム投稿タイプ設計

### 3.1 投稿タイプ一覧

| 投稿タイプ | スラッグ | アイコン | 説明 |
|-----------|---------|---------|------|
| お店 | `shop` | dashicons-store | 商店街の店舗情報 |
| イベント | `event` | dashicons-calendar-alt | イベント・催事情報 |
| スポット | `spot` | dashicons-location | 観光スポット・名所 |
| コラム | `column` | dashicons-edit | 七ぶらコラム記事 |
| お隣さんの話 | `resident` | dashicons-groups | 住民インタビュー |
| お知らせ | `news` | dashicons-megaphone | インフォメーション |
| ギャラリー写真 | `gallery_photo` | dashicons-camera | ギャラリー用写真 |
| 学ぶ施設 | `learn_facility` | dashicons-welcome-learn-more | 学習・体験施設 |
| 求人情報 | `job` | dashicons-businessman | 求人・採用情報 |
| コワーキング | `coworking` | dashicons-building | コワーキングスペース |
| 空き物件 | `property` | dashicons-admin-home | テナント・空き物件 |

### 3.2 カスタムタクソノミー一覧

| タクソノミー | スラッグ | 紐付け投稿タイプ | ターム例 |
|------------|---------|----------------|---------|
| 店舗カテゴリー | `shop_category` | shop | 食べる、買う、遊ぶ、泊まる、学ぶ、その他 |
| エリア | `area` | shop, spot, event | 七間町通り、人宿町、呉服町、鷹匠 |
| イベントカテゴリー | `event_category` | event | 祭り、マルシェ、ワークショップ、映画、季節行事 |
| スポットタイプ | `spot_type` | spot | 歴史、自然、文化、グルメ、体験 |
| コラムカテゴリー | `column_category` | column | 暮らし、食、文化、人、季節 |
| ギャラリーカテゴリー | `gallery_category` | gallery_photo | 風景、建物、人、食、イベント、季節 |
| 学ぶカテゴリー | `learn_category` | learn_facility | 文化・歴史体験、塾・学習塾、習い事・教室、資格・スキルアップ |
| 業種カテゴリー | `job_industry` | job | 飲食、小売、サービス、IT、教育 |
| 物件タイプ | `property_type` | property | 店舗、オフィス、住居兼店舗 |

---

## 4. ACFフィールドグループ設計

### 4.1 トップページ（front-page.php）

**フィールドグループ名:** `fg_home`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `hero_video` | ファイル（動画） | ヒーロー動画 |
| `hero_image` | 画像 | ヒーロー画像（フォールバック） |
| `hero_caption` | テキスト | ヒーロー動画のキャプション |
| `category_nav` | リピーター | カテゴリーナビゲーション |
| ├ `icon` | セレクト | アイコン種別 |
| ├ `label` | テキスト | ラベル |
| └ `link` | ページリンク | リンク先ページ |
| `upcoming_event` | 投稿オブジェクト | 直近のイベント（event投稿タイプから選択） |
| `featured_shops` | リレーションシップ | ピックアップ店舗（shop投稿タイプから複数選択） |
| `column_articles` | リレーションシップ | ピックアップコラム（column投稿タイプから複数選択） |

### 4.2 お店（shop投稿タイプ）

**フィールドグループ名:** `fg_shop`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `shop_name_kana` | テキスト | 店名ふりがな |
| `shop_catchphrase` | テキスト | キャッチフレーズ |
| `shop_description` | テキストエリア | 店舗紹介文 |
| `shop_main_image` | 画像 | メイン画像 |
| `shop_gallery` | ギャラリー | 店舗写真ギャラリー |
| `shop_address` | テキスト | 住所 |
| `shop_phone` | テキスト | 電話番号 |
| `shop_hours` | テキストエリア | 営業時間 |
| `shop_closed` | テキスト | 定休日 |
| `shop_website` | URL | 公式サイト |
| `shop_instagram` | URL | Instagram |
| `shop_map_lat` | 数値 | 緯度 |
| `shop_map_lng` | 数値 | 経度 |
| `shop_features` | チェックボックス | 特徴タグ（テイクアウト可、駐車場あり等） |
| `shop_menu` | リピーター | メニュー・商品 |
| ├ `item_name` | テキスト | 商品名 |
| ├ `item_price` | テキスト | 価格 |
| └ `item_image` | 画像 | 商品画像 |
| `shop_faq` | リピーター | よくある質問 |
| ├ `question` | テキスト | 質問 |
| └ `answer` | テキストエリア | 回答 |

### 4.3 イベント（event投稿タイプ）

**フィールドグループ名:** `fg_event`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `event_date_start` | 日付ピッカー | 開催日（開始） |
| `event_date_end` | 日付ピッカー | 開催日（終了） |
| `event_time` | テキスト | 開催時間 |
| `event_venue` | テキスト | 会場名 |
| `event_address` | テキスト | 会場住所 |
| `event_fee` | テキスト | 参加費 |
| `event_capacity` | テキスト | 定員 |
| `event_description` | WYSIWYG | イベント詳細 |
| `event_main_image` | 画像 | メイン画像 |
| `event_gallery` | ギャラリー | イベント写真 |
| `event_organizer` | テキスト | 主催者 |
| `event_contact` | テキストエリア | お問い合わせ先 |
| `event_external_url` | URL | 外部申込リンク |
| `event_map_lat` | 数値 | 緯度 |
| `event_map_lng` | 数値 | 経度 |
| `event_faq` | リピーター | よくある質問 |
| ├ `question` | テキスト | 質問 |
| └ `answer` | テキストエリア | 回答 |

### 4.4 スポット（spot投稿タイプ）

**フィールドグループ名:** `fg_spot`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `spot_description` | テキストエリア | スポット紹介文 |
| `spot_main_image` | 画像 | メイン画像 |
| `spot_gallery` | ギャラリー | スポット写真 |
| `spot_address` | テキスト | 住所 |
| `spot_hours` | テキスト | 営業時間・開放時間 |
| `spot_fee` | テキスト | 入場料 |
| `spot_map_lat` | 数値 | 緯度 |
| `spot_map_lng` | 数値 | 経度 |
| `spot_duration` | テキスト | 所要時間目安 |
| `spot_season` | チェックボックス | おすすめ季節 |
| `spot_related_spots` | リレーションシップ | 関連スポット |

### 4.5 コラム（column投稿タイプ）

**フィールドグループ名:** `fg_column`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `column_author_name` | テキスト | 著者名 |
| `column_author_image` | 画像 | 著者写真 |
| `column_author_bio` | テキストエリア | 著者プロフィール |
| `column_main_image` | 画像 | アイキャッチ画像 |
| `column_excerpt` | テキストエリア | 抜粋文 |
| `column_read_time` | 数値 | 読了時間（分） |

### 4.6 お隣さんの話（resident投稿タイプ）

**フィールドグループ名:** `fg_resident`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `resident_name` | テキスト | 名前（表示名） |
| `resident_age` | テキスト | 年代 |
| `resident_occupation` | テキスト | 職業 |
| `resident_years` | テキスト | 居住年数 |
| `resident_portrait` | 画像 | ポートレート写真 |
| `resident_quote` | テキスト | 一言コメント |
| `resident_story` | WYSIWYG | インタビュー本文 |
| `resident_favorite_spot` | リレーションシップ | お気に入りスポット |

### 4.7 学ぶ施設（learn_facility投稿タイプ）

**フィールドグループ名:** `fg_learn_facility`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `facility_name` | テキスト | 施設名 |
| `facility_type` | テキスト | 種別（塾、教室等） |
| `facility_description` | テキストエリア | 施設紹介文 |
| `facility_main_image` | 画像 | メイン画像 |
| `facility_address` | テキスト | 住所 |
| `facility_phone` | テキスト | 電話番号 |
| `facility_hours` | テキスト | 営業時間 |
| `facility_fee` | テキスト | 料金目安 |
| `facility_target` | テキスト | 対象者 |
| `facility_website` | URL | 公式サイト |
| `facility_features` | チェックボックス | 特徴タグ |
| `facility_faq` | リピーター | よくある質問 |
| ├ `question` | テキスト | 質問 |
| └ `answer` | テキストエリア | 回答 |

### 4.8 求人情報（job投稿タイプ）

**フィールドグループ名:** `fg_job`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `job_company` | テキスト | 会社名・店舗名 |
| `job_position` | テキスト | 募集職種 |
| `job_type` | セレクト | 雇用形態（正社員、パート、アルバイト等） |
| `job_salary` | テキスト | 給与 |
| `job_hours` | テキスト | 勤務時間 |
| `job_location` | テキスト | 勤務地 |
| `job_description` | WYSIWYG | 仕事内容 |
| `job_requirements` | テキストエリア | 応募条件 |
| `job_benefits` | テキストエリア | 待遇・福利厚生 |
| `job_contact` | テキストエリア | 応募方法・連絡先 |
| `job_deadline` | 日付ピッカー | 募集期限 |
| `job_is_active` | 真偽値 | 募集中フラグ |

### 4.9 コワーキング（coworking投稿タイプ）

**フィールドグループ名:** `fg_coworking`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `cw_name` | テキスト | スペース名 |
| `cw_description` | テキストエリア | 紹介文 |
| `cw_main_image` | 画像 | メイン画像 |
| `cw_gallery` | ギャラリー | 写真ギャラリー |
| `cw_address` | テキスト | 住所 |
| `cw_hours` | テキスト | 営業時間 |
| `cw_pricing` | リピーター | 料金プラン |
| ├ `plan_name` | テキスト | プラン名 |
| └ `plan_price` | テキスト | 料金 |
| `cw_amenities` | チェックボックス | 設備（Wi-Fi、電源、会議室等） |
| `cw_capacity` | テキスト | 席数 |
| `cw_website` | URL | 公式サイト |
| `cw_phone` | テキスト | 電話番号 |

### 4.10 空き物件（property投稿タイプ）

**フィールドグループ名:** `fg_property`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `prop_name` | テキスト | 物件名 |
| `prop_address` | テキスト | 所在地 |
| `prop_rent` | テキスト | 賃料 |
| `prop_area` | テキスト | 面積 |
| `prop_floor` | テキスト | 階数 |
| `prop_description` | テキストエリア | 物件紹介文 |
| `prop_main_image` | 画像 | メイン画像 |
| `prop_gallery` | ギャラリー | 物件写真 |
| `prop_features` | チェックボックス | 設備・条件 |
| `prop_contact` | テキストエリア | お問い合わせ先 |
| `prop_is_available` | 真偽値 | 空き状況フラグ |

### 4.11 ギャラリー写真（gallery_photo投稿タイプ）

**フィールドグループ名:** `fg_gallery_photo`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `photo_image` | 画像 | 写真 |
| `photo_caption` | テキスト | キャプション |
| `photo_photographer` | テキスト | 撮影者名 |
| `photo_date` | 日付ピッカー | 撮影日 |
| `photo_location` | テキスト | 撮影場所 |
| `photo_is_featured` | 真偽値 | ピックアップフラグ |

### 4.12 くらしガイド（page-guide.php）

**フィールドグループ名:** `fg_guide`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `guide_public_facilities` | リピーター | 公共施設一覧 |
| ├ `facility_name` | テキスト | 施設名 |
| ├ `facility_address` | テキスト | 住所 |
| ├ `facility_phone` | テキスト | 電話番号 |
| ├ `facility_hours` | テキスト | 開館時間 |
| └ `facility_url` | URL | 公式サイト |
| `guide_medical` | リピーター | 医療機関一覧 |
| ├ `medical_name` | テキスト | 医療機関名 |
| ├ `medical_type` | セレクト | 種別（内科、歯科等） |
| ├ `medical_address` | テキスト | 住所 |
| ├ `medical_phone` | テキスト | 電話番号 |
| └ `medical_hours` | テキスト | 診療時間 |
| `guide_garbage` | リピーター | ごみの出し方 |
| ├ `garbage_type` | テキスト | ごみの種類 |
| ├ `garbage_day` | テキスト | 収集曜日 |
| ├ `garbage_color` | カラーピッカー | 袋の色 |
| └ `garbage_note` | テキストエリア | 注意事項 |
| `guide_rules` | リピーター | 生活ルール |
| ├ `rule_title` | テキスト | ルール名 |
| └ `rule_content` | テキストエリア | 内容 |
| `guide_external_links` | リピーター | 外部リンク集 |
| ├ `link_name` | テキスト | リンク名 |
| ├ `link_url` | URL | URL |
| └ `link_description` | テキスト | 説明 |

### 4.13 いのちを守る（page-safety.php）

**フィールドグループ名:** `fg_safety`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `safety_emergency_contacts` | リピーター | 緊急連絡先 |
| ├ `contact_name` | テキスト | 機関名 |
| ├ `contact_phone` | テキスト | 電話番号 |
| └ `contact_note` | テキスト | 備考 |
| `safety_shelters` | リピーター | 避難場所一覧 |
| ├ `shelter_name` | テキスト | 避難場所名 |
| ├ `shelter_address` | テキスト | 住所 |
| ├ `shelter_type` | セレクト | 種別（広域避難場所、一時避難場所等） |
| ├ `shelter_capacity` | テキスト | 収容人数 |
| └ `shelter_note` | テキストエリア | 備考 |
| `safety_earthquake` | グループ | 地震・津波対策 |
| ├ `eq_description` | WYSIWYG | 解説文 |
| └ `eq_links` | リピーター | 関連リンク |
| `safety_fuji` | グループ | 富士山噴火対策 |
| ├ `fuji_description` | WYSIWYG | 解説文 |
| └ `fuji_links` | リピーター | 関連リンク |
| `safety_preparedness` | リピーター | 日頃の備え |
| ├ `prep_title` | テキスト | 項目名 |
| └ `prep_content` | テキストエリア | 内容 |
| `safety_official_links` | リピーター | 公式情報リンク |
| ├ `official_name` | テキスト | 機関名 |
| ├ `official_url` | URL | URL |
| └ `official_description` | テキスト | 説明 |

### 4.14 町の紹介（page-about.php）

**フィールドグループ名:** `fg_about`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `about_hero_image` | 画像 | ヒーロー画像 |
| `about_intro` | WYSIWYG | 紹介文 |
| `about_timeline` | リピーター | 歴史年表 |
| ├ `year` | テキスト | 年代 |
| ├ `title` | テキスト | 出来事 |
| └ `description` | テキストエリア | 詳細 |
| `about_stats` | リピーター | 数字で見る七間町 |
| ├ `stat_number` | テキスト | 数値 |
| ├ `stat_unit` | テキスト | 単位 |
| └ `stat_label` | テキスト | ラベル |
| `about_sections` | リピーター | セクション（自由構成） |
| ├ `section_title` | テキスト | セクション見出し |
| ├ `section_content` | WYSIWYG | セクション本文 |
| └ `section_image` | 画像 | セクション画像 |

### 4.15 映画の町（page-cinema.php）

**フィールドグループ名:** `fg_cinema`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `cinema_hero_image` | 画像 | ヒーロー画像 |
| `cinema_intro` | WYSIWYG | 紹介文 |
| `cinema_timeline` | リピーター | 映画館の歴史年表 |
| ├ `year` | テキスト | 年代 |
| ├ `title` | テキスト | 出来事 |
| ├ `description` | テキストエリア | 詳細 |
| └ `image` | 画像 | 写真 |
| `cinema_current` | リピーター | 現在の映画関連施設 |
| ├ `name` | テキスト | 施設名 |
| ├ `description` | テキストエリア | 紹介文 |
| └ `url` | URL | 公式サイト |
| `cinema_events` | リレーションシップ | 映画関連イベント |

### 4.16 スポンサー募集（page-sponsor.php）

**フィールドグループ名:** `fg_sponsor`

| フィールド名 | フィールドタイプ | 説明 |
|-------------|--------------|------|
| `sponsor_benefits` | リピーター | スポンサーになるメリット |
| ├ `benefit_title` | テキスト | タイトル |
| └ `benefit_description` | テキストエリア | 説明 |
| `sponsor_plans` | リピーター | スポンサープラン |
| ├ `plan_name` | テキスト | プラン名 |
| ├ `plan_price` | テキスト | 価格 |
| └ `plan_features` | テキストエリア | 特典内容 |

---

## 5. 共通コンポーネント設計

### 5.1 テンプレートパーツ

| ファイル名 | 説明 |
|-----------|------|
| `header.php` | グローバルヘッダー（ロゴ、メインナビ、ハンバーガーメニュー、言語切替） |
| `footer.php` | グローバルフッター（4カラムリンク、SNSリンク、コピーライト） |
| `template-parts/breadcrumb.php` | パンくずリスト（構造化データ付き） |
| `template-parts/page-hero.php` | ページヒーロー（タイトル、サブタイトル、背景画像） |
| `template-parts/card-shop.php` | お店カード（一覧用） |
| `template-parts/card-event.php` | イベントカード（一覧用） |
| `template-parts/card-spot.php` | スポットカード（一覧用） |
| `template-parts/card-column.php` | コラムカード（一覧用） |
| `template-parts/card-resident.php` | お隣さんカード（一覧用） |
| `template-parts/faq-section.php` | FAQ表示セクション（構造化データ付き） |
| `template-parts/cta-section.php` | CTA（お問い合わせ誘導等） |
| `template-parts/related-posts.php` | 関連記事表示 |
| `template-parts/share-buttons.php` | SNSシェアボタン |
| `template-parts/tab-navigation.php` | タブ切り替えナビゲーション |

### 5.2 テーマファイル構成

```
sichikenchou/
├── style.css                    ← テーマ情報（ヘッダーのみ）
├── functions.php                ← inc/ を require するだけ
├── index.php
├── header.php
├── footer.php
├── front-page.php               ← トップページ
├── page.php                     ← 汎用固定ページ
├── single.php                   ← 汎用個別ページ
├── archive.php                  ← 汎用アーカイブ
├── search.php
├── 404.php
│
├── page-about.php               ← 町の紹介
├── page-cinema.php              ← 映画の町
├── page-walk.php                ← 町をめぐる
├── page-living.php              ← 町に住む
├── page-learn.php               ← 町で学ぶ
├── page-work.php                ← 町で働く
├── page-gallery.php             ← 町のギャラリー
├── page-guide.php               ← くらしガイド
├── page-safety.php              ← いのちを守る
├── page-tourism.php             ← 観光情報
├── page-access.php              ← アクセス
├── page-info.php                ← インフォメーション
├── page-sponsor.php             ← スポンサー募集
├── page-links.php               ← 関連リンク
├── page-contact.php             ← お問い合わせ
├── page-privacy.php             ← プライバシーポリシー
├── page-terms.php               ← 利用規約
├── page-company.php             ← 運営会社
│
├── archive-shop.php
├── single-shop.php
├── archive-event.php
├── single-event.php
├── archive-spot.php
├── single-spot.php
├── archive-column.php
├── single-column.php
├── archive-resident.php
├── single-resident.php
│
├── template-parts/
│   ├── components/              ← 汎用UIパーツ（c- 対応）
│   │   ├── breadcrumbs.php
│   │   ├── svg-sprite.php       ← インラインSVG一元管理
│   │   ├── mobile-cta-bar.php
│   │   └── post-thumbnail.php
│   ├── shop/                    ← お店関連パーツ
│   │   ├── card.php
│   │   └── sections/
│   │       ├── hero.php
│   │       ├── info.php
│   │       ├── menu.php
│   │       └── faq.php
│   ├── event/
│   │   ├── card.php
│   │   └── sections/
│   ├── spot/
│   │   └── card.php
│   ├── column/
│   │   └── card.php
│   ├── resident/
│   │   └── card.php
│   ├── content-card.php
│   └── content-none.php
│
├── inc/
│   ├── constants.php            ← 投稿タイプ名等の定数
│   ├── helpers.php              ← 汎用ヘルパー関数
│   ├── enqueue.php              ← CSS/JS読み込み
│   ├── menu-locations.php       ← ナビゲーションメニュー登録
│   ├── breadcrumbs.php          ← パンくず生成
│   ├── schema.php               ← 構造化データ（JSON-LD）
│   ├── seo.php                  ← メタタグ・OGP
│   ├── seo-llmo.php             ← llms.txt・AIクローラー対応
│   ├── acf-options.php          ← ACFオプションページ
│   ├── disable-comments.php     ← コメント機能無効化
│   ├── walker-nav.php           ← カスタムナビゲーションウォーカー
│   ├── cpt-shop.php             ← お店
│   ├── cpt-event.php            ← イベント
│   ├── cpt-spot.php             ← スポット
│   ├── cpt-column.php           ← コラム
│   ├── cpt-resident.php         ← お隣さんの話
│   ├── cpt-gallery.php          ← ギャラリー写真
│   ├── cpt-learn.php            ← 学ぶ施設
│   ├── cpt-job.php              ← 求人情報
│   ├── cpt-coworking.php        ← コワーキング
│   ├── cpt-property.php         ← 空き物件
│   └── cpt-news.php             ← お知らせ
│
├── acf-json/                    ← ACF Local JSON（フィールド定義の自動同期）
│
├── assets/
│   ├── scss/
│   │   ├── main.scss            ← エントリーポイント
│   │   ├── editor.scss          ← ブロックエディター用
│   │   ├── _variables.scss
│   │   ├── _mixin.scss
│   │   ├── _functions.scss
│   │   ├── _base.scss
│   │   ├── foundation/
│   │   │   └── _reset.scss
│   │   ├── layout/
│   │   │   ├── _header.scss
│   │   │   ├── _footer.scss
│   │   │   └── _container.scss
│   │   ├── object/
│   │   │   └── component/
│   │   │       ├── _breadcrumbs.scss
│   │   │       ├── _tab.scss
│   │   │       ├── _accordion.scss
│   │   │       ├── _card.scss
│   │   │       ├── _button.scss
│   │   │       └── _heading.scss
│   │   └── pages/
│   │       ├── _front-page.scss
│   │       ├── _about.scss
│   │       ├── _cinema.scss
│   │       ├── _walk.scss
│   │       ├── _gallery.scss
│   │       ├── _guide.scss
│   │       ├── _safety.scss
│   │       ├── _archive-shop.scss
│   │       ├── _single-shop.scss
│   │       ├── _archive-event.scss
│   │       ├── _single-event.scss
│   │       ├── _single-column.scss
│   │       └── _404.scss
│   ├── css/                     ← SCSSコンパイル済み出力先
│   ├── js/
│   │   ├── main.js              ← ハンバーガー・スクロール等共通JS
│   │   ├── page-walk.js         ← 町をめぐる（タブ・フィルター）
│   │   ├── page-gallery.js      ← ギャラリー（ライトボックス・フィルター）
│   │   ├── page-guide.js        ← くらしガイド（タブ）
│   │   ├── page-safety.js       ← いのちを守る（タブ）
│   │   ├── page-work.js         ← 町で働く（タブ）
│   │   ├── page-learn.js        ← 町で学ぶ（タブ）
│   │   ├── page-contact.js      ← お問い合わせフォーム
│   │   └── single-shop.js       ← お店個別（マップ等）
│   ├── images/
│   │   ├── common/              ← OGP・ロゴ・ノーイメージ
│   │   ├── logo/
│   │   ├── top/
│   │   └── icons/
│   └── vendor/                  ← サードパーティライブラリ
│
├── llms.txt                     ← LLM向けサイト情報ファイル
├── CLAUDE.local.md              ← Claude向けローカルルール（コメント規約・HTML規約等）
├── .vscode/
│   └── settings.json            ← Live Sass Compilerのコンパイル設定
├── .gitignore
└── .htmlvalidate.json
```

**SCSSコンパイル:** VS Code拡張「Live Sass Compiler」を使用。`assets/scss/` → `assets/css/` に自動コンパイル。設定は `.vscode/settings.json` で管理。`node_modules` / `package.json` は不要。

---

## 5.3 SCSS コーディングルール

- **単位は `rem()` 関数で統一**。`_functions.scss` に `@function rem($px) { @return $px / 16px * 1rem; }` を定義し、すべてのサイズ指定に使う。pxの直書きを禁止（border幅・line-height等の例外は除く）。
- **PC-first**。ベースがPC、スマートフォンは `@media (max-width: 768px)` で上書き。
- ブレイクポイントは `_variables.scss` に `$bp-sp: 768px;` として管理し、直書き禁止。

---

## 5.4 ACF / CPT 管理ルール

- **ACF フィールドを PHP でハードコードしない**。すべてのフィールドグループは管理画面（ACF PRO の GUI）で定義し、`acf-json/` ディレクトリへの Local JSON 自動同期で管理する。`register_field_group()` や `acf_add_local_field_group()` のPHP直書きは禁止。
- **カスタム投稿タイプ（CPT）・タクソノミーも ACF PRO の管理画面UIで定義する**。「カスタムフィールド > 投稿タイプ」「カスタムフィールド > タクソノミー」から作成し、`acf-json/` に自動同期。`register_post_type()` / `register_taxonomy()` のPHP直書きは禁止。CPTのスラッグ参照は `inc/constants.php` の定数を使う。
- `acf-json/` はバージョン管理（git）に含める。

---

## 6. 各ページ詳細セクション構成

### 6.1 トップページ（/）

1. **ヒーローセクション**: 左=縦長の町並み写真、右=「七間町」円形テキスト + 「SHIZUOKA」サブテキスト。桜色（#F2D5CE）背景。SVGマップは使用しない。
2. **カテゴリーナビゲーション**: 9カテゴリーのアイコン付き円形リンクを1行に並べる（モバイルは横スクロール）
3. **インフォメーション＆イベント（2カラム）**: 左=インフォメーション（日付＋タイトルのリスト形式、最新3件）、右=直近のイベント（画像＋日付＋タイトル＋テキストのカード）
4. **お隣さん写真ロール**: 住民・店主の円形プロフィール写真を横一列に並べる
5. **七間町商店街のお店（桜色背景）**: 左=店舗写真コラージュ（3枚）、右=見出し＋説明文＋「お店を見る」ボタン
6. **観光マップ**: 町並みイラスト／鳥瞰写真を全幅またはカード形式で表示
7. **町の紹介（2段構成）**:
   - 左テキスト＋右画像: 「静岡市中心部にある"文化×日常"が共存する街。」＋説明文＋CTAボタン
   - 右テキスト＋左画像: 「あなたのすぐ近くにある、惠かの物語。」＋説明文＋CTAボタン
8. **七間町の風景（ギャラリープレビュー）**: タブ切り替え（七間町の風景 / 七間町の建物 / 七間町の食べ物）＋写真グリッド＋「ギャラリーを見る」ボタン
9. **七間町へのアクセス**: 左=写真、右=「七間町へのアクセス」見出し＋新幹線・静岡駅からのアクセス情報＋「詳しく見る」ボタン
10. **七ぶらコラム**: 見出し＋「コラムを読む」リンク＋コラムカード3件（画像＋タイトル＋テキスト）
11. **スポンサー**: ロゴ一覧（横並び）
12. **メディアパートナー**: ロゴ一覧（横並び）

### 6.2 町の紹介（/about/）

1. **ヒーローセクション**: ページタイトル + 背景画像
2. **サイドバーナビゲーション**: ページ内リンク（sticky）
3. **七間町とは**: 紹介文 + 写真
4. **歴史年表**: タイムライン形式の歴史紹介
5. **数字で見る七間町**: 統計データのビジュアル表示
6. **町の魅力セクション**: 複数セクションの自由構成

### 6.3 映画の町（/cinema/）

1. **ヒーローセクション**: フィルムリール風デザイン
2. **映画の町の歴史**: タイムライン形式
3. **現在の映画関連施設**: 施設カード一覧
4. **映画関連イベント**: 関連イベントの表示

### 6.4 町をめぐる（/walk/）

1. **ヒーローセクション**: ページタイトル + 背景画像
2. **検索タブ**: おすすめコース / スポット検索 / モデルコース の3タブ切り替え
3. **おすすめコース**: コースカード一覧（所要時間、スポット数、難易度表示）
4. **スポット検索**: カテゴリーフィルター + スポットカード一覧
5. **月間人気ランキング**: 人気スポットのランキング表示

### 6.5 町に住む（/living/）

1. **ヒーローセクション**: ページタイトル + 背景画像
2. **住みやすさの特徴**: 特徴カード一覧
3. **お隣さんの話**: 住民インタビューのピックアップ
4. **生活情報リンク**: くらしガイドへの誘導

### 6.6 町で学ぶ（/learn/）

1. **ヒーローセクション**: ページタイトル + 背景画像
2. **カテゴリータブ**: 文化・歴史体験 / 塾・学習塾 / 習い事・教室 / 資格・スキルアップ の4タブ切り替え
3. **施設カード一覧**: 各カテゴリーの施設をカード形式で表示（画像、名前、種別、対象者、料金、特徴タグ）
4. **FAQ**: よくある質問（各施設・カテゴリー共通）

### 6.7 町で働く（/work/）

1. **ヒーローセクション**: ページタイトル + 背景画像
2. **4タブナビゲーション**: CAREER（求人） / SPACE（拠点） / PLAYERS（ディレクトリ） / OPPORTUNITY（物件）
3. **CAREER**: 求人カード一覧（職種、雇用形態、給与、勤務地）
4. **SPACE**: コワーキングスペースカード一覧
5. **PLAYERS**: 事業者ディレクトリ
6. **OPPORTUNITY**: 空き物件カード一覧

### 6.8 町のギャラリー（/gallery/）

1. **ヒーローセクション**: ページタイトル + 背景画像
2. **カテゴリーフィルター**: すべて / 風景 / 建物 / 人 / 食 / イベント / 季節 のフィルターボタン
3. **マソンリーレイアウト**: 写真グリッド（ライトボックス付き）
4. **写真募集セクション**: 「皆さんの撮影した写真をぜひ掲載しませんか？」の募集案内

### 6.9 くらしガイド（/guide/）

1. **ヒーローセクション**: ページタイトル + 背景画像
2. **5タブナビゲーション**: 公共施設 / 医療機関 / ごみの出し方 / 生活ルール / 外部リンク
3. **公共施設**: 施設カード一覧（名前、住所、電話、開館時間）
4. **医療機関**: 医療機関カード一覧（種別フィルター付き）
5. **ごみの出し方**: ごみ種別ごとの収集日・注意事項テーブル
6. **生活ルール**: ルール一覧（アコーディオン形式）
7. **外部リンク**: 静岡市役所等の公式リンク集

### 6.10 いのちを守る（/safety/）

1. **ヒーローセクション**: 南海トラフ地震警告バナー + ページタイトル
2. **6タブナビゲーション**: 緊急連絡先 / 避難場所 / 地震・津波 / 富士山噴火 / 日頃の備え / 公式情報
3. **緊急連絡先**: 連絡先テーブル（110、119、静岡市災害対策本部等）
4. **避難場所**: 避難場所カード一覧（種別、住所、収容人数）
5. **地震・津波**: 南海トラフ地震の解説、行動マニュアル、公式リンク
6. **富士山噴火**: 噴火シナリオ、ハザードマップリンク、行動指針
7. **日頃の備え**: 備蓄品チェックリスト、家族の約束事
8. **公式情報**: 県庁・市役所・気象庁等の公式リンク集

---

## 7. ナビゲーション設計

Manusプロトタイプサイト（`https://shichikancho-yx4urq24.manus.space/`）を参照。ヘッダーは**2段構成**。

### 7.1 トップナビゲーション（1段目）

観光客・外部訪問者向けの主要コンテンツへのリンク。

| 項目 | リンク先 |
|------|---------|
| 観光情報 | `/visit/` |
| 商店街のお店 | `/shops/` |
| イベント | `/events/` |
| 七ぶらコラム | `/column/` |
| アクセス | `/access/` |
| 言語切替（Japanese ▾） | ─ |

### 7.2 サブナビゲーション（2段目）

地域住民・生活者向けのコンテンツへのリンク。アイコン付き。

| 項目 | アイコン | リンク先 |
|------|---------|---------|
| 町の紹介 | 家 | `/about/` |
| 映画の町 | フィルム | `/cinema-town/` |
| 町をめぐる | ピン | `/explore/` |
| 町に住む | 人物 | `/living/` |
| 町でまなぶ | 帽子 | `/learning/` |
| 町で働く | ブリーフケース | `/working/` |
| 町のギャラリー | カメラ | `/gallery/` |
| くらしガイド | 本 | `/life-guide/` |
| いのちを守る | シールド | `/safety/` |

**PC**: ヘッダー固定 2段横並び。2段目はアイコン+テキスト。  
**SP（max-width: 768px）**: ハンバーガーメニュー → ドロワー展開（Manusサイトのモバイルレイアウト参照）。1段目・2段目は縦並びにまとめる。

### 7.3 カテゴリーアイコングリッド（トップページのみ）

ヒーローセクション直下。サブナビゲーション（7.2）と同じ9項目をアイコン+テキストでグリッド表示。  
**SP**: 横4列 or 横スクロール（Manusサイト参照）。

### 7.3 フッターナビゲーション

4カラム構成：
- **七間町について**: 町の紹介、映画の町、アクセス
- **暮らし**: くらしガイド、いのちを守る、町に住む
- **楽しむ**: 町をめぐる、町のギャラリー、イベント
- **その他**: お問い合わせ、スポンサー募集、関連リンク、プライバシーポリシー、利用規約、運営会社

---

## 8. WordPress設定

### 8.1 使用プラグイン

| プラグイン | 用途 |
|-----------|------|
| **Advanced Custom Fields PRO** | カスタムフィールド管理（`acf-json/` でLocal JSON同期） |
| ACF QuickEdit Fields | ACF フィールドの一覧画面インライン編集 |
| **Contact Form 7** | お問い合わせフォーム（`page-contact.php` に埋め込み） |
| All in One SEO | SEO管理（メタタグ・サイトマップ・OGP） |
| All-in-One WP Migration and Backup | バックアップ・移行 |
| Category Order and Taxonomy Terms Order | タクソノミーターム並び順カスタマイズ |
| Easy Table of Contents | 目次自動生成（コラム・ガイド系ページ） |
| FileBird Lite | メディアライブラリのフォルダ管理 |
| Intuitive Custom Post Order | CPT投稿の並び順ドラッグ変更 |
| Show Current Template | 開発時：現在のテンプレートファイル名表示 |
| Simple Local Avatars | ユーザーアバター画像をローカル管理 |
| SVG Support | SVGファイルのアップロード対応 |
| Yoast Duplicate Post | 投稿・固定ページの複製 |

### 8.2 パーマリンク設定

`/%postname%/` （投稿名ベース）

### 8.3 メニュー登録

- `primary`: メインナビゲーション
- `footer`: フッターナビゲーション
- `mobile`: モバイルメニュー

---

## 9. 構造化データ（JSON-LD）出力関数一覧

`inc/schema.php` に以下の関数を実装し、各テンプレートから呼び出す。詳細なJSON-LD出力例は「指示プロンプト」のF.4セクションを参照。

| 関数名 | 出力Schema | 呼び出しテンプレート |
|--------|-----------|-------------------|
| `schema_website()` | `WebSite` + `SearchAction` | 全ページ（`header.php`） |
| `schema_organization()` | `Organization` | 全ページ（`header.php`） |
| `schema_breadcrumb()` | `BreadcrumbList` | 全ページ（`header.php`） |
| `schema_local_business()` | `LocalBusiness`（商店街全体） | `front-page.php` |
| `schema_shop( $post_id )` | `LocalBusiness` サブタイプ自動切替 | `single-shop.php` |
| `schema_event( $post_id )` | `Event` サブタイプ自動切替 | `single-event.php` |
| `schema_spot( $post_id )` | `TouristAttraction` + `Place` | `single-spot.php` |
| `schema_article( $post_id )` | `Article` / `BlogPosting` + `Person` | `single-column.php`, `single-resident.php` |
| `schema_job( $post_id )` | `JobPosting` | 町で働く（求人セクション） |
| `schema_property( $post_id )` | `RealEstateListing` | 町で働く（空き物件セクション） |
| `schema_learn_facility( $post_id )` | `EducationalOrganization` 等 | 町で学ぶ（各施設） |
| `schema_medical( $data )` | `Hospital` / `Dentist` / `Physician` / `Pharmacy` | `page-guide.php` |
| `schema_emergency( $data )` | `EmergencyService` | `page-safety.php` |
| `schema_shelter( $data )` | `CivicStructure` + `GeoCoordinates` | `page-safety.php` |
| `schema_civic( $data )` | `CivicStructure` / `Library` / `Park` | `page-guide.php` |
| `schema_faq( $field_name )` | `FAQPage` | FAQ設置ページ全般 |
| `schema_howto( $field_name )` | `HowTo` | `page-guide.php`（ごみの出し方） |
| `schema_item_list( $items )` | `ItemList` | 全アーカイブ・一覧ページ |
| `schema_image_gallery()` | `ImageGallery` | `page-gallery.php`, `single-shop.php` |
| `schema_tourist_trip( $data )` | `TouristTrip` | `page-walk.php` |
| `schema_tourist_destination()` | `TouristDestination` | `page-tourism.php` |
| `schema_contact_page()` | `ContactPage` | `page-contact.php` |
| `schema_offer( $data )` | `Offer` | `page-sponsor.php`, `single-event.php` |

### 9.1 テンプレート別Schema呼び出しマップ

```php
// header.php（全ページ共通）
<?php
  schema_website();
  schema_organization();
  schema_breadcrumb();
?>

// front-page.php
<?php schema_local_business(); ?>

// single-shop.php
<?php
  schema_shop( get_the_ID() );
  schema_image_gallery();
  if ( get_field('shop_faq') ) schema_faq('shop_faq');
?>

// single-event.php
<?php
  schema_event( get_the_ID() );
  if ( get_field('event_faq') ) schema_faq('event_faq');
?>

// single-spot.php
<?php
  schema_spot( get_the_ID() );
  schema_image_gallery();
?>

// single-column.php / single-resident.php
<?php schema_article( get_the_ID() ); ?>

// page-guide.php
<?php
  schema_howto('guide_garbage_steps');
  schema_faq('guide_life_rules_faq');
  // 医療機関ループ内
  foreach ( $medical_facilities as $facility ) {
    schema_medical( $facility );
  }
  // 公共施設ループ内
  foreach ( $civic_facilities as $facility ) {
    schema_civic( $facility );
  }
?>

// page-safety.php
<?php
  schema_faq('safety_preparedness_faq');
  // 緊急連絡先ループ内
  foreach ( $emergency_contacts as $contact ) {
    schema_emergency( $contact );
  }
  // 避難場所ループ内
  foreach ( $shelters as $shelter ) {
    schema_shelter( $shelter );
  }
?>

// page-walk.php
<?php
  schema_item_list( $courses );
  foreach ( $courses as $course ) {
    schema_tourist_trip( $course );
  }
  if ( get_field('walk_faq') ) schema_faq('walk_faq');
?>

// page-gallery.php
<?php schema_image_gallery(); ?>

// page-tourism.php
<?php
  schema_tourist_destination();
  schema_item_list( $spots );
?>

// page-work.php（求人・物件セクション）
<?php
  foreach ( $jobs as $job ) {
    schema_job( $job->ID );
  }
  foreach ( $properties as $property ) {
    schema_property( $property->ID );
  }
?>

// page-learn.php
<?php
  schema_item_list( $facilities );
  foreach ( $facilities as $facility ) {
    schema_learn_facility( $facility->ID );
  }
?>

// archive-*.php（全アーカイブ）
<?php schema_item_list( $posts ); ?>

// page-contact.php
<?php schema_contact_page(); ?>

// page-sponsor.php
<?php schema_offer( get_field('sponsor_plans') ); ?>
```

---

## 10. 開発進行フェーズ

作業は以下のフェーズ順で進める。各フェーズ完了後に次へ。依存関係があるため順序を守る。

### チェックリスト凡例
- `[ ]` 未着手
- `[x]` 完了

---

### Phase 1: テーマ基盤構築

テーマとして認識され、管理画面に表示されるための最小限のファイル群。

- [ ] `style.css` — テーマヘッダーのみ（スタイルなし）
- [ ] `index.php` — 最小限のフォールバック
- [ ] `functions.php` — `inc/` を `require` するだけ
- [ ] `inc/constants.php` — CPTスラッグ・タクソノミースラッグ定数
- [ ] `inc/enqueue.php` — CSS/JSエンキュー（`main.css` のみ）
- [ ] `inc/menu-locations.php` — `primary` / `sub` / `footer` メニュー登録
- [ ] `.vscode/settings.json` — Live Sass Compiler 設定
- [ ] `.gitignore`

---

### Phase 2: SCSS基盤構築

`rem()` 関数・変数・リセット・ベーススタイル。コンパイルが通ることを確認してから進む。

- [ ] `assets/scss/_functions.scss` — `rem()` 関数定義
- [ ] `assets/scss/_variables.scss` — カラー・フォント・ブレイクポイント（`$bp-sp: 768px`）
- [ ] `assets/scss/_mixin.scss` — よく使うmixin（`sp()` メディアクエリmixinなど）
- [ ] `assets/scss/foundation/_reset.scss` — CSS Reset（modern-normalize相当）
- [ ] `assets/scss/_base.scss` — `body`・`a`・`img` 等の基本スタイル、Googleフォント読み込み
- [ ] `assets/scss/layout/_container.scss` — `l-container` 幅・余白
- [ ] `assets/scss/layout/_header.scss` — 2段ヘッダー（PC/SP）
- [ ] `assets/scss/layout/_footer.scss` — フッター
- [ ] `assets/scss/main.scss` — 全ファイルを `@use` でまとめ
- [ ] コンパイル確認: `assets/css/main.css` 生成OK

---

### Phase 3: CPT・タクソノミー登録

**ACF PRO の管理画面 UI で定義する**（PHP直書き禁止）。  
「カスタムフィールド > 投稿タイプ」「カスタムフィールド > タクソノミー」から作成 → `acf-json/` に自動同期されることを確認。

**投稿タイプ（11件）**
- [ ] `shop`（お店）— スラッグ: `shops`、アーカイブ有効
- [ ] `event`（イベント）— スラッグ: `events`、アーカイブ有効
- [ ] `column`（七ぶらコラム）— スラッグ: `column`、アーカイブ有効
- [ ] `resident`（お隣さんの話）— スラッグ: `stories`、アーカイブ有効
- [ ] `spot`（スポット）— スラッグ: `spots`、アーカイブ有効
- [ ] `gallery_photo`（ギャラリー写真）— スラッグ: `gallery-photos`
- [ ] `learn_facility`（学ぶ施設）— スラッグ: `learn-facilities`
- [ ] `job`（求人情報）— スラッグ: `jobs`、アーカイブ有効
- [ ] `coworking`（コワーキング）— スラッグ: `coworking`
- [ ] `property`（空き物件）— スラッグ: `properties`
- [ ] `news`（お知らせ）— スラッグ: `news`、アーカイブ有効

**タクソノミー（9件）**
- [ ] `shop_category`（店舗カテゴリー）← shop
- [ ] `area`（エリア）← shop, spot, event
- [ ] `event_category`（イベントカテゴリー）← event
- [ ] `spot_type`（スポットタイプ）← spot
- [ ] `column_category`（コラムカテゴリー）← column
- [ ] `gallery_category`（ギャラリーカテゴリー）← gallery_photo
- [ ] `learn_category`（学ぶカテゴリー）← learn_facility
- [ ] `job_industry`（業種カテゴリー）← job
- [ ] `property_type`（物件タイプ）← property

- [ ] `acf-json/` にJSONが生成されていることを確認

---

### Phase 4: 共通テンプレート（ヘッダー・フッター）

全ページで使われる共通パーツ。ここが固まると各ページ実装がスムーズになる。

- [ ] `header.php` — 2段ナビ（PC）＋ハンバーガー（SP）
- [ ] `footer.php` — 4カラムリンク＋SNS＋コピーライト
- [ ] `template-parts/components/breadcrumbs.php`
- [ ] `template-parts/components/svg-sprite.php` — アイコン登録
- [ ] `template-parts/components/mobile-cta-bar.php`
- [ ] `inc/breadcrumbs.php` — パンくず生成関数
- [ ] `inc/walker-nav.php` — カスタムNavWalker
- [ ] `assets/js/main.js` — ハンバーガー開閉・スクロール制御
- [ ] `assets/scss/object/component/_button.scss`
- [ ] `assets/scss/object/component/_heading.scss`
- [ ] `assets/scss/object/component/_breadcrumbs.scss`

---

### Phase 5: トップページ

最も複雑なページ。ヘッダー・フッター完成後に実施。

- [ ] `front-page.php` — 12セクション実装
- [ ] `assets/scss/pages/_front-page.scss`
- [ ] ACF フィールドグループ `fg_home` を管理画面で定義 → `acf-json/` 同期確認

---

### Phase 6: 固定ページ群（観光・お店・イベント以外）

テンプレート種別ごとにまとめて実装する。

**E（テキスト記事型）:**
- [ ] `page-about.php` + SCSS
- [ ] `page-cinema.php` + SCSS
- [ ] `page-privacy.php`
- [ ] `page-terms.php`
- [ ] `page-company.php`

**B（タブ切り替え型）:**
- [ ] `page-learn.php` + SCSS + JS
- [ ] `page-work.php` + SCSS + JS
- [ ] `page-guide.php` + SCSS + JS
- [ ] `page-safety.php` + SCSS + JS

**D（ランディング型）:**
- [ ] `page-living.php` + SCSS

**G（専用レイアウト）:**
- [ ] `page-walk.php` + SCSS + JS（フォトコンテスト・グリッド）
- [ ] `page-gallery.php` + SCSS + JS（フィルター・ライトボックス）
- [ ] `page-access.php` + SCSS + JS（タブ切り替え）
- [ ] `page-sponsor.php` + SCSS
- [ ] `page-contact.php` + SCSS（CF7埋め込み）

---

### Phase 7: 投稿タイプ アーカイブ・詳細ページ

**お店:**
- [ ] `archive-shop.php` + `template-parts/shop/card.php` + SCSS
- [ ] `single-shop.php` + `template-parts/shop/sections/` + SCSS
- [ ] ACF `fg_shop` 管理画面定義 → JSON同期

**イベント:**
- [ ] `archive-event.php` + `template-parts/event/card.php` + SCSS
- [ ] `single-event.php` + SCSS
- [ ] ACF `fg_event` 管理画面定義 → JSON同期

**コラム（七ぶらコラム）:**
- [ ] `archive-column.php` + SCSS（3カラムカードグリッド）
- [ ] `single-column.php` + SCSS（本文＋サイドバー）
- [ ] ACF `fg_column` 管理画面定義 → JSON同期

**インフォメーション:**
- [ ] `page-info.php`（固定ページ版） → `archive-news.php` + SCSS
- [ ] `single-news.php`

**お隣さんの話:**
- [ ] `archive-resident.php` + SCSS（スライダー＋カード＋サイドバー）
- [ ] `single-resident.php` + SCSS

---

### Phase 8: SEO・構造化データ・ユーティリティ

- [ ] `inc/seo.php` — メタタグ・OGP
- [ ] `inc/schema.php` — JSON-LD全関数
- [ ] `inc/seo-llmo.php` — llms.txt・AIクローラー対応
- [ ] `inc/acf-options.php` — ACFオプションページ
- [ ] `inc/disable-comments.php`
- [ ] `llms.txt`
- [ ] `404.php` + SCSS

---

### Phase 9: 品質チェック・最終調整

- [ ] W3C HTMLバリデーション（主要ページ）
- [ ] Google PageSpeed Insights（モバイル70点以上目標）
- [ ] SP実機確認（iPhone / Android）
- [ ] `search.php` 実装
- [ ] OGP・Twitterカード確認
- [ ] 構造化データ検証（Googleリッチリザルトテスト）

---

## 11. Manusプロトタイプ参照 ── ページデザインパターン一覧

**参照URL:** `https://shichikancho-yx4urq24.manus.space/`  
デザイン実装時はこのプロトタイプを正とする。各ページのデザインパターンと主要UIコンポーネントを記録。

### 10.1 テンプレート種別定義

| テンプレート種別 | 特徴 |
|---------------|------|
| **A: アーカイブ/フィルター型** | 検索バー＋フィルター＋カードグリッド（3カラム） |
| **B: タブ切り替え型** | ページタイトル帯＋リード文＋タブUI＋コンテンツ切り替え |
| **C: 記事/読み物型** | 左＝本文コンテンツ、右＝サイドバー（新着・カテゴリー） |
| **D: ランディング型** | 大キャッチコピー＋CTAボタン＋詳細セクション縦積み |
| **E: 固定ページ記事型** | パンくず＋大見出し＋リード文＋センター画像＋本文 |
| **F: リスト型（シンプル）** | タイトル帯＋日付・タグ・タイトルの行リスト。アイキャッチなし |
| **G: 特殊ページ** | ページ固有の専用レイアウト |

### 10.2 各ページのデザインパターン

| Manus URL | WP URL（本番） | ページ名 | テンプレート種別 | 主要UIコンポーネント |
|----------|--------------|---------|---------------|-----------------|
| `/` | `/` | ホーム | G | ヒーロー（左=動画/写真・右=静岡SVGマップ）＋アイコングリッド（9項目）＋インフォメーション＆イベント2カラム＋お店ピックアップ＋観光マップ＋町紹介2段＋ギャラリープレビュー＋アクセス＋コラム＋スポンサー |
| `/about` | `/about/` | 町の紹介 | E | パンくず＋ページタイトル帯＋センター大画像＋本文テキスト |
| `/tourism` | `/visit/` | 観光情報 | A | Pick up Newsバー＋「人気の散策コース」3カラムカードグリッド |
| `/shops` | `/shops/` | 商店街のお店 | A | 検索バー（店名検索）＋カテゴリーセレクト＋エリアセレクト＋支払いタグフィルター＋3カラムカードグリッド |
| `/events` | `/events/` | イベント | G | フルワイド大スライダー（終了/開催中バッジ）＋イベントカード一覧 |
| `/column` | `/column/` | 七ぶらコラム | A | 「特集コラム」3カラムカードグリッド（カテゴリー色・著者・日付・読了時間） |
| `/column/1` | `/column/{slug}/` | コラム詳細 | C | 左=大アイキャッチ＋本文、右=サイドバー（新着情報リスト） |
| `/access` | `/access/` | アクセス | G | 現在地ナビボタン（全幅）＋「東京からわずか69分」キャッチ＋電車・バスタブ切り替え＋経路カード |
| `/cinema` | `/cinema-town/` | 映画の町 | E | リード文＋左テキスト＋右画像の2カラム＋ストーリーガイドリスト（01〜） |
| `/walk` | `/explore/` | 町をめぐる | G | フォトコンテスト告知バナー（全幅・緑背景）＋入賞作品グリッド（4カラム） |
| `/living` | `/living/` | 町に住む | D | 大キャッチコピー＋CTAボタン2つ（移住相談・説明会）＋詳細セクション |
| `/learn` | `/learning/` | 町でまなぶ | B | 「学びの町、七間町」リード＋4タブ（文化・歴史体験 / 塾・学習塾 / 習い事・教室 / 資格・スキルアップ）＋カードリスト |
| `/work` | `/working/` | 町で働く | B | 「歴史を継承し、次代を創る。」リード＋4タブ（CAREER/SPACE/PLAYERS/OPPORTUNITY）＋各タブコンテンツ |
| `/gallery` | `/gallery/` | 町のギャラリー | A | フィルタータグ（すべて/風景/建物/人/食/イベント/季節）＋3カラム写真グリッド |
| `/guide` | `/life-guide/` | くらしガイド | B | リード文＋5タブ（公共施設/医療機関/ごみの出し方/生活ルール/外部リンク）＋各タブコンテンツ |
| `/safety` | `/safety/` | いのちを守る | B | 赤色警告帯（南海トラフ）＋6タブ（緊急連絡先/避難場所/地震・津波/富士山噴火/日頃の備え/公式情報） |
| `/info` | `/news/` | インフォメーション | F | ページタイトル帯＋日付・タグバッジ・タイトルの行リスト（アイキャッチなし） |
| `/residents` | `/stories/` | お隣さんの話 | C | スライダー（ピンク背景）＋3カラムカード＋右サイドバー（新着・カテゴリー） |
| `/sponsor` | `/sponsorship/` | スポンサー募集 | G | ページタイトル帯＋メリット3カード＋スポンサープランテーブル（プラチナ/ゴールド/シルバー） |
| `/contact` | `/contact/` | お問い合わせ | G | 2カラム（左=連絡先情報カード＋地図、右=CF7フォーム） |
| `/privacy` | `/privacy/` | プライバシーポリシー | E | ページタイトル帯＋本文のみ（テキストページ） |
| `/terms` | `/guide/` | ご利用案内 | E | ページタイトル帯＋本文のみ |
| `/company` | `/organization/` | 運営会社 | E | ページタイトル帯＋本文のみ |

### 10.3 SPレイアウト方針

Manusプロトタイプサイトのモバイル表示を参照。基本方針：

- **ナビ**: ハンバーガーボタン → ドロワー（トップナビ＋サブナビを縦並びでまとめて表示）
- **カードグリッド**: 3カラム → 1カラム（一部2カラム）
- **2カラムレイアウト**: 縦積みに変換（テキスト＋画像は画像先行 or 後置）
- **タブUI**: 横スクロールタブ（`overflow-x: auto; white-space: nowrap;`）
- **ヒーロー（TOP）**: 2カラム → 縦積み（上=写真、下=SVGマップ）
- **アイコングリッド**: 4列グリッド（2行）
- **インフォメーション＆イベント**: 縦積み（インフォメーション上、イベント下）
- **テーブル**: 横スクロール対応
- ブレイクポイント: `max-width: 768px`（`$bp-sp` 変数使用）
