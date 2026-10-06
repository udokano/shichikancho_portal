# sichikenchou テーマ — Session Handoff

直近の Claude セッションで触った内容のサマリー。次のセッションはここを最初に読む。

---

## 1. 直近のセッションでやったこと

### ★ 2026-10-05〜06 セッション（サーバー＝正に転換・WP/プラグイン更新・news ブロックエディタ化・求人を CPT+ACF 駆動に）

**前提が変わった**：テストサーバー `https://shichikancho.main-color.com/`（SSH: `ssh mc-core`・CORESERVER）を調べたところ、**先方が子テーマ `sichikenchou-finish` を作って運用中**だった。有効テーマは子テーマ（親 = sichikenchou）。先方は管理画面＋自作ツールで店舗名簿319件を下書き投入済み。**AIM でローカル → サーバーへ流すと全部消える**ため、運用を反転。

#### A) サーバー → ローカルへ取り込み（コンテンツの正をサーバーに）
- サーバー DB（接頭辞 `siwn_`）を取得してローカルへ投入。**ローカルの wp-config も `siwn_` に変更**（旧 `wp_` テーブルは残置・未削除）
- URL 置換1332件、子テーマ・mu-plugins・uploads（145MB）同期、ローカルで http-auth を無効化
- サーバーにのみ存在したテンプレート5件（page-company / page-contact / page-links / page-privacy / single-resident）をリポジトリに取り込み（`d3da7a4`）
- 子テーマを Git 管理に。GitHub **非公開** `udokano/sichikenchou-finish`（初回 `146d1d3`）
- 取り込みスクリプト `tools/pull-finish.sh`（rsync → commit → push ＋ 親テーマのズレ件数も表示）。**cron 登録済み：平日 9:00**、ログは `tools/pull-finish.log`

#### B) サーバーの掃除（開発用ファイルが公開ディレクトリに置かれていた）
- 削除：`.git`(15MB) / `.claude/worktrees`(12MB) / `.codex` / `.vscode` / `.sass-cache` / `.playwright-mcp` / `_docs` / `acf-import` / `div` / ルートの `*.md` / `sitemap.csv` / **`_seed-*.php` 6本（5本は認証ガード無しで URL から実行可能だった）** / 旧 `inc/*.php` 17本
- 退避アーカイブ：`tools/sc-import/backups/server-devfiles-*.tar.gz`（21MB・1446ファイル）
- サーバーの PHP ファイル 221本 → 69本

#### C) WordPress・プラグイン更新（サーバー / ローカル両方）
- 本体 7.0.2 → **7.1.2**（7.0.6 を経由）。DB バージョンは 61833 で変化なし
- ACF Pro 6.8.6→6.8.10 / CF7 6.1.6→6.1.7 / AIM 7.107→7.111＋拡張 2.86→2.87 / その他マイナー計10件
- **保留**：AIOSEO 4.9.10 → 5.0.2.1、Taxonomy Terms Order 1.9.9.1 → 2.0（いずれもメジャー）
- 先方の自動更新設定は `auto_update_core_major = enabled`。**意図的な固定ではなく、ベーシック認証で wp-cron が動いていないのが原因**（予約投稿・定期処理も止まる）

#### D) インフォメーション（news）をブロックエディタに戻す（`b1897fc`・サーバー反映済み）
- 原因：`inc/admin.php` の `sc_classic_editor_types()` に `news` が入っていた（プラグインではなくテーマ側の指定）
- news 16件は全てブロック形式で保存済み。クラシックで保存するとブロックが壊れる状態だった
- 全16件のブロック種類を確認：段落41 / 見出し10 / リスト1。クラシックブロック・生HTML は 0
- **同じ矛盾が resident(18) / property(10) / job(10) / gallery_photo(10) / coworking(5) / spot(13) に残存**（今回は news だけ対応）

#### E) 求人（job）を直書きから CPT + ACF 駆動に（`0fd20d9`・サーバー反映済み）
- page-work.php に求人カード7件＋モーダル7件が直書きされていた。922行 → 192行
- 新規：`template-parts/components/job-card.php` / `job-modal.php`、`sc_job_type_labels()`（inc/helpers.php）
- ACF：雇用形態を**チェックボックス（複数選択）**化（schema.org の employmentType は複数可）、`TEMPORARY` のラベルを「契約・派遣（有期）」に変更して契約社員を収容、`job_holiday` / `job_website` / `job_tags` を追加
- 直書きにしか無かった会社名・職種・休日・応募条件・待遇・タグを ACF へ取り込み。本文は空にし `_sc_legacy_content` に退避
- **求人情報の投稿タイプから editor を除去**（`supports: title, thumbnail`）
- 絞り込みチップを実データ＋ACF 選択肢から生成。JS を data 属性ベースに変更（雇用形態の複数持ち対応）
- 未使用だった `schema_job()` を page-work.php から呼び出し（JobPosting ×10）。description は ACF、url は `/work/#job-modal-{ID}`
- 動作確認（ローカル・ブラウザ）：カード10・モーダル10、絞り込み（業務委託2 / 正社員4 / 契約派遣1 / 医療福祉1 / キーワード「そば」1）、モーダル内容すべて正常

#### F) 求人 ACF のフィールド順をモーダル順に + 重複グループ掃除（`75cdff6`・push 済み・**サーバー未反映**）
- 編集画面の並びをモーダルの表示順に変更：雇用形態 → 会社名 → 仕事内容 → 給与 → 勤務地 → 勤務時間 → 休日 → 職種 → 応募条件 → 待遇 →（以下元の順）タグ / 応募方法 / 企業サイト / 応募締切 / 募集中
- 定義は不変（並びと `modified` のみ）。インポート用 JSON はテーマ外 `wp-content/themes/acf-import-group_job-reorder.json`
- 反映は管理画面インポートと同じ処理を wp-cli で実行（`acf_get_internal_post_type_post()` で既存 ID を引いて `acf_import_internal_post_type()`）。`acf-json/group_job.json` は ACF の自動同期で更新
- **ローカル DB に `group_job` が2件あった**（ID 430＝旧12フィールド / ID 1704＝15フィールド）。ACF が解決するのは 430。1704 と子15件（1705〜1719）、親なしで残っていた `field_job_type`（ID 1703）を削除。原因不明（E のチェックボックス化時の副産物と推測）
- バックアップ：`wp-content/themes/acf-group_job-dup-1704-backup.sql` / `acf-field_job_type-dup-1703-backup.sql`（不要になったら削除）
- **会社名・タグが出ない3件（ID 125 SNS・広報 / 126 そば店スタッフ / 127 まちづくりコーディネーター）はデータ欠け**。`job_company` / `job_tags` / `job_position` / `job_hours` / `job_holiday` / `job_benefits` のメタ行が無い。旧直書きに載っていなかった簡易投稿で、元データがリポジトリに無い。テンプレートは正常（空なら出さない分岐）。`job_position` が無いので職種絞り込みにも掛からない
- push で `d3da7a4` / `b1897fc` / `0fd20d9` も同時に GitHub へ上がった（それまで未 push）
- 確認はすべて curl + wp-cli。ブラウザでの目視はしていない

