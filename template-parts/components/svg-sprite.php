<?php
// インライン SVG スプライト（body 直後に配置）
// 新規アイコン追加はここに <symbol> を追加する
?>
<svg class="u-hidden" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

	<!-- ナビアイコン：町の紹介 -->
	<symbol id="icon-house" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
		<polyline points="9 22 9 12 15 12 15 22"/>
	</symbol>

	<!-- ナビアイコン：映画の町 -->
	<symbol id="icon-film" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/>
		<line x1="7" y1="2" x2="7" y2="22"/>
		<line x1="17" y1="2" x2="17" y2="22"/>
		<line x1="2" y1="12" x2="22" y2="12"/>
		<line x1="2" y1="7" x2="7" y2="7"/>
		<line x1="2" y1="17" x2="7" y2="17"/>
		<line x1="17" y1="17" x2="22" y2="17"/>
		<line x1="17" y1="7" x2="22" y2="7"/>
	</symbol>

	<!-- ナビアイコン：町をめぐる -->
	<symbol id="icon-map-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
		<circle cx="12" cy="10" r="3"/>
	</symbol>

	<!-- ナビアイコン：町に住む -->
	<symbol id="icon-person" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
		<circle cx="12" cy="7" r="4"/>
	</symbol>

	<!-- ナビアイコン：町で学ぶ -->
	<symbol id="icon-hat" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
		<path d="M6 12v5c3 3 9 3 12 0v-5"/>
	</symbol>

	<!-- ナビアイコン：町で働く -->
	<symbol id="icon-briefcase" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
		<path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
	</symbol>

	<!-- ナビアイコン：町のギャラリー -->
	<symbol id="icon-camera" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
		<circle cx="12" cy="13" r="4"/>
	</symbol>

	<!-- ナビアイコン：くらしガイド -->
	<symbol id="icon-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
		<path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
	</symbol>

	<!-- ナビアイコン：いのちを守る -->
	<symbol id="icon-shield" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
	</symbol>

	<!-- 共通：検索 -->
	<symbol id="icon-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="11" cy="11" r="8"/>
		<line x1="21" y1="21" x2="16.65" y2="16.65"/>
	</symbol>

	<!-- 共通：閉じる -->
	<symbol id="icon-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<line x1="18" y1="6" x2="6" y2="18"/>
		<line x1="6" y1="6" x2="18" y2="18"/>
	</symbol>

	<!-- 共通：chevron-right -->
	<symbol id="icon-chevron-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<polyline points="9 18 15 12 9 6"/>
	</symbol>

	<!-- 共通：chevron-left -->
	<symbol id="icon-chevron-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<polyline points="15 18 9 12 15 6"/>
	</symbol>

	<!-- 共通：clock -->
	<symbol id="icon-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="12" cy="12" r="9"/>
		<polyline points="12 7 12 12 16 14"/>
	</symbol>

	<!-- 共通：calendar（日付） -->
	<symbol id="icon-calendar" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M8 2v4"/>
		<path d="M16 2v4"/>
		<rect width="18" height="18" x="3" y="4" rx="2"/>
		<path d="M3 10h18"/>
	</symbol>

	<!-- 共通：ruler（距離） -->
	<symbol id="icon-ruler" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M3 17 17 3l4 4L7 21z"/>
		<line x1="7" y1="13" x2="9" y2="15"/>
		<line x1="10" y1="10" x2="12" y2="12"/>
		<line x1="13" y1="7" x2="15" y2="9"/>
	</symbol>

	<!-- 共通：share -->
	<symbol id="icon-share" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="18" cy="5" r="3"/>
		<circle cx="6" cy="12" r="3"/>
		<circle cx="18" cy="19" r="3"/>
		<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
		<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
	</symbol>

	<!-- 共通：外部リンク -->
	<symbol id="icon-external" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
		<polyline points="15 3 21 3 21 9"/>
		<line x1="10" y1="14" x2="21" y2="3"/>
	</symbol>

	<!-- 共通：電話 -->
	<symbol id="icon-phone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
	</symbol>

	<!-- 共通：メール -->
	<symbol id="icon-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
		<polyline points="22,6 12,13 2,6"/>
	</symbol>

	<!-- SNS: Instagram -->
	<symbol id="icon-instagram" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
		<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
		<line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
	</symbol>

	<!-- SNS: X -->
	<symbol id="icon-x" viewBox="0 0 24 24" fill="currentColor">
		<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
	</symbol>
	<!-- SNS: LINE -->
	<symbol id="icon-line" viewBox="0 0 24 24" fill="currentColor">
		<path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
	</symbol>
	<!-- SNS: YouTube -->
	<symbol id="icon-youtube" viewBox="0 0 24 24" fill="currentColor">
		<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
	</symbol>
	<!-- SNS: Facebook -->
	<symbol id="icon-facebook" viewBox="0 0 24 24" fill="currentColor">
		<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
	</symbol>
	<!-- カテゴリー: 町で商い -->
	<symbol id="icon-store" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/>
		<path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
		<path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/>
		<path d="M2 7h20"/>
		<path d="M22 7v3a2 2 0 0 1-2 2a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"/>
	</symbol>
	<!-- SNS: Pinterest -->
	<symbol id="icon-pinterest" viewBox="0 0 24 24" fill="currentColor">
		<path d="M12 0C5.373 0 0 5.373 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 0 1 .083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/>
	</symbol>

	<!-- Heroicons Solid: users（人口） -->
	<symbol id="icon-users-solid" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" clip-rule="evenodd"/>
	</symbol>

	<!-- Heroicons Solid: home（世帯数） -->
	<symbol id="icon-home-solid" viewBox="0 0 24 24" fill="currentColor">
		<path d="M11.47 3.84a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.06l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 0 0 1.061 1.06l8.69-8.69Z"/>
		<path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z"/>
	</symbol>

	<!-- Heroicons Solid: bolt（新幹線） -->
	<symbol id="icon-bolt-solid" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M14.615 1.595a.75.75 0 0 1 .359.852L12.982 9.75h7.268a.75.75 0 0 1 .548 1.262l-10.5 11.25a.75.75 0 0 1-1.272-.71l1.992-7.302H3.75a.75.75 0 0 1-.548-1.262l10.5-11.25a.75.75 0 0 1 .913-.143Z" clip-rule="evenodd"/>
	</symbol>

	<!-- Heroicons Solid: map-pin（駅・場所） -->
	<symbol id="icon-map-pin-solid" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742Zm0-12.04a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" clip-rule="evenodd"/>
	</symbol>

	<!-- Heroicons Solid: heart（お気に入り） -->
	<symbol id="icon-heart-solid" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" clip-rule="evenodd"/>
	</symbol>

	<!-- Heroicons Solid: heart-outline（お気に入り未設定） -->
	<symbol id="icon-heart-outline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
		<path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
	</symbol>

	<!-- Heroicons Solid: x-mark（閉じるボタン） -->
	<symbol id="icon-x-mark" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
	</symbol>

	<!-- /living で使う特徴アイコン群（lucide ベース、line: 1.6） -->
	<symbol id="icon-school" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M22 10v6"/><path d="M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
	</symbol>

	<symbol id="icon-park" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M12 2L8 8h2v4H6l6 8 6-8h-4V8h2z"/>
	</symbol>

	<symbol id="icon-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>
	</symbol>

	<symbol id="icon-people" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
		<circle cx="9" cy="7" r="4"/>
		<path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
		<path d="M16 3.13a4 4 0 0 1 0 7.75"/>
	</symbol>

	<symbol id="icon-medical" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<rect x="3" y="3" width="18" height="18" rx="2"/>
		<path d="M12 7v10"/><path d="M7 12h10"/>
	</symbol>

	<symbol id="icon-train" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<rect x="4" y="3" width="16" height="16" rx="2"/>
		<path d="M4 11h16"/>
		<circle cx="8.5" cy="15" r="1"/>
		<circle cx="15.5" cy="15" r="1"/>
		<path d="M8 19l-2 3"/>
		<path d="M16 19l2 3"/>
	</symbol>

	<symbol id="icon-desk" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<rect x="2" y="3" width="20" height="14" rx="2"/>
		<path d="M8 21h8"/><path d="M12 17v4"/>
	</symbol>

	<symbol id="icon-cafe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M17 8h1a4 4 0 1 1 0 8h-1"/>
		<path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4z"/>
		<line x1="6" y1="2" x2="6" y2="4"/>
		<line x1="10" y1="2" x2="10" y2="4"/>
		<line x1="14" y1="2" x2="14" y2="4"/>
	</symbol>

	<symbol id="icon-culture" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M3 21h18"/>
		<path d="M3 10h18"/>
		<path d="M5 6l7-4 7 4"/>
		<path d="M5 21V10"/>
		<path d="M19 21V10"/>
		<path d="M9 21v-7"/>
		<path d="M15 21v-7"/>
	</symbol>

	<!-- lucide star（fill 反映） -->
	<symbol id="icon-star" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
	</symbol>

	<!-- lucide sparkles -->
	<symbol id="icon-sparkles" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>
		<path d="M20 3v4"/><path d="M22 5h-4"/>
		<path d="M4 17v2"/><path d="M5 18H3"/>
	</symbol>

	<!-- lucide yen ($-like) -->
	<symbol id="icon-yen" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M5 3l7 9 7-9"/><path d="M5 13h14"/><path d="M5 17h14"/><path d="M12 12v9"/>
	</symbol>

	<!-- Heroicons solid mountain（富士山相当） -->
	<symbol id="icon-mountain" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M11.378 4.066a.75.75 0 0 1 1.244 0l8.25 12a.75.75 0 0 1-.622 1.184H3.75a.75.75 0 0 1-.622-1.184l8.25-12Zm.622 2.486L5.343 15.75H8.69l1.55-2.325a.75.75 0 0 1 1.137-.13l1.83 1.83 2.34-3.51-3.547-5.063Z" clip-rule="evenodd"/>
	</symbol>

	<!-- Heroicons solid building-library（寺社・歴史的建造物相当） -->
	<symbol id="icon-building-library" viewBox="0 0 24 24" fill="currentColor">
		<path d="M11.584 2.376a.75.75 0 0 1 .832 0l9 6a.75.75 0 1 1-.832 1.248L12 3.901 3.416 9.624a.75.75 0 0 1-.832-1.248l9-6Z"/>
		<path fill-rule="evenodd" d="M20.25 10.332v9.918H21a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1 0-1.5h.75v-9.918a.75.75 0 0 1 .634-.74A49.109 49.109 0 0 1 12 9c2.59 0 5.134.202 7.616.592a.75.75 0 0 1 .634.74Zm-7.5 2.418a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Zm3-.75a.75.75 0 0 1 .75.75v6.75a.75.75 0 0 1-1.5 0v-6.75a.75.75 0 0 1 .75-.75ZM9 12.75a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Z" clip-rule="evenodd"/>
		<path d="M12 7.875a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z"/>
	</symbol>

	<!-- Heroicons solid home-modern（街並み相当） -->
	<symbol id="icon-house-modern" viewBox="0 0 24 24" fill="currentColor">
		<path d="M19.006 3.705a.75.75 0 1 0-.512-1.41L6 6.838V3a.75.75 0 0 0-.75-.75h-1.5A.75.75 0 0 0 3 3v4.93l-1.006.365a.75.75 0 0 0 .512 1.41l16.5-6Z"/>
		<path fill-rule="evenodd" d="M3.019 11.114 18 5.667v3.421l4.006 1.457a.75.75 0 1 1-.512 1.41l-.494-.18v8.475h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3v-9.129l.019-.007ZM18 20.25v-9.566l1.5.546v9.02H18Zm-9-6a.75.75 0 0 0-.75.75v4.5c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75V15a.75.75 0 0 0-.75-.75H9Z" clip-rule="evenodd"/>
	</symbol>

	<!-- Heroicons solid cake（グルメ相当） -->
	<symbol id="icon-cake" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M16.685 2.314a.75.75 0 0 1 .23 1.034l-1.752 2.776A4.486 4.486 0 0 1 18 9.75c0 .918-.275 1.772-.747 2.484a.75.75 0 1 1-1.249-.832A2.985 2.985 0 0 0 16.5 9.75a3 3 0 0 0-2.25-2.905v.218c0 .314-.026.624-.075.926a.75.75 0 0 1-1.481-.241c.037-.226.056-.46.056-.685V6.475A3 3 0 0 0 7.5 8.25c0 1.005.5 1.913 1.301 2.475a.75.75 0 1 1-.86 1.228A4.486 4.486 0 0 1 6 8.25a4.5 4.5 0 0 1 4.5-4.5h.027c.273 0 .526-.131.685-.351l1.293-1.776a.75.75 0 0 1 1.034-.23l3.146 1.921ZM3 14.25v3.75a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3v-3.75a3 3 0 0 0-3-3H6a3 3 0 0 0-3 3Z" clip-rule="evenodd"/>
	</symbol>

	<!-- Heroicons solid fire -->
	<symbol id="icon-fire" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.715.75.75 0 0 0-1.152-.082A9 9 0 1 0 15.68 4.534a7.46 7.46 0 0 1-2.717-2.248ZM15.75 14.25a3.75 3.75 0 1 1-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 0 1 1.925-3.546 3.75 3.75 0 0 1 3.255 3.718Z" clip-rule="evenodd"/>
	</symbol>

	<!-- lucide tag -->
	<symbol id="icon-tag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
		<line x1="7" y1="7" x2="7.01" y2="7"/>
	</symbol>

	<!-- lucide wifi -->
	<symbol id="icon-wifi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M5 13a10 10 0 0 1 14 0"/>
		<path d="M8.5 16.5a5 5 0 0 1 7 0"/>
		<path d="M2 8.82a15 15 0 0 1 20 0"/>
		<line x1="12" y1="20" x2="12.01" y2="20"/>
	</symbol>

	<!-- lucide building（建物） -->
	<symbol id="icon-building" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<rect x="4" y="2" width="16" height="20" rx="2"/>
		<path d="M9 22v-4h6v4"/>
		<path d="M8 6h.01"/><path d="M16 6h.01"/>
		<path d="M12 6h.01"/><path d="M12 10h.01"/>
		<path d="M12 14h.01"/><path d="M16 10h.01"/>
		<path d="M16 14h.01"/><path d="M8 10h.01"/>
		<path d="M8 14h.01"/>
	</symbol>

	<symbol id="icon-bus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M8 6v6"/><path d="M15 6v6"/>
		<path d="M2 12h19.6"/>
		<path d="M18 18h3s.5-1.7.8-4.3c.3-2.7.2-3.7.2-3.7H2S2 11 2.2 13.7C2.5 16.3 3 18 3 18h3"/>
		<circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/>
	</symbol>

	<symbol id="icon-car" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v9a2 2 0 0 1-2 2h-2"/>
		<circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/>
	</symbol>

	<symbol id="icon-bicycle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="18.5" cy="17.5" r="3.5"/><circle cx="5.5" cy="17.5" r="3.5"/>
		<circle cx="15" cy="5" r="1"/>
		<path d="M12 17.5V14l-3-3 4-3 2 3h2"/>
	</symbol>

	<symbol id="icon-parking" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
		<rect x="3" y="3" width="18" height="18" rx="2"/>
		<path d="M9 17V7h4a3 3 0 0 1 0 6H9"/>
	</symbol>

	<!-- 観光スポット spot_type フィルター用 -->
	<symbol id="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
		<circle cx="12" cy="12" r="3"/>
	</symbol>

	<symbol id="icon-shopping-bag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
		<path d="M3 6h18"/>
		<path d="M16 10a4 4 0 0 1-8 0"/>
	</symbol>

	<symbol id="icon-utensils" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/>
		<path d="M7 2v20"/>
		<path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>
	</symbol>

	<symbol id="icon-bed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<path d="M2 4v16"/>
		<path d="M2 8h18a2 2 0 0 1 2 2v10"/>
		<path d="M2 17h20"/>
		<path d="M6 8v9"/>
	</symbol>

	<symbol id="icon-graduation-cap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/>
		<path d="M22 10v6"/>
		<path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
	</symbol>

	<symbol id="icon-ellipsis" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<circle cx="12" cy="12" r="1"/>
		<circle cx="19" cy="12" r="1"/>
		<circle cx="5" cy="12" r="1"/>
	</symbol>

	<symbol id="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
		<polyline points="20 6 9 17 4 12"/>
	</symbol>

	<symbol id="icon-squares-2x2-solid" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z" clip-rule="evenodd"/>
	</symbol>

	<symbol id="icon-sun-solid" viewBox="0 0 24 24" fill="currentColor">
		<path d="M12 2.25a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75ZM7.5 12a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM18.894 6.166a.75.75 0 0 0-1.06-1.06l-1.591 1.59a.75.75 0 1 0 1.06 1.061l1.591-1.59ZM21.75 12a.75.75 0 0 1-.75.75h-2.25a.75.75 0 0 1 0-1.5H21a.75.75 0 0 1 .75.75ZM17.834 18.894a.75.75 0 0 0 1.06-1.06l-1.59-1.591a.75.75 0 1 0-1.061 1.06l1.59 1.591ZM12 18a.75.75 0 0 1 .75.75V21a.75.75 0 0 1-1.5 0v-2.25A.75.75 0 0 1 12 18ZM7.166 17.834a.75.75 0 0 0-1.06 1.06l1.59 1.591a.75.75 0 1 0 1.061-1.06l-1.59-1.591ZM6 12a.75.75 0 0 1-.75.75H3a.75.75 0 0 1 0-1.5h2.25A.75.75 0 0 1 6 12ZM6.166 6.166a.75.75 0 0 0 1.06 1.06l1.591-1.59a.75.75 0 1 0-1.061-1.061l-1.59 1.591Z"/>
	</symbol>

	<symbol id="icon-clock-solid" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd"/>
	</symbol>

	<symbol id="icon-chevron-down" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
		<polyline points="6 9 12 15 18 9"/>
	</symbol>

	<!-- 城（天守閣シルエット: 屋根＋本体＋胸壁） -->
	<symbol id="icon-castle" viewBox="0 0 24 24" fill="currentColor">
		<path d="M12 2 8 5h8l-4-3zm-1 4v3H7V6H5v3H3v2h18V9h-2V6h-2v3h-4V6h-2zm-7 7v7h3v-4h2v4h2v-4h2v4h2v-4h2v4h3v-7H4z"/>
	</symbol>

	<!-- 茶碗にご飯・箸（碗＋ご飯ふくらみ＋上に箸2本） -->
	<symbol id="icon-rice-bowl" viewBox="0 0 24 24" fill="currentColor">
		<path d="M14.5 2.2 6.7 8.4l.9 1.1 7.8-6.2-.9-1.1zm3 1.6L9.7 10l.9 1.1 7.8-6.2-.9-1.1zM3 12c0 .4.1.8.2 1.2l.4-.1c1.5 4.6 4.7 7 8.4 7s6.9-2.4 8.4-7l.4.1c.1-.4.2-.8.2-1.2H3zm-.5-1.5h19c.3 0 .5.2.5.5s-.2.5-.5.5h-19c-.3 0-.5-.2-.5-.5s.2-.5.5-.5z"/>
	</symbol>

	<!-- 木（広葉樹: 葉の塊＋幹） -->
	<symbol id="icon-tree" viewBox="0 0 24 24" fill="currentColor">
		<path d="M12 2C8.7 2 6 4.7 6 8c0 .7.1 1.4.4 2-1.4.6-2.4 2-2.4 3.7 0 2.1 1.7 3.8 3.8 3.8H11v5h2v-5h3.3c2.1 0 3.7-1.7 3.7-3.8 0-1.7-1-3.1-2.4-3.7.3-.6.4-1.3.4-2 0-3.3-2.7-6-6-6z"/>
	</symbol>

	<!-- 桜（5枚花弁＋中心の蕊） -->
	<symbol id="icon-flower" viewBox="0 0 24 24" fill="currentColor">
		<path d="M12 2c1.7 0 3 1.6 3 3.5 0 .6-.2 1.2-.5 1.7l1.2-.1c1.7-.2 3.3 1.2 3.5 3 .2 1.8-1.2 3.4-2.9 3.6L15 14l.8.9c1.1 1.4.9 3.4-.5 4.5-1.3 1.1-3.3.9-4.4-.5l-.9-1.1-.9 1.1c-1.1 1.4-3.1 1.6-4.4.5s-1.6-3.1-.5-4.5L5 14l-1.3-.3c-1.7-.2-3.1-1.8-2.9-3.6.2-1.8 1.8-3.2 3.5-3l1.2.1c-.3-.5-.5-1.1-.5-1.7C5 3.6 6.3 2 8 2c1 0 1.9.6 2.5 1.4l1.5 2 1.5-2C13.1 2.6 14 2 12 2zm0 7.5c-1.4 0-2.5 1.1-2.5 2.5s1.1 2.5 2.5 2.5 2.5-1.1 2.5-2.5-1.1-2.5-2.5-2.5z"/>
	</symbol>

	<!-- 共通：プラス（アコーディオン開閉） -->
	<symbol id="icon-plus" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
	</symbol>

	<!-- Heroicons solid trophy（入賞・受賞） -->
	<symbol id="icon-trophy" viewBox="0 0 24 24" fill="currentColor">
		<path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 0 0-.584.859 6.753 6.753 0 0 0 6.138 5.6 6.73 6.73 0 0 0 2.743 1.346A6.707 6.707 0 0 1 9.279 15H8.54c-1.036 0-1.875.84-1.875 1.875V19.5h-.75a2.25 2.25 0 0 0-2.25 2.25c0 .414.336.75.75.75h15.27a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-2.25-2.25h-.75v-2.625c0-1.036-.84-1.875-1.875-1.875h-.739a6.706 6.706 0 0 1-1.112-3.173 6.73 6.73 0 0 0 2.743-1.347 6.753 6.753 0 0 0 6.139-5.6.75.75 0 0 0-.585-.858 47.077 47.077 0 0 0-3.07-.543V2.62a.75.75 0 0 0-.658-.744 49.22 49.22 0 0 0-6.093-.377c-2.063 0-4.096.128-6.093.377a.75.75 0 0 0-.657.744Zm0 2.629c0 1.196.312 2.32.857 3.294A5.266 5.266 0 0 1 3.16 5.337a45.6 45.6 0 0 1 2.006-.343v.256Zm13.668 0v-.256c.674.1 1.343.214 2.006.343a5.265 5.265 0 0 1-2.863 3.207 6.72 6.72 0 0 0 .857-3.294Z" clip-rule="evenodd"/>
	</symbol>

</svg>
