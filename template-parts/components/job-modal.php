<?php
/** 求人の詳細モーダル（町で働くページ） */
$job_id    = get_the_ID();
$job_types = sc_job_type_labels( $job_id );
$job_info  = [
	'給与'     => (string) get_field( 'job_salary', $job_id ),
	'勤務地'   => (string) get_field( 'job_location', $job_id ),
	'勤務時間' => (string) get_field( 'job_hours', $job_id ),
	'休日'     => (string) get_field( 'job_holiday', $job_id ),
	'職種'     => (string) get_field( 'job_position', $job_id ),
];
$job_info  = array_filter( $job_info, fn( $v ) => '' !== trim( $v ) );
// 応募条件・待遇は1行1項目で入力。ACF 側で改行が <br> に変換されるため両方で分割する
$job_split   = fn( $value ) => array_values( array_filter( array_map(
	fn( $line ) => trim( wp_strip_all_tags( $line ) ),
	preg_split( '#<br\s*/?>|\r\n|\r|\n#i', (string) $value )
) ) );
$job_reqs    = $job_split( get_field( 'job_requirements', $job_id ) );
$job_welfare = $job_split( get_field( 'job_benefits', $job_id ) );
$job_site    = sc_field_url( 'job_website', $job_id );
$job_limit   = (string) get_field( 'job_deadline', $job_id );
?>
<div class="c-modal" id="job-modal-<?php echo esc_attr( $job_id ); ?>" hidden aria-hidden="true">
	<div class="c-modal__overlay" data-close></div>
	<div class="c-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="job-modal-<?php echo esc_attr( $job_id ); ?>-title">
		<button type="button" class="c-modal__close" data-close aria-label="閉じる">
			<svg aria-hidden="true" focusable="false"><use href="#icon-close"></use></svg>
		</button>
		<div class="c-modal__head">
			<span class="c-modal__type"><?php echo esc_html( implode( '・', $job_types ) ); ?></span>
			<h2 class="c-modal__title" id="job-modal-<?php echo esc_attr( $job_id ); ?>-title"><?php the_title(); ?></h2>
			<?php if ( get_field( 'job_company', $job_id ) ) : ?>
				<p class="c-modal__subtitle">
					<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-store"></use></svg>
					<?php echo sc_field( 'job_company', $job_id ); ?>
				</p>
			<?php endif; ?>
		</div>
		<!-- /.c-modal__head -->
		<div class="c-modal__content">
			<?php if ( get_field( 'job_description', $job_id ) ) : ?>
				<div class="c-modal__section">
					<h3 class="c-modal__section-title">仕事内容</h3>
					<p class="c-modal__section-text"><?php echo nl2br( esc_html( wp_strip_all_tags( (string) get_field( 'job_description', $job_id ) ) ) ); ?></p>
				</div>
				<!-- /.c-modal__section -->
			<?php endif; ?>
			<?php if ( $job_info ) : ?>
				<div class="c-modal__section">
					<div class="c-modal__info-grid">
						<?php foreach ( $job_info as $job_label => $job_value ) : ?>
							<div class="c-modal__info-box">
								<p class="c-modal__info-label"><?php echo esc_html( $job_label ); ?></p>
								<p class="c-modal__info-value"><?php echo esc_html( $job_value ); ?></p>
							</div>
							<!-- /.c-modal__info-box -->
						<?php endforeach; ?>
					</div>
					<!-- /.c-modal__info-grid -->
				</div>
				<!-- /.c-modal__section -->
			<?php endif; ?>
			<?php if ( $job_reqs ) : ?>
				<div class="c-modal__section">
					<h3 class="c-modal__section-title">応募条件</h3>
					<ul class="c-modal__check-list">
						<?php foreach ( $job_reqs as $job_req ) : ?>
							<li><?php echo esc_html( $job_req ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<!-- /.c-modal__section -->
			<?php endif; ?>
			<?php if ( $job_welfare ) : ?>
				<div class="c-modal__section">
					<h3 class="c-modal__section-title">待遇・福利厚生</h3>
					<ul class="c-modal__chip-list">
						<?php foreach ( $job_welfare as $job_item ) : ?>
							<li><span class="c-tag c-tag--success"><?php echo esc_html( $job_item ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<!-- /.c-modal__section -->
			<?php endif; ?>
			<div class="c-modal__meta-line">
				<span>
					<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>
					掲載: <?php echo esc_html( get_the_date( 'Y/m/d' ) ); ?>
				</span>
				<?php if ( $job_limit ) : ?>
					<span>
						<svg aria-hidden="true" focusable="false" width="14" height="14"><use href="#icon-tag"></use></svg>
						締切: <?php echo esc_html( $job_limit ); ?>
					</span>
				<?php endif; ?>
			</div>
			<!-- /.c-modal__meta-line -->
		</div>
		<!-- /.c-modal__content -->
		<div class="c-modal__actions">
			<a class="c-btn c-btn--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-mail"></use></svg>
				この求人に問い合わせる
			</a>
			<?php if ( $job_site ) : ?>
				<a class="c-btn c-btn--outline" href="<?php echo $job_site; ?>" target="_blank" rel="noopener noreferrer">
					<svg class="c-btn__icon" aria-hidden="true" focusable="false"><use href="#icon-external"></use></svg>
					企業サイトで詳細を見る
				</a>
			<?php endif; ?>
		</div>
		<!-- /.c-modal__actions -->
	</div>
	<!-- /.c-modal__dialog -->
</div>
<!-- /.c-modal -->