**ハマりポイント**
- wp-cli が「データベース接続確立エラー」になる。Local の PHP にソケットを直接渡す：`"/Applications/Local.app/Contents/Resources/extraResources/lightning-services/php-8.2.29+0/bin/darwin-arm64/bin/php" -d mysqli.default_socket="$HOME/Library/Application Support/Local/run/IG98zSrPa/mysql/mysqld.sock" /usr/local/bin/wp-cli.phar ...`（`WP_CLI_PHP_ARGS` はパスの空白で失敗）
- 編集画面のフィールド順は DB ではなく `acf-json` の定義が優先される。DB 側に重複があっても表示は変わらないので気付きにくい
- ACF グループを消すとき `acf_delete_field_group()` は使わない（同じキーの `acf-json` ファイルまで消える）。CLI から `wp_delete_post()` で子フィールド → グループの順に消す

### ★ 2026-10-04 セッション（掲載情報の一括取込フロー設計 + Excel一括登録ツール作成・テーマ外）

**背景**：ディレクターから「ページ内の情報を本当の情報にしたい／コラム100本・店舗・病院・学校も」。現地確認ができないため、**ディレクター側 Claude Code で情報収集 → Excel → こちらでローカルに一括登録 → AIM でテストサーバーへインポート**の分担で合意。記事本文はディレクター側が担当、こちらはデータ投入のみ。

**作成物（テーマ外・git / AIM 書き出し対象外）** `/Users/okanoyusuke/Local Sites/sitikentxhou/tools/sc-import/`
- `run.sh <import.xlsx> [--apply]`：既定 dry-run。`--apply` 時のみ mysqldump を `backups/` へ。JSON とレポートは `work/`
- `xlsx2json.py`：openpyxl でシート→行配列 JSON（float / 日付を文字列化）
- `import.php`：`wp eval-file` で実行。shop / spot / living / shelter の4シート対応

**仕様**
- 列名 = ACF フィールド名。`title` 完全一致で既存投稿を照合（複数ヒットは保留）、無ければ publish で新規
- 空セルは既存値を上書きしない。`確度=低` の行は見送り
- タクソノミー（shop_category / area / spot_type）は**既存タームのみ紐付け、新規作成しない**（表記ゆれで分類が増えるのを防ぐ）
- checkbox / select は choices 照合して不正値を除外
- 緯度経度は国土地理院 AddressSearch で補完（〒・全角を整形してから投げる。住所変更時か lat 空のときだけ）
- アイキャッチは xlsx と同階層 `images/` から取り込み、添付に `_sci_source`（元ファイル名）を残して二重登録を防止
- living は page-living.php の `{lf,lb,ls,lac,lsu}_facility_groups` を、シートに出たタブだけ丸ごと置換
- shelter（page-safety.php 直書き）は取込対象外。レポートに一覧出力 → PHP 手修正
- 標準出力は件数サマリのみ、行ごとの結果は `work/report-*.md`

**検証済み**
- dry-run：更新 / 新規 / 確度低 / 選択肢外 / 未登録ターム / 画像なし / タブ不正 の判定すべて想定通り
- apply：既存1店を同値更新で成功。バックアップは36テーブル・`Dump completed` 確認
- 新規経路：トランザクション内で作成 → ACF / ターム / 緯度経度 / アイキャッチ / living 置換まで確認後 ROLLBACK（テスト投稿・画像の残存なし）
- 初版は郵便番号付き住所でジオコーディング0件 → `mb_convert_kana` + 〒除去 + 数字間ハイフン正規化で解消

**環境メモ（重要）**
- wp-cli は Local の PHP + `-d mysqli.default_socket=...` + `--skip-plugins=http-auth` でないと動かない。http-auth が CLI も `This Site is Restricted` で弾く
- テストサーバー `https://shichikancho.main-color.com/` の Basic 認証は**サーバー設定ではなく http-auth プラグイン（DB 保存）**。AIM インポートでサーバー側の ID/PW がローカル値に上書きされる

### ★ 2026-07-25 セッション（SEO/MEO 精査・AIOSEO 投入・LLMO 分離・捏造データ除去・営業時間スキーマ全面修正）

**未コミット**: `inc/schema.php` / `inc/seo.php` / `inc/seo-llmo.php`(新規) / `functions.php` / `llms.txt` / `_docs/`(新規)。**DB 投入分は git 外**

#### AG) NAP 正の確定とプロトタイプ照合（★調査）
- プロトタイプ https://shichikancho-yx4urq24.manus.space/ の copyright 準拠で運営者名の正は **「七間町町内会」**
- 住所の正: 〒420-0035 静岡県静岡市葵区七間町17-9
- meta description（全ページ共通1本）: 「静岡県静岡市葵区七間町。駿河カルチャーライン構想の中心地として、観光情報、商店街のお店、イベント情報など、町の魅力を発信します。」
- **電話番号・Instagram 以外の SNS URL は一次情報が存在しない**。捏造せず空のまま

#### AH) AIOSEO へ DB 直投入（★DB・git 外）
- `wp_options.aioseo_options` に投入: `searchAppearance.global.metaDescription` / `schema.organizationName`=七間町町内会 / `schema.organizationLogo` / `social.profiles.urls.instagramUrl`
- **`organizationLogo` が `.local` 絶対URL**。本番移行時に書換必須
- JSON を mysql バッチモードでパイプすると壊れる → **PHP mysqli + `mysqli_set_charset('utf8mb4')` で書く**こと

#### AI) LLMO を `inc/seo-llmo.php` に分離（★コード）
- zakkushi テーマと同構成。llms.txt 動的生成 / robots.txt AI 許可 / AI meta・geo タグ / AIOSEO 拡張を `seo.php` から移動。`seo.php` は純粋な SEO 補完のみに
- `sc_aioseo_schema_enrich()`（`aioseo_schema_output` フィルタ）— AIOSEO の WebPage/CollectionPage に `mainEntity` / `about` / `speakable` を注入（shop→`#shop`, spot→`#spot`, front→`#localbusiness`）、BreadcrumbList の item 文字列を `@id` オブジェクト化
- `sc_aioseo_og_type_place()` — shop/spot の og:type を article→website に補正、`article:*` タグ除去
- `/llms.txt` が `/llms.txt/` へ 301 されていたのを `redirect_canonical` フィルタで抑止

#### AJ) 捏造 NAP データの除去（★DB・git 外）
- seed 店舗 6件（38/39/40/41/45/47）: `shop_phone` の `054-000-%` と `shop_website` の `%example.com%` を空に。**架空の電話番号は MEO 上の実害**
- spot 59〜65 に `spot_address` + `_spot_address`(=`field_spot_address`) を投入。**検索で裏取りできた住所のみ**
- spot 66（旧田中家屋敷跡）と 67（MICE推進センター＝市役所内の課であり施設でない）は検証不能につき**意図的にスキップ**
- `schema_local_business()` の `'openingHours' => 'Mo-Su 10:00-21:00'` を削除（商店街に統一営業時間など無い）

#### AK) 営業時間スキーマの全面書き換え（★コード・inc/schema.php）
- **旧 `sc_shop_normalize_hours()` は最初の1レンジしか拾わず、3店で事実と違う営業時間を出力していた**（1256 芳龍→土日ランチのみ / 1257 みむらや→昼のみ / 1258 揚屋たけ→昼のみ）。さらに Google 由来の「11時30分～14時00分」形式に非対応で **35店の曜日別データを丸ごと捨てていた**
- `sc_shop_time()` / `sc_shop_time_ranges()` / `sc_shop_hours_specs()` を新設
  - 曜日別表記「月曜日: 11時30分～14時00分, 17時00分～22時00分」を優先解析し、同一時間帯の曜日をまとめて複数 spec で出力
  - 「火曜日: 定休日」の行は時間帯が取れず自然に `dayOfWeek` から外れる
  - **曜日不明かつ複数レンジは構造化しない**（生テキストを `openingHours` に退避）。嘘を出すくらいなら出さない方針
- 定休日未入力の店は `dayOfWeek` を省略（年中無休と主張しない）
- 結果: 構造化 44件 / 生テキスト 3件 / 時間データ無し 4件

#### AL) AIOSEO 有料版 Local SEO の適用可否（★調査・結論=使えない）
- 公式ドキュメント（2026-04-15 更新）: Local SEO は **Plus プラン以上**（Plus/Pro/Elite。Basic 不可）
- ただし LocalBusiness を出せるのは **ホームページ** か **AIOSEO 専用の `Locations` CPT** のみ。既存 `shop` CPT には適用できない
- 使うなら shop 47件を Locations CPT に全移行 → URL・テンプレ・ACF・タクソノミー作り直し。**割に合わないので shop/spot/event の schema はテーマ側で保持**
- 価格ページの JSON 抽出は Pro=Local SEO 無しという矛盾した結果を返した（LLM の読み違い）。**pricing ページより docs を信じること**

#### AM) column の Article 二重出力チェック（★調査・結論=重複なし）
- AIOSEO の `column` CPT デフォルトグラフは `WebPage`（`aioseo()->schema->getDefaultPostTypeGraph()` で確認）。出力ノードは BreadcrumbList / Organization / Person / WebPage / WebSite のみ
- **テーマの `schema_article()` が唯一の Article 出力元**。削除すると Article が消えるので維持

#### AN) メタディスクリプション全件投入（★DB・git 外）
- 投入前は**全ページがグローバル既定文1本のみ**（ページ別設定はほぼ空）。公開コンテンツ 213件で **空 0 / ユニーク 212種** に
- 保存経路は管理画面と同一（`\AIOSEO\Plugin\Common\Models\Post::getPost()->save()` = `wp_aioseo_posts` / `aioseo()->dynamicOptions->save(true)` = `aioseo_options_dynamic`）。**管理画面から普通に編集できる状態**
- 固定ページ 27枚は個別投入。入力済みは上書きしないガード付き。**エリア5ページは `/area/` の子なので `get_page_by_path('tokiwa')` では拾えない** → ID 指定（1223〜1227）
- CPT アーカイブ 8種（shop/spot/event/column/resident/news/job/learn_facility）: `dynamicOptions->searchAppearance->archives->{pt}->metaDescription`
- **CPT 個別は `#custom_field-{ACFフィールド}` スマートタグで自動化。Lite でも動作する（実測）**
  - shop→`shop_description`（抜粋は 10/51 しか無いが ACF は 47/51）/ spot→`spot_description` / learn_facility→`facility_description` / walk_course→`walk_description`
  - ACF 空の投稿も AIOSEO が本文から自動生成にフォールバックするため空にならない。**抜粋への転記は不要だった**
- resident は**本文がシードの同一ダミーで8件完全重複**していたため `#post_title｜…` 形式で一意化。**実インタビューに差し替えたら `#post_excerpt` に戻す**
- 個別に手当て: photo_award 4件（説明用 ACF が無い）/ shop 2件（揚屋たけ 162字・大石精肉店 179字 → 句点単位で詰めた）
- 「町に住む」は description 欄に文字列 `#post_content` が入っており、本文が空のテンプレページのため空文字に解決されていた
- 文面の控えは `_docs/seo-meta-drafts.md`。**テーマ PHP にハードコードしない**（ユーザー方針: メタは管理画面が正、テーマ側は保険のみ）
- **本番移行時はこの控えから再投入する**（DB は git 外）。固定ページ27枚・アーカイブ8種・投稿タイプ別フォーマット・個別6件の実文言と API 手順を同ファイルに収録済み

#### AO) 散策コースのルートマップが機能していない件（★DB・git 外）
- 症状: `/walk-course/ocha-machiaruki/` でルートマップが実質出ない。**テンプレートは正常**（`single-walk_course.php:140` の `count($map_points) >= 1` でセクション自体は描画される）
- 原因: **参照スポット側の `spot_map_lat` / `spot_map_lng` が空**。お茶コースは 6件中 5件が空で、開いてもピン1本のみ・ルート線なし（`walkmap.js` の polyline は 2点以上で描画）
- 全6コースを監査 → 欠落は2コースで計7件（お茶 1/6・歴史探訪 3/5）。他4コースは元から充足
- 対応: `SC_GOOGLE_MAPS_KEY` で実データ取得し7件に投入。**住所ありは Geocoding / 住所なしは Places Text Search**。`formatted_address` に「葵区」を含まない結果は採用しないガード付き
  - Geocoding: 八千代寿し鐵(ROOFTOP) / 茶町(APPROXIMATE=町域の代表点) / 静岡市歴史博物館(ROOFTOP)
  - Places: T's green omachi(七間町16-7) / 田丸屋本店(紺屋町6-7) / ルモンドふじがや(昭和町6-1) / うおかね(馬場町33)
- トレーサビリティ用に `_sc_place_id` と `_sc_geo_source`（`geocode:2026-07-25` 形式）を記録
- **田丸屋本店は候補3つ**（紺屋町の本店 / パルシェ店 / 駿河区の株式会社本社）。まち歩き動線として中心街の本店を採用。意図が違えば差し替え
- 検証: 全6コースが座標100%。実 HTML の `data-points` で6点出力を確認

#### AP) 散策コースの所要時間に移動手段を明示（★コード）
- 背景: 歴史探訪コースの「24分」は **PDF のシェアサイクル時間**（区間も `自転車 1分`…）。「散策コース」枠で分数だけ出すと徒歩24分と読まれる（実際は徒歩1時間前後の距離）
- **新規 ACF フィールドは追加していない**。`walk_spots` の `time_to_next`（「徒歩 15分」「自転車 5分」形式）先頭語から導出
- `inc/helpers.php` に2関数追加: `sc_walk_transport()`（徒歩/自転車/バス/電車/タクシー/車を集約・混在は「徒歩・バス」）/ `sc_walk_duration_label()`（「自転車 24分」を返す。手段不明なら従来通り分のみ）
- 差し替え6箇所: `single-walk_course.php`(44, 301) / `page-walk.php`(258, 367) / `page-explore.php`(172, 234)
- 結果: 歴史探訪=自転車 24分、他5コース=徒歩 45〜120分
- **`page-explore.php` は「Template Name: 町をめぐる」だがどのページにも未割当**（`/walk/` が `page-walk.php` で稼働中）。重複テンプレートの整理は未判断

#### AQ) 静岡市パンフ PDF とサイト spot/shop の突き合わせ（★調査）
- 対象: `https://www.visit-shizuoka.com/asset/pamphlet/shizuoka-city-discovery-trip.pdf`（24ページ・**見開き1PDFページ=印刷2ページ**。印刷 P.16 は PDF p9）
- **AREA GUIDE 1「静岡市街地」（印刷 P.15-18）のみが七間町圏**。GUIDE 2〜7（日本平/丸子・宇津ノ谷/清水・三保/興津・由比・蒲原/オクシズ/用宗）は清水区・駿河区・葵区山間部で掲載基準外
- 番号付き12件のうち **未登録6件**: 臨済寺（大岩町7-1）/ 駿府楽市（黒金町47 アスティ）/ 12-twelve（紺屋町7-14）/ しずチカ茶店 一茶（黒金町49-1）/ **人宿藍染工房（人宿町2-6-5）** / 静岡ホビースクエア（駿河区＝圏外）
- 地図ラベルの未登録: **葵舟**（駿府城の堀めぐり遊覧船・要予約）/ **茶町KINZABURO**（お茶コース SPOT4 の中核）/ 華陽院（家康公祖母の墓所）/ 二加番稲荷神社 / 静岡近代美術館 / PARCO / 弥次喜多銅像 / 竹千代像
- **PDF P.15 にもう1本、未登録の徒歩コースあり**: 「街歩きの王道 歴史探究 街あるき」= JR静岡駅→しずチカ茶店一茶→静岡浅間神社→日本料理うおかね→葵舟(駿府城公園)→人宿町散策→青葉おでん街→JR静岡駅（**徒歩 計93分**）。前回の「モデルコース10本」棚卸しはページ3〜7しか見ておらず漏れていた。**各 AREA GUIDE ページに同種のミニ徒歩コースが付く構成**
- 副産物: **spot の重複登録**を発見 — 静岡浅間神社(62/1324) / 駿府城公園と駿府城跡(58/1313) / カフェ・ド・七間(spot 787・shop 39 でCPT跨ぎ) / 映画館めぐりコースが七間町商店街(786)を2回連続参照

#### AR) /tourism/ にコース一覧への導線を追加（★コード・DB）
- `page-tourism.php` の「人気の散策コース」末尾に `c-btn c-btn--primary` で「散策コースをすべて見る」ボタン追加。SCSS は `_page-tourism.scss` の `&__walks-more`
- 最初に専用一覧ページ `/courses/`（`page-course-list.php` + `_course-list.scss` + 固定ページ ID 1346）を作ったが、**`/walk/` に絞り込み付き一覧が既にあった**ためユーザー判断でボタンは `/walk/` へ。**ID 1346 は draft に降格**（削除ではないので復帰可能）
- **`page-course-list.php` と `assets/scss/pages/_course-list.scss` + `main.scss` の `@use 'pages/course-list'` は未使用のまま残置**。`/walk/` 一本化で確定なら削除（git 未追跡なので消すと復元不可）
- ボタンのガードは `get_page_by_path('walk')`。ページが無い環境では出力しない

#### AS) PDF コース・スポットの一括投入（★DB・git 外）
- 対象の線引き: **JR静岡駅発着で公共交通・徒歩で完結するコースのみ**。IC 発着の車前提コースは原則対象外（後述の例外1本）
- **スポット20件を新規作成**。住所・電話・説明は PDF 記載、座標は Geocoding（住所あり）/ Places Text Search（住所なし）で裏取り。`_sc_source` に出典、`_sc_place_id` / `_sc_geo_source` に取得元を記録
  - 葵区中心部: しずチカ茶店 一茶 / 葵舟 / 人宿町 / 駿府楽市 / 人宿藍染工房 / 12-twelve / 臨済寺 / 入船鮨 両替町店 / 茶町KINZABURO
  - 東海道・丸子: 石部屋 / 駿府の工房 匠宿 / 丁子屋
  - 日本平・久能山・清水: 久能山東照宮 / 日本平夢テラス / 日本平ロープウェイ / 三保松原 / 日本平ホテル / グリーンエイトカフェ / やすらぎの森 食事処たけのこ / 清照由苑
- **コース5本を新規作成**（区間時間・移動手段は PDF のコース図を `pdftoppm` で拡大して1区間ずつ転記）
  - 歴史探究 街あるき（徒歩93分・P.15 街歩きの王道）/ 東海道名物と静岡のソウルフード（徒歩・バス100分）/ 家康公が眠る国宝と絶景（徒歩・バス・ロープウェイ176分）/ 絶景フォトスポットめぐり（徒歩・ロープウェイ106分）/ **感動のお茶体験（車80分・ユーザー指示で車前提だが例外的に投入）**
- `sc_walk_transport()` に **ロープウェイ** を追加
- **PDF どおりにできなかった点**: 青葉おでん街は shop 登録（ID 1290）で `walk_spots.ref` が spot 限定のため立ち寄り先にできず、隣接の青葉横丁（spot 1288）で代用
- 全11コースが座標100%。ルートマップに全点が乗る状態

#### AT) コース↔スポット / エリア↔スポット の逆引き導線（★コード）
- `inc/helpers.php` に3関数追加
  - `sc_get_courses_by_spot()` — **ACF リピーターは meta_query でキーのワイルドカード指定ができない**ため `$wpdb` で `meta_key LIKE 'walk_spots_%_ref'` を直接引く
  - `sc_get_area_term_map()` / `sc_get_post_areas()` — 町名ターム→大エリアの逆引き。正は各エリアページの ACF `area_linked_terms`（page-area.php と同じ解決順）
- `single-spot.php`: 「このスポットをめぐる散策コース」（所要時間・スポット数つきカード）と「◯◯エリアのガイドを見る」リンクを追加。`_single-spot.scss` に `&__courses` / `&__areaguide`（エリア色は slug 別モディファイア。**インライン style は使わない**）
- データ側の穴埋め: 住所から area タームを**12件付与**（spot 6・shop 6）、`spot_address` が空だった4件（T's green omachi / 田丸屋本店 / ルモンドふじがや / うおかね）を Places 取得済みの住所で補完
- **未接続19件は意図的**（清水区・駿河区＝5エリア管轄外／葵区だが町名タームが未登録＝茶町・追手町・黒金町・八千代町・大岩町・弥勒・土太夫町）

#### AU) /walk/ のカードメタ崩れ修正 + コース一覧アンカー（★コード）
- **アイコンとテキストのズレ**: `.p-walk__course-card-meta` が `display:contents` で div/dt を潰し、`align-items` 未指定だったため 14px の SVG が上寄せ。さらに dl 直下の `gap:16px` がアイコン⇔値の間にも効き、グルーピングも崩れていた
- `page-walk.php` に `-meta-row` / `-meta-term` / `-meta-desc` クラスを付与し、`_walk.scss` の裸タグセレクタ（div/dt/dd）を廃止して行を実体化（`align-items:center` / 行内 4px・項目間 16px）
- `/tourism/` のボタン飛び先を `/walk/#walk-courses-title` に。**飛び先の見出しが存在しなかった**（`aria-labelledby` が空参照）ので `page-walk.php` に「散策コース一覧（N件）」の h2 を追加、`scroll-margin-top` 付き

#### AV) ACF 双方向フィールドの検討（★結論=不要・作ったものは削除済み）
- 「コース↔スポットを ACF の関連フィールドで双方向に」という要望で、両側にトップレベル Relationship を新設する import JSON と移行スクリプトを作成 → **ユーザーが「前からできていた」と判断し削除**
- 判明した事実は残す: **`walk_spots.ref` はリピーター内サブフィールドなので ACF の双方向対象にできない**（双方向はトップレベルの関連フィールド同士のみ。ACF PRO 6.8.6 のソースで確認）。双方向にするなら別のトップレベルフィールドを新設するしかなく、順路リピーターと二重管理になる
- 既存の関連フィールド（`pickup_*` / `spot_related_spots` / `resident_favorite_spot`）は**すべて片方向**。双方向設定を使っている箇所は現状ゼロ
- 迷走の原因: 「双方向」をフロントの相互リンクと解釈して single-spot 側の逆引き→カスタムメタボックスと2回作り直した。**管理画面の話か表示の話かを最初に確認すること**

#### 環境ノウハウ（このセッションで判明）
- **`/usr/local/bin/wp` ラッパーは Local の PHP を使うためソケット指定が効かない**。homebrew php で phar を直接叩く:
  ```
  php -d mysqli.default_socket="$HOME/Library/Application Support/Local/run/IG98zSrPa/mysql/mysqld.sock" \
    /usr/local/bin/wp-cli.phar eval-file <script> --skip-plugins=http-auth
  ```
- これで **ブラウザ無しで JSON-LD を全件検査できる**（http-auth があるので HTTP 経由は不可）。`ob_start()` + `schema_shop($id)` で出力を捕まえる
- firecrawl は **404 ページでも JSON 抽出が「それらしい値」を返す**。`statusCode` を必ず見ること

---

### ★ 2026-07-24〜25 セッション（エリアガイド chuo-kanko 風・Places一括インポート・SC_TPL_URI 定数化）

**コミット済み**: `998ab39`(コードレビュー=本セッションの大半) → `1c1e1d7`(TOPコード修正=front-page)。未コミットは HANDOFF.md のみ。**DB投入分（AB/AC）は git 外**

#### Z) /tourism/ エリアガイドを chuo-kanko 風レイアウトに（★コード）
- マップ縮小: `&-map-wrap` max-width 896→**627px**（旧の70%）。`width:70%` 指定は max-width で頭打ちになり効かなかったため max-width 側を縮めた
- **PC: 3カラム**（左320px｜マップ｜右320px、`&-layout` grid）。左=baba/shichikancho、右=takajo/gofuku/tokiwa（マップ領域位置に合わせ振分け。`$area_map_sides` in page-tourism.php）
- ラベルカード = エリア色ピル（白文字・rem(13)・nowrap）＋「[ 主な観光名所 ]」リスト。**リストは spot 投稿連動**（area_terms→term解決→spot最大5件、`$area_spots_titles`）。spot 0件用の静的フォールバック配列あり（現在は全エリア spot 有で不使用）
- **SP: マップ上にピルを絶対配置で重ねる**（参考サイトSP準拠）。`&-layout` relative + `&-side` display:contents + slug別 %座標。リスト/トグルはSP非表示
- **注意**: 途中で実装したSPアコーディオン（`.js-area-acc`、main.js）は上記変更で**どこでも発火しない死にコード**。戻す予定が無ければ削除可
- 旧モバイル用フォールバックリスト（`&-list` 一式）は撤去済
- タブアイコンのズレ修正: `&__spots-tab-icon` に flex中央寄せ＋`font-size:0`（マークアップ改行の空白ノードでSVGが4px左に寄っていた）

#### AA) エリア配色をマップ5色に全site統一（★コード）
- `--area-color` を **マップ同色**（shichikancho #8BA7C5 / tokiwa #CBA7A1 / gofuku #A4BBAE / takajo #D3C4A7 / baba #A8A7C4）に統一
- 対象: `_page-tourism.scss`（ラベルカード＋exploreカード）と `_area.scss`（エリア詳細ページ）。旧パステル（#f8b4c4 等）は全廃

#### AB) Google Places 一括インポート spot/shop 56件（★DB・公開済）
- 町名ごとに Text Search →「葵区＋町名」住所一致・レビュー3件以上のみ採用。spot エリア≤5 / shop ≤8、評価件数順。**`_sc_place_id` メタで再実行時も重複しない**。`_sc_places_import`=2026-07-24 で一括抽出可
- ACF: 住所/電話/サイト/営業時間/緯度経度、area ターム（町名）、spot_type / shop_category を自動設定
- **概要文も投入済**: editorial_summary（あれば）→ 公式サイト meta description のリライト → 情報薄い店は業態レベルの無難な文。ファクトチェック済（賤機山古墳=円墳・東海の日光・坤櫓など裏取り、駿府町の記述は市民文化会館ベースに修正済）
- **要ユーザー精査**: 非加盟店掲載の是非（コンコルド=パチンコ、マクドナルド、静岡駅寄りチェーン等）。業態推定の desc 数件（ＤＯＮ幸庵/やぶ福/河内庵/サングリア/カウボーイ）。駿河屋の shop_category が「食べる」誤分類のまま
- spotと二重登録された shop 4件＋神社1件は削除済

#### AC) エリア詳細ページのコンテンツ補完（★DB/ACF）
- **町の紹介**（area_towns リピーター）: 不足14町を追記（既存3町保持）。全5ページ=4/3/3/4/5町
- **冒頭セクション**（area_intro_en / area_intro_title / area_features×3）: 空だった4エリア（tokiwa/gofuku/takajo/baba）に投入。空フィールドのみ設定・七間町エリアは無変更
- **未登録のまま**: 4エリアの area_history / area_course（歴史は事実確認の重要度高、資料もらってからが安全）

#### AD) 記事系 single の No-image ヘッダー非表示（★コード）
- spot/news/column/event の4テンプレ: アイキャッチ未設定時にヒーロー画像ブロックごと非表示（`sc_no_image_url()` フォールバック廃止、`?: ''` + if ガード）
- カード一覧・関連スポットのサムネ placeholder は現状維持。single-property は物件系のため対象外（同パターン残存）
- **注意**: アイキャッチ無しイベントは「終了」バッジも消える（ヒーロー内にあるため）

#### AE) single-shop パンくず一本化（★コード）
- タイトル上の独自パンくず `p-shop__crumb`（商店街のお店›カテゴリ›エリア）を削除、Gナビ直下の共通 breadcrumbs のみに。SCSS の `&__crumb` 一式も削除
- **注意**: エリアタームへの導線がページから消えた（共通パンくずはカテゴリまで）

#### AF) front-page.php 整理 + SC_TPL_URI 定数化（★コード）
- front-page.php: 未使用 `$banner_base` 削除 / 配列コピペを array_fill・array_merge に / スクロール帯の二重 foreach を for×2 に / コメント不一致修正 / 観光マップ h2 内の崩れ整形。**出力HTML完全一致を diff 検証済**
- **`SC_TPL_URI` 定数**を inc/constants.php 先頭で定義（`get_template_directory_uri()`）。テーマ全体 **50箇所・13ファイル** を一括置換（header/footer/front-page/page-* 5枚/inc 4枚）。get_template_directory()（パス系）は対象外
- **shop カテゴリチップを実ターム連動に**: 静的 `$shop_cats`（泊まる/学ぶ等の架空データ）→ `get_terms(TAX_SHOP_CAT)` 全ターム＋実件数。**旧リンク `?cat egory=` はアーカイブが読まず絞り込み無効だった**→ 実仕様 `?cat[]=スラッグ` に修正（「買う」6件で件数一致を検証済）

#### 環境ノウハウ（このセッションで判明）
- サイトは **http-auth プラグインで Basic認証 demo/pass**（wp_options `http_auth_settings`）。curl は `-u demo:pass`
- **CLI から WP を叩く方法**: wp-cli は DB 接続不可。`php -d mysqli.default_socket="~/Library/Application Support/Local/run/IG98zSrPa/mysql/mysqld.sock" -r '$_SERVER["PHP_AUTH_USER"]="demo"; $_SERVER["PHP_AUTH_PW"]="pass"; require "wp-load.php"; ...'` が確実（認証偽装しないと http-auth が wp_die する）
- DB直: Local の mysql バイナリ + 上記ソケット、`--default-character-set=utf8mb4` 必須（化け防止）

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

- **テストサーバー**：`ssh mc-core`（CORESERVER v2012 / `~/domains/shichikancho.main-color.com/public_html`）。ベーシック認証は http-auth プラグイン（DB 保存・`demo` / `pass`）。wp-cli は `php ~/wp-cli.phar --skip-plugins=http-auth`
- **ローカルのテーブル接頭辞は `siwn_`**（2026-10-05 にサーバー DB を取り込んだ際にサーバーへ合わせた）。旧 `wp_` テーブルは残置
- **ローカルの管理者は `admin`（サーバー由来）**。Local アプリのワンクリックログインは効かない。パスワードは `wp user update admin --user_pass=...` で付け替える
- **有効テーマはローカル・サーバーとも子テーマ `sichikenchou-finish`**。親テーマだけ直しても、子が上書きしているファイル（front-page / footer / page-living / page-area / page-learn / page-tourism / page-access / archive-event / area-map.php / assets/main.js）は反映されない
- **子テーマの取り込み**：`tools/pull-finish.sh`（平日 9:00 に cron 実行・ログは `tools/pull-finish.log`）。GitHub は非公開リポジトリ `udokano/sichikenchou-finish`
- **バックアップ置き場**：`tools/sc-import/backups/`（DB ダンプ・サーバー退避アーカイブ・更新前プラグイン）
- **SCSS コンパイル必須**：編集後 `npx sass assets/scss/main.scss assets/css/main.css --style expanded --no-source-map`。`assets/css/main.css` は **.gitignore 対象**（コミットしない・本番でビルド前提）
- **OPcache**：PHP 編集後、`public/flush.php`（`opcache_reset()`）を curl で叩いて削除。本番反映には別途必要
- **WP-CLI で DB 操作可（推奨）**：Local ソケット + `--skip-plugins=http-auth` + `--exec` で DB_HOST 上書き（詳細は §1-J）。ラッパー例 `scratchpad/wpx.sh`。ACF データ投入は `wp eval-file seed.php`（`update_field()`）。ブラウザ経由 seed はもう不要
  - `wp --skip-plugins=http-auth --exec="define('DB_HOST','localhost:~/Library/Application Support/Local/run/IG98zSrPa/mysql/mysqld.sock');" <cmd>`
  - **`wp post term set` は `--by=id` 必須**（既定は名前扱いでゴミターム量産）
- **Restricted Site Access / http-auth プラグイン稼働**：未ログインの curl は 401。CLI は `--skip-plugins=http-auth` で回避
- **検証は Claude in Chrome の `javascript_tool`**（認証済みタブ）。computed-style や naturalWidth で確認。chrome-devtools MCP は別ブラウザ起動で http-auth 未認証→ローカルサイト到達不可
- **JSON-LD / PHP ロジックの検証はブラウザ不要**。homebrew php で wp-cli.phar を直接叩く（`/usr/local/bin/wp` ラッパーは Local の PHP を使うのでソケット指定が効かない）:
  ```
  php -d mysqli.default_socket="$HOME/Library/Application Support/Local/run/IG98zSrPa/mysql/mysqld.sock" \
    /usr/local/bin/wp-cli.phar eval-file <script> --skip-plugins=http-auth
  ```
  `ob_start()` + `schema_shop($id)` で出力を捕捉して全件検査できる

---

## 3. 残タスク

### SEO/MEO（2026-07-25 セッション由来）
- **NAP 統一 — ユーザー判断待ちで stay**。footer「七間町 町内会」/ `schema.php` の `SC_ORG_NAME`「七間町商店街振興組合」/ copyright「七間町町内会」の3表記混在。**Google に3種類の事業者名を送っている状態で MEO 上いちばん実害が大きい**。町内会側への確認が必要
- `schema_organization()` の `sameAs` に**未検証の Facebook URL がハードコード**（`https://www.facebook.com/shichikencho/`）。実在確認できないなら削除
- `schema_article` の `publisher.logo` 欠落 / `schema_event` の price がフリーテキスト / `schema_property` の `offers: null`
- `seo.php` の `sc_seo_fill_missing_description()` が**未フックの死にコード**。AIOSEO 無効時は description 二重出力
- 曜日不明で生テキストに退避した3件（1256/1257/1258）の `openingHours` は schema.org 想定形式でないため Rich Results Test で警告が出る可能性。虚偽よりマシという判断だがキー自体を落とす選択もあり
- 深夜跨ぎ（`closes: "00:00"`）を Google が翌日扱いと解釈するか実機の Rich Results Test で未確認
- ローマ字表記ゆれ: blogname「SHICHIKENCHO」/ プロトタイプ「SHICHIKANCHO」/ Instagram「shichikencho」— **ブランド判断待ち**
- 静的 `llms.txt` と `inc/seo-llmo.php` の動的エンドポイントで内容が重複。整理するか要判断
- **「関連リンク」が `/links/`(1169) と `/links-2/`(1170) で重複公開**。内容も description も同一。統合 or 非公開化はユーザー判断待ち（削除は不可逆のため未実行）
- ページ別**タイトル**（`#post_title #separator_sa #site_title` の既定のまま）と **OGP 画像**は未着手。description のみ全件投入済み
- resident の本文がシードのダミーのまま8件同一。実インタビュー投入後に AIOSEO 側フォーマットを `#post_excerpt` に戻す
- **クライアント確認待ち**: 実店舗41件の定休日・価格帯 / 商店街の代表電話番号 / Instagram 以外の公式SNS URL（**すべて捏造しない方針。空のまま**）
- **本番移行時**: AIOSEO `organizationLogo` の `.local` URL 書換、http-auth 解除、robots/sitemap 再確認

### 散策コース / PDF 由来（2026-07-25 セッション由来）
- **未使用ファイルの処分判断**: `page-course-list.php` / `assets/scss/pages/_course-list.scss` / `main.scss` の `@use 'pages/course-list'` / 固定ページ ID 1346（draft）。`/walk/` 一本化なら削除
- **`page-explore.php`（Template Name: 町をめぐる）が未割当のまま**。`page-walk.php` と役割重複。統合 or 削除の判断
- **車前提コース4本が未投入**（美食②駿河湾／風景・癒し①②／体験①②）。入れるならスポット約20件の追加が要る。※お茶①「感動のお茶体験」は指示により投入済み
- **PDF 未登録のまま残したスポット**: 華陽院 / 二加番稲荷神社 / 静岡近代美術館 / PARCO / 弥次喜多銅像 / 竹千代像 / 静岡ホビースクエア（駿河区）
- **葵区だが area タームが無く大エリアに紐づかないスポット**: 茶町・茶町KINZABURO(土太夫町)・八千代 寿し鐵(八千代町)・静岡市歴史博物館(追手町)・駿府楽市/しずチカ茶店一茶(黒金町)・臨済寺(大岩町)・石部屋(弥勒)。**タームを足して5エリアに含めるかは要判断**（含めるとエリアページの掲載範囲が変わる）
- **`浅間通り商店街`(spot 61) が「青葉通り」タームのまま**でどのエリアにも解決しない。浅間通りは馬場町・宮ヶ崎町側なのでターム誤りの可能性
- **spot の重複整理**（AQ 参照）: 静岡浅間神社 62/1324・駿府城公園 58/駿府城跡 1313・カフェ・ド・七間 spot787/shop39。史跡として別ページに分けるのか統合かは編集判断
- **映画館めぐりコースが七間町商店街(786)を2回連続で参照**。データ入力ミスの可能性
- **青葉おでん街をコースに入れられない**（shop 登録・`walk_spots.ref` は spot 限定）。ref に shop を許可するか、spot として登録し直すかの判断
- **車前提コースが「散策コース」一覧に混在**（感動のお茶体験＝車80分）。移動手段での絞り込みか「車のコース」バッジがあると親切
- **`p-explore__course-card-meta` が `display:contents` のまま**（single-walk_course.php の関連コース）。dt が `u-sr-only` でアイコンを持たないため AU) の症状は出ないが、裸タグセレクタ違反としては残存

### サーバー運用・掲載情報（2026-10-06 時点）
- **サーバーの `/work/` を目視確認**（ベーシック認証でこちらからは開けない）。求人カード・モーダル・絞り込みの表示確認
- 先方へ連絡：WP 7.1.2 に上げたこと、news と求人の編集画面が変わったこと、ベーシック認証が `demo` / `pass` のままであること
- **wp-cron が動いていない**（ベーシック認証で弾かれる）。`DISABLE_WP_CRON` ＋ サーバー cron への切り替えを先方に提案するか判断
- AIOSEO 5.0 / Taxonomy Terms Order 2.0 のメジャー更新（先方の作業が落ち着いてから・構造化データの検証付き）
- クラシック固定のまま本文がブロック形式の CPT（resident / property / job / gallery_photo / coworking / spot）の扱いを決める
- **求人 ACF の並び替えをサーバーに反映**（F 参照）。テーマ更新後、サーバーの ACF 管理画面で同期するか `acf-import-group_job-reorder.json` をインポート。サーバー DB にも `group_job` の重複が無いか先に確認
- 求人 3件（ID 125 / 126 / 127）の会社名・職種・タグ等が未入力。正しい情報を入れるか下書きに戻すか判断（サーバー側で）
- **ACF フィールドの残骸がサイト全体に残存**（ローカル DB）：キー重複 44 / 親投稿なし 322（単純 JOIN の概算・内訳未調査）。求人以外のグループでも重複インポートが起きている可能性
- 求人：`job_website` は10件とも未入力、`validThrough`（応募締切）は JobPosting に 0/10
- 求人：PICK UP 設定（おすすめバッジ）が未登録。ページ送りは直書き削除で消えたまま（件数が増えたら実装）
- 避難所（page-safety.php 直書き）の実データ化。行政オープンデータ由来に差し替え
- page-living.php の仮データらしき施設名・イベント名（コワーキング七間町 / Library Lounge 葵 / Tech Meetup Shizuoka 等）の実在確認
- ローカルの旧 `wp_` テーブル36個の削除（`siwn_` へ移行済み・動作確認後に）
- `0fd20d9` に開始前からの未コミット変更（inc/helpers.php・inc/schema.php）が混在。分割するか判断

### その他
- **Places インポート56件の精査**（`_sc_places_import`=2026-07-24 で抽出可）：非加盟店の掲載可否（コンコルド/マクドナルド/駅前チェーン等）、業態推定 desc 5件の実態確認、駿河屋の shop_category「食べる」→「買う」修正
- 4エリア（tokiwa/gofuku/takajo/baba）の **area_history / area_course が未登録**。資料をもらってから投入
- エリア詳細・町の紹介の画像サブフィールドが全町空。ラベルカードの冒頭文・紹介文はユーザーの文言レビュー待ち
- main.js の `.js-area-acc`（SPアコーディオン）が死にコード。SPピル重ねレイアウト確定なら削除
- スポンサー/メディアロゴが no-image のまま（front-page の仮データ）
- **DB投入分は全て git 外**（Q〜V の contact本文/ACF値/site_icon/CF7メッセージ/同期パターン1266/area_linked_terms + Z〜AC の spot/shop 56件/概要文/area_towns/intro）。本番は別途移行
- likes 機能はデッドコード。削除するか判断（inc/likes.php + main.js 550-585 + _gallery/_walk.scss の like）
- スポット3件のURLスラッグが日本語（%エンコード）。英字化するか要判断（公開直後の今が安全）
- 旧 area ターム `青葉通り`(12) がどのエリアにも未割当。削除 or 割当
- tokiwa 等 ②〜⑤エリアは spot/shop/event のタグ付けが薄く各セクション空。先方のタグ付け作業待ち
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

- **NAP（名称・住所・電話）を絶対に捏造しない**。架空の電話番号・住所・SNS URL は MEO 上の実害になる。裏が取れない項目は**キーごと出力しない**のが正。schema.php は空値を落とす設計になっている
- **構造化データは「出さない」より「嘘を出す」方が悪い**。営業時間パーサ（`sc_shop_hours_specs()`）は曜日対応が確定できない表記を意図的に構造化しない。この判断を「取りこぼし」と誤読して緩めないこと
- **JSON-LD はテーマ側と AIOSEO の二系統がある**。WebSite/Organization/Breadcrumb/WebPage は AIOSEO（`header.php` で `defined('AIOSEO_VERSION')` により委譲）、shop/spot/event/article/front-page の LocalBusiness はテーマ側（`inc/schema.php`）。AIOSEO 有料版でも shop CPT には LocalBusiness を出せないのでこの分担は変えられない
- **`wp_options.aioseo_options` を書き換えるときは PHP mysqli + `mysqli_set_charset('utf8mb4')`**。mysql バッチモードにパイプすると JSON が壊れる。書く前に必ずバックアップ
- **AIOSEO への投入は生 SQL でなく AIOSEO の API を使う**。ページ別は `\AIOSEO\Plugin\Common\Models\Post::getPost($id)` → `description` 代入 → `save()`、設定系は `aioseo()->dynamicOptions->...` → `save(true)`。管理画面と同じ保存先になり、UI からも編集できる
- **SEO メタは管理画面（AIOSEO）が正**。定型文をテーマ PHP に足さない。テーマ側フォールバック（`seo.php`）は AIOSEO 無効時の保険として早期 return 構造を維持する
- **「マップが出ない」系はテンプレより先に参照先の座標を疑う**。散策コースのルートマップは条件が `count($map_points) >= 1` なので、座標が1件でもあればセクションは描画され「実装済みに見える」。ルート線は2点以上必要（`walkmap.js`）。同じ構造は front-page のマップ・shop/spot 詳細にもある
- **座標を Google から補完するときは `formatted_address` に「葵区」が入っているか必ず確認**。名称検索は同名の別店舗・本社をよく引く（田丸屋本店は3候補あった）。採用したら `_sc_place_id` / `_sc_geo_source` を残して出典を辿れるようにする
- **所要時間を出すときは移動手段も出す**（`sc_walk_duration_label()`）。徒歩と自転車が混在するデータなので分数だけでは誤読される
- **観光パンフ PDF は見開き1ページ＝印刷2ページ**。印刷ページ番号で指示されたら `(印刷番号+2)/2` で PDF ページに変換。テキスト抽出はレイアウトが崩れるので、コース図・地図は画像で読む方が確実
- **新規に一覧ページを作る前に既存ページを確認する**。`/walk/` に絞り込み付きコース一覧があるのを見落として `/courses/` を二重に作った（AR）。CPT アーカイブの有無だけ見ると「一覧が無い」と誤判断する
- **「双方向にしたい」は管理画面の話かフロントの話か先に確認する**。フロントの相互リンク → カスタムメタボックス → ACF 双方向フィールド、と3回作り直した（AV）。結論は「元からできていた」
- **ACF リピーターのサブフィールドは逆引きも双方向設定もできない**。逆引きは `$wpdb` で `meta_key LIKE 'walk_spots_%_ref'`、双方向はトップレベルの関連フィールドを別途作るしかない
- **管理画面に出す HTML でもインライン `style` を書かない**。警告表示は WP コアの `notice notice-warning inline` を使う
- **firecrawl は 404 ページでも JSON 抽出が「それらしい値」を返す**。`statusCode` を必ず確認。ベンダーの pricing ページより docs の方が正確
- **ローカルにページキャッシュあり**。テンプレ/CSS を変えても旧HTMLが出ることがある → 確認は `?nocache=1` 等のクエリ付与かスーパーリロードでバスト（enqueue の filemtime キャッシュバストとは別レイヤー）
- **`assets/css/main.css` は gitignore 対象**（コンパイル済みは非追跡）。SCSS 変更後は `npx sass ...` で再コンパイルしてローカル反映、コミットは SCSS ソースのみ。デプロイ先ではビルドが要る
- **アンカースクロールは `scroll-behavior: smooth` / `scrollIntoView` 禁止**。Google 翻訳の `html{height:100%}` と干渉して smooth が不発になる。`main.js` の `scrollToHash()` のように scroll-behavior を一時 auto に上書きして `window.scrollTo` で instant 実行すること
- **CF7 標準 CSS が未読込**（`cf7CssLoaded: false`）。そのため CF7 が SR 用に出す `.screen-reader-response` が視覚表示される。新規 CF7 フォームを置くページでは SR 専用クリップを当てること
- **CF7 はカード/li 自体に `slick-slide`/`slick-center` を付与**（ラップ div を作らない）。dim 等は子孫セレクタでなく要素自身に当てる
- slick 生成要素・CF7 出力・wp-block などプラグイン出力は class 付与不可 → 要素セレクタ使用可（`CLAUDE.local.md` の裸タグ禁止の例外）
- **SP slick は実機スワイプ未目視**（`resize_window` が実ビューポートに効かず SP 幅に絞れない・innerWidth が縮まない）。PC 幅は確認済み。DevTools デバイスモードで最終確認推奨
- **`js-center-slider` は living / photo-contest / shop・event アーカイブ PICK UP で使用**。PC もスライダー化したい箇所は `is-pc-slider`（+ `data-pc-slides`）を付与、操作バーは空 `<div class="c-slider-nav js-center-slider-nav">` を隣接配置（§1-N）
- **grid/flex 内に slick を置くときは親カラムに `min-width:0`**（無いと slick トラック幅でカラムが暴走膨張）。slick 対象要素自体は `display:block`（grid 残存で潰れる）
- **AIM でローカル → サーバーのインポートは禁止**。サーバーが正。デプロイは親テーマのファイルを rsync（`_seed-*.php` / `acf-import/` は除外）
- **子テーマが親の `assets/js/main.js` を丸ごと差し替えている**（functions.php で登録済みスクリプトの src を書き換え・親と1875行差分）。**親テーマの JS 修正は画面に出ない**。JS を直すときは両方に入れる
- **ACF の定義はローカル JSON が優先**。DB だけ変えても反映されない。変更は `acf_import_field_group()` / `acf_import_internal_post_type()`（管理画面のインポートと同じ経路）で入れると acf-json も自動同期される
- **ACF のテキストエリアは改行を `<br>` に変換して保存**。1行1項目で分割するときは改行と `<br>` の両方で split する
- サーバーの wp-cli は未導入 → `~/wp-cli.phar` を設置済み。`php ~/wp-cli.phar --skip-plugins=http-auth` で実行（http-auth が CLI も弾く）
- **Excel 取込は `title` 完全一致**。店名の表記が1文字変わると別投稿として新規作成される → dry-run レポートの「新規」を毎回確認
- **AIM インポートは DB 丸ごと上書き**。テストサーバー管理画面での編集は運用上禁止（次のインポートで消える）。Basic 認証の ID/PW もローカル値で上書きされる
- ACF / CPT / CF7 のハードコード禁止ルール（`CLAUDE.local.md`）厳守。フォーム定義は管理画面 or DB seed で
